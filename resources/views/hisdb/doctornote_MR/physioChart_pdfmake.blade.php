<!DOCTYPE html>
<html>
    <head>
        <title>Request For Physiotherapy</title>
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
                        image: 'letterhead', width: 430, style: 'tableHeader', colSpan: 5, alignment: 'center'
                    },
                    { canvas: [ { type: 'line', x1: 0, y1: 5, x2: 515, y2: 5, lineWidth: 0.5 } ] },
                    {
                        style: 'tableExample',
                        table: {
                            widths: [160,80,260],
                            // headerRows: 5,
                            // keepWithHeaderRows: 5,
                            body: [
                                [
                                    { text: 'REQUEST FOR\nPHYSIOTHERAPY', rowSpan: 2, alignment: 'center' },
                                    { text: 'Name : ', bold: true, alignment: 'left', border: [true, true, true, false] },
                                    { text: '{{$pat_physio->Name}}', alignment: 'left', border: [true, true, true, false] },
                                ],
                                [
                                    {},
                                    { text: 'Gender : ', bold: true, alignment: 'left', border: [true, false, true, false] },
                                    @if($pat_physio->Sex == 'M')
                                    { text: '[√] Male / [ ] Female', alignment: 'left', border: [true, false, true, false] },
                                    @elseif($pat_physio->Sex == 'F')
                                    { text: '[ ] Male / [√] Female', alignment: 'left', border: [true, false, true, false] },
                                    @else
                                    { text: '[ ] Male / [ ] Female', alignment: 'left', border: [true, false, true, false] },
                                    @endif
                                ],
                                [
                                    {
                                        text: [
                                            { text: 'Date Registered : ', bold: true, alignment: 'left' },
                                            { text: '{{\Carbon\Carbon::createFromFormat('Y-m-d',$pat_physio->reg_date)->format('d-m-Y')}}' },
                                        ], alignment: 'left', border: [true, false, true, false]
                                    },
                                    { text: 'NRIC : ', bold: true, alignment: 'left', border: [true, false, true, false] },
                                    { text: '{{$pat_physio->Newic}}', alignment: 'left', border: [true, false, true, false] },
                                ],
                                [
                                    {
                                        text: [
                                            { text: 'Date Requested : ', bold: true, alignment: 'left' },
                                            @if(!empty($pat_physio->req_date))
                                                { text: '{{\Carbon\Carbon::createFromFormat('Y-m-d',$pat_physio->req_date)->format('d-m-Y')}}' },
                                            @else
                                                { text: '' },
                                            @endif
                                            { text: '\n\nWard / Clinic : ', bold: true, alignment: 'left' },
                                            { text: '{{$pat_physio->EpWard}}' },
                                        ], alignment: 'left', border: [true, false, true, false]
                                    },
                                    { text: 'Address : ', bold: true, alignment: 'left', border: [true, false, true, false] },
                                    { text: `{!!str_replace('`', '', $pat_physio->Address1)!!}\n{!!str_replace('`', '', $pat_physio->Address2)!!}\n{!!str_replace('`', '', $pat_physio->Address3)!!}`, alignment: 'left', border: [true, false, true, false] },
                                ],
                                [
                                    {
                                        text: [
                                            { text: 'MRN No. : ', bold: true, alignment: 'left' },
                                            { text: '{{$pat_physio->mrn}}' },
                                        ], alignment: 'left', border: [true, false, true, true]
                                    },
                                    { text: 'Contact Number : ', bold: true, alignment: 'left', border: [true, false, true, true] },
                                    { text: '{{$pat_physio->telhp}}', alignment: 'left', border: [true, false, true, true] },
                                ],
                            ]
                        },
                        layout: {
                            fillColor: function (rowIndex, node, columnIndex){
                                // return (rowIndex === 0) ? '#000000' : null;
                            }
                        }
                    },
                    {
                        style: 'tableOne',
                        table: {
                            widths: [160,350],
                            // headerRows: 5,
                            // keepWithHeaderRows: 5,
                            body: [
                                [
                                    { text: 'TO BE COMPLETED BY REQUESTING DOCTOR', colSpan: 2, italics: true, style: 'subheader1', alignment: 'center' },
                                    {},
                                ],
                                [
                                    {
                                        text: [
                                            { text: 'Clinical Diagnosis:', bold: true, alignment: 'left' },
                                            { text: `\n\n{!!$pat_physio->clinic_diag!!}` },
                                        ], alignment: 'left'
                                    },
                                    {
                                        text: [
                                            { text: 'Treatment', italics: true, bold: true, alignment: 'center' },
                                            // { text: `\n\n{!!$pat_physio->treatment!!}` },
                                            @if($pat_physio->tr_physio == '1')
                                                { text: '\n\n[√] PHYSIOTHERAPY\n', bold: true },
                                            @else
                                                { text: '\n\n[  ] PHYSIOTHERAPY\n', bold: true },
                                            @endif
                                            @if($pat_physio->tr_occuptherapy == '1')
                                                { text: '[√] OCCUPATIONAL THERAPY\n', bold: true },
                                            @else
                                                { text: '[  ] OCCUPATIONAL THERAPY\n', bold: true },
                                            @endif
                                            @if($pat_physio->tr_respiphysio == '1')
                                                { text: '[√] RESPIRATORY PHYSIOTHERAPY\n', bold: true },
                                            @else
                                                { text: '[  ] RESPIRATORY PHYSIOTHERAPY\n', bold: true },
                                            @endif
                                            @if($pat_physio->tr_neuro == '1')
                                                { text: '[√] NEURO REHAB\n', bold: true },
                                            @else
                                                { text: '[  ] NEURO REHAB\n', bold: true },
                                            @endif
                                            @if($pat_physio->tr_splint == '1')
                                                { text: '[√] SPLINTING\n', bold: true },
                                            @else
                                                { text: '[  ] SPLINTING\n', bold: true },
                                            @endif
                                            @if($pat_physio->tr_speech == '1')
                                                { text: '[√] SPEECH\n', bold: true },
                                            @else
                                                { text: '[  ] SPEECH\n', bold: true },
                                            @endif
                                        ], rowSpan: 2, alignment: 'left'
                                    }
                                ],
                                [
                                    { text: `Relevant Finding(s) : \n\n{!!$pat_physio->findings!!}`, alignment: 'left' },
                                    {},
                                ],
                                [
                                    { text: 'Signature & Name of Requesting Doctor : \n\n{{$pat_physio->doctorname}}', alignment: 'left' },
                                    {
                                        text: [
                                            { text: 'Remarks', italics: false, bold: true, alignment: 'left' },
                                            { text: `\n\n{!!$pat_physio->remarks!!}` },
                                        ], alignment: 'left'
                                    },
                                ],
                            ]
                        },
                        layout: {
                            fillColor: function (rowIndex, node, columnIndex){
                                return (rowIndex === 0) ? '#848884' : null;
                            }
                        }
                    },
                    { canvas: [ { type: 'line', x1: 0, y1: 5, x2: 515, y2: 5, lineWidth: 0.5 } ] },
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
                    subheader1: {
                        fontSize: 10,
                        // margin: [0, 10, 0, 5],
                        // background: 'black',
                        color: 'white',
                    },
                    tableExample: {
                        fontSize: 8,
                        margin: [0, 5, 0, 0]
                    },
                    tableOne: {
                        fontSize: 8,
                        margin: [0, 0, 0, 10]
                    },
                    tableHeader: {
                        bold: true,
                        fontSize: 9,
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
                        url: "{{asset('/img/logo/IMSCletterhead.png')}}",
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