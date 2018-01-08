<?php 
	include_once('../../../../header2.php'); 
?>
<body>

	<div class='row'>
		<div>
			<button id='confirmBut' class='btn btn-default btn-xs' style=''><i></i> Confirm</button>
			<button id='postBut' class='btn btn-default btn-xs' style=''><i></i> Post</button>
		</div>
		<form id="searchForm" class="formclass" style='width:99%'>
			<fieldset>
				<div class="ScolClass">
						<div name='Scol'>Search By : </div>
				</div>
				<div class="StextClass">
					<input name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase">
				</div>
			 </fieldset> 
		</form>
		<button id='sbut1' class='btn btn-default btn-xs' style=''><i></i> Search</button>
    	<div class='col-md-12' style="padding:0 0 15px 0">
            <table id="jqGrid" class="table table-striped"></table>
            <div id="jqGridPager"></div>
        </div>
    </div>

    
    
 	<!--------------------------------Supplier Form------------------>
	
	<div id="dialogForm" title="Add Form" >
		<form class='form-horizontal' style='width:99%' id='formdata'>
			
			<div class="prevnext btn-group pull-right">
				<a class='btn btn-default' name='prev'><i class='fa fa-chevron-left'></i></a>
				<a class='btn btn-primary' name='next' style='color:white'> Next <i class='fa fa-chevron-right'></i></a>
			</div>

        	<div class="form-group">
		  		<label class="col-md-2 control-label" for="prdept">Purchase Dept Code</label>  
                <div class="col-md-3">
                    <input id="prdept" value='<?php echo $_SESSION['deptcode'] ?>' name="prdept" type="text" maxlength="40" class="form-control input-sm" data-validation="required" frozeOnEdit>
                </div>
                

                <label class="col-md-2 control-label" for="purreqno">Purchase Request No</label>  
	 			<div class="col-md-3">
		  			<div class='input-group'>
						<input id="purreqno" name="purreqno" type="text" class="form-control input-sm" >
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
		  			</div>
		  			<span class="help-block"></span>
	  			</div>
			</div>
                
            <div class="form-group">
		  		<label class="col-md-2 control-label" for="reqdept">Request Department</label>  
	  			<div class="col-md-3">
		  			<div class='input-group'>
						<input id="reqdept" name="reqdept" type="text" class="form-control input-sm" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
		  			</div>
		  			<span class="help-block"></span>
	  			</div>
		  
		 		<label class="col-md-2 control-label" for="deldept">Delivery Department</label>  
	  			<div class="col-md-3">
		  			<div class='input-group'>
						<input id="deldept" name="deldept" type="text" class="form-control input-sm" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
		  			</div>
		  			<span class="help-block"></span>
	  			</div>
			</div>

			<div class="form-group">
		  		<label class="col-md-2 control-label" for="suppcode">Supplier</label>  
	  			<div class="col-md-3">
		  			<div class='input-group'>
						<input id="suppcode" name="suppcode" type="text" class="form-control input-sm" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
		  			</div>
		  			<span class="help-block"></span>
	  			</div>
		  
		 		<label class="col-md-2 control-label" for="termdays">Term Days</label>  
	  			<div class="col-md-3">
	  				<input id="termdaystermdays" value='30' name="termdays" type="text" maxlength="40" class="form-control input-sm" data-validation="required">
	  			</div>
			</div>

			<div class="form-group">
		  		<label class="col-md-2 control-label" for="purdate">Delivery Date</label>  
				<div class="col-md-3">
					<input id="deldate" name="deldate" type="date" class="form-control input-sm" data-validation="required">
				</div>

				<label class="col-md-2 control-label" for="expecteddate">Expected Date</label>  
				<div class="col-md-3">
					<input id="expecteddate" name="expecteddate" type="date" class="form-control input-sm" data-validation="required">
				</div>
			</div>

            <div class="form-group">
		  		<label class="col-md-2 control-label" for="remarks">Remarks</label>  
		  			<div class="col-md-8">
		  				<input id="remarks" name="remarks" type="text" maxlength="100" class="form-control input-sm">
		 			</div>
			</div>            
		</form>

		<fieldset style="border:3px; border-top:1px solid black;">
	       <legend style="text-align:center; width:17% !important; border-bottom:0px !important;
	       font-size:16px !important; font-weight: bold;">DO Detail Items</legend>
	    </fieldset>

		<div class='row'>
	        <div id='gridSuppitems_c' class='col-md-12' style="padding:0 0 15px 0">
	            <table id="gridSuppitems" class="table table-striped"></table>
	            <div id="jqGridPager2"></div>
	        </div>
	    </div>
	</div>
    
    <!--------------------------------End supplier Form------------------>   

	<?php 
		include_once('../../../../footer.php');
	?>
	<script src="delord.js"></script>
	<script src="../../../../assets/js/utility.js"></script>
	<script src="../../../../assets/js/dialogHandler.js"></script>

</body>
</html>