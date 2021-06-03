@extends('home')

@section('cont')
<div>
    <kbd><a href="/user/index" class="text-white font-weight-bolder"><i class="fas fa-chevron-left"></i></i> BACK</a></kbd>
</div><br>
<div class="row">
    <div class="col-md-8">
        <h3 class="p-3 display-4">Activate Users</h3>
    </div>

    <!-- Search Bar and Buttons -->
    <div class="col-md-4 mt-5">
        <form action="/user/searchSelfRegistered" method="get">
            <div class="input-group">
                <input type="search" class="form-control" name="search" placeholder="Search Self Registered Users">
                <span class="form-group-button">
                    <button type="submit" class="btn btn-primary ml-2"><i class="fa fa-search" aria-hidden="true"></i></button>
                    <a class="btn btn-warning ml-1" href="/admin/showSelfRegistered"><i class="fa fa-retweet" aria-hidden="true"></i></a>
                </span>
            </div>
        </form>
    </div>
</div>
<hr>
<div class="flex row border-secondary rounded-lg ml-3">
    <span>
        <h5 class="p-3">User Details</h5>
    </span>


    <table class="table table-hover table-light border-secondary rounded-lg mr-4">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Organization</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>More Data</th>
                <th>Activate</th>
            </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{$user->id}}</td>
                <td>{{$user->name}}</td>
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

                <!-- Opens the more view -->
                <td><a href="/user/more/{{$user->id}}" class="btn btn-outline-info" role="button">...</a></td>

                <!-- opens the activate view -->
                <td><a href="/admin/showActivate/{{$user->id}}" class="btn btn-outline-success" role="button">Activate</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection