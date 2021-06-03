@extends('home')

@section('cont')

<kbd><a href="/organization/index" class="text-white font-weight-bolder"><i class="fas fa-chevron-left"></i></i> BACK</a></kbd>
<div class="container">
    <!-- Sessions to display success or failure -->
    <span>
        <h3 class="text-center bg-success text-light">{{session('message')}}</h3>
    </span>
    <span>
        <h3 class="text-center bg-danger text-light">{{session('danger')}}</h3>
    </span>
    <span>
    <h2 style="text-align:center;" class="text-dark">Edit&nbsp;{{$organization->title}}</h2>
    <hr>
    <div class="row justify-content-md-center border p-4 bg-white">
        <div class="col-6 ml-3">
            <form method="post" action="/organization/update/{{$organization->id}}">

                <h5 style="text-align:left;" class="text-dark">Organization Details</h5>
                @csrf
                @method('patch')
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Title</span>
                    </div>
                    <!-- Fill in the input fields with the current values of the organization -->
                    <input type="text" class="form-control" name="title" value="{{$organization->title}}">
                </div>
                @error('title')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror

                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">City</span>
                    </div>
                    <input type="text" class="form-control" name="city" value="{{$organization->city}}">
                </div>
                @error('city')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror

                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Country</span>
                    </div>
                    <input type="text" class="form-control" name="country" value="{{$organization->country}}">
                </div>
                @error('country')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Type</span>
                    </div>
                    <select name="organization_type" class="custom-select">
                        @if($organization->type->title == NULL)
                        <option selected value="NULL">Select Organization</option>
                        @else
                        <option selected value="{{$organization->type->id}}">{{$organization->type->title}}</option>
                        @endif
                        @foreach($org_type as $org)
                        <option value="{{$org->id}}">{{$org->title}}</option>
                        @endforeach
                    </select>
                </div>
                @error('organization_type')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Description</span>
                    </div>
                    <input type="text" class="form-control" name="country" value="{{$organization->description}}">
                </div>
                @error('description')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <div style="float:right;">
                    <button type="submit" class="btn btn-warning">Update</button>
                </div>
            </form>
        </div>
    </div>
    <div class="row justify-content-md-center border p-4 bg-white">
        <div class="col-6 ml-3">
            <h5 style="text-align:left;" class="text-dark">Contact Details</h5>
            <br>
            <p>Primary contact</p>
            <table class="table table-light table-striped border-secondary rounded-lg mr-4">
                    <thead>
                        <tr>
                        <th scope="col">Type</th>
                        <th scope="col">Signature</th>
                    </thead>
                    <tbody>
                        @foreach ($contact as $key => $value)
                        @if($value->primary == 1)
                        <tr>
                            <td><input type="text" class="form-control" name="type" value="{{$value->type}}"></td>
                            <td><input type="text" class="form-control" name="contact_signature" value="{{$value->contact_signature}}"></td>
                           <td><input class="form-check-input" type="hidden" name='primary' id='1'  value=1 checked/></td>
                             <td><a href="/organization/contactremove/{{$value->id}}" class="btn btn-outline-warning" role="button">Remove</a></td>
                        </tr>
                        @endif
                        @endforeach
                         </tbody>
            </table>
            <br>
            <p>Other contacts</p>
            <table class="table table-light table-striped border-secondary rounded-lg mr-4">
                    <thead>
                        <tr>
                        <th scope="col">Type</th>
                        <th scope="col">Signature</th>
                    </thead>
                    <tbody>
                        @foreach ($contact as $key => $value)
                        @if($value->primary != 1)
                        <tr>
                            <td><input type="text" class="form-control" name="type" value="{{$value->type}}"></td>
                            <td><input type="text" class="form-control" name="contact_signature" value="{{$value->contact_signature}}"></td>
                           <td><input class="form-check-input" type="hidden" name='primary' id='1'  value=1 checked/></td>
                             <td><a href="/organization/contactremove/{{$value->id}}" class="btn btn-outline-warning" role="button">Remove</a></td>
                        </tr>
                        @endif
                        @endforeach
                         </tbody>
            </table>

            <p>Add new contact</p>
            <form method="post" action="/organization/contactupdate/{{$organization->id}}">
                @csrf
                <div class="form-check border-secondary rounded-lg mb-4" style="background-color:#ebeef0">
                    <label class="mt-2"> Add new Contact: </label>
                    <hr>
                     <div class="container">
                        <div class="row">
                            <div class="col-sm-4">
                                <select name="type" class="custom-select">
                                    <option disabled selected value >Contact Type</option>
                                    <option value ="1" >Mobile Phone</option>
                                    <option value ="2">Fixed Line</option>
                                    <option value ="3">Email</option>
                                    <option value ="4">Address</option>
                                </select>
                                @error('type')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-sm-6 pl-0 pr-0 ml-0 mr-0">
                                <input type='text' class="form-control" name='contact_signature' id='contact_signature' placeholder="Details"/>
                            </div>
                            <div class="col-sm pl-4 pr-0 ml-0 mr-0 form-check">
                                <input class="form-check-input" type="checkbox" name='primary' id='1'  value=1 />&nbsp<label for='primary'>Primary</label>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div style="float:right;">
                    <button type="submit" name="status" value ="1" class="btn btn-warning">Add</button>
                </div>
            </form>
        </div>
    </div>
   
</div>

@endsection