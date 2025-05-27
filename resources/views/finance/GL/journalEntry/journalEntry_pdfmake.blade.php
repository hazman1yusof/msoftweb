<!DOCTYPE html>
<html>
<head>
<title>JOURNAL ENTRY LISTING</title>

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
			pageMargins: [20, 20, 20, 30],
		  	content: [
                {
                    image: 'letterhead',width:175, style: 'tableHeader', colSpan: 5, alignment: 'center'
                },
				{
                    text: 'JOURNAL ENTRY LISTING',
                    style: 'header',
                    alignment: 'center'
				},
                {
                    style: 'tableExample',
                    table: {
                        headerRows: 1,
                        widths: [80,'*',80,94,'*'],//panjang standard dia 515

                        body: [
                            [
								{text: 'Doc No '}, 
								{text: ': {{str_pad($gljnlhdr->auditno, 7, "0", STR_PAD_LEFT)}}'},{},
								{text: 'Date '}, 
								{text: ": {{\Carbon\Carbon::createFromFormat('Y-m-d',$gljnlhdr->docdate)->format('d-m-Y')}}"},
							],
                            [
								{text: 'Period'}, 
								{text: ': {{$gljnlhdr->period}}'},{},
								{text: 'Year'},
								{text: ': {{$gljnlhdr->year}}'}
							],
                        ]
                    },
			        layout: 'noBorders',
		        },
				{
                    text:  `{!!$gljnlhdr->description!!}`,
					fontSize: 9,
					margin: [0,10,0,5],
                    alignment: 'center'
				},

                // {canvas: [{ type: 'line', x1: 0, y1: 5, x2: 515, y2: 5, lineWidth: 0.5 }]},

                {
                    style: 'tableDetail',
                    table: {
                        headerRows: 1,
                        // widths: [80,25,30,23,30,40,40,40,40,40,30],//panjang standard dia 515
                        widths: [12,35,45,150,60,60,40,40],

                        body: [
                            [
								{text: 'No', style: 'tableHeader'}, 
								{text: 'Cost Code', style: 'tableHeader'}, 
								{text: 'Account', style: 'tableHeader'},
								{text: 'Description', style: 'tableHeader'}, 
								{text: 'Amount\nDR', style: 'tableHeader', alignment: 'right'}, 
								{text: 'Amount\nCR', style: 'tableHeader', alignment: 'right'}, 
								{text: 'Added\nBy', style: 'tableHeader'}, 
								{text: 'Posted\nBy', style: 'tableHeader'}, 

							],

							@php($line=1)
							@php($amtdr=0)
							@php($amtcr=0)
							@foreach ($gljnldtl as $obj)
							[
								
								{text:`{{$line}}`},
								{text:`{{$obj->costcode}}`},
                                {text:'{{$obj->glaccount}}'},
                                {text:`{!!$obj->description!!}`},

                                @if($obj->drcrsign == 'DR')
								@php($amtdr+=$obj->amount)
								{text:'{{number_format($obj->amount,2)}}', alignment: 'right'},
								{text:'', alignment: 'right'},
                                @else
								@php($amtcr+=$obj->amount)
								{text:'', alignment: 'right'},
								{text:'{{number_format($obj->amount,2)}}', alignment: 'right'},
                                @endif

                                {text:'{{$obj->adduser}}'},
                                {text:'{{$obj->lastuser}}'},
							],

							@php($line+=1)
							@endforeach


                            [
								{text: '', style: 'tableHeader'}, 
								{text: '', style: 'tableHeader'}, 
								{text: '', style: 'tableHeader'},
								{text: 'Total', style: 'tableHeader'}, 
								{text: '{{number_format($amtdr,2)}}', alignment: 'right'}, 
								{text: '{{number_format($amtcr,2)}}', alignment: 'right'}, 
								{text: '', style: 'tableHeader'}, 
								{text: '', style: 'tableHeader'}, 

							]
                        ]
                    },
			        layout: 'lightHorizontalLines',
		        },
                {
                    text: 'SUMMARY ACCOUNTING ENTRIES\n', fontSize: 8, bold:true,
					margin: [0,10,0,5],
		        },
                {
                    style: 'tableDetail',
                    table: {
                        headerRows: 1,
                        widths: [40,150,50,50],//panjang standard dia 515

                        body: [

							@foreach ($summ_acc as $acc)
							[
								{text:'{{$acc->glaccount}}'}, //cc
								{text:`{!!$acc->description!!}`}, //cc_desc
								@if($acc->drcrsign == 'DR')
								{text:'{{number_format($acc->amount_add,2)}}', alignment: 'right'},
								{text:'', alignment: 'right'},
                                @else
								{text:'', alignment: 'right'},
								{text:'{{number_format($acc->amount_add,2)}}', alignment: 'right'},
                                @endif
							],
							@endforeach
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