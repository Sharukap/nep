@extends('home')

@section('cont')

<kbd><a href="/approval-item/showRequests" class="text-white font-weight-bolder"><i class="fas fa-chevron-left"></i></i> BACK</a></kbd>
<hr>
<div class="container">

    <dl class="row">
        <dt class="col-sm-3">Plan Number:</dt>
        <dd class="col-sm-9">{{$land->title}}</dd>

        <dt class="col-sm-3">Surveyor Name:</dt>
        <dd class="col-sm-9">{{$land->surveyor_name}}</dd>

        <dt class="col-sm-3">Province:</dt>
        <dd class="col-sm-9">{{$land->province->province}}</dd>

        <dt class="col-sm-3">District:</dt>
        <dd class="col-sm-9">{{$land->district->district}}</dd>

        <dt class="col-sm-3">Grama Sevaka Division:</dt>
        <dd class="col-sm-9">{{$land->gs_division->gs_division}}</dd>

        <dt class="col-sm-3">Governing Organizations:</dt>
        <dd class="col-sm-9">
            <!-- if other type, then will show that instead -->
            {{$other_removal_requestor}}
            <ul class="list-unstyled">
                @foreach($land->governing_organizations as $governing_organization)
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
                @default
                <li>Other</li>
                @endswitch
                @endforeach
            </ul>
        </dd>

        <dt class="col-sm-3">Status:</dt>
        <dd class="col-sm-9">{{$land->status->type}}</dd>

        <dt class="col-sm-3 text-truncate">Created at:</dt>
        <dd class="col-sm-9">{{$land->created_at}}</dd>

        <dt class="col-sm-3">Active User:</dt>
        @if($process->activity_user_id == NULL)
        <dd class="col-sm-9">No User Assigned Yet</dd>
        @else
        <dd class="col-sm-9">{{$process->activity_user->name}}</dd>
        @endif
    </dl>
    <div class="container border border-dark border-rounded">
        <div id="mapid" style="height:400px;" name="map"></div>
    </div>
    @if($process->status_id < 2) <div style="float:right;">
        <button class="btn btn-outline-danger" onclick="if(confirm('Are you sure you wish to delete this request and all it\'s related data?')){ event.preventDefault();
                            document.getElementById('form-delete-{{$process->id}}').submit()}">Delete</button>

        <form id="{{'form-delete-'.$process->id}}" style="display:none" method="post" action="/land/delete/{{$land->id}}">
            @csrf
            @method('delete');
        </form>
</div>
@endif
</div>

<script>
    /// MAP MODULE
    var center = [7.2906, 80.6337];

    // Create the map
    var map = L.map('mapid').setView(center, 10);

    // Set up the OSM layer 
    L.tileLayer(
        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Data © <a href="http://osm.org/copyright">OpenStreetMap</a>',
            maxZoom: 18
        }).addTo(map);


    var polygon = @json($polygon);
    var layer = L.geoJSON(JSON.parse(polygon)).addTo(map);

    // Adjust map to show the kml
    var bounds = layer.getBounds();
    map.fitBounds(bounds);
</script>
@endsection