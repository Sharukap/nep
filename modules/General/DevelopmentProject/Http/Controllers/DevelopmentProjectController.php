<?php

namespace DevelopmentProject\Http\Controllers;

use App\Models\Development_Project;
use App\Models\Land_Parcel;
use App\Models\Gazette;
use App\Models\Province;
use App\Models\District;
use App\Models\GS_Division;
use App\Models\Organization;
use App\Models\User;
use App\Models\Process_Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ApplicationMade;
use Illuminate\Support\Facades\DB;
use App\CustomClass\organization_assign;
use App\CustomClass\lanparcel_creation;

class DevelopmentProjectController extends Controller
{

    //Returns the view for the application form passing in data of lands, organziations and gazettes
    public function form()
    {
        $province = Province::all();
        $district = District::all();
        $gs = GS_Division::orderBy('gs_division')->get();
        $organizations = Organization::where('type_id', '=', '1')->get();
        $gazettes = Gazette::all();
        return view('developmentProject::form', [
            'provinces' => $province,
            'districts' => $district,
            'gs' => $gs,
            'organizations' => $organizations,
            'gazettes' => $gazettes,
        ]);
    }

    // Saves the form to the development projects table as well as creates 1 or more entries in the process items table
    // depenign on the number of governing organizations selected.
    public function save(Request $request)
    {
        if (Auth()->user()->role_id != 6) {
            if (request('checklandowner')) {
                $request->validate([
                    'title' => 'required',
                    'planNo' => 'required',
                    'surveyorName' => 'required',
                    'province' => 'required',
                    'district' => 'required',
                    'gs_division' => 'required',
                    'gazette' => 'required|exists:gazettes,gazette_number',
                    'polygon' => 'required',
                    'land_owner' => 'required',
                    'landownertype' => 'required|in:1,2',
                    'organization' => 'nullable|exists:organizations,title',
                ]);
            } else {
                $request->validate([
                    'title' => 'required',
                    'planNo' => 'required',
                    'surveyorName' => 'required',
                    'province' => 'required',
                    'district' => 'required',
                    'gs_division' => 'required',
                    'gazette' => 'required|exists:gazettes,gazette_number',
                    'polygon' => 'required',
                    'land_owner' => 'required|exists:organizations,title',
                    'organization' => 'nullable|exists:organizations,title',
                ]);
            }
        }
        if (Auth()->user()->role_id = 6) {
            $request->validate([
                'title' => 'required',
                'planNo' => 'required',
                'surveyorName' => 'required',
                'province' => 'required',
                'district' => 'required',
                'gs_division' => 'required',
                'gazette' => 'required|exists:gazettes,gazette_number',
                'polygon' => 'required',
                'organization' => 'nullable|exists:organizations,title',
            ]);
        }


        DB::transaction(function () use ($request) {
            $landid = lanparcel_creation::land_save($request);

            $dev = new Development_Project();
            $dev->title = request('title');
            //$dev->gazette_id = request('gazette');

            $gazette = Gazette::where('gazette_number', request('gazette'))->pluck('id');
            $dev->gazette_id = $gazette[0];

            $dev->governing_organizations = 1; //I've set this to defalt until we remove the column as it is redundant data

            $dev->land_parcel_id = $landid;
            $dev->created_by_user_id = request('createdBy');
            if (request('isProtected')) {
                $dev->protected_area = request('isProtected');
            }
            //saving the coordinates in string form. when giving back to the map it needs to be converted back into JSON in the script.
            $dev->save();

            //process item for the development project
            $latest = Development_Project::latest()->first();
            $process = new Process_Item();
            $process->form_type_id = 2;
            $process->form_id = $latest->id;
            $process->created_by_user_id = request('createdBy');
            $process->request_organization = Auth::user()->organization_id;

            if (Auth()->user()->role_id = 6) {
                $process->request_organization = 6;
            } else {
                if (request('checklandowner')) {
                    $process->other_land_owner_name = request('land_owner');
                    $process->other_land_owner_type = request('landownertype');
                } else {
                    $land_owner = Organization::where('title', request('land_owner'))->pluck('id');
                    $process->request_organization = $land_owner[0];
                }
            }
            if($request->filled('organization')){
                $organization = Organization::where('title', $request['organization'])->pluck('id');
                $Process_item->activity_organization = $organization[0];
            }
            $process->save();

            //process item for the land parcel
            $latestDevProcess = Process_Item::latest()->first();
            if(empty($request->input('organization'))){
                organization_assign::auto_assign($latestDevProcess->id,request('district'),request('province'));
                $latestDevProcess =Process_Item::latest()->first();
            }
            else{
                $Admins = User::where('organization_id',$latestDevProcess->activity_organization)->whereBetween('role_id', [1, 2])->get();
                Notification::send($Admins, new ApplicationMade($latestDevProcess));
            }

            $landProcess = new Process_Item();
            $landProcess->form_id = $landid;
            $landProcess->remark = "Verify these land details";
            $landProcess->prerequisite = 0;
            $landProcess->status_id = $latestDevProcess->status_id;
            $landProcess->form_type_id = 5;
            $landProcess->created_by_user_id = request('createdBy');
            $landProcess->prerequisite_id = $latestDevProcess->id;

            if (Auth()->user()->role_id = 6) {
                $landProcess->request_organization = 6;
            } else {
                if (request('checklandowner')) {
                    $landProcess->other_land_owner_name = request('land_owner');
                    $landProcess->other_land_owner_type = request('landownertype');
                } else {
                    $land_owner = Organization::where('title', request('land_owner'))->pluck('id');
                    $landProcess->request_organization = $land_owner[0];
                }
            }
            $landProcess->activity_organization = $latestDevProcess->activity_organization;
            $landProcess->save();
        });
        return redirect('/general/pending')->with('message', 'Request Created Successfully');
    }

    public function show($id)
    {
        $process_item = Process_Item::find($id);
        $development_project = Development_Project::find($process_item->form_id);
        $land_data = Land_Parcel::find($development_project->land_parcel_id);
        return view('developmentProject::show', [
            'development_project' => $development_project,
            'land' => $land_data,
            'polygon' => $land_data->polygon,
            'process' => $process_item,
        ]);
    }

    public function destroy($processid, $devid, $landid)
    {
        $prereqs = Process_Item::where("prerequisite_id", "=", $processid)->pluck('id');
        //ddd($processid, $treeid, $landid, $prereqs[0]);

        DB::transaction(function () use ($processid, $devid, $landid, $prereqs) {

            $landParcelProcess = Process_Item::find($prereqs[0]);
            $landParcelProcess->delete();

            $devProjectProcess = Process_Item::find($processid);
            $devProjectProcess->delete();

            $devProject = Development_Project::find($devid);
            $devProject->delete();

            $landParcel = Land_Parcel::find($landid);
            $landParcel->delete();
        });
        return redirect('/approval-item/showRequests')->with('message', 'Request Successfully Deleted');
    }

    public function edit($processid, $devid, $landid)
    {
        $process = Process_Item::find($processid);
        $dev = Development_Project::find($devid);
        return view('developmentProject::edit', [
            'process' => $process,
            'dev' => $dev,
        ]);
    }

    public function gazetteAutocomplete(Request $request)
    {
        $data = Gazette::select("gazette_number")
            ->where("gazette_number", "LIKE", "%{$request->terms}%")
            ->get();

        return response()->json($data);
    }
}
