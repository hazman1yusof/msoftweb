<!DOCTYPE html>
<html>
<head>
<title>Purchase Order</title>

</head>

<!-- <script src="https://unpkg.com/@jsreport/browser-client/dist/jsreport.umd.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="mydata.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha512-a9NgEEK7tsCvABL7KqtUTQjl69z7091EVPpw5KxPlZ93T141ffe1woLtbXTX+r2/8TtTvRX/v4zTL2UlMUPgwg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.min.js" integrity="sha512-P0bOMePRS378NwmPDVPU455C/TuxDS+8QwJozdc7PGgN8kLqR4ems0U/3DeJkmiE31749vYWHvBOtR+37qDCZQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</object>

<script>
	
	var purordhd = {
		@foreach($purordhd as $key => $val) 
			'{{$key}}' : '{{$val}}',
		@endforeach 
	};

	var purorddt=[
		@foreach($purorddt as $key => $podt)
		[
			@foreach($podt as $key2 => $val)
				{'{{$key2}}' : `{{$val}}`},
			@endforeach
		],
		@endforeach 
	];

	var supplier = {
		@foreach($supplier as $key => $val) 
			'{{$key}}' : '{{$val}}',
		@endforeach 
	};

	var company = {
		@foreach($company as $key => $val) 
			'{{$key}}' : '{{$val}}',
		@endforeach 
	};

	var deldept = {
		@foreach($deldept as $key => $val) 
			'{{$key}}' : '{{$val}}',
		@endforeach 
	};

	var totamt_bm = '{{$totamt_bm}}';
	var total_tax = '{{$total_tax}}';
	var total_discamt = '{{$total_discamt}}';

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
							[
								{image: 'letterhead',width:175, height:65, style: 'tableHeader', colSpan: 5, alignment: 'center'},{},{},{},{},

								{text: 'Purchase Order', style: 'tableHeader', colSpan: 6, alignment: 'center'},{},{},{},{},{}
							],

							[
								{
									text: 'Address To:\n{{$supplier->SuppCode}}\n{{$supplier->Name}}\n{{$supplier->Addr1}}\n{{$supplier->Addr2}}\n{{$supplier->Addr3}}\n{{$supplier->Addr4}}', 
									colSpan: 5, 
									rowSpan: 4,
									alignment: 'left'},{},{},{},{},
								{
								 	text: 'Purchase No.',
								 	colSpan: 2, alignment: 'left'},{},
								{
									text: '{{$purordhd->prdept}}'+'{{str_pad($purordhd->purordno, 9, '0', STR_PAD_LEFT)}}',
								 	colSpan: 4, alignment: 'left'},{},{},{}
							],
							
							[
								{},{},{},{},{},
								{
								 	text: 'Purchase Date.',
								 	colSpan: 2, alignment: 'left'},{},
								{
									text: '{{\Carbon\Carbon::createFromFormat('Y-m-d',$purordhd->purdate)->format('d-m-Y')}}',
								 	colSpan: 4, alignment: 'left'},{},{},{}
							],

							[
								{},{},{},{},{},
								{
								 	text: 'Contact Person.',
								 	colSpan: 2, alignment: 'left'},{},
								{
									text: '{{$supplier->ContPers}}',
								 	colSpan: 4, alignment: 'left'},{},{},{}
							],

							[{},{},{},{},{},
							{
							 	text: 'Contact No.',
							 	colSpan: 2, alignment: 'left'},{},
							{
								text: '{{$supplier->TelNo}}',
							 	colSpan: 4, alignment: 'left'},{},{},{}],

							[{text:'No.'},{text:'Description',colSpan: 4},{},{},{},{text:'UOM'},{text:'Quantity'},{text:'Unit Price'},{text:'Tax Amt'},{text:'Discount Amount'},{text:'Nett Amount'}],

							@foreach ($purorddt as $index=>$obj)
							[
								{text:'{{++$index}}'},
								{text:`{{$obj->description}}\n{{$obj->remarks}}`,colSpan: 4},{},{},{},
								{text:'{{$obj->uomcode}}'},
								{text:'{{$obj->qtyorder}}', alignment: 'right'},
								{text:'{{number_format($obj->unitprice,2)}}', alignment: 'right'},
								{text:'{{number_format($obj->tot_gst,2)}}', alignment: 'right'},
								{text:'{{number_format($obj->amtdisc,2)}}', alignment: 'right'},
								{text:'{{number_format($obj->amount,2)}}', alignment: 'right'},
							],
							@endforeach

							[
								{text:'TOTAL', style: 'totalbold', colSpan: 5},{},{},{},{},{},{},{},
								{text:'{{number_format($total_tax,2)}}', alignment: 'right'},
								{text:'{{number_format($total_discamt,2)}}', alignment: 'right'},
								{text:'{{number_format($purordhd->totamount,2)}}', alignment: 'right'}
							],

							[
								{text:'RINGGIT MALAYSIA: \n{{$totamt_bm}}', style: 'totalbold',  italics: true, colSpan: 11,pageBreak: 'before'}
							],
							
							[
								{text:

									`Please Deliver goods/services/works with original purchase order, delivery order and invoice to:\n\nAddress To:\n{{$deldept->description}}\n
										{{$deldept->addr1}}\n
										{{$deldept->addr2}}\n
										{{$deldept->addr3}}\n
										{{$deldept->addr4}}\n
									Contact Person: {{$deldept->contactper}}\n
									Tel No.: {{$deldept->tel}}\n
									Email: {{$deldept->email}}\n
									`
									,colSpan: 6,rowSpan:4},{},{},{},{},{},
								{text:'Delivered By: \n\n\n\n\n\n', style: 'totalbold',colSpan: 3,rowSpan:3},{},{},
								{text:'Approval: \n\n\n\n\n\n', style: 'totalbold',colSpan: 2,rowSpan:3},{}
							],
							
							[
								{},{},{},{},{},{},
								{},{},{},
								{},{}
							],
							
							[
								{},{},{},{},{},{},
								{},{},{},
								{},{}
							],
							
							[
								{},{},{},{},{},{},
								{text:'Sign: \n\n\n\n\n\n   Position:\n\n Date:\n\n', style: 'totalbold',colSpan: 5},{},{},
								{},{}
							]
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
				  url: 'http://msoftweb.test/img/MSLetterHead.jpg',
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