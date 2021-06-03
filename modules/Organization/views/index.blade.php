@extends('adminorg')

@section('admin')
<div class="flex row border-secondary rounded-lg ml-3 justify-content-between">
    <!-- Sessions to display success or failure -->
    <span>
        <h3 class="text-center bg-success text-light">{{session('message')}}</h3>
    </span>
    <span>
        <h3 class="text-center bg-danger text-light">{{session('danger')}}</h3>
    </span>
    <span>
        <!-- opens the create view -->
        <a href="/organization/create1" class="btn btn-info mr-4" role="button">Create Organization</a>
    </span>
    <table class="table table-light table-striped border-secondary rounded-lg mt-2 mr-4">
        <thead>
            <tr>
            <th scope="col">ID</th>
            <th scope="col">Title</th>
            <th scope="col">City</th>
            <th scope="col">Country</th>
            <th scope="col">Type</th>
            <th scope="col">Status</th>
            <th scope="col">More Details</th>
            <th scope="col">Edit Organization</th>
            <th scope="col">Disable</th>
            </tr>
        </thead>
        <tbody>
            @foreach($organization as $organization)
            <tr>
                <td>{{$organization->id}}</td>
                <td>{{$organization->title}}</td>
                <td>{{$organization->city}}</td>
                <td>{{$organization->country}}</td>

                <!-- If the organization isnt null display the name of the organization else display unassigned -->
                
                @if($organization->type_id == NULL)
                <td>Unassigned</td>
                @else
                <td>{{$organization->type->title}}</td>
                @endif
              
                @switch($organization->status)
                @case('0')
                <td>Inactive</td>
                @break;
                @case('1')
                <td>Active</td>
                @break;
                @endswitch

                 <!-- opens the more view -->
                 <td class="text-center"><a href="/organization/more/{{$organization->id}}" class="btn btn-outline-info mr-4" role="button">...</a></td>
                <!-- opent he edit view -->
                <td><a href="/organization/edit/{{$organization->id}}" class="btn btn-outline-warning" role="button">Edit</a></td>
           
                <td>
                    <button class="btn btn-outline-danger" onclick="event.preventDefault();
                            document.getElementById('form-delete-{{$organization->id}}').submit()">Delete</button>

                    <form id="{{'form-delete-'.$organization->id}}" style="display:none" method="post" action="/organization/delete/{{$organization->id}}">
                        @csrf
                        @method('delete');
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection