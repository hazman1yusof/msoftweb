<!DOCTYPE html>
<html>
    <head>
        <title>Stock In Transit Report</title>
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
                        text: '\nSTOCK IN TRANSIT REPORT\n',
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
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: [42,35,35,37,110,30,30,30,30,30,30,30,34,42,30],  //panjang standard dia 515
                            body: [
                                [
                                    {text: 'TRAN\nDATE', style: 'tableHeader'},
                                    {text: 'DOC NO', style: 'tableHeader'},
                                    {text: 'TRAN\nDEPT', style: 'tableHeader'},
                                    {text: 'SENDER/\nRECEIVER', style: 'tableHeader'},
                                    {text: 'ITEMCODE', style: 'tableHeader'},
                                    {text: 'UOM CODE\nTRAN DEPT', style: 'tableHeader'},
                                    {text: 'QOH\nTRAN DEPT', style: 'tableHeader', alignment: 'right'},
                                    {text: 'UOM CODE\nRECV DEPT', style: 'tableHeader'},
                                    {text: 'QOH\nRECV DEPT', style: 'tableHeader', alignment: 'right'},
                                    {text: 'TRAN QTY', style: 'tableHeader', alignment: 'right'},
                                    {text: 'QTY REQ', style: 'tableHeader', alignment: 'right'},
                                    {text: 'NET\nPRICE', style: 'tableHeader', alignment: 'right'},
                                    {text: 'AMOUNT', style: 'tableHeader', alignment: 'right'},
                                    {text: 'EXP DATE', style: 'tableHeader', alignment: 'left'},
                                    {text: 'BATCH NO', style: 'tableHeader', alignment: 'left'},
                                ],
                                @foreach ($ivtxn as $obj)
                                    [
                                        {text: '{{\Carbon\Carbon::createFromFormat('Y-m-d',$obj->trandate)->format('d-m-Y')}}'},
                                        {text: '{{str_pad($obj->docno, 7, "0", STR_PAD_LEFT)}}'},
                                        {text: '{{$obj->txndept}}'},
                                        {text: '{{$obj->sndrcv}}'},
                                        {text: `{!!str_replace('`', '', $obj->itemcode)!!}\n{!!str_replace('`', '', $obj->description)!!}`},
                                        {text: `{!!$obj->uomcode!!}`},
                                        {text: '{{$obj->qtyonhand}}', alignment: 'right'},
                                        {text: `{!!$obj->uomcoderecv!!}`},
                                        {text: '{{$obj->qtyonhandrecv}}', alignment: 'right'},
                                        {text: '{{number_format($obj->txnqty,2)}}', alignment: 'right'},
                                        {text: '{{number_format($obj->qtyrequest,2)}}', alignment: 'right'},
                                        {text: '{{number_format($obj->netprice,2)}}', alignment: 'right' },
                                        {text: '{{number_format($obj->amount,2)}}', alignment: 'right'},
                                        {text: '{{\Carbon\Carbon::createFromFormat('Y-m-d',$obj->expdate)->format('d-m-Y')}}'},
                                        {text: '{{$obj->batchno}}'},
                                    ],
                                @endforeach
                            ]
                        },
                        layout: 'lightHorizontalLines',
                    },

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