<!DOCTYPE html>
<html>
<head>
<title>Print Patient Label</title>

</head>

<!-- <script src="https://unpkg.com/@jsreport/browser-client/dist/jsreport.umd.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="mydata.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>

</object>

<script>

	var item_dtl=[
		@foreach($table_chgtrx as $key => $dt)
		{
			@foreach($dt as $key2 => $val)
				'{{$key2}}' : `{!!str_replace('`', '', $val)!!}`,
			@endforeach
		},
		@endforeach 
	];

	console.log(item_dtl);
	
	var pat_mast = {
		@foreach($pat_mast as $key => $val) 
			'{{$key}}' : `{!!str_replace('`', '', $val)!!}`,
		@endforeach 
		'company' : '{{$company->name}}'
	};

	$(document).ready(function () {
		var docDefinition = {
			pageSize: {
				width: 60 * 2.8346456693,
			    height: 40 * 2.8346456693,
			},
			pageMargins: [5, 5, 5, 5],
			content:make_content(),
		  	styles: {
				normal: {
					margin: [0, 2, 0, 2],
					alignment:'left',
					fontSize:7
				},
				normal_tbl: {
					margin: [0, 0, 0, 0],
					alignment:'left',
					fontSize:7
				},
				table:{
					margin: [0, 0, 0, 0],
				}
			},
			defaultStyle: {
				fontSize: 12,
			},
		};

		// pdfMake.createPdf(docDefinition).getBase64(function(data) {
		// 	var base64data = "data:base64"+data;
		// 	console.log($('object#pdfPreview').attr('data',base64data));
		// 	// document.getElementById('pdfPreview').data = base64data;
			
		// });
		pdfMake.createPdf(docDefinition).getDataUrl(function(dataURL) {
			$('#pdfiframe').attr('src',dataURL);
		});
	});
	
	function make_content(){
		var content = [];

		item_dtl.forEach(function(e,i){
			content.push({text:pat_mast.company.toUpperCase(), bold:true, fontSize:10,alignment:'center' });
			content.push({text:pat_mast.Name.toUpperCase(), style: 'normal', bold:true ,alignment:'center' });

			content.push({
				style: 'table',
	            table: {
	                widths: [27,70,10,50],
	                body:[
	                	[
							{text: 'I/C',bold: true, style: 'normal_tbl'}, 
							{text: ': '+pat_mast.Newic.toUpperCase(), style: 'normal_tbl'},
							{text: 'MRN',bold: true, style: 'normal_tbl'}, 
							{text: ': '+pat_mast.MRN.toUpperCase(), style: 'normal_tbl'},
						],[
							{text: e.description,bold: true, style: 'normal_tbl',colSpan:4,margin: [0,5, 0, 0]}, 
							{}, 
							{}, 
							{},
						],[
							{text: 'Dose',bold: true, style: 'normal_tbl'}, 
							{text: ': '+e.doscode_desc, style: 'normal_tbl'},
							{text: 'Qty',bold: true, style: 'normal_tbl'}, 
							{text: ': '+e.quantity, style: 'normal_tbl'},
						],[
							{text: 'Frequency',bold: true, style: 'normal_tbl'}, 
							{text: ': '+e.frequency_desc, style: 'normal_tbl',colSpan:3},
							{}, 
							{},
						],[
							{text: 'Indicator',bold: true, style: 'normal_tbl'}, 
							{text: ': '+e.drugindicator_desc, style: 'normal_tbl',colSpan:3},
							{}, 
							{},
						],[
							{text: 'Instruction',bold: true, style: 'normal_tbl'}, 
							{text: ': '+e.addinstruction_desc, style: 'normal_tbl',colSpan:3},
							{}, 
							{},
						]
	                ]
	        },layout: 'noBorders'});
		});


			// var retval = [
			// 	{text:e.itemcode, style: 'bold', },
			// 	{text:e.uomcode, style: 'normal', },

			// ]

		return content;
	}

</script>

<body style="margin: 0px;">

<iframe id="pdfiframe" width="100%" height="100%" src="" frameborder="0" style="width: 99vw;height: 99vh;"></iframe>

</body>
</html>