<!DOCTYPE html>
<html>
    <head>
        <title>Montreal Cognitive Assessment (MOCA)</title>
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
                        text: ' MONTREAL COGNITIVE ASSESSMENT (MOCA)',
                        style: 'header',
                        alignment: 'center',
                    },
                    {
                        style: 'tableExample',
                        table: {
                            // headerRows: 1,
                            widths: [70,3,'*',60,3,'*'], // panjang standard dia 515
                            body: [
                                [
                                    { text: 'Name' },
                                    { text: ':' },
                                    { text: `{!!$moca->Name!!}`,},
                                    { text: 'MRN' },
                                    { text: ':' },
                                    { text: '{{str_pad($moca->mrn, 7, "0", STR_PAD_LEFT)}}' },
                                ],
                                [
                                    { text: 'Education' },
                                    { text: ':' },
                                    { text: '{{$moca->education}}' },
                                    { text: 'Date' },
                                    { text: ':' },
                                    { text: '{{\Carbon\Carbon::createFromFormat('Y-m-d',$moca->dateAssessment)->format('d-m-Y')}}' },
                                ],
                            ]
                            
                        },
                        layout: 'noBorders',
                    },
                    {
                        style: 'tableExample2',
                        table: {
                            headerRows: 1,
                            widths: ['*','*'], // panjang standard dia 515
                            body: [
                                [
                                    { text: 'Assessment', style: 'tableHeader', alignment: 'left', fillColor: '#dddddd' },
                                    { text: 'Points', style: 'tableHeader', alignment: 'center', fillColor: '#dddddd' },
                                ],
                                [
                                    { text: 'Visuospatial/Executive' },
                                    { text: '{{$moca->visuospatial}}/5', alignment: 'center' },
                                ],
                                [
                                    { text: 'Naming' },
                                    { text: '{{$moca->naming}}/3', alignment: 'center' },
                                ],
                                [
                                    { text: 'Memory' },
                                    { text: 'No Points', alignment: 'center' },
                                ],
                                [
                                    { text: 'Attention' },
                                    { text: '', fillColor: '#dddddd' },
                                ],
                                [
                                    { text: '\u200B\t\u200B\t- Read list of digits' },
                                    { text: '{{$moca->attention1}}/2', alignment: 'center'},
                                ],
                                [
                                    { text: '\u200B\t\u200B\t- Read list of letters' },
                                    { text: '{{$moca->attention2}}/1', alignment: 'center'},
                                ],
                                [
                                    { text: '\u200B\t\u200B\t- Serial 7 substraction starting at 100' },
                                    { text: '{{$moca->attention3}}/3', alignment: 'center'},
                                ],
                                [
                                    { text: 'Language' },
                                    { text: '', fillColor: '#dddddd' },
                                ],
                                [
                                    { text: '\u200B\t\u200B\t- Repeat' },
                                    { text: '{{$moca->languageRepeat}}/2', alignment: 'center'},
                                ],
                                [
                                    { text: '\u200B\t\u200B\t- Fluency' },
                                    { text: '{{$moca->languageFluency}}/1', alignment: 'center'},
                                ],
                                [
                                    { text: 'Abstraction' },
                                    { text: '{{$moca->abstraction}}/2', alignment: 'center' },
                                ],
                                [
                                    { text: 'Delayed Recall' },
                                    { text: '{{$moca->delayed}}/5', alignment: 'center' },
                                ],
                                [
                                    { text: 'Orientation' },
                                    { text: '{{$moca->orientation}}/6', alignment: 'center' },
                                ],
                                [
                                    { text: 'TOTAL', bold:true },
                                    { text: '{{$moca->tot_moca}}/30', alignment: 'center' },
                                ],
                            ],
                        },
                        layout: {
                            fillColor: function (rowIndex, node, columnIndex){
                                // return (rowIndex === 0) ? '#000000' : null;
                            }
                        }                    
                    },
                    { text: '\nNormal ≥ 26/30\nAdd 1 point if ≤ 12 years education',fontSize: 10, bold:true,},

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
                        fontSize: 9,
                        margin: [0, 5, 0, 0]
                    },
                    tableExample2: {
                        fontSize: 10,
                        margin: [0, 5, 0, 0]
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
                    },
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