<!DOCTYPE html>
<html>
<head>
<title>Direct Payment</title>

</head>

<!-- <script src="https://unpkg.com/@jsreport/browser-client/dist/jsreport.umd.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="mydata.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha512-a9NgEEK7tsCvABL7KqtUTQjl69z7091EVPpw5KxPlZ93T141ffe1woLtbXTX+r2/8TtTvRX/v4zTL2UlMUPgwg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.min.js" integrity="sha512-P0bOMePRS378NwmPDVPU455C/TuxDS+8QwJozdc7PGgN8kLqR4ems0U/3DeJkmiE31749vYWHvBOtR+37qDCZQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/numeral@2.0.6/numeral.min.js"></script>
</object>

<script>
	
	var ini_header={
			pvno:`{{str_pad($apacthdr->pvno, 7, '0', STR_PAD_LEFT)}}`,
			pvdate:`{{\Carbon\Carbon::parse($apacthdr->actdate)->format('d/m/Y')}}`,
			payto:`{!!$apacthdr->payto!!}`,
			pname:`{!!$apacthdr->suppname!!}`,
			padd1:`{!!$apacthdr->addr1!!}`,
			padd2:`{!!$apacthdr->addr2!!}`,
			padd3:`{!!$apacthdr->addr3!!}`,
			ptelno:`{{$apacthdr->telno}}`,
			desc:`{{strtoupper($apacthdr->remarks)}}`,
			remarks:``,
			prepby:``,
			checkby:``,
			approveby:``,
			signature:``,
			drcode:`{{$apacthdr->payto}}`,
			crcode:`{{$apacthdr->bankcode}}`, 
			bankname:`{{$apacthdr->bankname}}`, 
			totamt:`{{$apacthdr->amount}}`,
			totamt_str:`{{$totamt_eng}}`,
		};	

	var ini_body=[
			@foreach ($apactdtl as $obj)
			{
				date:`{{\Carbon\Carbon::parse($obj->adddate)->format('d/m/Y')}}`,
				docno:`{!!strtoupper($obj->document)!!}`,
				remarks:`{!!strtoupper($obj->remarks)!!}`,
				amt:`{{$obj->amount}}`,
				category:`{!!$obj->category!!}`,
				desc:`{!!strtoupper($obj->desc)!!}`,
			},
			@endforeach
	];

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
					var logohdr = {image: 'logohdr',style:'header_img',width:200, alignment: 'center'};
					var title = {text: '\n{{$title}}',fontSize:14,alignment: 'center',bold: true};
					var pageno = {text: 'Page: '+currentPage+'/'+pageCount,fontSize:9,alignment: 'right', margin: [0, 0, 50, -8]};
					retval.push(logohdr);
					retval.push(title);
					retval.push(pageno);

				}else{
					var title = {text: '\n{{$title}}',fontSize:14,alignment: 'center',bold: true, margin: [0, 71, 0, 0]};
					var pageno = {text: 'Page: '+currentPage+'/'+pageCount,fontSize:9,alignment: 'right', margin: [0, 0, 50, -8]};
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
			pageMargins: [30, 110, 40, 70],
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
					fontSize: 9,
					margin: [5, 20, 0, 0]
				},

			},
			images: {
				logohdr: "{{asset('/img/letterheadukm.png')}}",
			}
		};

		pdfMake.createPdf(docDefinition).getDataUrl(function(dataURL) {
			$('#pdfiframe').attr('src',dataURL);
		});
	});

function make_pdf(){

	var ret_pdf=[];
	var pdf=[
  		{
            style: 'header_tbl',
            table: {
                widths: [120,290],//panjang standard dia 515
                body: [
                    [
						{text: 'CREDITOR CODE',bold: true}, 
						{text: ': '+ini_header.payto},
					],[
						{text: 'PAYEE NAME'}, 
						{text: ': '+ini_header.pname},
					],[
						{text: 'PAYEE ADDRESS'}, 
						{text: ': '+ini_header.padd1},
					],[
						{text: ''}, 
						{text: ': '+ini_header.padd2},
					],[
						{text: ''}, 
						{text: ': '+ini_header.padd3},
					],
					[{},{}],
					[{text:'PAYEE TEL NO',bold: true},{text: ': '+ini_header.ptelno}],
                ]
            },
	        layout: 'noBorders',
        },
		{text:'MR/MRS,',alignment: 'left', fontSize: 9, margin: [5, 8, 0, 0]},
		// {text:ini_header.desc,alignment: 'justify', fontSize: 9, margin: [5, 5, 0, 3]},
  		{
            style: 'body_tbl',
            table: {
                headerRows: 1,
            	dontBreakRows: true,
                widths: [50,120,'*',60],//panjang standard dia 515
                body: make_body()
            }
        },
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
                widths: ['*','*','*','*'],//panjang standard dia 515
                body: [
                	[
						{text: 'Prepared by\n\n\n\n\n______________________\n\nName:',bold: true,alignment: 'left'}, 
						{text: 'Checked By\n\n\n\n\n______________________\n\nName:',bold: true,alignment: 'left'},
						{text: 'Approved By\n\n\n\n\n______________________\n\nName:',bold: true,alignment: 'left'}, 
						{text: 'Signatures\n\n\n\n\n______________________\n\nName:',bold: true,alignment: 'left'},
                	]
                ]
            },
	        layout: 'noBorders',
        },
        {
            style: 'body_drcr',
            table: {
                widths: [15,45,90,80],//panjang standard dia 515
                body:dr_category()
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
	        // {text:'REMARK: '+ini_header.remarks,alignment: 'left',fontSize:9,style: 'body_remark'},
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

	if(ini_body.length > 20 && make_body_loop == 0){

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
				{text:e.remarks, style: 'body_row', border: [false, false, false, false]},
				{text:numeral(e.amt).format('0,0.00'), style: 'body_row',alignment: 'right', border: [false, false, false, false]},
	    	];
	    	retval.push(arr);
	    });
	}
	make_body_loop = make_body_loop + 1;

	if(retval.length<20){
    	let loop_btm = 20-retval.length;

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

function dr_category(){
	let retval=[
		[	
			{text: 'CR',alignment: 'left'}, 
			{text: ini_header.crcode,alignment: 'left'}, 
			{text: ini_header.bankname,alignment: 'left'}, 
			{text: numeral(ini_header.totamt).format('0,0.00'),alignment: 'left'},
		]
	];

	ini_body.forEach(function(e,i){
    	let arr = [
			{text: 'DR',alignment: 'left'}, 
			{text: e.category,alignment: 'left'}, 
			{text: e.desc,alignment: 'left'}, 
			{text: numeral(e.amt).format('0,0.00'),alignment: 'left'},
    	];
    	retval.push(arr);
    });

    return retval;
}

</script>

<body style="margin: 0px;">

<iframe id="pdfiframe" width="100%" height="100%" src="" frameborder="0" style="width: 99vw;height: 99vh;"></iframe>

</body>
</html>