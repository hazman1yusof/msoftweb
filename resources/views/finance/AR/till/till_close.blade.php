@extends('layouts.main')

@section('title', 'Close Till')

@section('body')
	<div class="panel panel-default" style="margin:20px">
	    <div class="panel-heading">Close Till</div>
	    <div class="panel-body">
			<div class='panel panel-info'>
				<div class='col-md-12' style="padding:0 0 15px 0">
					<div class="form-group">
						<div class='col-md-2 minuspad-15'>
							<label>Till Code: </label><input id="tillcode" name="tillcode" type="text" class="form-control input-sm" data-validation="required" rdonly>
						</div>

						<div class='col-md-4 '>
							<label>Department: </label><input id="deptcode" name="deptcode" type="text" class="form-control input-sm" rdonly>
						</div>
					</div>		
				</div>

				<div class='col-md-12' style="padding:0 0 15px 0">
					<div class="form-group">
						<div class='col-md-2 minuspad-15'>
							<label>Open Date: </label><input id="effectdate" name="effectdate" type="date" class="form-control input-sm" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
						</div>

						<div class='col-md-2 '>
							<label>Time: </label><input id="effecttime" name="effecttime" type="text" class="form-control input-sm" rdonly>
						</div>
					</div>		
				</div>

				<div class='col-md-12' style="padding:0 0 15px 0">
					<div class="form-group">
						<div class='col-md-2 minuspad-15'>
							<label>Cash Opening Amount: </label><input id="cashOpenAmt" name="cashOpenAmt" type="text" class="form-control input-sm" data-validation="required" value="0.00" >
						</div>

						<div class='col-md-2 '>
							<label>Cashier: </label><input id="cashier" name="cashier" type="text" class="form-control input-sm" rdonly>
						</div>
					</div>		
				</div>
			</div>
			
			<div class='col-md-6'>
				<div class='panel panel-info'>
					<div class="panel-body">
						<div class="form-group">
							<h6>Amount Collected: </h6>
							<label class="col-md-2 control-label" for="CashCollected">Cash</label>  
								<div class="col-md-3">
									<input id="CashCollected" name="CashCollected" type="text" class="form-control input-sm" value="0.00" >
							</div>

							<label class="col-md-2 control-label" for="CashRefund">Cash</label>  
								<div class="col-md-3">
									<input id="CashRefund" name="CashRefund" type="text" class="form-control input-sm" value="0.00" >
							</div>
						</div>
					</div>

					<div class="panel-body">
						<div class="form-group">
							<label class="col-md-2 control-label" for="ChequeCollected">Cheque</label>  
								<div class="col-md-3">
									<input id="ChequeCollected" name="ChequeCollected" type="text" class="form-control input-sm" value="0.00" >
							</div>

							<label class="col-md-2 control-label" for="ChequeRefund">Cheque</label>  
								<div class="col-md-3">
									<input id="ChequeRefund" name="ChequeRefund" type="text" class="form-control input-sm" value="0.00" >
							</div>
						</div>
					</div>

					<div class="panel-body">
						<div class="form-group">
							<label class="col-md-2 control-label" for="CardCollected">Card</label>  
								<div class="col-md-3">
									<input id="CardCollected" name="CardCollected" type="text" class="form-control input-sm" value="0.00">
							</div>

							<label class="col-md-2 control-label" for="CardRefund">Card</label>  
								<div class="col-md-3">
									<input id="CardRefund" name="CardRefund" type="text" class="form-control input-sm" value="0.00">
							</div>
						</div>
					</div>

					<div class="panel-body">
						<div class="form-group">
							<label class="col-md-2 control-label" for="DebitCollected">Auto Debit</label>  
								<div class="col-md-3">
									<input id="DebitCollected" name="DebitCollected" type="text" class="form-control input-sm" value="0.00">
							</div>

							<label class="col-md-2 control-label" for="DebitRefund">Auto Debit</label>  
								<div class="col-md-3">
									<input id="DebitRefund" name="DebitRefund" type="text" class="form-control input-sm" value="0.00">
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class='col-md-6'>
				<div class='panel panel-info'>
					<div class="panel-body">
						<div class="form-group">
							<label class="col-md-2 control-label" for="closedate">Close Date</label>  
								<div class="col-md-3">
									<input id="closedate" name="closedate" type="date" class="form-control input-sm" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
							</div>

							<label class="col-md-2 control-label" for="closetime">TIme</label>  
								<div class="col-md-3">
									<input id="closetime" name="closetime" type="text" class="form-control input-sm">
							</div>
						</div>
					</div>

					<div class="panel-body">
						<div class="form-group">
							<label class="col-md-2 control-label" for="cashCloseBal">Cash Closing Bal.</label>  
								<div class="col-md-3">
									<input id="cashBal" name="cashBal" type="text" class="form-control input-sm" value="0.00">
							</div>

							<label class="col-md-2 control-label" for="Cashier">Cashier</label>  
								<div class="col-md-3">
									<input id="Cashier" name="Cashier" type="text" class="form-control input-sm">
							</div>
						</div>
					</div>

					<div class="panel-body">
						<div class="form-group">
							<label class="col-md-2 control-label" for="ActCloseBal">Actual Closing Balance</label>  
								<div class="col-md-3">
									<input id="ActCloseBal" name="ActCloseBal" type="text" class="form-control input-sm" value="0.00">
							</div>

							<label class="col-md-2 control-label" for="discrepancy">Discrepancy</label>  
								<div class="col-md-3">
									<input id="discrepancy" name="discrepancy" type="text" class="form-control input-sm" value="0.00">
							</div>
						</div>
					</div>

					<div class="panel-body">
						<div class="form-group">
							<label class="col-md-2 control-label" for="remarks">Reason</label> 
								<div class="col-md-8"> 
									<textarea class="form-control input-sm text-uppercase" name="remarks" rows="3" cols="55" maxlength="400" id="remarks" ></textarea>
								</div>
						</div>
					</div>
				</div>
			</div>
	    </div>
	</div>
@endsection


@section('scripts')
	<script type="text/javascript">
		$(document).ready(function () {
			if(!$("table#jqGrid").is("[tabindex]")){
				$("#jqGrid").bind("jqGridGridComplete", function () {
					$("table#jqGrid").attr('tabindex',2);
					$("td#input_jqGridPager input.ui-pg-input.form-control").attr('tabindex',3);
					$("td#input_jqGridPager input.ui-pg-input.form-control").on('focus',onfocus_pageof);
					if($('table#jqGrid').data('enter')){
						$('td#input_jqGridPager input.ui-pg-input.form-control').focus();
						$("table#jqGrid").data('enter',false);
					}
				});
			}

			function onfocus_pageof(){
				$(this).keydown(function(e){
					var code = e.keyCode || e.which;
					if (code == '9'){
						e.preventDefault();
						$('input[name=Stext]').focus();
					}
				});

				$(this).keyup(function(e) {
					var code = e.keyCode || e.which;
					if (code == '13'){
						$("table#jqGrid").data('enter',true);
					}
				});
			}
		});
	</script>

	<script src="js/finance/AR/till/till_close.js"></script>
@endsection