@extends('home')

@section('cont')
<kbd><a href="/environment/updatedataspecies" class="text-white font-weight-bolder"><i class="fas fa-chevron-left"></i></i> BACK</a></kbd>
<div class="container">
    <!-- FAQ button -->
    <div class="d-flex mb-2 justify-content-end">
        <span><a title="FAQ" style="font-size:24px;cursor:pointer;" data-toggle="modal" data-target="#speciesHelp"><i class="fa fa-info-circle" aria-hidden="true"></i></a></span>
    </div>
    @include('faq')
    <h4 style="text-align:center;" class="text-dark">Add New Species</h4>
    <hr>
    <div class="row justify-content-md-center border p-4 bg-white">
        <div class="col-lg ml-3">

            <h6 style="text-align:left;" class="text-dark">Species Details</h6>
            <hr>
            <form action='/environment/newspecies' method="post">
                @csrf

                @if(\Session::has('success'))
                <div class="alert alert-success">
                    <p>{{\Session::get('success') }} </p>

                </div>
                @endif

                <div class="row border rounded-lg p-4 bg-white">
                    <div class="col border border-muted rounded-lg mr-2 p-2">
                        <div class="form-group">
                            <label for="number_of_tree_species">Species Type</label>
                            <input type="text" class="form-control" name="type" placeholder="Enter Type">
                            @error('type')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="number_of_tree_species">Species Title</label>
                            <input type="text" class="form-control" name="title" placeholder="Enter Title">
                            @error('title')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>


                        </br>
                        <h6>Scientific Name</h6>
                        <div class="form-group">
                            <input type="text" name="scientific_name" class="form-control" placeholder="Enter name">
                            @error('scientific_name')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        </br>
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

                        <div id="accordion" class="mb-3">
                            <div class="card mb-3">
                                <div class="card-header bg-white">
                                    <a class="collapsed card-link text-dark" data-toggle="collapse" href="#collapseone">
                                        Taxanomy
                                    </a>
                                </div>
                                <div id="collapseone" class="collapse" data-parent="#accordion">
                                    <div class="card-body">
                                        <strong>Select Multiple</strong>
                                        <fieldset>
                                            <input type="checkbox" name="taxanomy[]" value="Clone"><label class="ml-2">Clone</label> <br>
                                            <input type="checkbox" name="taxanomy[]" value="Domain"><label class="ml-2">Domain</label> <br>
                                            <input type="checkbox" name="taxanomy[]" value="Species"><label class="ml-2">Species</label> <br>
                                            <input type="checkbox" name="taxanomy[]" value="Kingdom"><label class="ml-2">Kingdom</label> <br>
                                            <input type="checkbox" name="taxanomy[]" value="Phylum"><label class="ml-2">Phylum</label> <br>
                                        </fieldset>
                                    </div>
                                </div>
                                @error('taxanomy')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="card">
                                <div class="card-header bg-white">
                                    <a class="collapsed card-link text-dark" data-toggle="collapse" href="#collapsetwo">
                                        Habitats
                                    </a>
                                </div>
                                <div id="collapsetwo" class="collapse" data-parent="#accordion">
                                    <div class="card-body">
                                        <strong>Select Multiple</strong>
                                        <fieldset>
                                            <input type="checkbox" name="habitat[]" value="Montane forest"><label class="ml-2">Montane forest</label> <br>
                                            <input type="checkbox" name="habitat[]" value="Sub-Montane forest"><label class="ml-2">Sub-Montane forest</label> <br>
                                            <input type="checkbox" name="habitat[]" value="Low land wet evergreen forest"><label class="ml-2">Low land wet evergreen forest</label> <br>
                                            <input type="checkbox" name="habitat[]" value="Dry mixed evergreen forest"><label class="ml-2">Dry mixed evergreen forest</label> <br>
                                        </fieldset>
                                    </div>
                                </div>
                                @error('habitat')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <label>Project Description</label>
                        <textarea class="form-control" rows="5" name="description"></textarea>
                        @error('description')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        </br>
                        </br>

                        </br>
                        <div class="col border border-muted rounded-lg p-4">
                            <!-- ////////MAP GOES HERE -->
                            <div id="mapid" style="height:400px;" name="map"></div>

                            @error('polygon')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <input id="polygon" type="hidden" name="polygon" class="form-control @error('polygon') is-invalid @enderror" value="{{request('polygon')}}" /> <br>


                        </div>


                        <div class="p-3" style="float:right;">
                            <button type="submit" name="submit" class="btn bd-navbar text-white">Submit</button>
                        </div>


                        <input type="hidden" class="form-control" name="createby" value="{{Auth::user()->id}}">
                        <input type="hidden" class="form-control" name="status" value="0">
                    </div>
                </div>
        </div>
        </form>
    </div>
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