<!DOCTYPE html>
<html>
<head>
<title>PO Listing</title>

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
			header: function(currentPage, pageCount, pageSize) {
				var retval=[];
				var header_tbl = {
	                    style: 'headerPage',
	                    table: {
	                        headerRows: 1,
                            widths: [40,110,35,35,50,40,40,40,40,60,35,40,40],  //panjang standard dia 515
	                        body: [
	                            [
                                    { border: [false, false, false, true],
                                        text: 'PRICE CODE', style: 'tableHeader',
                                    },
                                    { border: [false, false, false, true],
                                        text: 'ITEM CODE', style: 'tableHeader',
                                    },
                                    { border: [false, false, false, true],
                                        text: 'UOM CODE', style: 'tableHeader',
                                    },
                                    { border: [false, false, false, true],
                                        text: 'PO\nUOM', style: 'tableHeader',
                                    },
                                    { border: [false, false, false, true],
                                        text: 'TAX CODE', style: 'tableHeader',
                                    },
                                    { border: [false, false, false, true],
                                        text: 'QTY REQ', style: 'tableHeader',
                                    },
                                    { border: [false, false, false, true],
                                        text: 'QTY ORDER', style: 'tableHeader',
                                    },
                                    { border: [false, false, false, true],
                                        text: 'QTY BAL', style: 'tableHeader',
                                    },
                                    { border: [false, false, false, true],
                                        text: 'UNIT PRICE', style: 'tableHeader',
                                    },
                                    { border: [false, false, false, true],
                                        text: 'PERCENTAGE\nDISC (%)', style: 'tableHeader',
                                    },
                                    { border: [false, false, false, true],
                                        text: 'DISC PER UNIT', style: 'tableHeader',
                                    },
                                    { border: [false, false, false, true],
                                        text: 'TOTAL GST', style: 'tableHeader',
                                    },
                                    { border: [false, false, false, true],
                                        text: 'TOTAL AMOUNT', style: 'tableHeader',
                                    },
                                ],
	                        ]
	                    },
                        defaultBorder: false,
			        }

				if(currentPage == 1){
					var logohdr = {image: 'letterhead', width: 200, height: 40, style: 'tableHeader', alignment: 'center',margin: [30, 25, 0, 0]};
					var title =  {text: '\nPO LISTING\n',style: 'header',alignment: 'center'};
					retval.push(logohdr);
					retval.push(title);
				}else{
                    retval.push(header_tbl);
				}
				return retval

			},
			footer: function(currentPage, pageCount) {
				if(currentPage == pageCount){
					return [
                        { text: currentPage.toString() + ' of ' + pageCount, alignment: 'center' }
				    ]
				}
			},
			pageSize: 'A4',
            pageOrientation: 'landscape',
		  	content: [
                {
                    style: 'tableExampleHeader',
                    table: {
                        headerRows: 1,
                        widths: [40,110,35,35,50,40,40,40,40,60,35,40,40],  //panjang standard dia 515
                        body: [
                            [
                                { border: [false, false, false, true],
                                    text: 'PRICE CODE', style: 'tableHeader',
                                },
                                { border: [false, false, false, true],
                                    text: 'ITEM CODE', style: 'tableHeader',
                                },
                                { border: [false, false, false, true],
                                    text: 'UOM CODE', style: 'tableHeader',
                                },
                                { border: [false, false, false, true],
                                    text: 'PO\nUOM', style: 'tableHeader',
                                },
                                { border: [false, false, false, true],
                                    text: 'TAX CODE', style: 'tableHeader',
                                },
                                { border: [false, false, false, true],
                                    text: 'QTY REQ', style: 'tableHeader',
                                },
                                { border: [false, false, false, true],
                                    text: 'QTY ORDER', style: 'tableHeader',
                                },
                                { border: [false, false, false, true],
                                    text: 'QTY BAL', style: 'tableHeader',
                                },
                                { border: [false, false, false, true],
                                    text: 'UNIT PRICE', style: 'tableHeader',
                                },
                                { border: [false, false, false, true],
                                    text: 'PERCENTAGE\nDISC (%)', style: 'tableHeader',
                                },
                                { border: [false, false, false, true],
                                    text: 'DISC PER UNIT', style: 'tableHeader',
                                },
                                { border: [false, false, false, true],
                                    text: 'TOTAL GST', style: 'tableHeader',
                                },
                                { border: [false, false, false, true],
                                    text: 'TOTAL AMOUNT', style: 'tableHeader',
                                },

                            ],
                            
                        ]
                    },
                    defaultBorder: false,
                },
            @foreach ($POListing as $polisting) 
                {
                    style: 'tableExample',
                    table: {
                        headerRows: 1,
                    	dontBreakRows: true,
                        widths: ['*','*','*','*', '*','*'],  //panjang standard dia 515
                        body: [
                            @foreach ($purordhd as $obj)
                                @if($obj->recno == $polisting->recno)
                                [
                                    {text: `PURCHASE DEPT : {!!str_replace('`', '', $obj->prdept)!!}\n {!!str_replace('`', '', $obj->dept_desc)!!}`, style: 'tableHeader'},
                                    {text: `DELIVERY DEPT : {!!str_replace('`', '', $obj->deldept)!!}\n {!!str_replace('`', '', $obj->deldept_desc)!!}`, style: 'tableHeader'},
                                    {text: 'PO NO : {{str_pad($obj->purordno, 7, "0", STR_PAD_LEFT)}}', style: 'tableHeader'},
                                    {text: 'PO DATE : {{\Carbon\Carbon::createFromFormat('Y-m-d',$obj->purdate)->format('d-m-Y')}}', style: 'tableHeader'},
                                    {text: `SUPPLIER CODE : {!!str_replace('`', '', $obj->suppcode)!!}\n {!!str_replace('`', '', $obj->supp_name)!!}`, style: 'tableHeader'},
                                    {text: 'STATUS : {{$obj->recstatus}}', style: 'tableHeader'},
                                ],
                                @endif
                            @endforeach
                        ]
                    },
                    layout: 'noBorders',

		        },
                {
                    style: 'tableExample',
                    table: {
                        widths: [40,110,35,35,50,40,40,40,40,60,35,40,40],  //panjang standard dia 515
                        body: [      
                            @foreach ($purorddt as $obj_dt)
                                @if($obj_dt->recno == $polisting->recno)
                                    [
                                        {text: '{{$obj_dt->pricecode}}\n{{$obj_dt->pc_desc}}'},
                                        {text: `{!!str_replace('`', '', $obj_dt->itemcode)!!}\n{!!str_replace('`', '', $obj_dt->description)!!}`},
                                        {text: `{!!$obj_dt->uomcode!!}`},
                                        {text: `{!!$obj_dt->pouom!!}`},
                                        {text: '{{$obj_dt->taxcode}}\n{{$obj_dt->tax_desc}}'},
                                        {text: '{{number_format($obj_dt->qtyrequest,2)}}', alignment: 'right' },
                                        {text: '{{number_format($obj_dt->qtyorder,2)}}', alignment: 'right' },
                                        {text: '{{number_format($obj_dt->qtyoutstand,2)}}', alignment: 'right' },
                                        {text: '{{number_format($obj_dt->unitprice,2)}}', alignment: 'right' },
                                        {text: '{{number_format($obj_dt->perdisc,2)}}', alignment: 'right' },
                                        {text: '{{number_format($obj_dt->amtdisc,2)}}', alignment: 'right' },
                                        {text: '{{number_format($obj_dt->tot_gst,2)}}', alignment: 'right' },
                                        {text: '{{number_format($obj_dt->totamount,2)}}', alignment: 'right' },
                                    ],
                                @endif
                            @endforeach
                        ]
                    },
                    layout: 'noBorders',
                },
            @endforeach
			],

			styles: {
                header: {
                    fontSize: 14,
                    bold: true,
                    margin: [0, 0, 0, 10]
                },
                header1: {
                    fontSize: 14,
                    bold: true,
                    margin: [60, 0, 0, 10]
                },
                subheader: {
                    fontSize: 16,
                    bold: true,
                    margin: [0, 10, 0, 5]
                },
                tableExample: {
                    fontSize: 8,
                    margin: [0, 5, 0, 15]
                },
                tableExampleHeader: {
                    fontSize: 8,
                    margin: [0, 35, 10, 15]
                },
                tableHeader: {
                    bold: true,
                    fontSize: 8,
                    color: 'black'
                },
                totalbold: {
                    bold: true,
                    fontSize: 10,
                },
                comp_header: {
                    bold: true,
                    fontSize: 8,
                },
                headerPage: {
                    fontSize: 8,
                    margin: [40, 15, 10, 15]//l,t,r,b
                },
			},
			images: {
                letterhead: {
                    url: "{{asset('/img/MSLetterHead.jpg')}}",
                    headers: {
                        myheader: '123',
                        myotherheader: 'abc',
                    }
                }
            },
		};

		pdfMake.createPdf(docDefinition).getDataUrl(function(dataURL) {
			$('#pdfiframe').attr('src',dataURL);
		});
	});

</script>

    <body style="margin: 0px;">
        <iframe id="pdfiframe" width="100%" height="100%" src="" frameborder="0" style="width: 99vw;height: 99vh;"></iframe>
    </body>
</html>