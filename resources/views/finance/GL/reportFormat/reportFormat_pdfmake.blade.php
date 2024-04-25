<!DOCTYPE html>
<html>
    <head>
        <title>Report Format</title>
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
                // pageOrientation: 'landscape',
                content: [
                    {
                        image: 'letterhead', width: 200, height: 40, style: 'tableHeader', colSpan: 5, alignment: 'center'
                    },
                    {
                        text: '\nREPORT FORMAT\n',
                        style: 'header',
                        alignment: 'center'
                    },
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: [60,10,'*'],    // panjang standard dia 515
                            body: [
                                [
                                    { text: 'Report Name', style: 'tableHeader' },
                                    { text: ':', style: 'tableHeader' },
                                    { text: '{{$glrpthdr->rptname}}', style: 'tableHeader' },
                                ],
                                [
                                    { text: 'Description', style: 'tableHeader' },
                                    { text: ':', style: 'tableHeader' },
                                    { text: '{{$glrpthdr->description}}', style: 'tableHeader' },
                                ],
                                [
                                    { text: 'Category', style: 'tableHeader' },
                                    { text: ':', style: 'tableHeader' },
                                    { text: `{!!str_replace('`', '', $glrpthdr->rpttype)!!}`, style: 'tableHeader' },
                                ],
                            ]
                        },
                        layout: 'noBorders',
                    },
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: [22,35,35,55,22,70,37,45,45,35],    // panjang standard dia 515
                            body: [
                                [
                                    { text: 'No.', style: 'tableHeader', alignment: 'right' },
                                    { text: 'Print Flag', style: 'tableHeader' },
                                    { text: 'Row Def', style: 'tableHeader' },
                                    { text: 'Code', style: 'tableHeader' },
                                    { text: 'Note', style: 'tableHeader', alignment: 'right' },
                                    { text: 'Description', style: 'tableHeader' },
                                    { text: 'Formula', style: 'tableHeader', alignment: 'right' },
                                    { text: 'Cost Code From', style: 'tableHeader' },
                                    { text: 'Cost Code To', style: 'tableHeader' },
                                    { text: 'Reverse Sign', style: 'tableHeader' },
                                ],
                                @foreach ($glrptfmt as $obj)
                                [
                                    { text: '{{$obj->lineno_}}', alignment: 'right' },
                                    @if($obj->printflag == 'Y')
                                        { text: 'YES' },
                                    @else
                                        { text: 'NO' },
                                    @endif
                                    @if($obj->rowdef == 'H')
                                        { text: 'Header' },
                                    @elseif($obj->rowdef == 'D')
                                        { text: 'Detail' },
                                    @elseif($obj->rowdef == 'S')
                                        { text: 'Spacing' },
                                    @else
                                        { text: 'Total' },
                                    @endif
                                    { text: '{{$obj->code}}' },
                                    { text: '{{$obj->note}}', alignment: 'right' },
                                    { text: `{!!str_replace('`', '', $obj->description)!!}` },
                                    { text: '{{$obj->formula}}', alignment: 'right' },
                                    { text: '{{$obj->costcodefr}}' },
                                    { text: '{{$obj->costcodeto}}' },
                                    { text: '{{$obj->revsign}}' },
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
            
            pdfMake.createPdf(docDefinition).getDataUrl(function (dataURL){
                $('#pdfiframe').attr('src',dataURL);
            });
        });
    </script>
    
    <body style="margin: 0px;">
        <iframe id="pdfiframe" width="100%" height="100%" src="" frameborder="0" style="width: 99vw;height: 99vh;"></iframe>
    </body>
</html>