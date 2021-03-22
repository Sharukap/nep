@extends('home')

@section('cont')
<h3 class="p-3 display-4" style="display:inline">User Management</h3>


<div class="container mt-4 bg-white border">

  <nav class="navbar navbar-expand-sm navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link h4" href="/user/index">Users</a>
      </li>
      <li class="nav-item">
        <a class="nav-link h4" href="/organization/index">Organizations</a>
      </li>
    </ul>
  </nav>
  <div class="col-md">
    @yield('admin')
  </div>
</div>

@endsection