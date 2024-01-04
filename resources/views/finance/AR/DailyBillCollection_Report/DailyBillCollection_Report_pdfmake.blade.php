<!DOCTYPE html>
<html>
    <head>
        <title>Daily Bill And Collection</title>
    </head>
    
    <!-- <script src="https://unpkg.com/@jsreport/browser-client/dist/jsreport.umd.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="mydata.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha512-a9NgEEK7tsCvABL7KqtUTQjl69z7091EVPpw5KxPlZ93T141ffe1woLtbXTX+r2/8TtTvRX/v4zTL2UlMUPgwg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.min.js" integrity="sha512-P0bOMePRS378NwmPDVPU455C/TuxDS+8QwJozdc7PGgN8kLqR4ems0U/3DeJkmiE31749vYWHvBOtR+37qDCZQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
    </object>
    
    <script>
        
        var array_report=[
            @foreach($array_report as $key => $array_report1)
            [
                @foreach($array_report1 as $key2 => $val)
                    {'{{$key2}}' : `{{$val}}`},
                @endforeach
            ],
            @endforeach
        ];
        
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
                            widths: [38,40,50,32,25,32,32,35,32,32,32],  //panjang standard dia 515
                            body: [
                                [   
                                    { text: 'Date', style: 'tableHeader' },
                                    { text: 'Payer Code', style: 'tableHeader' },
                                    { text: 'Name', style: 'tableHeader' },
                                    { text: 'Bill Amt', style: 'tableHeader', alignment: 'right' },
                                    { text: 'Bill No', style: 'tableHeader' },
                                    { text: 'Cash', style: 'tableHeader', alignment: 'right' },
                                    { text: 'Card', style: 'tableHeader', alignment: 'right' },
                                    { text: 'Cheque', style: 'tableHeader', alignment: 'right' },
                                    { text: 'TT', style: 'tableHeader', alignment: 'right' },
                                    { text: 'Credit Note', style: 'tableHeader', alignment: 'right' },
                                    { text: 'Receipt No', style: 'tableHeader' },
                                    
                                ],
                                @foreach ($array_report as $obj)
                                [
                                    { text: '{{\Carbon\Carbon::parse($obj->posteddate)->format('d/m/Y')}}' },
                                    { text: '{{$obj->payercode}}' },
                                    { text: '{{$obj->dm_name}}' },
                                    { text: '{{number_format($obj->amount,2)}}', alignment: 'right' },
                                    { text: '{{$obj->invno}}' },
                                    { text: '{{number_format($obj->cash_amount,2)}}', alignment: 'right' },
                                    { text: '{{number_format($obj->card_amount,2)}}', alignment: 'right' },
                                    { text: '{{number_format($obj->cheque_amount,2)}}', alignment: 'right' },
                                    { text: '{{number_format($obj->tt_amount,2)}}', alignment: 'right' },
                                    { text: '{{number_format($obj->cn_amount,2)}}', alignment: 'right' },
                                    { text: '{{$obj->recptno}}' },
                                ],
                                @endforeach
                            ]
                        },
                        layout: 'lightHorizontalLines',
                    },
                    // { canvas: [ { type: 'line', x1: 0, y1: 5, x2: 515, y2: 5, lineWidth: 0.5 } ] },
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
                        url: '{{asset('/img/MSLetterHead.jpg')}}',
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