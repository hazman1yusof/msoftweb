<!DOCTYPE html>
<html>
    <head>
        <title>AP Statement</title>
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
                        image: 'letterhead',width:200, height:40, style: 'tableHeader', colSpan: 5, alignment: 'center'
                    },
                    {
                        text: '\nAP STATEMENT\n',
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

                    @foreach ($supp_code as $scode) 
                        {
                            style: 'tableExample',
                            table: {
                                headerRows: 1,
                                widths: [40, 20, 500], //panjang standard dia 515
                                body: [
                                    [
                                        { text: 'CODE',fontSize: 9,bold: true},
                                        { text: ':',fontSize: 9,bold: true},
                                        { text: '{{$scode->suppcode}}',fontSize: 9,bold: true},
                                    ],
                                    [
                                        { text: 'NAME',fontSize: 9,bold: true},
                                        { text: ':',fontSize: 9,bold: true},
                                        { text: `{!!$scode->supplier_name!!}`,fontSize: 9,bold: true},
                                    ],
                                    [
                                        { text: 'ADDRESS',fontSize: 9,bold: true},
                                        { text: ':',fontSize: 9,bold: true},
                                        { text: `{!!str_replace('`', '', $scode->Addr1)!!} {!!str_replace('`', '', $scode->Addr2)!!} \n{!!str_replace('`', '', $scode->Addr3)!!} {!!str_replace('`', '', $scode->Addr4)!!}`, alignment:'left', fontSize: 9,bold: true},
                                    ],
                                ]
                            },
                            layout: 'noBorders',
                        },

                        {
                            style: 'tableExample',
                            table: {
                                //headerRows: 1,
                                widths: [50, 65, '*', 55, 55, 55],  //panjang standard dia 515
                                body: [
                                    [
                                        { text: 'Date', style: 'tableHeader' },
                                        { text: 'Document', style: 'tableHeader' },
                                        { text: 'Reference', style: 'tableHeader' },
                                        { text: 'Amount DR', style: 'tableHeader', alignment: 'right' },
                                        { text: 'Amount CR', style: 'tableHeader', alignment: 'right' },
                                        { text: 'Balance', style: 'tableHeader', alignment: 'right' },

                                    ],
                                    [
                                        {},
                                        {},
                                        { text: 'OPENING BALANCE', style: 'tableHeader'},
                                        {},
                                        {},
                                        { text: '{{number_format($scode->openbal,2)}}', alignment: 'right', style: 'tableHeader'},

                                    ],
                                    @php($tot_dr = 0)
                                    @php($tot_cr = 0)
                                    @php($tot_bal = 0)
                                    @foreach ($array_report as $obj)
                                        @if($obj->suppcode == $scode->suppcode)
                                        [
                                            { text: '{{\Carbon\Carbon::parse($obj->postdate)->format('d/m/Y')}}' },
                                            { text: '{{strtoupper($obj->trantype)}}/{{strtoupper($obj->docno)}}', alignment: 'left' },
                                            { text: '{{strtoupper($obj->remarks)}}', alignment: 'left' },
                                            @if(!empty($obj->amount_dr))
                                                @php($tot_dr += $obj->amount_dr)
                                                { text: '{{number_format($obj->amount_dr,2)}}', alignment: 'right' },
                                            @else
                                                {},
                                            @endif 

                                            @if(!empty($obj->amount_cr))
                                                @php($tot_cr += $obj->amount_cr)
                                                { text: '{{number_format($obj->amount_cr,2)}}', alignment: 'right' },
                                            @else
                                                {},
                                            @endif
                                                @php($tot_bal += $obj->balance)
                                            { text: '{{number_format($obj->balance,2)}}', alignment: 'right' },
                                        ],
                                        @endif
                                    @endforeach
                                    [
                                        {},
                                        {},
                                        { text: 'Total Amount',style: 'tableHeader' },
                                        { text: '{{number_format($tot_dr,2)}}', alignment: 'right', style: 'tableHeader' },
                                        { text: '{{number_format($tot_cr,2)}}', alignment: 'right', style: 'tableHeader' },
                                        { text: '{{number_format($tot_bal,2)}}', alignment: 'right', style: 'tableHeader'},
                                    
                                    ]
                                ]
                            },
                            layout: 'lightHorizontalLines',
                        },
                        { text: '', alignment: 'left', fontSize: 9, pageBreak: 'after' },
                    @endforeach
                ],
                styles: {
                    header: {
                        fontSize: 14,
                        bold: true,
                        margin: [0, 0, 0, 3]
                    },
                    tableExample: {
                        fontSize: 9,
                        margin: [0, 5, 0, 15]
                    },
                    tableHeader: {
                        bold: true,
                        fontSize: 9,
                        color: 'black'
                    },
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