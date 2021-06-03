<?php

namespace Admin\Http\Controllers;

use App\Models\Designation;
use App\Models\Organization;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    // The more details view for all the users will be the same. So one view is being used with one function to display that view.
    public function more($id)
    {
        $user = User::find($id);
        return view('admin::more', compact('user'));
    }

    // Returns the create.blade.php view in the admin module.
    public function create()
    {
        $organizations = Organization::all();
        $designations = Designation::all();
        if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2) {
            $roles = Role::where('id', '>', 1)->get();
        } elseif (Auth::user()->role_id == 3) {
            $roles = Role::where('id', '>', 3)->get();
        } elseif (Auth::user()->role_id == 4) {
            $roles = Role::where('id', '>', 4)->get();
        }

        return view('admin::create', [
            'designations' => $designations,
            'organizations' => $organizations,
            'roles' => $roles,
        ]);
    }

    // When the user fills in the details of the new user and clicks submit it will be handled here
    // The password will by defauly be password, which is encypted and saved to the DB.
    // Hope to send an email to the newly created user's email saying login and change password?
    public function store(Request $request)
    {
        // if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2) {
        //     $request->validate([
        //         'name' => 'required',
        //         'email' => 'required|email',
        //         'designation' => 'required|in:1,2,3,4,5,6,7',
        //         'role' => 'required|in:2,3,4,5,6',
        //         'organization' => 'required|in:1,2,3,4,5,6',
        //     ]);
        // }
        // $request->validate([
        //     'name' => 'required',
        //     'email' => 'required|email',
        //     'designation' => 'required|in:1,2,3,4,5,6,7',
        //     'role' => 'required|in:2,3,4,5,6',
        // ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->organization_id = $request->organization;
        $user->role_id = $request->role;
        $user->designation_id = $request->designation;
        $user->created_by_user_id = $request->created_by;
        $user->status = $request->status;
        $user->password = bcrypt("password");
        $user->save();
        return redirect('/user/index')->with('message', 'User Successfully created');
    }

    // Returns the edit view for admin.
    public function edit($id)
    {
        $organizations = Organization::all();
        $designations = Designation::all();
        $user = User::find($id);
        return view('admin::edit', [
            'user' => $user,
            'designations' => $designations,
            'organizations' => $organizations
        ]);
    }

    // When the admin clicks the submit button in the edit view, the following data will be 
    // patched for the relavant user who is being edited and saved in the db.
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'designation_id' => $request->designation,
            'organization_id' => $request->organization,
        ]);
        //  Redirect back to the admin view via the UserController which will 
        //  check the current user role and direct back to the index page appropriately along with a session message saying success.
        return redirect('/user/index')->with('message', 'User Updated Successfully');
    }

    // Routing logic
    public function index()
    {
        $role = Auth::user()->role_id;
        $org = Auth::user()->organization_id;

        switch ($role) {
            case 1:         // Role = 1 means super administrator. Display all users.
                $users = User::where('role_id', '>', 0)->orWhereNull('role_id')->get();        // Get null (self registered) users as well
                return view('admin::index', [
                    'users' => $users,
                ]);
                break;
            case 2:     // Role = 2 means administrator. Get all users from the org that Admin is from
                $users = User::where([
                    ['role_id', '>', 1],           // where role id = 1
                    ['organization_id', '=', $org], // and org=current user's org
                ])->get();
                return view('admin::index', [
                    'users' => $users,
                ]);
                break;
            case 3:     // Role = 3 means head of org.
                $users = User::where([
                    ['role_id', '>', 3],   //Get all users whose role is > 3. Means less than the HoO role. 
                    ['organization_id', '=', $org],  //Get users from the same org as the logged in user.
                ])->get();
                return view('admin::index', [
                    'users' => $users,
                ]);
                break;
            case 4:
                $users = User::where([
                    ['role_id', '>', 4],       // Role = 4 means manager.
                    ['organization_id', '=', $org],
                ])->get();
                return view('admin::index', [
                    'users' => $users,
                ]);
                break;
            default:            //Else display the unauthorized view.
                return view('admin::unauthorized');
        }
    }

    public function searchUsers(Request $request)
    {
        $role = Auth::user()->role_id;
        $org = Auth::user()->organization_id;

        $search = $request->get('search');

        if ($role == 1) {
            $users = User::select()
                ->where("name", "LIKE", '%' . $search . '%')
                ->where('status', '=', 1)
                ->get();
        } else {
            $users = User::select()
                ->where("name", "LIKE", '%' . $search . '%')
                ->where('status', '=', 1)
                ->where('role_id', '>', $role)
                ->where('organization_id', '=', $org)
                ->get();
        }

        return view('admin::index', [
            'users' => $users,
        ]);
    }

    public function searchSelfRegistered(Request $request)
    {
        $search = $request->get('search');
        $users = User::select()
            ->where("name", "LIKE", '%' . $search . '%')
            ->where('status', '=', 0)
            ->get();

        return view('admin::admin.selfRegistered', [
            'users' => $users,
        ]);
    }

    // Function to reset the password.
    public function alterPassword(Request $request)
    {
        // Validation rules;
        // Data is required in all fields
        // new password must match cnfirm password.
        // new password and confirm password has a minimum of 6 chars and max of 255.
        $rules = [
            'currentpassword' => 'required',
            'newpassword' => 'required | max:255',
            'newpassword' => 'min:6|required_with:confirmpassword|same:confirmpassword',
            'confirmpassword' => 'min:6 | max:255|same:newpassword',

        ];

        $messages = [
            "newpassword.same" => "Passwords must match",
            "confirmpassword.same" => "Passwords must match",
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        // If validation fails redirect back to the view with the errors.
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        // If validation is successfull check the old password with the password in the DB
        if (Hash::check($request->currentpassword, Auth::user()->password, [])) {
            $user = User::find(Auth::user()->id);
            $user->update([
                'password' => bcrypt($request->newpassword),    // If matched update the db witht he new password
            ]);
            return redirect('/user/index')->with('message', 'Password Successfully Changed');
        } else
            return redirect('/user/index')->with('danger', 'Current Password is Incorrect'); // Else show that old password is incorrect
    }
}
