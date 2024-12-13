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
                            widths: [80,'*',80,'*'], // panjang standard dia 515
                            body: [
                                [
                                    { text: 'DATE FOR OP', bold: true, fontSize: 11},
                                    { text: ':\t{{\Carbon\Carbon::createFromFormat('Y-m-d',$pat_otbook->op_date)->format('d-m-Y')}}\n', bold: true, fontSize: 11},
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
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: ['*'], // panjang standard dia 515
                            body: [
                                [
                                    { text: 'TYPE OF OPERATION / PROCEDURE\u200B\t:\u200B\t{{$pat_otbook->oper_type}}'},
                                ],
                            ]
                        },
                        layout: 'noBorders',
                    },
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: [80,'*',80,'*'], // panjang standard dia 515
                            body: [
                                [
                                    { text: 'TYPE OF ADMISSION' },
                                    @if($pat_otbook->adm_type == 'DC')
                                        { text: ':\u200B\tDAY CASE'},
                                    @else
                                        { text: ':\u200B\tIN PATIENT' },
                                    @endif

                                    { text: 'ANAESTHETIST' },
                                    @if($pat_otbook->anaesthetist == '1')
                                        { text: ':\u200B\tREQUIRED' },
                                    @else
                                        { text: ':\u200B\tNOT REQUIRED' },
                                    @endif
                                ],
                                [
                                    { text: 'TYPE OF PAYMENT' },
                                    { text: ':\u200B\t{{$pat_otbook->pyrmode}}' },
                                    {},{},
                                ],
                            ]
                        },
                        layout: 'noBorders',
                    },
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: ['*'], // panjang standard dia 515
                            body: [
                                [                                    
                                    @if($pat_otbook->pay_type == 'PT')
                                        { text: 'TYPE OF INSURANCE (IF ANY)\u200B\t:\u200B\t'},
                                    @else
                                        { text: 'TYPE OF INSURANCE (IF ANY)\u200B\t:\u200B\t{{$pat_otbook->debtor_name}}'},
                                    @endif
                                ],
                                [
                                    @if($pat_otbook->pay_type == 'PT')
                                        { text: 'MEDICAL CARD/POLICY NUM \u200B\t:\u200B\t'},
                                    @else
                                        { text: 'MEDICAL CARD/POLICY NUM \u200B\t:\u200B\t{{$pat_otbook->staffid}}'},
                                    @endif

                                ],
                            ]
                        },
                        layout: 'noBorders',
                    },
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: ['*'], // panjang standard dia 515
                            body: [
                                [
                                    {
                                        text: [
                                            {text:'SPECIAL REMARKS/INSTRUCTION FOR MEDICATION OR ANY RELATED TO CASE\u200B\t:\u200B\t\n\n', bold:true},
                                            `{!!str_replace('`', '', $pat_otbook->remarks)!!}`,
                                        ],
                                    },
                                ],
                            ]
                        },
                        // layout: 'noBorders',
                    },
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: ['*'], // panjang standard dia 515
                            body: [
                                [
                                    { text: '\n\n\n\n\n.......................................................\n'},
                                ],
                                [
                                    { text: 'NAME, SIGN & COP\n\n'},
                                ],
                                [
                                    {
                                        text: [
                                            {text:'SURGEON/OPERATING DOCTOR\u200B\t:\u200B\t', bold:true},
                                            '{{$pat_otbook->doctorname}}',
                                        ],
                                    },
                                ],
                            ]
                        },
                        layout: 'noBorders',
                    },
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
                        margin: [0, 5, 0, 5]
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