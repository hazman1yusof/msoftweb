<!DOCTYPE html>
<html>
    <head>
        <title>PRE-CONTRAST QUESTIONNAIRE</title>
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
                    {
                        text: 'PRE-CONTRAST QUESTIONNAIRE',
                        style: 'header', alignment: 'center', decoration: 'underline', decorationStyle: 'solid',decorationColor: 'black'
                    },
                    {
                        text: '(This form is to be filled in by the requesting doctor at the time of making the request)\n',
                        alignment: 'center',
                        fontSize: 9, italics: true
                    },
                    {
                        style: 'tableExample',
                        table: {
                            // headerRows: 1,
                            widths: [38,38,38,38,38,38,38,38,38,38,38], // panjang standard dia 515
                            body: [
                                [
                                    {
                                        text: [
                                            'Patient Name : ',
                                            { text: '{{strtoupper($preContrast->Name)}}' },
                                        ], colSpan: 5, alignment: 'left', 
                                    },{},{},{},{},
                                    {
                                        text: [
                                            'Age : ',
                                            { text: '{{$age}}' },
                                        ], colSpan: 3, alignment: 'left'
                                    },{},{},
                                    {
                                        text: [
                                            'IC : ',
                                            { text: '{{$preContrast->Newic}}' },
                                        ], colSpan: 3, alignment: 'left'
                                    },{},{},
                                ],
                                [
                                    {
                                        text: [
                                            'Examination : ',
                                            { text: `{!!$preContrast->examination!!}`},
                                        ], colSpan: 11, alignment: 'left',
                                    },{},{},{},{},{},{},{},{},{},{},
                                ],
                                
                            ]
                        },
                        layout: {
                            fillColor: function (rowIndex, node, columnIndex){
                                // return (rowIndex === 0) ? '#000000' : null;
                            }
                        }
                    },
                    { text: '\nATTENTION\n\n', alignment: 'center',fontSize: 10, bold:true, decoration: 'underline', decorationStyle: 'solid',decorationColor: 'black'},
                    {
                        style: 'tableExample2',
                        table: {
                            // headerRows: 1,
                            widths: [15,'*',20,20], // panjang standard dia 515
                            body: [
                                [
                                    {
                                        text: [
                                            'Your patient (may/will require) I.V Contast Media. Is (he/she) in the high-risk group?\nDoes (he/she) have:-',
                                        ], colSpan: 2, style: 'tableHeader',
                                    },{},
                                    { text: 'YES', style: 'tableHeader', alignment: 'center' },
                                    { text: 'NO', style: 'tableHeader', alignment: 'center' }
                                ],
                                [
                                    { text: 'a)' },
                                    { text: 'Define history of allergy?'},
                                    @if($preContrast->hisAllergy == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                    @if($preContrast->hisAllergy == '0')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                ],
                                [
                                    { text: 'b)' },
                                    { text: 'Have Fever/Allergic rhinitis?'},
                                    @if($preContrast->feverAllergic == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                    @if($preContrast->feverAllergic == '0')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                ],
                                [
                                    { text: 'c)' },
                                    { text: 'Previous reaction to contrast media?'},
                                    @if($preContrast->prevReactContrast == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                    @if($preContrast->prevReactContrast == '0')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                ],
                                [
                                    { text: 'd)' },
                                    { text: 'Previous reaction of drug?'},
                                    @if($preContrast->prevReactDrug == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                    @if($preContrast->prevReactDrug == '0')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                ],
                                [
                                    { text: 'e)' },
                                    { text: 'Asthma?'},
                                    @if($preContrast->asthma == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                    @if($preContrast->asthma == '0')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                ],
                                [
                                    { text: 'f)' },
                                    { text: 'Heart Disease?'},
                                    @if($preContrast->heartDisease == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                    @if($preContrast->heartDisease == '0')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                ],
                                [
                                    { text: 'g)' },
                                    { text: 'Very old (< 65 years) or very young (< 1 years)'},
                                    @if($preContrast->veryOldYoung == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                    @if($preContrast->veryOldYoung == '0')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                ],
                                [
                                    { text: 'h)' },
                                    { text: 'Poor general condition?'},
                                    @if($preContrast->poorCondition == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                    @if($preContrast->poorCondition == '0')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                ],
                                [
                                    { text: 'i)' },
                                    { text: 'Dehydrated?'},
                                    @if($preContrast->dehydrated == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                    @if($preContrast->dehydrated == '0')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                ],
                                [
                                    { text: 'j)' },
                                    { text: 'Other serious medical condition?'},
                                    @if($preContrast->seriousMedCondition == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                    @if($preContrast->seriousMedCondition == '0')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                ],
                                [
                                    {
                                        text: [
                                            'If the Answer to any of the above is ',
                                            { text: 'YES', bold: true },
                                            ', please review the indication for the examination. Will an alternative imaging modality suffice?'
                                        ], colSpan: 4,
                                    },{},{},{}
                                ],
                                [
                                    {
                                        text: [
                                            'Patient In-Group ',
                                            { text: '[a)]', bold: true },'to ', { text: '[e)]', bold: true },
                                            ' will need steroid pre-treatment.'
                                        ], colSpan: 4,
                                    },{},{},{}
                                ],
                                [
                                    {
                                        text: [
                                            'Suggested regime -  ',
                                            { text: '(Adult Doses)\n', italics: true },
                                            '\u200B\t\u200B\t\u200B\t\u200B\tTab. Prednisolone 50 mg\u200B\t\u200B\t 12 hours before the procedure and\n\u200B\t\u200B\t\u200B\t\u200B\tTab. Prednisolone 50 mg\u200B\t\u200B\t 2 hours before the procedure',
                                        ], colSpan: 4,
                                    },{},{},{}
                                ],
                                [
                                    { text: 'a)' },
                                    { text: 'Previous contrast media examination'},
                                    @if($preContrast->prevContrastExam == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                    @if($preContrast->prevContrastExam == '0')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                ],
                                [
                                    { text: 'b)' },
                                    { text: 'Consent for procedure where necessary (overleaf)'},
                                    @if($preContrast->consentProcedure == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                    @if($preContrast->consentProcedure == '0')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                ],
                                [
                                    { text: 'c)' },
                                    { text: 'LMP (in female of reproductive age group)'},
                                    { text: '{{\Carbon\Carbon::createFromFormat('Y-m-d',$preContrast->LMP)->format('d-m-Y')}}', colSpan: 2},{},
                                ],
                                [
                                    { text: 'd)' },
                                    { text: 'Renal function (blood Urea/serum Creatinine)'},
                                    { text: '{{$preContrast->renalFunction}}', colSpan: 2},{},
                                ],
                            ],
                        },
                    },
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: ['*'], // panjang standard dia 515
                            body: [
                                [
                                    {text: '\n\n\n\n\n__________________________________________________\n\n(Signature and designation of requesting doctor)',bold: true,alignment: 'right'}, 
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
                        margin: [0, 5, 0, 0]
                    },
                    tableExample2: {
                        fontSize: 8,
                        margin: [0, 0, 0, 0]
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