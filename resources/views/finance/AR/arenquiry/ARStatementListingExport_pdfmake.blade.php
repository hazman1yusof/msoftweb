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
        
        var openbal = '{{$openbal}}';
        
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
                    // {
                    //     text: '{{$company->name}}\n{{$company->address1}}\n{{$company->address2}}\n{{$company->address3}}\n{{$company->address4}}\n\n\n',
                    //     alignment: 'center',
                    //     style: 'comp_header'
                    // },
                    @foreach($debtormast as $index => $debtor)
                    { text: '{{++$index}}. {{$debtor->debtorcode}}', alignment: 'left', fontSize: 9, bold: true },
                    { text: '{!!$debtor->name!!}', alignment: 'left', fontSize: 9, bold: true },
                    { text: '{!!$debtor->address1!!} {!!$debtor->address2!!} {!!$debtor->address3!!} {!!$debtor->address4!!}', alignment: 'left', fontSize: 9, bold: true },
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: [60,60,120,60,60,60],  //panjang standard dia 515
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
                                    { text: ' ' },
                                    { text: ' ' },
                                    { text: 'OPENING BALANCE' },
                                    { text: ' ' },
                                    { text: ' ' },
                                    { text: '{{number_format($openbal,2)}}', alignment: 'right' },
                                ],
                                @php($totalAmount_dr = 0)
                                @php($totalAmount_cr = 0)
                                @foreach ($array_report as $obj)
                                    @if($obj->debtorcode == $debtor->debtorcode)
                                    [
                                        { text: '{{\Carbon\Carbon::parse($obj->posteddate)->format('d/m/Y')}}' },
                                        { text: '{{$obj->trantype}}/{{str_pad($obj->auditno, 5, "0", STR_PAD_LEFT)}}' },
                                        { text: '{{$obj->reference}}' },
                                        @if(!empty($obj->amount_dr))
                                            { text: '{{number_format($obj->amount_dr,2)}}', alignment: 'right' },
                                        @else
                                            { text: ' ' },
                                        @endif
                                        @if(!empty($obj->amount_cr))
                                            { text: '{{number_format($obj->amount_cr,2)}}', alignment: 'right' },
                                        @else
                                            { text: ' ' },
                                        @endif
                                        { text: '{{number_format($obj->balance,2)}}', alignment: 'right' },
                                    ],
                                    @php($totalAmount_dr += $obj->amount_dr)
                                    @php($totalAmount_cr += $obj->amount_cr)
                                    @endif
                                @endforeach
                                [
                                    { text: ' ' },
                                    { text: ' ' },
                                    { text: 'TOTAL', bold: true },
                                    { text: '{{number_format($totalAmount_dr,2)}}', alignment: 'right', bold: true },
                                    { text: '{{number_format($totalAmount_cr,2)}}', alignment: 'right', bold: true },
                                    { text: ' ' },
                                ],
                                
                            ]
                        },
                        layout: 'lightHorizontalLines',
                    },
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