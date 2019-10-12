@extends('layouts.main')

@section('title', 'Period')


@section('body')
	<div class='row'>

    	<div class='col-md-5'>
    		<div class="panel panel-default">
    		<div class="panel panel-body">
            <table id="jqGrid" class="table table-striped"></table>
            <div id="jqGridPager"></div>
            	<div>
					<!--<button type="button" id='saveyear' class='btn btn-info'>Save</button>
					<button id='cancelyear' class='btn btn-info'>Cancel</button>-->
				</div>
			</div></div>
        </div>



        <div class='col-md-7' >
    		<div class="panel panel-default">
    		<div class="panel panel-body">
			<form class='form-horizontal' style='width:99%' id='formdata'>
			{{ csrf_field() }}

			<input type="hidden" name="idno">

				<div class="prevnext btn-group pull-right">
				</div>

				<div class="form-group">
	                <label class="col-md-3 control-label" for="year">Year</label>  
	                    <div class="col-md-4">
	                      	<input id="year" name="year" type="text" maxlength="9" class="form-control input-sm" data-validation="required,number" frozeOnEdit rdonly>
	                    </div>
	                    <button type="button" id='saveyear' class='btn btn-info'>Save</button>
	                    <button type="button" id='cancelyear' class='btn btn-info'>Cancel</button>
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
						    <tr id="td1">
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

						   	<tr id="td2">
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

						  	<tr id="td3">
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

						    <tr id="td4">
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

						    <tr id="td5">
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

						    <tr id="td6">
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

						    <tr id="td7">
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

						    <tr id="td8">
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

						    <tr id="td9">
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

						    <tr id="td10">
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

						    <tr id="td11">
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

						    <tr id="td12">
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

			</form></div></div>
		</div>
    </div>
@endsection

@section('scripts')
	<script src="js/finance/GL/period/period.js"></script>
@endsection