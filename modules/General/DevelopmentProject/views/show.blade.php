@extends('home')

@section('cont')

<kbd><a href="/approval-item/showRequests" class="text-white font-weight-bolder"><i class="fas fa-chevron-left"></i></i> BACK</a></kbd>
<hr>
<div class="container">
    <dl class="row">
        <dt class="col-sm-3">Title:</dt>
        <dd class="col-sm-9">{{$development_project->title}}</dd>

        <dt class="col-sm-3">Province:</dt>
        <dd class="col-sm-9">{{$land->province->province}}</dd>

        <dt class="col-sm-3">District:</dt>
        <dd class="col-sm-9">{{$land->district->district}}</dd>

        <dt class="col-sm-3">Grama Sevaka Division:</dt>
        <dd class="col-sm-9">{{$land->gs_division->gs_division}}</dd>

        <dt class="col-sm-3">Category:</dt>
        <dd class="col-sm-9">Development Project</dd>

        <dt class="col-sm-3">Gazette:</dt>
        <dd class="col-sm-9">{{$development_project->gazette->title}}</dd>

        <dt class="col-sm-3">Governing Organizations:</dt>
        <dd class="col-sm-9">
            <ul class="list-unstyled">
                @foreach($development_project->governing_organizations as $governing_organization)
                @switch($governing_organization)
                @case(1)
                <li>Reforest Sri Lanka</li>
                @break
                @case(2)
                <li>Ministry of Environment</li>
                @break
                @case(3)
                <li>Central Environmental Authority</li>
                @break
                @case(4)
                <li>Ministry of Wildlife</li>
                @break
                @case(5)
                <li>Road Development Authority</li>
                @break
                @endswitch
                @endforeach
            </ul>
        </dd>

        <dt class="col-sm-3">Request Org:</dt>
        <!-- @if($process->request_organization) -->
        <dd class="col-sm-9">{{$process->requesting_organization->title}}</dd>
        <!-- @else
        <dd class="col-sm-9">{{$process->other_land_owner_name}}</dd>
        @endif -->

        <dt class="col-sm-3">Request to Org:</dt>
        <!-- @if($process->activity_organization) -->
        <dd class="col-sm-9">{{$process->Activity_organization->title}}</dd>
        <!-- @else
        <dd class="col-sm-9">{{$process->other_removal_requestor_name}}</dd>
        @endif -->

        <dt class="col-sm-3">Logs:</dt>
        @if($development_project->logs == 0)
        <dd class="col-sm-9">No Logs</dd>
        @else
        <dd class="col-sm-9">{{$development_project->logs}}</dd>
        @endif

        <dt class="col-sm-3">Plan Number:</dt>
        <dd class="col-sm-9">{{$development_project->land_parcel->title}}</dd>

        <dt class="col-sm-3">Surveyor Name:</dt>
        <dd class="col-sm-9">{{$development_project->land_parcel->surveyor_name}}</dd>

        <dt class="col-sm-3">Status:</dt>
        <dd class="col-sm-9">{{$development_project->status->type}}</dd>

        <dt class="col-sm-3">Created at:</dt>
        <dd class="col-sm-9">{{$development_project->created_at}}</dd>

        <dt class="col-sm-3">Active User:</dt>
        @if($process->activity_user_id == NULL)
        <dd class="col-sm-9">No User Assigned Yet</dd>
        @else
        <dd class="col-sm-9">{{$process->activity_user->name}}</dd>
        @endif
    </dl>
    <div class="border border-dark border-rounded">
        <div id="mapid" style="height:400px;" name="map"></div>
    </div>
    @if($process->status_id < 2) 
    <div class="mt-3" style="float:right;">
        <!-- <a class="btn btn-outline-warning" href="/dev-project/edit/{{$process->id}}/{{$development_project->id}}/{{$land->id}}">Edit</a> -->
        <button class="btn btn-outline-danger" onclick="if (confirm('Are you sure you wish to delete this request and all it\'s related data?')){
            event.preventDefault();
            document.getElementById('form-delete-{{$process->id}}').submit()}">Delete</button>

        <form id="{{'form-delete-'.$process->id}}" style="display:none" method="post" action="/dev-project/delete/{{$process->id}}/{{$development_project->id}}/{{$land->id}}">
            @csrf
            @method('delete');
        </form>
</div>
@endif
</div>



<script type="text/javascript">
    var center = [7.2906, 80.6337];

    // Create the map
    var map = L.map('mapid').setView(center, 10);

    // Set up the OSM layer 
    L.tileLayer(
        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Data © <a href="http://osm.org/copyright">OpenStreetMap</a>',
            maxZoom: 18
        }).addTo(map);


    //FROM LARAVEL THE COORDINATES ARE BEING TAKEN TO THE SCRIPT AND CONVERTED TO JSON
    var polygon = @json($polygon);
    console.log(polygon);

    //ADDING THE JSOON COORDINATES TO MAP
    var layer = L.geoJSON(JSON.parse(polygon)).addTo(map);

    // Adjust map to show the kml
    var bounds = layer.getBounds();
    map.fitBounds(bounds);
</script>
@endsection