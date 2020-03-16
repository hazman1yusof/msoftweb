@extends('layouts.main')

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

	.ui-dialog { z-index: 1123 !important ;}

	fieldset.scheduler-border {
	    border: 1px groove #ddd !important;
	    padding: 2px;
	    -webkit-box-shadow:  0px 0px 0px 0px #000;
	    box-shadow:  0px 0px 0px 0px #000;
	}

    legend.scheduler-border {
        font-size: 1.2em !important;
        font-weight: bold !important;
        text-align: left !important;
        width:auto;
        padding:0;
        margin:0px;
        border-bottom:none;
    }
    .scheduler-border button{
    	margin:3px;
	}

	.alertmodal{
		margin-top: 150px;
	}

	.alertmodal .modal-body{
		background-color:red;
		color:white;
		border-radius:5px;
	}



@endsection

@section('css')
	<link rel="stylesheet" href="plugins/datatables/css/jquery.dataTables.css">
	<link href="plugins/glDatePicker/styles/glDatePicker.default.css" rel="stylesheet" type="text/css">
@endsection

@section('title', 'Emergency Department')


@section('js')

    <script type="text/javascript">
        $(function () {
    		$("body").show();

    		$('#mydate').glDatePicker(
				{
				    showAlways: true,
				    
				});
    	});
    </script>

@endsection

@section('body')

	<input type="text" id="mydate" gldp-id="mydate" />
    <div gldp-el="mydate"
         style="width:400px; height:300px; position:absolute; top:70px; left:100px;">
    </div>

@endsection

@section('scripts')

	<script type="text/javascript" src="plugins/glDatePicker/glDatePicker.js"></script>
	<script type="text/javascript" src="plugins/glDatePicker/glDatePicker.min.js"></script>

@endsection