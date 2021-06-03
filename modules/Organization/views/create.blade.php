@extends('home')
@section('cont')
<kbd><a href="/organization/index" class="text-white font-weight-bolder"><i class="fas fa-chevron-left"></i></i> BACK</a></kbd>
<div class="container">
   <h2 style="text-align:center;" class="text-dark">Create Organization</h2>
   <hr>
   <div class="row justify-content-md-center border p-4 bg-white">
      <div class="col-6 ml-3">
         <!--Organizaion Details -->        
         <h6 style="text-align:left;" class="text-dark">Organization Details</h6>
         <hr>
         <form method="post" action="/organization/store">
            @csrf
            <!-- Title. -->
            <div class="input-group mb-3">
               <div class="input-group-prepend">
                  <span class="input-group-text">Organization Name</span>
               </div>
               <input type="text" class="form-control" name="title" placeholder="Enter name" required value="{{ old('title') }}">
            </div>
            @error('title')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <!-- City. -->
            <div class="input-group mb-3">
               <div class="input-group-prepend">
                  <span class="input-group-text">City</span>
               </div>
               <input type="text" class="form-control" name="city" placeholder="Enter City" required value="{{ old('city') }}">
            </div>
            @error('city')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <!-- Select Organization Type. -->
            <div class="input-group mb-3">
               <div class="input-group-prepend">
                  <span class="input-group-text">Organization Type</span>
               </diV>
               <select name="organization_type" class="custom-select" required>
                  <option disabled selected>Organization Type</option>
                  @foreach ($org_types as $org_type)
                  <option value="{{ $org_type->id }}"{{ Request::old()?(Request::old('organization_type')==$org_type->id?'selected="selected"':''):'' }}>{{ $org_type->title }}</option>
                  @endforeach
               </select>
            </div>
            @error('type')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
             <!-- Select Branch Type. -->
             <div class="input-group mb-3">
               <div class="input-group-prepend">
                  <span class="input-group-text">Branch Type</span>
               </diV>
               <select name="branch_type" class="custom-select" required>
                  <option disabled selected>Branch Type</option>
                  @foreach ($branches as $branch)
                  <option value="{{ $branch->id }}"{{ Request::old()?(Request::old('branch_type')==$branch->id?'selected="selected"':''):'' }}>{{ $branch->title }}</option>
                  @endforeach
               </select>
            </div>
            @error('branch_type')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <!-- Description field. -->
            <div class="input-group mb-3">
               <div class="input-group-prepend">
                  <span class="input-group-text">Description</span>
               </div>
               <textarea  class="form-control" rows="3" name="description" placeholder="Enter Description">{{ old('description') }}</textarea>
               
            </div>
            @error('description')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <br>
            <!--Contact Details. -->
            <h6 style="text-align:left;" class="text-dark">Contact Details</h6>
            <br>
            <div class="input-group mb-3">
               <div class="input-group-prepend">
                     <select name="type" class="custom-select" required>
                        <option disabled selected value >Primary Contact Type</option>
                        @foreach($contact_types as $contact_type)
                        <option value="{{ $contact_type->id }}"{{ Request::old()?(Request::old('type')==$contact_type->id?'selected="selected"':''):'' }}>{{ $contact_type->title }}</option>
                        @endforeach
                     </select>
               </div>
               <input type="text" class="form-control" name="contact" placeholder="Enter contact" required  value="{{ old('contact') }}">
            </div>
            @error('type')
                     <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            @error('contact')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <div class="input-group mb-3">
               <div class="input-group-prepend">
                  <span class="input-group-text">Address</span>
               </div>
               <input type="text" class="form-control" name="address" placeholder="Enter address" required  value="{{ old('address') }}">
            </div>
            @error('address')
               <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <br>
            
            <!-- End Select Contact Type. -->
            <!--pass in the user's organization id as well -->
            <input type="hidden" class="form-control" name="created_by" value="{{Auth::user()->id}}"/>
            <div style="float:right;">
               <button type="submit" name="status" value="1" class="btn btn-primary">Create</button>
            </div>
         </form>
      </div>
   </div>
</div>
@endsection
