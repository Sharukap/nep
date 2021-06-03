@extends('home')

@section('cont')

<kbd><a href="/organization/index" class="text-white font-weight-bolder"><i class="fas fa-chevron-left"></i></i> BACK</a></kbd>

<div class="container">
    <h2 style="text-align:center;" class="text-dark">Details of {{$organization->title}}</h2><hr>
    <div class="row justify-content-md-center border p-4 bg-white">
        <div class="col-8">
            <form>
            <h6 style="text-align:left;" class="text-dark">Organization Details</h6>
           <hr>
           
            
            <div class="row">
                <div class="col">
                <strong>Organization Name :</strong>
                </div>
                <div class="col">
                {{ $organization->title }}
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col">
                <strong>City :</strong>
                </div>
                <div class="col">
                {{ $organization->city }}
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col">
                <strong>Organization Type :</strong>
                </div>
                @if($organization->type_id == NULL)
                <div class="col">
                    Not assigned
                </div> 
                @else
                <div class="col">
                    {{$organization->type->title}}
                </div> 
                @endif
            </div>
            <br>
            <div class="row">
                <div class="col">
                <strong>Organization Branch Type :</strong>
                </div>
                @if($organization->branch_type_id == NULL)
                <div class="col">
                    Not specified
                </div> 
                @else
                <div class="col">
                    {{$organization->branch_type->title}}
                </div> 
                @endif
            </div>
            <br>
            <div class="row">
                <div class="col">
                <strong>Country :</strong>
                </div>
                <div class="col">
                {{ $organization->country}}
                </div> 
            </div>
            <br>
            <div class="row">
                <div class="col">
                <strong>Status :</strong>
                </div>
                <div class="col">
                    @switch($organization->status)
                    @case('0')
                    <td>Inactive</td>
                    @break;
                    @case('1')
                    <td>Active</td>
                    @break;
                    @endswitch
    
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col">
                <strong>Description :</strong>
                </div>
                <div class="col">
                {{ $organization->description }}
                </div> 
            </div>
            <br>
            <br>
            <h6 style="text-align:left;" class="text-dark">Contact Details</h6>
       
            <hr>

            <table class="table table-light table-striped border-secondary rounded-lg mr-4">
                <thead>
                    <tr>
                   
                    <th scope="col">Contact Type</th>
                    <th scope="col">Value</th>
                </thead>
                <tbody>
                    @foreach ($contact as $key => $value)
                    <tr>
                  
                        <td>{{$value->type}}</td>
                        <td>{{$value->contact_signature}}</td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
            <br>
            </form>
        </div>
    </div>
</div>

@endsection