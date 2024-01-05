<!DOCTYPE html>
<html>
    <head>
        <title>Sales By Item</title>
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
                        image: 'letterhead',width:400, height:80, style: 'tableHeader', colSpan: 5, alignment: 'center'
                    },
                    {
                        text: '\nSALES BY ITEM\n',
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
                            widths: ['*','*','*','*','*','*','*'], //panjang standard dia 515
                            body: [
                                [
                                    { text: 'Date', style: 'tableHeader' },
                                    { text: 'Charge Code', style: 'tableHeader' },
                                    { text: 'Description', style: 'tableHeader'},
                                    { text: 'Quantity', style: 'tableHeader', alignment: 'right'  },
                                    { text: 'Amount', style: 'tableHeader', alignment: 'right'  },
                                    { text: 'Tax', style: 'tableHeader', alignment: 'right'  },
                                    { text: 'Total', style: 'tableHeader', alignment: 'right'  },
                                ],
                            ]
                        },
                        layout: 'noBorders',
                    },

                    @php($totalAmount = 0)
                    @foreach($invno_array as $invno)
                        @php($amt = 0)
                        @php($tax = 0)
                        @php($tot = 0)
                        @foreach ($dbacthdr as $obj)
                            @if($invno == $obj->invno)
                                @if($amt == 0)
                                    { text: '{{$obj->debtorcode}} {{$obj->dm_desc}} ({{str_pad($obj->invno, 7, "0", STR_PAD_LEFT)}})',alignment: 'left' ,fontSize: 9,bold: true},
                                @endif
                                {
                                    style: 'tableExample',
                                    table: {
                                        headerRows: 1,
                                        widths: ['*','*','*','*','*','*','*'],  //panjang standard dia 515
                                        body: [
                                            [
                                                { text: '{{\Carbon\Carbon::parse($obj->trxdate)->format('d/m/Y')}}'},
                                                { text: '{{$obj->chgcode}}'},
                                                { text: '{{$obj->cm_desc}}'},
                                                { text: '{{$obj->quantity}}'},
                                                { text: '{{number_format($obj->amount, 2, '.', ',')}}'},
                                                { text: '{{number_format($obj->taxamount, 2, '.', ',')}}'},
                                                { text: '{{number_format($obj->amount+$obj->taxamount, 2, '.', ',')}}'},
                                            ],
                                            @php($amt += $obj->amount)
                                            @php($tax += $obj->taxamount)
                                            @php($tot += $obj->amount+$obj->taxamount)
                                            @php($totalAmount += $tot)
                            @endif
                        @endforeach
                                            [
                                                { text: '', style: 'tableHeader' },
                                                { text: '', style: 'tableHeader' },
                                                { text: '', style: 'tableHeader' },
                                                { text: 'Total Amount',style: 'tableHeader' },
                                                { text: '{{number_format($amt, 2, '.', ',')}}', alignment: 'right' },
                                                { text: '{{number_format($tax, 2, '.', ',')}}', style: 'tableHeader' },
                                                { text: '{{number_format($tot, 2, '.', ',')}}', style: 'tableHeader' },
                                                
                                            ]
                                        ]
                                    },
                                    layout: 'lightHorizontalLines',
                                },
                    @endforeach

                    // { canvas: [ { type: 'line', x1: 0, y1: 5, x2: 515, y2: 5, lineWidth: 0.5 } ] },
                    // {
                    //     style: 'tableExample',
                    //     table: {
                    //         headerRows: 1,
                    //         widths: ['*','*','*','*','*','*','*'], //panjang standard dia 515
                    //         body: [
                    //             [
                    //                 { text: '', style: 'tableHeader' },
                    //                 { text: '', style: 'tableHeader' },
                    //                 { text: '', style: 'tableHeader' },
                    //                 { text: '', style: 'tableHeader' },,
                    //                 { text: '', style: 'tableHeader' },
                    //                 { text: 'Total Amount',style: 'tableHeader' },
                    //                 { text: '{{number_format($totalAmount, 2, '.', ',')}}', style: 'tableHeader' },
                    //             ],
                    //         ]
                    //     },
                    //     layout: 'noBorders',
                    // },
                  
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