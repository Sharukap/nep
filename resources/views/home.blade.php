<!DOCTYPE html>
<html lang="en">

<head>
    <title>National Environment Platform</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js" integrity="sha512-HWlJyU4ut5HkEj0QsK/IxBCY55n5ZpskyjVlAoV9Z7XQwwkqXoYdCIC93/htL3Gu5H3R4an/S0h2NXfbZk3g7w==" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    @livewireStyles
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />

    <script src='https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-omnivore/v0.3.1/leaflet-omnivore.min.js'></script>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw-src.css" integrity="sha512-vJfMKRRm4c4UupyPwGUZI8U651mSzbmmPgR3sdE3LcwBPsdGeARvUM5EcSTg34DK8YIRiIo+oJwNfZPMKEQyug==" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js" integrity="sha512-ozq8xQKq6urvuU6jNgkfqAmT7jKN2XumbrX1JiB3TnF7tI48DPI4Gy1GXKD/V3EExgAs1V+pRO7vwtS1LHg0Gw==" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.15.3/xlsx.full.min.js"></script>

    <!-- Search for laravel -->
    <script src="https://cdn-geoweb.s3.amazonaws.com/esri-leaflet/0.0.1-beta.5/esri-leaflet.js"></script>
    <script src="https://cdn-geoweb.s3.amazonaws.com/esri-leaflet-geocoder/0.0.1-beta.5/esri-leaflet-geocoder.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn-geoweb.s3.amazonaws.com/esri-leaflet-geocoder/0.0.1-beta.5/esri-leaflet-geocoder.css">


    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <link href="{{ url('/css/app.css') }}" rel="stylesheet">

    <script type="text/javascript" src="{{ URL::asset('js/tokml.js') }}"></script>
</head>

<body style="background-color:#f0f0f7">
    @livewireScripts
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-2 bd-navbar">
                <div class="p-2 flex-shrink-3">
                    <nav class="navbar">
                        <!-- Links -->
                        <ul class="navbar-nav">
                            <li class="nav-item mt-5">
                                <a class="nav-link text-light" href="/general/pending"><i class="fa fa-fw fa-home mr-3" aria-hidden="true"></i> General</a>
                            </li>
                            <li class="nav-item mt-3">
                                <a class="nav-link text-light" href="/user/index"><i class="fa fa-fw fa-user mr-3" aria-hidden="true"></i> Users</a>
                            </li>
                            <li class="nav-item mt-3">
                                <a class="nav-link text-light" href="/environment/updatedata"><i class="fa fa-fw fa-tree mr-3" aria-hidden="true"></i> Environment</a>
                            </li>
                            <li class="nav-item mt-3">
                                <a class="nav-link text-light" href="/glad"><i class="fa fa-fw fa-globe mr-3" aria-hidden="true"></i> Tracking</a>
                            </li>
                            <li class="nav-item mt-3">
                                <a class="nav-link text-light" href="/approval-item/showRequests"><i class="fa fa-fw fa-arrow-down mr-3" aria-hidden="true"></i> Requests</a>
                            </li>
                            @if(Auth::user()->role_id < 3)
                                <li class="nav-item mt-3">
                            @else
                                <li class="nav-item mt-3" style="margin-bottom:400px;">
                            @endif
                                    <a class="nav-link text-light" href="/reporting/overview"><i class="fa fa-fw fa-book mr-3" aria-hidden="true"></i> Reporting</a>
                                </li>
                            @if(Auth::user()->role_id < 3)
                            <li class="nav-item mt-3" style="margin-bottom:400px;">
                                <a class="nav-link text-light" href="/general/systemSetting"><i class="fa fa-fw fa-cog mr-3" aria-hidden="true"></i> System Settings</a>
                            </li>
                            @endif
                            <li class="nav-item mt-3"></li>
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="col-lg" style="padding-left: 0; padding-right: 0;">

                <!-- TOP NAV -->
                <nav class="navbar navbar-expand-sm bg-white navbar-dark">
                    <!-- Brand/logo -->
                    <a class="navbar-brand mr-auto" href="#">
                        <img src="/Logo.jpeg" alt="logo" style="width:70px;">
                    </a>

                    <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="media align-items-center">
                            <div class="media-body  ml-2  d-none d-lg-block">
                                <span class="mb-0 text-sm  text-dark font-weight-bold"><i class="fas fa-bell mr-1"></i></span>
                                @if(auth()->user()->unreadNotifications->count())
                                <span class="mb-0 text-sm  font-weight-bold"><span class="badge badge-light">{{auth()->user()->unreadNotifications->count()}}</span></span>
                                @endif
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-menu  dropdown-menu-right ">
                        <div class="dropdown-header noti-title">
                            <h6 class="text-overflow m-0">Your Notifications:</h6>
                        </div>
                        @foreach(auth()->user()->unreadNotifications as $notification)
                        <ul>
                            @if($notification->type == "App\Notifications\StaffAssigned")
                            <a href="/approval-item/investigate/{{$notification->data['process_id']}}"><i class="ni ni-single-02"></i><span>
                                    <li>
                                        <p> {{$notification->data['type']}} application No {{$notification->data['form_id']}} {{$notification->data['action']}}</p></i>
                                </span></a>
                            @endif
                            @if($notification->type == "App\Notifications\AssignOrg")
                            <a href="/approval-item/assignstaff/{{$notification->data['process_id']}}"><i class="ni ni-single-02"></i><span>
                                    <li>
                                        <p> {{$notification->data['type']}} application No {{$notification->data['form_id']}} {{$notification->data['action']}}</p></i>
                                </span></a>
                            @endif
                            @if($notification->type == "App\Notifications\ApplicationMade")
                            <a href="/approval-item/assignorganization/{{$notification->data['process_id']}}"><i class="ni ni-single-02"></i><span>
                                    <li>
                                        <p>New {{$notification->data['type']}} application No {{$notification->data['form_id']}} {{$notification->data['action']}} .</p></i>
                                </span></a>
                            @endif
                            @if($notification->type == "App\Notifications\prereqmemo")
                            <a href="/approval-item/investigate/{{$notification->data['prerequisite_id']}}"><i class="ni ni-single-02"></i><span>
                                    <li>
                                        <p>Prerequisite no {{$notification->data['prerequisite_id']}} {{$notification->data['remark']}} .</p></i>
                                    </li>
                                </span></a>
                            @endif
                        </ul>
                        @endforeach
                        @if(auth()->user()->unreadNotifications->count())
                        <div class="dropdown-divider"></div>
                        <a href="/markAsRead" class="dropdown-item">
                            <span>Mark all as Read</span>
                        </a>
                        @endif
                    </div>

                    <ul class="navbar-nav">
                        <!-- Authentication Links -->
                        @guest
                        <li class="nav-item">
                            <a class="nav-link text-dark" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link text-dark" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                        @endif
                        @else
                        <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <div class="media align-items-center">
                                <div class="media-body  ml-2  d-none d-lg-block">
                                    <span class="mb-0 text-sm text-dark font-weight-bold">{{ Auth::user()->name }}</span>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu  dropdown-menu-right ">
                            <div class="dropdown-header noti-title">
                                <h6 class="text-overflow m-0">Welcome!</h6>
                            </div>
                            <a href="#!" class="dropdown-item">
                                <i class="ni ni-single-02"></i>
                                <span>User</span>
                            </a>
                            <a href="/admin/passwordReset" class="dropdown-item">
                                <i class="ni ni-settings-gear-65"></i>
                                <span>Change my password</span>
                            </a>
                            <a href="#!" class="dropdown-item">
                                <i class="ni ni-calendar-grid-58"></i>
                                <span>Activity</span>
                            </a>
                            <a href="#!" class="dropdown-item">
                                <i class="ni ni-support-16"></i>
                                <span>Support</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault();document.getElementById('logout-form').submit();">

                                <i class="ni ni-user-run"></i>
                                {{ __('Logout') }}
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </a>
                        </div>
                        </li>
                        @endguest
                    </ul>
                </nav>
                <div style="background-color:#f0f0f7" class="col-md">
                    @yield('cont')
                    <br>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div>
        {{-- sticky footer --}}
        <footer class="footer">
            <div class="d-flex  bg-light justify-content-end">
                <br>
                <a href="#" class="text-secondary mr-2">
                    <i class="fab fa-facebook-square"></i> Facebook |
                </a>
                <a href="#" class="text-secondary mr-2">
                    <i class="fab fa-twitter-square"></i> Twitter |
                </a>
                <a href="#" class="text-secondary mr-2">
                    <i class="fab fa-instagram"></i> Instagram
                </a>
            </div>
            <div class="d-flex bg-light justify-content-center">
                <h5 class="text-secondary"><i class="far fa-copyright"></i> 2021 by RFSL - LSF - Ministry of Environment</h5><br>
            </div>
            <div class="d-flex bg-light justify-content-center">
                <h6>All rights reserved</h6>
            </div>
        </footer>
    </div>
</body>

</html>