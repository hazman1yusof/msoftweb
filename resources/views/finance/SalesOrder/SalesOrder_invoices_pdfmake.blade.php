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
	$(document).ready(function () {
		var docDefinition = {
			footer: function(currentPage, pageCount) {
				return [
			      { text: currentPage.toString() + ' of ' + pageCount, alignment: 'center' }
			    ]
			},
			pageSize: 'A4',
			content: [
				@foreach ($invno_arr as $keyd => $dbacthdr)
					{
						image: 'letterhead',width:250, style: 'tableHeader', colSpan: 5, alignment: 'center' @if($keyd != 0), pageBreak: 'before' @endif 
					},
					{
						text: '\nINVOICE\n',
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
									{text: '{{\Carbon\Carbon::createFromFormat('Y-m-d',$dbacthdr->entrydate)->format('d-m-Y')}}'},
									{text: 'BILL NO', alignment: 'right'},
									{text: ':'},
									{text: 'INV-{{str_pad($dbacthdr->invno, 6, "0", STR_PAD_LEFT)}}, DO-{{str_pad($dbacthdr->invno, 6, "0", STR_PAD_LEFT)}}'},
									
								],
								[
									{text: 'DEBTOR', alignment: 'right'},
									{text: ':'},
									{text: '{{$dbacthdr->debt_debtcode}}'},
									{text: 'DOCTOR', alignment: 'right'},
									{text: ':'},
									{text: `{!!str_replace('`', '', $dbacthdr->doctorname)!!}`},
								],
								[
									{text: 'NAME', alignment: 'right'},
									{text: ':'},
									{text: '{{$dbacthdr->debt_name}}'},
									{text: 'PATIENT', alignment: 'right'},
									{text: ':'},
									@if(!empty($dbacthdr->mrn))
										{text: `({{$dbacthdr->mrn}}) {!!str_replace('`', '', $dbacthdr->pm_name)!!}`},
									@else
										{text: ''},
									@endif
									
								],
								[
									{text: 'ADDRESS', alignment: 'right'},
									{text: ':'},
									{text: '{{$dbacthdr->cust_address1}}\n{{$dbacthdr->cust_address2}}\n{{$dbacthdr->cust_address3}}\n{{$dbacthdr->cust_address4}}'},
									{text: 'ADDRESS', alignment: 'right'},
									{text: ':'},
									{text: `{!!strtoupper(str_replace('`', '', $dbacthdr->pm_address1))!!}\n{!!strtoupper(str_replace('`', '', $dbacthdr->pm_address2))!!}\n{!!strtoupper(str_replace('`', '', $dbacthdr->pm_address3))!!}\n{{strtoupper(str_replace('`', '', $dbacthdr->pm_postcode))}}`},
								],
								[
									{text: 'CREDIT TERM', alignment: 'right'},
									{text: ':'},
									@if(!empty($dbacthdr->crterm))
										{text: '{{$dbacthdr->crterm}} DAYS'},
									@else
										{text: ''},
									@endif
									{text: 'BILL TYPE', alignment: 'right'},
									{text: ':'},
									@if(!empty($dbacthdr->hdrtype) && !empty($dbacthdr->bt_desc))
										{text: '{{$dbacthdr->hdrtype}} ({{$dbacthdr->bt_desc}})'},
									@else
										{text: ''},
									@endif	
								],
								@if(strlen($dbacthdr->remark) > 3)
								[
									{text: 'REMARK', alignment: 'right'},
									{text: ':'},
									{text: `{!!str_replace('`', '', $dbacthdr->remark)!!}`, colSpan:4},{},{},{}
								],
								@endif

							]
						},
						layout: 'noBorders',
					},
					{
						style: 'tableExample',
						table: {
							widths: [50,30,30,25,50,50,50,50,50,50], //515
							body: [
								[
									{text:'Description',colSpan: 3, style:'totalbold',border: [false, true, false, true]},{},{},
									{text:'UOM', style:'totalbold',border: [false, true, false, true]},
									{text:'Expiry', style:'totalbold',border: [false, true, false, true]},
									{text:'Batchno', style:'totalbold',border: [false, true, false, true]},
									{text:'Quantity', style:'totalbold', alignment: 'right',border: [false, true, false, true]},
									{text:'Unit Price', style:'totalbold', alignment: 'right',border: [false, true, false, true]},
									{text:'Tax Amt', style:'totalbold', alignment: 'right',border: [false, true, false, true]},
									{text:'Amount', style:'totalbold', alignment: 'right',border: [false, true, false, true]},
								],
								@foreach ($dbacthdr_all as $key => $obj)
									@if($obj->invno == $dbacthdr->invno)
										[
											{text:`{{$key + 1}}. {!!$obj->chggroup!!}`,colSpan: 3,border: [false, false, false, false]},{},{},
											{text:`{!!$obj->uom!!}`,border: [false, false, false, false]},
											{text:' ',border: [false, false, false, false]},
											{text:' ',border: [false, false, false, false]},
											{text:'{{$obj->quantity}}', alignment: 'right',border: [false, false, false, false]},
											{text:'{{number_format($obj->unitprice,2)}}', alignment: 'right',border: [false, false, false, false]},
											{text:'{{number_format($obj->taxamt,2)}}', alignment: 'right',border: [false, false, false, false]},
											{text:'{{number_format($obj->amount,2)}}', alignment: 'right',border: [false, false, false, false]},
										],
										[
											{text:`{!!str_replace('`', '', $obj->chgmast_desc)!!}`,colSpan: 10, margin: [0, -5, 0, 0],border: [false, false, false, false]},{},{},{},{},{},{},{},{},{}
										],
									@endif
								@endforeach
							]
						}
					},
					{
						style: 'tableExample',
						table: {
							widths: [50,30,30,25,40,40,38,40,46,40], //515
							body: [
								[
									{text:'TOTAL', style: 'totalbold', colSpan: 8,fontSize: 9},{},{},{},{},{},{},{},
									{text:'{{number_format($dbacthdr->hdr_amount,2)}}', alignment: 'right',colSpan: 2},{}
								],
								[
									{text:'RINGGIT MALAYSIA: {{$dbacthdr->totamt_bm}}', style: 'totalbold',  italics: true, colSpan: 10,fontSize: 9}
								],
								[
									@if(($dbacthdr->deptcode) == 'IMP')
									{text:
										`\nATTENTION:\n\n1. Please quote invoice number when making payments.\n
										2. All cheque/money order should be crossed and payable to UKM MEDICARE SDN BHD/COMPANY ACCOUNT NO: MAYBANK 564137536420.\n
										3. Please ignore this invoice if payment has been made.\n
										4. Please inform us with payment proof for EFT/direct payment.\n`,
										colSpan: 10},{},{},{},{},{},{},{},{},{},
									@else
									{text:
										`ATTENTION:\n\n1. All bank draft/cheques should be crossed and payable to: \n
											\u200B\t\u200B\t\u200B\t\u200B\t{{$company->name}}/COMPANY ACCOUNT NO: MAYBANK 5641 3753 6420.\n
										2. EFT/CDM/ATM payment: ACCOUNT NO: 5641 3753 6420 and fax/email/sms/whatsapp bank slip/transfer note to:\n
										\u200B\t\u200B\t\u200B\t\u200B\tCREDIT CONTROL DEPT: 03-91737346/creditcontrol@ukmsc.com.my/012-9145906.\n
										3. Please ensure to receive/request correct receipt after payment is made.`,
										colSpan: 10},{},{},{},{},{},{},{},{},{},
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
				@endforeach

				
				
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

</script>

<body style="margin: 0px;">

<iframe id="pdfiframe" width="100%" height="100%" src="" frameborder="0" style="width: 99vw;height: 99vh;"></iframe>

</body>
</html>