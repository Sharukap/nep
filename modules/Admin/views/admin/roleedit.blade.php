@extends('adminorg')

@section('admin')

<div class="container">
    <!-- Sessions to display success or failure -->
    <span>
        <h3 class="text-center bg-success text-light">{{session('message')}}</h3>
    </span>
    <span>
        <h3 class="text-center bg-danger text-light">{{session('danger')}}</h3>
    </span>
    <span>
    <div class="row content-md-center border p-4 bg-white">
        <div class="col-6 ml-3">
        <h4 style="text-align:center;" class="text-dark">Edit Role access</h4>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                        <span class="input-group-text">ID</span>
                </div>
                <input type="text" class="form-control" name="id" value="{{$role->id}}" readonly>
            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                        <span class="input-group-text">Role Title</span>
                </div>
                <input type="text" class="form-control" name="id" value="{{$role->title}}" readonly>
            </div>
            <div class="form-check border-secondary rounded-lg mb-4" style="background-color:#ebeef0">
                    <label class="mt-2"> Modules currently Allowed: </label>
                    <hr>
                    <table>
                    @foreach($roleaccesses as $roleaccess)
                        <tr>
                            <td>{{$roleaccess->access->access}}</td>
                            <td><a href="/admin/removeAccess/{{$roleaccess->id}}" class="btn btn-outline-warning" role="button">Remove</a></td>
                        </td>
                    @endforeach
                    </table>
                </div> 
            <form method="post" action="/admin/rolePriviledge/{{$role->id}}">
                @csrf
                <div class="form-check border-secondary rounded-lg mb-4" style="background-color:#ebeef0">
                    <label class="mt-2"> Add new module access: </label>
                    <hr>
                    <fieldset>
                    @foreach($accesses as $access)
                        <input type="checkbox" name="modules[]" value="{{$access->id}}" checked><label class="ml-2">{{$access->access}}</label> <br>
                    @endforeach
                    </fieldset>
                </div> 
                <div style="float:right;">
                    <button type="submit" class="btn btn-warning">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection