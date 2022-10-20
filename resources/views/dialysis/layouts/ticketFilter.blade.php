<?php
	$display = (Auth::user()->type=='customer') ? "none":"";
?>

<div class="ui teal segment" style="padding-bottom: 30px;">
	<form method="GET" class="ui form" id="filterForm_basic" action="/ticket" name="ticketSearch" >
		<div class="three fields">
			<div class="field">
				<label>Posted By</label>
				<input type="text" name="postby" placeholder="Posted By" value="@if(!empty(Request::input('postby'))){{Request::input('postby')}}@endif">
			</div>
			<div class="field">
				<label>Question</label>
				<input type="text" name="question" placeholder="Question" value="@if(!empty(Request::input('question'))){{Request::input('question')}}@endif">
			</div>
			<div class="field"><button class="ui teal button " style="margin-left: 0px;margin-top: 22px"> Search Chat </button></div>
		</div>

	</form>
</div>