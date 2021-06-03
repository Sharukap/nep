@extends('home')

@section('cont')
<h3 class="p-3 display-5" style="display:inline">Reporting Module</h3>
<nav class="navbar navbar-expand-lg navbar-light justify-content-around">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link mr-4" href="/reporting/overview">Overview<span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link mr-4" href="/reporting/tree-removal">Tree Removal</a>
            </li>
            <li class="nav-item">
                <a class="nav-link mr-4" href="/reporting/dev-proj">Development Project</a>
            </li>
            <li class="nav-item">

                <a class="nav-link mr-4" href="/reporting/restoration">Restoration</a>

            </li>
            <li class="nav-item">
                <a class="nav-link" href="/reporting/complaints">Complaints</a>
            </li>

        </ul>
    </div>
</nav>
<div class="col-md">
    @yield('reporting')
</div>
<!--chart js -->
<script src="{{ url('/vendor/chart.js/dist/Chart.min.js') }}"></script>
<script src="{{ url('/vendor/chart.js/dist/Chart.extension.js') }}"></script>
<script src="{{ url('/vendor/create-charts.js' ) }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.0/FileSaver.js" integrity="sha512-UNbeFrHORGTzMn3HTt00fvdojBYHLPxJbLChmtoyDwB6P9hX5mah3kMKm0HHNx/EvSPJt14b+SlD8xhuZ4w9Lg==" crossorigin="anonymous"></script>
@endsection