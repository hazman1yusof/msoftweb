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

	var billsum=[
		@foreach($billsum as $key => $bilsm)
		{
			@foreach($bilsm as $key2 => $val)
				'{{$key2}}' : `{!!str_replace('`', '', $val)!!}`,
			@endforeach
		},
		@endforeach 
	];

	$(document).ready(function () {
		var docDefinition = {
			pageSize: 'A4',
			pageMargins: [2, 5, 2, 5],
			content: [
				{
					style: 'tableExample',
					table: {
						headerRows: 0,
						widths: [133], //panjang standard dia 515
						body: [
							[
								{text: 'K-HEALTH - PPUKM\nARAS BAWAH (G) LOBI PELAWAT,\nPUSAT PERUBATAN UKM,\nJALAN YAACOB LATIFF,\n56000 CHERAS, KUALA LUMPUR.\nTel: 019-2289357\nFax: 03-91739357', alignment: 'center'},
							],
							[
								{text: `Cashier : {{strtoupper($tilldetl->cashier)}}\nReceipt : {{$receipt[0]->recptno}}\nDate      : {{\Carbon\Carbon::parse($receipt[0]->entrydate)->format('d/m/Y')}} {{$receipt[0]->entrytime}}`, alignment: 'left'},
							],
							[
								{text: `----------------------------------------------------`, alignment: 'center'},
							],
							[
								{text: `Description                      Subtotal`, alignment: 'center'},
							],
							[
								{text: `----------------------------------------------------`, alignment: 'center'},
							],
							[
								make_detail()
							],
							[
								{text: `----------------------------------------------------`, alignment: 'center'},
							],
							[
								{
									style: 'tableDetail',
									table: {
										widths: [73,50],
										body: [
											[
												{text:'TOTAL',margin: [0, 0, 0, 0]},
												{text:`{{$dbacthdr->amount}}`, alignment: 'right',margin: [0, 0, 0, 0]}
											],
											[
												{text:'DEPOSIT/PAYMENT',margin: [0, -2, 0, 0]},
												{text:`{{number_format($dbacthdr->amount - $dbacthdr->outamount,2)}}`, alignment: 'right',margin: [0, -2, 0, 0]}
											],
											[
												{text:'BALANCE',margin: [0, -2, 0, 0]},
												{text:`{{$dbacthdr->outamount}}`, alignment: 'right',margin: [0, -2, 0, 0]}
											]
										]
									},
									layout: 'noBorders',
								}
							],
							[
								{text: `----------------------------------------------------`, alignment: 'center'},
							],
							@foreach($receipt as $key => $receipt_obj)
							[
								{
									style: 'tableDetail',
									table: {
										widths: [50,73],
										body: [
											[
												{text:'Card No.',margin: [0, 0, 0, 0]},
												{text:`:  {{$receipt_obj->reference}}`, alignment: 'left',margin: [0, 0, 0, 0]}
											],
											[
												{text:'Pay Mode',margin: [0, -2, 0, 0]},
												{text:`:  {!!$receipt_obj->paymode!!}`, alignment: 'left',margin: [0, -2, 0, 0]}
											],
											@if(strtolower($receipt_obj->paymode) == 'cash')
											[
												{text:' - Paid',margin: [0, -2, 0, 0],italics:true},
												{text:`:  RM {{number_format($receipt_obj->amount+$receipt_obj->RCCASHbalance,2)}}`, alignment: 'left',margin: [0, -2, 0, 0]}
											],
											[
												{text:' - Balance',margin: [0, -2, 0, 0],italics:true},
												{text:`:  RM {{number_format($receipt_obj->RCCASHbalance,2)}}`, alignment: 'left',margin: [0, -2, 0, 0]}
											],
											@endif
											[
												{text:'App.Code',margin: [0, -2, 0, 0]},
												{text:`:  `, alignment: 'left',margin: [0, -2, 0, 0]}
											]
										]
									},
									layout: 'noBorders',
								}
							],
							@endforeach
							[
								{text: `----------------------------------------------------`, alignment: 'center'},
							],
							[
								{
									style: 'tableDetail',
									table: {
										widths: [50,73],
										body: [
											[
												{text:'Name',margin: [0, 0, 0, 0]},
												{text:`:  {{$receipt[0]->payername}}`, alignment: 'left',margin: [0, 0, 0, 0]}
											],
											[
												{text:'Doctor',margin: [0, -2, 0, 0]},
												{text:`:  @if(empty($receipt[0]->doctorcode)){{'NONE'}}@else{{$receipt[0]->doctorcode}}@endif`, alignment: 'left',margin: [0, -2, 0, 0]}
											],
											[
												{text:'IC No',margin: [0, -2, 0, 0]},
												{text:`:  `, alignment: 'left',margin: [0, -2, 0, 0]}
											]
										]
									},
									layout: 'noBorders',
								}
							],
							[
								{text: `----------------------------------------------------`, alignment: 'center'},
							],
							[
								{text: `Thank You ! Please Come Again !\nGoods Sold Are Not Returnable But\nExchangeble within 7 days with\nOriginal Receipt`, alignment: 'center'},
							],

						]
					},
					layout: 'noBorders',
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
				tableDetail: {
					fontSize: 9,
					margin: [0, -5, 0, -5]
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
			}
		};

		pdfMake.createPdf(docDefinition).getDataUrl(function(dataURL) {
			$('#pdfiframe').attr('src',dataURL);
		});
	});
	
	function make_detail(){
		var body = [];

		billsum.forEach(function(e,i){
			body.push([{text:e.chggroup+'-'+e.chgmast_desc,colSpan:2},{}]);
			body.push([{text:Math.round(e.quantity)+' X '+e.unitprice,margin: [0, -4, 0, 0]},{text:e.amount, alignment: 'right',margin: [0, -4, 0, 0]}]);
		});

		var retval = {
						style: 'tableDetail',
						table: {
							widths: [75,50],
							body: body
						},
						layout: 'noBorders',
					};

		return retval;
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