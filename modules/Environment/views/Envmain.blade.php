@extends('home')

@section('cont')
<h3 class="p-3 display-5" style="display:inline">Environment Module</h3>

<div class="container mt-4 bg-white border">
  <nav class="navbar navbar-expand-sm navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="/environment/updatedata">Eco-System Management</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/environment/updatedataspecies">Species Management</a>
      </li>
    </ul>
  </nav>

  <div class="col-md">
    @yield('env')
  </div>
</div>

@endsection