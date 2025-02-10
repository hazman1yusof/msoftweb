<!DOCTYPE html>
<html>
    <head>
        <title>Debtor Master</title>
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
                pageOrientation: 'landscape',
                content: [
                    {
                        image: 'letterhead', width: 200, height: 40, style: 'tableHeader', colSpan: 5, alignment: 'center'
                    },
                    {
                        text: '\nDEBTOR MASTER\n',
                        style: 'header',
                        alignment: 'center'
                    },
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: [60,50,150,43,80,80,80,130],    // panjang standard dia 515
                            body: [
                                [
                                    { text: 'Code', style: 'tableHeader' },
                                    { text: 'Financial Class', style: 'tableHeader' },
                                    { text: 'Name', style: 'tableHeader' },
                                    { text: 'Postcode', style: 'tableHeader' },
                                    { text: 'Tel Office', style: 'tableHeader' },
                                    { text: 'Fax', style: 'tableHeader' },
                                    { text: 'Contact', style: 'tableHeader' },
                                    { text: 'Email', style: 'tableHeader' },
                                ],
                                @foreach ($debtormast as $obj)
                                [
                                    { text: '{{$obj->debtorcode}}' },
                                    { text: '{{$obj->debtortype}}' },
                                    { text: `{!!str_replace('`', '', $obj->name)!!}\n{!!str_replace('`', '', $obj->address1)!!}\n{!!str_replace('`', '', $obj->address2)!!}\n{!!str_replace('`', '', $obj->address3)!!}\n{!!str_replace('`', '', $obj->address4)!!}` },
                                    { text: '{{$obj->postcode}}' },
                                    { text: '{{$obj->teloffice}}' },
                                    { text: '{{$obj->fax}}' },
                                    { text: '{{$obj->contact}}' },
                                    { text: '{{$obj->email}}' },
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
                        url: "{{asset('./img/MSLetterHead.jpg')}}",
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