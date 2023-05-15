@extends('layouts.main_fomantic')

@section('title', 'Computer ID Setup')

@section('style')
  .error_{
    border-color: #740b0b !important;
    background: #ffefef !important;
  }
  .success_{
    border-color: #004c00 !important;
    background: #f3fff3 !important;
  }
@endsection

@section('content')

<div class="ui container" style="margin-top: 50px;">
  <div class="ui secondary segment bluecloudsegment">
      <h4>Setup Computer ID</h4>
  </div>
  <div class="ui segment diaform">
      <form class="ui form">
        <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
        <div class="field">
          <label>Computer ID</label>
          <input type="text" name="computerid" id="computerid" autocomplete="off">
        </div>
         <button type="button" class="ui button" onclick="setcompid()">Set ID</button>
      </form>
  </div>
</div>

@endsection


@section('scripts')
	<script type="text/javascript" src="{{ asset('js/setup/computerid/computerid.js') }}"></script>
@endsection