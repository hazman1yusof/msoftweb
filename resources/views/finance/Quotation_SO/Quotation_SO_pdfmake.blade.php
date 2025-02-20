<!DOCTYPE html>
<html>
<head>
<title>Quotation</title>

</head>

<!-- <script src="https://unpkg.com/@jsreport/browser-client/dist/jsreport.umd.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="mydata.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha512-a9NgEEK7tsCvABL7KqtUTQjl69z7091EVPpw5KxPlZ93T141ffe1woLtbXTX+r2/8TtTvRX/v4zTL2UlMUPgwg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.min.js" integrity="sha512-P0bOMePRS378NwmPDVPU455C/TuxDS+8QwJozdc7PGgN8kLqR4ems0U/3DeJkmiE31749vYWHvBOtR+37qDCZQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</object>

<script>
	
	var salehdr = {
		@foreach($salehdr as $key => $val) 
			'{{$key}}' : `{!!$val!!}`,
		@endforeach 
	};

	var salesum=[
		@foreach($salesum as $key => $bilsm)
		[
			@foreach($bilsm as $key2 => $val)
				{'{{$key2}}' : `{{$val}}`},
			@endforeach
		],
		@endforeach 
	];

	var company = {
		@foreach($company as $key => $val) 
			'{{$key}}' : `{{$val}}`,
		@endforeach 
	};

	var title = {
		@foreach($company as $key => $val) 
			'{{$key}}' : `{{$val}}`,
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
			pageMargins: [30, 30, 20, 70],
			content: [
				{
					image: 'letterhead',width:250, style: 'tableHeader', colSpan: 5, alignment: 'center'
				},
				{
					text: '\n{{$title}}\n',
					style: 'header',
					alignment: 'center'
				},
				{
					style: 'tableExample',
					table: {
						headerRows: 1,
						widths: [60, 3, '*', 60, 3, '*'], //panjang standard dia 515
						body: [
							[
								{text: 'BILL DATE', alignment: 'right'},
								{text: ':'},
								{text: '{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$salehdr->entrydate)->format('d-m-Y')}}'},
								{text: 'QUOTE NO', alignment: 'right'},
								{text: ':'},
								{text: 'QUOTE-{{str_pad($salehdr->quoteno, 6, "0", STR_PAD_LEFT)}}'},
								
							],
							[
								{text: 'DEBTOR', alignment: 'right'},
								{text: ':'},
								{text: '{{$salehdr->debtorcode}}'},
								{text: 'DOCTOR', alignment: 'right'},
								{text: ':'},
								{text: `{!!$salehdr->doctorname!!}`},
							],
							[
								{text: 'NAME', alignment: 'right'},
								{text: ':'},
								{text: '{{$salehdr->debtorcode_desc}}'},
								{text: 'PATIENT', alignment: 'right'},
								{text: ':'},
								@if(!empty($salehdr->mrn))
									{text: `({{$salehdr->mrn}}) {!!$salehdr->pm_name!!}`},
								@else
									{text: ''},
								@endif
								
							],
							[
								{text: 'ADDRESS', alignment: 'right'},
								{text: ':'},
								{text: `{!!strtoupper($salehdr->address1)!!}\n{!!strtoupper($salehdr->address2)!!}\n{!!strtoupper($salehdr->address3)!!}\n{{strtoupper($salehdr->address4)}}`},
								{text: 'ADDRESS', alignment: 'right'},
								{text: ':'},
								{text: `{!!strtoupper($salehdr->pm_address1)!!}\n{!!strtoupper($salehdr->pm_address2)!!}\n{!!strtoupper($salehdr->pm_address3)!!}\n{{strtoupper($salehdr->pm_postcode)}}`},
							],
							[
								{text: 'CREDIT TERM', alignment: 'right'},
								{text: ':'},
								@if(!empty($salehdr->termvalue))
									{text: '{{$salehdr->termvalue}} DAYS'},
								@else
									{text: ''},
								@endif
								{text: 'BILL TYPE', alignment: 'right'},
								{text: ':'},
								@if(!empty($salehdr->hdrtype) && !empty($salehdr->billtype_desc))
									{text: '{{$salehdr->hdrtype}} ({{$salehdr->billtype_desc}})'},
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
						widths: [143,38,40,40,48,40,40,40], //515
						body: [
							[
								{text:'Description',colSpan: 3, style:'totalbold'},{},{},
								{text:'UOM', style:'totalbold'},
								{text:'Quantity', style:'totalbold', alignment: 'right'},
								{text:'Qty Deliver', style:'totalbold',alignment: 'right'},
								// {text:'Qty On Hand', style:'totalbold',alignment: 'right'},
								{text:'Unit Price', style:'totalbold', alignment: 'right'},
								// {text:'Tax Amt', style:'totalbold', alignment: 'right'},
								{text:'Amount', style:'totalbold', alignment: 'right'},
							],
							@foreach ($salesum as $obj)
							[
								{text:`{!!$obj->chggroup!!} - {!!$obj->chgmast_desc!!}`,colSpan: 3},{},{},
								{text:`{!!$obj->uom_desc!!}`},
								{text:'{{$obj->quantity}}', alignment: 'right'},
								{text:'{{$obj->qtydelivered}}', alignment: 'right'},
								// {text:'{{$obj->qtyonhand}}', alignment: 'right'},
								{text:'{{number_format($obj->unitprice,2)}}', alignment: 'right'},
								// {text:'{{number_format($obj->taxamt,2)}}', alignment: 'right'},
								{text:'{{number_format($obj->totamount,2)}}', alignment: 'right'},
							],
							@endforeach
							[
								{text:'TOTAL', style: 'totalbold', colSpan: 7},{},{},{},{},{},{},
								{text:'{{number_format($salehdr->amount,2)}}', alignment: 'right'}
							],
							[
								{text:'RINGGIT MALAYSIA: {{$totamt_bm}}', style: 'totalbold',  italics: true, colSpan: 8}
							],
							[
								@if(($salehdr->deptcode) == 'IMP')
								{text:
									`ATTENTION:\n\n1. All bank draft/cheques should be crossed and payable to: {{$company->name}}/COMPANY ACCOUNT NO: MAYBANK 564137536420.\n
									2. Please ensure to receive/request correct receipt after payment is made.\n
									3. For further enquiries: please do not hesitate to contact: MILA/ARIF, Office Telephone No./Fax: 03-91735642, HP No. 019-2304606.\n
									KINDLY ENSURE THAT ALL PAYMENT IS DONE AT:\n
									\u200B\tMED-UKM
									\u200B\t(Dimiliki oleh UKM MEDICARE SDN. BHD.)
									\u200B\tAras Bawah (G) Lobi Pelawat, Pusat Perubatan UKM
									\u200B\tJalan Yaacob Latiff, 56000 Cheras Kuala Lumpur
									\u200B\tTel / Fax 03-9173 9357    Mobile 019-228 9357\n
									
									PLEASE ASK FOR RECEIPT\n`,
									colSpan: 8},{},{},{},{},{},{},{},
								@else
								{text:
									`ATTENTION:\n\n1. All bank draft/cheques should be crossed and payable to: \n
										\u200B\t\u200B\t\u200B\t\u200B\t{{$company->name}}/COMPANY ACCOUNT NO: MAYBANK 564137536420.\n
									2. EFT/CDM/ATM payment: ACCOUNT NO: 564137536420 and fax/email/sms/whatsapp bank slip/transfer note to:\n
									\u200B\t\u200B\t\u200B\t\u200B\tCREDIT CONTROL DEPT: 03-91737346/creditcontrol@ukmsc.com.my/012-9145906.\n
									3. Please ensure to receive/request correct receipt after payment is made.`,
									colSpan: 8},{},{},{},{},{},{},{},
								@endif
								
							],
						]
					},
					layout: 'lightHorizontalLines',
				},
				{
                    text: '\nTHIS IS COMPUTER GENERATED DOCUMENT. NO SIGNATURE IS REQUIRED.', fontSize: 10, alignment: 'center'
                },
				{
					text: 'Date printed: {{\Carbon\Carbon::now("Asia/Kuala_Lumpur")->format('d/m/Y')}} {{\Carbon\Carbon::now("Asia/Kuala_Lumpur")->format('H:i')}} by {{session('username')}}', fontSize: 7, alignment: 'center'
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
				letterhead: {
					url: "{{asset('/img/letterheadukm.png')}}",
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