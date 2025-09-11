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

	$(document).ready(function () {
		var docDefinition = {
			footer: function(currentPage, pageCount) {
				return [
			      { text: 'This is computer-generated document. No signature is required.',italics: true, alignment: 'center',fontSize: 9 },
			      { text: currentPage.toString() + ' of ' + pageCount, alignment: 'center',fontSize: 9 }
			    ]
			},
			pageSize: 'A4',
			pageMargins: [10, 20, 20, 30],
		  	content: [
                {
                    image: 'letterhead',width:175, style: 'tableHeader', colSpan: 5, alignment: 'center'
                },
				{
                    text: "{{$title}}",
                    style: 'header',
                    alignment: 'center'
				},
                {
                    style: 'tableExample',
                    table: {
                        headerRows: 1,
                        widths: [80, '*',80,94,'*'],//panjang standard dia 515

                        body: [
                            [
								{text: 'Received  Department '}, 
								{text: ': {{$delordhd->deldept}}'},{},
								{text: 'Goods Received Note No. '},
								{text: ': {{str_pad($delordhd->docno, 7, "0", STR_PAD_LEFT)}}'}
							],
                            [
								{text: 'DO/Inv No. '}, 
								{text: ': {{$delordhd->delordno}}'},{},
								{text: 'Purchase Order No. '},
								{text: ': @if(!empty($delordhd->srcdocno)){{str_pad($delordhd->srcdocno, 7, "0", STR_PAD_LEFT)}}@else{{"-"}}@endif'}
							],
                            [
								{text: 'Supplier '}, 
								{text: ': {{$delordhd->suppcode}}'},{},

								@if(!empty($delordhd->trandate))
								{text: 'Received Date '},
								@else
								{text: ''},
								@endif

								@if(!empty($delordhd->trandate))
								{text: ': {{\Carbon\Carbon::createFromFormat('Y-m-d',$delordhd->trandate)->format('d-m-Y')}}'},
								@else
								{text: ''},
								@endif
							],
                            [
								{text: 'Status '}, 
								{text: ': {{$delordhd->recstatus}}'},{},

								@if(!empty($delordhd->trantime))
								{text: 'Time '},
								@else
								{text: ''},
								@endif

								@if(!empty($delordhd->trantime))
								{text: ': {{\Carbon\Carbon::createFromFormat('H:i:s',$delordhd->trantime)->format('H:i')}}'},
								@else
								{text: ''},
								@endif
							],
                            [
								{text: 'Remarks '}, 
								{text: `: {!!str_replace('`', '', $delordhd->remarks)!!}`},{},
								
								@if(!empty($delordhd->deliverydate))
								{text: 'Delivery Date'},
								@else
								{text: ''},
								@endif
								
								@if(!empty($delordhd->deliverydate))
								{text: ': {{\Carbon\Carbon::createFromFormat('Y-m-d',$delordhd->deliverydate)->format('d-m-Y')}}'},
								@else
								{text: ''},
								@endif
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
                        // widths: [80,25,30,23,30,40,40,40,40,40,30],//panjang standard dia 515
                        widths: [140,30,30,25,40,40,45,40,60],

                        body: [
                            [
								{text: 'Item', style: 'tableHeader'}, 
								{text: 'UOM', style: 'tableHeader'}, 
								{text: 'Qty', style: 'tableHeader'},
								{text: 'Tax\nCode', style: 'tableHeader'}, 
								{text: 'Unit\nPrice', style: 'tableHeader', alignment: 'right'}, 
								// {text: 'Disc\nAmount', style: 'tableHeader', alignment: 'right'}, 
                                {text: 'Amount', style: 'tableHeader', alignment: 'right'}, 
								// {text: 'Tax\nAmount', style: 'tableHeader', alignment: 'right'}, 
								{text: 'Net\nAmount (RM)', style: 'tableHeader', alignment: 'right'}, 
                                {text: 'Expiry\nDate', style: 'tableHeader'},  
                                {text: 'Batch No', style: 'tableHeader'}, 

							],

							@foreach ($delorddt as $obj)
							[
								
								{text:`{{$obj->itemcode}}\n{!!str_replace('`', '', $obj->description)!!}\n{{$obj->kkmappno}}`},
								{text:`{!!$obj->uomcode!!}`},
                                {text:'{{$obj->qtydelivered}}'},
								{text:'{{$obj->taxcode}}'},
								{text:'{{number_format($obj->unitprice,2)}}', alignment: 'right'},
								{text:'{{number_format($obj->amount,2)}}', alignment: 'right'},
								{text:'{{number_format($obj->totamount,2)}}', alignment: 'right'},
								@if(!empty($obj->expdate))
                                {text:'{{$obj->expdate}}'},
								@else
                                {text:''},
								@endif
                                {text:'{{$obj->batchno}}'},
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
                    text: 'SUMMARY ACCOUNTING ENTRIES\n', fontSize: 12,
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
								{text: 'Certified By: \n\n'}, 
                                {text: 'Received/Accepted By:\n\n'}, 
							],
                            [
								{text: '{{$delordhd->respersonid}}', fontSize: 8, bold:true},
								{text: '{{$delordhd->postedby_name}}', fontSize: 8, bold:true},
								
							],
                        ]
                    },
			        layout: 'noBorders',
		        },
			],
			styles: {
				header: {
					fontSize: 12,
					bold: true,
					margin: [0, 10, 0, 0]
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
					margin: [0, 0, 0, 0],
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
                        url: '{{asset('/img/letterheadukm.png')}}',
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