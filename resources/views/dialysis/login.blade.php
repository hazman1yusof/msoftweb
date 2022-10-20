@extends('layouts.main')

@section('content')
<!-- Content area -->
<div class="ui container content">
	<div class="ui centered grid">
		<div class="column" style="max-width: 350px;">
			<form class="ui form" method="POST" autocomplete="off">
				{{ csrf_field() }}
				<div class="ui raised segment" style="background: rgb(121 205 255 / 50%); padding: 20px 40px;">
					<div class="ui medium rounded image">
					  <img src="{{ asset('img/LOGOpicom.png') }}">
					</div>
					<div class="field" style="text-align: center;font-size: medium;letter-spacing: .8px;">
						<label>Log In</label>
					</div>
					<div class="field">
						<!-- <label>Username</label> -->
						<input placeholder="Username" type="text" name="username" autocomplete="off">
					</div>
					<div class="field">
						<!-- <label>Password</label> -->
						<div class="ui icon input">
						  <input placeholder="Password" type="password" name="password" id="inputPassword" autocomplete="off">
						  <i id="showpwd" class="eye link icon"></i>
						</div>
					</div>
					<div class="field">
						<!-- <label>Company</label> -->
						<select class="ui selection dropdown" id="compcode" name="compcode">
						  @foreach ($company as $obj)
							    <option value="{{$obj->compcode}}">{{$obj->name}}</option>
							@endforeach
						</select>
					</div>
					<!-- <div class="ui stackable two column grid">
						<div class="ui checkbox column">
							<input type="checkbox" tabindex="0" class="hidden" name="remember">
							<label>Remember me</label>
						</div>
					</div> -->
				<button class="ui fluid basic button" type="submit" style="margin-top: 10px; background: rgb(255 255 255);"><b>Log In</b></button>
				</div>

				
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
</div>
@endsection

@section('js')
	<script src="{{ asset('js/login.js') }}"></script>
@endsection

@section('_style')
	body {
      background-color: #DADADA;
    }
    body > .grid {
      height: 100%;
    }
@endsection