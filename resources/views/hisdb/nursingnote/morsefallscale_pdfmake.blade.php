<!DOCTYPE html>
<html>
    <head>
        <title>Daily Morse Fall Scale Assessment Chart</title>
    </head>
    
    <!-- <script src="https://unpkg.com/@jsreport/browser-client/dist/jsreport.umd.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="mydata.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha512-a9NgEEK7tsCvABL7KqtUTQjl69z7091EVPpw5KxPlZ93T141ffe1woLtbXTX+r2/8TtTvRX/v4zTL2UlMUPgwg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.min.js" integrity="sha512-P0bOMePRS378NwmPDVPU455C/TuxDS+8QwJozdc7PGgN8kLqR4ems0U/3DeJkmiE31749vYWHvBOtR+37qDCZQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
    </object>
    
    <script>
        var morsefallscale = [
            @foreach($morsefallscale as $key => $dt)
            {
                @foreach($dt as $key2 => $val)
                    '{{$key2}}' : `{!!str_replace('`', '', $val)!!}`,
                @endforeach
            },
            @endforeach
        ];
        
        var datetime = [
            @foreach($datetime as $key => $datetime1)
            {
                @foreach($datetime1 as $key2 => $val)
                    '{{$key2}}' : `{{$val}}`,
                @endforeach
            },
            @endforeach
        ];
        
        $(document).ready(function (){
            var docDefinition = {
                footer: function (currentPage, pageCount){
                    return [
                        // { text: 'This is computer-generated document. No signature is required.', italics: true, alignment: 'center', fontSize: 9 },
                        { text: currentPage.toString() + ' of ' + pageCount, alignment: 'center', fontSize: 9 }
                    ]
                },
                pageSize: 'A4',
                // pageOrientation: 'landscape',
                // pageMargins: [10, 20, 20, 30],
                content: [
                    {
                        image: 'letterhead', width: 500, style: 'tableHeader', colSpan: 5, alignment: 'center'
                    },
                    {
                        text: 'DAILY MORSE FALL SCALE ASSESSMENT CHART\n',
                        style: 'header',
                        alignment: 'center'
                    },
                    {
                        style: 'tableExample1',
                        table: {
                            headerRows: 1,
                            widths: [50,160,35,50,80,85], // panjang standard dia 515
                            body: [
                                [
                                    { text: 'NAME' },
                                    { text: `\u200B\t{!!$pat_mast->Name!!}` },
                                    { text: 'MRN' },
                                    { text: '\u200B\t{{str_pad($pat_mast->mrn, 7, "0", STR_PAD_LEFT)}}' },
                                    { text: 'WARD' },
                                    { text: '\u200B\t{{$pat_mast->ward}}' },
                                ],
                                [
                                    { text: 'DIAGNOSIS' },
                                    { text: `\u200B\t{!!$pat_otbook->diagnosis!!}` },
                                    { text: 'AGE' },
                                    { text: '\u200B\t{{$age}}' },
                                    { text: 'ADMISSION DATE' },
                                    { text: '\u200B\t{{\Carbon\Carbon::createFromFormat('Y-m-d',$episode->reg_date)->format('d/m/Y')}}' },
                                ],
                            ]
                        },
                        // layout: 'noBorders',
                    },
                    // { canvas: [{ type: 'line', x1: 0, y1: 5, x2: 515, y2: 5, lineWidth: 0.5 }] },
                    {
                        style: 'tableExample',
                        table: make_table(),
                        // layout: 'lightHorizontalLines',
                    },
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: [130,60,70,110,100], // panjang standard dia 515
                            body: [
                                [
                                    { text: 'LEVEL OF RISK', bold: true, alignment: 'center' },
                                    { text: 'MFS SCORE', bold: true, alignment: 'center' },
                                    { text: 'COLOUR CODE', bold: true, alignment: 'center' },
                                    { text: 'ACTION', bold: true, alignment: 'center' },
                                    {
                                        text: [
                                            { text: '\n\nPatient assessment ' },
                                            { text: 'MUST be done Daily', bold: true },
                                            { text: ' and during change of patient\u2019s status' },
                                        ], alignment: 'center', rowSpan: 5
                                    },
                                ],
                                [
                                    { text: 'NO RISKS FOR FALL' },
                                    { text: '0', alignment: 'center' },
                                    { text: 'None', alignment: 'center' },
                                    { text: 'Implement Standard Falls Risk Interventions', rowSpan: 2 },
                                    {},
                                ],
                                [
                                    { text: 'LOW RISK' },
                                    { text: '1 - 24', alignment: 'center' },
                                    { text: 'WHITE', alignment: 'center' },
                                    {},
                                    {},
                                ],
                                [
                                    { text: 'MODERATE RISK' },
                                    { text: '25 - 45', alignment: 'center' },
                                    { text: 'YELLOW', alignment: 'center' },
                                    { text: 'Implement Moderate Falls Risk Interventions' },
                                    {},
                                ],
                                [
                                    { text: 'HIGH RISK' },
                                    { text: '> 45', alignment: 'center' },
                                    { text: 'RED', alignment: 'center' },
                                    { text: 'Implement High Falls Risk Interventions' },
                                    {},
                                ],
                            ]
                        },
                        // layout: 'noBorders',
                    },
                ],
                styles: {
                    header: {
                        fontSize: 14,
                        bold: true,
                        margin: [0, 10, 0, 0]
                    },
                    subheader: {
                        fontSize: 16,
                        bold: true,
                        margin: [0, 10, 0, 5]
                    },
                    tableExample: {
                        fontSize: 9,
                        margin: [0, 0, 0, 0]
                    },
                    tableExample1: {
                        fontSize: 8,
                        margin: [0, 5, 0, 0]
                    },
                    tableDetail: {
                        fontSize: 7.5,
                        margin: [0, 0, 0, 8]
                    },
                    tableHeader: {
                        bold: true,
                        fontSize: 9,
                        margin: [0, 0, 0, 0],
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
        
        function make_table(){
            var widths = [170,40];
            var body = [
                [
                    { text: 'ITEMS', bold: true, alignment: 'center', rowSpan: 3 },
                    { text: 'DATE' },
                ],
                [
                    {},
                    { text: 'TIME' },
                ],
                [
                    {},
                    { text: 'SCORE' },
                ],
                [
                    {
                        text: [
                            { text: 'History of fall\n', bold: true, alignment: 'left' },
                            { text: 'NO\n' },
                            { text: 'YES' },
                        ], alignment: 'left',
                    },
                    {
                        text: [
                            {},
                            { text: '\n0' },
                            { text: '\n25' },
                        ], alignment: 'center',
                    },
                ],
                [
                    {
                        text: [
                            { text: 'Secondary Diagnosis\n', bold: true, alignment: 'left' },
                            { text: 'If only 1 active medical diagnosis\n' },
                            { text: 'Secondary diagnosis ≥ 2 medical diagnosis in chart' },
                        ], alignment: 'left',
                    },
                    {
                        text: [
                            {},
                            { text: '\n0' },
                            { text: '\n\n15' },
                        ], alignment: 'center',
                    },
                ],
                [
                    {
                        text: [
                            { text: 'Ambulatory Aids\n', bold: true, alignment: 'left' },
                            { text: 'None / Bed rest / Nurse Assist\n' },
                            { text: 'Crutches / Cane / Walker\n' },
                            { text: 'Furniture (Patient clutched onto furniture for support)' },
                        ], alignment: 'left',
                    },
                    {
                        text: [
                            {},
                            { text: '\n0' },
                            { text: '\n15' },
                            { text: '\n\n30' },
                        ], alignment: 'center',
                    },
                ],
                [
                    { text: 'IV therapy / Heparin Lock (IV devices)', bold: true },
                    {
                        text: [
                            { text: 'No: 0' },
                            { text: '\nYes: 20' },
                        ], alignment: 'center',
                    },
                ],
                [
                    {
                        text: [
                            { text: 'Gait\n', bold: true, alignment: 'left' },
                            { text: 'Normal / bed rest / immobile\n' },
                            { text: 'Weak\n' },
                            { text: 'Impaired' },
                        ], alignment: 'left',
                    },
                    {
                        text: [
                            {},
                            { text: '\n0' },
                            { text: '\n15' },
                            { text: '\n20' },
                        ], alignment: 'center',
                    },
                ],
                [
                    {
                        text: [
                            { text: 'Mental Status\n', bold: true, alignment: 'left' },
                            { text: 'Oriented to own ability\n' },
                            { text: 'Over estimates or forgets limitations' },
                        ], alignment: 'left',
                    },
                    {
                        text: [
                            {},
                            { text: '\n0' },
                            { text: '\n15' },
                        ], alignment: 'center',
                    },
                ],
                [
                    { text: 'Total Score :', bold: true, colSpan: 2 },{},
                ],
                [
                    { text: 'Name of Staff :', bold: true, colSpan: 2 },{},
                ],
            ];
            
            morsefallscale.forEach(function (element, index){
                widths.push('*');
                
                body[0][0].colSpan += 1;
                body[0].push({text: element.date});
                
                body[1].push({text: element.time});
                
                body[2].push({text: 'SCORE'});
                
                body[3].push({text: '\n'+element.fallHistory, noWrap: false, alignment: 'center'});
                
                body[4].push({text: '\n'+element.secondaryDiag, noWrap: false, alignment: 'center'});
                
                body[5].push({text: '\n'+element.ambulatoryAids, noWrap: false, alignment: 'center'});
                
                body[6].push({text: '\n'+element.IVtherapy, noWrap: false, alignment: 'center'});
                
                body[7].push({text: '\n'+element.gait, noWrap: false, alignment: 'center'});
                
                body[8].push({text: '\n'+element.mentalStatus, noWrap: false, alignment: 'center'});
                
                body[9].push({text: element.totalScore, noWrap: false, alignment: 'center'});
                
                body[10].push({text: element.adduser, noWrap: false, alignment: 'center'});
            });
            
            return {
                // headerRows: 1,
                widths: widths, // panjang standard dia 515
                body: body,
            };
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