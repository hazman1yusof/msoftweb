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
  <style type="text/css">
    body {
      background-image:url( {{ asset('img/page-hospital.jpg') }} ) !important;
      background-repeat: no-repeat !important;
      background-size: cover !important;
      background-position: bottom !important;
      height: 100vh;
      width: 100vw;
    }

    #computerid_warning{
      margin-top: 10px;
      padding: 10px;
      font-weight: bold;
      text-align: center;
    }
    
  </style>
</head>

<body>
  <div class="container">

    <form class="form-signin" name="login" method="POST" action="./login">
      {{ csrf_field() }}
      <h2 class="form-signin-heading">Please Log in</h2>
      <label for="username" class="sr-only">Email address</label>
      <input type="text" id="username" name='username' class="form-control" placeholder="Username" required autofocus>
      <label for="inputPassword" class="sr-only">Password</label>
      <div style="position:relative">
        <input type="password" id="inputPassword" name='password' class="form-control" placeholder="Password" required>
        <span id="showpwd" class="glyphicon glyphicon-eye-open" style="position: absolute;right: 10px;top: 17px;cursor: pointer;z-index: 100;"></span>
      </div>
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
      <input type="hidden" id="mobile" name="mobile" value="false">
      <input type="hidden" id="computerid" name="computerid" >
      <button class="btn btn-lg btn-primary btn-block">Sign in</button>
      <div id="computerid_warning" class="alert alert-danger" role="alert" style="display:none;">Computer ID are not set yet, please ask admin to set your computer id</div>
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

    $(document).ready(function(){
      let mql = window.matchMedia("(max-width: 768px)");
      $('#mobile').val(mql.matches);
      
      $('#showpwd').click(function(){
        if($(this).hasClass('glyphicon-eye-open')){
          $(this).addClass('glyphicon-eye-close').removeClass('glyphicon-eye-open');
          $('#inputPassword').attr('type','text');
        }else if($(this).hasClass('glyphicon-eye-close')){
          $(this).removeClass('glyphicon-eye-close').addClass('glyphicon-eye-open');
          $('#inputPassword').attr('type','password');
        }
      });
    });

    var computerid_val = localStorage.getItem('computerid');
    if(!computerid_val){
      $('#computerid_warning').show();
    }else{
      $('#computerid').val(computerid_val);
    }

    // function signing_in()
    // {
    //     Profile.signing_in($("#username").val(), $("#inputPassword").val(), $("#cmb_companies").val());
    // }
</script>

</body>
</html>
