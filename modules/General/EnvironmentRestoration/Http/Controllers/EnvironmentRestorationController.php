<?php

namespace EnvironmentRestoration\Http\Controllers;

use App\Models\Land_Parcel;
use App\Models\Environment_Restoration;
use App\Models\Environment_Restoration_Activity;
use App\Models\Environment_Restoration_Species;
use App\Models\Organization;
use App\Models\Process_Item;
use App\Models\Env_type;
use App\Models\Species;
use App\Models\User;
use App\Models\District;
use App\Models\Province;
use App\Models\GS_Division;
use App\Models\Gazette;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ApplicationMade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\CustomClass\organization_assign;
use App\CustomClass\lanparcel_creation;

class EnvironmentRestorationController extends Controller
{
    // public function index()
    // {
    //     $restorations = Environment_Restoration::all();       //show all records for index
    //     return view('environmentRestoration::index', [
    //         'restorations' => Environment_Restoration::paginate(10),
    //     ]);
    // }

    // public function myIndex()
    // {
    //     $userID = Auth::user()->id;
    //     $restorations = Environment_Restoration::where('created_by_user_id','=', $userID)->get();       //show all records for index
    //     return view('environmentRestoration::myIndex', [
    //         'restorations' => $restorations,
    //     ]);
    // }

    public function create()
    {
        $restorations = Environment_Restoration::all();         //shows all records of enviroment restoration request
        $organizations = Organization::where('type_id', '=', '1')->get(); //show all records for all government organizations
        $restoration_activities = Environment_Restoration_Activity::all();
        $ecosystems = Env_type::all();
        $province = Province::all();
        $district = District::all();
        $gazettes = Gazette::all();
        $gs = GS_Division::orderBy('gs_division')->get();
        return view('environmentRestoration::create', [
            'restorations' => $restorations,
            'organizations' => $organizations,
            'gazettes' => $gazettes,
            'restoration_activities' => $restoration_activities,
            'ecosystems' => $ecosystems,
            'provinces' => $province,
            'districts' => $district,
            'gs' => $gs,
        ]);
    }

    public function show($id)           //show one record for moreinfo button
    {
        $process_item = Process_Item::find($id);
        $restoration = Environment_Restoration::find($process_item->form_id);
        //$restoration = Environment_Restoration::find($id);
        // $species = Environment_Restoration_Species::find($restoration->id); 
        $species = Environment_Restoration_Species::where('environment_restoration_id', ($restoration->id))->get();
        $land = Land_Parcel::find($restoration->land_parcel_id);
        $polygon = $land->pluck('polygon')->first();
        //ddd($restoration->environment_restoration_activity_id);
        //ddd($restoration->Environment_Restoration_Activity->title);
        //ddd($restoration->ecosystems_type->type);
        $govorgs = $land->pluck('governing_organizations');
        //ddd($land->title);
        //ddd($restoration->Status);
        return view('environmentRestoration::show', [
            'restoration' => $restoration,
            'species' => $species,
            'land' => $land,
            'polygon' => $polygon,
            'govorgs' => $govorgs
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'planNo' => 'required',
            'province' => 'required',
            'district' => 'required',
            'gs_division' => 'required',
            'environment_restoration_activity' => 'required',
            'environment_restoration_activity' => 'required',
            'ecosystem' => 'required',
            'activity_org' => 'nullable|exists:organizations,title',
            'polygon' => 'required'
        ]);
        DB::transaction(function () use ($request) {

            $newland =lanparcel_creation::land_save($request);
            
            $restoration = new Environment_Restoration();
            $restoration->title = request('title');
            $restoration->environment_restoration_activity_id = request('environment_restoration_activity');
            $restoration->organization_id = request('organization');
            $restoration->eco_system_id = request('ecosystem');
            $restoration->land_parcel_id = $newland;
            $restoration->created_by_user_id = request('createdBy');
            $restoration->status = request('status');
            $restoration->save();

            $latest = Environment_Restoration::latest()->first();
            $newres = $latest->id;
            
            //restoration process item
            $Process_item = new Process_Item();
            $Process_item->form_id = $latest->id;
            $Process_item->form_type_id = 3;
            $Process_item->created_by_user_id = request('createdBy');
            if($request->filled('activity_org')){
                $activityorgid = Organization::where('title', request('activity_org'))->pluck('id');
                $Process_item->activity_organization = $activityorgid[0];
            }
            $Process_item->request_organization = Auth::user()->organization_id;
            $Process_item->prerequisite_id = null;
            $Process_item->prerequisite = 0;
            $Process_item->status_id = 1;
            $Process_item->save();
            //+
            $latestprocess = Process_Item::latest()->first();
            if(empty($request->input('activity_org'))){
                organization_assign::auto_assign($latestprocess->id,request('district'),request('province'));
                $latestprocess =Process_Item::latest()->first();
            }
            else{
                $Admins = User::where('organization_id',$latestprocess->activity_organization)->whereBetween('role_id', [1, 2])->get();
                Notification::send($Admins, new ApplicationMade($latestprocess));
            }
            $process = new Process_Item();
            $process->form_type_id = 5;
            $process->form_id = $newland;
            $process->created_by_user_id = request('createdBy');
            $process->request_organization = Auth::user()->organization_id;
            $process->activity_organization = $latestprocess->activity_organization;
            $process->status_id = $latestprocess->status_id;
            $process->prerequisite_id = $latestprocess->id;
            $process->prerequisite = 0;
            $process->save();

            
            //Adding to Environment Restoration Species Table using ajax
            $rules = array(
                'statusSpecies.*'  => 'required',
                'species_name.*'  => 'required|exists:species_information,title',
                'quantity.*'  => 'required|integer',
                'height.*'  => 'required|integer',
                'dimension.*'  => 'required',
                'remark.*'  => 'required'
            );
            $error = Validator::make($request->all(), $rules);
            if ($error->fails()) {
                return response()->json([
                    'error'  => $error->errors()->all()
                ]);
            }
            
            $statusSpecies = $request->statusSpecies;
            $species_names = $request->species_name;
            $quantity = $request->quantity;
            $height = $request->height;
            $dimension = $request->dimension;
            $remark = $request->remark;
            for ($count = 0; $count < count($species_names); $count++) {
                $species_id = Species::where('title', $species_names[$count])->pluck('id');
                $data = array(
                    'environment_restoration_id' => $newres,
                    'status' => $statusSpecies[$count],
                    'species_id'  => $species_id[0],
                    'quantity'  => $quantity[$count],
                    'height'  => $height[$count],
                    'dimensions'  => $dimension[$count],
                    'remarks'  => $remark[$count],
                );
                $insert_data[] = $data;
            }

            Environment_Restoration_Species::insert($insert_data);

            //land request process item
            
            
        });

        return redirect('/general/pending')->with('message', 'Restoration Request Created Successfully');
    }
}
