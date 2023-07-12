<!DOCTYPE html>
<html>
<head>
<title>Sales Order</title>

</head>

<!-- <script src="https://unpkg.com/@jsreport/browser-client/dist/jsreport.umd.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="mydata.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha512-a9NgEEK7tsCvABL7KqtUTQjl69z7091EVPpw5KxPlZ93T141ffe1woLtbXTX+r2/8TtTvRX/v4zTL2UlMUPgwg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.min.js" integrity="sha512-P0bOMePRS378NwmPDVPU455C/TuxDS+8QwJozdc7PGgN8kLqR4ems0U/3DeJkmiE31749vYWHvBOtR+37qDCZQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</object>

<script>
	
	var dbacthdr = {
		@foreach($dbacthdr as $key => $val) 
			'{{$key}}' : '{{$val}}',
		@endforeach 
	};

	var billsum=[
		@foreach($billsum as $key => $bilsm)
		[
			@foreach($bilsm as $key2 => $val)
				{'{{$key2}}' : `{{$val}}`},
			@endforeach
		],
		@endforeach 
	];

	var company = {
		@foreach($company as $key => $val) 
			'{{$key}}' : '{{$val}}',
		@endforeach 
	};

	var title = {
		@foreach($company as $key => $val) 
			'{{$key}}' : '{{$val}}',
		@endforeach 
	};

	var totamt_bm = '{{$totamt_bm}}';

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
						widths: ['*','*','*','*','*','*','*','*','*','*'],
						// headerRows: 5,
						// keepWithHeaderRows: 5,
						body: [
							[
								{image: 'letterhead',width:175, height:65, style: 'tableHeader', colSpan: 5, alignment: 'center'},{},{},{},{},

								{text: '\n\n{{$title}}', style: 'tableHeader', colSpan: 5, alignment: 'center'},{},{},{},{}
							],

							[
								{
									text: 'Address To:\n\n{{$dbacthdr->debt_name}}\n{{$dbacthdr->cust_address1}}\n{{$dbacthdr->cust_address2}}\n{{$dbacthdr->cust_address3}}\n{{$dbacthdr->cust_address4}}', 
									colSpan: 5, 
									rowSpan: 4,
									alignment: 'left'},{},{},{},{},
								{
								 	text: 'Document Number',
								 	colSpan: 2, alignment: 'left'},{},
								{
									text: '{{str_pad($dbacthdr->auditno, 7, '0', STR_PAD_LEFT)}}',
								 	colSpan: 3, alignment: 'left'},{},{}
							],
							
							[
								{},{},{},{},{},
								{
								 	text: 'PO Date',
								 	colSpan: 2, alignment: 'left'},{},
								{
									text: '{{\Carbon\Carbon::createFromFormat('Y-m-d',$dbacthdr->podate)->format('d-m-Y')}}',
								 	colSpan: 3, alignment: 'left'},{},{}
							],

							[
								{},{},{},{},{},
								{
								 	text: 'Invoice No',
								 	colSpan: 2, alignment: 'left'},{},
								{
									text: '{{str_pad($dbacthdr->invno, 7, '0', STR_PAD_LEFT)}}',
								 	colSpan: 3, alignment: 'left'},{},{}
							],

							[
                                {},{},{},{},{},
                                {
                                    text: 'Invoice Date',
                                    colSpan: 2, alignment: 'left'},{},
                                {
                                    text: '{{\Carbon\Carbon::createFromFormat('Y-m-d',$dbacthdr->entrydate)->format('d-m-Y')}}',
                                    colSpan: 3, alignment: 'left'},{},{}
                            ],

							[
                                {text:'Description',colSpan: 5, style:'totalbold'},{},{},{},{},
                                {text:'UOM', style:'totalbold'},
                                {text:'Quantity', style:'totalbold'},
                                {text:'Unit Price', style:'totalbold'},
                                {text:'Tax Amt', style:'totalbold'},
                                {text:'Amount', style:'totalbold'}
                            ],

							@foreach ($billsum as $obj)
							[
								{text:`{{$obj->chgmast_desc}}`,colSpan: 5},{},{},{},{},
								{text:'{{$obj->uom}}'},
								{text:'{{$obj->quantity}}', alignment: 'right'},
								{text:'{{number_format($obj->unitprice,2)}}', alignment: 'right'},
								{text:'{{number_format($obj->taxamt,2)}}', alignment: 'right'},
								{text:'{{number_format($obj->amount,2)}}', alignment: 'right'},
							],
							@endforeach

                            [
								{text:'TOTAL', style: 'totalbold', colSpan: 9},{},{},{},{},{},{},{},{},
								{text:'{{number_format($dbacthdr->amount,2)}}', alignment: 'right'}
							],

							[
								{text:'RINGGIT MALAYSIA: {{$totamt_bm}}', style: 'totalbold',  italics: true, colSpan: 10}
							],
						
							
							[
								{text:

								    `ATTENTION:\n\n1. Payment of this bill can be pay to any registration counter of {{$company->name}} by stating the referral invoice number.\n
                                    2. Payment can be made by cash.\n
                                    3. Only cross cheque for any registered company with Ministry of Health Malaysia is acceptable and be issue to Director of	{{$company->name}}.\n
                                    4. Any inquiries must be issue to : \n
                                        {{$company->name}}\n{{$company->address1}}\n{{$company->address2}} {{$company->address3}}\n{{$company->address4}}\n`
									, colSpan: 10},{},{},{},{},{},{},{},{},{},
							],
						]
					}
				},
               
                {
					text: '\nPrinted Date: {{\Carbon\Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y')}}', fontSize: 8, italics: true,
				},
				{
					text: 'Printed Time: {{\Carbon\Carbon::now("Asia/Kuala_Lumpur")->format('H:i')}}', fontSize: 8, italics: true,
				},
				{
					text: 'Printed By: {{session('username')}}', fontSize: 8, italics: true,
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
					fontSize: 9,
					margin: [0, 5, 0, 15]
				},
				tableHeader: {
					bold: true,
					fontSize: 13,
					color: 'black'
				},
				totalbold: {
					bold: true,
					fontSize: 10,
				}
			},
			images: {
				letterhead: {
				url: 'http://msoftweb.test:8443/img/MSLetterHead.jpg',
			      headers: {
			        myheader: '123',
			        myotherheader: 'abc',
			      }
			    }
			}
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

	function make_header(){

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