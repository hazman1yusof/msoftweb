@extends('layouts.main')

@section('content')
<!-- Content area -->
<div class="ui container content">
	<div class="ui centered grid">
		<div class="column" style="max-width: 550px;">
			<form class="ui form" method="POST" autocomplete="off">
				{{ csrf_field() }}
				<div class="ui attached tall stacked teal segment">
					<div class="field">
						<label>Username</label>
						<input placeholder="Username" type="text" name="username" autocomplete="off">
					</div>
					<div class="field">
						<label>Password</label>
						<div class="ui icon input">
						  <input placeholder="Password" type="password" name="password" id="inputPassword" autocomplete="off">
						  <i id="showpwd" class="eye link icon"></i>
						</div>
					</div>
					<!-- <div class="ui stackable two column grid">
						<div class="ui checkbox column">
							<input type="checkbox" tabindex="0" class="hidden" name="remember">
							<label>Remember me</label>
						</div>
					</div> -->
				</div>

				<button class="ui fluid button teal" type="submit" style="margin-top: 10px">Log In</button>
				
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