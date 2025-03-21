<!DOCTYPE html>
<html>
    <head>
        <title>PRESCRIPTION FORM</title>
    </head>
    
    <!-- <script src="https://unpkg.com/@jsreport/browser-client/dist/jsreport.umd.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="mydata.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha512-a9NgEEK7tsCvABL7KqtUTQjl69z7091EVPpw5KxPlZ93T141ffe1woLtbXTX+r2/8TtTvRX/v4zTL2UlMUPgwg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.min.js" integrity="sha512-P0bOMePRS378NwmPDVPU455C/TuxDS+8QwJozdc7PGgN8kLqR4ems0U/3DeJkmiE31749vYWHvBOtR+37qDCZQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
    </object>
    
    <script>
        var PS_No = '';
        var EpType = '';
        var Debtor = '';
        var Name = '';
        var Diagnosis = '';
        var Allergic = '';
        var IDNumber = '';
        var MRN = '';
        var Age = '';
        var TCA = '';
        var Clinic = '';
        
        var pharmacy = [
            
        ];
        
        var medications = [
            
        ];
        
        var Sex = '';
        var Dates = '';
        var Address1 = '';
        var Address2 = '';
        var Address3 = '';
        var SpecialistName = '';
        var Speciality = '';
        var PrescriptionDate = '';
        
        $(document).ready(function (){
            var docDefinition = {
                footer: function (currentPage, pageCount){
                    return [
                        { text: currentPage.toString() + ' of ' + pageCount, alignment: 'center' }
                    ]
                },
                pageSize: 'A4',
                pageMargins: [23, 23, 23, 23],
                // pageOrientation: 'landscape',
                content: [
                    {
                        style: 'tableLetterhead',
                        table: {
                            // widths: [90,40], // panjang standard dia 515
                            body: [
                                [
                                    {
                                        image: 'letterhead', width: 175, style: 'tableHeader', alignment: 'center'
                                    },
                                    {
                                        image: 'letterhead2', width: 175, style: 'tableHeader', alignment: 'center'
                                    },
                                ],
                            ]
                        },
                        layout: 'noBorders',
                    },
                    // {
                    //     image: 'letterhead', width: 175, style: 'tableHeader', colSpan: 5, alignment: 'center'
                    // },
                    // [
                    //     {
                    //         text: [
                    //             '\nPRESCRIPTION FORM\n',
                    //         ],
                    //         style: 'header',
                    //         alignment: 'center',
                    //     },
                    // ],
                    // {
                    //     style: 'tableExample',
                    //     table: {
                    //         widths: [45,140,5,40,40,40,40,40,40,40],
                    //         // headerRows: 5,
                    //         // keepWithHeaderRows: 5,
                    //         body: [
                    //             [
                    //                 {
                    //                     text: [
                    //                         'Patient Detail',
                    //                     ], colSpan: 2, bold: true, alignment: 'center'
                    //                 },{},
                    //                 {
                    //                     text: [
                    //                         '',
                    //                     ], border: [true, false, true, false],
                    //                 },
                    //                 { text: 'PS No', bold: true },
                    //                 { text: PS_No, colSpan: 2 },{},
                    //                 { text: 'Ep Type', bold: true },
                    //                 { text: EpType },
                    //                 { text: 'Debtor', bold: true },
                    //                 { text: Debtor }
                    //             ],
                    //             [
                    //                 { text: 'Name', bold: true },
                    //                 { text: Name },
                    //                 {
                    //                     text: [
                    //                         '',
                    //                     ], border: [true, false, true, false],
                    //                 },
                    //                 { text: 'Diagnosis', bold: true },
                    //                 { text: Diagnosis, colSpan: 3 },{},{},
                    //                 { text: 'Allergic', bold: true },
                    //                 { text: Allergic, colSpan: 2 },{},
                    //             ],
                    //             [
                    //                 { text: 'ID Number', bold: true },
                    //                 { text: IDNumber },
                    //                 {
                    //                     text: [
                    //                         '',
                    //                     ], colSpan: 8, border: [true, false, false, false],
                    //                 },{},{},{},{},{},{},{},
                    //             ],
                    //             [
                    //                 { text: 'MRN', bold: true },
                    //                 { text: MRN },
                    //                 {
                    //                     text: [
                    //                         '',
                    //                     ], border: [true, false, true, false],
                    //                 },
                    //                 { text: 'Main Pharmacy UKMSC', bold: true, colSpan: 7, alignment: 'center' },{},{},{},{},{},{},
                    //             ],
                    //             [
                    //                 { text: 'Age', bold: true },
                    //                 { text: Age },
                    //                 {
                    //                     text: [
                    //                         '',
                    //                     ], border: [true, false, true, false],
                    //                 },
                    //                 { text: 'TCA', bold: true, alignment: 'center' },
                    //                 { text: TCA, colSpan: 2 },{},
                    //                 { text: 'Clinic', bold: true, alignment: 'center' },
                    //                 { text: Clinic, colSpan: 3 },{},{},
                    //             ],
                    //             [
                    //                 { text: 'Sex', bold: true },
                    //                 { text: Sex },
                    //                 {
                    //                     text: [
                    //                         '',
                    //                     ], border: [true, false, true, false],
                    //                 },
                    //                 { text: 'Date', bold: true, alignment: 'center' },
                    //                 { text: 'Supply', bold: true, alignment: 'center' },
                    //                 { text: 'Verify', bold: true, alignment: 'center' },
                    //                 { text: 'Fill', bold: true, alignment: 'center' },
                    //                 { text: 'Check', bold: true, alignment: 'center' },
                    //                 { text: 'Dispense', bold: true, alignment: 'center' },
                    //                 { text: 'Remark', bold: true, alignment: 'center' },
                    //             ],
                    //             [
                    //                 { text: 'Date', bold: true },
                    //                 { text: Dates },
                    //                 {
                    //                     text: [
                    //                         '',
                    //                     ], border: [true, false, true, false],
                    //                 },
                    //                 { text: 'Date', bold: true, alignment: 'center' },
                    //                 { text: 'Supply', bold: true, alignment: 'center' },
                    //                 { text: 'Verify', bold: true, alignment: 'center' },
                    //                 { text: 'Fill', bold: true, alignment: 'center' },
                    //                 { text: 'Check', bold: true, alignment: 'center' },
                    //                 { text: 'Dispense', bold: true, alignment: 'center' },
                    //                 { text: 'Remark', bold: true, alignment: 'center' },
                    //             ],
                    //         ]
                    //     },
                    // },
                    {
                        columns: [
                            {
                                width: 'auto',
                                style: 'tableExample',
                                table: {
                                    widths: [45,140],
                                    // headerRows: 5,
                                    // keepWithHeaderRows: 5,
                                    body: [
                                        [
                                            {
                                                text: [
                                                    'Patient Detail',
                                                ], colSpan: 2, bold: true, alignment: 'center'
                                            },{},
                                        ],
                                        [
                                            { text: 'Name', bold: true },
                                            { text: Name },
                                        ],
                                        [
                                            { text: 'ID Number', bold: true },
                                            { text: IDNumber },
                                        ],
                                        [
                                            { text: 'MRN', bold: true },
                                            { text: MRN },
                                        ],
                                        [
                                            { text: 'Age', bold: true },
                                            { text: Age },
                                        ],
                                        [
                                            { text: 'Sex', bold: true },
                                            { text: Sex },
                                        ],
                                        [
                                            { text: 'Date', bold: true },
                                            { text: Dates },
                                        ],
                                        [
                                            { text: 'Address', bold: true, rowSpan: 3 },
                                            { text: Address1 },
                                        ],
                                        [
                                            {},
                                            { text: Address2 },
                                        ],
                                        [
                                            {},
                                            { text: Address3 },
                                        ],
                                    ]
                                },
                            },
                            {
                                width: 'auto',
                                style: 'tableExample',
                                table: {
                                    widths: [40,40,40,40,40,40,40],
                                    // headerRows: 5,
                                    // keepWithHeaderRows: 5,
                                    body: [
                                        [
                                            { text: 'PS No', bold: true },
                                            { text: PS_No, colSpan: 2 },{},
                                            { text: 'Ep Type', bold: true },
                                            { text: EpType },
                                            { text: 'Debtor', bold: true },
                                            { text: Debtor }
                                        ],
                                        [
                                            { text: 'Diagnosis', bold: true },
                                            { text: Diagnosis, colSpan: 3 },{},{},
                                            { text: 'Allergic', bold: true },
                                            { text: Allergic, colSpan: 2 },{},
                                        ],
                                        [
                                            {
                                                text: [
                                                    '',
                                                ], colSpan: 7, border: [false, false, false, false],
                                            },{},{},{},{},{},{},
                                        ],
                                        [
                                            {
                                                text: [
                                                    '',
                                                ], colSpan: 7, border: [false, false, false, false],
                                            },{},{},{},{},{},{},
                                        ],
                                        [
                                            { text: 'Main Pharmacy UKMSC', bold: true, colSpan: 7, alignment: 'center' },{},{},{},{},{},{},
                                        ],
                                        [
                                            { text: 'TCA', bold: true, alignment: 'center' },
                                            { text: TCA, colSpan: 2 },{},
                                            { text: 'Clinic', bold: true, alignment: 'center' },
                                            { text: Clinic, colSpan: 3 },{},{},
                                        ],
                                        [
                                            { text: 'Date', bold: true, alignment: 'center' },
                                            { text: 'Supply', bold: true, alignment: 'center' },
                                            { text: 'Verify', bold: true, alignment: 'center' },
                                            { text: 'Fill', bold: true, alignment: 'center' },
                                            { text: 'Check', bold: true, alignment: 'center' },
                                            { text: 'Dispense', bold: true, alignment: 'center' },
                                            { text: 'Remark', bold: true, alignment: 'center' },
                                        ],
                                        @foreach($pharmacy as $obj)
                                        [
                                            { text: '{{$obj->date}}' },
                                            { text: '{{$obj->supply}}' },
                                            { text: '{{$obj->verify}}' },
                                            { text: '{{$obj->fill}}' },
                                            { text: '{{$obj->check}}' },
                                            { text: '{{$obj->dispense}}' },
                                            { text: '{{$obj->remark}}' },
                                        ],
                                        @endforeach
                                    ]
                                },
                            }
                        ],
                        columnGap: 10,
                    },
                    {
                        style: 'tableExample',
                        table: {
                            widths: [20,130,40,40,42,40,40,40,40,40],
                            // headerRows: 5,
                            // keepWithHeaderRows: 5,
                            body: [
                                [
                                    { text: 'No', style: 'tableHeader' },
                                    { text: 'Medication', style: 'tableHeader' },
                                    { text: 'Dose', style: 'tableHeader' },
                                    { text: 'Unit', style: 'tableHeader' },
                                    { text: 'Frequency', style: 'tableHeader' },
                                    { text: 'Duration', style: 'tableHeader' },
                                    { text: 'Qty', style: 'tableHeader' },
                                    { text: 'Unit Price', style: 'tableHeader', alignment: 'right' },
                                    { text: 'Total Price', style: 'tableHeader', alignment: 'right' },
                                    { text: 'Expiry', style: 'tableHeader' },
                                ],
                                @foreach($medications as $obj)
                                [
                                    { text: '{{$obj->idno}}' },
                                    { text: '{{$obj->medication}}' },
                                    { text: '{{$obj->dose}}' },
                                    { text: '{{$obj->unit}}' },
                                    { text: '{{$obj->frequency}}' },
                                    { text: '{{$obj->duration}}' },
                                    { text: '{{$obj->qty}}' },
                                    { text: '{{number_format($obj->unitPrice,2)}}', alignment: 'right' },
                                    { text: '{{number_format($obj->totalPrice,2)}}', alignment: 'right' },
                                    { text: '{{$obj->expiry}}' },
                                ],
                                @endforeach
                            ]
                        },
                    },
                    {
                        style: 'tableExample',
                        table: {
                            widths: [90,'*'], // panjang standard dia 515
                            body: [
                                [
                                    { text: 'Specialist Name', fontSize: 9, bold: true, alignment: 'left' },
                                    { text: SpecialistName, fontSize: 9, bold: true, alignment: 'left' },
                                ],
                                [
                                    { text: 'Speciality', fontSize: 9, bold: true, alignment: 'left' },
                                    { text: Speciality, fontSize: 9, bold: true, alignment: 'left' },
                                ],
                                [
                                    { text: 'Date of Prescription', fontSize: 9, bold: true, alignment: 'left' },
                                    { text: PrescriptionDate, fontSize: 9, bold: true, alignment: 'left' },
                                ],
                            ]
                        },
                        layout: 'noBorders',
                    },
                    {
                        style: 'tableExample',
                        table: {
                            widths: [90,'*'], // panjang standard dia 515
                            body: [
                                [
                                    {
                                        text: [
                                            { text: 'Disclaimer\n', bold: true },
                                            'This Prescriber name needs no manual signature and certificate as it was auto-generated from the system',
                                        ], colSpan: 2, alignment: 'left'
                                    },{},
                                ],
                            ]
                        },
                        layout: 'noBorders',
                    },
                ],
                styles: {
                    header: {
                        fontSize: 12,
                        bold: true,
                        margin: [0, 0, 0, 10]
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
                    tableLetterhead: {
                        fontSize: 9,
                        margin: [80, 15, 0, 0],
                    },
                    tableExample: {
                        fontSize: 9,
                        margin: [0, 15, 0, 0],
                    },
                    tableExample2: {
                        fontSize: 9,
                        margin: [0, 5, 0, 15]
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
                // defaultStyle: {
                //     columnGap: 5
                // },
                images: {
                    letterhead: {
                        url: "{{asset('/img/ukmsclogo.png')}}",
                        headers: {
                            myheader: '123',
                            myotherheader: 'abc',
                        }
                    },
                    letterhead2: {
                        url: "{{asset('/img/ukmscaddr.png')}}",
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