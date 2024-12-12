<!DOCTYPE html>
<html>
    <head>
        <title>Ward / OT Booking Slip</title>
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
                        // { text: 'This is computer-generated document. No signature is required.', italics: true, alignment: 'center', fontSize: 9 },
                        { text: currentPage.toString() + ' of ' + pageCount, alignment: 'center', fontSize: 9 }
                    ]
                },
                pageSize: 'A4',
                // pageMargins: [10, 20, 20, 30],
                content: [
                    {
                        image: 'letterhead', width: 175, style: 'tableHeader', colSpan: 5, alignment: 'center'
                    },
                    {
                        text: '\nWARD / OT BOOKING SLIP\n\n',
                        style: 'header',
                        alignment: 'center'
                    },
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: [70,'*',50,'*'], // panjang standard dia 515
                            body: [
                                [
                                    { text: 'DATE FOR OP', bold: true },
                                    { text: ':\t{{\Carbon\Carbon::createFromFormat('Y-m-d',$pat_otbook->op_date)->format('d-m-Y')}}', bold: true },
                                    {},{},
                                ],
                                [
                                    { text: 'PATIENTS NAME' },
                                    { text: ':\t{{$pat_otbook->Name}}' },
                                    { text: 'NRIC' },
                                    { text: ':\t{{$pat_otbook->Newic}}' }
                                ],
                            ]
                        },
                        layout: 'noBorders',
                    },
                    { text: 'TYPE OF OPERATION / PROCEDURE\t:\t{{$pat_otbook->oper_type}} - {{$pat_otbook->procedure}}', fontSize: 9 },
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: [100,1,90,90], // panjang standard dia 515
                            body: [
                                // [
                                //     { text: 'TYPE OF OPERATION / PROCEDURE\t:', colSpan: 2 },{},
                                //     { text: '\t{{$pat_otbook->oper_type}} - {{$pat_otbook->procedure}}' },
                                // ],
                                [
                                    { text: 'TYPE OF ADMISSION' },
                                    { text: ':' },
                                    @if($pat_otbook->adm_type == 'DC')
                                        { text: 'DAY CASE [\t√\t]' },
                                        { text: 'IN PATIENT [\t\t]' },
                                    @else
                                        { text: 'DAY CASE [\t\t]' },
                                        { text: 'IN PATIENT [\t√\t]' },
                                    @endif
                                ],
                                [
                                    { text: 'ANAESTHETIST' },
                                    { text: ':' },
                                    @if($pat_otbook->anaesthetist == '1')
                                        { text: 'REQUIRED [\t√\t]' },
                                        { text: 'NOT REQUIRED [\t\t]' },
                                    @else
                                        { text: 'REQUIRED [\t\t]' },
                                        { text: 'NOT REQUIRED [\t√\t]' },
                                    @endif
                                ],
                                [
                                    { text: 'TYPE OF PAYMENT' },
                                    { text: ':' },
                                    { text: 'CASH [\t\t]' },
                                    { text: 'INSURANCE [\t\t]' },
                                ],
                                // [
                                //     { text: 'TYPE OF INSURANCE (IF ANY)\t:', colSpan: 2 },{},
                                //     { text: ' ' },
                                // ],
                                // [
                                //     { text: 'MEDICAL CARD / POLICY NUM\t:', colSpan: 2 },{},
                                //     { text: ' ' },
                                // ],
                                // [
                                //     { text: 'DIAGNOSIS\t:', colSpan: 2 },{},
                                //     { text: ' ' },
                                // ],
                                // [
                                //     { text: 'COMPANY REPRESENTATIVE NUMBER FOR MEDICATION (IF ANY)\t:', colSpan: 2 },{},
                                //     { text: ' ' },
                                // ],
                            ]
                        },
                        layout: 'noBorders',
                    },
                    // { text: 'COMPANY REPRESENTATIVE NUMBER FOR MEDICATION (IF ANY)\t:\t ', fontSize: 9 },
                    // { canvas: [{ type: 'line', x1: 0, y1: 5, x2: 515, y2: 5, lineWidth: 0.5 }] },
                ],
                styles: {
                    header: {
                        fontSize: 14,
                        bold: true,
                        margin: [0, 10, 0, 0]
                    },
                    subheader: {
                        fontSize: 16,
                        bold: true,
                        margin: [0, 10, 0, 5]
                    },
                    tableExample: {
                        fontSize: 9,
                        margin: [0, 5, 0, 10]
                    },
                    tableDetail: {
                        fontSize: 7.5,
                        margin: [0, 0, 0, 8]
                    },
                    tableHeader: {
                        bold: true,
                        fontSize: 9,
                        margin: [0, 0, 0, 0],
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
                        url: '{{asset('/img/letterheadukm.png')}}',
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
            //     document.getElementById('pdfPreview').data = base64data;
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