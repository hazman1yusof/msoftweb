<?php 
	include_once('../../../../header.php'); 
?>
<body style="display:none">
	 
	<!-------------------------------- Search + table ---------------------->
	<div class='row'>
		<!--<form id="searchForm" class="formclass" style='width:99%'>
			<fieldset>
				<div class="ScolClass">
						<div name='Scol'>Search By : </div>
				</div>
				<div class="StextClass">
					<input name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase">
				</div>
			 </fieldset> 
		</form>  min="<?php //echo date("Y-m-d"); ?>"-->
    	<div class='col-md-5'>
            <table id="jqGrid" class="table table-striped"></table>
            <div id="jqGridPager"></div>
            	<div>
					<!--<button type="button" id='saveyear' class='btn btn-info'>Save</button>
					<button id='cancelyear' class='btn btn-info'>Cancel</button>-->
				</div>
        </div>
<!--<div class="panel panel-default">
		    	<div class="panel-heading">Delivery Order DataEntry Header</div>
		    		<div class="panel-body">
		    			<div class='col-md-12' style="padding:0 0 15px 0">
            				<table id="jqGrid" class="table table-striped"></table>
            					<div id="jqGridPager"></div>
        				</div>
		    		</div>
		</div>-->



        <div class='col-md-7' >
			<form class='form-horizontal' style='width:99%' id='formdata'>

				<div class="prevnext btn-group pull-right">
				</div>

				<div class="form-group">
	                <label class="col-md-3 control-label" for="year">Year</label>  
	                    <div class="col-md-4">
	                      	<input id="year" name="year" type="text" maxlength="9" class="form-control input-sm text-uppercase" data-validation="required,number" frozeOnEdit rdonly>
	                    </div>
	                    <button type="button" id='saveyear' class='btn btn-info'>Save</button>
	                    <button type="button" id='cancelyear' class='btn btn-info'>Cancel</button>
	                    <button type="button" id='r' class='btn btn-info'>R</button>
				</div>
	                <table id="addPd" class ="table table-bordered">
	                	<thead>
					      <tr>
					         <th>Period</th>
					         <th>Date From</th>
					         <th>Date To</th>
					         <th>Status</th>
					      </tr>
					    </thead>
					    <tbody>
						    <tr id="1">
						    	<th scope="row">1</th>
						      		<td>
						      			<input id="datefr1" name="datefr1" type="date" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
						      			<input id="dateto1" name="dateto1" type="date" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
						      			<select class="form-control" name="periodstatus1" id="periodstatus1">
									        <option name="recstatus1" value="O" selected>Open</option>
									        <option name="recstatus1" value="C">Close</option>
									      </select>
						      		</td>
						    </tr>

						   	<tr id="2">
						      	<th scope="row">2</th>
							  		<td>
						      			<input id="datefr2" name="datefr2" type="date" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
						      			<input id="dateto2" name="dateto2" type="date" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
						      			<select class="form-control" name="periodstatus2" id="periodstatus2">
									        <option value="O" selected>Open</option>
									        <option value="C">Close</option>
									      </select>
						      		</td>
						    </tr>

						  	<tr id="3">
						      <th scope="row">3</th>
						      		<td>
						      			<input id="datefr3" name="datefr3" type="date" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
						      			<input id="dateto3" name="dateto3" type="date" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
						      			<select class="form-control" name="periodstatus3">
									        <option value="O" selected>Open</option>
									        <option value="C">Close</option>
									      </select>
						      		</td>
						    </tr>

						    <tr id="4">
						      	<th scope="row">4</th>
						      		<td>
						      			<input id="datefr4" name="datefr4" type="date" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
						      			<input id="dateto4" name="dateto4" type="date" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
						      			<select class="form-control" name="periodstatus4">
									        <option value="O" selected>Open</option>
									        <option value="C">Close</option>
									      </select>
						      		</td>
						    </tr>

						    <tr id="5">
						      	<th scope="row">5</th>
						      		<td>
						      			<input id="datefr5" name="datefr5" type="date" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
						      			<input id="dateto5" name="dateto5" type="date" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
						      			<select class="form-control" name="periodstatus5">
									        <option value="O" selected>Open</option>
									        <option value="C">Close</option>
									      </select>
						      		</td>
						    </tr>

						    <tr id="6">
						      	<th scope="row">6</th>
						     		<td>
						      			<input id="datefr6" name="datefr6" type="date" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
						      			<input id="dateto6" name="dateto6" type="date" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
						      			<select class="form-control" name="periodstatus6">
									        <option value="O" selected>Open</option>
									        <option value="C">Close</option>
									      </select>
						      		</td>
						    </tr>

						    <tr id="7">
						      	<th scope="row">7</th>
						      		<td>
						      			<input id="datefr7" name="datefr7" type="date" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
						      			<input id="dateto7" name="dateto7" type="date" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
						      			<select class="form-control" name="periodstatus7">
									        <option value="O" selected>Open</option>
									        <option value="C">Close</option>
									      </select>
						      		</td>
						    </tr>

						    <tr id="8">
						      	<th scope="row">8</th>
						      		<td>
						      			<input id="datefr8" name="datefr8" type="date" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
						      			<input id="dateto8" name="dateto8" type="date" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
						      			<select class="form-control" name="periodstatus8">
									        <option value="O" selected>Open</option>
									        <option value="C">Close</option>
									      </select>
						      		</td>
						    </tr>

						    <tr id="9">
						      	<th scope="row">9</th>
						      		<td>
						      			<input id="datefr9" name="datefr9" type="date" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
						      			<input id="dateto9" name="dateto9" type="date" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
						      			<select class="form-control" name="periodstatus9">
									        <option value="O" selected>Open</option>
									        <option value="C">Close</option>
									      </select>
						      		</td>
						    </tr>

						    <tr id="10">
						      	<th scope="row">10</th>
						      		<td>
						      			<input id="datefr10" name="datefr10" type="date" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
						      			<input id="dateto10" name="dateto10" type="date" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
						      			<select class="form-control" name="periodstatus10">
									        <option value="O" selected>Open</option>
									        <option value="C">Close</option>
									      </select>
						      		</td>
						    </tr>

						    <tr id="11">
						      	<th scope="row">11</th>
						      		<td>
						      			<input id="datefr11" name="datefr11" type="date" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
						      			<input id="dateto11" name="dateto11" type="date" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
						      			<select class="form-control" name="periodstatus11">
									        <option value="O" selected>Open</option>
									        <option value="C">Close</option>
									      </select>
						      		</td>
						    </tr>

						    <tr id="12">
						      	<th scope="row">12</th>
						      		<td>
						      			<input id="datefr12" name="datefr12" type="date" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
						      			<input id="dateto12" name="dateto12" type="date" class="form-control" data-validation="required" rdonly>
						      		</td>
						      		<td>
						      			<select class="form-control" name="periodstatus12">
									        <option value="O" selected>Open</option>
									        <option value="C">Close</option>
									      </select>
						      		</td>
						    </tr>
						</tbody>
	                </table>

			</form>
		</div>
    </div>
	<!-------------------------------- End Search + table   value="<?php //echo date("Y"); ?>" ------------------>

	
		

	<?php 
		include_once('../../../../footer.php'); 
	?>
	
	<!-- JS Implementing Plugins -->

	<!-- JS Customization -->

	<!-- JS Page Level -->
	<script src="period.js"></script>
	<script src="../../../../assets/js/utility.js"></script>
	<script src="../../../../assets/js/dialogHandler.js"></script>

<script>
		
</script>
</body>
</html>