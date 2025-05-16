@extends('layouts.main')

@section('title', 'Chart Account')

@section('style')
	.num{
		width:20px;
	}
	.mybtn{
		float: right;
		display: none;
	}
	.bg-primary .mybtn{
		display:block;
	}
	.textalignright,#TableGlmasTran_filter { text-align:right !important; }
	.textalignright div { padding-right: 5px; }
	.numericCol{
		text-align : right;
	}
	.bg-info{
		background-color: white;
	}
	input.uppercase {
  		text-transform: uppercase;
	}

@endsection

@section('body')

	<!-------------------------------- Search + table ---------------------->
    <input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">

    <form id='formdata'>
        <div class='row'>
            <div class='col-md-12' style="padding:0 0 15px 0;">
                <div class="form-group"> 
                <div class="col-md-7">
                    <label class="control-label" for="glaccountSearch">GL Account</label>  
                    <div class='input-group'>
                        <input id="glaccountSearch" name="glaccountSearch" type="text" class="form-control input-sm uppercase" autocomplete="off"/>
                        <a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
                    </div>
                        <span class="help-block"></span>
                </div>
                <div class="col-md-2">
                    <label class="control-label" for="yearSearch">Year</label>  
                    <select id='yearSearch' name='yearSearch' class="form-control input-sm"></select>
                </div>
                <div class="col-md-1">
                    <button type="button" id="search" class="btn btn-primary" style="position:absolute;top:17px">Search</button>
                </div>
                </div>
            </div>
                
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class='col-md-12' style="padding:0 0 15px 0">
                        <table id="jqGrid" class="table table-striped"></table>
                            <div id="jqGridPager"></div>
                    </div>
                </div>
            </div>

            <div class='panel panel-default'>
                <div class="panel-body">
                <input id="costcode" name="costcode" type="hidden">
                <input id="glaccount" name="glaccount" type="hidden">
                <input id="year" name="year" type="hidden">

                <table id="addChartAcc" class ="table table-bordered">
                            <thead>
                            <tr>
                                <th>Period</th>
                                <th>Actual</th>
                                <th>Budget</th>
                                <th>Variance</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr id="td1">
                                    <th scope="row">1</th>
                                        <td>
                                            <input id="actamount1" name="actamount1" type="text" class="form-control text-right" data-sanitize-number-format="0,0.00" readonly>
                                        </td>
                                        <td>
                                            <input id="bdgamount1" name="bdgamount1" type="text" class="form-control text-right" data-sanitize-number-format="0,0.00" readonly>
                                        </td>
                                        <td>
                                            <input id="varamount1" name="varamount1" type="text" class="form-control text-right" data-sanitize-number-format="0,0.00" readonly>
                                        </td>
                                </tr>

                                <tr id="td2">
                                    <th scope="row">2</th>
                                        <td>
                                            <input id="actamount2" name="actamount2" type="text" class="form-control text-right" data-sanitize-number-format="0,0.00" readonly>
                                        </td>
                                        <td>
                                            <input id="bdgamount2" name="bdgamount2" type="text" class="form-control text-right" data-sanitize-number-format="0,0.00" readonly>
                                        </td>
                                        <td>
                                            <input id="varamount2" name="varamount2" type="text" class="form-control text-right" data-sanitize-number-format="0,0.00" readonly>
                                        </td>
                                </tr>

                                <tr id="td3">
                                <th scope="row">3</th>
                                        <td>
                                            <input id="actamount3" name="actamount3" type="text" class="form-control text-right" data-sanitize-number-format="0,0.00" readonly>
                                        </td>
                                        <td>
                                            <input id="bdgamount3" name="bdgamount3" type="text" class="form-control text-right" data-sanitize-number-format="0,0.00" readonly>
                                        </td>
                                        <td>
                                            <input id="varamount3" name="varamount3" type="text" class="form-control text-right" data-sanitize-number-format="0,0.00" readonly>
                                        </td>
                                </tr>

                                <tr id="td4">
                                    <th scope="row">4</th>
                                        <td>
                                            <input id="actamount4" name="actamount4" type="text" class="form-control text-right" data-sanitize-number-format="0,0.00" readonly>
                                        </td>
                                        <td>
                                            <input id="bdgamount4" name="bdgamount4" type="text" class="form-control text-right" data-sanitize-number-format="0,0.00" readonly>
                                        </td>
                                        <td>
                                            <input id="varamount4" name="varamount4" type="text" class="form-control text-right" data-sanitize-number-format="0,0.00" readonly>
                                        </td>
                                </tr>

                                <tr id="td5">
                                    <th scope="row">5</th>
                                        <td>
                                            <input id="actamount5" name="actamount5" type="text" class="form-control text-right" data-sanitize-number-format="0,0.00" readonly>
                                        </td>
                                        <td>
                                            <input id="bdgamount5" name="bdgamount5" type="text" class="form-control text-right" data-sanitize-number-format="0,0.00" readonly>
                                        </td>
                                        <td>
                                            <input id="varamount5" name="varamount5" type="text" class="form-control text-right" data-sanitize-number-format="0,0.00" readonly>
                                        </td>
                                </tr>

                                <tr id="td6">
                                    <th scope="row">6</th>
                                        <td>
                                            <input id="actamount6" name="actamount6" type="text" class="form-control text-right" data-sanitize-number-format="0,0.00" readonly>
                                        </td>
                                        <td>
                                            <input id="bdgamount6" name="bdgamount6" type="text" class="form-control text-right" data-sanitize-number-format="0,0.00" readonly>
                                        </td>
                                        <td>
                                            <input id="varamount6" name="varamount6" type="text" class="form-control text-right" data-sanitize-number-format="0,0.00" readonly>
                                        </td>
                                </tr>

                                <tr id="td7">
                                    <th scope="row">7</th>
                                        <td>
                                            <input id="actamount7" name="actamount7" type="text" class="form-control text-right" data-sanitize-number-format="0,0.00" readonly>
                                        </td>
                                        <td>
                                            <input id="bdgamount7" name="bdgamount7" type="text" class="form-control text-right" data-sanitize-number-format="0,0.00" readonly>
                                        </td>
                                        <td>
                                            <input id="varamount7" name="varamount7" type="text" class="form-control text-right" data-sanitize-number-format="0,0.00" readonly>
                                        </td>
                                </tr>

                                <tr id="td8">
                                    <th scope="row">8</th>
                                        <td>
                                            <input id="actamount8" name="actamount8" type="text" class="form-control text-right" data-sanitize-number-format="0,0.00" readonly>
                                        </td>
                                        <td>
                                            <input id="bdgamount8" name="bdgamount8" type="text" class="form-control text-right" data-sanitize-number-format="0,0.00" readonly>
                                        </td>
                                        <td>
                                            <input id="varamount8" name="varamount8" type="text" class="form-control text-right" data-sanitize-number-format="0,0.00" readonly>
                                        </td>
                                </tr>

                                <tr id="td9">
                                    <th scope="row">9</th>
                                        <td>
                                            <input id="actamount9" name="actamount9" type="text" class="form-control text-right" data-sanitize-number-format="0,0.00" readonly>
                                        </td>
                                        <td>
                                            <input id="bdgamount9" name="bdgamount9" type="text" class="form-control text-right" data-sanitize-number-format="0,0.00" readonly>
                                        </td>
                                        <td>
                                            <input id="varamount9" name="varamount9" type="text" class="form-control text-right" data-sanitize-number-format="0,0.00" readonly>
                                        </td>
                                </tr>

                                <tr id="td10">
                                    <th scope="row">10</th>
                                        <td>
                                            <input id="actamount10" name="actamount10" type="text" class="form-control text-right" data-sanitize-number-format="0,0.00" readonly>
                                        </td>
                                        <td>
                                            <input id="bdgamount10" name="bdgamount10" type="text" class="form-control text-right" data-sanitize-number-format="0,0.00" readonly>
                                        </td>
                                        <td>
                                            <input id="varamount10" name="varamount10" type="text" class="form-control text-right" data-sanitize-number-format="0,0.00" readonly>
                                        </td>
                                </tr>

                                <tr id="td11">
                                    <th scope="row">11</th>
                                        <td>
                                            <input id="actamount11" name="actamount11" type="text" class="form-control text-right" data-sanitize-number-format="0,0.00" readonly>
                                        </td>
                                        <td>
                                            <input id="bdgamount11" name="bdgamount11" type="text" class="form-control text-right" data-sanitize-number-format="0,0.00" readonly>
                                        </td>
                                        <td>
                                            <input id="varamount11" name="varamount11" type="text" class="form-control text-right" data-sanitize-number-format="0,0.00" readonly>
                                        </td>
                                </tr>

                                <tr id="td12">
                                    <th scope="row">12</th>
                                        <td>
                                            <input id="actamount12" name="actamount12" type="text" class="form-control text-right" data-sanitize-number-format="0,0.00" readonly>
                                        </td>
                                        <td>
                                            <input id="bdgamount12" name="bdgamount12" type="text" class="form-control text-right" data-sanitize-number-format="0,0.00" readonly>
                                        </td>
                                        <td>
                                            <input id="varamount12" name="varamount12" type="text" class="form-control text-right" data-sanitize-number-format="0,0.00" readonly>
                                        </td>
                                </tr>
                                <tr id="td13">
                                    <th scope="row">Total</th>
                                        <td>
                                            <input id="totalActual" name="totalActual" type="text" class="form-control text-right" data-sanitize-number-format="0,0.00" readonly>
                                        </td>
                                        <td>
                                            <input id="totalBdg" name="totalBdg" type="text" class="form-control text-right" data-sanitize-number-format="0,0.00" readonly>
                                        </td>
                                        <td>
                                            <input id="totalVar" name="totalVar" type="text" class="form-control text-right" data-sanitize-number-format="0,0.00" readonly>
                                        </td>
                                </tr>
                            </tbody>
                            
                            
                        </table>
                        <div class="prevnext btn-group pull-right">
                            <button type="button" id='save' class='btn btn-info btn-sm pull-right' style='margin: 0.2%;'>Save</button>
					        <button type="button" id='edit' class='btn btn-info btn-sm pull-right' style='margin: 0.2%;'>Edit</button>
				        </div>
                </div>
            </div>
            
        </div>
    </form>
	<!-------------------------------- End Search + table ------------------>

@endsection

@section('scripts')

	<script src="js/finance/GL/chartAccount/chartAccount.js?v=1.1"></script>
	
@endsection