<!DOCTYPE html>
<html>
<head>
<title>Stock Count</title>

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
	
	var phycnthd = {
		@foreach($phycnthd as $key => $val) 
			'{{$key}}' : '{{$val}}',
		@endforeach 
	};

	var company = {
		@foreach($company as $key => $val) 
			'{{$key}}' : '{{$val}}',
		@endforeach 
	};

	var printby = `{{session('username')}}`;

	var phycntdt=[
		@foreach($phycntdt as $key => $dtobj)
			{
			@foreach($dtobj as $key2 => $val)
				'{{$key2}}' : `{!!str_replace('`', '', $val)!!}`,
			@endforeach
			},
		
		@endforeach 
	];

	console.log(phycntdt);

	$(document).ready(function () {
		var docDefinition = {
			header: function(currentPage, pageCount, pageSize) {
				var retval=[];
				var header_tbl = {
	                    style: 'header_tbl',
	                    table: {
	                        headerRows: 1,
	                        widths: ['*','*','*','*','*','*','*','*',],//panjang standard dia 515
	                        body: [
	                            [
									{text: 'Department',bold: true}, 
									{text: ': '+phycnthd.srcdept},
									{text: 'Document No',bold: true}, 
									{text: ': '+phycnthd.docno},
									{text: 'Record No',bold: true}, 
									{text: ': '+phycnthd.recno},
									{text: 'Status',bold: true}, 
									{text: ': '+phycnthd.recstatus},
								],[
									{text: 'Item From',bold: true}, 
									{text: ': '+phycnthd.itemfrom},
									{text: 'Item To',bold: true}, 
									{text: ': '+phycnthd.itemto},
									{text: 'Rack No',bold: true}, 
									{text: ': '+phycnthd.rackno},
									{text: 'Page',bold: true}, 
									{text: ': '+currentPage+' / '+pageCount},
								],[
									{text: 'Freeze Date',bold: true}, 
									{text: ': '+phycnthd.frzdate},
									{text: 'Freeze Time',bold: true}, 
									{text: ': '+phycnthd.frztime},
									{text: 'Freezed By',bold: true}, 
									{text: ': '+phycnthd.adduser},
									{text: 'Printed By',bold: true}, 
									{text: ': '+printby},
								],[
									{text: 'Phy Count Date',bold: true}, 
									{text: ': '+phycnthd.phycntdate},
									{text: 'Phy Count Time',bold: true}, 
									{text: ': '+phycnthd.phycnttime},
									{text: 'Counted By',bold: true}, 
									{text: ': '+phycnthd.upduser},
									{text: 'Posted By',bold: true}, 
									{text: ': '+phycnthd.upduser},
								],[
									{text: 'Remarks',bold: true}, 
									{text: ': '+phycnthd.remarks, colSpan: 7},
									{},
									{},
									{},
									{},
									{},
									{},
								]
	                        ]
	                    },
				        layout: 'noBorders',
			        }

				var title = {text: company.name+'\n\nPhysical Count Listing',fontSize:10,alignment: 'center',bold: true, margin: [0, 20, 0, 0]};
				retval.push(title);

				retval.push(header_tbl);
				return retval

			},
			footer: function(currentPage, pageCount) {
				return [
			      { text: currentPage.toString() + ' of ' + pageCount, alignment: 'center' }
			    ]
			},
			pageSize: 'A4',
			pageMargins: [30, 150, 20, 50],
			pageOrientation: 'landscape',
		  	content: [
				{
					style: 'tableExample',
					table: {
	                    headerRows: 1,
	                	dontBreakRows: true,
	                    widths: [20,150,50,60,40,50,50,50,50,50,50,60,60],//panjang standard dia 515
						body: make_body()
					}
				},
			],
			styles: {
				tableExample: {
					fontSize: 9,
					margin: [0, 15, 0, 0]
				},
				header_tbl: {
					fontSize: 9,
					margin: [30, 20, 40, 20]
				},
				body_ttl: {
					margin: [0, 2, 0, 2]
				},
				body_row: {
					margin: [0, 3, 0, 3]
				},
				body_row2: {
					margin: [0, 0, 0, 2]
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

	function make_body(){
		var retval = [
	        [
				{text:'Line\nNo.',bold: true, style: 'body_ttl',alignment: 'left',border: [false, true, false, true]},
				{text:'ItemCode\nDescription',bold: true, style: 'body_ttl',alignment: 'left',border: [false, true, false, true]},
				{text:'Expiry\nDate',bold: true, style: 'body_ttl',alignment: 'left',border: [false, true, false, true]},
				{text:'Batch No',bold: true, style: 'body_ttl',alignment: 'left',border: [false, true, false, true]},
				{text:'UOM Code',bold: true, style: 'body_ttl',alignment: 'left',border: [false, true, false, true]},
				{text:'W.Avg\nCost',bold: true, style: 'body_ttl',alignment: 'right',border: [false, true, false, true]},
				{text:'Freeze\nQty',bold: true, style: 'body_ttl',alignment: 'right',border: [false, true, false, true]},
				{text:'Count\nQty',bold: true, style: 'body_ttl',alignment: 'right',border: [false, true, false, true]},
				{text:'Physical\nQty',bold: true, style: 'body_ttl',alignment: 'right',border: [false, true, false, true]},
				{text:'Variance\nQty',bold: true, style: 'body_ttl',alignment: 'right',border: [false, true, false, true]},
				{text:'Freeze\nValue',bold: true, style: 'body_ttl',alignment: 'right',border: [false, true, false, true]},
				{text:'Variance\nValue',bold: true, style: 'body_ttl',alignment: 'right',border: [false, true, false, true]}
			]
	    ];

		let totalvv = 0;
		let totalfrz = 0;
		phycntdt.forEach(function(e,i){
			let arr1 = [
				{text:parseInt(e.lineno_)+1, style: 'body_row', border: [false, false, false, false]},
				{text:e.itemcode, style: 'body_row', border: [false, false, false, false]},
				{text:dateFormatter(e.expdate), style: 'body_row', border: [false, false, false, false]},
				{text:e.batchno, style: 'body_row', border: [false, false, false, false]},
				{text:e.uomcode, style: 'body_row', border: [false, false, false, false]},
				{text:myparseFloat(e.unitcost),alignment: 'right', style: 'body_row', border: [false, false, false, false]},
				{text:myparseFloat(e.thyqty),alignment: 'right', style: 'body_row', border: [false, false, false, false]},
				{text:'',alignment: 'right', style: 'body_row', border: [false, false, false, true]},
				{text:myparseFloat(e.phyqty),alignment: 'right', style: 'body_row', border: [false, false, false, false]},
				{text:myparseFloat(parseFloat(e.phyqty) - parseFloat(e.thyqty)),alignment: 'right', style: 'body_row',border: [false, false, false, false]},
				{text:myparseFloatVV(e.unitcost,parseFloat(e.thyqty)),alignment: 'right', style: 'body_row', border: [false, false, false, false]},
				{text:myparseFloatVV(e.unitcost,parseFloat(e.phyqty) - parseFloat(e.thyqty)),alignment: 'right', style: 'body_row', border: [false, false, false, false]},
			];
    		retval.push(arr1);
    		let arr2 = [
				{text:'', style: 'body_row2', border: [false, false, false, false]},
				{text:e.description,colSpan: 5, style: 'body_row2', border: [false, false, false, false]},
				{},
				{},
				{},
				{},
				{text:e.remark,colSpan: 6, style: 'body_row2',alignment: 'right', border: [false, false, false, false]},
				{},
				{},
				{},
				{},
				{},
			];
    		retval.push(arr2);
    		totalfrz = parseFloat(totalfrz) + parseFloat(myparseFloatVV(e.unitcost,parseFloat(e.thyqty)));
    		totalvv = parseFloat(totalvv) + parseFloat(myparseFloatVV(e.unitcost,parseFloat(e.phyqty) - parseFloat(e.thyqty)));
		});

		let arrtot =  [
				{text:'*** TOTAL ***',bold: true,colSpan: 10,alignment: 'left', style: 'body_row', border: [false, false, false, false]},
				{},
				{},
				{},
				{},
				{},
				{},
				{},
				{},
				{},
				{text:myparseFloat(totalfrz),alignment: 'right', style: 'body_row', border: [false, false, false, false]},
				{text:myparseFloat(totalvv),alignment: 'right', style: 'body_row', border: [false, false, false, false]},
			];
		retval.push(arrtot);

    	return retval;
	}

	function dateFormatter(val){
		if(val == null) return '';
		if(val.trim() == '') return '';
		return moment(val).format("DD-MM-YYYY");
	} 

	function myparseFloat(val){
		if(Number.isInteger(val)){
			return numeral(val).format('0,0.00');
		}
		if(val == '') return '0.00';
		if(val == null) return '0.00';
		return numeral(val).format('0,0.00');
	} 

	function myparseFloatVV(unitcost,varqty){
		if(varqty == null) return '0.00';
		if(varqty == '') return '0.00';
		return numeral(unitcost*varqty).format('0,0.00');
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