@extends('home')

@section('cont')

<kbd><a href="/approval-item/showRequests" class="text-white font-weight-bolder"><i class="fas fa-chevron-left"></i></i> BACK</a></kbd>
<hr>
<div class="container">
    <div class="row">
        <div class="col border border-muted rounded-lg mr-2 p-2">
            <dl class="row">
                <dt class="col-sm-3">Province:</dt>
                <dd class="col-sm-9">{{$land->province->province}}</dd>

                <dt class="col-sm-3">District:</dt>
                <dd class="col-sm-9">{{$land->district->district}}</dd>

                <dt class="col-sm-3">Grama Sevaka Division:</dt>
                <dd class="col-sm-9">{{$land->gs_division->gs_division}}</dd>

                <dt class="col-sm-3">Description:</dt>
                <dd class="col-sm-9">
                    <p>{{$tree->description}}</p>
                </dd>

                <dt class="col-sm-3">Governing Organizations:</dt>
                <dd class="col-sm-9">
                    <!-- if other type, then will show that instead -->
                    {{$other_removal_requestor}}

                    <ul class="list-unstyled">
                        @foreach($tree->governing_organizations as $governing_organization)
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
                @if($process->request_organization)
                <dd class="col-sm-9">{{$process->requesting_organization->title}}</dd>
                @else
                <dd class="col-sm-9">{{$process->other_land_owner_name}} (External)</dd>
                @endif

                <dt class="col-sm-3">Request to Org:</dt>
                @if($process->activity_organization)
                <dd class="col-sm-9">{{$process->Activity_organization->title}}</dd>
                @else
                <dd class="col-sm-9">{{$process->other_removal_requestor_name}} (External)</dd>
                @endif

                <dt class="col-sm-3">Category:</dt>
                <dd class="col-sm-9">Tree Removal</dd>

                <dt class="col-sm-3">Land Size:</dt>
                <dd class="col-sm-9">{{$tree->land_size}} {{$tree->land_size_unit}}</dd>

                <dt class="col-sm-3">Number of Trees:</dt>
                <dd class="col-sm-9">{{$tree->no_of_trees}}</dd>

                <dt class="col-sm-3">Number of Tree Species:</dt>
                <dd class="col-sm-9">{{$tree->no_of_tree_species}}</dd>

                <dt class="col-sm-3">Number of Mammal Species:</dt>
                <dd class="col-sm-9">{{$tree->no_of_mammal_species}}</dd>

                <dt class="col-sm-3">Number of Amphibian Species:</dt>
                <dd class="col-sm-9">{{$tree->no_of_amphibian_species}}</dd>

                <dt class="col-sm-3">Number of Reptile Species:</dt>
                <dd class="col-sm-9">{{$tree->no_of_reptile_species}}</dd>

                <dt class="col-sm-3">Number of Avian Species:</dt>
                <dd class="col-sm-9">{{$tree->no_of_avian_species}}</dd>

                <dt class="col-sm-3">Number of Floral Species:</dt>
                <dd class="col-sm-9">{{$tree->no_of_flora_species}}</dd>

                <dt class="col-sm-3">Species Special Notes:</dt>
                <dd class="col-sm-9">
                    <p>{{$tree->species_special_notes}}</p>
                </dd>

                <dt class="col-sm-3">Special Approval:</dt>
                <dd class="col-sm-9">{{$tree->special_approval}}</dd>

                <dt class="col-sm-3">Plan Number:</dt>
                <dd class="col-sm-9">{{$tree->land_parcel->title}}</dd>

                <dt class="col-sm-3">Surveyor Name:</dt>
                <dd class="col-sm-9">{{$tree->land_parcel->surveyor_name}}</dd>

                <dt class="col-sm-3">Status:</dt>
                <dd class="col-sm-9">{{$tree->status->type}}</dd>

                <dt class="col-sm-3">Created at:</dt>
                <dd class="col-sm-9">{{$tree->created_at}}</dd>

                <dt class="col-sm-3">Active User:</dt>
                @if($process->activity_user_id == NULL)
                <dd class="col-sm-9">No User Assigned Yet</dd>
                @else
                <dd class="col-sm-9">{{$process->activity_user->name}}</dd>
                @endif
        </div>
        <div class="col border border-muted rounded-lg mr-2 p-2">
            <dt class="col-sm-3">Properties</dt>
            <hr>
            @if($location==0)
            <dd class="col-sm-9">No Properties</dd>
            @else
            @for($x = 0; $x < count($location); $x++) <dd class="col-sm-9">
                <dl class="row">
                    <dt class="col-sm-7">Tree Species ID:</dt>
                    <dd class="col-sm-5">{{$location[$x]['tree_species_id']}}</dd>

                    <dt class="col-sm-7">Tree ID:</dt>
                    <dd class="col-sm-5">{{$location[$x]['tree_id']}}</dd>

                    <dt class="col-sm-7">Diameter at Breast Height:</dt>
                    <dd class="col-sm-5">{{$location[$x]['diameter_at_breast_height']}}</dd>

                    <dt class="col-sm-7">Diameter of Stump</dt>
                    <dd class="col-sm-5">{{$location[$x]['diameter_at_stump']}}</dd>

                    <dt class="col-sm-7">Height:</dt>
                    <dd class="col-sm-5">{{$location[$x]['height']}}</dd>

                    <dt class="col-sm-7">Timber Volume:</dt>
                    <dd class="col-sm-5">{{$location[$x]['timber_volume']}}</dd>

                    <dt class="col-sm-7">Cubic Feet:</dt>
                    <dd class="col-sm-5">{{$location[$x]['timber_cubic']}}</dd>

                    <dt class="col-sm-7">Age</dt>
                    <dd class="col-sm-5">{{$location[$x]['age']}}</dd>

                    <dt class="col-7">Remarks</dt>
                    <dd class="col-5">{{$location[$x]['remark']}}</dd>
                </dl>
                </dd>
                @endfor
                @endif
                </dl>
        </div>
    </div>



    <div class="border border-dark border-rounded">
        <div id="mapid" style="height:400px;" name="map"></div>
    </div>
    <div class="row">
        @isset($Photos)
        <div class="row p-4 bg-white">
            <div class="card-deck">
                @foreach($Photos as $photo)
                <div class="card" style="background-color:#99A3A4">
                    <img class="card-img-top" src="{{asset('/storage/'.$photo)}}" alt="photo">
                    <div class="card-body text-center">
                        <a class="nav-link text-dark font-italic p-2" href="/crime-report/downloadimage/{{$photo}}">Download Image</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endisset
        @empty($Photos)
        <p>No photos included in the application</p>
        @endempty
    </div>
    @if($process->status_id < 2) 
    <div style="float:right;">
        <button class="btn btn-outline-danger" onclick="if(confirm('Are you sure you wish to delete this request and all it\'s related data?')){ event.preventDefault();
                            document.getElementById('form-delete-{{$process->id}}').submit()}">Delete</button>

        <form id="{{'form-delete-'.$process->id}}" style="display:none" method="post" action="/tree-removal/delete/{{$process->id}}/{{$tree->id}}/{{$land->id}}">
            @csrf
            @method('delete');
        </form>
</div>
@endif
</div>

<script>
    /// BACK BUTTON
    function goBack() {
        window.history.back();
    }

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