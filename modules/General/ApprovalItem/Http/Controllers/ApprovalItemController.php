<?php

namespace ApprovalItem\Http\Controllers;

use App\Models\User;
use App\Models\Organization;
use App\Models\Crime_report;
use App\Models\Tree_Removal_Request;
use App\Models\Development_Project;
use App\Models\Process_Item;
use App\Models\Form_Type;
use App\Models\Process_item_progress;
use App\Models\Process_item_status;
use App\Models\Land_Parcel;
use App\Models\Land_Has_Organization;
use App\Models\Land_Has_Gazette;
use App\Models\Environment_Restoration;
use App\Models\Environment_Restoration_Activity;
use App\Models\Environment_Restoration_Species;
use App\Mail\RequestApproved;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Mail\AssignOrganization;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Mail;
use App\Notifications\StaffAssigned;
use App\Notifications\AssignOrg;
use App\Notifications\prereqmemo;
use Illuminate\Support\Facades\Storage;
use PDF;
use Redirect;


class ApprovalItemController extends Controller
{

    public function confirm_assign_staff($id, $pid)
    {
        $array = DB::transaction(function () use ($id, $pid) {
            $Process_item = Process_Item::find($pid);
            $new_assign = 1;
            if ($Process_item->activity_user_id != null) {
                $new_assign = '0';
            }

            Process_Item::where('id', $pid)->update([
                'activity_user_id' => $id,
                'status_id' => 3
            ]);
            $user = User::find($id);
            Notification::send($user, new StaffAssigned($Process_item));
            return $new_assign;
        });
        if ($array == 0) {
            return back()->with('message', 'Authority changed Successfully');
        }
        return back()->with('message', 'Authority assigned Successfully');
    }

    public function change_assign_organization($id, $pid)
    {
        DB::transaction(function () use ($id, $pid) {
            $Process_item = Process_Item::find($pid);
            $Users = User::where([
                ['role_id', '=', 3],
                ['organization_id', '=', $id],
            ])->orWhere([
                ['role_id', '=', 4],
                ['organization_id', '=', $id],
            ])->get();
            Process_Item::where('id', $pid)->update([
                'activity_organization' => $id,
                'status_id' => 2
            ]);
            Process_Item::where([
                ['prerequisite_id', '=', $Process_item],
                ['prerequisite', '=', 0],
            ])->update([
                'activity_organization' => $id,
                'status_id' => 2
            ]);
            Notification::send($Users, new AssignOrg($Process_item));
        });

        return back()->with('message', 'Assigned Organization Successfully');
    }

    public function assign_unregistered_organization(request $request)
    {
        $request->validate([
            'organization' => 'required',
            'email' => 'required',
        ]);
        $array = DB::transaction(function () use ($request) {

            //$User = User::find($request['create_by']);
            Process_Item::where('id', $request['process_id'])->update([
                'other_removal_requestor_name' => $request['organization'],
                'status_id' => 2
            ]);
            $process_item = Process_Item::find($request['process_id']);
            return ($process_item);
        });
        $user = User::find($request['create_by']);
        if ($array->form_type_id == '1') {
            $item = Tree_Removal_Request::find($array->form_id);
            $Photos = Json_decode($item->images);
            $data = $item->tree_locations;
        } else if ($array->form_type_id == '2') {
            $item = Development_Project::find($array->form_id);
            $Photos = Json_decode($item->images);
            $data = null;
        } else if ($array->form_type_id == '3') {
            $item = Environment_Restoration::find($array->form_id);
            $Photos = null;
            $data = Environment_Restoration_Species::all()->where('environment_restoration_id', $item->id);
            $Land_Organizations = Land_Has_Organization::where('land_parcel_id', $item->land_parcel_id)->get();
        } else if ($array->form_type_id == '4') {
            $item = Crime_report::find($array->form_id);
            $Photos = Json_decode($item->photos);
            $data = null;
        }
        $land_parcel = Land_Parcel::find($item->land_parcel_id);
        //dd($array);


        $pdf = PDF::loadView('approvalItem::index', [
            'process_item' => $array,
            'user' => $user,
            'item' => $item,
            'polygon' => $land_parcel->polygon,
            'data' => $data,
        ]);
        $array->requestor_email = $request['email'];

        $process_item = $array->toarray();

        if ($Photos != null) {
            $y = 0;
            foreach ($Photos as $photo) {
                //return Storage::disk('public')->download($photo);
                $contents[$y] =  Storage::disk('public')->get($photo);
                $y++;
            }
        }
        if (isset($contents)) {
            Mail::send('emails.assignorg', $process_item, function ($message) use ($pdf, $contents, $Photos, $process_item) {

                $message->to($process_item['requestor_email']);
                $message->subject('Assigning application');
                $message->attachData($pdf->output(), 'document.pdf');
                for ($y = 0; $y < count($contents); $y++) {
                    $message->attachData($contents[$y], $Photos[$y]);
                }
            });
        } else {
            Mail::send('emails.assignorg', $process_item, function ($message) use ($pdf, $process_item) {

                $message->to($process_item['requestor_email']);
                $message->subject('Assigning application');
                $message->attachData($pdf->output(), 'document.pdf');
            });
        }

        return back()->with('message', 'Successfully forwarded the application through email');
    }

    public function showRequests()
    {
        $items = Process_Item::where('created_by_user_id', '=', Auth::user()->id)->get();
        $organizations = Organization::all();
        return view('approvalItem::requests', [
            'items' => $items,
            'organizations' => $organizations,
        ]);
    }

    public function filterRequests(Request $request)
    {
        $organizations = Organization::all();
        if ($request->date && $request->organization) {
            $items = Process_Item::where([
                ['created_by_user_id', '=', Auth::user()->id],
                ['activity_organization', '=', $request->organization],
            ])->whereDate('created_at', '=', $request->date)->get();
            return view('approvalItem::requests', [
                'items' => $items,
                'organizations' => $organizations,
            ]);
        } else if ($request->date) {
            $items = Process_Item::where('created_by_user_id', '=', Auth::user()->id)->whereDate('created_at', '=', $request->date)->get();
            return view('approvalItem::requests', [
                'items' => $items,
                'organizations' => $organizations,
            ]);
        } else if ($request->organization) {
            $items = Process_Item::where([
                ['created_by_user_id', '=', Auth::user()->id],
                ['activity_organization', '=', $request->organization],
            ])->get();
            return view('approvalItem::requests', [
                'items' => $items,
                'organizations' => $organizations,
            ]);
        } else {
            $items = Process_Item::where('created_by_user_id', '=', Auth::user()->id)->get();
            return view('approvalItem::requests', [
                'items' => $items,
                'organizations' => $organizations,
            ]);
        }
    }

    public function choose_assign_staff($id)
    {
        $process_item = Process_Item::find($id);
        if ($process_item->status_id > 2) {
            return redirect()->action(
                [ApprovalItemController::class, 'investigate'],
                ['id' => $id]
            );
        }
        $Organizations = Organization::all();
        $organization = Auth::user()->organization_id;
        if (Auth::user()->role_id == '3') {
            $Users = User::where([
                ['role_id', '>', 3],
                ['organization_id', '=', $organization],
            ])->get();
        } else {
            $Users = User::where([
                ['role_id', '=', 5],
                ['organization_id', '=', $organization],
            ])->get();
        }
        if ($process_item->form_type_id == '1') {
            $item = Tree_Removal_Request::find($process_item->form_id);
            $Photos = Json_decode($item->images);
            $data = $item->tree_locations;
        } else if ($process_item->form_type_id == '2') {
            $item = Development_Project::find($process_item->form_id);
            $Photos = null;
            $data = null;
        } else if ($process_item->form_type_id == '3') {
            $item = Environment_Restoration::find($process_item->form_id);
            $data = Environment_Restoration_Species::all()->where('environment_restoration_id', $item->id);
        } else if ($process_item->form_type_id == '4') {
            $item = Crime_report::find($process_item->form_id);
            $Photos = Json_decode($item->photos);
            $data = null;
        }
        if ($process_item->form_type_id != '5') {
            $land_parcel = Land_Parcel::find($item->land_parcel_id);
            $landProcess = Process_Item::where([
                ['prerequisite_id', '=', $process_item->id],
                ['prerequisite', '=', 0],
            ])->first();

            return view('approvalItem::staffAssign', [
                'item' => $item,
                'Organizations' => $Organizations,
                'process_item' => $process_item,
                'polygon' => $land_parcel->polygon,
                'land_process' => $landProcess,
                'Photos' => $Photos,
                'data' => $data,
                'Users' => $Users,

            ]);
        } else {
            $item = Land_Parcel::find($process_item->form_id);
            $Land_Organizations = Land_Has_Organization::where('land_parcel_id', $item->id)->get();
            $Land_Gazzettes = Land_Has_Gazette::where('land_parcel_id', $item->id)->get();
            return view('approvalItem::staffAssign', [
                'item' => $item,
                'process_item' => $process_item,
                'Organizations' => $Organizations,
                'polygon' => $item->polygon,
                'LandOrganizations' => $Land_Organizations,
                'Users' => $Users,
                'Land_Gazzettes' =>$Land_Gazzettes,
            ]);
        }
    }

    public function choose_assign_organization($id)
    {
        $process_item = Process_Item::find($id);
        $Organizations = Organization::all();

        if ($process_item->form_type_id == '1') {
            $item = Tree_Removal_Request::find($process_item->form_id);
            $data = $item->tree_locations;
            $Photos = Json_decode($item->images);
        } else if ($process_item->form_type_id == '2') {
            $item = Development_Project::find($process_item->form_id);
            $data = null;
            $Photos = null;
        } else if ($process_item->form_type_id == '3') {
            $item = Environment_Restoration::find($process_item->form_id);
            $data = Environment_Restoration_Species::all()->where('environment_restoration_id', $item->id);
            $Photos = null;
        } else if ($process_item->form_type_id == '4') {
            $item = Crime_report::find($process_item->form_id);
            $data = null;
            $Photos = Json_decode($item->photos);
        }

        if ($process_item->form_type_id != '5') {
            $land_parcel = Land_Parcel::find($item->land_parcel_id);
            $landProcess = Process_Item::where([
                ['prerequisite_id', '=', $process_item->id],
                ['prerequisite', '=', 0],
            ])->first();
            $test =Process_Item::where([
                ['form_type_id', '=', $item->land_parcel_id],
                ['form_id', '=', 5],
            ])->first();
            return view('approvalItem::assignOrg', [
                'Organizations' => $Organizations,
                'process_item' => $process_item,
                'polygon' => $land_parcel->polygon,
                'item' => $item,
                'land_process' => $landProcess,
                'data' => $data,
                'Photos' => $Photos,
            ]);
        } else {
            $item = Land_Parcel::find($process_item->form_id);
            $Land_Organizations = Land_Has_Organization::where('land_parcel_id', $item->id)->get();
            $Land_Gazzettes = Land_Has_Gazette::where('land_parcel_id', $item->id)->get();
            return view('approvalItem::assignOrg', [
                'item' => $item,
                'process_item' => $process_item,
                'Organizations' => $Organizations,
                'polygon' => $item->polygon,
                'LandOrganizations' => $Land_Organizations,
                'Land_Gazzettes' =>$Land_Gazzettes,
            ]);
        }
    }

    public function investigate($id)
    {
        $process_item = Process_Item::find($id);
        $Organizations = Organization::all();
        $Prerequisites = Process_Item::all()->where('prerequisite_id', $process_item->id);
        $Process_item_statuses = Process_item_status::all();
        $Process_item_progresses = Process_item_progress::all()->where('process_item_id', $id);
        $organization = Auth::user()->organization_id;
        if (Auth::user()->role_id == '3') {
            $Users = User::where([
                ['role_id', '>', 3],
                ['organization_id', '=', $organization],
            ])->get();
        } else {
            $Users = User::where([
                ['role_id', '=', 5],
                ['organization_id', '=', $organization],
            ])->get();
        }
        if ($process_item->form_type_id == '1') {
            $item = Tree_Removal_Request::find($process_item->form_id);
            $Photos = Json_decode($item->images);
            $data = $item->tree_locations;
        } else if ($process_item->form_type_id == '2') {
            $item = Development_Project::find($process_item->form_id);
            $Photos = null;
            $data = null;
        } else if ($process_item->form_type_id == '3') {
            $item = Environment_Restoration::find($process_item->form_id);
            $data = Environment_Restoration_Species::all()->where('environment_restoration_id', $item->id);
        } else if ($process_item->form_type_id == '4') {
            $item = Crime_report::find($process_item->form_id);
            $Photos = Json_decode($item->photos);
            $data = null;
        }
        if ($process_item->form_type_id != '5') {

            $land_parcel = Land_Parcel::find($item->land_parcel_id);
            $landProcess = Process_Item::where([
                ['prerequisite_id', '=', $process_item->id],
                ['prerequisite', '=', 0],
            ])->first();
            
            return view('approvalItem::investigate', [
                'item' => $item,
                'Organizations' => $Organizations,
                'Prerequisites' => $Prerequisites,
                'process_item' => $process_item,
                'polygon' => $land_parcel->polygon,
                'Process_item_statuses' => $Process_item_statuses,
                'Process_item_progresses' => $Process_item_progresses,
                'Photos' => $Photos,
                'data' => $data,
                'Users' => $Users,
                'land_process' => $landProcess,
            ]);
        } else {
            $item = Land_Parcel::find($process_item->form_id);
            $Land_Organizations = Land_Has_Organization::where('land_parcel_id', $item->id)->get();
            $Land_Gazzettes = Land_Has_Gazette::where('land_parcel_id', $item->id)->get();
            return view('approvalItem::investigate', [
                'item' => $item,
                'process_item' => $process_item,
                'Organizations' => $Organizations,
                'polygon' => $item->polygon,
                'Users' => $Users,
                'Process_item_statuses' => $Process_item_statuses,
                'Process_item_progresses' => $Process_item_progresses,
                'Prerequisites' => $Prerequisites,
                'LandOrganizations' => $Land_Organizations,
                'Land_Gazzettes' =>$Land_Gazzettes,
            ]);
        }
    }
    public function create_prerequisite(Request $request)
    {

        $request->validate([
            'organization' => 'required|not_in:0',
            'request' => 'required',
        ]);
        $id = $request['process_id'];
        $Process_item_old = Process_Item::find($id);

        $Process_Item = new Process_Item;
        $Process_Item->created_by_user_id = $request['create_by'];
        $Process_Item->request_organization = $request['create_organization'];
        $Process_Item->activity_organization = $request['organization'];
        $Process_Item->form_id = $Process_item_old['form_id'];
        $Process_Item->form_type_id = $Process_item_old['form_type_id'];
        $Process_Item->status_id = "2";
        $Process_Item->prerequisite = "1";
        $Process_Item->prerequisite_id = $Process_item_old['id'];
        $Process_Item->remark = $request['request'];
        $Process_Item->save();
        return back()->with('message', 'Prerequisite logged Successfully');
    }

    public function cancel_prerequisite($id, $userid)
    {
        $Process_Item = Process_Item::find($id);
        $User = User::find($userid);
        $remark = $Process_Item->remark . ' cancelled by ' . $User->name . ' (userId: ' . $User->id . ')';
        $Process_Item->update([
            'status_id' => 8,
            'remark' => $remark,
        ]);
        if ($Process_Item->created_by_user_id == $userid) {
            return back()->with('message', 'Prerequisite is removed successfully');
        }
        $user = User::find($Process_Item->created_by_user_id);
        $Process_Item->created_by_user_id = $userid;
        Notification::send($user, new prereqmemo($Process_Item));
        return back()->with('message', 'Prerequisite removed and requestor notified');
    }

    public function progress_update(Request $request)
    {

        $request->validate([
            'status' => 'required|not_in:0',
            'request' => 'required',
        ]);
        $id = $request['process_id'];
        Process_Item::where('id', $id)->update(['status_id' => 4]);
        $Process_item_progress = new Process_item_progress;
        $Process_item_progress->created_by_user_id = $request['create_by'];
        $Process_item_progress->process_item_id = $request['process_id'];
        $Process_item_progress->status_id = $request['status'];
        $Process_item_progress->remark = $request['request'];
        $Process_item_progress->save();
        $Process_item_statuses = Process_item_status::all();

        //dd($Process_item_progress,$Process_item_statuses);
        return back()->with('message', 'Progress updated Successfully');
    }

    public function final_approval(Request $request)
    {

        $request->validate([
            'status' => 'required|not_in:0',
            'request' => 'required',
        ]);
        $id = $request['process_id'];
        $title = Process_item_status::where('id', $request['status'])->first()->status_title;
        if ($request['status'] == 5) {
            $Incomplete_prerequisites2 = Process_Item::all()->where(
                'status_id',
                '!=',
                '5',
            )->where(
                'status_id',
                '!=',
                '8',
            )->where('prerequisite_id', $id);
            if ($Incomplete_prerequisites2->isNotEmpty()) {
                //dd($Incomplete_prerequisites2);
                return back()->with('warning', 'Prerequisites need to be approved first');
            } else {

                Process_Item::where('id', $id)->update(['status_id' => 5]);
                $Process_item_progress = new Process_item_progress;
                $Process_item_progress->created_by_user_id = $request['create_by'];
                $Process_item_progress->process_item_id = $request['process_id'];
                $Process_item_progress->status_id = $request['status'];
                $Process_item_progress->remark = 'Final Approval of application ' . $request['request'];
                $Process_item_progress->save();
            }
        } else {
            Process_Item::where('id', $id)->update(['status_id' => 6]);
            $Process_item_progress = new Process_item_progress;
            $Process_item_progress->created_by_user_id = $request['create_by'];
            $Process_item_progress->process_item_id = $request['process_id'];
            $Process_item_progress->status_id = $request['status'];
            $Process_item_progress->remark = 'Final Reject of application ' . $request['request'];
            $Process_item_progress->save();
        }



        return back()->with('message', 'Request ' . $title);
    }
}
