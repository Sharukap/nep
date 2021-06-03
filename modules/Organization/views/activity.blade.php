@extends('adminorg')

@section('admin')
<!-- Sessions to display success or failure -->
<span>
        <h3 class="text-center bg-success text-light">{{session('message')}}</h3>
    </span>
    <span>
        <h3 class="text-center bg-danger text-light">{{session('danger')}}</h3>
    </span>
   
<div class="container">
    <div class="row justify-content-md-center border p-4 bg-white">  
        <span>
        <!-- opens the create view -->
        <a href="/organization/newActivity" class="btn btn-info mr-4"  role="button">Assign new organization for handling applications</a>
        </span>
        <table class="table table-light table-striped border-secondary rounded-lg mt-2 mr-4">
            <thead>
                <tr>
                <th scope="col">Form</th>
                <th scope="col">Area of control</th>
                <th scope="col">Organization</th>
                <th scope="col">Admin review</th>
                <th scope="col">Remove</th>
                </tr>
            </thead>
            <tbody>
                @foreach($organizations as $organization)
                <tr>
                    <td>{{$organization->form_type->type}}</td>
                    @if($organization->district == null)
                    <td>{{$organization->province->province}} Province</td>
                    @elseif($organization->district_id == 26)
                    <td>{{$organization->district->district}}</td>
                    @else
                    <td>{{$organization->district->district}} District</td>
                    @endif
                    <td>{{$organization->organization->title}} {{$organization->organization->branch_type->title}}</td>
                    @if($organization->admin_access == 1)
                    <td>On</td>
                    @else
                    <td>Off</td>
                    @endif
                    <!-- opent he edit view -->
                    <td><a href="/organization/activityremove/{{$organization->id}}" class="btn btn-outline-warning" role="button">Remove</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
   
</div>

@endsection