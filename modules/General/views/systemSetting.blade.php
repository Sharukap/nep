@extends('home')

@section('cont')
<h3 class="p-3 display-4">System Settings</h3>
<hr>
<div class="row justify-content-center">
<table class="table table-striped mr-4">
        <thead>
            <tr>
                <th>Setting</th>
                <th>Current Status</th>
                @if($activity == 0)
                    <th>On</th>
                @elseif($activity == 1)
                    <th>Off</th>
                @endif
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Allow admins to review applications auto assigned to your organization</td>
                @if($activity == 3)
                    <td>Auto assign not configured yet for this organization</td>
                @elseif($activity == 0)
                    <td>Off</td>
                    <td><a href="/general/autoadminOn" class="text-muted">On</a></td>
                @else
                    <td>On</td>
                    <td><a href="/general/autoadminOff" class="text-muted">Off</a></td>
                @endif
            </tr>
        </tbody>
    </table>
</div>


@endsection