@extends('adminorg')

@section('admin')

<div class="flex row border-secondary rounded-lg ml-3 justify-content-between">
    <!-- Search Bar -->
    <div class="col-md-5">
        <form action="/user/searchUsers" method="get">
            <div class="input-group">
                <input type="search" class="form-control" name="search" placeholder="Search Users">
                <span class="form-group-button">
                    <button type="submit" class="btn btn-primary ml-2"><i class="fa fa-search" aria-hidden="true"></i></button>
                    <a class="btn btn-warning ml-1" href="/user/index"><i class="fa fa-retweet" aria-hidden="true"></i></a>
                </span>
            </div>
        </form>
    </div>

    <span>
        <!-- Only show the self registered users button if Admin or Super Admin -->
        @if (Auth::user()->role_id == 1 ||Auth::user()->role_id == 2)
        <!-- Opens the selfRegistered view -->
        <a href="/admin/showSelfRegistered" class="btn btn-success mr-4" role="button">Activate Users</a>
        @endif
        <!-- opens the create view -->
        <a href="/user/create" class="btn btn-info mr-4" role="button">Create User</a>
    </span>
    <table class="table table-hover table-light mt-2 mr-4">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Organization</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>More Data</th>
                <th>Edit User</th>
                <!-- Only show the Privilege and Delete fields if Admin or Super Admin -->
                @if (Auth::user()->role_id == 1 ||Auth::user()->role_id == 2)
                <th>Change Privilege</th>
                <th>Disable User</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{$user->id}}</td>
                <td>{{$user->name}}</td>
                <!-- If the organization isnt null display the name of the organization else display unassigned -->
                @if($user->organization == NULL)
                <td>Unassigned</td>
                @else
                <td>{{$user->organization->title}}</td>
                @endif
                <td>{{$user->email}}</td>
                @if($user->role == NULL)
                <td>Unassigned</td>
                @else
                <td>{{$user->role->title}}</td>
                @endif
                @switch($user->status)
                @case('0')
                <td>Inactive</td>
                @break;
                @case('1')
                <td>Active</td>
                @break;
                @endswitch
                <!-- opens the more view -->
                <td class="text-center"><a href="/user/more/{{$user->id}}" class="btn btn-outline-info mr-4" role="button">...</a></td>

                <!-- opent he edit view -->
                <td><a href="/user/edit/{{$user->id}}" class="btn btn-outline-warning" role="button">Edit</a></td>
                @if(Auth::user()->role_id == 1 ||Auth::user()->role_id == 2)
                <!-- opens the privilege view -->

                <td class="text-center"><a href="/admin/changePrivilege/{{$user->id}}" class="btn btn-outline-info" role="button">Privilege</a></td>
                <!-- Invisible form to delete a user and send a delete request to the controller -->
                <td>
                    <button class="btn btn-outline-danger" onclick="event.preventDefault();
                            document.getElementById('form-delete-{{$user->id}}').submit()">Delete</button>

                    <form id="{{'form-delete-'.$user->id}}" style="display:none" method="post" action="/admin/delete/{{$user->id}}">
                        @csrf
                        @method('delete');
                    </form>
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection