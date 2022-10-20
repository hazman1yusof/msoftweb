@extends('layouts.main')

@section('title')
Create User
@endsection

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Add User</h1>
  </div>
  <div class="section-body d-flex justify-content-center">
    <div class="card card-primary col-lg-6 col-md-8 col-xs-12">

      <div class="card-body"> 

      	@if($errors->any())
		<div class="segment">
			<div class="ui error message">
				<ul>
				@foreach($errors->all() as $error)
					<li>{{$error}}</li>
				@endforeach
				</ul>
			</div>
		</div>
		@endif
        <form method="POST">

		{{csrf_field()}}
          <div class="form-group">
            <label for="username">Username</label>
            <input id="username" type="username" class="form-control" name="username">
            <div class="invalid-feedback">
            </div>
          </div>

          <div class="form-group">
            <label for="name">Name</label>
            <input id="name" type="name" class="form-control" name="name">
            <div class="invalid-feedback">
            </div>
          </div>

          <div class="row">
            <div class="form-group col-6">
              <label for="password">Password</label>
              <input id="password" type="password" class="form-control" name="password" autofocus="">
            </div>
            <div class="form-group col-6">
              <label for="password_confirmation">Re-type Password</label>
              <input id="password_confirmation" type="password" class="form-control" name="password_confirmation">
            </div>
          </div>
          
          <div class="form-group">
            <label for="groupid">User Type</label>
            <select id="groupid" name="groupid" class="form-control">
      			  <option value="patient">Patient</option>
      			  <option value="admin">Admin</option>
              <option value="MR">Medical Record</option>
              <option value="clinical">Clinical</option>
      			</select>
          </div>

          <div class="form-group">
            <label for="televideo">Tele Video</label>
            <select id="televideo" name="televideo" class="form-control">
              <option value="true">Yes</option>
              <option value="false" selected="selected">No</option>
            </select>
          </div>

          <div class="form-group">
            <button type="submit" class="btn btn-primary btn-lg btn-block">
              Register
            </button>
          </div>

        </form>

       

      </div>

    </div>
  </div>
</section>
@endsection



@section('stylesheet')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
@endsection