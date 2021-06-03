<?php


namespace Environment\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Env;
use App\Models\Env_type;
use App\Models\District;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class EnvController extends Controller
{
    public function show($id)           //show one record for moreinfo button
    {
        $ecosystems = Env::find($id);
        return view('environment/Envindex', compact('ecosystems', 'id'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'eco_type' => 'required',
            'description' => 'required',
            'polygon' => 'required',
            'district' => 'required',

        ]);
        $ecosystems = new Env;
        $ecosystems->type_id = $request->input('eco_type');
        if (request('isProtected')) {
            $ecosystems->protected_area = request('isProtected');
        }
        else{
            $ecosystems->protected_area = 0;
        }
        $ecosystems->district_id = $request->district;
        $ecosystems->title = $request->input('title');
        $ecosystems->polygon = request('polygon');
        $ecosystems->description = $request->input('description');
        $ecosystems->created_by_user_id = $request->input('createby');
        $ecosystems->status = $request->input('status');


        $ecosystems->save();


        return redirect('/environment/createrequest')->with('success', 'Data Added successfully');
    }

    public function index()
    {
        $ecosystems = Env::all();
        return view('environment::Envindex', compact('ecosystems', $ecosystems));
    }
    //Get eco system types from database.
    public function loadform()
    {

        $data = Env_type::all();
        $districts = District::all();
        return view('environment::request',[
            'districts' => $districts,
            'data' => $data,
        ]);
    }


    /* public function edit($id){
    $ecosystems= Env::select ('select * from eco_systems where id = ?',[$id]);

    return view('')

 */
    // Delete the request when the authorized parties click the delete button

    public function delete($id)
    {

        $ecosystems = Env::find($id);

        $ecosystems->delete();


        return redirect()->back()->with('success', 'Request  Successfully Deleted');
    }

    /* public function track()
    {

        $items = Env::where('created_by_user_id', '=', Auth::user()->id)->get();
        return view('environment::trackrequesteco')->with('items', $items);
    } */
    // Return the more view option. 
    public function more($id)
    {

        $ecosystems = Env::find($id);
        $polygon = Env::find($id)->polygon;
        return view('environment::moreeco', [
            'ecosystems' => $ecosystems,
            'polygon' => $polygon,
        ]);
    }
    // Update the status of the request when the authorized parties click the Approve button
    public function statusupdate(Request $request, $id)
    {
        $ecosystems = Env::find($id);
        $ecosystems->update([

            'status' => $request->status

        ]);

        return redirect()->back()->with('success', 'Request Approved Succesfully');
    }
}
