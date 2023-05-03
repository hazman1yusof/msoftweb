@extends('layouts.main_fomantic')

@section('title', 'Quotation')

@section('style')
  a.all_attach{
    margin:2px !important;
  }
  label.error{
    color:darkred !important;
  }
  input.error{
    border-color: darkred !important;
  }
  #quote tr.active{
    background: #e6f9ff;
  }
  .ui.blue2.button{
    background-color: #d9efff !important;
    color: #000 !important;
    box-shadow: 1px 1px 0 0 #bdbdbd, 0 0 0 0 rgba(34,36,38,.15) inset;
  }
@endsection

@section('content')

<div class="ui container" style="margin-top: 50px;">

	<table class="ui celled table" id="quote">
	  <thead>
	    <tr>
	    	<th>ID</th>
		    <th>compcode</th>
		    <th>Subject<button class="ui green icon button right floated" type="button" id="add_quote"><i class="plus icon"></i> Add Quotation</button></th>
		    <th>dept</th>
	  	</tr>
		</thead>
	</table>

</div>

<div class="ui modal" id="quote_modal">
  <!-- <i class="close icon"></i> -->
  <div class="header">
    Add Quotation
  </div>
  <div class="content">
    <form class="ui form" id="formdata">
      <input id="idno" name="idno" type="hidden">
      <input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">
      <div class="field">
        <label>Subject</label>
        <input type="text" id="subject" name="subject" placeholder="Subject" required>
      </div>
      <div class="field" >
        <textarea id="particulars" name="particulars"></textarea>
        <!-- <div id="summernote"></div> -->
      </div>
      <button type="button" id='click' class='ui icon button btn' ><i class="paperclip icon"></i> Upload Attachment</button>
      <input type="file" name="file" id="file" accept="audio/*,image/*,video/*,application/pdf" style="display: none;">
      <div class="ui segment" id="all_attach">
      </div>
    </form>
  </div>
  <div class="actions">
    <button class="ui black deny button">
      Cancel
    </button>
    <button class="ui positive right labeled icon button">
      Submit
      <i class="checkmark icon"></i>
    </button>
  </div>
</div>

@endsection


@section('scripts')
  <!-- include summernote css/js -->
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
	<script type="text/javascript" src="{{ asset('js/material/Quote/Quote.js') }}"></script>
@endsection