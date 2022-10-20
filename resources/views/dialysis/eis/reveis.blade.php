@extends('layouts.main')

@section('title')
REV EIS
@endsection

@section('stylesheet')
    <style id="plotly.js-style-global"></style>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="https://pivottable.js.org/dist/pivot.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/c3/0.4.11/c3.min.css">
    <!-- <link rel="stylesheet" href="{{ asset('assets/node_modules/izitoast/dist/css/iziToast.min.css') }}"> -->
@endsection


@section('css')
<style type="text/css">
  .form-check{
    padding-right:15px;
  }
  .pvtFilterBox{
      z-index: 120 !important;
  }
  .card-body{
      overflow-x: scroll !important;
  }
  #preview{
    position: absolute;
    right: 0px;
  }
  .form-inline{
    padding: 10px;
  }
  .form-check-label{
    padding-right: 10px;  
  }
  .pvtHorizList li { display: inline-block !important; } 
</style>
@endsection


@section('content')
<section class="section">
  <div class="section-header">
    <h1>EIS - Revenue By Services</h1>
  </div>
  <hr>

  <div class="section-body" >
    <div class="card">

    <div id="preview">
      <canvas width="100" height="70" id="canvas-preview"></canvas>
    </div>

        <form class="form-inline">
          <div class="form-group">
            <div class="form-check">
              <label class="form-check-label" for="fromdate">
                Date From: 
              </label>
              <input class="form-control" type="month" id="fromdate" name="fromdate" >
            </div>
            <div class="form-check">
              <label class="form-check-label" for="todate">
                To: 
              </label>
              <input class="form-control" type="month" id="todate" name="todate" >
            </div>
            <button class="btn btn-primary" type="button" id="fetch">Fetch Data</button>
          </div>
        </form>

        <form class="form-inline">
          <div class="form-group">
            <div class="form-check">
              <input class="form-check-input" type="radio" name="type" id="dis" value="dis" checked>
              <label class="form-check-label" for="dis">
                Discharge
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="type" id="reg" value="reg">
              <label class="form-check-label" for="reg">
                Registration
              </label>
            </div>
          </div>
        </form>

        <div id="output"></div>
      </div>
    </div>
  </div>
</section>
@endsection

@section('js')
    <script src="{{ asset('js/reveis.js') }}"></script>
    <script src="{{ asset('assets/gauge.min.js') }}"></script>
    <!-- <script src="{{ asset('js/izitoast/dist/js/iziToast.min.js') }}"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/localforage/1.9.0/localforage.min.js" integrity="sha512-GkJRKF+k/yzHfJUg9LrNLQhS0jorQe4kES+GHkqtQThCJ5z5A1KwCXH0GYbJApDh/a3ERFvq9xbRJY9mEXzQiA==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.5/d3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/c3/0.4.11/c3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
    <script src="https://cdn.plot.ly/plotly-basic-latest.min.js"></script>
    <script src="https://pivottable.js.org/dist/pivot.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pivottable/2.23.0/c3_renderers.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pivottable/2.23.0/plotly_renderers.min.js"></script>
@endsection

@section('stylesheet')
@endsection
