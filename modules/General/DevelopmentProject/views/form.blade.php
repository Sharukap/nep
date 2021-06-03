@extends('general')

@section('general')

<div class="container">
    <!-- FAQ button -->
    <div class="d-flex mb-2 justify-content-end">
        <span><a title="FAQ" style="font-size:24px;cursor:pointer;" data-toggle="modal" data-target="#devHelp"><i class="fa fa-info-circle" aria-hidden="true"></i></a></span>
    </div>
    @include('faq')
    <form action="/dev-project/saveForm" method="post">
        @csrf

        <div class="container">
            <div class="row p-4 bg-white">
                <div class="col border border-muted rounded-lg mr-2 p-4">
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input type="text" class="form-control" placeholder="Enter Title" id="title" name="title" value="{{ old('title') }}">
                        @error('title')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        Gazette:<input type="text" class="form-control typeahead" placeholder="Search" name="gazette" value="{{ old('gazette') }}" />
                        @error('gazette')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        Forward to Organization (this will override auto assign):<input type="text" class="form-control typeahead3" placeholder="Search" name="organization" value="{{ old('organization') }}" />
                        @error('organization')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="title">Plan Number:</label>
                        <input type="text" class="form-control @error('planNo') is-invalid @enderror" value="{{ old('planNo') }}" placeholder="Enter Plan Number" id="planNo" name="planNo">
                        @error('planNo')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="title">Surveyor Name:</label>
                        <input type="text" class="form-control @error('surveyorName') is-invalid @enderror" value="{{ old('surveyorName') }}" placeholder="Enter Surveyor Name" id="surveyorName" name="surveyorName">
                        @error('surveyorName')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Citizens arent allowed to fill in organization. It will automatically filled -->
                    @if(Auth()->user()->role_id != 6 )
                        <div class="form-group">
                            Land Owner:<input type="text" class="form-control typeahead3 @error('land_owner') is-invalid @enderror" value="{{ old('land_owner') }}" placeholder="Search" name="land_owner" />
                            @error('land_owner')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="customCheck1" value="1" name="checklandowner" {{ old('checklandowner') == "1" ? 'checked' : ''}}>
                                <label class="custom-control-label" for="customCheck1"><strong>Is Unregistered</strong></label>
                            </div>
                        </div>
                        <div class="extLandOwner" id="extLandOwner">
                            <div class="form-group">
                                <label>Land Owner Type:</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="landownertype" id="landownertype1" value="1" {{(old('landownertype') == '1') ? 'checked' : ''}}>
                                    <label class="form-check-label" for="landownertype1">
                                        Government
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="landownertype" id="landownertype2" value="2" {{(old('landownertype') == '2') ? 'checked' : ''}}>
                                    <label class="form-check-label" for="landownertype2">
                                        Private
                                    </label>
                                </div>
                                @error('landownertype')
                                <div class="alert alert-danger">Please Select the Type</div>
                                @enderror
                            </div>
                        </div>
                    @endif
                   
                    <div id="accordion" class="mb-3">
                        <div class="card mb-3">
                            <div class="card-header bg-white">
                                <a class="collapsed card-link text-dark" data-toggle="collapse" href="#collapseone">
                                    Organizations Governing Land (Optional)
                                </a>
                            </div>
                            <div id="collapseone" class="collapse" data-parent="#accordion">
                                <div class="card-body">
                                    <strong>Select 1 or More</strong>
                                    <fieldset>
                                        @foreach($organizations as $organization)
                                        <input type="checkbox" name="governing_orgs[]" value="{{$organization->id}}" @if( is_array(old('governing_orgs')) && in_array($organization->id, old('governing_orgs'))) checked @endif><label class="ml-2">{{$organization->title}}</label> <br>
                                        @endforeach
                                    </fieldset>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header bg-white">
                                <a class="collapsed card-link text-dark" data-toggle="collapse" href="#collapsetwo">
                                    Gazettes Relavant to Land (Optional)
                                </a>
                            </div>
                            <div id="collapsetwo" class="collapse" data-parent="#accordion">
                                <div class="card-body">
                                    <strong>Select 1 or More</strong>
                                    <fieldset>
                                        @foreach($gazettes as $gazette)
                                        <input type="checkbox" name="gazettes[]" value="{{$gazette->id}}" @if( is_array(old('gazettes')) && in_array($gazette->id, old('gazettes'))) checked @endif> <label class="ml-2">{{$gazette->title}}</label> <br>
                                        @endforeach
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="btn bd-navbar text-light">Submit</button>
                    </div>
                </div>
                <div class="col border border-muted rounded-lg p-4">
                    <div class="form-group">
                        <label for="province">Province:</label>
                        <select class="custom-select @error('province') is-invalid @enderror" name="province">
                            <option disabled selected value="">Select</option>
                            @foreach ($provinces as $province)
                            <option value="{{ $province->id }}" {{ Request::old()?(Request::old('province')==$province->id?'selected="selected"':''):'' }}>{{ $province->province }}</option>
                            @endforeach
                        </select>
                        @error('province')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

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
                        <label for="province">Grama Sevaka Division:</label>
                        <select class="custom-select @error('gs_division') is-invalid @enderror" name="gs_division">
                            <option disabled selected value="">Select</option>
                            @foreach ($gs as $gs_division)
                            <option value="{{ $gs_division->id }}" {{ Request::old()?(Request::old('gs_division')==$gs_division->id?'selected="selected"':''):'' }}>{{ $gs_division->gs_division }}</option>
                            @endforeach
                        </select>
                        @error('gs_division')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- ////////MAP GOES HERE -->
                    <label>Select Location On Map*</label>
                    <span style="float:right; cursor:pointer;"><kbd><a title="How to Draw Shapes on the Map" class="text-white" data-toggle="modal" data-target="#mapHelp">How To Mark Location</a></kbd></span>
                    <div id="mapid" style="height:400px;" name="map"></div>
                    @error('polygon')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <br>
                    <input id="polygon" type="hidden" class="form-control" name="polygon" value="{{request('polygon')}}">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="customCheck" value="1" name="isProtected">
                        <label class="custom-control-label" for="customCheck"><strong>Check if land is a protected area</strong></label>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" class="form-control" name="createdBy" value="{{Auth::user()->id}}">

    </form>
</div>


<script type="text/javascript">
    var path = "{{route('gazette')}}";
    $('input.typeahead').typeahead({
        source: function(terms, process) {

            return $.get(path, {
                terms: terms
            }, function(data) {
                console.log(data);
                objects = [];
                data.map(i => {
                    objects.push(i.gazette_number)
                })
                console.log(objects);
                return process(objects);
            })
        },
    });

    //THIS USES THE AUTOMECOMPLETE FUNCTION IN TREE REMOVAL CONTROLLER
    var path3 = "{{route('organization')}}";
    $('input.typeahead3').typeahead({
        source: function(terms, process) {

            return $.get(path3, {
                terms: terms
            }, function(data) {
                console.log(data);
                objects = [];
                data.map(i => {
                    objects.push(i.title)
                })
                console.log(objects);
                return process(objects);
            })
        },
    });


    /// SCRIPT FOR THE MAP
    var map = L.map('mapid', {
        center: [7.2906, 80.6337], //if the location cannot be fetched it will be set to Kandy
        zoom: 12
    });

    window.onload = function() {
        var popup = L.popup();
        //false,               ,popup, map.center
        function geolocationErrorOccurred(geolocationSupported, popup, latLng) {
            popup.setLatLng(latLng);
            popup.setContent(geolocationSupported ?
                '<b>Error:</b> Geolocation service failed. Enable Location.' :
                '<b>Error:</b> This browser doesn\'t support geolocation.');
            popup.openOn(map);
        }
        //If theres an error then 

        if (navigator.geolocation) { //using an inbuilt function to get the lat and long of the user.
            navigator.geolocation.getCurrentPosition(function(position) {
                var latLng = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };

                popup.setLatLng(latLng);
                popup.setContent('This is your current location');
                popup.openOn(map);
                //setting the map to the user location
                map.setView(latLng);

            }, function() {
                geolocationErrorOccurred(true, popup, map.getCenter());
            });
        } else {
            //No browser support geolocation service
            geolocationErrorOccurred(false, popup, map.getCenter());
        }
        //keeping the dynamic components open if checked
        if ($("#customCheck1").is(':checked')) {
            $("#extLandOwner").show();
        } else {
            $("#extLandOwner").hide()
        }
    }

    // Set up the OSM layer 
    //map tiles are “square bitmap graphics displayed in a grid arrangement to show a map.”
    //There are a number of different tile providers (or tileservers), some are free and open source. We are using OSM
    L.tileLayer(
        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Data © <a href="http://osm.org/copyright">OpenStreetMap</a>',
            maxZoom: 18
        }).addTo(map);
    //we’re calling tilelayer() to create the tile layer, passing in the OSM URL first, then the second argument is an object containing the options for our new tile 
    //layer (including attribution is critical here to comply with licensing), and then the tile layer is added to the map using addTo().

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


        drawnItems.addLayer(layer);
        $('#polygon').val(JSON.stringify(drawnItems.toGeoJSON())); //geoJSON converts a layer to JSON

        ///Converting your layer to a KML
        //$('#kml').val(tokml(drawnItems.toGeoJSON()));
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

    //toggle extra details for external land owner
    $(function() {
        $("#customCheck1").click(function() {
            if ($(this).is(":checked")) {
                $("#extLandOwner").show();
            } else {
                $("#extLandOwner").hide();
            }
        });
    });

    //toggle extra details for external requestor
    $(function() {
        $("#customCheck2").click(function() {
            if ($(this).is(":checked")) {
                $("#extRequestor").show();
            } else {
                $("#extRequestor").hide();
            }
        });
    });
</script>
@endsection