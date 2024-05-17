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
                footer: function(currentPage, pageCount) {
                    return [
                        { text: currentPage.toString() + ' of ' + pageCount, alignment: 'center' }
                    ]
                },
                pageSize: 'A4',
                pageOrientation: 'landscape',
                content: [
                    {
                        image: 'letterhead', width: 200, height: 40, style: 'tableHeader', colSpan: 5, alignment: 'center'
                    },
                    {
                        text: '\nDO LISTING\n',
                        style: 'header',
                        alignment: 'center',
                    },
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: ['*', '*'], //panjang standard dia 515
                            body: [
                                [
                                    {text: 'DATE : {{\Carbon\Carbon::now()->format('d/m/Y')}}' },
                                    {text: 'TIME : {{\Carbon\Carbon::now("Asia/Kuala_Lumpur")->format('h:i A')}}'},
                                ],
                            ]
                        },
                        layout: 'noBorders',
                    },
                    @foreach ($DOListing as $dolisting) 
                    { text: 'REC NO : {{$dolisting->recno}}', alignment: 'left', fontSize: 9, bold: true },

                        {
                            style: 'tableExampleHeader',
                            table: {
                                headerRows: 1,
                                widths: ['*','*','*','*','*','*',150,'*','*','*'],  //panjang standard dia 515
                                body: [
                                    [
                                        {text: 'PURCHASE DEPT', style: 'tableHeader'},
                                        {text: 'DELIVERY DEPT', style: 'tableHeader'},
                                        {text: 'REQ DEPT', style: 'tableHeader'},
                                        {text: 'DO NO', style: 'tableHeader'},
                                        {text: 'GRN NO', style: 'tableHeader', alignment: 'right'},
                                        {text: 'REC DATE', style: 'tableHeader'},
                                        {text: 'SUPPLIER CODE', style: 'tableHeader'},
                                        {text: 'PO NO', style: 'tableHeader'},
                                        {text: 'INVOICE NO', style: 'tableHeader'},
                                        {text: 'TOTAL AMOUNT', style: 'tableHeader', alignment: 'right'},
                                    ],
                                    @foreach ($delordhd as $obj)
                                        @if($obj->recno == $dolisting->recno)
                                            [
                                                {text: '{{$obj->prdept}}'},
                                                {text: '{{$obj->deldept}}'},
                                                {text: '{{$obj->reqdept}}'},
                                                {text: '{{$obj->delordno}}'},
                                                {text: '{{str_pad($obj->docno, 7, "0", STR_PAD_LEFT)}}'},
                                                {text: '{{\Carbon\Carbon::createFromFormat('Y-m-d',$obj->trandate)->format('d-m-Y')}}'},
                                                {text: `{!!str_replace('`', '', $obj->suppcode)!!}\n{!!str_replace('`', '', $obj->supp_name)!!}`},
                                                {text: '{{$obj->srcdocno}}'},
                                                {text: '{{$obj->invoiceno}}'},
                                                {text: '{{number_format($obj->totamount,2)}}', alignment: 'right' },
                                            ],
                                        @endif
                                    @endforeach
                                ]
                            },
                            
                            layout: 'lightHorizontalLines',
                        },
                        {
                            style: 'tableExample',
                            table: {
                                headerRows: 1,
                                widths: [30,110,35,35,35,35,35,35,35,60,35,35,40],  //panjang standard dia 515
                                body: [
                                    [
                                        {text: 'PRICE CODE', style: 'tableHeader'},
                                        {text: 'ITEM CODE', style: 'tableHeader'},
                                        {text: 'UOM CODE', style: 'tableHeader'},
                                        {text: 'PO\nUOM', style: 'tableHeader'},
                                        {text: 'TAX CODE', style: 'tableHeader'},
                                        {text: 'QTY ORDER', style: 'tableHeader', alignment: 'right'},
                                        {text: 'QTY\nDELIVERED', style: 'tableHeader', alignment: 'right'},
                                        {text: 'QTY BAL', style: 'tableHeader', alignment: 'right'},
                                        {text: 'UNIT PRICE', style: 'tableHeader', alignment: 'right'},
                                        {text: 'PERCENTAGE\nDISC (%)', style: 'tableHeader', alignment: 'right'},
                                        {text: 'DISC PER UNIT', style: 'tableHeader', alignment: 'right'},
                                        {text: 'TOTAL GST', style: 'tableHeader', alignment: 'right'},
                                        {text: 'TOTAL AMOUNT', style: 'tableHeader', alignment: 'right'},
                                    ],
                                    @foreach ($delorddt as $obj_dt)
                                        @if($obj_dt->recno == $dolisting->recno)
                                            [
                                                {text: '{{$obj_dt->pricecode}}'},
                                                {text: `{!!str_replace('`', '', $obj_dt->itemcode)!!}\n{!!str_replace('`', '', $obj_dt->description)!!}`},
                                                {text: `{!!$obj_dt->uomcode!!}`},
                                                {text: `{!!$obj_dt->pouom!!}`},
                                                {text: '{{$obj_dt->taxcode}}'},
                                                {text: '{{number_format($obj_dt->qtyorder,2)}}', alignment: 'right' },
                                                {text: '{{number_format($obj_dt->qtydelivered,2)}}', alignment: 'right' },
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
                            layout: 'lightHorizontalLines',
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
                        fontSize: 9,
                        margin: [0, 5, 0, 15]
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