<!DOCTYPE html>
<html>
    <head>
        <title>Discharge Summary</title>
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
                        image: 'letterhead', width: 500, style: 'tableHeader', colSpan: 5, alignment: 'center'
                    },
                    {
                        text: '\nDISCHARGE SUMMARY\n',
                        style: 'header',
                        alignment: 'center',
                    },
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: [60, '*',60,94,'*'], //panjang standard dia 515
                            body: [
                                [
                                    {text: 'NAME ' },
                                    {text: ': {{$discharge->Name}}'},{},
                                    {text: 'MRN ' },
                                    {text: ': {{str_pad($discharge->mrn, 7, "0", STR_PAD_LEFT)}}'},
                                ],
                                [
                                    {text: 'DATE OF\nADMISSION '},
                                    {text: ': {{\Carbon\Carbon::createFromFormat('Y-m-d',$discharge->reg_date)->format('d-m-Y')}}'},{},
                                    {text: 'DATE OF DISCHARGE '},
                                    @if(!empty($discharge->dischargedate))
                                        {text: ': {{\Carbon\Carbon::createFromFormat('Y-m-d',$discharge->dischargedate)->format('d-m-Y')}}'},
                                    @else
                                        { text: ': ' },
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
                            widths: [80,'*'], //panjang standard dia 515
                            body: [
                                [
                                    {text: 'FINAL DIAGNOSIS',bold: true,},
                                    {text: `{!!$discharge->diagfinal!!}`},
                                ],
                                [
                                    {text: 'PATOLOGIST\n(if related)',bold: true,},
                                    {text: `{!!$discharge->patologist!!}`},
                                ],
                                [
                                    {text: 'HISTORY OF ILLNESS',bold: true,},
                                    {text: `{!!$discharge->clinicalnote!!}`},
                                ],
                                [
                                    {text: 'PHYSICAL EXAMINATION',bold: true,},
                                    {text: `{!!$discharge->phyexam!!}`},
                                ],
                                [
                                    {text: 'INVESTIGATION',bold: true,},
                                    {text: `{!!$discharge->diagprov!!}`},
                                ],
                                [
                                    {text: 'TREATMENT & MEDICATION',bold: true,},
                                    {text: `{!!$discharge->treatment!!}`},
                                ],
                                [
                                    {text: 'SUMMARY',bold: true,},
                                    {text: `{!!$discharge->summary!!}`},
                                ],
                                [
                                    {text: 'FOLLOW UP',bold: true,},
                                    {text: `{!!$discharge->followup!!}`},
                                ],
                            ]
                        },
                        // layout: 'lightHorizontalLines',
                    },
                    {
                        style: 'body_sign',
                        table: {
                            widths: ['*'],//panjang standard dia 515
                            body: [
                                [
                                   
                                    {text: `\n\n\n\n\n_____________________________________\n\nName & Signature of Attending Doctor`,bold: true,alignment: 'left'},

                                ]
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
                        fontSize: 7.5,
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
                    },
                    body_sign: {
                        fontSize: 8,
                        margin: [1, 20, 0, 0]//left, top, right, bottom
				    },
                },
                images: {
                    letterhead: {
                        url: "{{asset('/img/logo/IMSCletterhead.png')}}",
                        headers: {
                            myheader: '123',
                            myotherheader: 'abc',
                            margin: [1, 5, 0, 0]//left, top, right, bottom

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