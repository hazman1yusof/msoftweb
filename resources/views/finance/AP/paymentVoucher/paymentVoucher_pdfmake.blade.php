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
<script src="https://cdn.jsdelivr.net/npm/numeral@2.0.6/numeral.min.js"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fomantic-ui@2.9.2/dist/semantic.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/fomantic-ui@2.9.2/dist/semantic.min.js"></script>
</object>

<script>
	var merge_done = [false,false];
	var merge_key = makeid(20); 
	var ini_header={
		pvno:`{{str_pad($apacthdr->pvno, 7, '0', STR_PAD_LEFT)}}`,
		pvdate:`{{\Carbon\Carbon::parse($apacthdr->actdate)->format('d/m/Y')}}`,
		cred_code:`{{$apacthdr->suppcode}}`,
		pname:`{{strtoupper($apacthdr->suppname)}}`,
		padd1:`{{strtoupper($apacthdr->addr1)}}`,
		padd2:`{{strtoupper($apacthdr->addr2)}}`,
		padd3:`{{strtoupper($apacthdr->addr3)}}`,
		ptelno:`{{$apacthdr->telno}}`,
		desc:``,
		remarks:`{{strtoupper($apacthdr->remarks)}}`,
		requestby:`{{$apacthdr->requestby}}`,
		supportby:`{{$apacthdr->supportby}}`,
		verifiedby:`{{$apacthdr->verifiedby}}`,
		approvedby:`{{$apacthdr->approvedby}}`,

		requestby_name:`{{$apacthdr->requestby_name}}`,
		supportby_name:`{{$apacthdr->supportby_name}}`,
		verifiedby_name:`{{$apacthdr->verifiedby_name}}`,
		approvedby_name:`{{$apacthdr->approvedby_name}}`,

		requestby_dsg:`{{$apacthdr->requestby_dsg}}`,
		supportby_dsg:`{{$apacthdr->supportby_dsg}}`,
		verifiedby_dsg:`{{$apacthdr->verifiedby_dsg}}`,
		approvedby_dsg:`{{$apacthdr->approvedby_dsg}}`,

		signature:``,
		drcode:`{{$apacthdr->suppcode}}`,
		crcode:`{{$apacthdr->bankcode}}`, 
		suppname:`{!!strtoupper($apacthdr->suppname)!!}`,
		bankname:`{{strtoupper($apacthdr->bankname)}}`, 
		AccNo:`{{$apacthdr->AccNo}}`,
		CompRegNo:`{{$apacthdr->CompRegNo}}`,
		TINNo:`{{$apacthdr->TINNo}}`,
		totamt:`{{$apacthdr->amount}}`,
		totamt_str:`{{$totamt_eng}}`,
		bankaccno:`{{$apacthdr->h_bankaccno}}`,
	};	

	var ini_body=[
		@foreach ($apalloc as $obj)
		{
			date:`{{\Carbon\Carbon::parse($obj->allocdate)->format('d/m/Y')}}`,
			docno:`{{strtoupper($obj->reference)}}`,
			desc:`{{strtoupper($obj->remarks)}}`,
			amt:`{{$obj->allocamount}}`,
		},
		@endforeach
		];

	var ini_compbankdet={
		bankaccno:`{{$company->bankaccno}}`,
		bankname:`{{strtoupper($company->bankname)}}`,
	};

    var subtotal=0;
    var disc=0;
    var nettotal=0;

	$(document).ready(function () {
		var docDefinition = {
			header: function(currentPage, pageCount, pageSize) {
				var retval=[];
				var header_pono = {
	                    style: 'header_pono',
	                    table: {
	                        widths: ['*','*'],//panjang standard dia 515
	                        body: [
	                            [
									{text: 'PAYMENT VOUCHER #: '+ini_header.pvno,bold: true, alignment: 'left', border: [false,true,false,true]}, //left, top, right, bottom
									{text: 'Date : '+ini_header.pvdate, alignment: 'right',bold: true, border: [false,true,false,true]},
								]
	                        ]
	                    },
			        }

				if(currentPage == 1){
					var logohdr = {image: 'letterhead',style:'header_img',width:350, height:75, alignment: 'center'};
					var title = {text: '\n{{$title}}',fontSize:14,alignment: 'center',bold: true};
					var compbankdet = {text: 'COMP A/C NO: '+ini_compbankdet.bankname+ ' ' +ini_compbankdet.bankaccno,fontSize:9,alignment: 'left', margin: [30, 0, 50, -8]};
					var pageno = {text: 'Page: '+currentPage+'/'+pageCount,fontSize:9,alignment: 'right', margin: [0, 0, 50, -8]};
					retval.push(logohdr);
					retval.push(title);
					retval.push(compbankdet);
					retval.push(pageno);
				}else{
					var title = {text: '\n{{$title}}',fontSize:14,alignment: 'center',bold: true, margin: [0, 71, 0, 0]};
					var compbankdet = {text: 'COMP A/C NO: '+ini_compbankdet.bankname+ ' ' +ini_compbankdet.bankaccno,fontSize:9,alignment: 'left', margin: [30, 0, 50, -8]};					
					var pageno = {text: 'Page: '+currentPage+'/'+pageCount,fontSize:9,alignment: 'right', margin: [0, 0, 50, -8]};
					retval.push(title);
					retval.push(compbankdet);
					retval.push(pageno);
				}

				retval.push(header_pono);
				return retval

			},
			// footer: function(currentPage, pageCount) {
			// 	if(currentPage == 1){
			// 		return [
			// 	      {image: 'logofooter',width:600, alignment: 'center'}
			// 	    ]
			// 	}
			// },
			pageSize: 'A4',
			pageMargins: [30, 150, 40, 70],
		  	content: make_pdf(),
			styles: {
				header_img: {
					margin: [30, 5, 0, 0]
				},
				header_pono: {
					fontSize: 9,
					margin: [30, 10, 40, 50]
				},
				header_tbl: {
					fontSize: 9,
					margin: [5, 0, 0, 0]
				},
				body_tbl: {
					fontSize: 9,
					margin: [0, 8, 0, 5]
				},
				body_ttl: {
					margin: [0, 2, 0, 2]
				},
				body_totamt_str: {
					fontSize: 9,
					margin: [0, 5, 0, 0]
				},
				body_drcr: {
					fontSize: 7,
					margin: [5, 30, 0, 0]
				},
				body_remark: {
					fontSize: 9,
					margin: [5, 15, 0, 0]
				},
				body_row: {
					margin: [0, 0, 0, 0]
				},
				body_name: {
					fontSize: 9,
					margin: [0, 25, 0, 5]
				},
				body_total: {
					fontSize: 9,
					margin: [0, 5, 0, 5]
				},
				body_sign: {
					fontSize: 8,
					margin: [1, 20, 0, 0]//left, top, right, bottom
				},

			},
			images: {
				letterhead: {
					url: '{{asset('/img/MSLetterHead.jpg')}}',
					headers: {
						myheader: '123',
						myotherheader: 'abc',
					}
				}
			}
		};

		pdfMake.createPdf(docDefinition).getDataUrl(function(dataURL) {
			$('#pdfiframe').attr('src',dataURL);
		});

		pdfMake.createPdf(docDefinition).getBase64(function(dataURL) {
			var obj = {
				base64:dataURL,
				_token:$('#_token').val(),
				merge_key:merge_key,
				lineno_:1
			};

			$.post( '../attachment_upload/form?page=merge_pdf',$.param(obj) , function( data ) {
			}).done(function(data) {
				merge_done[0] = true;
				get_merge_pdf();
			});
		});
	});

	function make_pdf(){

		var ret_pdf=[];
		var pdf=[
	  		{
	            style: 'header_tbl',
	            table: {
	                widths: [80,200,80,190],//panjang standard dia 515
	                body: [
	                    [
							{text: 'CREDITOR CODE',bold: true}, 
							{text: ': '+ini_header.cred_code},
							{text: ''}, {text: ''},
						],[
							{text: 'PAYEE NAME'}, 
							{text: ': '+ini_header.pname},
							{text: ''}, {text: ''},
						],[
							{text: 'PAYEE ADDRESS'}, 
							{text: ': '+ini_header.padd1},
							{text: ''}, {text: ''},
						],[
							{text: ''}, 
							{text: ': '+ini_header.padd2},
							{text: ''}, {text: ''},
						],[
							{text: ''}, 
							{text: ': '+ini_header.padd3},
							{text: ''}, {text: ''},
						],
						[{},{},{},{}],
						[{text:'PAYEE TEL NO',bold: true},{text: ': '+ini_header.ptelno},{text:'BANK A/C NO',bold: true},{text: ': '+ini_header.bankaccno}],
						[{text:'REG NO',bold: true},{text: ': '+ini_header.CompRegNo},{text:'TIN NO',bold: true},{text: ': '+ini_header.TINNo}]
					],
	            },
		        layout: 'noBorders',
	        },
			// {text:'MR/MRS,',alignment: 'left', fontSize: 9, margin: [5, 8, 0, 0]},
			{text:ini_header.desc,alignment: 'justify', fontSize: 9, margin: [5, 5, 0, 3]},
	  		{
	            style: 'body_tbl',
	            table: {
	                headerRows: 1,
	            	dontBreakRows: true,
	                widths: [50,120,'*',60],//panjang standard dia 515
	                body: make_body()
	            }
	        },
	        {text:'REMARK: '+ini_header.remarks,alignment: 'left',fontSize:9,style: 'body_remark'},
	        {
	            style: 'body_totamt_str',
	            table: {
	                widths: [400,'*'],//panjang standard dia 515
	                body: [
	                    [
							{text: 'RINGGIT MALAYSIA: '+ini_header.totamt_str,alignment: 'left', border: [false,true,false,true]},
							{text: numeral(ini_header.totamt).format('0,0.00'),alignment: 'right', border: [false,true,false,true]},
						]
	                ]
	            },
	        },
	        {
	            style: 'body_sign',
	            table: {
	                widths: ['*','*','*','*','*'],//panjang standard dia 515
	                body: [
	                	[
							{text: 'Prepared by\n\n\n\n\n__________________\n\n'+ini_header.requestby_name+ ' \n\n ' +ini_header.requestby_dsg,bold: true,alignment: 'left'}, 
							{text: 'Verified by\n\n\n\n\n__________________\n\n'+ini_header.verifiedby_name+ ' \n\n ' +ini_header.verifiedby_dsg,bold: true,alignment: 'left'},
							{text: 'Approved By\n\n\n\n\n__________________\n\n'+ini_header.approvedby_name+ ' \n\n ' +ini_header.approvedby_dsg,bold: true,alignment: 'left'}, 
							{text: 'Signatures\n\n\n\n\n__________________\n\nName:',bold: true,alignment: 'left'},
							{text: 'Signatures\n\n\n\n\n__________________\n\nName:',bold: true,alignment: 'left'},

	                	]
	                ]
	            },
		        layout: 'noBorders',
	        },
	        {
	            style: 'body_drcr',
	            table: {
	                widths: [45,90,80],//panjang standard dia 515
	                body: [
	                	[
							{text: ini_header.drcode,alignment: 'left'}, 
							{text: ini_header.suppname,alignment: 'left'}, 
							{text: numeral(ini_header.totamt).format('0,0.00'),alignment: 'left'},
	                	],
	                	[
							{text: ini_header.crcode,alignment: 'left'}, 
							{text: ini_header.bankname,alignment: 'left'}, 
							{text: numeral(ini_header.totamt).format('0,0.00'),alignment: 'left'},
	                	],
	                ]
	            },
		        layout: 'noBorders',
	        },

		];

		if(ini_body.length > 5){
	    	var next_pdf = [
		  		{
		  			pageBreak: 'before',
		            style: 'body_tbl',
		            table: {
		                headerRows: 1,
		            	dontBreakRows: true,
		                widths: [50,120,'*',60],//panjang standard dia 515
		                body: make_body()
		            }
		        },
		        {text:'REMARK: '+ini_header.remarks,alignment: 'left',fontSize:9,style: 'body_remark'},
		        {
		            style: 'body_totamt_str',
		            table: {
		                widths: [400,'*'],//panjang standard dia 515
		                body: [
		                    [
								{text: 'RINGGIT MALAYSIA: '+ini_header.totamt_str,alignment: 'left', border: [false,true,false,true]},
								{text: numeral(ini_header.totamt).format('0,0.00'),alignment: 'right', border: [false,true,false,true]},
							]
		                ]
		            },
		        }
	    	];

	    	ret_pdf =  pdf.concat(next_pdf);
	    }else{
	    	ret_pdf = pdf;
	    }

		return ret_pdf;
	}

	var make_body_loop = 0;
	function make_body(){
		var retval = [
	        [
				{text:'DATE',bold: true, style: 'body_ttl',border: [false, true, false, true]},
				{text:'DOCUMENT NO.',bold: true, style: 'body_ttl',border: [false, true, false, true]},
				{text:'DESCRIPTION',bold: true, style: 'body_ttl',border: [false, true, false, true]},
				{text:'AMOUNT(RM)',bold: true, alignment: 'right', style: 'body_ttl',border: [false, true, false, true]},
			],
	    ];

		if(ini_body.length > 5 && make_body_loop == 0){

	    	let arr = [
				{text:'', style: 'body_row', border: [false, false, false, false]},
				{text:'', style: 'body_row', border: [false, false, false, false]},
				{text:'PLEASE REFER TO ATTACHMENT', style: 'body_row', border: [false, false, false, false]},
				{text:'', style: 'body_row',alignment: 'right', border: [false, false, false, false]},
	    	];
	    	retval.push(arr);

		}else{

		    ini_body.forEach(function(e,i){
		    	let arr = [
					{text:e.date, style: 'body_row', border: [false, false, false, false]},
					{text:e.docno, style: 'body_row', border: [false, false, false, false]},
					{text:e.desc, style: 'body_row', border: [false, false, false, false]},
					{text:numeral(e.amt).format('0,0.00'), style: 'body_row',alignment: 'right', border: [false, false, false, false]},
		    	];
		    	retval.push(arr);
		    });
		}
		make_body_loop = make_body_loop + 1;

		if(retval.length<5){
	    	let loop_btm = 5-retval.length;

	    	for (var i = (loop_btm+loop_btm); i >= 0; i--) {
	    		retval.push([
					{text:' ', style: 'body_row', border: [false, false, false, false]},
					{text:' ', style: 'body_row', border: [false, false, false, false]},
					{text:' ', style: 'body_row', border: [false, false, false, false]},
					{text:' ', style: 'body_row', border: [false, false, false, false]},
		    	]);
	    	}
	    }

	    return retval;
	}


@if(is_object($CN_obj))
	var ini_header_cn={
			auditno:`{{str_pad($CN_obj->apacthdr->auditno, 7, '0', STR_PAD_LEFT)}}`,
			docdate:`{{\Carbon\Carbon::parse($apacthdr->actdate)->format('d/m/Y')}}`,
			cred_code:`{{$CN_obj->apacthdr->suppcode}}`,
			pname:`{{strtoupper($CN_obj->apacthdr->suppname)}}`,
			padd1:`{{strtoupper($CN_obj->apacthdr->addr1)}}`,
			padd2:`{{strtoupper($CN_obj->apacthdr->addr2)}}`,
			padd3:`{{strtoupper($CN_obj->apacthdr->addr3)}}`,
			ptelno:`{{$CN_obj->apacthdr->telno}}`,
			desc:``,
			remarks:`{{strtoupper($CN_obj->apacthdr->remarks)}}`,
			prepby:``,
			checkby:``,
			approveby:``,
			signature:``,
			drcode:`{{$CN_obj->apacthdr->suppcode}}`,
			crcode:`{{$CN_obj->apacthdr->bankcode}}`, 
			suppname:`{!!strtoupper($CN_obj->apacthdr->suppname)!!}`,
			bankname:`{{strtoupper($CN_obj->apacthdr->bankname)}}`, 
			AccNo:`{{$CN_obj->apacthdr->AccNo}}`,
			CompRegNo:`{{$CN_obj->apacthdr->CompRegNo}}`,
			TINNo:`{{$CN_obj->apacthdr->TINNo}}`,
			totamt:`{{$CN_obj->apacthdr->amount}}`,
			totamt_str:`{{$CN_obj->totamt_eng}}`,
			};	

	var ini_body_cn=[
				@foreach ($CN_obj->apalloc as $obj)
				{
					date:`{{\Carbon\Carbon::parse($obj->allocdate)->format('d/m/Y')}}`,
					docno:`{{strtoupper($obj->reference)}}`,
					desc:`{{strtoupper($obj->remarks)}}`,
					amt:`{{$obj->allocamount}}`,
				},
				@endforeach
				];

    var subtotal_cn=0;
    var disc_cn=0;
    var nettotal_cn=0;
	$(document).ready(function () {
		var docDefinition_cn = {
			header: function(currentPage, pageCount, pageSize) {
				var retval=[];
				var header_pono = {
	                    style: 'header_pono',
	                    table: {
	                        widths: ['*','*'],//panjang standard dia 515
	                        body: [
	                            [
									{text: 'AUDIT NO #: '+ini_header_cn.auditno,bold: true, alignment: 'left', border: [false,true,false,true]}, //left, top, right, bottom
									{text: 'Date : '+ini_header_cn.docdate, alignment: 'right',bold: true, border: [false,true,false,true]},
								]
	                        ]
	                    },
			        }

				if(currentPage == 1){
					var logohdr = {image: 'letterhead',style:'header_img',width:350, height:75, alignment: 'center'};
					var title = {text: '\n{{$CN_obj->title}}',fontSize:14,alignment: 'center',bold: true};
					var pageno = {text: 'Page: '+currentPage+'/'+pageCount,fontSize:9,alignment: 'right', margin: [0, 0, 50, -8]};
					retval.push(logohdr);
					retval.push(title);
					retval.push(pageno);
				}else{
					var title = {text: '\n{{$CN_obj->title}}',fontSize:14,alignment: 'center',bold: true, margin: [0, 71, 0, 0]};
					retval.push(title);
					retval.push(pageno);
				}

				retval.push(header_pono);
				return retval

			},
			// footer: function(currentPage, pageCount) {
			// 	if(currentPage == 1){
			// 		return [
			// 	      {image: 'logofooter',width:600, alignment: 'center'}
			// 	    ]
			// 	}
			// },
			pageSize: 'A4',
			pageMargins: [30, 150, 40, 70],
		  	content: make_pdf_cn(),
			styles: {
				header_img: {
					margin: [30, 5, 0, 0]
				},
				header_pono: {
					fontSize: 9,
					margin: [30, 10, 40, 50]
				},
				header_tbl: {
					fontSize: 9,
					margin: [5, 0, 0, 0]
				},
				body_tbl: {
					fontSize: 9,
					margin: [0, 8, 0, 5]
				},
				body_ttl: {
					margin: [0, 2, 0, 2]
				},
				body_totamt_str: {
					fontSize: 9,
					margin: [0, 5, 0, 0]
				},
				body_drcr: {
					fontSize: 9,
					margin: [5, 30, 0, 0]
				},
				body_remark: {
					fontSize: 9,
					margin: [5, 15, 0, 0]
				},
				body_row: {
					margin: [0, 0, 0, 0]
				},
				body_name: {
					fontSize: 9,
					margin: [0, 25, 0, 5]
				},
				body_total: {
					fontSize: 9,
					margin: [0, 5, 0, 5]
				},
				body_sign: {
					fontSize: 8,
					margin: [1, 20, 0, 0]//left, top, right, bottom
				},

			},
			images: {
				letterhead: {
					url: '{{asset('/img/MSLetterHead.jpg')}}',
					headers: {
						myheader: '123',
						myotherheader: 'abc',
					}
				}
			}
		};

		pdfMake.createPdf(docDefinition_cn).getDataUrl(function(dataURL_cn) {
			$('#pdfiframe_cn').attr('src',dataURL_cn);
		});

		pdfMake.createPdf(docDefinition_cn).getBase64(function(dataURL) {
			var obj = {
				base64:dataURL,
				_token:$('#_token').val(),
				merge_key:merge_key,
				lineno_:2
			};

			$.post( '../attachment_upload/form?page=merge_pdf',$.param(obj) , function( data ) {
			}).done(function(data){
				merge_done[1] = true;
				get_merge_pdf();
			});
		});
	});

	function make_pdf_cn(){

		var ret_pdf=[];
		var pdf=[
	  		{
	            style: 'header_tbl',
	            table: {
	                widths: [80,200,80,190],//panjang standard dia 515
	                body: [
	                    [
							{text: 'CREDITOR CODE',bold: true}, 
							{text: ': '+ini_header_cn.cred_code},
							{text: ''}, {text: ''},
						],[
							{text: 'PAYEE NAME'}, 
							{text: ': '+ini_header_cn.pname},
							{text: ''}, {text: ''},
						],[
							{text: 'PAYEE ADDRESS'}, 
							{text: ': '+ini_header_cn.padd1},
							{text: ''}, {text: ''},
						],[
							{text: ''}, 
							{text: ': '+ini_header_cn.padd2},
							{text: ''}, {text: ''},
						],[
							{text: ''}, 
							{text: ': '+ini_header_cn.padd3},
							{text: ''}, {text: ''},
						],
						[{},{},{},{}],
						[{text:'PAYEE TEL NO',bold: true},{text: ': '+ini_header_cn.ptelno},{text:'BANK A/C NO',bold: true},{text: ': '+ini_header_cn.AccNo}],
						[{text:'REG NO',bold: true},{text: ': '+ini_header_cn.CompRegNo},{text:'TIN NO',bold: true},{text: ': '+ini_header_cn.TINNo}]
					],
	            },
		        layout: 'noBorders',
	        },
			// {text:'MR/MRS,',alignment: 'left', fontSize: 9, margin: [5, 8, 0, 0]},
			{text:ini_header_cn.desc,alignment: 'justify', fontSize: 9, margin: [5, 5, 0, 3]},
	  		{
	            style: 'body_tbl',
	            table: {
	                headerRows: 1,
	            	dontBreakRows: true,
	                widths: [50,120,'*',60],//panjang standard dia 515
	                body: make_body_cn()
	            }
	        },
	        {text:'REMARK: '+ini_header_cn.remarks,alignment: 'left',fontSize:9,style: 'body_remark'},
	        {
	            style: 'body_totamt_str',
	            table: {
	                widths: [400,'*'],//panjang standard dia 515
	                body: [
	                    [
							{text: 'RINGGIT MALAYSIA: '+ini_header_cn.totamt_str,alignment: 'left', border: [false,true,false,true]},
							{text: numeral(ini_header_cn.totamt).format('0,0.00'),alignment: 'right', border: [false,true,false,true]},
						]
	                ]
	            },
	        },
	        {
	            style: 'body_sign',
	            table: {
	                widths: ['*','*','*','*','*'],//panjang standard dia 515
	                body: [
	                	[
							{text: 'Prepared ddby\n\n\n\n\n__________________\n\nName:',bold: true,alignment: 'left'}, 
							{text: 'Checked By\n\n\n\n\n__________________\n\nName:',bold: true,alignment: 'left'},
							{text: 'Approved By\n\n\n\n\n__________________\n\nName:',bold: true,alignment: 'left'}, 
							{text: 'Signatures\n\n\n\n\n__________________\n\nName:',bold: true,alignment: 'left'},
							{text: 'Signatures\n\n\n\n\n__________________\n\nName:',bold: true,alignment: 'left'},
	                	]
	                ]
	            },
		        layout: 'noBorders',
	        },
	        {
	            style: 'body_drcr',
	            table: {
	                widths: [45,90,80],//panjang standard dia 515
	                body: [
	                	[
							{text: ini_header_cn.drcode,alignment: 'left'}, 
							{text: ini_header_cn.suppname,alignment: 'left'}, 
							{text: numeral(ini_header_cn.totamt).format('0,0.00'),alignment: 'left'},
	                	],
	                	[
							{text: ini_header_cn.crcode,alignment: 'left'}, 
							{text: ini_header_cn.bankname,alignment: 'left'}, 
							{text: numeral(ini_header_cn.totamt).format('0,0.00'),alignment: 'left'},
	                	],
	                ]
	            },
		        layout: 'noBorders',
	        },

		];

		if(ini_body.length > 5){
	    	var next_pdf = [
		  		{
		  			pageBreak: 'before',
		            style: 'body_tbl',
		            table: {
		                headerRows: 1,
		            	dontBreakRows: true,
		                widths: [50,120,'*',60],//panjang standard dia 515
		                body: make_body_cn()
		            }
		        },
		        {text:'REMARK: '+ini_header_cn.remarks,alignment: 'left',fontSize:9,style: 'body_remark'},
		        {
		            style: 'body_totamt_str',
		            table: {
		                widths: [400,'*'],//panjang standard dia 515
		                body: [
		                    [
								{text: 'RINGGIT MALAYSIA: '+ini_header_cn.totamt_str,alignment: 'left', border: [false,true,false,true]},
								{text: numeral(ini_header_cn.totamt).format('0,0.00'),alignment: 'right', border: [false,true,false,true]},
							]
		                ]
		            },
		        }
	    	];

	    	ret_pdf =  pdf.concat(next_pdf);
	    }else{
	    	ret_pdf = pdf;
	    }

		return ret_pdf;
	}

	var make_body_loop_cn = 0;
	function make_body_cn(){
		var retval = [
	        [
				{text:'DATE',bold: true, style: 'body_ttl',border: [false, true, false, true]},
				{text:'DOCUMENT NO.',bold: true, style: 'body_ttl',border: [false, true, false, true]},
				{text:'DESCRIPTION',bold: true, style: 'body_ttl',border: [false, true, false, true]},
				{text:'AMOUNT(RM)',bold: true, alignment: 'right', style: 'body_ttl',border: [false, true, false, true]},
			],
	    ];

		if(ini_body_cn.length > 5 && make_body_loop_cn == 0){

	    	let arr = [
				{text:'', style: 'body_row', border: [false, false, false, false]},
				{text:'', style: 'body_row', border: [false, false, false, false]},
				{text:'PLEASE REFER TO ATTACHMENT', style: 'body_row', border: [false, false, false, false]},
				{text:'', style: 'body_row',alignment: 'right', border: [false, false, false, false]},
	    	];
	    	retval.push(arr);

		}else{

		    ini_body_cn.forEach(function(e,i){
		    	let arr = [
					{text:e.date, style: 'body_row', border: [false, false, false, false]},
					{text:e.docno, style: 'body_row', border: [false, false, false, false]},
					{text:e.desc, style: 'body_row', border: [false, false, false, false]},
					{text:numeral(e.amt).format('0,0.00'), style: 'body_row',alignment: 'right', border: [false, false, false, false]},
		    	];
		    	retval.push(arr);
		    });
		}
		make_body_loop_cn = make_body_loop_cn + 1;

		if(retval.length<5){
	    	let loop_btm = 5-retval.length;

	    	for (var i = (loop_btm+loop_btm); i >= 0; i--) {
	    		retval.push([
					{text:' ', style: 'body_row', border: [false, false, false, false]},
					{text:' ', style: 'body_row', border: [false, false, false, false]},
					{text:' ', style: 'body_row', border: [false, false, false, false]},
					{text:' ', style: 'body_row', border: [false, false, false, false]},
		    	]);
	    	}
	    }

	    return retval;
	}

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

	});


@endif
function get_merge_pdf(){
	let execute = true;
	merge_done.forEach(function(e,i){
		if(e==false){
			execute = false
		}
	});
	if(execute){
		var obj = {
			page:'get_merge_pdf',
			merge_key:merge_key,
		};

		$('#pdfiframe_merge').attr('src',"../attachment_upload/table?"+$.param(obj));
	}
}

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


</script>

<body style="margin: 0px;">
<input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">

@if(is_object($CN_obj))
<div class="ui segments" style="width: 18vw;height: 95vh;float: left; margin: 10px; position: fixed;">
  <div class="ui secondary segment">
    <h3><b>Navigation</b></h3>
  </div>
  <div class="ui segment teal inverted canclick" style="cursor: pointer;" data-goto='#pdfiframe'>
    <p>Payment Voucher</p>
  </div>
  <div class="ui segment canclick" style="cursor: pointer;" data-goto='#pdfiframe_cn'>
    <p>Credit Note</p>
  </div>
  <div class="ui segment canclick" style="cursor: pointer;" data-goto='#pdfiframe_merge'>
    <p>Merged File</p>
  </div>
</div>
<iframe id="pdfiframe" width="100%" height="100%" src="" frameborder="0" style="width: 79vw;height: 100vh;float: right;"></iframe>
<iframe id="pdfiframe_cn" width="100%" height="100%" src="" frameborder="0" style="width: 79vw;height: 100vh;float: right;"></iframe>
<iframe id="pdfiframe_merge" width="100%" height="100%" src="" frameborder="0" style="width: 79vw;height: 100vh;float: right;"></iframe>

@else
<iframe id="pdfiframe" width="100%" height="100%" src="" frameborder="0" style="height: 99vh;float: right;"></iframe>
@endif

</body>
</html>