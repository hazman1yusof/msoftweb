<!DOCTYPE html>
<html>
    <head>
        <title>Claim Batch Listing</title>
    </head>
    
    <!-- <script src="https://unpkg.com/@jsreport/browser-client/dist/jsreport.umd.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="mydata.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha512-a9NgEEK7tsCvABL7KqtUTQjl69z7091EVPpw5KxPlZ93T141ffe1woLtbXTX+r2/8TtTvRX/v4zTL2UlMUPgwg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.min.js" integrity="sha512-P0bOMePRS378NwmPDVPU455C/TuxDS+8QwJozdc7PGgN8kLqR4ems0U/3DeJkmiE31749vYWHvBOtR+37qDCZQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
    </object>
    
    <script>

        var array_report = [
            @foreach($array_report as $key => $array_report1)
            [
                @foreach($array_report1 as $key2 => $val)
                    {'{{$key2}}' : `{{$val}}`},
                @endforeach
            ],
            @endforeach
        ];

        $(document).ready(function (){
            var docDefinition = {
                footer: function (currentPage, pageCount){
                    return [
                        { text: currentPage.toString() + ' of ' + pageCount, alignment: 'center' }
                    ]
                },
                pageSize: 'A4',
                // pageOrientation: 'landscape',
                content: [
                    {
                        image: 'letterhead', width: 200, height: 40, style: 'tableHeader', colSpan: 5, alignment: 'center'
                    },
                    { text: '\n\n{{\Carbon\Carbon::parse($date1)->format('dS F Y')}}\n\n', fontSize: 9 },
                    { text: '{{$debtormast->address1}}', fontSize: 9 },
                    @if(!empty($debtormast->address2))
                        { text: '{{$debtormast->address2}}', fontSize: 9 },
                    @endif
                    @if(!empty($debtormast->address3))
                        { text: '{{$debtormast->address3}}', fontSize: 9 },
                    @endif
                    @if(!empty($debtormast->address4))
                        { text: '{{$debtormast->address4}}', fontSize: 9 },
                    @endif
                    { text: '\nAttention: {{$debtormast->name}}\n\n\n', fontSize: 9 },
                    { text: '{{$title}}\n\n', fontSize: 9, bold: true },
                    { text: `{!!str_replace('`', '', $content)!!}\n\n\n`, fontSize: 9 },
                    { text: '{{$sign_off}}\n\n', fontSize: 9, bold: true },
                    { text: '{{$officer}}\n\n', fontSize: 9, bold: true },
                    { text: '{{$designation}}\n\n', fontSize: 9, bold: true },
                    { text: '', alignment: 'left', fontSize: 9, pageBreak: 'after' },
                    @foreach($debtormast_obj as $index => $debtor)
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
                            widths: [50,50,60,100,60,60,60], // panjang standard dia 515
                            body: [
                                [
                                    { text: 'Doc Date', style: 'tableHeader' },
                                    { text: 'Date Send', style: 'tableHeader' },
                                    { text: 'Document', style: 'tableHeader' },
                                    { text: 'Reference', style: 'tableHeader' },
                                    { text: 'Amount', style: 'tableHeader', alignment: 'right' },
                                    { text: 'Balance', style: 'tableHeader', alignment: 'right' },
                                ],
                                @php($totalAmount_dr = 0)
                                @php($totalAmount_cr = 0)
                                @foreach ($array_report as $obj)
                                    @if($obj->debtorcode == $debtor->debtorcode)
                                    [
                                        { text: '{{\Carbon\Carbon::parse($obj->posteddate)->format('d/m/Y')}}' },
                                        @if(!empty($obj->datesend))
                                            { text: '{{\Carbon\Carbon::parse($obj->datesend)->format('d/m/Y')}}' },
                                        @else
                                            { text: ' ' },
                                        @endif
                                        { text: '{{$obj->trantype}}/{{str_pad($obj->auditno, 5, "0", STR_PAD_LEFT)}}' },
                                        { text: '{{$obj->reference}}' },
                                        @if(!empty($obj->amount_dr))
                                            @php($totalAmount_dr += $obj->amount_dr)
                                            { text: '{{number_format($obj->amount_dr,2)}}', alignment: 'right' },
                                        @else
                                            { text: ' ' },
                                        @endif
                                        { text: '{{number_format($obj->balance,2)}}', alignment: 'right' },
                                    ],
                                    @endif
                                @endforeach
                                [
                                    { text: ' ' },
                                    { text: ' ' },
                                    { text: ' ' },
                                    { text: 'TOTAL', bold: true },
                                    { text: '{{number_format($totalAmount_dr,2)}}', alignment: 'right', bold: true },
                                    { text: ' ' },
                                ],
                            ]
                        },
                        layout: 'lightHorizontalLines',
                    },
                    { text: '\n\n\n\n\n\n{{$company->name}}', fontSize: 9, bold: true },
                    {
                        style: 'body_sign',
                        table: {
                            widths: ['*','*','*'],//panjang standard dia 515
                            dontBreakRows: true,
                            body: [
                                [
                                    {text: 'Prepared By\n\n\n\n______________________\n',bold: true,alignment: 'left'}, 
                                    {text: 'Verified By\n\n\n\n______________________\n',bold: true,alignment: 'left'},
                                    {text: 'Approved By\n\n\n\n______________________\n',bold: true,alignment: 'left'},
                                ],
                                // [
                                //  {text: '______________________',alignment: 'center', margin: [0, 30, 0, 0]}, 
                                //  {text: '______________________',alignment: 'center', margin: [0, 30, 0, 0]},
                                //  {text: '______________________',alignment: 'center', margin: [0, 30, 0, 0]}, 
                                //  {text: '______________________',alignment: 'center', margin: [0, 30, 0, 0]},
                                // ],
                            ]
                        },
                        layout: 'noBorders',
                    },
                    { text: '', alignment: 'left', fontSize: 9, pageBreak: 'after' },
                    @endforeach
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
                    body_sign: {
                        fontSize: 9,
                        margin: [0, 20, 0, 0]
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