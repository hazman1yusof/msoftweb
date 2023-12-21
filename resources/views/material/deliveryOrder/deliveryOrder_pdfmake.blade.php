<!DOCTYPE html>
<html>
<head>
<title>Delivery Order</title>

</head>

<!-- <script src="https://unpkg.com/@jsreport/browser-client/dist/jsreport.umd.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="mydata.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha512-a9NgEEK7tsCvABL7KqtUTQjl69z7091EVPpw5KxPlZ93T141ffe1woLtbXTX+r2/8TtTvRX/v4zTL2UlMUPgwg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.min.js" integrity="sha512-P0bOMePRS378NwmPDVPU455C/TuxDS+8QwJozdc7PGgN8kLqR4ems0U/3DeJkmiE31749vYWHvBOtR+37qDCZQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</object>

<script>
	
	var delordhd = {
		@foreach($delordhd as $key => $val) 
			'{{$key}}' : '{{$val}}',
		@endforeach 
	};

	var delorddt=[
		@foreach($delorddt as $key => $dodt)
		[
			@foreach($dodt as $key2 => $val)
				{'{{$key2}}' : `{!!str_replace('`', '', $val)!!}`},
			@endforeach
		],
		@endforeach 
	];

	var company = {
		@foreach($company as $key => $val) 
			'{{$key}}' : '{{$val}}',
		@endforeach 
	};

    var totamt_eng = '{{$totamt_eng}}';
	var total_amt = '{{$total_amt}}';
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
                    text: 'DELIVERY ORDER',
                    style: 'header',
                    alignment: 'LEFT'
				},
                {
                    image: 'letterhead',width:300, height:65, style: 'tableHeader', colSpan: 5, alignment: 'center'
                },
                {
                    style: 'tableExample',
                    table: {
                        headerRows: 1,
                        widths: ['*', '*'],//panjang standard dia 515

                        body: [
                            [
								{text: 'Received  Department : {{$delordhd->deldept}}'}, 
								{text: 'Goods Receipt No. : {{$delordhd->docno}}'},
							],
                            [
								{text: 'DO/Inv No. : {{$delordhd->delordno}}'}, 
								{text: 'Purchase Order No. : {{$delordhd->srcdocno}}'},
							],
                            [
								{text: 'Supplier : {{$delordhd->suppcode}}'}, 

								@if(!empty($delordhd->trandate))
								{text: 'Received Date : {{\Carbon\Carbon::createFromFormat('Y-m-d',$delordhd->trandate)->format('d-m-Y')}}'},
								@else
								{text: ''},
								@endif
							],
                            [
								{text: 'Status : {{$delordhd->recstatus}}'}, 

								@if(!empty($delordhd->trantime))
								{text: 'Time : {{\Carbon\Carbon::createFromFormat('H:i:s',$delordhd->trantime)->format('H:i')}}'},
								@else
								{text: ''},
								@endif
							],
                            [
								{text: 'Remarks : {{$delordhd->remarks}}'}, 
								
								@if(!empty($delordhd->deliverydate))
								{text: 'Delivery Date : {{\Carbon\Carbon::createFromFormat('Y-m-d',$delordhd->deliverydate)->format('d-m-Y')}}'},
								@else
								{text: ''},
								@endif
							],
                            [
								{text: ''}, 
								{text: 'Checked Date : '},
							],
                        ]
                    },
			        layout: 'noBorders',
		        },

                // {canvas: [{ type: 'line', x1: 0, y1: 5, x2: 515, y2: 5, lineWidth: 0.5 }]},

                {
                    style: 'tableDetail',
                    table: {
                        headerRows: 1,
                        widths: [20,40,20,14,21,40,40,40,40,40,70],//panjang standard dia 515

                        body: [
                            [
								{text: 'Type', style: 'tableHeader'}, 
								{text: 'Item', style: 'tableHeader'}, 
								{text: 'UOM', style: 'tableHeader'}, 
								{text: 'Qty', style: 'tableHeader'},
								{text: 'Tax\nCode', style: 'tableHeader'}, 
								{text: 'Unit\nPrice', style: 'tableHeader'}, 
								{text: 'Disc\nAmount', style: 'tableHeader'}, 
                                {text: 'Amount', style: 'tableHeader'}, 
								{text: 'Tax\nAmount', style: 'tableHeader'}, 
								{text: 'Net\nAmount (RM)', style: 'tableHeader'}, 
                                {text: 'Expiry\nDate', style: 'tableHeader'}, 
							],

							@foreach ($delorddt as $obj)
							[
								
								{text:'{{$obj->pricecode}}'},
								{text:`{{$obj->itemcode}}\n{!!str_replace('`', '', $obj->description)!!}`},
								{text:`{!!$obj->uomcode!!}`},
                                {text:'{{$obj->qtydelivered}}'},
								{text:'{{$obj->taxcode}}'},
								{text:'{{number_format($obj->unitprice,2)}}', alignment: 'right'},
                                {text:'{{number_format($obj->amtdisc,2)}}', alignment: 'right'},
								{text:'{{number_format($obj->amount,2)}}', alignment: 'right'},
								{text:'{{number_format($obj->tot_gst,2)}}', alignment: 'right'},
								{text:'{{number_format($obj->totamount,2)}}', alignment: 'right'},
								@if(!empty($obj->expdate))
                                {text:'{{\Carbon\Carbon::createFromFormat('Y-m-d',$obj->expdate)->format('d-m-Y')}}'},
								@else
                                {text:''},
								@endif
							],
							@endforeach
                        ]
                    },
			        layout: 'lightHorizontalLines',
		        },
				{
                    style: 'tableDetail',
                    table: {
                        headerRows: 1,
                        widths: ['*','*','*','*','*','*','*','*','*','*','*'],//panjang standard dia 515

                        body: [
                            [
								{}, 
								{}, 
								{}, 
								{},
								{}, 
								{}, 
								{text: 'Sub Amount: '}, 
                                {text: '{{number_format($total_amt,2)}}', alignment: 'right'}, 
								{text: '{{number_format($total_discamt,2)}}', alignment: 'right'}, 
								{text: '{{number_format($total_amt,2)}}', alignment: 'right'}, 
                                {}, 
							],

							[
								{},
								{},
								{},
                                {},
								{},
								{},
                                {text:'Amount Discount: '},
								{},
								{},
								{text:'{{number_format($total_discamt,2)}}', alignment: 'right'},
                                {},
							],
							
							[
								{},
								{},
								{},
                                {},
								{},
								{},
                                {text:'Total Amount: ', style:'totalbold', fontSize:7.5},
								{},
								{},
								{text:'{{number_format($total_amt,2)}}', alignment: 'right'},
                                {},
							],
                        ]
                    },
			        layout: 'noBorders',
		        },
               
                {
                    text: 'SUMMARY ACCOUNTING ENTRIES\n', fontSize: 14,
		        },
                {
                    style: 'tableDetail',
                    table: {
                        headerRows: 1,
                        widths: ['*','*','*','*','*','*'],//panjang standard dia 515

                        body: [
                            [
								{text: 'CostCode', style: 'tableHeader'}, 
								{text: 'CCDesc', style: 'tableHeader'}, 
								{text: 'AccNo', style: 'tableHeader'}, 
								{text: 'AccDesc', style: 'tableHeader'},
								{text: 'Dr Amount', style: 'tableHeader', alignment: 'right'}, 
								{text: 'Cr Amount', style: 'tableHeader', alignment: 'right'}, 
								
							],

							@foreach ($db_acc as $obj)
							[
								{text:'{{$obj[0]}}'}, //cc
								{text:'{{$obj[1]}}'}, //cc_desc
								{text:'{{$obj[2]}}'}, //acc
                                {text:'{{$obj[3]}}'}, //acc_desc
								{text:'{{number_format($obj[4], 2)}}', alignment: 'right'}, //floatval
								{text:'{{number_format($obj[5], 2)}}', alignment: 'right'}, //0
							],
							@endforeach

							@foreach ($cr_acc as $obj)
							[
								{text:'{{$obj[0]}}'},
								{text:'{{$obj[1]}}'},
								{text:'{{$obj[2]}}'},
                                {text:'{{$obj[3]}}'},
								{text:'{{number_format($obj[4], 2)}}', alignment: 'right'},
								{text:'{{number_format($obj[5], 2)}}', alignment: 'right'},
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
								{text: 'Certified By: \n\n\n\n'}, 
                                {text: 'Received/Accepted By:\n\n\n\n'}, 
							],
                            [
                                {text: '___________________'},
								{text: '___________________'}, 
							],
                            [
								{text: 'Name:', fontSize: 8},
								{text: 'Name:', fontSize: 8},
								
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
				tableExample: {
					fontSize: 8,
					margin: [0, 5, 0, 10]
				},
                tableDetail: {
					fontSize: 7.5,
					margin: [0, 0, 0, 8]
				},
				tableHeader: {
					bold: true,
					fontSize: 9,
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
                    letterhead: {
                        url: '{{asset('/img/MSLetterHead.jpg')}}',
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