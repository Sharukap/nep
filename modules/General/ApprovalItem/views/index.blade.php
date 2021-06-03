<!DOCTYPE html>
<html lang="en">
<head>
    <title>National Environment Platform</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
    .main-img {
        background: url('https://ak2.picdn.net/shutterstock/videos/9057502/thumb/12.jpg');
        background-position: center center;
        background-repeat: no-repeat;
        background-size: cover;
        height: 100vh;
        width: 100%;
    }
    </style>
</head>
<body>
<div class="container">
    <div class="row p-4 bg-white">
        <div class="col border border-muted rounded-lg mr-2 p-4">
            <h2>{{$process_item->form_type->type}} Application No {{$process_item->form_id}}</h2>
            <h4>Reference No {{$process_item->id}}</h4>
            <hr>
        </div>
    </div>
    <div class="row p-4 bg-white">
        <div class="col border border-muted rounded-lg mr-2 p-4">
            <ul>
            <li>Application created by {{$process_item->created_by_user->name}} logged on {{date('d-m-Y',strtotime($item->created_at))}}</li>
            <li>Assigned by Mr/Mrs. {{$user->name}}, {{$user->designation->designation}} at {{$user->organization->title}}</li>
            <li>Assigned to {{$process_item->other_removal_requestor_name}} for investigation</li>
            </ul>
        </div>
    </div>
    <div class="row p-4 bg-white">
            <div class="col border border-muted rounded-lg mr-2 p-4">
                @switch($process_item->form_type_id)
                    @case('1')
                        <table class="table table-light table-striped border-secondary rounded-lg mr-4">
                            <thead>
                                <tr>
                                    <th>Province</th>
                                    <th>District</th>
                                    <th>GS Division</th>
                                    <th>Special approval</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    @if($item->province == NULL)
                                    <td>Unassigned</td>
                                    @else
                                    <td>{{$item->province->province}}</td>
                                    @endif 
                                    @if($item->district == NULL)
                                    <td>Unassigned</td>
                                    @else
                                    <td>{{$item->district->district}}</td>
                                    @endif 
                                    @if($item->gs_division == NULL)
                                    <td>Unassigned</td>
                                    @else
                                    <td>{{$item->gs_division->gs_division}}</td>
                                    @endif
                                    @if($item->special_approval==0)
                                        <td>Not a protected area</td>
                                    @elseif($item->special_approval==1)
                                        <td>Protected area</td>
                                    @endif
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table-light table-striped border-secondary rounded-lg mr-4">
                            <thead>
                                <tr>
                                    <th>Land size</th>
                                    <th>unit</th>
                                    <th>No of Trees</th>
                                    <th>No of Tree Species</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{$item->land_size}}</td>
                                    <td>Acres</td>
                                    <td>{{$item->no_of_trees}}</td>
                                    <td>{{$item->no_of_tree_species}}<td>
                                </tr>
                            </tbody>
                        </table>
                        <h6>Additional Data</h6>
                        <table class="table table-light table-striped border-secondary rounded-lg mr-4">
                                <thead>
                                    <tr>
                                        <th>Number of Mamal Species</th>
                                        <th>Number of Amphibian Species</th>
                                        <th>Number of Reptile Species</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{$item->no_of_mammal_species}}</td>
                                        <td>{{$item->no_of_amphibian_species}}</td>
                                        <td>{{$item->no_of_reptile_species}}</td>
                                    </tr>
                                </tbody>
                        </table>
                        <table class="table table-light table-striped border-secondary rounded-lg mr-4">
                                <thead>
                                    <tr>
                                        <th>Number of Avian Species</th>
                                        <th>Number of Flora Species</th>
                                        <th>Tree Species special notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{$item->no_of_avian_species}}</td>
                                        <td>{{$item->no_of_flora_species}}</td>
                                        <td>{{$item->species_special_notes}}</td>
                                    </tr>
                                </tbody>
                        </table>
                    @break
                    @case('2')
                        <table class="table table-light table-striped border-secondary rounded-lg mr-4">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Gazzete</th>
                                    <th>Protected Area</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{$item->title}}</td>
                                    <td>{{$item->gazette->title}}</td>
                                    <td>{{$item->protected_area}}</td>               
                                </tr>
                            </tbody>
                        </table>
                    @break
                    @case('4')
                        <table class="table table-light table-striped border-secondary rounded-lg mr-4">
                            <thead>
                                <tr>
                                    <th>Crime type</th>
                                    <th>description</th>
                                    <th>Date complained logged</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{$item->crime_type->type}}</td>
                                    <td>{{$item->description}}</td>
                                    <td>{{date('d-m-Y',strtotime($item->created_at))}}</td>
                                </tr>
                            </tbody>
                        </table>
                    @break
                @endswitch
            </div>
    </div>
        @if($process_item->form_type_id == 1)
            <div class="row p-4 bg-white">
                <div class="col border border-muted rounded-lg mr-2 p-4">
                    <h6>Tree Data</h6>
                    @if(count($data) < 1)
                        <h1>No data</h1>
                    @else
                        <table class="table table-light table-striped border-secondary rounded-lg mr-4">
                            <thead>
                                <tr>
                                    <th>Tree ID</th>
                                    <th>Tree Species ID</th>
                                    <th>Width at Breast Height</th>
                                    <th>Height</th>
                                    <th>Timber Volume</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for($x = 0; $x < count($data); $x++)
                                <tr>
                                    <td>{{$data[$x]['tree_id']}}</td>
                                    <td>{{$data[$x]['tree_species_id']}}</td>
                                    <td>{{$data[$x]['width_at_breast_height']}}</td>
                                    <td>{{$data[$x]['height']}}</td>
                                    <td>{{$data[$x]['timber_volume']}}</td>
                                </tr>
                                @endfor
                            </tbody>
                        </table>
                        <table class="table table-light table-striped border-secondary rounded-lg mr-4">
                            <thead>
                                <tr>
                                    <th>Tree ID</th>
                                    <th>Timber Cubic</th>
                                    <th>Age</th>
                                    <th>Remark</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for($x = 0; $x < count($data); $x++)
                                <tr>
                                    <td>{{$data[$x]['tree_id']}}</td>
                                    <td>{{$data[$x]['timber_cubic']}}</td>
                                    <td>{{$data[$x]['age']}}</td>
                                    <td>{{$data[$x]['remark']}}</td>
                                </tr>
                                @endfor
                            </tbody>
                        </table>
                    @endif
                </div>             
            </div>
        @endif
    <div class="row p-4 bg-white">
        <div class="col border border-muted rounded-lg mr-2 p-4">
            <div class="d-flex bg-light justify-content-center">
                <h5 class="text-secondary"><i class="far fa-copyright"></i> 2021 by RFSL - LSF - Ministry of Environment</h5><br>
            </div>
        </div>
    </div>
</div>



</body>

</html>