<!DOCTYPE html>
<html>
    <head>
        <title>AR Statement Listing</title>
    </head>
    
    <!-- <script src="https://unpkg.com/@jsreport/browser-client/dist/jsreport.umd.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="mydata.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha512-a9NgEEK7tsCvABL7KqtUTQjl69z7091EVPpw5KxPlZ93T141ffe1woLtbXTX+r2/8TtTvRX/v4zTL2UlMUPgwg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.min.js" integrity="sha512-P0bOMePRS378NwmPDVPU455C/TuxDS+8QwJozdc7PGgN8kLqR4ems0U/3DeJkmiE31749vYWHvBOtR+37qDCZQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
    </object>
    
    <script>
        
        $(document).ready(function (){
            var docDefinition = {
                footer: function (currentPage, pageCount){
                    return [
                        { text: currentPage.toString() + ' of ' + pageCount, alignment: 'center' }
                    ]
                },
                pageSize: 'A4',
                pageMargins: [22, 20, 10, 20],
                content: [
                    {
                        image: 'letterhead', width: 200, style: 'tableHeader', colSpan: 5, alignment: 'center'
                    },
                    {
                        text: '\nAR STATEMENT\n',
                        style: 'header',
                        alignment: 'center'
                    },
                    @foreach($debtorcode as $index => $debtor)
                    {
                        style: 'tableExample',
                        table: {
                            // headerRows: 1,
                            widths: [40,5,400], // panjang standard dia 515
                            body: [
                                [
                                    { text: 'CODE', fontSize: 9, bold: true },
                                    { text: ':', fontSize: 9, bold: true },
                                    { text: '{{$debtor->debtorcode}}', fontSize: 9, bold: true },
                                ],
                                [
                                    { text: 'NAME', fontSize: 9, bold: true },
                                    { text: ':', fontSize: 9, bold: true },
                                    { text: '{!!$debtor->name!!}', fontSize: 9, bold: true },
                                ],
                                [
                                    { text: 'ADDRESS', fontSize: 9, bold: true },
                                    { text: ':', fontSize: 9, bold: true },
                                    { text: '{!!$debtor->address1!!} {!!$debtor->address2!!} {!!$debtor->address3!!} {!!$debtor->address4!!}', fontSize: 9, bold: true },
                                ],
                            ]
                        },
                        layout: 'noBorders',
                    },
                    {
                        style: 'tableExample',
                        table: {
                            // headerRows: 1,
                            widths: [80,50,55,55,55,58,55,55], // panjang standard dia 515
                            body: [
                                [
                                    { text: 'Document', style: 'tableHeader' ,border: [false, false, false, true]},
                                    { text: 'Date', style: 'tableHeader' ,border: [false, false, false, true]},
                                    { text: '1-30 Days', style: 'tableHeader', alignment: 'right' ,border: [false, false, false, true]},
                                    { text: '31-60 Days', style: 'tableHeader', alignment: 'right' ,border: [false, false, false, true]},
                                    { text: '61-90 Days', style: 'tableHeader', alignment: 'right' ,border: [false, false, false, true]},
                                    { text: '91-120 Days', style: 'tableHeader', alignment: 'right' ,border: [false, false, false, true]},
                                    { text: '>120 Days', style: 'tableHeader', alignment: 'right' ,border: [false, false, false, true]},
                                    { text: 'Total', style: 'tableHeader', alignment: 'right' ,border: [false, false, false, true]},
                                ],
                                @php($total = 0.00)
                                @foreach ($array_report as $obj)
                                    @if($obj->debtorcode == $debtor->debtorcode)
                                    @php($total += $obj->newamt)
                                    [
                                        { text: '{{$obj->remark}}',colSpan: 6, alignment: 'left',border: [false, false, false, false]},{},{},{},{},{},
                                        @if(!empty($obj->mrn))
                                            { text: 'MRN: {{$obj->mrn}}',colSpan: 2, alignment: 'left',border: [false, false, false, false]},{},
                                        @else
                                            { text: ' ',colSpan: 2,border: [false, false, false, false]},{},
                                        @endif
                                    ],
                                    [
                                        { text: '{{$obj->doc_no}}',border: [false, false, false, true]},
                                        { text: '{{$obj->posteddate}}',border: [false, false, false, true]},

                                        @php($total_line = 0)
                                        @foreach ($grouping as $key => $group)
                                            @if($key == $obj->group)
                                            @php($total_line += $obj->newamt)
                                            { text: '{{$obj->newamt}}', alignment: 'right',border: [false, false, false, true]},
                                            @else
                                            { text: '0.00', alignment: 'right',border: [false, false, false, true]},
                                            @endif
                                        @endforeach

                                        { text: '{{$total_line}}', alignment: 'right',border: [false, false, false, true]},

                                    ],
                                    @endif
                                @endforeach
                                [
                                    { text: 'TOTAL', bold:true, alignment: 'right', colSpan:7,border: [false, false, false, true]},{},{},{},{},{},{},
                                    { text: '{{$total}}', bold:true, alignment: 'right',border: [false, false, false, true]},
                                ],
                            ]
                        },
                        defaultBorder: false,
                    },
                    { text: '', alignment: 'left', fontSize: 9, pageBreak: 'after' },
                    @endforeach
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
                        url: "{{asset('/img/letterheadukm.png')}}",
                        headers: {
                            myheader: '123',
                            myotherheader: 'abc',
                        }
                    }
                }
            };
            
            // pdfMake.createPdf(docDefinition).getBase64(function (data){
            //     var base64data = "data:base64"+data;
            //     console.log($('object#pdfPreview').attr('data',base64data));
            //     // document.getElementById('pdfPreview').data = base64data;
            // });
            
            pdfMake.createPdf(docDefinition).getDataUrl(function (dataURL){
                $('#pdfiframe').attr('src',dataURL);
            });
        });
        
        function make_header(){
            
        }
        
        // pdfMake.createPdf(docDefinition).getDataUrl(function (dataURL){
        //     console.log(dataURL);
        //     document.getElementById('pdfPreview').data = dataURL;
        // });
        
        // jsreport.serverUrl = 'http://localhost:5488'
        // async function preview(){
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