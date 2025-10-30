@extends('layouts.main')

@section('title', 'Supplier')

@section('style')

.panel-heading.collapsed .fa-angle-double-up,
.panel-heading .fa-angle-double-down {
  display: none;
}

.panel-heading.collapsed .fa-angle-double-down,
.panel-heading .fa-angle-double-up {
  display: inline-block;
}

i.fa {
  cursor: pointer;
  float: right;
 <!--  margin-right: 5px; -->
}

.collapsed ~ .panel-body {
  padding: 0;
}

.clearfix {
	overflow: auto;
}

.input-group-addon {
	background-color: #2c699e;
}

@endsection

@section('body')


	 
	<!-------------------------------- Search + table ---------------------->
	<div class='row'>
		<form id="searchForm" class="formclass" style='width:99%'  onkeydown="return event.key != 'Enter';">
			<fieldset>
				<div class="ScolClass">
						<div name='Scol'>Search By : </div>
				</div>
				<div class="StextClass">
					<input name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase"  tabindex="1">
					<div style="position:absolute;bottom:0;right:0;">
						<!--<label class="checkbox-inline"><input type="checkbox" value=""><b> Repeat add</b></label>-->
					</div>
				</div>
			 </fieldset> 
		</form>

		<div class="panel panel-default">
            <div class="panel-heading"> Supplier
			<a class='pull-right pointer text-primary' style="padding-left: 30px;color: #518351;" id='pdfgen_excel' href="" target="_blank" >
				<span class='fa fa-file-excel-o fa-lg'></span> Download Excel 
			</a>
			<a class='pull-right pointer text-primary' style="padding-left: 30px;color: #a35252;" id='pdfgen1' href="" target="_blank">
				<span class='fa fa-file-pdf-o fa-lg'></span> Print PDF
			</a>
				<a class='pull-right pointer text-primary' style="padding-left: 30px" id='attcahment_go'>
					<span class='fa fa-paperclip'></span> Attachment 
				</a>            </div>
            <div class="panel-body">
                <div class='col-md-12' style="padding:0 0 15px 0">
                    <table id="jqGrid" class="table table-striped"></table>
                    <div id="jqGridPager"></div>
                </div>
            </div>
        </div>

		<div class="panel panel-default" style="position: relative;" id="gridSuppitems_c">
			<div class="panel-heading clearfix collapsed" data-toggle="collapse" data-target="#jqGrid3_panel1">
				<b>SUPPLIER : </b><span id="suppItemCode_show"></span> - <span id="suppItemname_show"></span>
					<i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
					<i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i>
						<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 50px; top: 10px;">
							<h5>Supplier Item</h5>
						</div>
			</div>

			<div id="jqGrid3_panel1" class="panel-collapse collapse">
				<div class="panel-body">
					<div class='col-md-12' style="padding:0 0 15px 0">
						<table id="gridSuppitems" class="table table-striped"></table>
							<div id="jqGridPager2"></div>
					</div>
				</div>
			</div>	
		</div>

		<div class="panel panel-default" style="position: relative;" id="gridSuppBonus_c">
			<div class="panel-heading clearfix collapsed" data-toggle="collapse" href="#jqGrid3_panel2">
			<b>SUPPLIER : </b><span id="suppBonCode_show"></span> - <span id="suppBonName_show"></span>
					<i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
					<i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i>
						<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 50px; top: 10px;">
							<h5>Supplier Bonus</h5>
						</div>
			</div>

			<div id="jqGrid3_panel2" class="panel-collapse collapse">
				<div class="panel-body">
					<div class='col-md-12' style="padding:0 0 15px 0">
						<table id="gridSuppBonus" class="table table-striped"></table>
							<div id="jqGridPager3"></div>
					</div>
				</div>
			</div>	
		</div>

		<!-- attachment -->
		<div class="panel panel-default" style="position: relative;" id="gridAttch_c">
			<div class="panel-heading clearfix collapsed" data-toggle="collapse" data-target="#gridAttch_panel" id="panel_gridpv">
			<b>SUPPLIER : </b><span id="attachCode_show"></span> - <span id="attachName_show"></span>
				<i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
				<i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i>
				<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 50px; top: 10px;">
					<h5>Attachment</h5>
				</div>
			</div>
			<div id="gridAttch_panel" class="panel-collapse collapse">
				<div class="panel-body" style="height: calc(100vh - 70px); padding: 0px;">
					<div class='col-md-12' style="padding:0 0 15px 0" >
						<iframe id='attach_iframe' src='' style="height: calc(100vh - 100px);width: 100%; border: none;"></iframe>
					</div>
				</div>
			</div>	
		</div>

    </div>

	<!-------------------------------- End Search + table ------------------>

	<div id="dialogForm" title="Add Form" >
		<form class='form-horizontal' style='width:99%' id='formdata'>
		
			{{ csrf_field() }}
			<input type="hidden" name="idno">
			<div class="form-group">
				<label class="col-md-2 control-label" for="SuppCode">Supplier Code</label>  
					<div class="col-md-2">
						<input id="SuppCode" name="SuppCode" type="text" maxlength="10" class="form-control input-sm text-uppercase" rdonly>
					</div>
			</div>
				
			<div class="form-group">
				<label class="col-md-2 control-label" for="Name">Name</label>  
					<div class="col-md-8">
						<input id="Name" name="Name" type="text" maxlength="333" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value" >
					</div>
			</div>
			
			<div class="form-group">
				<label class="col-md-2 control-label" for="Addr1">Address</label>  
					<div class="col-md-8">
						<input id="Addr1" name="Addr1" type="text" maxlength="333" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value" >
					</div>
			</div>
		
			<div class="form-group">
				<div class="col-md-offset-2 col-md-8">
					<input id="Addr2" name="Addr2" type="text" maxlength="333" class="form-control input-sm text-uppercase">
				</div>
			</div>
		
			<div class="form-group">
				<div class="col-md-offset-2 col-md-8">
					<input id="Addr3" name="Addr3" type="text" maxlength="333" class="form-control input-sm text-uppercase">
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-md-offset-2 col-md-8">
					<input id="Addr4" name="Addr4" type="text" maxlength="333" class="form-control input-sm text-uppercase">
				</div>
			</div>
				
			<div class="form-group">
				<label class="col-md-2 control-label" for="TelNo">Tel No</label>  
					<div class="col-md-3">
						<input id="TelNo" name="TelNo" type="text" maxlength="50" class="form-control input-sm">
					</div>
					
				<label class="col-md-2 control-label" for="Faxno">Fax No</label>  
					<div class="col-md-3">
						<input id="Faxno" name="Faxno" type="text" maxlength="30" class="form-control input-sm">
					</div>
			</div>
			
			<div class="form-group">
				<label class="col-md-2 control-label" for="ContPers">Contact Person</label>  
					<div class="col-md-3">
						<input id="ContPers" name="ContPers" type="text" maxlength="40" class="form-control input-sm text-uppercase">
					</div>
			
				<label class="col-md-2 control-label" for="SuppGroup">Supplier Group</label>  
					<div class="col-md-3">
						<div class='input-group'>
							<input id="SuppGroup" name="SuppGroup" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value" >
							<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
						</div>
						<span class="help-block"></span>
					</div>
			</div>
				
			<div class="form-group">
				<label class="col-md-2 control-label" for="CostCode">Cost Code</label>  
					<div class="col-md-3">
						<div class='input-group'>
							<input id="CostCode" name="CostCode" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value" frozeOnEdit>
							<!-- <a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a> -->
						</div>
						<span class="help-block"></span>
					</div>
			
				<label class="col-md-2 control-label" for="GlAccNo">GL Account No</label>  
					<div class="col-md-3">
						<div class='input-group'>
							<input id="GlAccNo" name="GlAccNo" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value" frozeOnEdit>
							<!-- <a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a> -->
						</div>
						<span class="help-block"></span>
					</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="Advccode">Advance Cost Code</label>  
					<div class="col-md-3">
						<div class='input-group'>
							<input id="Advccode" name="Advccode" type="text" class="form-control input-sm text-uppercase">
							<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
						</div>
						<span class="help-block"></span>
					</div>
			
				<label class="col-md-2 control-label" for="AdvGlaccno">Advance GL Account No</label>  
					<div class="col-md-3">
						<div class='input-group'>
							<input id="AdvGlaccno" name="AdvGlaccno" type="text" class="form-control input-sm text-uppercase">
							<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
						</div>
						<span class="help-block"></span>
					</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="adduser">Created By</label>  
					<div class="col-md-2">
						<input id="adduser" name="adduser" type="text" class="form-control input-sm" frozeOnEdit hideOne>
					</div>

				<label class="col-md-3 control-label" for="upduser">Last Entered</label>  
					<div class="col-md-2">
						<input id="upduser" name="upduser" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
					</div>
				</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="adddate">Created Date</label>  
					<div class="col-md-2">
						<input id="adddate" name="adddate" type="text" class="form-control input-sm" frozeOnEdit hideOne>
					</div>

				<label class="col-md-3 control-label" for="upddate">Last Entered Date</label>  
					<div class="col-md-2">
						<input id="upddate" name="upddate" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
					</div>
			</div>  

			<div class="form-group">
				<label class="col-md-2 control-label" for="computerid">Computer Id</label>  
					<div class="col-md-2">
						<input id="computerid" name="computerid" type="text" class="form-control input-sm" frozeOnEdit hideOne>
					</div>

				<label class="col-md-3 control-label" for="lastcomputerid">Last Computer Id</label>  
					<div class="col-md-2">
						<input id="lastcomputerid" name="lastcomputerid" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
					</div>
			</div> 

			<div class="form-group">
				<label class="col-md-2 control-label" for="AccNo">Company Bank A/C No</label>  
					<div class="col-md-3">
						<input id="AccNo" name="AccNo" type="text" class="form-control input-sm text-uppercase">
					</div>

				<label class="col-md-2 control-label" for="CompRegNo">Company Register No</label>  
					<div class="col-md-3">
						<input id="CompRegNo" name="CompRegNo" type="text" class="form-control input-sm text-uppercase">
					</div>
						
				<!-- <label class="col-md-2 control-label" for="SuppFlg">Supply Goods</label>  
				<div class="col-md-2">
					<label class="radio-inline"><input type="radio" name="SuppFlg" value='Yes' data-validation="">Yes</label>
					<label class="radio-inline"><input type="radio" name="SuppFlg" value='No' data-validation="">No</label>
				</div> -->
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="indvNewic">Individual I/C No.</label>  
					<div class="col-md-3">
						<input id="indvNewic" name="indvNewic" type="text" class="form-control input-sm text-uppercase">
					</div>

				<label class="col-md-2 control-label" for="indvOtherno">Individual Other No.</label>  
					<div class="col-md-3">
						<input id="indvOtherno" name="indvOtherno" type="text" class="form-control input-sm text-uppercase">
					</div>
						
				<!-- <label class="col-md-2 control-label" for="SuppFlg">Supply Goods</label>  
				<div class="col-md-2">
					<label class="radio-inline"><input type="radio" name="SuppFlg" value='Yes' data-validation="">Yes</label>
					<label class="radio-inline"><input type="radio" name="SuppFlg" value='No' data-validation="">No</label>
				</div> -->
			</div>
			
			<div class="form-group">
				<label class="col-md-2 control-label" for="recstatus">Record Status</label>  
					<div class="col-md-3">
						<label class="radio-inline"><input type="radio" name="recstatus" value='ACTIVE' checked>Active</label>
						<label class="radio-inline"><input type="radio" name="recstatus" value='DEACTIVE' >Deactive</label>
					</div>

				<label class="col-md-2 control-label" for="TermDays">Payment Terms</label>  
					<div class="col-md-3">
						<div class="input-group">
							<input id="TermDays" name="TermDays" type="text" value="30" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0" value="0">
							<span class="input-group-addon">days</span>
						</div>
					</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="TINNo">Tax ID No (TIN)</label>  
					<div class="col-md-3">
						<input id="TINNo" name="TINNo" type="text" maxlength="15" class="form-control input-sm text-uppercase">
					</div>
			</div>
			
			<!-- <fieldset style="border:3px; border-top:1px solid black;">
				<legend style="text-align:center; width:17% !important; border-bottom:0px !important;     font-size:16px !important; font-weight: bold;">Payment Terms</legend>
			</fieldset>
			
			<div class="form-group">
				<label class="col-md-2 control-label" for="TermDisp">Disposable</label>  
					<div class="col-md-2">
						<input id="TermDisp" name="TermDisp" type="text" value="0" maxlength="9" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0" value="0">
					</div>
				
				<label class="col-md-2 control-label" for="TermNonDisp">Non-Disposable</label>  
					<div class="col-md-2">
						<input id="TermNonDisp" name="TermNonDisp" type="text" value="0" maxlength="9" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0" value="0">
					</div>

				<label class="col-md-1 control-label" for="TermOthers">Other</label>    
					<div class="col-md-2">
						<input id="TermOthers" name="TermOthers" type="text" value="0" maxlength="9" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0" value="0">
					</div>
			</div>              	 -->

		</form>
	</div>

	  	<!--------------------------------End supplier Form------------------>
        
        
        <!--------------------------------Supplier Item Form ------------------>

        <div id="Dsuppitems" title="Supplier Item" >
        	<form class='form-horizontal' style='width:99%' id='Fsuppitems'>
			
				{{ csrf_field() }}
				<input type="hidden" name="si_idno">
            
            	<input id="lineno_" name="si_lineno_" type="hidden" class="form-control input-sm">
                <input id="suppcode" name="si_suppcode" type="hidden" class="form-control input-sm">
               
            	 <div class="form-group">
				  <label class="col-md-3 control-label" for="si_pricecode">Price Code</label>  
				  	<div class="col-md-3">
					  <div class='input-group'>
						<input id="si_pricecode" name="si_pricecode" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value" >
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
				  	</div>
                 </div>
            
            	<div class="form-group">
				  <label class="col-md-3 control-label" for="si_itemcode">Item Code</label>  
				  	<div class="col-md-3">
					  <div class='input-group'>
						<input id="si_itemcode" name="si_itemcode" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value" >
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
				  	</div>
                </div>	

                <div class="form-group">
				   	<label class="col-md-3 control-label" for="si_uomcode">UOM Code</label>  
				  	<div class="col-md-3">
					  <div class='input-group'>
						<input id="si_uomcode" name="si_uomcode" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value" >
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
				  	</div>
                </div>		
                 
                 <div class="form-group">
				  	<label class="col-md-3 control-label" for="si_purqty">Purchase Quantity</label>  
				  		<div class="col-md-2">
				  		<input id="si_purqty" name="si_purqty" type="text" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0">
				  		</div>

				  	<label class="col-md-2 control-label" for="si_unitprice">Unit Price</label>  
				  		<div class="col-md-2">
				  			<input id="si_unitprice" name="si_unitprice" type="text" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.0000">
				  		</div>
                </div>	
                
                <div class="form-group">
				  	<label class="col-md-3 control-label" for="si_perdiscount">Percentage of Discount</label>  
				  		<div class="col-md-2">
				  		<input id="si_perdiscount" name="si_perdiscount" type="text" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00">
				  		</div>

				  	<label class="col-md-2 control-label" for="si_amtdisc">Amount Discount</label>  
				  		<div class="col-md-2">
				  			<input id="si_amtdisc" name="si_amtdisc" type="text" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00">
				  		</div>
                </div>
                
                <div class="form-group">
				  	<label class="col-md-3 control-label" for="si_perslstax">Percentage of Sales Tax</label>  
				  		<div class="col-md-2">
				  		<input id="si_perslstax" name="si_perslstax" type="text" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00">
				  		</div>

				  	<label class="col-md-2 control-label" for="si_amtslstax">Amount Sales Tax</label>  
				  		<div class="col-md-2">
				  		<input id="si_amtslstax" name="si_amtslstax" type="text" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.0000">
				  		</div>
                </div>
                
                <div class="form-group">
				  	<label class="col-md-3 control-label" for="si_expirydate">Expiry Date</label>  
				  		<div class="col-md-2">
				  		<input id="si_expirydate" name="si_expirydate" type="Date" min="<?php echo date("Y-m-d"); ?>"   class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value" >
				  		</div>

				  	<label class="col-md-2 control-label" for="si_sitemcode">Item Code at Supplier's Site</label>  
				  		<div class="col-md-2">
				  		<input id="si_sitemcode" name="si_sitemcode" type="text" maxlength="12" class="form-control input-sm text-uppercase">
				  		</div>
                </div>

                <div class="form-group">
				  	<label class="col-md-3 control-label" for="si_adduser">Created By</label>  
						<div class="col-md-2">
						  	<input id="si_adduser" name="si_adduser" type="text" class="form-control input-sm" frozeOnEdit hideOne>
						</div>

					<label class="col-md-2 control-label" for="si_upduser">Last Entered</label>  
						<div class="col-md-2">
							<input id="si_upduser" name="si_upduser" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
						</div>
                </div>

                <div class="form-group">
				  	<label class="col-md-3 control-label" for="si_adddate">Created Date</label>  
						<div class="col-md-2">
						  	<input id="si_adddate" name="si_adddate" type="text" class="form-control input-sm" frozeOnEdit hideOne>
						</div>

					<label class="col-md-2 control-label" for="si_upddate">Last Entered Date</label>  
						<div class="col-md-2">
							<input id="si_upddate" name="si_upddate" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
						</div>
                </div>

                <div class="form-group">
					<label class="col-md-3 control-label" for="si_computerid">Computer Id</label>  
						<div class="col-md-2">
						  	<input id="si_computerid" name="si_computerid" type="text" class="form-control input-sm" frozeOnEdit hideOne>
						</div>

					<label class="col-md-2 control-label" for="si_lastcomputerid">Last Computer Id</label>  
						<div class="col-md-2">
							<input id="si_lastcomputerid" name="si_lastcomputerid" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
						  	</div>
				</div>   
			</form>
		</div>
		<!------------------------------end supplier item form---------------------------------->

		<div id="Dsuppbonus" title="Bonus Item" >
        	<form id="Fsuppbonus" class='form-horizontal' style='width:99%'>
			
				{{ csrf_field() }}
				<input type="hidden" name="sb_idno">
            
            <input name="sb_suppcode" type="hidden">
            <input name="sb_pricecode" type="hidden">
            <input name="sb_itemcode" type="hidden">
            <input name="sb_uomcode" type="hidden">
            <input name="sb_purqty" type="hidden">
            <input name="sb_lineno_" type="hidden">
            
            	<div class="form-group">
				  <label class="col-md-3 control-label" for="sb_bonpricecode">Bonus Price Code</label>  
				  	<div class="col-md-3">
					  <div class='input-group'>
						<input id="sb_bonpricecode" name="sb_bonpricecode" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value" >
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
				  	</div>
                 </div>
                 
                 <div class="form-group">
				  <label class="col-md-3 control-label" for="sb_bonitemcode">Bonus Item Code</label>  
				  	<div class="col-md-3">
					  <div class='input-group'>
						<input id="sb_bonitemcode" name="sb_bonitemcode" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value" >
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
				  	</div>
                </div>	
                
                <div class="form-group">
				  <label class="col-md-3 control-label" for="sb_bonuomcode">Bonus UOM Code</label>  
				  	<div class="col-md-3">
					  <div class='input-group'>
						<input id="sb_bonuomcode" name="sb_bonuomcode" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value" >
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
				  	</div>
                 </div>
                 
                 <div class="form-group">
				  	<label class="col-md-3 control-label" for="sb_bonqty">Bonus Quantity</label>  
				  		<div class="col-md-2">
				  		<input id="sb_bonqty" name="sb_bonqty" type="text" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0">
				  		</div>

				  	<label class="col-md-2 control-label" for="sb_bonsitemcode">Bonus Item Code at Supplier Site</label>  
				  		<div class="col-md-2">
				  		<input id="sb_bonsitemcode" name="sb_bonsitemcode" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value" >
				  		</div>
                </div>	
                
                <div class="form-group">
				  	<label class="col-md-3 control-label" for="sb_adduser">Created By</label>  
						<div class="col-md-2">
						  	<input id="sb_adduser" name="sb_adduser" type="text" class="form-control input-sm" frozeOnEdit hideOne>
						</div>

					<label class="col-md-2 control-label" for="sb_upduser">Last Entered</label>  
						<div class="col-md-2">
							<input id="sb_upduser" name="sb_upduser" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
						</div>
                </div>

                <div class="form-group">
				  	<label class="col-md-3 control-label" for="sb_adddate">Created Date</label>  
						<div class="col-md-2">
						  	<input id="sb_adddate" name="sb_adddate" type="text" class="form-control input-sm" frozeOnEdit hideOne>
						</div>

					<label class="col-md-2 control-label" for="sb_upddate">Last Entered Date</label>  
						<div class="col-md-2">
							<input id="sb_upddate" name="sb_upddate" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
						</div>
                </div>

                <div class="form-group">
					<label class="col-md-3 control-label" for="sb_computerid">Computer Id</label>  
						<div class="col-md-2">
						  	<input id="sb_computerid" name="sb_computerid" type="text" class="form-control input-sm" frozeOnEdit hideOne>
						</div>

					<label class="col-md-2 control-label" for="sb_lastcomputerid">Last Computer Id</label>  
						<div class="col-md-2">
							<input id="sb_lastcomputerid" name="sb_lastcomputerid" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
						  	</div>
				</div>   
            </form>
        </div>
	@endsection

@section('scripts')
	<script type="text/javascript">
		$(document).ready(function () {
			if(!$("table#jqGrid").is("[tabindex]")){
				$("#jqGrid").bind("jqGridGridComplete", function () {
					$("table#jqGrid").attr('tabindex', 2);
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

	<script src="js/material/supplier/supplier.js?v=1.3"></script>

@endsection