<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>


        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>


        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Pre Register</title>
        <!-- Styles -->
        <style>
        </style>
    </head>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#select").change(function(){
                if($(this).val() == 'ic'){
                    $("#icnumdiv").show();
                    $("#hpdiv").hide();
                }else{
                    $("#hpdiv").show();
                    $("#icnumdiv").hide();
                }
            });

            var qrcode_myic = localStorage.getItem('qrcode_myic');
            console.log(qrcode_myic)
            if(!qrcode_myic){
                $('#button_').click(function(){
                    console.log($('#ic').val());
                    localStorage.setItem('qrcode_myic', $('#ic').val());
                    $('#submit').click();
                });
            }else{
                $('#ic').val(qrcode_myic);
                $('#button_').click(function(){
                    console.log($('#ic').val());
                    localStorage.setItem('qrcode_myic', $('#ic').val());
                    $('#submit').click();
                });
            }

        });

    </script>
    <body>
        <div class="container">
            <div class="row justify-content-center">
            <div class="col-md-5 col-xs-12">
            <div class="card border-primary mt-5">
              <div class="card-header" style="background-color: #007bff;color: white;"><h5>Patient Registration</h5></div>
              <div class="card-body text-primary">
                    <div class="col-12" style="text-align: center">
                        <a class="navbar-brand" style="padding-top: 0">
                            <img src="{{asset('/img/LOGOpicom.png')}}" alt="logo" height="90px" width="auto">
                        </a>
                        <br/>
                        <p style="color: dimgrey;">Insert patient I/C number and click submit button below</p>
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (Session::has('success'))
                      <div class="alert alert-success">
                        {{ Session::get('success') }}
                      </div>
                    @endif
                    <form method="post" action="./prereg">
                        @csrf
                        <div class="form-group" >
                            <small for="select">Register Using: </small>
                            <select class="form-control form-control" id="select" name="select">
                              <option value="ic" selected="">I/C Number</option>
                              <option value="idnumber">Passport/Others</option>
                            </select>
                        </div>
                        <div class="form-group" id="icnumdiv">
                            <small for="ic">I/C Number</small>
                            <input type="text" class="@error('ic') is-invalid @enderror form-control" id="ic" name="ic" placeholder="e.g. 890128014381">
                        </div>

                        
                        <div class="form-group" id="idnumberdiv" style="display: none">
                            <small for="idnumber">Handphone</small>
                            <input type="text" class="@error('idnumber') is-invalid @enderror form-control" id="idnumber" name="idnumber" placeholder="Other No.">
                        </div>
                        <!-- <div class="form-group">
                            <small for="name">Name</small>
                            <input type="text" class="@error('name') is-invalid @enderror form-control" id="name" name="name" placeholder="Name">
                        </div> -->
                        <button type="button" id="button_" class="form-control btn btn-outline-primary">Submit</button>
                        <button type="submit" id="submit" style="display: none;">Submit</button>
                    </form>
              </div>
            </div>
            </div>
            </div>
        </div>

    </body>
</html>
