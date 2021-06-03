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
    <table class="table table-hover table-light mt-2 mr-4">
        <thead>
            <tr>
                <th>ID</th>
                <th>Role Name</th>
                <th>Update Role Privileges</th>
            </tr>
        </thead>
        <tbody>
            @foreach($roles as $role)
            @if($role->id > Auth::user()->role_id)
            <tr>
                <td>{{$role->id}}</td>
                <td>{{$role->title}}</td>
                
                <!-- opent he edit view -->
                <td><a href="/admin/roleedit/{{$role->id}}" class="btn btn-outline-warning" role="button">Edit</a></td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>
</div>
@endsection