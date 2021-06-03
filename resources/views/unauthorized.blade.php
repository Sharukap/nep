@extends('home')

@section('cont')
<h3 class="p-3 display-4" style="display:inline">Dashboard</h3>
<span>
        <h3 class="text-center bg-success text-light">{{session('message')}}</h3>
    </span>
    <span>
        <h3 class="text-center bg-danger text-light">{{session('danger')}}</h3>
    </span>
    <span>
<nav class="navbar navbar-expand-lg navbar-light justify-content-around">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
</nav>
<div class="col-md">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-white text-center">Tree Removal Requests This Month</div>
                <div class="card-body text-center">
                    <p class="card-text display-4">{{$tree_removals}}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-white text-center">Development Project Requests This Month</div>
                <div class="card-body text-center">
                    <p class="card-text display-4">{{$dev_projects}}</p>
                </div>
            </div>
        </div>
    </div>
</div>

    
@endsection