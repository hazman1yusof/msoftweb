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
					image: 'letterhead',width:175, height:65, style: 'tableHeader', colSpan: 5, alignment: 'center'
				},
				{
					text: ' {{$title}}\n',
					style: 'header',
					alignment: 'center'
				},
				{
					style: 'tableExample',
					table: {
						headerRows: 1,
						widths: [50, 10, '*', 50, 10, '*'], //panjang standard dia 515
						body: [
							[
								{text: 'GST ID NO'},
								{text: ':'},
								{text: ''},
								{},
								{},
								{},
								
							],
							[
								{text: 'DEBTOR'},
								{text: ':'},
								{text: '{{$dbacthdr->debt_debtcode}}'},
								{text: 'BILL NO'},
								{text: ':'},
								{text: '{{str_pad($dbacthdr->invno, 7, "0", STR_PAD_LEFT)}}'},
							],
							[
								{text: 'NAME'},
								{text: ':'},
								{text: '{{$dbacthdr->debt_name}}'},
								{text: 'BILL DATE'},
								{text: ':'},
								{text: '{{\Carbon\Carbon::createFromFormat('Y-m-d',$dbacthdr->entrydate)->format('d-m-Y')}}'},
								
							],
							[
								{text: 'ADDRESS'},
								{text: ':'},
								{text: '{{$dbacthdr->cust_address1}}\n{{$dbacthdr->cust_address2}}\n{{$dbacthdr->cust_address3}}\n{{$dbacthdr->cust_address4}}'},
								{text: 'FIN CLASS'},
								{text: ':'},
								@if(!empty($dbacthdr->dt_debtortycode) && !empty($dbacthdr->dt_description))
									{text: '{{$dbacthdr->dt_debtortycode}} ({{$dbacthdr->dt_description}})'},
								@else
									{text: ''},
								@endif
							],
							[{},{},{},{},{},{},],
							[
								{text: 'CREDIT TERM'},
								{text: ':'},
								@if(!empty($dbacthdr->crterm))
									{text: '{{$dbacthdr->crterm}} DAYS'},
								@else
									{text: ''},
								@endif
								{text: 'BILL TYPE'},
								{text: ':'},
								@if(!empty($dbacthdr->billtype) && !empty($dbacthdr->bt_desc))
									{text: '{{$dbacthdr->billtype}} ({{$dbacthdr->bt_desc}})'},
								@else
									{text: ''},
								@endif	
							],
						]
					},
					layout: 'noBorders',
				},
				{
					style: 'tableExample',
					table: {
						widths: ['*','*','*','*','*','*','*','*','*','*'],
						body: [

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
								{text:`{!!$obj->uom!!}`},
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
                                        \u200B\t\u200B\t\u200B\t\u200B\t{{$company->name}}\n\u200B\t\u200B\t\u200B\t\u200B\t{{$company->address1}}\n\u200B\t\u200B\t\u200B\t\u200B\t{{$company->address2}} {{$company->address3}}\n\u200B\t\u200B\t\u200B\t\u200B\t{{$company->address4}}\n`
									, colSpan: 10},{},{},{},{},{},{},{},{},{},
							],
						]
					},
					layout: 'lightHorizontalLines',
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
				}
			},
			images: {
				letterhead: 'data:image/jpeg;base64,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'
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