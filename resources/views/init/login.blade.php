<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Medicsoft Enterprise Edition - Log In</title>

  <link rel="stylesheet" href="plugins/bootstrap-3.3.5-dist/css/bootstrap.min.css"> 
  <!-- Custom styles for this template -->
  <link href="css/login.css" rel="stylesheet">
</head>

<body style="background: #eeeeee">
  <div class="container">

    <form class="form-signin" name="login" method="POST" action="./login">
      {{ csrf_field() }}
      <h2 class="form-signin-heading">Please Log in</h2>
      <label for="username" class="sr-only">Email address</label>
      <input type="text" id="username" name='username' class="form-control" placeholder="Username" required autofocus>
      <label for="inputPassword" class="sr-only">Password</label>
      <input type="password" id="inputPassword" name='password' class="form-control" placeholder="Password" required>
    	<label for="comp" class="sr-only">Company</label>
      <select id="cmb_companies" name="cmb_companies" class="form-control">
          <option value="">- Select a Company -</option>
          @foreach($company as $company)
            @if ($company->compcode === '9A')
              <option value="{{$company->compcode}}" selected>{{$company->name}}</option>
            @else
              <option value="{{$company->compcode}}">{{$company->name}}</option>
            @endif
          @endforeach
      </select>
      <input type="hidden" id="myurl" name="myurl" value="">
      <button class="btn btn-lg btn-primary btn-block">Sign in</button>
    </form>

  </div> 


<!-- JS Global Compulsory -->
<script src="plugins/jquery.min.js"></script>  
<script src="plugins/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>

<!-- JS Implementing Plugins -->
<!-- <script src="js/myjs/profile.js"></script> -->

<!-- JS Customization -->
<!-- <script src="js/myjs/custom.js"></script> -->

<!-- JS Page Level -->

<script>
  document.getElementById("myurl").value = window.location.hostname;
    // jQuery(document).ready(function() 
    // {
    //     Custom.init_cmb_companies();
    // });

    // function signing_in()
    // {
    //     Profile.signing_in($("#username").val(), $("#inputPassword").val(), $("#cmb_companies").val());
    // }
</script>

</body>
</html>
