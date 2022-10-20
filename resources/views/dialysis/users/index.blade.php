@extends('layouts.main')

@section('title')
Manage Users
@endsection

@section('content')
<section class="section">
  <div class="section-header" style="display: block;">
    <h1>Manage Users</h1>
    <a href="{{url('/user')}}"  class="btn btn-success active float-left mb-2" role="button" aria-pressed="true"><i class="plus icon"></i> Add New User</a>
    <div class="clearfix"></div>
    <!-- <a href="{{url('/user')}}" class="btn btn-icon btn-success" style="float: right;" tabindex="0" role="button" data-toggle="popover" data-trigger="hover" data-content="Add New User"><i class="fas fa-plus"></i></a> -->
  </div>

  <div class="section-body">
  	<div class="card">
      <div class="card-body">
        <div class="table-responsive">
        	<table id="example" class="table table-striped">
              <thead>
                  <tr>
                      <th>User Id</th>
                      <th>Username</th>
                      <th>Name</th>
                      <th>Type</th>
                      <th>Video Call</th>
                      <th>Action</th>
                  </tr>
              </thead>
              <tbody>
              	@foreach ($users as $user)
              	  <tr>
                      <td>{{$user->id}}</td>
                      <td>{{$user->username}}</td>
                      <td>{{$user->name}}</td>
                      <td>{{$user->groupid}}</td>
                      <td>{{$user->televideo}}</td>
                      <td>
                      	<a href="{{url('/user/'.$user->id)}}" class="btn btn-sm btn-secondary">Edit</a>
                      	<a href="{{url('/user/delete/'.$user->id)}}" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                      </td>
                  </tr>
      			@endforeach
              </tbody>
              <tfoot>
                  <tr>
                      <th>User Id</th>
                      <th>Username</th>
                      <th>Name</th>
                      <th>Type</th>
                      <th>Video Call</th>
                      <th>Action</th>
                  </tr>
              </tfoot>
          </table>
        </div>
    </div>
  </div>
</section>
@endsection

@section('js')
<script src="{{ asset('js/users.js') }}"></script>
<!-- <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script> -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
@endsection


@section('stylesheet')
<!-- <link rel="stylesheet" href="{{ asset('assets/node_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}"> -->
<!-- <link rel="stylesheet" href="{{ asset('assets/node_modules/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}" > -->
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
<!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/dataTables.semanticui.min.css"> -->
@endsection
