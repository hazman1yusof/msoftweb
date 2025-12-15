@extends('layouts.main')

@section('title', 'Consolidation Account')


@section('body')
<input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">

	<div class='row'>
        <form id="searchForm" class="formclass" style='width:99%; position:relative' onkeydown="return event.key != 'Enter';">
			<fieldset>
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

    	<div class='col-md-6'>
    		<div class="panel panel-default">
    		<div class="panel panel-body">
            <table id="jqGrid" class="table table-striped"></table>
            <div id="jqGridPager"></div>
			</div></div>
        </div>

        <div class='col-md-6'>
    		<div class="panel panel-default">
				<div class="panel panel-body">
					<form class='form-horizontal' style='width:99%' id='formdata'>
					{{ csrf_field() }}
					<input type="hidden" name="idno" id="idno">
						<div class="form-group">
							<label class="col-md-1 control-label" for="code">Code</label>  
								<div class="col-md-2">
									<input id="code" name="code" type="text" class="form-control input-sm text-uppercase" frozeOnEdit rdonly>
								</div>
							<label class="col-md-1 control-label" for="description">Desc</label>  
								<div class="col-md-8">
									<input id="description" name="description" type="text" class="form-control input-sm text-uppercase" rdonly>
								</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class='col-md-6'>
    		<div class="panel panel-default">
				<div class="panel panel-body">
					<form id='formdata2' class='form-vertical' style='width:99%'>
						<div id="jqGrid2_c" class='col-md-12'>
							<table id="jqGrid2" class="table table-striped"></table>
								<div id="jqGridPager2"></div>
						</div>
					</form>
				</div>
			</div>
		</div>
    </div>
@endsection

@section('scripts')
	<script src="js/finance/GL/consolidationAcc/consolidationAcc.js"></script>
@endsection