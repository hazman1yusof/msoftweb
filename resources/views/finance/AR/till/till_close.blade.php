@extends('layouts.main')

@section('title', 'Close Till')

@section('body')
	<div class="panel panel-default" style="margin:20px">
	    <div class="panel-heading">Close Till</div>
	    <div class="panel-body">
	    	<div class="col-md-12">
			<div class='panel panel-info'><div class="panel-body">
				<div class='col-md-12' style="padding:0 0 15px 0">
					<div class="form-group">
						<div class='col-md-2 minuspad-15'>
							<label>Till Code: </label><input id="tillcode" name="tillcode" type="text" class="form-control input-sm" data-validation="required" readonly value="@if (!empty($till)){{$till->tillcode}}@endif">
						</div>

						<div class='col-md-4 '>
							<label>Department: </label><input id="dept" name="dept" type="text" class="form-control input-sm" readonly value="@if (!empty($till)){{$till->dept}}@endif">
						</div>
					</div>		
				</div>

				<div class='col-md-12' style="padding:0 0 15px 0">
					<div class="form-group">
						<div class='col-md-2 minuspad-15'>
							<label>Open Date: </label><input id="opendate" name="opendate" type="date" class="form-control input-sm" readonly value="@if (!empty($tilldetl)){{$tilldetl->opendate}}@endif">
						</div>

						<div class='col-md-2 '>
							<label>Time: </label><input id="opentime" name="opentime" type="text" class="form-control input-sm" readonly value="@if (!empty($tilldetl)){{$tilldetl->opentime}}@endif">
						</div>
					</div>		
				</div>

				<div class='col-md-12' style="padding:0 0 15px 0">
					<div class="form-group">
						<div class='col-md-2 minuspad-15'>
							<label>Cash Opening Amount: </label><input id="openamt" name="openamt" type="text" class="form-control input-sm" data-validation="required" readonly value="@if (!empty($tilldetl)){{$tilldetl->openamt}}@endif">
						</div>

						<div class='col-md-2 '>
							<label>Cashier: </label><input id="cashier" name="cashier" type="text" class="form-control input-sm" readonly value="{{Auth::user()->username}}">
						</div>
					</div>		
				</div>
			</div></div>
			</div>
			
			<div class='col-md-6'>
				<div class='panel panel-info'>
					<div class="panel-body">
						<div class="col-md-4  col-md-offset-1" style="text-align: center;margin-bottom: 10px;padding-left: 60px;"><h6><b>Amount Collected: </b></h6></div>
						<div class="col-md-7" style="text-align: center;margin-bottom: 10px;"><h6><b>Amount Refund: </b></h6></div>
						<div class=""></div>
						<div class="form-group">
							<label class="col-md-2 control-label" for="CashCollected">Cash</label>  
								<div class="col-md-3">
									<input id="CashCollected" name="CashCollected" type="text" class="form-control input-sm" value="@if(!empty($sum_cash)){{$sum_cash}}@else{{number_format(0.00,2)}}@endif" readonly>
							</div>

							<label class="col-md-2 control-label" for="CashRefund">Cash</label>  
								<div class="col-md-3">
									<input id="CashRefund" name="CashRefund" type="text" class="form-control input-sm" value="0.00" readonly>
							</div>
						</div>
					</div>

					<div class="panel-body">
						<div class="form-group">
							<label class="col-md-2 control-label" for="ChequeCollected">Cheque</label>  
								<div class="col-md-3">
									<input id="ChequeCollected" name="ChequeCollected" type="text" class="form-control input-sm" value="@if(!empty($sum_chq)){{$sum_chq}}@else{{number_format(0.00,2)}}@endif" readonly>
							</div>

							<label class="col-md-2 control-label" for="ChequeRefund">Cheque</label>  
								<div class="col-md-3">
									<input id="ChequeRefund" name="ChequeRefund" type="text" class="form-control input-sm" value="0.00" readonly>
							</div>
						</div>
					</div>

					<div class="panel-body">
						<div class="form-group">
							<label class="col-md-2 control-label" for="CardCollected">Card</label>  
								<div class="col-md-3">
									<input id="CardCollected" name="CardCollected" type="text" class="form-control input-sm" value="@if(!empty($sum_card)){{$sum_card}}@else{{number_format(0.00,2)}}@endif" readonly>
							</div>

							<label class="col-md-2 control-label" for="CardRefund">Card</label>  
								<div class="col-md-3">
									<input id="CardRefund" name="CardRefund" type="text" class="form-control input-sm" value="0.00" readonly>
							</div>
						</div>
					</div>

					<div class="panel-body">
						<div class="form-group">
							<label class="col-md-2 control-label" for="DebitCollected">Auto Debit</label>  
								<div class="col-md-3">
									<input id="DebitCollected" name="DebitCollected" type="text" class="form-control input-sm" value="@if(!empty($sum_bank)){{$sum_bank}}@else{{number_format(0.00,2)}}@endif" readonly>
							</div>

							<label class="col-md-2 control-label" for="DebitRefund">Auto Debit</label>  
								<div class="col-md-3">
									<input id="DebitRefund" name="DebitRefund" type="text" class="form-control input-sm" value="0.00" readonly>
							</div>
							<br><br>
						</div>

						<div class="form-group">
							<div class='col-md-10' style="padding-right: 0px;">
								<div class="panel panel-info"><br>
								<div class="col-md-4  col-md-offset-1" style="text-align: center;margin-bottom: 10px;padding-left: 60px;"><h6><b>Denomination: </b></h6></div><br><br>
									<div class="panel-body">
										<div class="form-group">
											<label class="col-md-3 control-label" for="rm100">RM 100</label>
											<label class="col-md-1 control-label" for="darab">X</label>

											<div class="col-md-3">
												<input name="bilrm100" type="number" class="form-control input-sm" value="0" data-bill='100'>
											</div>

											<label class="col-md-1 control-label" for="abortus">=</label>
											<div class="col-md-3">
												<input name="totalrm100" type="number" class="form-control input-sm" value="0.00" readonly>
											</div>
										</div>
									</div>

									<div class="panel-body">
										<div class="form-group">
											<label class="col-md-3 control-label" for="rm50">RM 50</label>
											<label class="col-md-1 control-label" for="darab">X</label>

											<div class="col-md-3">
												<input name="bilrm50" type="number" class="form-control input-sm" value="0"  data-bill='50'>
											</div>

											<label class="col-md-1 control-label" for="abortus">=</label>
											<div class="col-md-3">
												<input name="totalrm50" type="number" class="form-control input-sm" value="0.00" readonly>
											</div>
										</div>
									</div>

									<div class="panel-body">
										<div class="form-group">
											<label class="col-md-3 control-label" for="rm20">RM 20</label>
											<label class="col-md-1 control-label" for="darab">X</label>

											<div class="col-md-3">
												<input name="bilrm20" type="number" class="form-control input-sm" value="0"  data-bill='20'>
											</div>

											<label class="col-md-1 control-label" for="abortus">=</label>
											<div class="col-md-3">
												<input name="totalrm20" type="number" class="form-control input-sm" value="0.00" readonly>
											</div>
										</div>
									</div>

									<div class="panel-body">
										<div class="form-group">
											<label class="col-md-3 control-label" for="rm10">RM 10</label>
											<label class="col-md-1 control-label" for="darab">X</label>

											<div class="col-md-3">
												<input name="bilrm10" type="number" class="form-control input-sm" value="0"  data-bill='10'>
											</div>

											<label class="col-md-1 control-label" for="abortus">=</label>
											<div class="col-md-3">
												<input name="totalrm10" type="number" class="form-control input-sm" value="0.00" readonly>
											</div>
										</div>
									</div>

									<div class="panel-body">
										<div class="form-group">
											<label class="col-md-3 control-label" for="rm5">RM 5</label>
											<label class="col-md-1 control-label" for="darab">X</label>

											<div class="col-md-3">
												<input name="bilrm5" type="number" class="form-control input-sm" value="0"  data-bill='5'>
											</div>

											<label class="col-md-1 control-label" for="abortus">=</label>
											<div class="col-md-3">
												<input name="totalrm5" type="number" class="form-control input-sm" value="0.00" readonly>
											</div>
										</div>
									</div>

									<div class="panel-body">
										<div class="form-group">
											<label class="col-md-3 control-label" for="rm1">RM 1</label>
											<label class="col-md-1 control-label" for="darab">X</label>

											<div class="col-md-3">
												<input name="bilrm1" type="number" class="form-control input-sm" value="0"  data-bill='1'>
											</div>

											<label class="col-md-1 control-label" for="abortus">=</label>
											<div class="col-md-3">
												<input name="totalrm1" type="number" class="form-control input-sm" value="0.00" readonly>
											</div>
										</div>
									</div>

									<div class="panel-body">
										<div class="form-group">
											<label class="col-md-3 control-label" for="cents">CENTS</label>
											<label class="col-md-1 control-label" for="darab">X</label>

											<div class="col-md-3">
												<input name="bilcents" type="number" class="form-control input-sm" value="0.00"  data-bill='1'>
											</div>

											<label class="col-md-1 control-label" for="abortus">=</label>
											<div class="col-md-3">
												<input name="totalcents" type="number" class="form-control input-sm" value="0.00" readonly>
											</div>
										</div>
									</div>

									<div class="panel-body">
										<div class="form-group">
											<label class="col-md-7 control-label" for="grandTotal">TOTAL</label>
											<div class="col-md-4">
												<input name="grandTotal" type="number" class="form-control input-sm" value="0.00" readonly>
											</div>
										</div>
									</div>								
								</div>
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
									<input id="closedate" name="closedate" type="date" class="form-control input-sm" value="{{Carbon\Carbon::now()->format('Y-m-d')}}" readonly>
							</div>

							<label class="col-md-2 control-label" for="closetime">TIme</label>  
								<div class="col-md-3">
									<input id="closetime" name="closetime" type="time" class="form-control input-sm" value="{{Carbon\Carbon::now()->format('H:i:s')}}" readonly>
							</div>
						</div>
					</div>

					<div class="panel-body">
						<div class="form-group">
							<label class="col-md-2 control-label" for="cashCloseBal">Cash Closing Bal.</label>  
								<div class="col-md-3">
									<input id="cashBal" name="cashBal" type="text" class="form-control input-sm" value="@if(!empty($sum_all)){{$sum_all}}@else{{number_format(0.00,2)}}@endif" readonly>
							</div>

							<label class="col-md-2 control-label" for="Cashier">Cashier</label>  
								<div class="col-md-3">
									<input id="Cashier" name="Cashier" type="text" class="form-control input-sm" readonly value="{{Auth::user()->username}}">
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
									<input id="discrepancy" name="discrepancy" type="text" class="form-control input-sm" value="0.00" readonly>
							</div>
						</div>
					</div>

					<div class="panel-body">
						<div class="form-group">
							<label class="col-md-2 control-label" for="remarks">Reason</label> 
								<div class="col-md-8"> 
									<textarea class="form-control input-sm text-uppercase" name="remarks" rows="2" cols="55" maxlength="400" id="remarks" ></textarea>
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