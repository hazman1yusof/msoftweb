<!DOCTYPE html>
<html>
    <head>
        <title>Auto Debit Listing</title>
    </head>
    
    <!-- <script src="https://unpkg.com/@jsreport/browser-client/dist/jsreport.umd.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="mydata.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha512-a9NgEEK7tsCvABL7KqtUTQjl69z7091EVPpw5KxPlZ93T141ffe1woLtbXTX+r2/8TtTvRX/v4zTL2UlMUPgwg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.min.js" integrity="sha512-P0bOMePRS378NwmPDVPU455C/TuxDS+8QwJozdc7PGgN8kLqR4ems0U/3DeJkmiE31749vYWHvBOtR+37qDCZQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
    </object>
    
    <script>
        
        var dbacthdr=[
            @foreach($dbacthdr as $key => $dbacthdr1)
            [
                @foreach($dbacthdr1 as $key2 => $val)
                    {'{{$key2}}' : `{{$val}}`},
                @endforeach
            ],
            @endforeach
        ];
        
        var totalAmount = '{{$totalAmount}}';
        
        var sum_cash = '{{$sum_cash}}';
        var sum_chq = '{{$sum_chq}}';
        var sum_card = '{{$sum_card}}';
        var sum_bank = '{{$sum_bank}}';
        var sum_all = '{{$sum_all}}';
        var sum_cash_ref = '{{$sum_cash_ref}}';
        var sum_chq_ref = '{{$sum_chq_ref}}';
        var sum_card_ref = '{{$sum_card_ref}}';
        var sum_bank_ref = '{{$sum_bank_ref}}';
        var sum_all_ref = '{{$sum_all_ref}}';
        
        var title = {
            @foreach($company as $key => $val)
                '{{$key}}' : '{{$val}}',
            @endforeach
        };
        
        var company = {
            @foreach($company as $key => $val)
                '{{$key}}' : '{{$val}}',
            @endforeach
        };
        
        var totamt_eng = '{{$totamt_eng}}';
        
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
                        text: '\n{{$title}}\n',
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
                    // {
                    //     text: '{{$company->name}}\n{{$company->address1}}\n{{$company->address2}}\n{{$company->address3}}\n{{$company->address4}}\n\n\n',
                    //     alignment: 'center',
                    //     style: 'comp_header'
                    // },
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: [50,50,60,50,50,50,50,50],  //panjang standard dia 515
                            body: [
                                [   
                                    { text: 'Receipt Date', style: 'tableHeader' },
                                    { text: 'Payer Code', style: 'tableHeader' },
                                    { text: 'Amount', style: 'tableHeader', alignment: 'right' },
                                    { text: 'Payer', style: 'tableHeader' },
                                    { text: 'FC', style: 'tableHeader' },
                                    { text: 'Mode', style: 'tableHeader' },
                                    { text: 'Reference', style: 'tableHeader' },
                                    { text: 'Receipt No', style: 'tableHeader' },
                                    
                                ],
                                @foreach ($dbacthdr as $obj)
                                [
                                    { text: '{{\Carbon\Carbon::parse($obj->posteddate)->format('d/m/Y')}}' },
                                    { text: '{{$obj->payercode}}' },
                                    { text: '{{number_format($obj->amount,2)}}', alignment: 'right' },
                                    { text: '{{$obj->payername}}' },
                                    { text: '{{$obj->dt_description}}' },
                                    { text: '{{$obj->paymode}}' },
                                    { text: '{{$obj->reference}}' },
                                    { text: '{{$obj->recptno}}' },
                                ],
                                @endforeach
                            ]
                        },
                        layout: 'lightHorizontalLines',
                    },
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
                    // {
                    //     text: '\nSUMMARY\n',
                    //     style: 'header1',
                    //     alignment: 'center'
                    // },
                    // {
                    //     style: 'tableExample',
                    //     table: {
                    //         headerRows: 1,
                    //         widths: ['*', '*', '*'],  //panjang standard dia 515
                    //         body: [
                    //             [
                    //                 { text: ' ', style: 'tableHeader' },
                    //                 { text: 'Amount Collected', style: 'tableHeader' },
                    //                 { text: 'Amount Refund', style: 'tableHeader' },
                    //             ],
                    //             [
                    //                 { text: 'Cash' },
                    //                 { text: '{{$sum_cash}}' },
                    //                 { text: '{{$sum_cash_ref}}' },
                    //             ],
                    //             [
                    //                 { text: 'Cheque' },
                    //                 { text: '{{$sum_chq}}' },
                    //                 { text: '{{$sum_chq_ref}}' },
                    //             ],
                    //             [
                    //                 { text: 'Card' },
                    //                 { text: '{{$sum_card}}' },
                    //                 { text: '{{$sum_card_ref}}' },
                    //             ],
                    //             [
                    //                 { text: 'Auto Debit' },
                    //                 { text: '{{$sum_bank}}' },
                    //                 { text: '{{$sum_bank_ref}}' },
                    //             ],
                    //         ]
                    //     },
                    //     layout: 'lightHorizontalLines',
                    // },
                    // { canvas: [ { type: 'line', x1: 0, y1: 0, x2: 515, y2: 0, lineWidth: 0.5 } ] },
                    // {
                    //     style: 'tableExample',
                    //     table: {
                    //         headerRows: 1,
                    //         widths: ['*', '*', '*'],  //panjang standard dia 515
                    //         body: [
                    //             [
                    //                 { text: 'Total', style: 'tableHeader' },
                    //                 { text: '{{$sum_all}}' },
                    //                 { text: '{{$sum_all_ref}}' },
                    //             ],
                    //         ]
                    //     },
                    //     layout: 'lightHorizontalLines',
                    // },
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
                        fontSize: 9,
                        margin: [0, 5, 0, 15]
                    },
                    tableHeader: {
                        bold: true,
                        fontSize: 10,
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