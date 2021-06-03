@extends('Envmain')

@section('env')


<div class="container">
    <div>
      <a href="/environment/createrequest" class="btn bd-navbar text-light" role="button">New Eco-System</a>
    </div>

    <br>
    <nav class="navbar navbar-expand-sm navbar-light">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link h4" href="/environment/updatedata">Info</a>
        </li>
        <li class="nav-item">
          <a class="nav-link h4" href="/environment/updatedataspecies">Quick Links</a>
        </li>
      </ul>
    </nav>




    <table class="table table-striped table-white">
      <thead>
        <tr>
          <th scope="col">ID</th>
          <th scope="col">Eco-System Type</th>
          <th scope="col">Description</th>
          <th scope="col">Status</th>
          <th scope="col">Approve</th>
          <th scope="col">Delete</th>
          <th scope="col">More</th>



        </tr>
      </thead>
      <tbody>

        @foreach ($ecosystems as $row)
        <tr>
          <td>{{$row->id}}</td>
          <td>{{$row->ecosystems_type->type}}</td>
          <td>{{$row->description}}</td>

          @switch($row->status)
          @case('0')
          <td>Inactive</td>
          @break;
          @case('1')
          <td>Active</td>
          @break;
          @endswitch




          <td>
            <form action="/environment/environment/updatestatus/{{$row->id}}" method="POST">
              {{csrf_field()}}
              {{method_field('PUT')}}


              <button type="submit" name="status" value="1" class="btn btn-outline-warning">Approve</button>



            </form>


          </td>

          <td>
            <form action="{{url('/environment/delete-request/'.$row ->id)}}" method="POST">
              {{csrf_field()}}
              {{method_field('DELETE')}}
              <button type="submit" class="btn btn-outline-danger">Delete </button>


            </form>
          </td>
          <!-- opens the more view -->
          <td class="text-center"><a href="/environment/moreeco/{{$row->id}}" class="btn btn-outline-info mr-4" role="button">...</a></td>



        </tr>

        @endforeach



      </tbody>
    </table>









</div>

@endsection