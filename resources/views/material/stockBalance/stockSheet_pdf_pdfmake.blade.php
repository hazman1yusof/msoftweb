<!DOCTYPE html>
<html>
<head>
<title>Stock Sheet PDF</title>

</head>

<!-- <script src="https://unpkg.com/@jsreport/browser-client/dist/jsreport.umd.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="mydata.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha512-a9NgEEK7tsCvABL7KqtUTQjl69z7091EVPpw5KxPlZ93T141ffe1woLtbXTX+r2/8TtTvRX/v4zTL2UlMUPgwg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.min.js" integrity="sha512-P0bOMePRS378NwmPDVPU455C/TuxDS+8QwJozdc7PGgN8kLqR4ems0U/3DeJkmiE31749vYWHvBOtR+37qDCZQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

<script>
	
	var stockloc=[
		@foreach($stockloc as $key => $dtobj)
			{
			@foreach($dtobj as $key2 => $val)
				'{{$key2}}' : `{!!str_replace('`', '', $val)!!}`,
			@endforeach
			},
		@endforeach 
	];

	var deptcode = [
		@foreach($deptcode as $key => $dtobj)
			{
			@foreach($dtobj as $key2 => $val)
				'{{$key2}}' : `{{$val}}`,
			@endforeach
			},
		@endforeach 
	];

	var company = {
		@foreach($company as $key => $val) 
			'{{$key}}' : '{{$val}}',
		@endforeach 
	};

	var header = {
		@foreach($header as $key => $val) 
			'{{$key}}' : '{{$val}}',
		@endforeach 
	};

	$(document).ready(function () {
		var docDefinition = {
			header: function(currentPage, pageCount, pageSize) {
				var retval=[];
				// var header_tbl = {
	            //         style: 'header_tbl',
	            //         table: {
	            //             headerRows: 1,
	            //             widths: ['*','*','*','*','*','*',],//panjang standard dia 515
	            //             body: [
	            //                 [
				// 					{text: 'Department',bold: true}, 
				// 					{text: ': '+header.deptfrom},
				// 					{text: '',bold: true}, 
				// 					{text: ': '+header.deptto},
				// 					{text: 'Year',bold: true}, 
				// 					{text: ': '+header.year},
				// 				],[
				// 					{text: 'Item From',bold: true}, 
				// 					{text: ': '+header.itemfrom},
				// 					{text: 'Item To',bold: true}, 
				// 					{text: ': '+header.itemto},
				// 					{text: 'Period',bold: true}, 
				// 					{text: ': '+header.period},
				// 				],[
				// 					{text: 'Print By',bold: true}, 
				// 					{text: ': '+header.printby},
				// 					{text: 'Print Date',bold: true}, 
				// 					{text: ': '+moment().format("DD-MM-YYYY")},
				// 					{text: 'Page',bold: true}, 
				// 					{text: ': '+currentPage+' / '+pageCount},
				// 				]
	            //             ]
	            //         },
				//         layout: 'noBorders',
			    //     }

				var title = {text: company.name+'\nStock Sheet',fontSize:10,alignment: 'center',bold: true, margin: [0, 20, 0, 0]};
				retval.push(title);

				// retval.push(header_tbl);
				return retval

			},
			footer: function(currentPage, pageCount) {
				return [
			      { text: currentPage.toString() + ' of ' + pageCount, alignment: 'center' }
			    ]
			},
			pageSize: 'A4',
			pageMargins: [20, 50, 20, 20],
		  	content: init_content(),
			styles: {
				tableExample: {
					fontSize: 9,
					margin: [0, 15, 0, 0]
				},
				header_tbl: {
					fontSize: 9,
					margin: [0, 0, 0, 10]
				},
				body_ttl: {
					margin: [0, 2, 0, 2]
				},
				body_row: {
					margin: [0, 3, 0, 3]
				},
				body_row2: {
					margin: [0, 10, 0, 0]
				},
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

	function init_content(){
		var content=[];

		deptcode.forEach(function(e,i){
			let ret_con ={
				style: 'tableExample',
				table: {
	                headerRows: 2,
	            	dontBreakRows: true,
	                widths: [55,130,40,50,50,180],//panjang standard dia 515
					body: make_body(e.deptcode.trim().toUpperCase(),e.description)
				}
			};
    		content.push(ret_con);
    		content.push({ text: '', alignment: 'left', fontSize: 9, pageBreak: 'after' });
		});

		return content;
	}

	function make_body(dept,desc){
		var retval = [
	        [
				{
					style: 'header_tbl',
                    table: {
                        headerRows: 1,
                        widths: ['*','*','*','*','*','*',],//panjang standard dia 515
                        body: [
                            [
								{text: 'Department',bold: true}, 
								{text: ': '+dept.toUpperCase()},
								{text: 'Name',bold: true}, 
								{text: ': '+desc.toUpperCase()},
								{text: 'Year',bold: true}, 
								{text: ': '+header.year},
							],[
								{text: 'Item From',bold: true}, 
								{text: ': '+header.itemfrom},
								{text: 'Item To',bold: true}, 
								{text: ': '+header.itemto},
								{text: 'Period',bold: true}, 
								{text: ': '+header.period},
							],[
								{text: 'Print By',bold: true}, 
								{text: ': '+header.printby},
								{text: 'Print Date',bold: true}, 
								{text: ': '+moment().format("DD-MM-YYYY")},
								{text: '',bold: true}, 
								{text: ''},
							]
                        ]
                    },
			        layout: 'noBorders',colSpan: 6,border: [false, false, false, false]
				},{},{},{},{},{}
			],
	        [
				{text:'Item Code',bold: true, style: 'body_ttl',alignment: 'left',border: [false, true, false, true]},
				{text:'Description',bold: true, style: 'body_ttl',alignment: 'left',border: [false, true, false, true]},
				{text:'Uom',bold: true, style: 'body_ttl',alignment: 'left',border: [false, true, false, true]},
				{text:'Closing\nQty',bold: true, style: 'body_ttl',alignment: 'right',border: [false, true, false, true]},
				{text:'Physical\nQty',bold: true, style: 'body_ttl',alignment: 'right',border: [false, true, false, true]},
				{text:'Remark',bold: true, style: 'body_ttl',alignment: 'left',border: [false, true, false, true]},
			]
	    ];
		stockloc.forEach(function(e,i){
			if(e.deptcode.trim().toUpperCase() == dept){
				let arr1 = [
					{text:e.itemcode, style: 'body_row', border: [false, false, false, false]},
					{text:e.description, style: 'body_row', border: [false, false, false, false]},
					{text:e.uomcode, style: 'body_row', border: [false, false, false, false]},
					{text:myparseFloat(e.close_balqty),alignment: 'right', style: 'body_row', border: [false, false, false, false]},
					{text:'___________',alignment: 'right', style: 'body_row2', border: [false, false, false, false]},
					{text:'____________________________________________',alignment: 'right', style: 'body_row2', border: [false, false, false, false]},
				];
	    		retval.push(arr1);
			}
		});

		return retval;
	}

	function dateFormatter(val){
		if(val == null) return '';
		if(val.trim() == '') return '';
		return moment(val).format("DD-MM-YYYY");
	} 

	function myparseFloat(val){
		if(val == null) return '0.00';
		if(val.trim() == '') return '0.00';
		return numeral(val).format('0,0.00');
	} 

	function myparseFloatVV(unitcost,dspqty){
		if(dspqty == null) return '0.00';
		if(dspqty.trim() == '') return '0.00';
		return numeral(unitcost*dspqty).format('0,0.00');
	} 
	

	// pdfMake.createPdf(docDefinition).getDataUrl(function(dataURL) {
	// 	console.log(dataURL);
	// 	document.getElementById('pdfPreview').data = dataURL;
	// });

	

	// jsreport.serverUrl = 'http://localhost:5488'
    // async function preview() {        
    //     const report = await jsreport.render({
	// 	  template: {
	// 	    name: 'mc'    
	// 	  },
	// 	  data: mydata
	// 	});
	// 	document.getElementById('pdfPreview').data = await report.toObjectURL()

    // }

    // preview().catch(console.error)
</script>

<body style="margin: 0px;">

<iframe id="pdfiframe" width="100%" height="100%" src="" frameborder="0" style="width: 99vw;height: 99vh;"></iframe>

</body>
</html>