<!DOCTYPE html>
<html>
<head>
<title>INVENTORY REQUEST</title>

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

	$(document).ready(function () {
		var docDefinition = {
			header: function(currentPage, pageCount, pageSize) {
				var retval=[];
				var header_tbl = {
	                    style: 'header_tbl',
	                    table: {
	                        headerRows: 1,
	                        widths: [100,'*',60,'*'],//panjang standard dia 515
	                        body: [
	                            [
									{text: 'Request Department',bold: true}, 
									{text: `: {!!$reqdept->description!!}`},
									{text: 'Request No',bold: true}, 
									{text: ': {{$ivreqhd->reqdept}}-{{str_pad($ivreqhd->ivreqno, 5, '0', STR_PAD_LEFT)}}'},
								],[
									{text: 'Request To Department',bold: true}, 
									{text: `: {!!$reqtodept->description!!}`},
									{text: 'Record No.',bold: true}, 
									{text: ': {{str_pad($ivreqhd->recno, 5, '0', STR_PAD_LEFT)}}'},
								]
	                        ]
	                    },
				        layout: 'noBorders',
			        }
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
					var title = {text: 'INVENTORY REQUEST',fontSize:12,alignment: 'center',bold: true, margin: [0, 5, 0, 5]};
					retval.push(header_tbl_bangi);
					// retval.push(addr1_unit);
					// retval.push(addr2_unit);
					// retval.push(addr3_unit);
					retval.push(title);
				}else{
					var title = {text: 'INVENTORY REQUEST',fontSize:12,alignment: 'center',bold: true, margin: [0, 56, 0, 5]};
					// retval.push(addr1_unit);
					// retval.push(addr2_unit);
					// retval.push(addr3_unit);
					retval.push(title);
				}

				retval.push(header_tbl);
				return retval

			},
			footer: function(currentPage, pageCount) {
				// return [
				// 	{
				// 		text: 'This is a computer-generated document. No signature is required.', alignment: 'center', fontSize: 10
				// 	},
				// ]
			},
			// footer: function (currentPage, pageCount){
			// 	if(currentPage == pageCount){
			// 		return [
			// 			// {image: 'logofooter',width:600, alignment: 'center'}
			// 		]
			// 	}
			// },
			pageSize: 'A4',
			pageMargins: [30,140, 20, 20],
			content: [
				{
					style: 'body_tbl',
					table: {
						headerRows: 1,
						dontBreakRows: true,
						widths: [20,170,'*','*','*','*','*'],//panjang standard dia 515
						body: [
							[
								{text:'Line No.',bold: true, style: 'body_ttl',border: [false, true, false, true]},
								{text:'Item',bold: true, style: 'body_ttl',border: [false, true, false, true]},
								{text:'UOM',bold: true, style: 'body_ttl',border: [false, true, false, true]},
								{text:'Qty Request',bold: true, style: 'body_ttl',alignment: 'right',border: [false, true, false, true]},
								{text:'Qty On Hand',bold: true, style: 'body_ttl',alignment: 'right',border: [false, true, false, true]},
								{text:'Qty Balance',bold: true, style: 'body_ttl',border: [false, true, false, true]},
								{text:'Price',bold: true, style: 'body_ttl',alignment: 'right',border: [false, true, false, true]},
							],
							@foreach ($ivreqdt as $index=>$dtl)
							[
								{text:'{{$dtl->lineno_}}', style: 'body_row', border: [false, false, false, false]},
								{text:'{{$dtl->itemcode}}', style: 'body_row', border: [false, false, false, false]},
								{text:`{!!str_replace('`', '', $dtl->uom_desc)!!}`, style: 'body_row', border: [false, false, false, false]},
								{text:'{{$dtl->qtyrequest}}', style: 'body_row',alignment: 'right', border: [false, false, false, false]},
								{text:'{{$dtl->qtyonhand}}', style: 'body_row',alignment: 'right', border: [false, false, false, false]},
								{text:'{{$dtl->qtybalance}}', style: 'body_row',alignment: 'right', border: [false, false, false, false]},
								{text:'{{number_format($dtl->netprice,2)}}', alignment: 'right', style: 'body_row', border: [false, false, false, false]},
							],
							[
								{text:'',style: 'body_row', border: [false, false, false, false]},
								{colSpan:5,text:`{!!str_replace('`', '', $dtl->description)!!}`, border: [false, false, false, false]},
								{text:'',style: 'body_row', border: [false, false, false, false]},
								{text:'',style: 'body_row', border: [false, false, false, false]},
								{text:'',style: 'body_row', border: [false, false, false, false]},
								{text:'',style: 'body_row', border: [false, false, false, false]},
								{text:'',style: 'body_row', border: [false, false, false, false]},
					    	],
					    	[
								{text:'',style: 'body_row', border: [false, false, false, false]},
								{colSpan:5,text:`{!!str_replace('`', '', $dtl->remarks)!!}`, border: [false, false, false, false]},
								{text:'',style: 'body_row', border: [false, false, false, false]},
								{text:'',style: 'body_row', border: [false, false, false, false]},
								{text:'',style: 'body_row', border: [false, false, false, false]},
								{text:'',style: 'body_row', border: [false, false, false, false]},
								{text:'',style: 'body_row', border: [false, false, false, false]},
					    	],
							@endforeach

                        ]
                    }
		        },
		        {text:'INVENTORY Request Remarks :',alignment: 'left',fontSize:9, margin: [0, 30, 0, 0]},
		        {text:`{!!str_replace('`', '', $ivreqhd->remarks)!!}`,alignment: 'left',fontSize:9},

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
					margin: [0, 0, 0, 0]
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
	});

</script>

<body style="margin: 0px;">

<input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">
<iframe id="pdfiframe" width="100%" height="100%" src="" frameborder="0" style="width: 100vw;height: 100vh;float: right;"></iframe>

</body>
</html>