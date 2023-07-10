<!DOCTYPE html>
<html>
<head>
<title>Inventory Transaction</title>

</head>

<!-- <script src="https://unpkg.com/@jsreport/browser-client/dist/jsreport.umd.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="mydata.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha512-a9NgEEK7tsCvABL7KqtUTQjl69z7091EVPpw5KxPlZ93T141ffe1woLtbXTX+r2/8TtTvRX/v4zTL2UlMUPgwg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.min.js" integrity="sha512-P0bOMePRS378NwmPDVPU455C/TuxDS+8QwJozdc7PGgN8kLqR4ems0U/3DeJkmiE31749vYWHvBOtR+37qDCZQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</object>

<script>
	
	var ivtmphd = {
		@foreach($ivtmphd as $key => $val) 
			'{{$key}}' : '{{$val}}',
		@endforeach 
	};

	var ivtmpdt=[
		@foreach($ivtmpdt as $key => $ivdt)
		[
			@foreach($ivdt as $key2 => $val)
				{'{{$key2}}' : `{{$val}}`},
			@endforeach
		],
		@endforeach 
	];

	var company = {
		@foreach($company as $key => $val) 
			'{{$key}}' : '{{$val}}',
		@endforeach 
	};


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
                    text: 'INVENTORY TRANSACTION',
                    style: 'header',
                    alignment: 'LEFT'
				},
                {
                    image: 'letterhead',width:175, height:65, style: 'tableHeader', colSpan: 5, alignment: 'center'
                },
                // {
                //     text: '{{$company->name}}\n{{$company->address1}}\n{{$company->address2}}\n{{$company->address3}}\n{{$company->address4}}\n\n\n', 
                //     alignment: 'center',
                //     style: 'comp_header'
                // },
            
                {
                    style: 'tableExample',
                    table: {
                        headerRows: 1,
                        widths: ['*', '*'],//panjang standard dia 515

                        body: [
                            [
								{text: 'Department : {{$ivtmphd->txndept}}'}, 
								{text: 'Document  No. : {{$ivtmphd->docno}}'},
							],
                            [
								{text: 'Type : {{$ivtmphd->sndrcvtype}}'}, 
								{text: 'TranType : {{$ivtmphd->trantype}}'},
							],
                            [
								{text: 'Sender/Receiver Name : {{$ivtmphd->sndrcv}}'}, 
								{text: 'Sender/Receiver Code : '},
							],
                            [
								{text: 'Date : {{\Carbon\Carbon::createFromFormat('Y-m-d',$ivtmphd->trandate)->format('d-m-Y')}}'}, 
								{text: 'Time : {{\Carbon\Carbon::createFromFormat('H:i:s',$ivtmphd->trantime)->format('H:i')}}'},
							],
                            [
								{text: 'Status : {{$ivtmphd->recstatus}}'}, 
								{text: 'Request No. : {{$ivtmphd->srcdocno}}'},
							],
                            [
								{text: 'Remarks: {{$ivtmphd->remarks}}'}, 
                                {}, 
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
                        widths: ['*','*','*','*','*','*','*','*'],//panjang standard dia 515

                        body: [
                            [
								{text: 'Line No', style: 'tableHeader'}, 
								{text: 'Itemcode', style: 'tableHeader'}, 
								{text: 'UOM', style: 'tableHeader'}, 
								{text: 'Qty', style: 'tableHeader'},
								{text: 'Amount', style: 'tableHeader'}, 
                                {text: 'Expiry\nDate', style: 'tableHeader'}, 
                                {text: 'Tran\nDate', style: 'tableHeader'}, 
								{text: 'Current\nQOH', style: 'tableHeader'}, 
							
							],

							@foreach ($ivtmpdt as $obj)
							[
								
								{text:'{{$obj->lineno_}}'},
								{text:'{{$obj->itemcode}}\n{{$obj->description}}'},
								{text:'{{$obj->uomcode}}'},
                                {text:'{{$obj->qtyonhand}}'},
								{text:'{{number_format($obj->amount,2)}}', alignment: 'right'},
                                {text:'@if(!empty($obj->expdate)){{\Carbon\Carbon::createFromFormat('Y-m-d',$obj->expdate)->format('d-m-Y')}}@endif'},
                                {text:'@if(!empty($obj->trandate)){{\Carbon\Carbon::createFromFormat('Y-m-d',$obj->trandate)->format('d-m-Y')}}@endif'},
                                {text:'{{$obj->qtyonhand}}'},
								
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
                        widths: ['*'],//panjang standard dia 515

                        body: [
                            [
								{text: 'Total Amount: ', fontSize: 9}, 
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
								{text: 'Costcode', style: 'tableHeader'}, 
								{text: 'CCDesc', style: 'tableHeader'}, 
								{text: 'AccNo', style: 'tableHeader'}, 
								{text: 'AccDesc', style: 'tableHeader'},
								{text: 'Dr Amount', style: 'tableHeader'}, 
								{text: 'Cr Amount', style: 'tableHeader'}, 
								
							],

							@foreach ($db_acc as $obj)
							[
								{text:'{{$obj[0]}}'},
								{text:'{{$obj[1]}}'},
								{text:'{{$obj[2]}}'},
                                {text:'{{$obj[3]}}'},
								{text:'{{$obj[4]}}'},
								{text:'{{$obj[5]}}'},
							],
							@endforeach

							@foreach ($cr_acc as $obj)
							[
								{text:'{{$obj[0]}}'},
								{text:'{{$obj[1]}}'},
								{text:'{{$obj[2]}}'},
                                {text:'{{$obj[3]}}'},
								{text:'{{$obj[4]}}'},
								{text:'{{$obj[5]}}'},
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
								{text: 'Prepared By & Date: \n\n\n\n'}, 
                                {text: 'Received By & Date:\n\n\n\n'}, 
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
				  url: 'http://msoftweb.test:8443/img/MSLetterHead.jpg',
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