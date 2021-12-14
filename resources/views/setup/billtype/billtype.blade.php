@extends('layouts.main')

@section('title', 'Bill Type Setup')

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

input.uppercase {
	text-transform: uppercase;
}

@endsection

@section('body') 

	<!-------------------------------- Search + table ---------------------->
	<div class='row'>
		<form id="searchForm" class="formclass" style='width:99%; position:relative' onkeydown="return event.key != 'Enter';">
			<fieldset>
				<input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">

				<div class='col-md-12' style="padding:0 0 15px 0;">
					<div class="form-group"> 
						<div class="col-md-2">
							<label class="control-label" for="Scol">Search By : </label>  
					  		<select id='Scol' name='Scol' class="form-control input-sm" tabindex="1"></select>
		              	</div>

					  	<div class="col-md-5">
					  		<label class="control-label"></label>  
							<input  name="Stext" type="search" seltext='true' placeholder="Search here ..." class="form-control text-uppercase" tabindex="2">
						</div>
		            </div>
				</div>
			</fieldset> 
		</form>
    	
    	<div class="panel panel-default">
			<div class="panel-heading">Bill Type Header</div>
		    <div class="panel-body">
		    	<div class='col-md-12' style="padding:0 0 15px 0">
            		<table id="jqGrid" class="table table-striped"></table>
					<div id="jqGridPager"></div>
        		</div>
		    </div>
		</div>

		<div class='click_row'>
			<label class="control-label">Bill Type</label>
			<span id="showbilltype" style="display: block;">&nbsp</span>
		</div>
		<div class='click_row'>
			<label class="control-label">Description</label>
			<span id="showbilldesc" style="display: block;">&nbsp</span>
		</div>

		<div class="panel panel-default" id="jqGridsvc_c">
			<div class="panel-heading clearfix collapsed" data-toggle="collapse" data-target="#jqGrid3_panel1">
				<i class="fa fa-angle-double-up" style="font-size:24px"></i><i class="fa fa-angle-double-down" style="font-size:24px"></i >Service 
			</div>
			<div id="jqGrid3_panel1" class="panel-collapse collapse">
				<div class="panel-body">
					<div class='col-md-12' style="padding:0 0 15px 0">
						<table id="jqGridsvc" class="table table-striped"></table>
						<div id="jqGridPager2"></div>
					</div>
				</div>
			</div>	
		</div>

		<div class='click_row' id="click_row2">
			<label class="control-label">Bill Type</label>
			<span id="showbilltype2" style="display: block;">&nbsp</span>
		</div>
		<div class='click_row' id="click_row2">
			<label class="control-label">Description</label>
			<span id="showbilldesc2" style="display: block;">&nbsp</span>
		</div>
		<div class='click_row' id="click_row2">
			<label class="control-label">Charge Group</label>
			<span id="showchggroup2" style="display: block;">&nbsp</span>
		</div>
		<div class='click_row' id="click_row2">
			<label class="control-label">Description</label>
			<span id="showgroupdesc2" style="display: block;">&nbsp</span>
		</div>

		<div class="panel panel-default" id="jqGriditem_c">
			<div class="panel-heading clearfix collapsed" data-toggle="collapse" href="#jqGrid3_panel2">
				<i class="fa fa-angle-double-up" style="font-size:24px"></i><i class="fa fa-angle-double-down" style="font-size:24px"></i> Item 
			</div>
			<div id="jqGrid3_panel2" class="panel-collapse collapse">
				<div class="panel-body">
					<div class='col-md-12' style="padding:0 0 15px 0">
						<table id="jqGriditem" class="table table-striped"></table>
						<div id="jqGridPager3"></div>
					</div>
				</div>
			</div>	
		</div>

		<div class='click_row' id="click_row3">
			<label class="control-label">Bill Type</label>
			<span id="showbilltype3" style="display: block;">&nbsp</span>
		</div>
		<div class='click_row' id="click_row3">
			<label class="control-label">Description</label>
			<span id="showbilldesc3" style="display: block;">&nbsp</span>
		</div>
		<div class='click_row' id="click_row3">
			<label class="control-label">Charge Group</label>
			<span id="showchggroup3" style="display: block;">&nbsp</span>
		</div>
		<div class='click_row' id="click_row3">
			<label class="control-label">Description</label>
			<span id="showgroupdesc3" style="display: block;">&nbsp</span>
		</div>

		<div class="panel panel-default" id="jqGridtype_c">
			<div class="panel-heading clearfix collapsed" data-toggle="collapse" href="#jqGrid3_panel3">
				<i class="fa fa-angle-double-up" style="font-size:24px"></i><i class="fa fa-angle-double-down" style="font-size:24px"></i> Type 
			</div>
			<div id="jqGrid3_panel3" class="panel-collapse collapse">
				<div class="panel-body">
					<div class='col-md-12' style="padding:0 0 15px 0">
						<table id="jqGridtype" class="table table-striped"></table>
						<div id="jqGridPager4"></div>
					</div>
				</div>
			</div>	
		</div>
    </div>
	<!-------------------------------- End Search + table ------------------>
		
	<!----------------------------------Bill Type Master Form -------------------->
	<div id="dialogForm" title="Add Form" >
		<form class='form-horizontal' style='width:99%' id='formdata'>
			{{ csrf_field() }}
			<input type="hidden" name="idno">

			<div class="prevnext btn-group pull-right"></div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="billtype">Bill Type</label>  
				<div class="col-md-3">
					<input id="billtype" name="billtype" type="text" maxlength="5" class="form-control input-sm uppercase" data-validation="required" frozeOnEdit>
				</div>

				<label class="col-md-2 control-label" for="a.description">Description</label>  
				<div class="col-md-3">
					<input id="description" name="description" type="text" maxlength="100" class="form-control input-sm uppercase" data-validation="required">
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="price">Price</label>  
				<div class="col-md-3">
					<table>
						<tr>
							<td><label class="radio-inline"><input type="radio" name="price" value='PRICE1' data-validation="required">Price 1</label></td>
							<td><label class="radio-inline"><input type="radio" name="price" value='PRICE2' data-validation="">Price 2</label></td>
							<td><label class="radio-inline"><input type="radio" name="price" value='PRICE3' data-validation="">Price 3</label></td>
							<td><label class="radio-inline"><input type="radio" name="price" value='COST PRICE' data-validation="">Cost Price</label></td>
						</tr>
					</table> 
				</div>

				<label class="col-md-2 control-label" for="service">All Service</label>  
				<div class="col-md-3">
					<label class="radio-inline"><input type="radio" name="service" value='1' data-validation="required">Yes</label>
					<label class="radio-inline"><input type="radio" name="service" value='0' data-validation="">No</label>
				</div>
			</div> 

			<div class="form-group">
				<label class="col-md-2 control-label" for="opprice">OP Price</label>  
				<div class="col-md-3">
					<label class="radio-inline"><input type="radio" name="opprice" value='1' data-validation="required">Yes</label>
					<label class="radio-inline"><input type="radio" name="opprice" value='0' data-validation="">No</label>
				</div>

				<label class="col-md-2 control-label" for="percent_">Percentage</label>  
				<div class="col-md-3">
					<div class='input-group'>
						<input id="percent_" name="percent_" type="text" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0"
						data-validation-optional-if-answered="amount" data-validation="number">
						<span class="input-group-addon">%</span>
					</div>
				</div>
			</div> 

			<div class="form-group">
				<label class="col-md-2 control-label" for="amount">Amount</label>  
				<div class="col-md-3">
					<input id="amount" name="amount" type="text" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00" 
					data-validation-optional-if-answered="percent_" data-validation="number">
				</div>

				<label class="col-md-2 control-label" for="recstatus">Record Status</label>  
				<div class="col-md-3">
					<label class="radio-inline"><input type="radio" name="recstatus" value='ACTIVE' checked>Active</label>
					<label class="radio-inline"><input type="radio" name="recstatus" value='DEACTIVE' >Deactive</label>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="adduser">Created By</label>  
				<div class="col-md-3">
					<input id="adduser" name="adduser" type="text" class="form-control input-sm" frozeOnEdit hideOne>
				</div>

				<label class="col-md-2 control-label" for="upduser">Last Entered</label>  
				<div class="col-md-3">
					<input id="upduser" name="upduser" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
				</div>
			</div>  

			<div class="form-group">
				<label class="col-md-2 control-label" for="adddate">Created Date</label>  
				<div class="col-md-3">
					<input id="adddate" name="adddate" type="text" class="form-control input-sm" frozeOnEdit hideOne>
				</div>

				<label class="col-md-2 control-label" for="upddate">Last Entered Date</label>  
				<div class="col-md-3">
					<input id="upddate" name="upddate" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
				</div>
			</div>  

			<div class="form-group">
				<label class="col-md-2 control-label" for="computerid">Computer Id</label>  
				<div class="col-md-3">
					<input id="computerid" name="computerid" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
				</div>

				<label class="col-md-2 control-label" for="lastcomputerid">Last Computer Id</label>  
				<div class="col-md-3">
					<input id="lastcomputerid" name="lastcomputerid" type="text" maxlength="30" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
				</div>
			</div>    

			<div class="form-group">
				<label class="col-md-2 control-label" for="ipaddress">IP Address</label>  
				<div class="col-md-3">
					<input id="ipaddress" name="ipaddress" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
				</div>

				<label class="col-md-2 control-label" for="lastipaddress">Last IP Address</label>  
				<div class="col-md-3">
					<input id="lastipaddress" name="lastipaddress" type="text" maxlength="30" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
				</div>
			</div>   

		</form>
	</div>
	<!--------------------------------END Bill Type Master Form ------------------>

	<!----------------------------------Bill Type Service Form -------------------->
	<div id="Dsvc" title="Bill Type Service" >
		<form class='form-horizontal' style='width:99%' id='Fsvc'>
			{{ csrf_field() }}
			<input type="hidden" name="svc_idno">

			<div class="prevnext btn-group pull-right"></div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="svc_billtype">Bill Type</label>  
				<div class="col-md-3">
					<input id="svc_billtype" name="svc_billtype" type="text" class="form-control input-sm uppercase" rdonly>
				</div>
				
				<label class="col-md-2 control-label" for="m_description">Description</label>  
				<div class="col-md-3">
					<input id="m_description" name="m_description" type="text" class="form-control input-sm uppercase" rdonly>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="svc_chggroup">Chg. Group</label>  
				<div class="col-md-3">
					<div class='input-group'>
						<input id="svc_chggroup" name="svc_chggroup" type="text" class="form-control input-sm uppercase" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					</div>
					<span class="help-block"></span>
				</div>

				<label class="col-md-2 control-label" for="svc_price">Price</label>  
				<div class="col-md-3">
					<table>
						<tr>
							<td><label class="radio-inline"><input type="radio" name="svc_price" value='PRICE1' data-validation="required">Price 1</label></td>
							<td><label class="radio-inline"><input type="radio" name="svc_price" value='PRICE2' data-validation="">Price 2</label></td>
							<td><label class="radio-inline"><input type="radio" name="svc_price" value='PRICE3' data-validation="">Price 3</label></td>
							<td><label class="radio-inline"><input type="radio" name="svc_price" value='COST PRICE' data-validation="">Cost Price</label></td>
						</tr>
					</table> 
				</div>
			</div>

            <div class="form-group">
    			<label class="col-md-2 control-label" for="svc_allitem">All Item</label>  
				<div class="col-md-3">
					<label class="radio-inline"><input type="radio" name="svc_allitem" value='1' data-validation="required">Yes</label>
					<label class="radio-inline"><input type="radio" name="svc_allitem" value='0' data-validation="">No</label>
				</div>

				<label class="col-md-2 control-label" for="svc_alltype">All Type</label>  
				<div class="col-md-3">
					<label class="radio-inline"><input type="radio" name="svc_alltype" value='1' data-validation="required">Yes</label>
					<label class="radio-inline"><input type="radio" name="svc_alltype" value='0' data-validation="">No</label>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="svc_amount">Amount</label>  
				<div class="col-md-3">
					<input id="svc_amount" name="svc_amount" type="text" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00" data-validation-optional-if-answered="svc_percent_" data-validation="number">
				</div>
				  
				<label class="col-md-2 control-label" for="svc_discchgcode">Disc Chg Code</label>  
				<div class="col-md-3">
					<div class='input-group'>
						<input id="svc_discchgcode" name="svc_discchgcode" type="text" class="form-control input-sm uppercase">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					</div>
					<span class="help-block"></span>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="svc_percent_">Percentage</label>  
				<div class="col-md-3">
					<div class='input-group'>
						<input id="svc_percent_" name="svc_percent_" type="text" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0" data-validation-optional-if-answered="svc_amount" data-validation="number">
						<!--data-validation="number" data-validation-allowing="float"-->
						<span class="input-group-addon">%</span>
					</div>
				</div>

				<label class="col-md-2 control-label" for="svc_recstatus">Record Status</label>  
				<div class="col-md-3">
					<input id="svc_recstatus" name="svc_recstatus" type="text" class="form-control input-sm" frozeOnEdit hideOne>
				</div>		
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="svc_adduser">Created By</label>  
				<div class="col-md-3">
					<input id="svc_adduser" name="svc_adduser" type="text" class="form-control input-sm" frozeOnEdit hideOne>
				</div>

				<label class="col-md-2 control-label" for="svc_upduser">Last Entered</label>  
				<div class="col-md-3">
					<input id="svc_upduser" name="svc_upduser" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
				</div>
			</div> 

			<div class="form-group">
				<label class="col-md-2 control-label" for="svc_adddate">Created Date</label>  
				<div class="col-md-3">
					<input id="svc_adddate" name="svc_adddate" type="text" class="form-control input-sm" frozeOnEdit hideOne>
				</div>

				<label class="col-md-2 control-label" for="svc_upddate">Last Entered Date</label>  
				<div class="col-md-3">
					<input id="svc_upddate" name="svc_upddate" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
				</div>
			</div>  

			<div class="form-group">
				<label class="col-md-2 control-label" for="svc_computerid">Computer Id</label>  
				<div class="col-md-3">
					<input id="svc_computerid" name="svc_computerid" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
				</div>

				<label class="col-md-2 control-label" for="svc_lastcomputerid">Last Computer Id</label>  
				<div class="col-md-3">
					<input id="svc_lastcomputerid" name="svc_lastcomputerid" type="text" maxlength="30" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
				</div>
			</div>    

			<div class="form-group">
				<label class="col-md-2 control-label" for="svc_ipaddress">IP Address</label>  
				<div class="col-md-3">
					<input id="svc_ipaddress" name="svc_ipaddress" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
				</div>

				<label class="col-md-2 control-label" for="svc_lastipaddress">Last IP Address</label>  
				<div class="col-md-3">
					<input id="svc_lastipaddress" name="svc_lastipaddress" type="text" maxlength="30" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
				</div>
			</div>

		</form>
	</div>
	<!--------------------------------END Bill Type Service Form ------------------>

	<!----------------------------------Bill Type Item Form -------------------->
	<div id="Ditem" title="Bill Type Item" >
		<form class='form-horizontal' style='width:99%' id='Fitem'>
			{{ csrf_field() }}
			<input type="hidden" name="i_idno">

			<input id="billtype" name="i_billtype" type="hidden" class="form-control input-sm">

			<div class="form-group">
				<label class="col-md-2 control-label" for="i_chggroup">Chg Group</label>   
				<div class="col-md-3">
					<input id="i_chggroup" name="i_chggroup" type="text" class="form-control input-sm uppercase" rdonly>
				</div>
				
				<label class="col-md-2 control-label" for="c_description">Description</label>  
				<div class="col-md-3">
					<input id="c_description" name="c_description" type="text" class="form-control input-sm uppercase" rdonly>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="i_chgcode">Chg. Code</label>  
				<div class="col-md-3">
					<div class='input-group'>
						<input id="i_chgcode" name="i_chgcode" type="text" class="form-control input-sm uppercase" data-validation="required" frozeOnEdit>
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					</div>
					<span class="help-block"></span>
				</div>

				<label class="col-md-2 control-label" for="i_price">Price</label>  
				<div class="col-md-3">
					<table>
						<tr>
							<td><label class="radio-inline"><input type="radio" name="i_price" value='PRICE1' data-validation="required">Price 1</label></td>
							<td><label class="radio-inline"><input type="radio" name="i_price" value='PRICE2' data-validation="">Price 2</label></td>
							<td><label class="radio-inline"><input type="radio" name="i_price" value='PRICE3' data-validation="">Price 3</label></td>
							<td><label class="radio-inline"><input type="radio" name="i_price" value='COST PRICE' data-validation="">Cost Price</label></td>
						</tr>
					</table> 
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="i_amount">Amount</label>  
				<div class="col-md-3">
					<input id="i_amount" name="i_amount" type="text" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00"
					data-validation-optional-if-answered="i_percent_" data-validation="number">
				</div>
					
				<label class="col-md-2 control-label" for="i_percent_">Percentage</label>  
				<div class="col-md-3">
					<div class='input-group'>
						<input id="i_percent_" name="i_percent_" type="text" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0"
						data-validation-optional-if-answered="i_amount" data-validation="number">
						<span class="input-group-addon">%</span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="i_recstatus">Record Status</label>  
				<div class="col-md-3">
					<input id="i_recstatus" name="i_recstatus" type="text" class="form-control input-sm" frozeOnEdit hideOne>
				</div>
			</div> 

			<div class="form-group">
				<label class="col-md-2 control-label" for="i_adduser">Created By</label>  
				<div class="col-md-3">
					<input id="i_adduser" name="i_adduser" type="text" class="form-control input-sm" frozeOnEdit hideOne>
				</div>

				<label class="col-md-2 control-label" for="i_upduser">Last Entered</label>  
				<div class="col-md-3">
					<input id="i_upduser" name="i_upduser" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
				</div>
			</div> 

			<div class="form-group">
				<label class="col-md-2 control-label" for="i_adddate">Created Date</label>  
				<div class="col-md-3">
					<input id="i_adddate" name="i_adddate" type="text" class="form-control input-sm" frozeOnEdit hideOne>
				</div>

				<label class="col-md-2 control-label" for="i_upddate">Last Entered Date</label>  
				<div class="col-md-3">
					<input id="i_upddate" name="i_upddate" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
				</div>
			</div>  

			<div class="form-group">
				<label class="col-md-2 control-label" for="i_computerid">Computer Id</label>  
				<div class="col-md-3">
					<input id="i_computerid" name="i_computerid" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
				</div>

				<label class="col-md-2 control-label" for="i_lastcomputerid">Last Computer Id</label>  
				<div class="col-md-3">
					<input id="i_lastcomputerid" name="i_lastcomputerid" type="text" maxlength="30" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
				</div>
			</div>    

			<div class="form-group">
				<label class="col-md-2 control-label" for="i_ipaddress">IP Address</label>  
				<div class="col-md-3">
					<input id="i_ipaddress" name="i_ipaddress" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
				</div>

				<label class="col-md-2 control-label" for="i_lastipaddress">Last IP Address</label>  
				<div class="col-md-3">
					<input id="i_lastipaddress" name="i_lastipaddress" type="text" maxlength="30" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
				</div>
			</div> 

		</form>
	</div>
	<!--------------------------------END Bill Type Item Form ------------------>

	<!----------------------------------Bill Charge Type Form -------------------->
	<div id="Dtype" title="Bill Charge Type" >
		<form class='form-horizontal' style='width:99%' id='Ftype'>
			{{ csrf_field() }}
			<input type="hidden" name="t_idno">

			<input id="billtype" name="t_billtype" type="hidden" class="form-control input-sm">

			<div class="form-group">
				<label class="col-md-2 control-label" for="t_chggroup">Chg Group</label>   
				<div class="col-md-3">
					<input id="t_chggroup" name="t_chggroup" type="text" class="form-control input-sm uppercase" rdonly>
				</div>
				
				<label class="col-md-2 control-label" for="cg_description">Description</label>  
				<div class="col-md-3">
					<input id="cg_description" name="cg_description" type="text" class="form-control input-sm uppercase" rdonly>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="t_chgtype">Chg. Type</label>  
				<div class="col-md-3">
					<div class='input-group'>
						<input id="t_chgtype" name="t_chgtype" type="text" class="form-control input-sm uppercase" data-validation="required" frozeOnEdit>
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					</div>
					<span class="help-block"></span>
				</div>
				
				<label class="col-md-2 control-label" for="t_price">Price</label>  
				<div class="col-md-3">
					<table>
						<tr>
							<td><label class="radio-inline"><input type="radio" name="t_price" value='PRICE1' data-validation="required">Price 1</label></td>
							<td><label class="radio-inline"><input type="radio" name="t_price" value='PRICE2' data-validation="">Price 2</label></td>
							<td><label class="radio-inline"><input type="radio" name="t_price" value='PRICE3' data-validation="">Price 3</label></td>
							<td><label class="radio-inline"><input type="radio" name="t_price" value='COST PRICE' data-validation="">Cost Price</label></td>
						</tr>
					</table> 
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="t_amount">Amount</label>  
				<div class="col-md-3">
					<input id="t_amount" name="t_amount" type="text" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00"
					data-validation-optional-if-answered="t_percent_" data-validation="number">
				</div>
				
				<label class="col-md-2 control-label" for="t_percent_">Percentage</label>  
				<div class="col-md-3">
					<div class='input-group'>
						<input id="t_percent_" name="t_percent_" type="text" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0"
						data-validation-optional-if-answered="t_amount" data-validation="number">
						<span class="input-group-addon">%</span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="t_recstatus">Record Status</label>  
				<div class="col-md-3">
					<input id="t_recstatus" name="t_recstatus" type="text" class="form-control input-sm" frozeOnEdit hideOne>
				</div>
			</div> 

			<div class="form-group">
				<label class="col-md-2 control-label" for="t_adduser">Created By</label>  
				<div class="col-md-3">
					<input id="t_adduser" name="t_adduser" type="text" class="form-control input-sm" frozeOnEdit hideOne>
				</div>

				<label class="col-md-2 control-label" for="t_upduser">Last Entered</label>  
				<div class="col-md-3">
					<input id="t_upduser" name="t_upduser" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
				</div>
			</div> 

			<div class="form-group">
				<label class="col-md-2 control-label" for="t_adddate">Created Date</label>  
				<div class="col-md-3">
					<input id="t_adddate" name="t_adddate" type="text" class="form-control input-sm" frozeOnEdit hideOne>
				</div>

				<label class="col-md-2 control-label" for="t_upddate">Last Entered Date</label>  
				<div class="col-md-3">
					<input id="t_upddate" name="t_upddate" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
				</div>
			</div>  

			<div class="form-group">
				<label class="col-md-2 control-label" for="t_computerid">Computer Id</label>  
				<div class="col-md-3">
					<input id="t_computerid" name="t_computerid" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
				</div>

				<label class="col-md-2 control-label" for="t_lastcomputerid">Last Computer Id</label>  
				<div class="col-md-3">
					<input id="t_lastcomputerid" name="t_lastcomputerid" type="text" maxlength="30" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
				</div>
			</div>    

			<div class="form-group">
				<label class="col-md-2 control-label" for="t_ipaddress">IP Address</label>  
				<div class="col-md-3">
					<input id="t_ipaddress" name="t_ipaddress" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
				</div>

				<label class="col-md-2 control-label" for="t_lastipaddress">Last IP Address</label>  
				<div class="col-md-3">
					<input id="t_lastipaddress" name="t_lastipaddress" type="text" maxlength="30" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
				</div>
			</div> 

		</form>
	</div>
	<!--------------------------------END Bill Charge Type Form ------------------>

@endsection


@section('scripts')
	<script type="text/javascript">
		$(document).ready(function () {
			if(!$("table#jqGrid").is("[tabindex]")){
				$("#jqGrid").bind("jqGridGridComplete", function () {
					$("table#jqGrid").attr('tabindex',3);
					$("td#input_jqGridPager input.ui-pg-input.form-control").attr('tabindex',4);
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
	
	<script src="js/setup/billtype/billtype.js"></script>
	
@endsection