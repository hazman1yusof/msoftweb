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
      background-image:url('{{$bgpic}}');
      background-repeat: no-repeat !important;
      background-size: cover !important;
      background-position: center !important;
      height: 100vh;
      width: 100vw;
    }

    #computerid_warning{
      margin-top: 10px;
      padding: 10px;
      font-weight: bold;
    }

    #computerid_warning select,#computerid_warning button,#computerid_warning input{
      padding: 5px;
      font-size: 12px;
    }

    input#computerid_show{
      padding: 5px;
      font-size: 12px;
      background:#daeeff;
    }
    
  </style>
</head>

<body>
  <div class="container">

    <form class="form-signin" name="login" method="POST" action="./login">
      {{ csrf_field() }}
      <h2 class="form-signin-heading"></h2>
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
          @foreach($company as $index => $company)
            @if ($index == 0)
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
      <div id="computerid_warning" class="alert alert-danger" role="alert" style="display:none;">
        <span>Set Computer ID</span><br>
        <select id="compid_type" class="form-control">
          <option>DESKTOP</option>
          <option>LAPTOP</option>
          <option>MOBILE</option>
        </select>
        <select id="compid_type2" class="form-control">
          <option>WORK</option>
          <option>PERSONAL</option>
          <option>HOME</option>
        </select>
        <input class="form-control" type="text" id="compid_name" placeholder="Type Your Name">
        <br>
        <button type="button" class="btn form-control" id="setid">Set ID</button>
      </div>
      <br>
      <input class="form-control" type="text" id="computerid_show" name="computerid_show" disabled>
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
  let mql = window.matchMedia("(max-width: 768px)");

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

      $("#setid").click(function(){
        let type = $('#compid_type').val();
        let type2 = $('#compid_type2').val();
        let name = $('#compid_name').val();

        localStorage.setItem('computerid', type+'_'+type2+'_'+name);
        $('#computerid').val(type+'_'+type2+'_'+name);
        $('#computerid_show').val(type+'_'+type2+'_'+name);
      });
    });

    var computerid_val = localStorage.getItem('computerid');
    if(!computerid_val){
      $('#computerid_warning').show();
    }else{
      $('#computerid').val(computerid_val);
      $('#computerid_show').val(computerid_val);
    }

    // function signing_in()
    // {
    //     Profile.signing_in($("#username").val(), $("#inputPassword").val(), $("#cmb_companies").val());
    // }
</script>

</body>
</html>
