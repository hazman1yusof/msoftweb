<!DOCTYPE html>
<html>
    <head>
        <title>Users Setup</title>
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
                        text: '\nUSERS SETUP\n',
                        style: 'header',
                        alignment: 'center'
                    },
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: [46,60,50,53,35,28,27,30,38,30],    // panjang standard dia 515
                            body: [
                                [
                                    { text: 'Username', style: 'tableHeader' },
                                    { text: 'Name', style: 'tableHeader' },
                                    { text: 'Group', style: 'tableHeader' },
                                    { text: 'Department', style: 'tableHeader' },
                                    { text: 'Cashier', style: 'tableHeader' },
                                    { text: 'Billing', style: 'tableHeader' },
                                    { text: 'Nurse', style: 'tableHeader' },
                                    { text: 'Doctor', style: 'tableHeader' },
                                    { text: 'Register', style: 'tableHeader' },
                                    { text: 'Price View', style: 'tableHeader' },
                                ],
                                @foreach ($users as $obj)
                                [
                                    { text: '{{$obj->username}}' },
                                    { text: '{{$obj->name}}' },
                                    { text: '{{$obj->groupid}}' },
                                    { text: '{{$obj->dept}}' },
                                    @if($obj->cashier == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '' },
                                    @endif
                                    @if($obj->billing == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '' },
                                    @endif
                                    @if($obj->nurse == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '' },
                                    @endif
                                    @if($obj->doctor == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '' },
                                    @endif
                                    @if($obj->register == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '' },
                                    @endif
                                    @if($obj->priceview == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '' },
                                    @endif
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