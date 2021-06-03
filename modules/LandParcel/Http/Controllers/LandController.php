<?php

namespace LandParcel\Http\Controllers;

use App\Models\Land_Parcel;
use App\Models\Province;
use App\Models\District;
use App\Models\GS_Division;
use App\Models\Organization;
use App\Models\Gazette;
use App\Models\Land_Has_Gazette;
use App\Models\User;
use App\Models\Land_Has_Organization;
use App\Models\Process_Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\CustomClass\organization_assign;
use App\CustomClass\lanparcel_creation;
use Illuminate\Support\Facades\Notification;
Use App\Notifications\ApplicationMade;

class LandController extends Controller
{
    public function form()
    {
        $gazettes = Gazette::all();
        $province = Province::all();
        $district = District::all();
        $organizations = Organization::all();
        $gs = GS_Division::orderBy('gs_division')->get();
        return view('land::form', [
            'organizations' => $organizations,
            'gazettes' => $gazettes,
            'provinces' => $province,
            'districts' => $district,
            'gs' => $gs,

        ]);
    }

    public function save(Request $request)
    {

        $request->validate([
            'planNo' => 'required',
            'surveyorName' => 'required',
            'province' => 'required',
            'district' => 'required',
            'gs_division' => 'required',
            'governing_orgs' => 'nullable',
            'gazettes' => 'nullable',
            'polygon' => 'required',
            'district' => 'required|not_in:0',
            'province' => 'required|not_in:0',
            'organization' => 'nullable|exists:organizations,title',
        ]);
        
        
        $landid =lanparcel_creation::land_save($request);
        $process = new Process_Item();
        $process->form_type_id = 5;
        $process->form_id = $landid;
        $process->created_by_user_id = request('createdBy');
        $process->request_organization = Auth::user()->organization_id;
        if($request->filled('organization')){
            $organization = Organization::where('title', $request['organization'])->pluck('id');
            $process->activity_organization = $org_id =$organization[0];
        }
        $process->save();

        $land_process=Process_Item::latest()->first();
        if(empty($request->input('organization'))){
            organization_assign::auto_assign($land_process->id,request('district'),request('province'));
            $land_process=Process_Item::latest()->first();
        }else{
            $Admins = User::where('organization_id',$land_process->activity_organization)->whereBetween('role_id', [1, 2])->get();
            Notification::send($Admins, new ApplicationMade($land_process));
        }
        lanparcel_creation::landprocesses_save($request,$landid,$land_process->id);
        

        return redirect('/general/pending')->with('message', 'Request Created Successfully');
    }


    public function show($id)
    {
        $item = Process_Item::find($id);
        $land_data = Land_Parcel::find($item->form_id);
        return view('land::show', [
            'land' => $land_data,
            'polygon' => $land_data->polygon,
            'other_removal_requestor' => $item->other_removal_requestor_name,
            'process' => $item,
        ]);
    }

    public function destroy($landid)
    {

        DB::transaction(function () use ($landid) {

            $landhasGazettes = Land_Has_Gazette::where("land_parcel_id", "=", $landid)->get();
            foreach ($landhasGazettes as $landhasGazette) {
                $landhasGazette->delete();
            }

            $landHasOrganizations = Land_Has_Organization::where("land_parcel_id", "=", $landid)->get();
            foreach ($landHasOrganizations as $landHasOrganization) {
                $landHasOrganization->delete();
            }

            $landProcess = Process_Item::where([
                ["form_id", "=", $landid],
                ['form_type_id', '=', 5],
            ])->get();
            foreach ($landProcess as $land) {
                $land->delete();
            }

            $landParcel = Land_Parcel::find($landid);
            $landParcel->delete();
        });
        return redirect('/approval-item/showRequests')->with('message', 'Request Successfully Deleted');
    }

    function action(Request $request)
    {

        if ($request->hasFile('select_file')) {
            $file = $request->file('select_file');
            //you also need to keep file extension as well
            $name = $file->getClientOriginalExtension();
            if ($name == "kml") {
                $image = $request->file('select_file');
                $new_name = rand() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('kml'), $new_name);
                return response()->json([
                    'message'   => 'File Upload Successfull',
                    'uploaded_image' => "kml/$new_name",
                    'class_name'  => 'alert-success'
                ]);
            } else {
                return response()->json([
                    'message'   => "Error Uploading File. Check File Type.",
                    'uploaded_image' => '',
                    'class_name'  => 'alert-danger'
                ]);
            }
        }
    }
}
