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
        var PS_No = '100567';
        var EpType = 'OP';
        var Debtor = 'Self-Pay';
        var Name = 'Hattem Ajwad bin Nasrul';
        var Diagnosis = 'Diagnosis';
        var Allergic = 'Allergic';
        var IDNumber = '390505-05-5051';
        var MRN = '75013/N597413';
        var Age = '86';
        var TCA = 'TCA';
        var Clinic = 'Clinic';
        var Sex = 'Male';
        var Dates = '25/2/2025';
        var Address1 = 'Tanah Surgo Ayah Abdul';
        var Address2 = 'Jalan Neraka';
        var Address3 = '57000 Kuala Lumpur';
        var SpecialistName = 'Assoc. Prof Dr Tuti Iryani';
        var Speciality = 'Psychiatrist';
        var PrescriptionDate = '25-Feb-25';
        
        var pharmacy = [
            {
                date:'date',
                supply:'supply',
                verify:'verify',
                fill:'fill',
                check:'check',
                dispense:'dispense',
                remark:'remark',
            }
        ];
        
        var medications = [
            {
                no:'1',
                desc:'LATUDA 80MG TABLET',
                method:'to be swallowed',
                instruction:'after meal',
                indication:'Gila',
                dose:'1',
                unit:'tab',
                freq:'1 x daily',
                duration:'3 Month',
                qty:'90',
                unitprice:'2.70',
                totalprice:'243.00',
                expiry:'Mar-27'
            }
        ];
        
        $(document).ready(function (){
            var docDefinition = {
                footer: function (currentPage, pageCount){
                    return [
                        { text: currentPage.toString() + ' of ' + pageCount, alignment: 'center' }
                    ]
                },
                pageSize: 'A4',
                pageMargins: [18, 18, 18, 18],
                // pageOrientation: 'landscape',
                content: [
                    {
                        style: 'tableLetterhead',
                        table: {
                            widths: ['*','*'], // panjang standard dia 515
                            body: [
                                [
                                    {
                                        image: 'letterhead', width: 175, alignment: 'left'
                                    },
                                    {
                                        image: 'letterhead2', width: 175, style: 'tableHeader', alignment: 'right'
                                    },
                                ],
                            ]
                        },
                        layout: 'noBorders',
                    },
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
                                    body: make_body1()
                                },
                            }
                        ],
                        columnGap: 10,
                    },
                    {
                        style: 'tableExample',
                        table: {
                            dontBreakRows: true,
                            widths: [15,171,25,25,42,40,30,40,40,40],
                            // headerRows: 5,
                            // keepWithHeaderRows: 5,
                            body: make_body2()
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
                        margin: [0, -5, 0, -20],
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
                    letterhead: 'data:image/png;base64,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',
                    letterhead2: 'data:image/png;base64,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'
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

        function make_body1(){
            let ret_arr = [
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
                            '  ',
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
                ]
            ];

            for (let i = 0; i < 4; i++) {
                if(pharmacy[i]){
                    let e = pharmacy[i];
                    let array = [
                        { text: e.date, alignment: 'center' },
                        { text: e.supply, alignment: 'center' },
                        { text: e.verify, alignment: 'center' },
                        { text: e.fill, alignment: 'center' },
                        { text: e.check, alignment: 'center' },
                        { text: e.dispense, alignment: 'center' },
                        { text: e.remark, alignment: 'center' }
                    ];
                    ret_arr.push(array);
                }else{
                    let array = [
                        { text: ' ', alignment: 'center' },
                        { text: ' ', alignment: 'center' },
                        { text: ' ', alignment: 'center' },
                        { text: ' ', alignment: 'center' },
                        { text: ' ', alignment: 'center' },
                        { text: ' ', alignment: 'center' },
                        { text: ' ', alignment: 'center' }
                    ];
                    ret_arr.push(array);
                }
            }

            return ret_arr;
        }

        function make_body2(){
            let ret_arr = [
                [
                    { text: 'No', style: 'tableHeader' },
                    { text: 'Medication', style: 'tableHeader' },
                    { text: 'Dose', style: 'tableHeader', alignment: 'center'  },
                    { text: 'Unit', style: 'tableHeader', alignment: 'center'  },
                    { text: 'Frequency', style: 'tableHeader', alignment: 'center'  },
                    { text: 'Duration', style: 'tableHeader', alignment: 'center'  },
                    { text: 'Qty', style: 'tableHeader', alignment: 'center'  },
                    { text: 'Unit Price', style: 'tableHeader', alignment: 'right' },
                    { text: 'Total Price', style: 'tableHeader', alignment: 'right' },
                    { text: 'Expiry', style: 'tableHeader', alignment: 'center'  },
                ]
            ];

            medications.forEach(function(e,i){
                let array = [
                    { text: e.no, style: 'tableHeader' },
                    {
                        columns: [
                            [
                                { text: e.desc, bold: true,margin:[0,0,0,4] },
                                {
                                    columns: [
                                        {text: 'Method',width:50},
                                        {text: e.method}
                                    ]
                                },{
                                    columns: [
                                        {text: 'Instruction',width:50},
                                        {text: e.instruction}
                                    ]
                                },{
                                    columns: [
                                        {text: 'Indication',width:50},
                                        {text: e.indication}
                                    ]
                                }
                            ]
                        ],
                    },
                    { text: e.dose, style: 'tableHeader', alignment: 'center' },
                    { text: e.unit, style: 'tableHeader', alignment: 'center'  },
                    { text: e.freq, style: 'tableHeader', alignment: 'center'  },
                    { text: e.duration, style: 'tableHeader', alignment: 'center'  },
                    { text: e.qty, style: 'tableHeader', alignment: 'center'  },
                    { text: e.unitprice, style: 'tableHeader', alignment: 'right' },
                    { text: e.totalprice, style: 'tableHeader', alignment: 'right' },
                    { text: e.expiry, style: 'tableHeader', alignment: 'center'  },
                ];

                ret_arr.push(array);
            });

            return ret_arr;
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