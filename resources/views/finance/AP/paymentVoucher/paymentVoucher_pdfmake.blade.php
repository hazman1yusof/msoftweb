<!DOCTYPE html>
<html>
<head>
<title>Payment Voucher</title>

</head>

<!-- <script src="https://unpkg.com/@jsreport/browser-client/dist/jsreport.umd.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="mydata.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha512-a9NgEEK7tsCvABL7KqtUTQjl69z7091EVPpw5KxPlZ93T141ffe1woLtbXTX+r2/8TtTvRX/v4zTL2UlMUPgwg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.min.js" integrity="sha512-P0bOMePRS378NwmPDVPU455C/TuxDS+8QwJozdc7PGgN8kLqR4ems0U/3DeJkmiE31749vYWHvBOtR+37qDCZQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</object>

<script>
	
	var apacthdr = {
		@foreach($apacthdr as $key => $val) 
			'{{$key}}' : '{{$val}}',
		@endforeach 
	};

	var apalloc=[
		@foreach($apalloc as $key => $alloc)
		[
			@foreach($alloc as $key2 => $val)
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

	var totamt_eng = '{{$totamt_eng}}';

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
					image: 'letterhead',width:400, height:80, style: 'tableHeader', colSpan: 5, alignment: 'center'
                },
				{
                    text: '{{$title}}',
                    style: 'header',
                    alignment: 'center'
				},
               
                // {
                //     text: '{{$company->name}}\n{{$company->address1}}\n{{$company->address2}}\n{{$company->address3}}\n{{$company->address4}}\n\n\n', 
                //     alignment: 'center',
                //     style: 'comp_header'
                // },
                {
                    style: 'tableExample',
                    table: {
                        headerRows: 1,
                        widths: ['*','*','*','*','*'],//panjang standard dia 515

                        body: [
                            [
								{text: 'Pay To', style: 'tableHeader'}, 
								{text: 'Voucher Number', style: 'tableHeader'}, 
								{text: 'Paymode', style: 'tableHeader'}, 
								{text: 'Date', style: 'tableHeader'}, 
								{text: 'Bank Acc No', style: 'tableHeader'}
							],
                            [
								'{{$apacthdr->suppname}}', 
								'{{$apacthdr->pvno}}', 
								'{{$apacthdr->paymode}}', 
								'{{\Carbon\Carbon::createFromFormat('Y-m-d',$apacthdr->actdate)->format('d-m-Y')}}', 
								'{{$apacthdr->bankaccno}}'
							],

                        ]
                    },
			        layout: 'lightHorizontalLines',
		        },
				{
                    style: 'tableExample',
                    table: {
                        headerRows: 1,
                        widths: ['*'],//panjang standard dia 515

                        body: [
                            [{text: 'Remark', style: 'tableHeader'}],
                            ['{{$apacthdr->remarks}}'],

                        ]
                    },
			        layout: 'headerLineOnly',
		        },
				{
                    style: 'tableExample',
                    table: {
                        headerRows: 1,
                        widths: ['*','*','*','*'],//panjang standard dia 515

                        body: [
                            [
								{text: 'Invoice Date', style: 'tableHeader'}, 
								{text: 'Invoice No', style: 'tableHeader'}, 
								{text: 'Description', style: 'tableHeader'}, 
								{text: 'Amount', style: 'tableHeader', alignment: 'right'}, 
							],
							@foreach ($apalloc as $obj)
							[
								
								{text:'{{\Carbon\Carbon::createFromFormat('Y-m-d',$obj->allocdate)->format('d-m-Y')}}'},
								{text:'{{$obj->reference}}'},
								{text:'{{$obj->remarks}}'},
								{text:'{{number_format($obj->refamount,2)}}', alignment: 'right'},
							],
							@endforeach

                        ]
                    },
			        layout: 'lightHorizontalLines',
		        },
				{
                    style: 'tableExample',
                    table: {
                        headerRows: 1,
                        widths: ['*', '*'],//panjang standard dia 515

                        body: [
                            [
								{text: 'Ringgit Malaysia', style: 'tableHeader'}, 
								{text: 'Total Amount', style: 'tableHeader', alignment: 'right'}
							],
                            [
								{text: '{{$totamt_eng}}'}, 
								{text: '{{number_format($apacthdr->amount,2)}}', alignment: 'right'}
							],

                        ]
                    },
			        layout: 'noBorders',
		        },
				{canvas: [{ type: 'line', x1: 0, y1: 5, x2: 515, y2: 5, lineWidth: 0.5 }]},
				{
                    style: 'tableExample',
                    table: {
                        headerRows: 1,
                        widths: ['*', '*', '*', '*', '*'],//panjang standard dia 515

                        body: [
                            [
								{text: 'Prepared By:\n\n\n\n', style: 'tableHeader'}, 
								{text: 'Checked By:', style: 'tableHeader'},
								{text: 'Approved By:', style: 'tableHeader'},
								{text: 'Signatories:', style: 'tableHeader'},
								{text: '', style: 'tableHeader'},
							],
                            [
								{text: '___________________'},
								{text: '___________________'}, 
								{text: '___________________'}, 
								{text: '___________________'}, 
								{text: '___________________'}, 
							],
							[
								{text: 'Name:', fontSize: 8},
								{text: 'Name:', fontSize: 8},
								{text: 'Name:', fontSize: 8},
								{text: 'Name:', fontSize: 8},
								{text: 'Name:', fontSize: 8},
							],

                        ]
                    },
			        layout: 'noBorders',
		        },
				{canvas: [{ type: 'line', x1: 0, y1: 5, x2: 515, y2: 5, lineWidth: 0.5 }]},
				{
					text: '\nReceived By:\n\n', style: 'tableHeader',
				},
				{
					text: 'Name:\n\n', fontSize: 10,
				},
				{
					text: 'IC or Passport:\n\n', fontSize: 10,
				},
				{
					text: 'Signature:\n\n',fontSize: 10,
				},
			
				{canvas: [{ type: 'line', x1: 0, y1: 5, x2: 515, y2: 5, lineWidth: 0.5 }]},
				{
					text: '\nDR: {{$apacthdr->suppcode}} {{$apacthdr->suppname}} {{number_format($apacthdr->amount,2)}}', fontSize: 9, 
				},
				{
					text: 'CR: {{$apacthdr->bankcode}} {{$apacthdr->bankname}} {{number_format($apacthdr->amount,2)}}', fontSize: 9,
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
					fontSize: 10,
					color: 'black'
				},
				totalbold: {
					bold: true,
					fontSize: 10,
				},
                comp_header: {
					bold: true,
					fontSize: 8,
				}

			},
			images: {
				letterhead: 'http://msoftweb.test:8443/img/MSLetterHead.jpg',
				// width: 150,
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