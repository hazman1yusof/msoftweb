<!DOCTYPE html>
<html>
    <head>
        <title>Department of Radiology</title>
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
                        image: 'letterhead', width: 500, style: 'tableHeader', colSpan: 5, alignment: 'center'
                    },
                    {
                        text: 'DEPARTMENT OF RADIOLOGY\n',
                        style: 'header',
                        alignment: 'center',
                    },
                    // { canvas: [ { type: 'line', x1: 0, y1: 5, x2: 515, y2: 5, lineWidth: 0.5 } ] },
                    {
                        style: 'tableExample',
                        table: {
                            widths: [38,38,38,38,38,38,38,38,38,38,38],
                            // headerRows: 5,
                            // keepWithHeaderRows: 5,
                            body: [
                                [
                                    {
                                        text: [
                                            'Patient Name (Capitals)',
                                            { text: '\u200B\t\u200B\t{{strtoupper($pat_radiology->Name)}}' },
                                        ], colSpan: 5, alignment: 'left'
                                    },{},{},{},{},
                                    {
                                        text: [
                                            'Race',
                                            { text: '\u200B\t\u200B\t{{$pat_radiology->RaceCode}}' },
                                        ], colSpan: 3, alignment: 'left'
                                    },{},{},
                                    {
                                        text: [
                                            'iPesakit',
                                            { text: '\u200B\t\u200B\t{{$pat_radiology->iPesakit}}' },
                                        ], colSpan: 3, alignment: 'left'
                                    },{},{},
                                ],
                                [
                                    {
                                        text: [
                                            'Identification Number',
                                            { text: '\u200B\t\u200B\t{{$pat_radiology->Newic}}' },
                                        ], colSpan: 4, alignment: 'left'
                                    },{},{},{},
                                    {
                                        text: [
                                            'Contact No',
                                            { text: '\u200B\t\u200B\t{{$pat_radiology->telhp}}' },
                                        ], colSpan: 3, alignment: 'left'
                                    },{},{},
                                    {
                                        text: [
                                            'Sex',
                                            { text: '\u200B\t{{$pat_radiology->Sex}}' },
                                        ], alignment: 'left'
                                    },
                                    {
                                        text: [
                                            'Weight',
                                            { text: '\u200B\t\u200B\t{{$pat_radiology->weight}} kg' },
                                        ], colSpan: 2, alignment: 'left'
                                    },{},
                                    {
                                        text: [
                                            'Age',
                                            { text: '\u200B\t{{$age}}' },
                                        ], alignment: 'left'
                                    },
                                ],
                                [
                                    @if($pat_radiology->pt_condition == 'walking')
                                    {
                                        text: [
                                            'Patient Condition',
                                            { text: '\u200B\t[√] Walking [  ] On Wheelchair [  ] Strecher' },
                                        ], colSpan: 3, alignment: 'left'
                                    },
                                    @elseif($pat_radiology->pt_condition == 'wheelchair')
                                    {
                                        text: [
                                            'Patient Condition',
                                            { text: '\u200B\t[  ] Walking [√] On Wheelchair [  ] Strecher' },
                                        ], colSpan: 3, alignment: 'left'
                                    },
                                    @elseif($pat_radiology->pt_condition == 'strecher')
                                    {
                                        text: [
                                            'Patient Condition',
                                            { text: '\u200B\t[  ] Walking [  ] On Wheelchair [√] Strecher' },
                                        ], colSpan: 3, alignment: 'left'
                                    },
                                    @else
                                    {
                                        text: [
                                            'Patient Condition',
                                            { text: '\u200B\t[  ] Walking [  ] On Wheelchair [  ] Strecher' },
                                        ], colSpan: 3, alignment: 'left'
                                    },
                                    @endif
                                    {},{},
                                    @if($pat_radiology->newcaseP == '1' || $pat_radiology->followupP == '1')
                                    {
                                        text: [
                                            'Pregnant',
                                            { text: '\u200B\t[√] Yes [  ] No' },
                                        ], colSpan: 3, alignment: 'left'
                                    },
                                    @elseif($pat_radiology->newcaseNP == '1' || $pat_radiology->followupNP == '1')
                                    {
                                        text: [
                                            'Pregnant',
                                            { text: '\u200B\t[  ] Yes [√] No' },
                                        ], colSpan: 3, alignment: 'left'
                                    },
                                    @else
                                    {
                                        text: [
                                            'Pregnant',
                                            { text: '\u200B\t[  ] Yes [  ] No' },
                                        ], colSpan: 3, alignment: 'left'
                                    },
                                    @endif
                                    {},{},
                                    @if(!empty($pat_radiology->LMP))
                                    {
                                        text: [
                                            'LMP',
                                            { text: '\n\n{{\Carbon\Carbon::createFromFormat('Y-m-d',$pat_radiology->LMP)->format('d-m-Y')}}' },
                                        ], colSpan: 2, alignment: 'left'
                                    },
                                    @else
                                    {
                                        text: [
                                            'LMP',
                                            { text: '' },
                                        ], colSpan: 2, alignment: 'left'
                                    },
                                    @endif
                                    {},
                                    {
                                        text: [
                                            'Asthma / Allergy',
                                            { text: `\n\n{!!str_replace('`', '', $pat_radiology->allergyh)!!}` },
                                        ], colSpan: 3, alignment: 'left'
                                    },{},{},
                                ],
                                [
                                    { text: 'Examination', colSpan: 11, border: [true, true, true, false] },
                                    {},{},{},{},{},
                                    {},{},{},{},{},
                                ],
                                [
                                    @if($pat_radiology->xray == '1')
                                    {
                                        text: [
                                            '[√] X-ray',
                                            { text: '\u200B\t Date: {{\Carbon\Carbon::createFromFormat('Y-m-d',$pat_radiology->xray_date)->format('d-m-Y')}}' },
                                            { text: `\nRemarks: {!!str_replace('`', '', $pat_radiology->xray_remark)!!}` },
                                        ], colSpan: 4, alignment: 'left', border: [true, false, false, false]
                                    },
                                    @else
                                    {
                                        text: [
                                            '[  ] X-ray',
                                            { text: '\u200B\t Date:  ' },
                                            { text: '\nRemarks: ' },
                                        ], colSpan: 4, alignment: 'left', border: [true, false, false, false]
                                    },
                                    @endif
                                    {},{},{},
                                    @if($pat_radiology->mri == '1')
                                    {
                                        text: [
                                            '[√] M.R.I',
                                            { text: '\u200B\t Date: {{\Carbon\Carbon::createFromFormat('Y-m-d',$pat_radiology->mri_date)->format('d-m-Y')}}' },
                                            { text: `\nRemarks: {!!str_replace('`', '', $pat_radiology->mri_remark)!!}` },
                                        ], colSpan: 4, alignment: 'left', border: [false, false, false, false]
                                    },
                                    @else
                                    {
                                        text: [
                                            '[  ] M.R.I',
                                            { text: '\u200B\t Date:  ' },
                                            { text: '\nRemarks: ' },
                                        ], colSpan: 4, alignment: 'left', border: [false, false, false, false]
                                    },
                                    @endif
                                    {},{},{},
                                    @if($pat_radiology->angio == '1')
                                    {
                                        text: [
                                            '[√] Angio',
                                            { text: '\u200B\t Date: {{\Carbon\Carbon::createFromFormat('Y-m-d',$pat_radiology->angio_date)->format('d-m-Y')}}' },
                                            { text: `\nRemarks: {!!str_replace('`', '', $pat_radiology->angio_remark)!!}` },
                                        ], colSpan: 3, alignment: 'left', border: [false, false, true, false]
                                    },
                                    @else
                                    {
                                        text: [
                                            '[  ] Angio',
                                            { text: '\u200B\t Date:  ' },
                                            { text: '\nRemarks: ' },
                                        ], colSpan: 3, alignment: 'left', border: [false, false, true, false]
                                    },
                                    @endif
                                    {},{},
                                ],
                                [
                                    @if($pat_radiology->ultrasound == '1')
                                    {
                                        text: [
                                            '[√] Ultrasound',
                                            { text: '\u200B\t Date: {{\Carbon\Carbon::createFromFormat('Y-m-d',$pat_radiology->ultrasound_date)->format('d-m-Y')}}' },
                                            { text: `\nRemarks: {!!str_replace('`', '', $pat_radiology->ultrasound_remark)!!}` },
                                        ], colSpan: 4, alignment: 'left', border: [true, false, false, false]
                                    },
                                    @else
                                    {
                                        text: [
                                            '[  ] Ultrasound',
                                            { text: '\u200B\t Date:  ' },
                                            { text: '\nRemarks: ' },
                                        ], colSpan: 4, alignment: 'left', border: [true, false, false, false]
                                    },
                                    @endif
                                    {},{},{},
                                    @if($pat_radiology->ct == '1')
                                    {
                                        text: [
                                            '[√] C.T',
                                            { text: '\u200B\t Date: {{\Carbon\Carbon::createFromFormat('Y-m-d',$pat_radiology->ct_date)->format('d-m-Y')}}' },
                                            { text: `\nRemarks: {!!str_replace('`', '', $pat_radiology->ct_remark)!!}` },
                                        ], colSpan: 4, alignment: 'left', border: [false, false, false, false]
                                    },
                                    @else
                                    {
                                        text: [
                                            '[  ] C.T',
                                            { text: '\u200B\t Date:  ' },
                                            { text: '\nRemarks: ' },
                                        ], colSpan: 4, alignment: 'left', border: [false, false, false, false]
                                    },
                                    @endif
                                    {},{},{},
                                    @if($pat_radiology->fluroscopy == '1')
                                    {
                                        text: [
                                            '[√] Fluroscopy',
                                            { text: '\u200B\t Date: {{\Carbon\Carbon::createFromFormat('Y-m-d',$pat_radiology->fluroscopy_date)->format('d-m-Y')}}' },
                                            { text: `\nRemarks: {!!str_replace('`', '', $pat_radiology->fluroscopy_remark)!!}` },
                                        ], colSpan: 3, alignment: 'left', border: [false, false, true, false]
                                    },
                                    @else
                                    {
                                        text: [
                                            '[  ] Fluroscopy',
                                            { text: '\u200B\t Date:  ' },
                                            { text: '\nRemarks: ' },
                                        ], colSpan: 3, alignment: 'left', border: [false, false, true, false]
                                    },
                                    @endif
                                    {},{},
                                ],
                                [
                                    @if($pat_radiology->mammogram == '1')
                                    {
                                        text: [
                                            '[√] Mammogram',
                                            { text: '\u200B\t Date: {{\Carbon\Carbon::createFromFormat('Y-m-d',$pat_radiology->mammogram_date)->format('d-m-Y')}}' },
                                            { text: `\nRemarks: {!!str_replace('`', '', $pat_radiology->mammogram_remark)!!}` },
                                        ], colSpan: 4, alignment: 'left', border: [true, false, false, true]
                                    },
                                    @else
                                    {
                                        text: [
                                            '[  ] Mammogram',
                                            { text: '\u200B\t Date:  ' },
                                            { text: '\nRemarks: ' },
                                        ], colSpan: 4, alignment: 'left', border: [true, false, false, true]
                                    },
                                    @endif
                                    {},{},{},
                                    @if($pat_radiology->bmd == '1')
                                    {
                                        text: [
                                            '[√] BMD',
                                            { text: '\u200B\t Date: {{\Carbon\Carbon::createFromFormat('Y-m-d',$pat_radiology->bmd_date)->format('d-m-Y')}}' },
                                            { text: `\nRemarks: {!!str_replace('`', '', $pat_radiology->bmd_remark)!!}` },
                                        ], colSpan: 4, alignment: 'left', border: [false, false, false, true]
                                    },
                                    @else
                                    {
                                        text: [
                                            '[  ] BMD',
                                            { text: '\u200B\t Date:  ' },
                                            { text: '\nRemarks: ' },
                                        ], colSpan: 4, alignment: 'left', border: [false, false, false, true]
                                    },
                                    @endif
                                    {},{},{},
                                    { text: '', colSpan: 3, border: [false, false, true, true] },
                                    {},{},
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
                        style: 'tableExample',
                        table: {
                            widths: [170,170,150],
                            // headerRows: 5,
                            // keepWithHeaderRows: 5,
                            body: [
                                [
                                    {
                                        text: [
                                            { text: 'Clinical Data:', alignment: 'left' },
                                            { text: `\n\n{!!str_replace('`', '', $pat_radiology->clinicaldata)!!}` },
                                            { text: '\n{{strtoupper($pat_radiology->doctorname)}}', alignment: 'right' },
                                            { text: '\n(Consultant/Physician)', bold: true, alignment: 'right' },
                                        ], colSpan: 3, alignment: 'left'
                                    },
                                    {},{},
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
                        style: 'tableExample',
                        table: {
                            widths: [170,170,150],
                            // headerRows: 5,
                            // keepWithHeaderRows: 5,
                            body: [
                                [
                                    {
                                        text: [
                                            { text: 'Radiology Note:', alignment: 'left' },
                                            { text: `\n\n{!!str_replace('`', '', $pat_radiology->rad_note)!!}` },
                                            { text: '\n{{strtoupper($pat_radiology->radiologist)}}', alignment: 'right' },
                                            { text: '\n(Radiologist)', bold: true, alignment: 'right' },
                                            @if(!empty($pat_radiology->rad_note))
                                            { text: '\nDate: {{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$pat_radiology->lastupdate)->format('d-m-Y')}}', alignment: 'right' },
                                            @endif
                                        ], colSpan: 3, alignment: 'left'
                                    },
                                    {},{},
                                ],
                            ]
                        },
                        layout: {
                            fillColor: function (rowIndex, node, columnIndex){
                                // return (rowIndex === 0) ? '#000000' : null;
                            }
                        }
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