@extends('layouts.main')

@section('title')
Edit Profile ({{ $user->name }})
@endsection

@section('content')
<!-- <section class="section">
  <div class="section-header">
    <h1>Edit Profile</h1>
  </div>
  <hr>
  <div class="section-body d-flex justify-content-center">
  	<div class="card card-primary col-lg-6 col-md-8 col-xs-12">

      <div class="card-body" > 

      	@if($errors->any())
      		<div class="segment">
      			<div class="alert alert-danger">
      				<ul>
      				@foreach($errors->all() as $error)
      					<li>{{$error}}</li>
      				@endforeach
      				</ul>
      			</div>
      		</div>
    		@endif

        @if (\Session::has('success'))
          <div class="alert alert-success">
            <ul>
              <li>{!! \Session::get('success') !!}</li>
            </ul>
          </div>
        @endif
        
        <form method="POST">

		      {{csrf_field()}}
          <div class="form-group">
            <label for="username">Username</label>
            <input id="username" type="username" class="form-control" name="username" value="{{$user->username}}" readonly="">
            <div class="invalid-feedback">
            </div>
          </div>

          <div class="form-group">
            <label for="name">Name</label>
            <input id="name" type="name" class="form-control" name="name" value="{{$user->name}}" readonly="">
            <div class="invalid-feedback">
            </div>
          </div>

          <div class="row">
            <div class="form-group col-6">
              <label for="password">Password</label>
              <input id="password" type="password" class="form-control" name="password" autofocus="" value="{{$user->password}}">
            </div>
            <div class="form-group col-6">
              <label for="password_confirmation">Re-type Password</label>
              <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" value="{{$user->password}}">
            </div>
          </div>
          
          <div class="form-group">
            <button type="submit" class="btn btn-primary btn-lg btn-block">
              Edit
            </button>
          </div>

        </form>

      </div>

    </div>
  </div>
</section> -->
<div class="ui container content">
  <div class="ui centered grid">
    <div class="column" style="max-width: 550px;">
      <form class="ui form" method="POST" autocomplete="off">
        {{ csrf_field() }}
        <div class="ui attached tall stacked teal segment">
          <div class="field">
            <label>Username</label>
            <input placeholder="Username" type="text" name="username" autocomplete="off" value="{{$user->username}}" readonly="readonly">
          </div>
          <div class="field">
            <label>Name</label>
            <input placeholder="Username" type="text" name="name" autocomplete="off" value="{{$user->name}}" readonly="readonly">
          </div>
          <div class="field">
            <label>Old Password</label>
            <div class="ui icon input">
              <input placeholder="Password" type="password" name="password" id="inputPassword" autocomplete="off">
              <i id="showpwd" class="eye link icon showpwd"></i>
            </div>
          </div>
          <div class="field">
            <label>New Password</label>
            <div class="ui icon input">
              <input placeholder="Password" type="password" name="password2" id="inputPassword2" autocomplete="off">
              <i id="showpwd2" class="eye link icon"></i>
            </div>
          </div>
          <!-- <div class="ui stackable two column grid">
            <div class="ui checkbox column">
              <input type="checkbox" tabindex="0" class="hidden" name="remember">
              <label>Remember me</label>
            </div>
          </div> -->
        </div>

        <button class="ui fluid button teal" type="submit" style="margin-top: 10px">Change</button>
        
      </form>
    </div>
    
  </div>
  @if($errors->any())
  <div class="ui centered grid">
    <div class="ui error message">
      <div class="header">{{$errors->first()}}</div>
    </div>
  </div>
  @endif

  @if(session()->has('success'))
  <div class="ui centered grid">
    <div class="ui success message">
      <div class="header">{{session('success')}}</div>
    </div>
  </div>
  @endif
</div>
@endsection

@section('js')
  <script src="{{ asset('js/editpassword.js') }}"></script>
@endsection

@section('_style')
  body {
      background-color: #DADADA;
    }
    body > .grid {
      height: 100%;
    }
@endsection

