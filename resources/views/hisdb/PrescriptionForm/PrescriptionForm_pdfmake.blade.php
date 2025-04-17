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
        
        var pharmacy = [
            {
                date:'date',
                supply:'supply',
                verify:'verify',
                fill:'fill',
                check:'check',
                dispense:'dispense',
                remark:'remark',
            },{
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
        
        var Sex = 'Male';
        var Dates = '25/2/2025';
        var Address1 = 'Tanah Surgo Ayah Abdul';
        var Address2 = 'Jalan Neraka';
        var Address3 = '57000 Kuala Lumpur';
        var SpecialistName = 'Assoc. Prof Dr Tuti Iryani';
        var Speciality = 'Psychiatrist';
        var PrescriptionDate = '25-Feb-25';
        
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
                                        image: 'letterhead', height: 30,width: 175, alignment: 'left'
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
                            widths: [20,130,40,40,42,40,40,40,40,40],
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
                        margin: [0, -5, 0, -15],
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
                ]
            ];

            pharmacy.forEach(function(e,i){
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
            });

            return ret_arr;
        }

        function make_body2(){
            let ret_arr = [
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
                ],[
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
                ]
                
            ];

            // medications.forEach(function(e,i){
            //     let array = [
            //         { text: e.date, alignment: 'center' },
            //         { text: e.supply, alignment: 'center' },
            //         { text: e.verify, alignment: 'center' },
            //         { text: e.fill, alignment: 'center' },
            //         { text: e.check, alignment: 'center' },
            //         { text: e.dispense, alignment: 'center' },
            //         { text: e.remark, alignment: 'center' }
            //     ];
            //     ret_arr.push(array);
            // });

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