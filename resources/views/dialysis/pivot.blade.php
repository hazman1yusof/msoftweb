@extends('layouts.main')

@section('title')
Pivot | Pivot
@endsection

@section('css')
    <style id="plotly.js-style-global"></style>
    <link rel="stylesheet" type="text/css" href="https://pivottable.js.org/dist/pivot.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/c3/0.4.11/c3.min.css">
@endsection

@section('style')
    .ui.toggle.checkbox .box:before, .ui.toggle.checkbox label:before {
        background: #db2828 !important;
    }

    .ui.toggle.checkbox input:focus~.box:before, .ui.toggle.checkbox input:focus~label:before {
        background-color: #db2828;
        border: none;
    }
    .ui.toggle.checkbox .box:hover::before, .ui.toggle.checkbox label:hover::before {
        background-color: #db282875;
        border: none;
    }
    .ui.checkbox input:focus~.box:before, .ui.checkbox input:focus~label:before {
        background: #db28289e;
        border-color: #96c8da;
    }
    .pvtFilterBox{
        z-index: 120 !important;
    }
    body{
        overflow-x: scroll !important;
    }
    .pvtHorizList li { display: inline-block !important; } 
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.5/d3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/c3/0.4.11/c3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
    <script src="https://cdn.plot.ly/plotly-basic-latest.min.js"></script>
    <script src="https://pivottable.js.org/dist/pivot.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pivottable/2.23.0/c3_renderers.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pivottable/2.23.0/plotly_renderers.min.js"></script>
    <script src="{{ asset('js/pivot.js') }}"></script>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                <h3 class="card-title">
                    Data Analysis
                </h3>

                <div class="ui form">
                  <div class="inline fields">
                    <div class="field">
                      <div class="ui radio checkbox">
                        <input type="radio" name="type" value="dis" checked="checked">
                        <label>Discharge</label>
                      </div>
                    </div>
                    <div class="field">
                      <div class="ui radio checkbox">
                        <input type="radio" name="type" value="reg">
                        <label>Registration</label>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="ui divider"></div>

                <div id="output">
                    
                </div>
                
                
              </div>
            </div>
        </div>
    </div>
</div>
@endsection