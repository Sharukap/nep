<?php

namespace Environment\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Species;
use App\Models\District;
use App\Models\Organization;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SpeciesController extends Controller
{
    // Load the form to enter data of the newly found species
    public function form()
    {
        $organization = Organization::all();
        $districts = District::all();
        return view('environment::species', [
            'org' => $organization,
            'districts' => $districts
        ]);
    }
    // Store the data in the database
    public function store(Request $request)
    {

        $request->validate([
            'type' => 'required',
            'title' => 'required',
            'scientific_name' => 'required',
            'habitat' => 'required',
            'taxanomy' => 'required',
            'description' => 'required',
            'createby' => 'required',
            'polygon' => 'required',
            'district' => 'required',
        ]);
        $species = new Species;
        $species->type = $request->input('type');
        $species->title = $request->input('title');
        $species->scientefic_name = $request->input('scientific_name');
        $species->habitats = $request->input('habitat');
        $species->taxa = $request->input('taxanomy');
        $species->polygon = request('polygon');
        $species->description = $request->input('description');
        $species->status_id = $request->input('status');
        $species->district_id = $request->input('district');
        $species->created_by_user_id = $request->input('createby');
        $species->save();



        return redirect('/environment/requestspecies')->with('success', 'Data Added successfully');
    }

    /* 
    public function track(Request $request)
    {
        $id = $request['create_by'];
        $species = Species::all()->where('created_by_user_id', $id)->toArray();
        return view('environment::trackrequest');
    } */
    // Return the main view
    public function index()
    {
        $species = Species::all();
        return view('environment::Spcindex', compact('species', $species));
    }


    /* public function showRequest()
    {

        $items = Species::where('created_by_user_id', '=', Auth::user()->id)->get();
        return view('environment::trackrequest', [
            'items' => $items,
        ]);
    } */
    // Show the index page with the user request details
    public function show($id)           //show one record for moreinfo button
    {
        $items = Species::find($id);
        return view('environment/Spcindex', compact('species', 'id'));
    }
    // Return more view.
    public function more($id)
    {

        $species = Species::find($id);
        $polygon = Species::find($id)->polygon;
        return view('environment::morespecies',[
            'species' => $species,
            'polygon' => $polygon,
            ]);
        return view('environment::morespecies', compact('species', $species));
    }
    public function delete($id)
    {

        $items = Species::find($id);

        $items->delete();
        return redirect()->back()->with('success', 'Request  Successfully Deleted');
    }



    public function statusupdate(Request $request, $id)
    {
        $species = Species::find($id);
        $species->update([

            'status_id' => $request->status

        ]);

        return redirect()->back()->with('success', 'Request Approved Succesfully');
    }
}
