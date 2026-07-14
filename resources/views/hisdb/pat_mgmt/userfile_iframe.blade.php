@extends('hisdb.pat_mgmt.semantic_iframe_main')

@section('title', 'Episode Iframe')

@section('css')
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

    div.imgcontainer a.circular.orange.btn{
        position: absolute;
        top: 20%;
        right: 20%;
    }
</style>

<script>
</script>
@endsection

@section('body')

<div id="pageDimmer" class="dimmer-overlay">
    <div class="text-center">
        <!-- Your Custom Loading Text -->
        <h3 class="fw-normal" id="textDimmer">Reading Mykad, please wait...</h3>
    </div>
</div>

<input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">
<input type="hidden" id="mrn_apptMain" value="{{$mrn}}">
<input type="hidden" id="episno_apptMain" value="{{$episno}}">

<div class="ui teal segment" id="allmodal">
    <h4 class="ui header">This user uploaded Files </h4>
    <button class="tiny ui icon button" type="button" id="refresh_userfile_btn" style="
        position: absolute;
        top: 5px;
        right: 20px;
        z-index: 5;
     ">
      <i class="undo icon"></i>
    </button>
    <button class="tiny ui orange icon button" type="button" id="upload_userfile_btn" style="
        position: absolute;
        top: 5px;
        right: 60px;
        z-index: 5;
     "> Upload
      <i class="cloud upload icon"></i>
    </button>
    <input type="file" id="upload_userfile_fld" name="upload_userfile_fld" style="display: none;">
    <!-- <input type='hidden' id="userfile_mrn" name="userfile_mrn"> -->

    <div class="ui top attached tabular menu" id="userfile_tab">
      <a class="active item" data-tab="document">Document</a>
      <a class="item" data-tab="imaging">Imaging</a>
      <a class="item" data-tab="lab">Lab Test</a>
    </div>
    <div class="ui tab" data-tab="document"></div>
    <div class="ui tab" data-tab="imaging"></div>
    <div class="ui tab" data-tab="lab"></div>
    <br>

    <table class="ui celled table" id='tablePreview'>
        <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Remark</th>
                <th>File Preview</th>
                <th>MRN</th>
                <th>Add User</th>
                <th>Add Date</th>
                <th>Download</th>
                <th>type</th>
                <th>Delete</th>
            </tr>
        </thead>
    </table>
</div>

@endsection

@section('js')
<script type="text/javascript" src="{{asset('js/hisdb/pat_mgmt/userfile_iframe.js')}}"></script>
@endsection