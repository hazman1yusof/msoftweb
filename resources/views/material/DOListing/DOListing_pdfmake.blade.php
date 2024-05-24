<!DOCTYPE html>
<html>
    <head>
        <title>DO Listing</title>
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
                            style: 'repeatHeader',
                            table: {
                                headerRows: 1,
                                widths: [40,110,35,35,50,40,50,40,40,60,35,40,40],  //panjang standard dia 515
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
                                            text: 'QTY ORDER', style: 'tableHeader', alignment: 'right'
                                        },
                                        { border: [false, false, false, true],
                                            text: 'QTY\nDELIVERED', style: 'tableHeader', alignment: 'right'
                                        },
                                        { border: [false, false, false, true],
                                            text: 'QTY BAL', style: 'tableHeader', alignment: 'right'
                                        },
                                        { border: [false, false, false, true],
                                            text: 'UNIT PRICE', style: 'tableHeader', alignment: 'right'
                                        },
                                        { border: [false, false, false, true],
                                            text: 'PERCENTAGE\nDISC (%)', style: 'tableHeader', alignment: 'right'
                                        },
                                        { border: [false, false, false, true],
                                            text: 'DISC PER UNIT', style: 'tableHeader', alignment: 'right'
                                        },
                                        { border: [false, false, false, true],
                                            text: 'TOTAL GST', style: 'tableHeader', alignment: 'right'
                                        },
                                        { border: [false, false, false, true],
                                            text: 'TOTAL AMOUNT', style: 'tableHeader', alignment: 'right'
                                        },
                                    ],
                                ]
                            },
                            defaultBorder: false,
                        }

                    if(currentPage == 1){
                        var logohdr = {image: 'letterhead', width: 200, height: 40, style: 'tableHeader', alignment: 'center',margin: [30, 20, 0, 15]};
                        retval.push(logohdr);
                    }else{
                        retval.push(header_tbl);
                    }
                    return retval

			    },
                footer: function(currentPage, pageCount) {
                    return [
                        { text: currentPage.toString() + ' of ' + pageCount, alignment: 'center', fontSize: 9}
                    ]
                },
                pageSize: 'A4',
                pageOrientation: 'landscape',
                content: [
                    {text: '\n\nDO LISTING\n', style: 'header', alignment: 'center'},
                    {
                        style: 'staticHeader',
                        table: {
                            headerRows: 1,
                            widths: [40,110,35,35,50,40,50,40,40,60,35,40,40],  //panjang standard dia 515
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
                                        text: 'QTY ORDER', style: 'tableHeader', alignment: 'right'
                                    },
                                    { border: [false, false, false, true],
                                        text: 'QTY\nDELIVERED', style: 'tableHeader', alignment: 'right'
                                    },
                                    { border: [false, false, false, true],
                                        text: 'QTY BAL', style: 'tableHeader', alignment: 'right'
                                    },
                                    { border: [false, false, false, true],
                                        text: 'UNIT PRICE', style: 'tableHeader', alignment: 'right'
                                    },
                                    { border: [false, false, false, true],
                                        text: 'PERCENTAGE\nDISC (%)', style: 'tableHeader', alignment: 'right'
                                    },
                                    { border: [false, false, false, true],
                                        text: 'DISC PER UNIT', style: 'tableHeader', alignment: 'right'
                                    },
                                    { border: [false, false, false, true],
                                        text: 'TOTAL GST', style: 'tableHeader', alignment: 'right'
                                    },
                                    { border: [false, false, false, true],
                                        text: 'TOTAL AMOUNT', style: 'tableHeader', alignment: 'right'
                                    },
                                ],
                            ]
                        },
                        defaultBorder: false,
                    },
                    
                    @foreach ($DOListing as $dolisting) 
                        {
                            style: 'tableExampleHeader',
                            table: {
                                dontBreakRows: true,
                                widths: ['*','*','*','*','*'],  //panjang standard dia 515
                                body: [
                                    @foreach ($delordhd as $obj)
                                        @if($obj->recno == $dolisting->recno)
                                            [
                                                {text: `PURCHASE DEPT : {!!str_replace('`', '', $obj->prdept)!!}\n {!!str_replace('`', '', $obj->dept_desc)!!}`, style: 'tableHeader'},
                                                {text: `DELIVERY DEPT : {!!str_replace('`', '', $obj->deldept)!!}\n {!!str_replace('`', '', $obj->dd_desc)!!}`, style: 'tableHeader'},

                                                @if(!empty($obj->reqdept))
                                                    {text: `REQUEST DEPT : {!!str_replace('`', '', $obj->reqdept)!!}\n {!!str_replace('`', '', $obj->rq_desc)!!}`, style: 'tableHeader'},
                                                @else
                                                    {text: 'REQUEST DEPT : - ', style: 'tableHeader'},
                                                @endif

                                                {text: 'DO NO : {{$obj->delordno}}', style: 'tableHeader'},
                                                {text: 'GRN NO : {{str_pad($obj->docno, 7, "0", STR_PAD_LEFT)}}', style: 'tableHeader'},
                                            ],
                                            [
                                                {text: 'REC DATE : {{\Carbon\Carbon::createFromFormat('Y-m-d',$obj->trandate)->format('d-m-Y')}}', style: 'tableHeader'},
                                                {text: `SUPPLIER CODE : {!!str_replace('`', '', $obj->suppcode)!!}\n {!!str_replace('`', '', $obj->supp_name)!!}`, style: 'tableHeader'},

                                                @if(!empty($obj->srcdocno))
                                                    {text: 'PO NO : {{str_pad($obj->srcdocno, 7, "0", STR_PAD_LEFT)}}', style: 'tableHeader'},
                                                @else
                                                    {text: 'PO NO : - ', style: 'tableHeader'},
                                                @endif

                                                @if(!empty($obj->invoiceno))
                                                    {text: 'INVOICE NO : {{$obj->invoiceno}}', style: 'tableHeader'},
                                                @else
                                                    {text: 'INVOICE NO : - ', style: 'tableHeader'},
                                                @endif

                                                {text: 'STATUS : {{$obj->recstatus}}', style: 'tableHeader'},
                                            ],
                                        @endif
                                    @endforeach
                                ]
                            },
                            layout: 'noBorders',
                        },
                        {
                            style: 'tableDetail',
                            table: {
                                dontBreakRows: true,
                                widths: [40,110,35,35,50,40,50,40,40,60,35,40,40],  //panjang standard dia 515
                                body: [
                                    @php($tot = 0)   
                                    @foreach ($delorddt as $obj_dt)
                                        @if($obj_dt->recno == $dolisting->recno)
                                            [
                                                {text: '{{$obj_dt->pricecode}}\n{{$obj_dt->pc_desc}}'},
                                                {text: `{!!str_replace('`', '', $obj_dt->itemcode)!!}\n{!!str_replace('`', '', $obj_dt->description)!!}`},
                                                {text: `{!!$obj_dt->uomcode!!}`},
                                                {text: `{!!$obj_dt->pouom!!}`},
                                                {text: '{{$obj_dt->taxcode}}\n{{$obj_dt->tax_desc}}'},
                                                {text: '{{number_format($obj_dt->qtyorder,2)}}', alignment: 'right' },
                                                {text: '{{number_format($obj_dt->qtydelivered,2)}}', alignment: 'right' },
                                                {text: '{{number_format($obj_dt->qtyoutstand,2)}}', alignment: 'right' },
                                                {text: '{{number_format($obj_dt->unitprice,2)}}', alignment: 'right' },
                                                {text: '{{number_format($obj_dt->perdisc,2)}}', alignment: 'right' },
                                                {text: '{{number_format($obj_dt->amtdisc,2)}}', alignment: 'right' },
                                                {text: '{{number_format($obj_dt->tot_gst,2)}}', alignment: 'right' },
                                                {text: '{{number_format($obj_dt->totamount,2)}}', alignment: 'right' },
                                            ],
                                        @php($tot += $obj_dt->totamount)
                                        @endif
                                    @endforeach
                                    [
                                        {},
                                        {},
                                        {},
                                        {},
                                        {},
                                        {},
                                        {},
                                        {},
                                        {},
                                        {},
                                        {text: 'TOTAL :', style: 'tableHeader'},
                                        {},
                                        {text: '{{number_format($tot,2)}}', alignment: 'right', style: 'tableHeader'},
                                    ],
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
                        margin: [0, 0, 0, 20]
                    },
                    tableExample: {
                        fontSize: 8,
                        margin: [0, 5, 0, 15]
                    },
                    tableExampleHeader: {
                        fontSize: 8,
                        margin: [0, 5, 0, 15]
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
                    repeatHeader: {
                        fontSize: 8,
                        margin: [40, 15, 10, 15]//l,t,r,b
                    },
                    staticHeader: {
                        fontSize: 8,
                        margin: [0, 5, 10, 10]
                    },
                    tableDetail: {
                        fontSize: 8,
                        margin: [5, 5, 0, 15]
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
                }
            };
            
            // pdfMake.createPdf(docDefinition).getBase64(function(data) {
            //     var base64data = "data:base64"+data;
            //     console.log($('object#pdfPreview').attr('data',base64data));
            //     // document.getElementById('pdfPreview').data = base64data;
            // });
            
            pdfMake.createPdf(docDefinition).getDataUrl(function(dataURL) {
                $('#pdfiframe').attr('src',dataURL);
            });
        });
        
        function make_header(){
            
        }
        
        // pdfMake.createPdf(docDefinition).getDataUrl(function(dataURL) {
        //     console.log(dataURL);
        //     document.getElementById('pdfPreview').data = dataURL;
        // });
        
        // jsreport.serverUrl = 'http://localhost:5488'
        // async function preview() {
        //     const report = await jsreport.render({
        //         template: {
        //             name: 'mc'
        //         },
        //         data: mydata
        //     });
        //     document.getElementById('pdfPreview').data = await report.toObjectURL()
        // }
        
        // preview().catch(console.error)
        
    </script>
    
    <body style="margin: 0px;">
        <iframe id="pdfiframe" width="100%" height="100%" src="" frameborder="0" style="width: 99vw;height: 99vh;"></iframe>
    </body>
</html>