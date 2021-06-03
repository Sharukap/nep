<?php
namespace App\CustomClass;
 
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\Models\Process_Item;
use App\Models\User;
use App\Models\Organization_Activity;
use Illuminate\Support\Facades\Notification;
Use App\Notifications\AssignOrg;
Use App\Notifications\ApplicationMade;

class organization_assign
{
    public static function  auto_assign($P_id, $District , $Province)
    {
      $Process_Item = Process_Item::find($P_id);
        $activity_org = Organization_Activity::where('district_id',$District)->where('form_type_id',$Process_Item->form_type_id)->first();
      if($activity_org != null){
        $Process_Item->activity_organization = $activity_org->organization_id;
        if($activity_org->admin_access == 0){
          $Process_Item->status_id=2;
        }else{
          $Process_Item->status_id=9;
        }
        
      }
      else{
        $activity_org2 = Organization_Activity::where('province_id',$Province)->where('form_type_id',$Process_Item->form_type_id)->first();
        if($activity_org2 != null){
          $Process_Item->activity_organization = $activity_org2->organization_id;
          if($activity_org2->admin_access == 0){
            $Process_Item->status_id=2;
          }else{
            $Process_Item->status_id=9;
          }
        }
        else{
            $activity_org3 = Organization_Activity::where('district_id',26)->where('form_type_id',$Process_Item->form_type_id)->first(); //checking for all island
          if($activity_org3 != null){
            $Process_Item->activity_organization = $activity_org3->organization_id;
            if($activity_org3->admin_access == 0){
              $Process_Item->status_id=2;
            }else{
              $Process_Item->status_id=9;
            }
          }
          else{
            $Process_Item->activity_organization=2;
          }
        }
      }
      $Process_Item->save();
      $Process_Itemnew = Process_Item::find($P_id);
      $Admins = User::where('organization_id',$Process_Item->activity_organization)->whereBetween('role_id', [1, 2])->get();
      $Managers = User::where('organization_id',$Process_Item->activity_organization)->whereBetween('role_id', [3, 4])->get();
      if($Process_Itemnew->status_id ==2){
        Notification::send($Managers, new AssignOrg($Process_Itemnew));
      }else if($Process_Itemnew->status_id == 9){
        Notification::send($Managers, new AssignOrg($Process_Itemnew));
        Notification::send($Admins, new ApplicationMade($Process_Itemnew));
      }else{
        Notification::send($Admins, new ApplicationMade($Process_Itemnew));
        //dd("new app");
      }
      return $Process_Item->activity_organization;
    }
}