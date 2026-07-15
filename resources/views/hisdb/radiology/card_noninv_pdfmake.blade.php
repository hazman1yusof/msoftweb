<!DOCTYPE html>
<html>
    <head>
        <title>cardiology non-invasive procedure form</title>
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
                pageMargins: [ 40, 10, 40, 40 ],
                // pageOrientation: 'landscape',
                content: [
                    {
                        image: 'letterhead', width: 430, style: 'tableHeader', alignment: 'left'
                    },
                    {
                        text: 'CARDIOLOGY NON-INVASIVE PROCEDURE FORM',
                        style: 'header', alignment: 'center'
                    },
                    {
                        style: 'tableExample',
                        table: {
                            // headerRows: 1,
                            widths: [20,'*'], // panjang standard dia 515
                            body: [
                                [   
                                    { text: `@if($card_noninv->card_type == 'card_type1'){{'√'}}@endif`, alignment: 'center'},
                                    { text: 'Transthoracic Echocardiogram',border: [false, false, false, false]}
                                ],
                                [
                                    { text: `@if($card_noninv->card_type == 'card_type2'){{'√'}}@endif`, alignment: 'center'},
                                    { text: 'Trans oesophagael Echocardiogram',border: [false, false, false, false]}
                                ],
                                [
                                    { text: `@if($card_noninv->card_type == 'card_type3'){{'√'}}@endif`, alignment: 'center'},
                                    { text: '24 hours Holter Monitoring',border: [false, false, false, false]}
                                ],
                                [
                                    { text: `@if($card_noninv->card_type == 'card_type4'){{'√'}}@endif`, alignment: 'center'},
                                    { text: 'Dobutamine Stress Echo / Treadmill / Bicycle',border: [false, false, false, false]}
                                ],
                                [
                                    { text: `@if($card_noninv->card_type == 'card_type5'){{'√'}}@endif`, alignment: 'center'},
                                    { text: '24 hours Ambulatory Blood Pressure Monitoring',border: [false, false, false, false]}
                                ],
                                
                            ]
                        },
                    },
                    {
                      canvas: [
                        {
                          type: 'line',
                          x1: 0, y1: 5,
                          x2: 515, y2: 5, // 515 matches standard A4 width page body
                          lineWidth: 1,
                          lineColor: 'darkgrey',
                        }
                      ]
                    },
                    {
                        text: 'Patient Particular',style:'normal',bold:true,margin:[0,15,0,0]
                    },
                    {
                        style: 'tableExample',
                        table: {
                            // headerRows: 1,
                            widths: [90,'*'], // panjang standard dia 515
                            body: [
                                [   
                                    { text: '1. Name',border: [false, false, false, false]},
                                    { text: `: {{$card_noninv->card_patname}}`,border: [false, false, false, false]}
                                ],
                                [   
                                    { text: '2. Age',border: [false, false, false, false]},
                                    { text: `: {{$card_noninv->card_age}}`,border: [false, false, false, false]}
                                ],
                                [   
                                    { text: '3. Gender',border: [false, false, false, false]},
                                    { text: `: {{$card_noninv->card_sex}}`,border: [false, false, false, false]}
                                ],
                                [   
                                    { text: '4. NRIC/Passport',border: [false, false, false, false]},
                                    { text: `: {{$card_noninv->card_newic}}`,border: [false, false, false, false]}
                                ],
                                [   
                                    { text: '5. Contact',border: [false, false, false, false]},
                                    { text: `: {{$card_noninv->card_telhp}}`,border: [false, false, false, false]}
                                ],
                                [   
                                    { text: '5. Address',border: [false, false, false, false]},
                                    { text: `: {!!$card_noninv->card_addr!!}`,border: [false, false, false, false]}
                                ]
                                
                            ]
                        },
                    },
                    {
                      canvas: [
                        {
                          type: 'line',
                          x1: 0, y1: 5,
                          x2: 515, y2: 5, // 515 matches standard A4 width page body
                          lineWidth: 1,
                          lineColor: 'darkgrey',
                        }
                      ]
                    },
                    {
                        text: '',style:'normal',margin:[0,15,0,0]
                    },
                    {
                        style: 'tableExample',
                        table: {
                            // headerRows: 1,
                            widths: [90,'*'], // panjang standard dia 515
                            body: [
                                [   
                                    { text: 'Indication',border: [false, false, false, false]},
                                    { text: `: {{$card_noninv->card_ind}}`,border: [false, false, false, false]}
                                ],
                                [   
                                    { text: 'Clinical Detail',border: [false, false, false, false]},
                                    { text: `: {{$card_noninv->card_clindet}}`,border: [false, false, false, false]}
                                ],
                                
                            ]
                        },
                    },
                    {
                      canvas: [
                        {
                          type: 'line',
                          x1: 0, y1: 5,
                          x2: 515, y2: 5, // 515 matches standard A4 width page body
                          lineWidth: 1,
                          lineColor: 'darkgrey',
                        }
                      ]
                    },
                    {
                        text: '',style:'normal',margin:[0,15,0,0]
                    },
                    {
                        style: 'tableExample',
                        table: {
                            // headerRows: 1,
                            widths: [90,250,80,100], // panjang standard dia 515
                            body: [
                                [   
                                    { text: 'Request By (DR Name)',border: [false, false, false, false],margin:[0,0,-10,0]},
                                    { text: `: {{$card_noninv->card_docname}}`,border: [false, false, false, false]},
                                    { text: 'Date',border: [false, false, false, false]},
                                    { text: `: @if(!empty($card_noninv->card_date)){{\Carbon\Carbon::createFromFormat('Y-m-d',$card_noninv->card_date)->format('d-m-Y')}}@endif`,border: [false, false, false, false]}
                                ],
                                [   
                                    { text: '(Ward/Clinic)',border: [false, false, false, false],margin:[38,0,-10,0]},
                                    { text: `: {{$card_noninv->card_wardclinic}}`,border: [false, false, false, false]},
                                    { text: 'Entered By',border: [false, false, false, false]},
                                    { text: `: {{$card_noninv->card_adduser}}`,border: [false, false, false, false]}
                                ],
                                [   
                                    { text: 'Clinical Appointment',border: [false, false, false, false]},
                                    { text: `: @if(!empty($card_noninv->card_apptdate)){{\Carbon\Carbon::createFromFormat('Y-m-d',$card_noninv->card_apptdate)->format('d-m-Y')}}@endif`,border: [false, false, false, false]},
                                    { text: '',border: [false, false, false, false]},
                                    { text: ``,border: [false, false, false, false]}
                                ],
                                
                            ]
                        },
                    },
                    {
                      canvas: [
                        {
                          type: 'line',
                          x1: 0, y1: 5,
                          x2: 515, y2: 5, // 515 matches standard A4 width page body
                          lineWidth: 1,
                          lineColor: 'darkgrey',
                        }
                      ]
                    },
                    {
                        text: '',style:'normal',margin:[0,15,0,0]
                    },
                    {
                        style: 'tableExample',
                        table: {
                            // headerRows: 1,
                            widths: [90,'*'], // panjang standard dia 515
                            body: [
                                [   
                                    { text: 'Checked By',border: [false, false, false, false]},
                                    { text: `: {{$card_noninv->card_chkby}}`,border: [false, false, false, false]},
                                ],
                            ]
                        },
                    },
                    {
                        text: '',style:'normal',margin:[0,5,0,0]
                    },
                    {
                        style: 'tableExample',
                        table: {
                            // headerRows: 1,
                            widths: [10,'*',10,'*',10,'*',10,'*'], // panjang standard dia 515
                            body: [
                                [   
                                    { text: `@if($card_noninv->card_chkty == 'card_chkty1'){{'√'}}@endif`, alignment: 'center'},
                                    { text: 'Immediate',border: [false, false, false, false]},
                                    { text: `@if($card_noninv->card_chkty == 'card_chkty2'){{'√'}}@endif`, alignment: 'center'},
                                    { text: 'Early',border: [false, false, false, false]},
                                    { text: `@if($card_noninv->card_chkty == 'card_chkty3'){{'√'}}@endif`, alignment: 'center'},
                                    { text: 'Routine',border: [false, false, false, false]},
                                    { text: `@if($card_noninv->card_chkty == 'card_chkty4'){{'√'}}@endif`, alignment: 'center'},
                                    { text: 'Insufficient Detail',border: [false, false, false, false]},
                                ],
                            ]
                        },
                    },
                    {
                        text: '',style:'normal',margin:[0,5,0,0]
                    },
                    {
                        style: 'tableExample',
                        table: {
                            // headerRows: 1,
                            widths: [90,'*'], // panjang standard dia 515
                            body: [
                                [   
                                    { text: 'Test Appointment',border: [false, false, false, false]},
                                    { text: `: @if(!empty($card_noninv->card_testapptdate)){{\Carbon\Carbon::createFromFormat('Y-m-d',$card_noninv->card_testapptdate)->format('d-m-Y')}}@endif`,border: [false, false, false, false]},
                                ],
                            ]
                        },
                    },
                ],
                styles: {
                    header: {
                        fontSize: 14,
                        bold: true,
                        margin: [0, 0, 0, 10]
                    },
                    normal:{
                        fontSize: 9,
                    },
                    tableExample: {
                        fontSize: 9,
                        margin: [0, 0, 0, 0]
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