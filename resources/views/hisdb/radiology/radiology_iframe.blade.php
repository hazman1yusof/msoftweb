@extends('hisdb.pat_mgmt.semantic_iframe_main')

@section('title', 'Radiology Iframe')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('patientcare/css/main.css') }}">
<link rel="stylesheet" href="{{asset('patientcare/css/doctornote.css') }}">

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
<style>
    #allmodal{
        padding: 10px ;
        height: 100vh;
        width: 100vw;
        overflow-x: hidden;
        margin: 0px ;
    }
    .dimmer-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background-color: rgba(0, 0, 0, 0.7); /* Slightly darker for better text contrast */
        z-index: 1060; /* Higher than default navbars and modals */
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
        
        /* Flexbox to center the text and spinner */
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        color: #ffffff; /* White text */
    }

    .dimmer-overlay.active {
        opacity: 1;
        visibility: visible;
    }
</style>
@endsection

@section('body')

<div id="pageDimmer" class="dimmer-overlay">
    <div class="text-center">
        <!-- Your Custom Loading Text -->
        <h3 class="fw-normal" id="textDimmer">Loading...</h3>
    </div>
</div>

<div class="ui column">
    <form id="formRequestFor">
        <input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">
        <input id="mrn_requestFor" name="mrn_requestFor" type="hidden" value="{{$mrn}}">
        <input id="episno_requestFor" name="episno_requestFor" type="hidden" value="{{$episno}}">
    </form>

    <!-- <div id="requestFor" class="ui segments" style="margin:0px;border: none;">
    </div> -->

    <div id="radiology" class="ui segment" style="margin: 0px;border: none;padding: 0px;">
        @include('hisdb.radiology.radiology_inside_iframe')
    </div>
</div>

@endsection

@section('js')
<script type="text/javascript" src="{{asset('patientcare/js/requestfor_iframe.js')}}"></script>
@endsection