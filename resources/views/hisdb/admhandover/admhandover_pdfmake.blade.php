<!DOCTYPE html>
<html>
    <head>
        <title>Admission Handover</title>
    </head>
    
    <!-- <script src="https://unpkg.com/@jsreport/browser-client/dist/jsreport.umd.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="mydata.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha512-a9NgEEK7tsCvABL7KqtUTQjl69z7091EVPpw5KxPlZ93T141ffe1woLtbXTX+r2/8TtTvRX/v4zTL2UlMUPgwg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.min.js" integrity="sha512-P0bOMePRS378NwmPDVPU455C/TuxDS+8QwJozdc7PGgN8kLqR4ems0U/3DeJkmiE31749vYWHvBOtR+37qDCZQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
    </object>
    
    <script>
        
        $(document).ready(function () {
            var docDefinition = {
                footer: function(currentPage, pageCount) {
                    return [
                        { text: currentPage.toString() + ' of ' + pageCount, alignment: 'center' }
                    ]
                },
                pageSize: 'A4',
                // pageOrientation: 'landscape',
                content: [
                    {
                        image: 'letterhead', width: 175, style: 'tableHeader', colSpan: 5, alignment: 'center'
                    },
                    {
                        text: '\nADMISSION HANDOVER\n',
                        style: 'header',
                        alignment: 'center',
                    },
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: [80, '*',80,94,'*'], //panjang standard dia 515
                            body: [
                                [
                                    {text: 'NAME ' },
                                    {text: ': {{$admhandover->Name}}'},{},
                                    {text: 'NRIC ' },
                                    {text: ': {{$admhandover->Newic}}'},
                                ],
                                [
                                    {text: 'MRN ' },
                                    {text: ': {{str_pad($admhandover->mrn, 7, "0", STR_PAD_LEFT)}}'},{},
                                    {text: 'DOCTOR ' },
                                    {text: ': {{$admhandover->doctorname}}'},
                                ],
                                [
                                    {text: 'DIAGNOSIS ' },
                                    {text: `: {!!$admhandover->diagnosis!!}`},{},
                                    {text: 'WEIGHT ' },
                                    {text: ': {{$admhandover->vs_weight}} KG'},
                                ],
                                [
                                    {text: 'DATE OF ADMISSION ' },
                                    {text: ': {{\Carbon\Carbon::createFromFormat('Y-m-d',$admhandover->dateofadm)->format('d-m-Y')}}'},{},
                                    {text: 'REASON ADMISSION ' },
                                    {text: ': {{$admhandover->reasonadm}}'},
                                ],
                                [
                                    {text: 'INPATIENT/DAYCARE ' },
                                    {text: ': {{$admhandover->type}}'},{},{},{}
                                ],
                            ]
                        },
                        layout: 'noBorders',
                    },
                    {
                        style: 'tableExample',
                        table: {
                            headerRows:1,
                            widths: [80, '*'], //panjang standard dia 515
                            body: [
                                [
                                    {text: 'MEDICAL HISTORY '},
                                    {text: `: {!!$admhandover->medicalhistory!!}`},
                                ],
                                [
                                    {text: 'SURGICAL HISTORY '},
                                    {text: `: {!!$admhandover->surgicalhistory!!}`},
                                ],
                            ]
                        },
                        layout: 'noBorders',
                    },
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: [80, '*'], //panjang standard dia 515
                            body: [
                                [
                                    {text: 'ALLERGY ',bold: true,},
                                    {},
                                ],
                                [
                                    @if(!empty(($admhandover->allergydrugs && $admhandover->drugs_remarks)))
                                    {text: 'DRUGS ' },
                                    {text: `: {!!$admhandover->drugs_remarks!!}`},
                                    @else
                                    {},{},
                                    @endif
                                ],
                                [
                                    @if(!empty(($admhandover->allergyplaster && $admhandover->plaster_remarks)))
                                    {text: 'PLASTER ' },
                                    {text: `: {!!$admhandover->plaster_remarks!!}`},
                                    @else
                                    {},{},
                                    @endif
                                ],
                                [
                                    @if(!empty(($admhandover->allergyfood && $admhandover->food_remarks)))
                                    {text: 'FOOD ' },
                                    {text: `: {!!$admhandover->food_remarks!!}`},
                                    @else
                                    {},{},
                                    @endif
                                ],
                                [
                                    @if(!empty(($admhandover->allergyenvironment && $admhandover->environment_remarks)))
                                    {text: 'ENVIRONMENT ' },
                                    {text: `: {!!$admhandover->environment_remarks!!}`},
                                    @else
                                    {},{},
                                    @endif
                                ],
                                [
                                    @if(!empty(($admhandover->allergyothers && $admhandover->others_remarks)))
                                    {text: 'OTHERS ' },
                                    {text: `: {!!$admhandover->others_remarks!!}`},
                                    @else
                                    {},{},
                                    @endif 
                                ],
                                [
                                    @if(!empty(($admhandover->allergyunknown && $admhandover->unknown_remarks)))
                                    {text: 'UNKNOWN ' },
                                    {text: `: {!!$admhandover->unknown_remarks!!}`},
                                    @else
                                    {},{},
                                    @endif
                                ],
                                [
                                    @if(!empty(($admhandover->allergynone && $admhandover->none_remarks)))
                                    {text: 'NONE ' },
                                    {text: `: {!!$admhandover->none_remarks!!}`},
                                    @else
                                    {},{},
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
                            widths: [20,'*',80,'*'], //panjang standard dia 515
                            body: [
                                [
                                    {text: 'NO',bold: true,},
                                    {text: 'PLAN',bold: true,},
                                    {text: '',bold: true,},
                                    {text: 'REMARK',bold: true,},
                                ],
                                [
                                    {text: '1.'},
                                    {text: 'RTK/PCR'},
                                    @if($admhandover->rtkpcr == '1')
                                        { text: '√'},
                                    @else
                                        { text: '' },
                                    @endif
                                    {text: '{{$admhandover->rtkpcr_remark}}'},
                                ],
                                [
                                    {text: '2.'},
                                    {text: 'BLOOD INVESTIGATION'},
                                    @if($admhandover->bloodinv == '1')
                                        { text: '√'},
                                    @else
                                        { text: '' },
                                    @endif
                                    {text: '{{$admhandover->bloodinv_remark}}'},
                                ],
                                [
                                    {text: '3.'},
                                    {text: 'BRANULA'},
                                    @if($admhandover->branula == '1')
                                        { text: '√'},
                                    @else
                                        { text: '' },
                                    @endif
                                    {text: '{{$admhandover->branula_remark}}'},
                                ],
                                [
                                    {text: '4.'},
                                    {text: 'CXR/MRI/CT SCAN'},
                                    @if($admhandover->scan == '1')
                                        { text: '√'},
                                    @else
                                        { text: '' },
                                    @endif
                                    {text: '{{$admhandover->scan_remark}}'},
                                ],
                                [
                                    {text: '5.'},
                                    {text: 'INSURANCE'},
                                    @if($admhandover->insurance == '1')
                                        { text: '√'},
                                    @else
                                        { text: '' },
                                    @endif
                                    {text: '{{$admhandover->insurance_remark}}'},
                                ],
                                [
                                    {text: '6.'},
                                    {text: 'MEDICATION (ANTIPLATLET)'},
                                    @if($admhandover->medication == '1')
                                        { text: '√'},
                                    @else
                                        { text: '' },
                                    @endif
                                    {text: '{{$admhandover->medication_remark}}'},
                                ],
                                [
                                    {text: '7.'},
                                    {text: 'CONSENT'},
                                    @if($admhandover->consent == '1')
                                        { text: '√'},
                                    @else
                                        { text: '' },
                                    @endif
                                    {text: '{{$admhandover->consent_remark}}'},
                                ],
                                [
                                    {text: '8.'},
                                    {text: 'SMOKING'},
                                    @if($admhandover->smoking == '1')
                                        { text: '√'},
                                    @else
                                        { text: '' },
                                    @endif
                                    {text: 'LAST TIME: {{$admhandover->smoking_remark}}'},
                                ],
                                [
                                    {text: '9.'},
                                    {text: 'NBM'},
                                    @if($admhandover->nbm == '1')
                                        { text: '√'},
                                    @else
                                        { text: '' },
                                    @endif
                                    {text: 'LAST MEAL: {{$admhandover->nbm_remark}}'},
                                ],
                            ]
                        },
                        layout: 'lightHorizontalLines',
                    },
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: ['*'], //panjang standard dia 515
                            body: [
                                [
                                    {text: `REPORT: \n\n{!!$admhandover->report!!}`},
                                ],
                            ],
                        },
                        layout: 'noBorders',
                    },
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: ['*', '*'],//panjang standard dia 515

                            body: [
                                [
                                    {text: 'PASS OVER BY: \n\n'}, 
                                    {text: 'TAKE OVER BY: \n\n'}, 
                                ],
                                [
                                    {text: '{{$admhandover->passoverby}}', fontSize: 8, bold:true},
                                    @if(!empty($admhandover->takeoverby))
                                        {text: '{{$admhandover->takeoverby}}', fontSize: 8, bold:true},
                                    @else
                                        {text: '' },
                                    @endif                                    
                                ],
                                [
                                    {text: '{{\Carbon\Carbon::parse($admhandover->adddate)->format('d/m/Y')}}', fontSize: 8},
                                    {text: '{{\Carbon\Carbon::parse($admhandover->adddate)->format('d/m/Y')}}', fontSize: 8},
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
                    tableExample: {
                        fontSize: 8,
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
                images: {
                    letterhead: {
                        url: "{{asset('/img/letterheadukm.png')}}",
                        headers: {
                            myheader: '123',
                            myotherheader: 'abc',
                        }
                    }
                }
            };
            
            // pdfMake.createPdf(docDefinition).getBase64(function(data) {
            //     var base64data = "data:base64"+data;
            //     console.log($('object#pdfPreview').attr('data',base64data));
            //     // document.getElementById('pdfPreview').data = base64data;
            // });
            
            pdfMake.createPdf(docDefinition).getDataUrl(function(dataURL) {
                $('#pdfiframe').attr('src',dataURL);
            });
        });
        
        // pdfMake.createPdf(docDefinition).getDataUrl(function(dataURL) {
        //     console.log(dataURL);
        //     document.getElementById('pdfPreview').data = dataURL;
        // });
        
        // jsreport.serverUrl = 'http://localhost:5488'
        // async function preview() {
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