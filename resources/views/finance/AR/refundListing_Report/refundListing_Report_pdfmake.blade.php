<!DOCTYPE html>
<html>
    <head>
        <title>Refund Listing</title>
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
                content: [
                    {
                        image: 'letterhead',width:175, height:65, style: 'tableHeader', colSpan: 5, alignment: 'center'
                    },
                    {
                        text: 'REFUND LISTING\n',
                        style: 'header',
                        alignment: 'center'
                    },
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: ['*', '*'], //panjang standard dia 515
                            body: [
                                [
                                    {text: 'DATE : {{\Carbon\Carbon::now("Asia/Kuala_Lumpur")->format('d/m/Y')}}' },
                                    {text: 'TIME : {{\Carbon\Carbon::now("Asia/Kuala_Lumpur")->format('h:i A')}}'},
                                ],
                            ]
                        },
                        layout: 'noBorders',
                    },


                    @foreach ($paymode as $pmode)
                    
                    { text: 'Payment Mode : {{$pmode->paymode}}',alignment: 'left' ,fontSize: 9,bold: true},
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: [50,40,40,'*',50,50,50,50],  //panjang standard dia 515
                            body: [
                                [
                                    { text: 'Receipt Date', style: 'tableHeader' },
                                    { text: 'Payer Code', style: 'tableHeader' },
                                    { text: 'Amount', style: 'tableHeader', alignment: 'right' },
                                    { text: 'Payer', style: 'tableHeader' },
                                    { text: 'Auth No', style: 'tableHeader' },
                                    { text: 'Exp Date', style: 'tableHeader' },
                                    { text: 'FC', style: 'tableHeader' },
                                    { text: 'Reference', style: 'tableHeader' },

                                ],
                                @php($tot = 0)
                                @foreach ($dbacthdr as $obj)
                                    @if($obj->paymode == $pmode->paymode)
                                    [
                                        { text: '{{\Carbon\Carbon::parse($obj->posteddate)->format('d/m/Y')}}' },
                                        { text: '{{$obj->payercode}}' },
                                        { text: '{{number_format($obj->amount,2)}}', alignment: 'right' },
                                        { text: '{{$obj->name}}' },
                                        { text: '{{$obj->authno}}' },
                                        { text: '{{\Carbon\Carbon::parse($obj->expdate)->format('d/m/Y')}}' },
                                        { text: '{{$obj->dt_description}}' },
                                        { text: '{{$obj->recptno}}' },

                                    ],
                                    @php($tot += $obj->amount)
                                    @endif
                                @endforeach
                                [
                                    { text: 'Total Amount', colSpan: 2,style: 'tableHeader' },
                                    { text: '', style: 'tableHeader' },
                                    { text: '{{number_format($tot,2)}}', alignment: 'right' },
                                    { text: '', style: 'tableHeader' },
                                    { text: '', style: 'tableHeader' },
                                    { text: '', style: 'tableHeader' },
                                    { text: '', style: 'tableHeader' },
                                    { text: '', style: 'tableHeader' },
                                ]
                            ]
                        },
                        layout: 'lightHorizontalLines',
                    },
                    @endforeach

                    { canvas: [ { type: 'line', x1: 0, y1: 5, x2: 515, y2: 5, lineWidth: 0.5 } ] },
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: ['*', '*'], //panjang standard dia 515
                            body: [
                                [
                                    { text: 'Ringgit Malaysia', style: 'tableHeader' },
                                    { text: 'Total Amount', style: 'tableHeader', alignment: 'right' },
                                ],
                                [
                                    { text: '{{$totamt_eng}}' },
                                    { text: '{{number_format($totalAmount,2)}}', alignment: 'right' },
                                ],
                            ]
                        },
                        layout: 'noBorders',
                    },
                  
                ],
                styles: {
                    header: {
                        fontSize: 14,
                        bold: true,
                        margin: [0, 0, 0, 10]
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