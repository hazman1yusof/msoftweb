<!DOCTYPE html>
<html>
<head>
<title>Page Title</title>

</head>

<!-- <script src="https://unpkg.com/@jsreport/browser-client/dist/jsreport.umd.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="mydata.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha512-a9NgEEK7tsCvABL7KqtUTQjl69z7091EVPpw5KxPlZ93T141ffe1woLtbXTX+r2/8TtTvRX/v4zTL2UlMUPgwg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.min.js" integrity="sha512-P0bOMePRS378NwmPDVPU455C/TuxDS+8QwJozdc7PGgN8kLqR4ems0U/3DeJkmiE31749vYWHvBOtR+37qDCZQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</object>

<script>
	
	purordhd
	
	var supplier = {
	    'SuppCode':'{{$supplier->SuppCode}}'
	};

	$(document).ready(function () {
		var docDefinition = {
			footer: function(currentPage, pageCount) {
				return [
			      { text: currentPage.toString() + ' of ' + pageCount, alignment: 'center' }
			    ]
			},
			pageSize: 'A4',
		  	content: [
				{
					style: 'tableExample',
					table: {
						widths: ['*','*','*','*','*','*','*','*','*','*','*'],
						// headerRows: 5,
						// keepWithHeaderRows: 5,
						body: [
							[{text: 'Image', style: 'tableHeader', colSpan: 5, alignment: 'center'},{},{},{},{},{text: 'Purchase Order', style: 'tableHeader', colSpan: 6, alignment: 'center'},{},{},{},{},{}],

							[{
								text: 'Address To:', 
								colSpan: 5, 
								rowSpan: 4,
								alignment: 'left'},{},{},{},{},
							{
							 	text: 'Purchase No.',
							 	colSpan: 2, alignment: 'left'},{},
							{
								text: supplier.SuppCode,
							 	colSpan: 4, alignment: 'left'},{},{},{}],
							[{},{},{},{},{},
							{
							 	text: 'Purchase Date.',
							 	colSpan: 2, alignment: 'left'},{},
							{
								text: '09-06-2023',
							 	colSpan: 4, alignment: 'left'},{},{},{}],
							[{},{},{},{},{},
							{
							 	text: 'Contact Person.',
							 	colSpan: 2, alignment: 'left'},{},
							{
								text: 'PCS000000066',
							 	colSpan: 4, alignment: 'left'},{},{},{}],
							[{},{},{},{},{},
							{
							 	text: 'Contact No.',
							 	colSpan: 2, alignment: 'left'},{},
							{
								text: '03-51610919',
							 	colSpan: 4, alignment: 'left'},{},{},{}],
						]
					}
				},
			],
			styles: {
				header: {
					fontSize: 18,
					bold: true,
					margin: [0, 0, 0, 10]
				},
				subheader: {
					fontSize: 16,
					bold: true,
					margin: [0, 10, 0, 5]
				},
				tableExample: {
					margin: [0, 5, 0, 15]
				},
				tableHeader: {
					bold: true,
					fontSize: 13,
					color: 'black'
				}
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