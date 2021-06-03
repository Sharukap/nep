@extends('home')

@section('cont')
<kbd><a href="/environment/updatedata" class="text-white font-weight-bolder"><i class="fas fa-chevron-left"></i></i> BACK</a></kbd>
<div class="container">
    <!-- FAQ button -->
    <div class="d-flex mb-2 justify-content-end">
        <span><a title="FAQ" style="font-size:24px;cursor:pointer;" data-toggle="modal" data-target="#ecoHelp"><i class="fa fa-info-circle" aria-hidden="true"></i></a></span>
    </div>
    @include('faq')
    <form action="/environment/newrequest" method="post">
        @csrf
        <div class="container">
            <div class="row justify-content-md-center p-4 bg-white">
                <h4 style="text-align:center;" class="text-dark">Add New Ecosystem</h4>
            </div>
            <div class="row justify-content-md-center p-4 bg-white">
                @if(\Session::has('success'))
                <div class="alert alert-success">
                    <p>{{\Session::get('success') }} </p>
                </div>
                @endif
            </div>
            <div class="row justify-content-md-center  p-4 bg-white">
                <div class="col border border-muted rounded-lg mr-2 p-4">
                    <h6 style="text-align:left;" class="text-dark">Eco-Systems Details</h6>
                    <br>
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input type="text" class="form-control" placeholder="Enter Title" id="title" name="title" value="{{ old('title') }}">
                    </div>
                    @error('title')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Type</span>
                        </div>
                        <select name="eco_type" class="custom-select @error('eco_type') is-invalid @enderror">
                            <option disabled selected value="">Select</option>
                            @foreach ($data as $page)
                            <option value="{{ $page->id }}">{{ $page->type }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('eco_type')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <div class="form-group">
                        <label for="province">District:</label>
                        <select class="custom-select @error('district') is-invalid @enderror" name="district">
                            <option disabled selected value="">Select</option>
                            @foreach ($districts as $district)
                            <option value="{{ $district->id }}" {{ Request::old()?(Request::old('district')==$district->id?'selected="selected"':''):'' }}>{{ $district->district }}</option>
                            @endforeach
                        </select>
                        @error('district')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea class="form-control" rows="5" name="description"></textarea>
                        @error('description')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <div id="mapid" style="height:400px;" name="map"></div>
                        @error('polygon')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="customCheck" value="1" name="isProtected">
                            <label class="custom-control-label" for="customCheck"><strong>Is Protected Area?</strong></label>
                        </div>
                        <input id="polygon" type="hidden" name="polygon" class="form-control @error('polygon') is-invalid @enderror" value="{{request('polygon')}}" />
                    </div>
                    <div class="form-group">
                        <label for="images">Image</label>
                        <div class="custom-file mb-3">
                            <input type="file" id="images" name="images">
                        </div>
                    </div>
                    <div style="float:right;">
                        <button type="submit" name="submit" class="btn bd-navbar text-white">Submit</button>
                    </div>
                    <input type="hidden" class="form-control" name="createby" value="{{Auth::user()->id}}">
                    <input type="hidden" class="form-control" name="status" value="0">
                </div>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    ///SCRIPT FOR THE MAP
    var center = [7.2906, 80.6337];

    // Create the map
    var map = L.map('mapid').setView(center, 10);

    // Set up the OSM layer 
    L.tileLayer(
        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Data © <a href="http://osm.org/copyright">OpenStreetMap</a>',
            maxZoom: 18
        }).addTo(map);

    var drawnItems = new L.FeatureGroup();
    map.addLayer(drawnItems);

    var drawControl = new L.Control.Draw({
        position: 'topright',
        draw: {
            polygon: {
                shapeOptions: {
                    color: 'purple'
                },
                allowIntersection: false,
                drawError: {
                    color: 'orange',
                    timeout: 1000
                },
                showArea: true,
                metric: false,
                repeatMode: true
            },
            polyline: {
                shapeOptions: {
                    color: 'red'
                },
            },
            circlemarker: false,
            rect: {
                shapeOptions: {
                    color: 'green'
                },
            },
            circle: false,
        },
        edit: {
            featureGroup: drawnItems
        }
    });
    map.addControl(drawControl);

    map.on('draw:created', function(e) {
        var type = e.layerType,
            layer = e.layer;

        if (type === 'marker') {
            layer.bindPopup('A popup!');
        }

        drawnItems.addLayer(layer);
        $('#polygon').val(JSON.stringify(layer.toGeoJSON()));
    });

    //SEARCH FUNCTIONALITY
    var searchControl = new L.esri.Controls.Geosearch().addTo(map);

    var results = new L.LayerGroup().addTo(map);

    searchControl.on('results', function(data) {
        results.clearLayers();
        for (var i = data.results.length - 1; i >= 0; i--) {
            results.addLayer(L.marker(data.results[i].latlng));
        }
    });

    setTimeout(function() {
        $('.pointer').fadeOut('slow');
    }, 3400);
</script>
@endsection