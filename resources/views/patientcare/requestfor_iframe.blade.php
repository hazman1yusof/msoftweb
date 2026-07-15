@extends('hisdb.pat_mgmt.semantic_iframe_main')

@section('title', 'Request for Iframe')

@section('css')

<link rel="stylesheet" type="text/css" href="{{ asset('patientcare/css/main.css') }}">
<link rel="stylesheet" href="{{asset('patientcare/css/doctornote.css') }}">
<style>
    #allmodal{
        padding: 10px ;
        height: 100vh;
        width: 100vw;
        overflow-x: hidden;
        margin: 0px ;
    }
    
    .preloader {
          width: 100%;
          height: 100%;
          top: 0;
          position: fixed;
          z-index: 99999;
          background: #fff;
    }
    .cssload-speeding-wheel {
        position: absolute;
        top: calc(50% - 3.5px);
        left: calc(50% - 3.5px);
        width: 31px;
        height: 31px;
        margin: 0 auto;
        border: 2px solid rgba(97,100,193,0.98);
        border-radius: 50%;
        border-left-color: transparent;
        border-right-color: transparent;
        animation: cssload-spin 425ms infinite linear;
        -o-animation: cssload-spin 425ms infinite linear;
        -ms-animation: cssload-spin 425ms infinite linear;
        -webkit-animation: cssload-spin 425ms infinite linear;
        -moz-animation: cssload-spin 425ms infinite linear;
    }
    @keyframes cssload-spin {
        100%{ transform: rotate(360deg); transform: rotate(360deg); }
    }

    @-o-keyframes cssload-spin {
        100%{ -o-transform: rotate(360deg); transform: rotate(360deg); }
    }

    @-ms-keyframes cssload-spin {
        100%{ -ms-transform: rotate(360deg); transform: rotate(360deg); }
    }

    @-webkit-keyframes cssload-spin {
        100%{ -webkit-transform: rotate(360deg); transform: rotate(360deg); }
    }

    @-moz-keyframes cssload-spin {
        100%{ -moz-transform: rotate(360deg); transform: rotate(360deg); }
    }

    .red.ui.right.labeled.input input{
        color: white !important;
        border-color: red !important;
        background-color: red !important;
    }
    
    .red.ui.table tr{
        color: white;
        background-color: red !important;
    }
    
    .red.ui.action.input input{
        color: white !important;
        border-color: red !important;
        background-color: red !important;
    }
    
    .yellow.ui.right.labeled.input input{
        color: black !important;
        border-color: #9e9e00 !important;
        background-color: yellow !important;
    }
    
    .yellow.ui.table tr{
        background-color: yellow !important;
    }
    
    .yellow.ui.action.input input{
        color: black !important;
        border-color: #9e9e00 !important;
        background-color: yellow !important;
    }
    
    .green.ui.right.labeled.input input{
        color: white !important;
        border-color: green !important;
        background-color: green !important;
    }
    
    .green.ui.table tr{
        color: white;
        background-color: green !important;
    }
    
    .green.ui.action.input input{
        color: white !important;
        border-color: green !important;
        background-color: green !important;
    }
</style>

<script>
    @if(empty($pat_mast_data))
        var pat_mast_data = null;
    @else
        var pat_mast_data = {
            @foreach($pat_mast_data as $key => $val) 
                '{{$key}}' : `{!!str_replace('`', '', $val)!!}`,
            @endforeach 
        };
    @endif


    @if(empty($episode_data))
        var episode_data = null;
    @else
        var episode_data = {
            @foreach($episode_data as $key => $val) 
                '{{$key}}' : `{!!str_replace('`', '', $val)!!}`,
            @endforeach 
        };
    @endif
</script>
@endsection

@section('body')
<div class="preloader">
    <div class="cssload-speeding-wheel"></div>
</div>

<input type="hidden" id="_mrn" value="{{$mrn}}">
<input type="hidden" id="_episno" value="{{$episno}}">
<input type="hidden" id="_phase" value="{{$phase}}">
<input id="age_requestFor" name="age_requestFor" type="hidden" value="{{$pat_mast_data->age}}">
<input id="ptname_requestFor" name="ptname_requestFor" type="hidden" value="{{$pat_mast_data->Name}}">
<input id="preg_requestFor" name="preg_requestFor" type="hidden" value="{{$episode_data->pregnant}}">
<input id="ic_requestFor" name="ic_requestFor" type="hidden" value="{{$pat_mast_data->Newic}}">
<input id="doctorname_requestFor" name="doctorname_requestFor" type="hidden" value="{{$episode_data->doctorname}}">
<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

@include('patientcare.requestfor')

@endsection

@section('js')
<script type="text/javascript" src="{{asset('patientcare/js/requestfor_iframe.js')}}"></script>
@endsection