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
<script src="https://cdn.jsdelivr.net/npm/numeral@2.0.6/numeral.min.js"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fomantic-ui@2.9.2/dist/semantic.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/fomantic-ui@2.9.2/dist/semantic.min.js"></script>
</object>

<script>
	var merge_key = makeid(20);
	var base64_pr = null;
	var session_deptcode = "{{Session::get('deptcode')}}";

	var attachmentfiles = [
		@foreach($attachment_files as $file)
		{	
			idno:'{{$file->idno}}',
			src:'{{$file->attachmentfile}}',
		},
		@endforeach
	]

	$(document).ready(function () {
		var docDefinition = {
			header: function(currentPage, pageCount, pageSize) {
				var retval=[];
				var header_tbl = {
	                    style: 'header_tbl',
	                    table: {
	                        headerRows: 1,
	                        widths: [60,290,101,190],//panjang standard dia 515
	                        body: [
	                            [
									{text: 'SUPPLIER',bold: true}, 
									{text: `: {!!str_replace('`', '', $supplier->Name)!!}`},
									{text: 'PURCHASE ORDER NO',bold: true}, 
									{text: ': {{$purordhd->prdept}}-{{str_pad($purordhd->purordno, 5, '0', STR_PAD_LEFT)}}'},
								],[
									{text: ''}, 
									{text: `: {!!str_replace('`', '', $supplier->Addr1)!!}`},
									{text: 'PURCHASE ORDER DATE',bold: true}, 
									{text: ': {{\Carbon\Carbon::createFromFormat('Y-m-d',$purordhd->purdate)->format('d-m-Y')}}'},
								],[
									{text: ''}, 
									{text: `: {!!str_replace('`', '', $supplier->Addr2)!!}`},
									{text: 'PAGE',bold: true}, 
									{text: ': '+currentPage+' / '+pageCount},
								],[
									{text: ''}, 
									{text: `: {!!str_replace('`', '', $supplier->Addr3)!!}`},
									{text: ''}, 
									{text: ''},
								],
								[
									{text:'TEL NO',bold: true},
									{text: ': {{$supplier->TelNo}}'},
									@if(!empty($purordhd->purreqno))
									{text: 'PR Document',bold: true},
									{text: ': {{$purordhd->reqdept}}-{{str_pad($purordhd->purreqno, 5, '0', STR_PAD_LEFT)}}'}
									@else
									{},{}
									@endif
								],
								// [{text:'FAX NO',bold: true},{text: ': {{$supplier->Faxno}}'},{},{}],
	                        ]
	                    },
				        layout: 'noBorders',
			        }

		        var header_tbl_deldept = {
                    style: 'header_tbl',
                    table: {
                        headerRows: 1,
                        widths: [60,290,110,70],//panjang standard dia 515
                        body: [
                            [
								{text: 'DELIVERY TO',bold: true}, 
								{text: `: {!!$deldept->addr1!!}`},
								{}, 
								{},
							],[
								{text: ''}, 
								{text: `: {!!$deldept->addr2!!}`},
								{}, 
								{},
							],[
								{text: ''}, 
								{text: `: {!!$deldept->addr3!!}`},
								{}, 
								{},
							],[
								{text: ''}, 
								{text: `: {!!$deldept->addr4!!}`},
								{text: ''}, 
								{text: ''},
							],
							// [{},{},{},{}],
							[{text:'TEL NO',bold: true},{text: ': {{$deldept->tel}}'},{},{}],
							// [{text:'FAX NO',bold: true},{text: ': {{$deldept->fax}}'},{},{}],
                        ]
                    },
			        layout: 'noBorders',
		        }

		        var header_cancel = {
                    style: 'header_tbl',
                    table: {
                        headerRows: 1,
                        widths: [80,200,110,70],//panjang standard dia 515
                        body: [
                            [
								{text: 'CANCEL REMARK',bold: true, color:'darkred'}, 
								{text: `: {!!$purordhd->cancelled_remark!!}`,colSpan: 2, color:'darkred'},
								{}, 
								{},
							],[
								{text: 'CANCEL BY',bold: true, color:'darkred'}, 
								{text: `: {!!$purordhd->cancelby!!}`, color:'darkred'},
								{}, 
								{},
							],
                        ]
                    },
			        layout: 'noBorders',
		        }

				// var addr1_unit = {text: `{!!$deldept->addr2!!}`,fontSize:8,alignment: 'center',margin: [0, 1, 0, 1]};
				// var addr2_unit = {text: `{!!$deldept->addr3!!}`,fontSize:8,alignment: 'center',margin: [0, 1, 0, 1]};
				// var addr3_unit = {text: `{!!$deldept->addr4!!}`,fontSize:8,alignment: 'center',margin: [0, 1, 0, 1]};
		        var header_tbl_bangi ={
					columns: [
					    {image: 'logohdr',style:'header_img',width:200,alignment: 'left'},
					    {
							width: '*',alignment: 'right',
							text: `{!!$company->address1!!} \n {!!$company->address2!!} \n {!!$company->address3!!} \n {!!$company->address4!!}`,
							fontSize:9,margin: [0, 10, 30, 0]
						},
					]
				}

				if(currentPage == 1){
					// var logohdr = {image: 'logohdr',style:'header_img',width:180, colSpan: 5, alignment: 'center'};
					var title = {text: 'PURCHASE ORDER',fontSize:12,alignment: 'center',bold: true, margin: [0, 5, 0, 5]};
					retval.push(header_tbl_bangi);
					// retval.push(addr1_unit);
					// retval.push(addr2_unit);
					// retval.push(addr3_unit);
					retval.push(title);
				}else{
					var title = {text: 'PURCHASE ORDER',fontSize:12,alignment: 'center',bold: true, margin: [0, 71, 0, 0]};
					// retval.push(addr1_unit);
					// retval.push(addr2_unit);
					// retval.push(addr3_unit);
					retval.push(title);
				}

				retval.push(header_tbl);
				@if($purordhd->recstatus == 'CANCELLED')
				retval.push(header_cancel);
				@else
				retval.push(header_tbl_deldept);
				@endif
				return retval

			},
			footer: function(currentPage, pageCount) {
				return [
					{
						text: 'This is a computer-generated document. No signature is required.', alignment: 'center', fontSize: 10
					},
				]
			},
			pageSize: 'A4',
			pageMargins: [30, 225, 20, 70],
			content: [
				{
					style: 'body_tbl',
					table: {
						headerRows: 1,
						dontBreakRows: true,
						widths: [20,80,60,50,60,60,30,50,60],//panjang standard dia 515
						body: [
							[
								{text:'Line No.',bold: true, style: 'body_ttl',border: [false, true, false, true]},
								{text:'Item',bold: true, style: 'body_ttl',border: [false, true, false, true]},
								{text:'Qty',bold: true, style: 'body_ttl',border: [false, true, false, true]},
								{text:'Packing',bold: true, style: 'body_ttl',border: [false, true, false, true]},
								{text:'Unit Price',bold: true, style: 'body_ttl',alignment: 'right',border: [false, true, false, true]},
								{text:'Amount',bold: true, style: 'body_ttl',alignment: 'right',border: [false, true, false, true]},
								{text:'Tax Code',bold: true, style: 'body_ttl',border: [false, true, false, true]},
								{text:'Tax Amount',bold: true, style: 'body_ttl',alignment: 'right',border: [false, true, false, true]},
								{text:'Net Amount\n(RM)',bold: true, style: 'body_ttl',alignment: 'right',border: [false, true, false, true]}
							],
							@foreach ($purorddt as $index=>$dtl)
							[
								{text:'{{$index + 1}}', style: 'body_row', border: [false, false, false, false]},
								{text:'{{$dtl->itemcode}}', style: 'body_row', border: [false, false, false, false]},
								{text:'{{$dtl->qtyorder}}', style: 'body_row', border: [false, false, false, false]},
								{text:`{!!str_replace('`', '', $dtl->uom_desc)!!}`, style: 'body_row', border: [false, false, false, false]},
								{text:'{{number_format($dtl->unitprice,2)}}', alignment: 'right', style: 'body_row', border: [false, false, false, false]},
								{text:'{{number_format($dtl->amount,2)}}', alignment: 'right', style: 'body_row', border: [false, false, false, false]},
								{text:'{{$dtl->taxcode}}', style: 'body_row',border: [false, false, false, false]},
								{text:'{{number_format($dtl->tot_gst,2)}}', alignment: 'right', style: 'body_row', border: [false, false, false, false]},
								{text:'{{number_format($dtl->totamount,2)}}', alignment: 'right', style: 'body_row', border: [false, false, false, false]},
							],
							[
								{text:'',style: 'body_row', border: [false, false, false, false]},
								{colSpan:6,text:`{!!str_replace('`', '', $dtl->description)!!}`, border: [false, false, false, false], margin: [0, -5, 0, 0]},
								{text:'',style: 'body_row', border: [false, false, false, false]},
								{text:'',style: 'body_row', border: [false, false, false, false]},
								{text:'',style: 'body_row', border: [false, false, false, false]},
								{text:'',style: 'body_row', border: [false, false, false, false]},
								{text:'',style: 'body_row', border: [false, false, false, false]},
								{text:'',style: 'body_row', border: [false, false, false, false]},
								{text:'',style: 'body_row', border: [false, false, false, false]}
					    	],
					    	[
								{text:'',style: 'body_row', border: [false, false, false, false]},
								{colSpan:6,text:`{!!str_replace('`', '', $dtl->remarks)!!}`, border: [false, false, false, false]},
								{text:'',style: 'body_row', border: [false, false, false, false]},
								{text:'',style: 'body_row', border: [false, false, false, false]},
								{text:'',style: 'body_row', border: [false, false, false, false]},
								{text:'',style: 'body_row', border: [false, false, false, false]},
								{text:'',style: 'body_row', border: [false, false, false, false]},
								{text:'',style: 'body_row', border: [false, false, false, false]},
								{text:'',style: 'body_row', border: [false, false, false, false]}
					    	],
							@endforeach

                        ]
                    }
		        },
		        {
                    style: 'body_total',
                    table: {
                        widths: [66,'*'],//panjang standard dia 515
                    	dontBreakRows: true,
                        body: [
                        	[
								{text: 'SUBTOTAL :',bold: true,alignment: 'left', border: [false, false, false, false]}, 
								{text: numeral({{$purordhd->subamount}}).format('0,0.00'),alignment: 'right', border: [false, false, false, false]},
                        	],
                        	[
								{text: 'DISCOUNT :',bold: true,alignment: 'left', border: [false, false, false, false]}, 
								{text: numeral({{$purordhd->amtdisc}}).format('0,0.00'),alignment: 'right', border: [false, false, false, false]},
                        	],
                        	[
								{text: 'NETT TOTAL : ',bold: true,alignment: 'left', border: [false, true, false, true]}, 
								{text: numeral({{$purordhd->subamount - $purordhd->amtdisc}}).format('0,0.00'),alignment: 'right', border: [false, true, false, true]},
                        	]
                        ]
                    }
		        },
		        {text:'Purchase Order Remarks :',alignment: 'left',fontSize:9, margin: [0, 30, 0, 0]},
		        {text:`{!!str_replace('`', '', $purordhd->remarks)!!}`,alignment: 'left',fontSize:9},
		        // {text:'1. Goods which are not comply to our specification and as per our Purchase Order will be rejected',alignment: 'left',fontSize:9, margin: [0, 20, 0, 0]},
		        // {text:'2. In order to ensure prompt payment, all Delivery Order, Invoices and other documents ',alignment: 'left',fontSize:9},,
		        // {text:'3. Any goods supplied without our Purchase Order will not be entertained.',alignment: 'left',fontSize:9},
		        {	margin: [0, 20, 0, 0],
					fontSize:9,
					ol: [
						'Goods which are not comply to our specification and as per our Purchase Order will be rejected at your own cost.',
						'In order to ensure prompt payment, all Delivery Order, Invoices and other documents related to this order must bear this Purchase Order Number.',
						'Any goods supplied without our Purchase Order will not be entertained.'
					]
				},
				{
					style: 'body_sign',
					table: {
						widths: ['*','*','*'],//panjang standard dia 515
						dontBreakRows: true,
						body: [
							@if($purordhd->prdept == 'IMP')
							[
								{text: 'Prepared By\n\n\n{{$purordhd->requestby_name}}',bold: true,alignment: 'center'},
								{text: 'Verified By\n\n\n{{$purordhd->verifiedby_name}}',bold: true,alignment: 'center'},
								{text: 'Approved By\n\n\n{{$purordhd->approvedby_name}}',bold: true,alignment: 'center'},
							],
							@else
							[
								{text: 'Prepared By\n\n\n{{$purordhd->requestby_name}}',bold: true,alignment: 'center'},
								{text: 'Verified By\n\n\n{{$purordhd->verifiedby_name}}',bold: true,alignment: 'center'},
								{text: 'Approved By\n\n\n{{$purordhd->approvedby_name}}',bold: true,alignment: 'center'},
							],
							@endif
							// [
							// 	{text: 'Request By\n\n\n\n______________________\n({{$purordhd->requestby_name}})',bold: true,alignment: 'center'},
							// 	{text: 'Support By\n\n\n\n______________________\n({{$purordhd->supportby_name}})',bold: true,alignment: 'center'},
							// 	{text: 'Verified By\n\n\n\n______________________\n({{$purordhd->verifiedby_name}})',bold: true,alignment: 'center'},
							// 	{text: 'Approved By\n\n\n\n______________________\n({{$purordhd->approvedby_name}})',bold: true,alignment: 'center'},
							// ],
							// [
							// 	{text: '______________________',alignment: 'center', margin: [0, 30, 0, 0]},
							// 	{text: '______________________',alignment: 'center', margin: [0, 30, 0, 0]},
							// 	{text: '______________________',alignment: 'center', margin: [0, 30, 0, 0]},
							// 	{text: '______________________',alignment: 'center', margin: [0, 30, 0, 0]},
							// ],
						]
					},
					layout: 'noBorders',
				},

			],
			styles: {
				header_img: {
					margin: [30, 10, 0, 0]
				},
				header_tbl: {
					fontSize: 9,
					margin: [30, 0, 40, 0]
				},
				body_tbl: {
					fontSize: 9,
					margin: [0, 0, 0, 0]
				},
				body_row: {
					margin: [0, 3, 0, 3]
				},
				body_ttl: {
					margin: [0, 2, 0, 2]
				},
				body_name: {
					fontSize: 9,
					margin: [0, 25, 0, 5]
				},
				body_total: {
					fontSize: 9,
					margin: [195, 25, 0, 5]
				},
				body_sign: {
					fontSize: 9,
					margin: [0, 20, 0, 0]
				},

			},
			images: {
				logohdr: "{{asset('/img/letterheadukm.png')}}",
			}
		};

		pdfMake.createPdf(docDefinition).getDataUrl(function(dataURL) {
			$('#pdfiframe').attr('src',dataURL);
		});

		pdfMake.createPdf(docDefinition).getBase64(function(dataURL) {
			base64_pr = dataURL;

			var obj = {
				base64:dataURL,
				_token:$('#_token').val(),
				merge_key:merge_key,
				lineno_:1
			};

			$.post( '../attachment_upload/form?page=merge_pdf',$.param(obj) , function( data ) {
			}).done(function(data) {
			});
		});

		@if(is_object($SO_obj))
			var docDefinition_so = {
				footer: function(currentPage, pageCount) {
					return [
				      { text: currentPage.toString() + ' of ' + pageCount, alignment: 'center' }
				    ]
				},
				pageSize: 'A4',
				content: [
					{
						image: 'letterhead',width:250, style: 'tableHeader', colSpan: 5, alignment: 'center'
					},
					{
						text: '\n{{$SO_obj->title}}\n',
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
									{text: '{{\Carbon\Carbon::createFromFormat('Y-m-d',$SO_obj->dbacthdr->entrydate)->format('d-m-Y')}}'},
									{text: 'BILL NO', alignment: 'right'},
									{text: ':'},
									{text: 'INV-{{str_pad($SO_obj->dbacthdr->invno, 6, "0", STR_PAD_LEFT)}}, DO-{{str_pad($SO_obj->dbacthdr->invno, 6, "0", STR_PAD_LEFT)}}'},
									
								],
								[
									{text: 'DEBTOR', alignment: 'right'},
									{text: ':'},
									{text: '{{$SO_obj->dbacthdr->debt_debtcode}}'},
									{text: 'DOCTOR', alignment: 'right'},
									{text: ':'},
									{text: `{!!$SO_obj->dbacthdr->doctorname!!}`},
								],
								[
									{text: 'NAME', alignment: 'right'},
									{text: ':'},
									{text: '{!!str_replace('`', '',$SO_obj->dbacthdr->debt_name)!!}'},
									{text: 'PATIENT', alignment: 'right'},
									{text: ':'},
									@if(!empty($SO_obj->dbacthdr->mrn))
										{text: `({{$SO_obj->dbacthdr->mrn}}) {!!str_replace('`', '',$SO_obj->dbacthdr->pm_name)!!}`},
									@else
										{text: ''},
									@endif
									
								],
								[
									{text: 'ADDRESS', alignment: 'right'},
									{text: ':'},
									{text: '{{$SO_obj->dbacthdr->cust_address1}}\n{{$SO_obj->dbacthdr->cust_address2}}\n{{$SO_obj->dbacthdr->cust_address3}}\n{{$SO_obj->dbacthdr->cust_address4}}'},
									{text: 'ADDRESS', alignment: 'right'},
									{text: ':'},
									{text: `{!!strtoupper(str_replace('`', '', $SO_obj->dbacthdr->pm_address1))!!}\n{!!strtoupper(str_replace('`', '', $SO_obj->dbacthdr->pm_address2))!!}\n{!!strtoupper(str_replace('`', '', $SO_obj->dbacthdr->pm_address3))!!}\n{{strtoupper(str_replace('`', '', $SO_obj->dbacthdr->pm_postcode))}}`},
								],
								[
									{text: 'CREDIT TERM', alignment: 'right'},
									{text: ':'},
									@if(!empty($dbacthdr->crterm))
										{text: '{{$SO_obj->dbacthdr->crterm}} DAYS'},
									@else
										{text: ''},
									@endif
									{text: 'BILL TYPE', alignment: 'right'},
									{text: ':'},
									@if(!empty($dbacthdr->hdrtype) && !empty($dbacthdr->bt_desc))
										{text: '{{$SO_obj->dbacthdr->hdrtype}} ({{$SO_obj->dbacthdr->bt_desc}})'},
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
								@foreach ($SO_obj->billsum as $key => $obj)
								[
									{text:`{{$key + 1}}. {!!$obj->chggroup!!}`,colSpan: 3,border: [false, false, false, false]},{},{},
									{text:`{!!$obj->uom!!}`,border: [false, false, false, false]},
									{text:'{{\Carbon\Carbon::parse($obj->expdate)->format('d/m/Y')}}',border: [false, false, false, false]},
									{text:`{!!$obj->batchno!!}`,border: [false, false, false, false]},
									{text:'{{$obj->quantity}}', alignment: 'right',border: [false, false, false, false]},
									{text:'{{number_format($obj->unitprice,2)}}', alignment: 'right',border: [false, false, false, false]},
									{text:'{{number_format($obj->taxamt,2)}}', alignment: 'right',border: [false, false, false, false]},
									{text:'{{number_format($obj->amount,2)}}', alignment: 'right',border: [false, false, false, false]},
								],
								[
									{text:`{!!$obj->chgmast_desc!!}`,colSpan: 10, margin: [0, -5, 0, 0],border: [false, false, false, false]},{},{},{},{},{},{},{},{},{}
								],
								@endforeach
							]
						}
					},
					{
						style: 'tableExample',
						table: {
							widths: [50,30,30,25,48,40,38,40,46,40], //515
							body: [
								[
									{text:'TOTAL', style: 'totalbold', colSpan: 9},{},{},{},{},{},{},{},{},
									{text:'{{number_format($SO_obj->dbacthdr->amount,2)}}', alignment: 'right'}
								],
								[
									{text:'RINGGIT MALAYSIA: {{$SO_obj->totamt_bm}}', style: 'totalbold',  italics: true, colSpan: 10}
								],
								[
									@if(($SO_obj->dbacthdr->deptcode) == 'IMP')
									{text:
										`ATTENTION:\n\n1. Please quote invoice number when making payments.\n
										2. All cheque/money order should be crossed and payable to UKM MEDICARE SDN BHD/COMPANY ACCOUNT NO: MAYBANK 564137536420.\n
										3. Please ignore this invoice if payment has been made.\n
										4. Please inform us with payment proof for EFT/direct payment.\n`,
										colSpan: 10},{},{},{},{},{},{},{},{},{},
									@else
									{text:
										`ATTENTION:\n\n1. All bank draft/cheques should be crossed and payable to: \n
											\u200B\t\u200B\t\u200B\t\u200B\t{{$SO_obj->company->name}}/COMPANY ACCOUNT NO: MAYBANK 5641 3753 6420.\n
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

			pdfMake.createPdf(docDefinition_so).getDataUrl(function(dataURL_so) {
				$('#pdfiframe_so').attr('src',dataURL_so);
			});

			pdfMake.createPdf(docDefinition_so).getBase64(function(dataURL_so) {
				var obj = {
					base64:dataURL_so,
					_token:$('#_token').val(),
					merge_key:merge_key,
					lineno_:2
				};

				$.post( '../attachment_upload/form?page=merge_pdf',$.param(obj) , function( data ) {
				}).done(function(data){
				});
			});
			
		@endif
	});

	$(document).ready(function () {
		$('div.canclick').click(function(){
			$('div.canclick').removeClass('teal inverted');
			$(this).addClass('teal inverted');
			var goto = $(this).data('goto');

			if($(goto).offset() != undefined){
			$('html, body').animate({
				scrollTop: $(goto).offset().top
				}, 500, function(){

				});
			}
		});

		$('#merge_btn').click(function(){
			let attach_array = [];
			$('input:checkbox:checked').each(function(){
				attach_array.push($(this).data('src'));
			});

			if(attach_array.length > 0 ){
				var obj = {
					page:'merge_pdf_with_attachment',
					merge_key:merge_key,
					attach_array:attach_array
				};

				$('#pdfiframe_merge').attr('src',"../attachment_upload/table?"+$.param(obj));
				$('#btn_merge,#pdfiframe_merge').show();
				$('#btn_merge').click();
			}else{
				alert('Select at least 1 PDF Attachment to merge with main PDF');
			}
		});

		populate_attachmentfile();

		$('#ref_dropdown.ui.dropdown')
		  .dropdown({
		  	onChange: function(value, text, $selectedItem) {
		      window.open(value);
		    }
		  });
	});

	function makeid(length) {
	    let result = '';
	    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	    const charactersLength = characters.length;
	    let counter = 0;
	    while (counter < length) {
	      result += characters.charAt(Math.floor(Math.random() * charactersLength));
	      counter += 1;
	    }
	    return result;
	}

	function populate_attachmentfile(){
		attachmentfiles.forEach(function(e,i){
			$('#pdfiframe_'+e.idno).attr('src',"../uploads/"+e.src);
		});
	}

	function make_body(){
		var retval = [
	        [
				{text:'Line No.',bold: true, style: 'body_ttl',border: [false, true, false, true]},
				{text:'Item',bold: true, style: 'body_ttl',border: [false, true, false, true]},
				{text:'Qty',bold: true, style: 'body_ttl',border: [false, true, false, true]},
				{text:'Packing',bold: true, style: 'body_ttl',border: [false, true, false, true]},
				{text:'Unit Price',bold: true, style: 'body_ttl',alignment: 'right',border: [false, true, false, true]},
				{text:'Amount',bold: true, style: 'body_ttl',alignment: 'right',border: [false, true, false, true]},
				{text:'Tax Code',bold: true, style: 'body_ttl',border: [false, true, false, true]},
				{text:'Tax Amount',bold: true, style: 'body_ttl',alignment: 'right',border: [false, true, false, true]},
				{text:'Net Amount\n(RM)',bold: true, style: 'body_ttl',alignment: 'right',border: [false, true, false, true]}
			],
	    ];

	    ini_body.forEach(function(e,i){
	    	let arr = [
				{text:e.lineno, style: 'body_row', border: [false, false, false, false]},
				{text:e.itemcode, style: 'body_row', border: [false, false, false, false]},
				{text:e.qtyorder, style: 'body_row', border: [false, false, false, false]},
				{text:e.uomdesc, style: 'body_row', border: [false, false, false, false]},
				{text:e.unitprice,alignment: 'right', style: 'body_row', border: [false, false, false, false]},
				{text:e.amount,alignment: 'right', style: 'body_row', border: [false, false, false, false]},
				{text:e.taxcode, style: 'body_row',border: [false, false, false, false]},
				{text:e.gstamt,alignment: 'right', style: 'body_row', border: [false, false, false, false]},
				{text:e.netamt,alignment: 'right', style: 'body_row', border: [false, false, false, false]},
	    	];
	    	retval.push(arr);
	    	let arr2 = [
				{text:'',style: 'body_row', border: [false, false, false, false]},
				{colSpan:6,text:e.description, border: [false, false, false, false]},
				{text:'',style: 'body_row', border: [false, false, false, false]},
				{text:'',style: 'body_row', border: [false, false, false, false]},
				{text:'',style: 'body_row', border: [false, false, false, false]},
				{text:'',style: 'body_row', border: [false, false, false, false]},
				{text:'',style: 'body_row', border: [false, false, false, false]},
				{text:'',style: 'body_row', border: [false, false, false, false]},
				{text:'',style: 'body_row', border: [false, false, false, false]}
	    	];
	    	let arr3 = [
				{text:'',style: 'body_row', border: [false, false, false, false]},
				{colSpan:6,text:e.remark, border: [false, false, false, false]},
				{text:'',style: 'body_row', border: [false, false, false, false]},
				{text:'',style: 'body_row', border: [false, false, false, false]},
				{text:'',style: 'body_row', border: [false, false, false, false]},
				{text:'',style: 'body_row', border: [false, false, false, false]},
				{text:'',style: 'body_row', border: [false, false, false, false]},
				{text:'',style: 'body_row', border: [false, false, false, false]},
				{text:'',style: 'body_row', border: [false, false, false, false]}
	    	];
	    	retval.push(arr2);
	    	retval.push(arr3);
	    	subtotal=subtotal+parseFloat(e.amount.replace(",",""));
	    	disc=disc+parseFloat(e.amtdisc.replace(",",""));
	    	nettotal=nettotal+parseFloat(e.netamt.replace(",",""));
	    });

	    return retval;
	}

</script>

<body style="margin: 0px;">

<input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">
<div class="ui segments" style="width: 18vw;height: 95vh;float: left; margin: 10px; position: fixed;">
  <div class="ui secondary segment">
    <h3>
	  <b>Navigation</b>
	  <button id="merge_btn" class="ui small primary button" style="font-size: 12px;padding: 6px 10px;float: right;">Merge</button>
	</h3>
	@if(!empty($print_connection->purreqhd) || !empty($print_connection->delordhd))
	<div class="ui dropdown" id="ref_dropdown">
	  <div class="text">Document Reference</div>
	  <i class="dropdown icon"></i>
	  <div class="menu">
	  	@if(!empty($print_connection->purreqhd))
	    <div class="item" data-value="../purchaseRequest/showpdf?recno={{$print_connection->purreqhd->recno}}">Purchase Request</div>
	  	@endif

	  	@if(!empty($print_connection->delordhd))
	    <div class="item" data-value="../deliveryOrder/showpdf?recno={{$print_connection->delordhd->recno}}">Delivery Order</div>
	  	@endif
	  </div>
	</div>
	@endif
  </div>
  <div class="ui segment teal inverted canclick" style="cursor: pointer;" data-goto='#pdfiframe'>
    <p>Purchase Order </p>
  </div>
  @if(is_object($SO_obj))
  <div class="ui segment canclick" style="cursor: pointer;" data-goto='#pdfiframe_so'>
    <p>Sales Order / Invoice <input type="checkbox" data-lineno="2" style="float: right;margin-right: 5px;"></p>
  </div>
  @endif

  @foreach($attachment_files as $file)
  <div class="ui segment canclick" style="cursor: pointer;" data-goto='#pdfiframe_{{$file->idno}}'>
    <p>{{$file->resulttext}} <input type="checkbox" data-src="{{$file->attachmentfile}}" name="{{$file->idno}}" style="float: right;margin-right: 5px;"></p>
  </div>
  @endforeach

  <div id="btn_merge" class="ui segment canclick" style="cursor: pointer;display: none;" data-goto='#pdfiframe_merge'>
    <p>Merged File</p>
  </div>
</div>

<iframe id="pdfiframe" width="100%" height="100%" src="" frameborder="0" style="width: 79vw;height: 100vh;float: right;"></iframe>

@if(is_object($SO_obj))
<iframe id="pdfiframe_so" width="100%" height="100%" src="" frameborder="0" style="width: 79vw;height: 100vh;float: right;"></iframe>
@endif

@foreach($attachment_files as $file)
<iframe id="pdfiframe_{{$file->idno}}" width="100%" height="100%" src="" frameborder="0" style="width: 79vw;height: 100vh;float: right;"></iframe>
@endforeach
<iframe id="pdfiframe_merge" width="100%" height="100%" src="" frameborder="0" style="width: 79vw;height: 100vh;float: right;display: none;"></iframe>

</body>
</html>