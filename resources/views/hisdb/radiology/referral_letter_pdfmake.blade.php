<!DOCTYPE html>
<html>
    <head>
        <title>REFERRAL LETTER</title>
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
                        text: 'REFERRAL LETTER',
                        style: 'header', alignment: 'center'
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
                        text: '',style:'normal',margin:[0,10,0,0]
                    },
                    {
                        style: 'tableExample',
                        table: {
                            // headerRows: 1,
                            widths: [100,10,80,10,80,10,80], // panjang standard dia 515
                            body: [
                                [   
                                    { text: 'Referral must be to ',border: [false, false, false, false]},
                                    { text: `@if($patreferral->refto == 'MO'){{'√'}}@endif`, alignment: 'center'},
                                    { text: 'Medical Officer',border: [false, false, false, false]},
                                    { text: `@if($patreferral->refto == 'CS'){{'√'}}@endif`, alignment: 'center'},
                                    { text: 'Clinical Specialist',border: [false, false, false, false]},
                                    { text: `@if($patreferral->refto == 'CONS'){{'√'}}@endif`, alignment: 'center'},
                                    { text: 'Consultant',border: [false, false, false, false]},
                                ],
                            ]
                        },
                    },
                    {
                        text: '',style:'normal',margin:[0,10,0,0]
                    },
                    {
                        style: 'tableExample',
                        table: {
                            // headerRows: 1,
                            widths: [90,'*'], // panjang standard dia 515
                            body: [
                                [   
                                    { text: 'To',border: [false, false, false, false]},
                                    { text: `: {{$patreferral->refdocname}}`,border: [false, false, false, false]},
                                ],
                            ]
                        },
                    },
                    {
                        text: '',style:'normal',margin:[0,10,0,0]
                    },
                    {
                        columns: [
                            {
                                width: 327,
                                table: {
                                    // headerRows: 1,
                                    widths: [90,'*'], // panjang standard dia 515
                                    body: [
                                        [   
                                            { text: 'Department/Unit',border: [false, false, false, false],style: 'normal'},
                                            { text: `: {{$patreferral->refdocdept}}`,border: [false, false, false, false],style: 'normal'},
                                        ],
                                    ]
                                }
                            },
                            {
                                width: '*',
                                table: {
                                    // headerRows: 1,
                                    widths: [10,80,10,80], // panjang standard dia 515
                                    body: [
                                        [   
                                            { text: `@if($patreferral->refprio == 'URG'){{'√'}}@endif`, alignment: 'center',style: 'normal'},
                                            { text: 'Urgent',border: [false, false, false, false],style: 'normal'},
                                            { text: `@if($patreferral->refprio == 'NOTURG'){{'√'}}@endif`, alignment: 'center',style: 'normal'},
                                            { text: 'Not Urgent',border: [false, false, false, false],style: 'normal'},
                                        ],
                                    ]
                                },
                            },
                        ]
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
                        text: '',style:'normal',margin:[0,10,0,0]
                    },
                    {
                        style: 'tableExample',
                        table: {
                            // headerRows: 1,
                            widths: [90,'*'], // panjang standard dia 515
                            body: [
                                [   
                                    { text: 'Patient Name',border: [false, false, false, false]},
                                    { text: `: {{$patreferral->refpatname}}`,border: [false, false, false, false]},
                                ],
                            ]
                        },
                    },
                    {
                        text: '',style:'normal',margin:[0,10,0,0]
                    },
                    {
                        columns: [
                            {
                                width: '*',
                                table: {
                                    // headerRows: 1,
                                    widths: [90,'*'], // panjang standard dia 515
                                    body: [
                                        [   
                                            { text: 'Age',border: [false, false, false, false],style: 'normal'},
                                            { text: `: {{$patreferral->refage}}`,border: [false, false, false, false],style: 'normal'},
                                        ],
                                    ]
                                }
                            },
                            {
                                width: '*',
                                table: {
                                    // headerRows: 1,
                                    widths: [90,'*'], // panjang standard dia 515
                                    body: [
                                        [   
                                            { text: 'Sex',border: [false, false, false, false],style: 'normal'},
                                            { text: `: {{$patreferral->refsex}}`,border: [false, false, false, false],style: 'normal'},
                                        ],
                                    ]
                                }
                            },
                        ]
                    },
                    {
                        text: '',style:'normal',margin:[0,10,0,0]
                    },
                    {
                        columns: [
                            {
                                width: '*',
                                table: {
                                    // headerRows: 1,
                                    widths: [90,'*'], // panjang standard dia 515
                                    body: [
                                        [   
                                            { text: 'I/C / Passport',border: [false, false, false, false],style: 'normal'},
                                            { text: `: {{$patreferral->refnewic}}`,border: [false, false, false, false],style: 'normal'},
                                        ],
                                    ]
                                }
                            },
                            {
                                width: '*',
                                table: {
                                    // headerRows: 1,
                                    widths: [90,'*'], // panjang standard dia 515
                                    body: [
                                        [   
                                            { text: 'Referral Number',border: [false, false, false, false],style: 'normal'},
                                            { text: `: {{$patreferral->reffno}}`,border: [false, false, false, false],style: 'normal'},
                                        ],
                                    ]
                                }
                            },
                        ]
                    },
                    {
                        text: '',style:'normal',margin:[0,10,0,0]
                    },
                    {
                        columns: [
                            {
                                width: '*',
                                table: {
                                    // headerRows: 1,
                                    widths: [90,'*'], // panjang standard dia 515
                                    body: [
                                        [   
                                            { text: 'Date',border: [false, false, false, false],style: 'normal'},
                                            { text: `: {{$patreferral->refdate}}`,border: [false, false, false, false],style: 'normal'},
                                        ],
                                    ]
                                }
                            },
                            {
                                width: '*',
                                table: {
                                    // headerRows: 1,
                                    widths: [90,'*'], // panjang standard dia 515
                                    body: [
                                        [   
                                            { text: 'Time',border: [false, false, false, false],style: 'normal'},
                                            { text: `: {{$patreferral->reftime}}`,border: [false, false, false, false],style: 'normal'},
                                        ],
                                    ]
                                }
                            },
                        ]
                    },
                    {
                        text: '',style:'normal',margin:[0,10,0,0]
                    },
                    {
                        style: 'tableExample',
                        table: {
                            // headerRows: 1,
                            widths: [90,'*'], // panjang standard dia 515
                            body: [
                                [   
                                    { text: 'Patient History',border: [false, false, false, false]},
                                    { text: `: {{$patreferral->refpathist}}`,border: [false, false, false, false]},
                                ],
                            ]
                        },
                    },
                    {
                        text: '',style:'normal',margin:[0,10,0,0]
                    },
                    {
                        style: 'tableExample',
                        table: {
                            // headerRows: 1,
                            widths: [90,'*'], // panjang standard dia 515
                            body: [
                                [   
                                    { text: 'Physical finding',border: [false, false, false, false]},
                                    { text: `: {{$patreferral->refphyfin}}`,border: [false, false, false, false]},
                                ],
                            ]
                        },
                    },
                    {
                        text: '',style:'normal',margin:[0,10,0,0]
                    },
                    {
                        style: 'tableExample',
                        table: {
                            // headerRows: 1,
                            widths: [90,'*'], // panjang standard dia 515
                            body: [
                                [   
                                    { text: 'Diagnosis',border: [false, false, false, false]},
                                    { text: `: {{$patreferral->refdiag}}`,border: [false, false, false, false]},
                                ],
                            ]
                        },
                    },
                    {
                        text: '',style:'normal',margin:[0,10,0,0]
                    },
                    {
                        style: 'tableExample',
                        table: {
                            // headerRows: 1,
                            widths: [90,'*'], // panjang standard dia 515
                            body: [
                                [   
                                    { text: 'Result of investigation',border: [false, false, false, false]},
                                    { text: `: {{$patreferral->refinvres}}`,border: [false, false, false, false]},
                                ],
                            ]
                        },
                    },
                    {
                        text: '',style:'normal',margin:[0,10,0,0]
                    },
                    {
                        style: 'tableExample',
                        table: {
                            // headerRows: 1,
                            widths: [90,'*'], // panjang standard dia 515
                            body: [
                                [   
                                    { text: 'Treatment',border: [false, false, false, false]},
                                    { text: `: {{$patreferral->reftreat}}`,border: [false, false, false, false]},
                                ],
                            ]
                        },
                    },
                    {
                        text: '',style:'normal',margin:[0,10,0,0]
                    },
                    {
                        style: 'tableExample',
                        table: {
                            // headerRows: 1,
                            widths: [90,'*'], // panjang standard dia 515
                            body: [
                                [   
                                    { text: 'Purpose',border: [false, false, false, false]},
                                    { text: `: {{$patreferral->refpurpose}}`,border: [false, false, false, false]},
                                ],
                            ]
                        },
                    },
                    {
                        text: '',style:'normal',margin:[0,10,0,0]
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
                        text: '',style:'normal',margin:[0,10,0,0]
                    },
                    {
                        style: 'tableExample',
                        table: {
                            // headerRows: 1,
                            widths: [100,10,80,10,80,10,80], // panjang standard dia 515
                            body: [
                                [   
                                    { text: 'From ',border: [false, false, false, false]},
                                    { text: `@if($patreferral->reffro == 'MO'){{'√'}}@endif`, alignment: 'center'},
                                    { text: 'Medical Officer',border: [false, false, false, false]},
                                    { text: `@if($patreferral->reffro == 'CS'){{'√'}}@endif`, alignment: 'center'},
                                    { text: 'Clinical Specialist',border: [false, false, false, false]},
                                    { text: `@if($patreferral->reffro == 'CONS'){{'√'}}@endif`, alignment: 'center'},
                                    { text: 'Consultant',border: [false, false, false, false]},
                                ],
                            ]
                        },
                    },
                    {
                        text: '',style:'normal',margin:[0,10,0,0]
                    },
                    {
                        columns: [
                            {
                                width: '*',
                                table: {
                                    // headerRows: 1,
                                    widths: [90,'*'], // panjang standard dia 515
                                    body: [
                                        [   
                                            { text: 'Name',border: [false, false, false, false],style: 'normal'},
                                            { text: `: {{$patreferral->refname}}`,border: [false, false, false, false],style: 'normal'},
                                        ],
                                    ]
                                }
                            },
                            {
                                width: '*',
                                table: {
                                    // headerRows: 1,
                                    widths: [90,'*'], // panjang standard dia 515
                                    body: [
                                        [   
                                            { text: 'Signature',border: [false, false, false, false],style: 'normal'},
                                            { text: `:`,border: [false, false, false, false],style: 'normal'},
                                        ],
                                    ]
                                }
                            },
                        ]
                    },
                    {
                        text: '',style:'normal',margin:[0,10,0,0]
                    },
                    {
                        columns: [
                            {
                                width: '*',
                                table: {
                                    // headerRows: 1,
                                    widths: [90,'*'], // panjang standard dia 515
                                    body: [
                                        [   
                                            { text: 'Department / Unit',border: [false, false, false, false],style: 'normal'},
                                            { text: `: {{$patreferral->refdept}}`,border: [false, false, false, false],style: 'normal'},
                                        ],
                                    ]
                                }
                            },
                            {
                                width: '*',
                                table: {
                                    // headerRows: 1,
                                    widths: [90,'*'], // panjang standard dia 515
                                    body: [
                                        [   
                                            { text: 'Phone Number',border: [false, false, false, false],style: 'normal'},
                                            { text: `: {{$patreferral->refphone}}`,border: [false, false, false, false],style: 'normal'},
                                        ],
                                    ]
                                }
                            },
                        ]
                    },
                    // { text: `{{$patreferral->reffreetxt}}`, style: 'normal' , pageBreak:'before'}
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