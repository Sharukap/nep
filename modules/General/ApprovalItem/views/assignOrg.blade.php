@extends('approvalItem::approval')

@section('approval')
<br>
<h5 >Assigning Organizations</h5>
<hr>
<div class="container">
    <div class="container">
        <div class="row p-4 bg-white">
            <h6>Change Assigned Organization</h6>
        </div>
    </div>
    <div class="container">
        <div class="row p-4 bg-white">
            <div class="col border border-muted rounded-lg mr-2 p-4">       
                <p>System registered Organizations</p>
                <table class="table  border-secondary rounded-lg mr-4">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Change</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($Organizations as $organization)
                        <tr>
                            <td>{{$organization->title}}</td>
                            <td><a href="/approval-item/changeassignOrganization/{{$organization->id}}/{{$process_item->id}}" class="text-dark">assign</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col border border-muted rounded-lg mr-2 p-4">
                <p>Non registered Organizations</p>
                <form action="\approval-item\changeassignOrganization" method="post">
                    @csrf
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Organization name" name="organization" value="{{ old('organization') }}"/>
                        @error('organization')
                            <div class="alert">                                   
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror 
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Organization email" name="email" value="{{ old('email') }}"/>
                        @error('email')
                            <div class="alert">                                   
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror 
                        <input type="hidden" class="form-control" name="create_by" value="{{ Auth::user()->id }}">
                        <input type="hidden" class="form-control" name="create_organization" value="{{ Auth::user()->organization_id }}">
                        <input type="hidden" class="form-control" name="process_id" value="{{ $process_item->id }}">
                    </div>
                    <div class="form-check">
                        <button type="submit" class="btn btn-primary" >Assign</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @if($process_item->form_type_id ==5 && $process_item->prerequisite_id != null)
            <div class="container">
                <div class="row p-4 bg-white">
                    <button type="submit" class="btn btn-primary" ><a href="/approval-item/assignorganization/{{$process_item->prerequisite_id}}" class="text-dark">Back to {{$process_item->prerequisite_process->form_type->type}}</a></button>
                </div>
            </div>
    @endif
</div>
@endsection