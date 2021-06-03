@extends('general')

@section('general')
<hr>
<div class="row justify-content-center">
    <div class="col-md-3">
        <div class="card">
            <div class="card-header bg-white text-center">Tree Removals This Month</div>
            <div class="card-body text-center">
                <p class="card-text display-4">{{$tree_removals}}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-header bg-white text-center">Development Projects This Month</div>
            <div class="card-body text-center">
                <p class="card-text display-4">{{$dev_projects}}</p>
            </div>
        </div>
    </div>
</div>
<hr>
<div class="row border-secondary rounded-lg ml-3">
    @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 2)
    <h5 class="p-3">New requests to confirm Organization assigning</h5>
    @elseif(Auth::user()->role_id == 3 || Auth::user()->role_id == 4 )
    <h5 class="p-3">New requests to be assigned to staff</h5>
    @elseif(Auth::user()->role_id == 5) 
    <h5 class="p-3">Applications to be investigated</h5>
    @endif
</div>
<form action="/general/filterItems" method="get">
    @csrf
    <div class="row justify-content-center">
        <div class="col-md-4">
            <select name="form_type" class="custom-select" required>
                <option value="0" selected>Select</option>
                <option value="1">Tree Cutting permission Requests</option>
                <option value="2">Development project permission Requests</option>
                <option value="4">Crime Reports</option>
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </div>
</form>

</br>
<div class="row border-secondary rounded-lg ml-3">
    <table class="table table-striped mr-4">
        <thead>
            <tr>
                <th>Category</th>
                <th>Date Submitted</th>
                @if(Auth::user()->role_id == 6)
                <th>Status</th>
                @else
                <th>Requested_by</th>
                @endif
                <th>Remark</th>
                @if(Auth::user()->role_id !== 6)
                <th>Check and Assign</th>
                @else
                <th>Check Progress</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($Process_items as $process_item)<tr>
            @if($process_item->prerequisite_id == null )
                <td>{{$process_item->form_type->type}}</td>
                <td>{{date('d-m-Y',strtotime($process_item->created_at))}}</td>
                @if($process_item->request_organization==null && $process_item->other_land_owner_name==null)
                <td>{{$process_item->created_by_user->name}}</td>
                @elseif($process_item->request_organization==null)
                <td>{{$process_item->other_land_owner_name}}</td>
                @else
                <td>{{$process_item->requesting_organization->title}}</td>
                @endif
                <td>{{$process_item->remark}}</td>
                @if(Auth::user()->role_id == 1 ||Auth::user()->role_id == 2)
                <td><a href="/approval-item/assignorganization/{{$process_item->id}}" class="text-muted">Assign</a></td>
                @elseif(Auth::user()->role_id == 3 ||Auth::user()->role_id == 4)
                <td><a href="/approval-item/assignstaff/{{$process_item->id}}" class="text-muted">Assign</a></td>
                @elseif(Auth::user()->role_id == 5)
                <td><a href="/approval-item/investigate/{{$process_item->id}}" class="text-muted">Investigate</a></td>
                @elseif(Auth::user()->role_id == 6)
                <td><a href="#" class="text-muted">View More Details</a></td>
                @endif
            @endif
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection