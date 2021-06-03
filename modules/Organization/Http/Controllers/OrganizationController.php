<?php
namespace Organization\Http\Controllers;
use App\Models\User;
use App\Models\Organization;
use App\Models\Province;
use App\Models\Organization_Activity;
use App\Models\Contact;
use App\Models\Form_Type;
use App\Models\Branch_Type;
use App\Models\Type;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use DB;

class OrganizationController extends Controller {
    
    // Returns the create.blade.php view in the organization module.
    public function create() {
        
        $contact_type = collect( [
            (['id' => 1, 'title' => "Mobile Phone"]),
            (['id' => 2, 'title' => "Land Phone"]),
            (['id' => 3, 'title' => "Email"])
        ])->map(function($row) {
            return (object) $row;
        });
       
        $branches = Branch_Type::all();
        //dd($contact_type,$branches);
        $org_types = Type::all();
        return view(('organization::create'), [
            'branches' => $branches,
            'org_types' => $org_types,
            'contact_types' => $contact_type
        ]);  
    }

    // When the user fills in the details of the new organization and clicks submit it will be handled here. organization details and contact details will store database.
    public function store(Request $request) {
        if($request->type==1 || $request->type == 2){
            $condition= "required|digits:10";
        }else{
            $condition = "required|email";
        }
        
        $request->validate([
            'title' => 'required',
            'city' => 'required',
            'organization_type' => 'required',
            'contact' => $condition,
            'address' =>'required',
        ]);
        //dd($request->all());
        $organization = new Organization();
        $organization->title = $request->title;
        $organization->city = $request->city;
        $organization->type_id = $request->organization_type;
        $organization->branch_type_id = $request->branch_type;
        $organization->description = $request->description;
        $organization->status = $request->status;
        $organization->save();
        $type = $request->type;
        $contact_signature = $request->contact_signature;

            $contact = new Contact();
            $contact->org_id = $organization->id;
            if($request->type==1){
                $contact->type ="Phone Number";
            }elseif($request->type==2){
                $contact->type ="Fixed Line";
            }else{
                $contact->type ="Email";
            }
            $contact->contact_signature = $request->contact;
            $contact->primary = 1;
            $contact->status = 7;
            $contact->save();

            $contact = new Contact();
            $contact->org_id = $organization->id;
            $contact->type = "Address";
            $contact->contact_signature = $request->address;
            $contact->primary = 0;
            $contact->status = 7;
            $contact->save();
        
        //direct back to the index page.
        return redirect('/organization/index')->with('message', 'Organization Successfully created');
    }

    // Return the View more details window for organization.
    public function more(Request $request){ 
        $organization = Organization::find($request->id); 
        //$contact = Contact::all();   
        $contact = Contact::Where('org_id',$request->id)->get(); 
        $activities = Organization_Activity::all();
        //dd($contact);   
        //direct back to the index page.                   
        return view('organization::more', compact("organization","contact"));
    }

    
    // Returns the edit view for organization.
    public function edit($id) {
        $organization = Organization::find($id);
        $org_type = Type::all();
        $contact = Contact::Where('org_id',$id)->get(); 
        //dd($contact);  
        //direct back to the index page.
        return view('organization::edit', [
            'organization' => $organization,
            'contact' => $contact, 
            'org_type' => $org_type,
        ]);
    }

    
    // When the admin clicks the submit button in the edit view, the following data will be
    // patched for the relavant organization who is being edited and saved in the db.
    public function update(Request $request, $id) {
        $organization = Organization::find($id);
        $organization->update([
            'title' => $request->title,
            'city' => $request->city, 
            'country' => $request->country, 
            'type_id' => $request->org_type, 
            'description' => $request->description, 
            'status' => $request->status, 
        ]);
       
        //direct back to the index page.
        return redirect('/organization/index')->with('message', 'Organization Updated Successfully');
    }

    public function contactremove($id)
    {
        $contact = Contact::find($id);
        $org_id  = $contact->org_id;
        $contact->delete();
        return back()->with('message', 'Contact removed Successfully');
    }
    
    public function contactupdate(Request $request, $id)
    {
        $request->validate([
            'type' => 'required',
        ]);

        if($request->type==1 || $request->type == 2){
            $condition= "required|digits:10";
        }elseif($request->type==3){
            $condition = "required|email";
        }else{
            $condition = "required";
        }
        $request->validate([
            'contact_signature' => $condition,
        ]);
            $contact = new Contact();
            $contact->org_id = $id;
            if($request->type==1){
                $contact->type ="Mobile Phone";
            }elseif($request->type==2){
                $contact->type ="Fixed Line";
            }elseif($request->type==3){
                $contact->type ="Email";
            }else{
                $contact->type ="Address";
            }
            $contact->contact_signature = $request->contact_signature;
            if($request->primary != null){
                $contact->primary = $request->primary;
            }else{
                $contact->primary = 0;
            }
            $contact->status = 7;
            $contact->save();
            
        return back()->with('message', 'Contact updated Successfully');
    }
    
    // Routing logic
    public function index() {
        
        $organization = Organization::all();
        $contact = Contact::all();
        //direct back to the index page.
        return view('organization::index')->with('organization', $organization)->with('contact',$contact);
        
    }

    public function activities() {

        $organizations = Organization_Activity::all();
        //direct back to the index page.
        return view('organization::activity', [
            'organizations' => $organizations,
        ]);  
    }

    public function new_activity() {
        $organizations = Organization::all();
        $Forms =Form_Type::all();
        $province = Province::all();
        $district = District::all();
        //direct back to the index page.
        return view('organization::newactivity', [
            'organizations' => $organizations,
            'Forms' => $Forms,
            'provinces' => $province,
            'districts' => $district,
        ]);  
    }

    public function activity_create(Request $request) {
        $exist_act = Organization_Activity::where('organization_id', request('organization'))->first();
        $Org_act = new Organization_Activity();
        $Org_act->form_type_id = $request->form_type;
        if((request('district') != 27) && (request('district') != null)){
            $Org_act->district_id = request('district');
        }
        else{
            $Org_act->province_id = request('province');
        }
        $Org_act->organization_id = request('organization');
        if($exist_act != null){
            $Org_act->admin_access = $exist_act->admin_access;
        }else{
            $Org_act->admin_access = 0;
        }
        
        $Org_act->save();
        return redirect('/organization/actIndex')->with('message', 'Organization Successfully assigned to handle application');
    }
    public function activity_remove($id) {
        $organization = Organization_Activity::find($id);
        $organization->delete();
        return redirect('/organization/actIndex')->with('message', 'Organization Successfully removed from handling application');
    }

    public function destroy($id) {
        $organization = Organization::find($id);
        $organization->delete();
        return redirect('/organization/index')->with('message', 'Organization Successfully Deleted');
    }

    public function activity_admin_on() {
        $id = Auth::user()->organization_id;
        Organization_Activity::where('organization_id',$id)->update(['admin_access' => 1]);
        return back()->with('message', 'Admin review turned on');
    }

    public function activity_admin_off() {
        $id = Auth::user()->organization_id;
        Organization_Activity::where('organization_id',$id)->update(['admin_access' => 0]);
        return back()->with('message', 'Admin review turned off');
    }
}