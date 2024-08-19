@extends('layouts.authdtl_mobile')

@section('title', 'Payment Voucher Mobile')

@section('css')
<style>
	.ui.segment, .ui.segments .segment {
    font-size: 0.85rem;
	}
	#main_container{
		padding-top: 10px;
	}
	#main_segment{
		height: 70vh;
	}
	#mygrid span{
/*		padding-left: 4px;*/
	}
	#mygrid .column{
		padding: 5px 0px;
	}
	#mygrid .column.cont1{
		padding: 5px 0px 5px 0px;
	}
	#mygrid .column.cont2{
		padding: 5px 0px 5px 0px;
	}
	#mygrid .row{
		padding: 0px;
	}
	.ui.grid {
	   margin: 0rem; 
	}
	span.span_txt{
		color: #0057b1;
    display: block;
    margin-top: -5px;
	}
</style>
@endsection

@section('content')
<div id="main_container" class="ui container">
	<div class="ui segments" id="main_segment">
	  <div class="ui secondary segment" id="main_title">
	    <h3>Payment Voucher</h3>
	  </div>
	  <div class="ui attached segment" id="main_segment" style="overflow:auto;">
	  	<h3>Header</h3>
	  	<div class="ui grid" id="mygrid">
	  		<div class="row">
		  		<div class="eight wide column ">
		  		 <b>Audit No:</b><span> {{str_pad($ap_hd->auditno, 7, '0', STR_PAD_LEFT)}}</span>
		  		</div>
		  		<!-- <div class="eight wide column cont2">
		  		 <b>Quotation No:</b><span></span>
		  		</div> -->
	  		</div>
	  		<div class="row">
		  		<div class="four wide column cont1">
		  			<b>Pay Mode:</b>
		  		</div>
		  		<div class="eleven wide column cont2">
		  		 <span>{{$ap_hd->paymode_desc}}</span>
		  		</div>
	  		</div>
	  		<div class="row">
		  		<div class="four wide column cont1">
		  			<b>Bank Code:</b>
		  		</div>
		  		<div class="eleven wide column cont2">
		  		 <span>{{$ap_hd->bankcode_desc}}</span>
		  		</div>
	  		</div>
	  		<div class="row">
		  		<div class="four wide column cont1">
		  			<b>Pay to:</b>
		  		</div>
		  		<div class="eleven wide column cont2">
		  		 <span>{{$ap_hd->suppcode_desc}}</span>
		  		</div>
	  		</div>
	  		<div class="row">
		  		<div class="four wide column cont1">
		  			<b>Remarks:</b>
		  		</div>
		  		<div class="eleven wide column cont2">
		  		 <span>{{$ap_hd->remarks}}</span>
		  		</div>
	  		</div>
	  		<div class="row">
		  		<div class="eight wide column">
		  		 <b>Date:</b><span> {{\Carbon\Carbon::parse($ap_hd->actdate)->format('d-m-Y')}}</span>
		  		</div>
		  		<div class="eight wide column cont2">
		  		 <b>Total Amount:</b><span> {{number_format($ap_hd->amount,2)}}</span>
		  		</div>
	  		</div>
	  	</div>
	  	<div class="ui divider"></div>
	  	<h3>Detail</h3>
	  	<table class="ui selectable celled striped table">
			  <tbody>
			  	@foreach($ap_dt as $dt)
			    <tr>
			      <td><b>Creditor</b> <span class="span_txt">{{$dt->suppcode_desc}}</span></td>
			      <td><b>Invoice Date</b> <span class="span_txt">{{$dt->allocdate}}</span></td>
			      <td><b>Invoice No</b> <span class="span_txt">{{$dt->reference}}</span></td>
			      <td><b>Amount</b> <span class="span_txt">{{number_format($dt->refamount)}}</span></td>
			      <td><b>O/S Amount</b> <span class="span_txt">{{number_format($dt->outamount)}}</span></td>
			      <td><b>Amount Paid</b> <span class="span_txt">{{number_format($dt->allocamount,2)}}</span></td>
			      <td><b>Balance</b> <span class="span_txt">{{number_format($dt->balance,2)}}</span></td>
			    </tr>
			    @endforeach
			  </tbody>
			</table>
	  </div>
		<div class="ui two bottom attached buttons">
		    <div class="ui negative button" id="reject">Reject</div>
		    <div class="ui positive button" id="post">{{$scope}}</div>
		</div>
	</div>
</div>

<div id='remark_modal' class="ui modal">
  <div class="content">
		<form class="ui form" id="formdata">
			<input type="hidden" id="oper" name="oper" value="{{$oper}}">
			<input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
  		<input type="hidden" id="idno" name="idno" value="{{$ap_hd->idno}}">
		  <div class="field">
		    <label>Remark</label>
		    <textarea rows="5" name="remarks" id="remarks"></textarea>
		  </div>
	  </form>
  </div>
  <div class="actions">
    <button class="ui negative button">Cancel</button>
    <button class="ui positive button" id="submit_remark">Submit</button>
  </div>
</div>
@endsection

@section('js')
<script>
	$(document).ready(function(){
		$("form#formdata").form({
			inline: true,
    	fields: {
	      remarks : ['maxLength[222]', 'empty']
	    }
		});

		$('div#post').click(function(){
			$("form#formdata").form('remove field','remarks');

			$('#remark_modal.modal')
			  .modal({
			    closable: false,
			    onApprove:function(){
			    	$('button#submit_remark').attr('disabled');
			    	return save_authdtl($('#oper').val());
			    }
			  }).modal('show');
		});

		$('div#reject').click(function(){
			if (confirm("Are you sure to reject this purchase request?") == true) {
				$("form#formdata").form('add rule', 'remarks', ['maxLength[222]', 'empty'])
				
			  $('#remark_modal.modal')
				  .modal({
				    closable: false,
				    onApprove:function(){
				    	$('button#submit_remark').attr('disabled');
				    	return save_authdtl('reject');
				    }
				  }).modal('show');
			}
		});
	});

	function save_authdtl(status){

		$('form#formdata').form('validate form');

		if(!$('form#formdata').form('is valid')){
			return false;
		}

		var obj={};
		obj.idno_array = [$('#idno').val()];
		obj.oper = status;
		obj._token = $('#_token').val();
		obj.remarks = $('#remarks').val();
		
		$.post( './SalesOrder/form', obj , function( data ) {

		}).fail(function(data) {

		}).success(function(data){
			close_and_refresh();
		});
	}

	function close_and_refresh(){
		if (window.frameElement) {
			parent.close_and_refresh();
		}
	}
</script>
@endsection