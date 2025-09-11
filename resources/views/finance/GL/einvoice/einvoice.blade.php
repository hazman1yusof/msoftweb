@extends('layouts.main')

@section('title', 'E-invoice')

@section('style')

input.uppercase {
	text-transform: uppercase;
}
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
@endsection

@section('body')

<input id="viewonly" name="viewonly" type="hidden" value="{{Request::get('viewonly')}}">
@if(Request::get('viewonly') == 'viewonly')
<input id="viewonly_auditno" type="hidden" value="{{Request::get('auditno')}}">
<input id="viewonly_lineno_" type="hidden" value="{{Request::get('lineno_')}}">
@endif

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

						<div class="col-md-5" style="padding-top: 20px;text-align: center;color: red">
					  		<p id="p_error"></p>
					  </div>
		      </div>
				</div>

				<div class="col-md-2" id="unitsearch">
            <label class="control-label" for="trandept">Unit</label> 
            <select id='unit' class="form-control input-sm">
                <option value="All">ALL</option>
                <option value="IMP" selected>IMP</option>
                <option value="W'HOUSE">W'HOUSE</option>
                <option value="KHEALTH">KHEALTH</option>
            </select>
        </div>
			</fieldset> 
		</form>
        <div class="panel panel-default">
		    <div class="panel-heading" style="position:relative;">Reprint Bill
					<a class='pull-right pointer text-primary' style="padding-left: 30px" id='reprint__summbill'>
				    Invoice
					</a>
					<a class='pull-right btn btn-sm btn-primary'  id='btn_open_dialog_login' style="
						position: absolute;
		    		top: 3px;
		    		right: 190px;">
		    	Submit</a>
		    </div>
		    <div class="panel-body">
		    	<div class='col-md-12' style="padding:0 0 15px 0">
            		<table id="jqGrid" class="table table-striped"></table>
            		<div id="jqGridPager"></div>
        		</div>
		    </div>
		</div>

		<div class="panel panel-default" style="position: relative;" id="acctent_c">
			<div class="panel-heading clearfix collapsed" data-toggle="collapse" data-target="#acctent_panel" id="panel_acctent">
				<b>Account Entries</b>
				<i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
				<i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i>
			</div>
			<div id="acctent_panel" class="panel-collapse collapse">
				<div class="panel-body" style="min-height: 50vh;">
					<b>Account Entries - <span id="acctent_title_span">Sales</span></b>
					<div class='pull-right pointer text-primary' style="padding-right:20px">
						<b>
							<a style="padding-right: 10px" id='a_sales_acctent' data-type='sales'> Sales </a>
						</b> | 
						<b>
							<a style="padding-left: 10px" id='a_cost_acctent' data-type='cost'> Cost </a>
						</b>
					</div>
					<div class='col-md-12' style="padding:15px 0 15px 0">
						<table id="gridacctent" class="table table-striped"></table>
						<div id="jqGridPageracctent"></div>
					</div>
				</div>
			</div>	
		</div> 

		<div id="dialog_user_login" title="E-invoice Authorisation" >
			<div class="panel-body" style="position: relative;">
				<div id="fail_msg" style="color: darkred;"></div>
				<div class="col-md-12">
				  	<label class="control-label" for="username_login">Username</label>  
						<input id="username_login" name="username_login" type="text" class="form-control input-sm text-uppercase" autocomplete="off">
				</div>
				<div class="col-md-12">
				  	<label class="control-label" for="password_login">Password</label>  
						<input id="password_login" name="password_login" type="password" class="form-control input-sm text-uppercase" autocomplete="off">
				</div>
				<div class="col-md-3">
					<button class="btn btn-primary pull-right" id="login_submit" style="margin-top: 10px;">Submit</button>
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
	<script src="js/finance/GL/einvoice/einvoice.js"></script>
@endsection