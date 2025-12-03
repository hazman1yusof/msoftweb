<!DOCTYPE html>
<html>
    <head>
        <title>THROMBOPHLEBITIS FORM</title>
    </head>
    
    <!-- <script src="https://unpkg.com/@jsreport/browser-client/dist/jsreport.umd.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="mydata.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha512-a9NgEEK7tsCvABL7KqtUTQjl69z7091EVPpw5KxPlZ93T141ffe1woLtbXTX+r2/8TtTvRX/v4zTL2UlMUPgwg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.min.js" integrity="sha512-P0bOMePRS378NwmPDVPU455C/TuxDS+8QwJozdc7PGgN8kLqR4ems0U/3DeJkmiE31749vYWHvBOtR+37qDCZQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
    </object>
    
    <script>
        var thromboGrid = [
            @foreach($thromboGrid as $key => $dt)
            {
                @foreach($dt as $key2 => $val)
                    '{{$key2}}' : `{!!str_replace('`', '', $val)!!}`,
                @endforeach
            },
            @endforeach
        ];

        $(document).ready(function (){
            var docDefinition = {
                footer: function (currentPage, pageCount){
                    return [
                        { text: currentPage.toString() + ' of ' + pageCount, alignment: 'center', fontSize: 9 }
                    ]
                },
                pageSize: 'A4',
                // pageOrientation: 'landscape',
                pageMargins: [10, 10, 10, 10],
                content: [
                    {
                        image: 'letterhead', width: 500, style: 'tableHeader', colSpan: 5, alignment: 'center'
                    },
                    {
                        text: 'THROMBOPHLEBITIS FORM\n',
                        style: 'header',
                        alignment: 'center'
                    },
                    {
                        style: 'tableExampleHeader',
                        table: {
                            headerRows: 1,
                            widths: [70,3,'*',60,3,'*'], // panjang standard dia 515
                            body: [
                                [
                                    { text: 'Name' },
                                    { text: ':' },
                                    { text: `{!!$thrombo->Name!!}`},
                                    { text: 'MRN' },
                                    { text: ':' },
                                    { text: '{{str_pad($thrombo->mrn, 7, "0", STR_PAD_LEFT)}}' },
                                ],
                            ]
                        },
                        layout: 'noBorders',
                    },
                    {
                        style: 'tableExampleHeader',
                        table: {
                            widths: ['*','*'],
                            body: [
                                [
                                    { text: 'CATHETER INSERTION', style: 'tableHeader', alignment: 'center', colSpan:2, fillColor: '#dddddd'},{},
                                ],
                                [
                                    { text: 'Date : {{\Carbon\Carbon::createFromFormat('Y-m-d',$thrombo->dateInsert)->format('d-m-Y')}}', style: 'tableHeader'},
                                    { text: 'Time : {{\Carbon\Carbon::createFromFormat('H:i:s',$thrombo->timeInsert)->format('H:i')}}', style: 'tableHeader'},
                                ],
                                [
                                    { text: 'Gauge', style: 'tableHeader'},
                                    @if(!empty($thrombo->gauge))
                                        { text: '{{$thrombo->gauge}}'},
                                    @else
                                        { text: ' ' },
                                    @endif
                                ],
                                [
                                    { text: 'Attempts', style: 'tableHeader'},
                                    @if(!empty($thrombo->attempts))
                                        { text: '{{$thrombo->attempts}}'},
                                    @else
                                        { text: ' ' },
                                    @endif
                                ],
                                [
                                    { text: 'Sites', style: 'tableHeader'},
                                    @if(!empty($thrombo->sitesMetacarpal))
                                        { text: 'Metacarpal: {{$thrombo->sitesMetacarpal}}' },
                                    @elseif (!empty($thrombo->sitesBasilic))
                                        { text: 'Basilic: {{$thrombo->sitesBasilic}}' },
                                    @elseif (!empty($thrombo->sitesCephalic))
                                        { text: 'Cephalic: {{$thrombo->sitesCephalic}}' },
                                    @elseif (!empty($thrombo->sitesMCubital))
                                        { text: 'M. Cubital: {{$thrombo->sitesMCubital}}' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                ],
                            ],
                        },
                    },
                    {
                        style: 'tableExampleHeader',
                        table: make_table_thrombo(),
                    },
                    {
                        style: 'tableExampleHeader',
                        table: {
                            widths: ['*','*'],
                            body: [
                                [
                                    { text: 'CATHETER REMOVAL', style: 'tableHeader', alignment: 'center', colSpan:2, fillColor: '#dddddd'},{},
                                ],
                                [
                                    @if(!empty($thrombo->dateRemoval))
                                        { text: 'Date : {{\Carbon\Carbon::createFromFormat('Y-m-d',$thrombo->dateRemoval)->format('d-m-Y')}}', style: 'tableHeader'},
                                    @else
                                        { text: 'Date : ', style: 'tableHeader'},
                                    @endif

                                    @if(!empty($thrombo->timeRemoval))
                                        { text: 'Time : {{\Carbon\Carbon::createFromFormat('H:i:s',$thrombo->timeRemoval)->format('H:i')}}', style: 'tableHeader'},
                                    @else
                                        { text: 'Time : ', style: 'tableHeader' },
                                    @endif
                                ],
                                [
                                    { text: 'Total dwelling time in hours', style: 'tableHeader'},
                                    @if(!empty($thrombo->totIndwelling))
                                        { text: '{{$thrombo->totIndwelling}}'},
                                    @else
                                        { text: ' ' },
                                    @endif
                                ],
                                [
                                    { text: 'Remarks', style: 'tableHeader'},
                                    @if(!empty($thrombo->remarksThrombo))
                                    { text: `{!!$thrombo->remarksThrombo!!}`},
                                    @else
                                        { text: ' ' },
                                    @endif

                                ],
                            ],
                        },
                    },
                    {
                        style: 'tableExample',
                        table: {
                            widths: ['*','*'],
                            body: [
                                [
                                    { image: 'thrombo', width: 250, alignment:'left'},
                                    { text: 'Phlebitis Score\n\nGrade 0: IV site appears healthy.\nGrade 1: One of the following is evident: slight pain at the IV site or slight redness near IV site.\nGrade 2: Two of the following are evidence: pain, erythema and swelling.\nGrade 3: All of the following sign are evidence: pain along the path of the catheter, erythema and induration.\nGrade 4: All of the following sign are evidence and extensive: pain along the catheter, erythema, induration and palpable venous cord.\nGrade 5: All of the following sign are evidence and extensive: pain along the catheter, erythema, induration, palpable venous cord and pyrexia.'}, 
                                ],
                            ],
                        },
                        layout: 'noBorders',
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
                    tableExampleHeader: {
                        fontSize: 9,
                        margin: [30, 5, 30, 0] //ltrb
                    },
                    tableExample: {
                        fontSize: 7,
                        margin: [30, 5, 30, 0] //ltrb
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
                    },
                    thrombo: {
                        url: "{{ asset('img/thrombophlebitis/thrombophlebitis_full.jpg') }}",
                    }
                }
            };
            
            // pdfMake.createPdf(docDefinition).getBase64(function (data){
            //     var base64data = "data:base64"+data;
            //     console.log($('object#pdfPreview').attr('data',base64data));
            //     document.getElementById('pdfPreview').data = base64data;
            // });
            
            pdfMake.createPdf(docDefinition).getDataUrl(function(dataURL) {
                $('#pdfiframe').attr('src',dataURL);
            });
        });

        function make_table_thrombo(){

            var widths = ['*'];
            var body = [
                [
                    { text: 'Flushing Done'},
                ],
                [
                    { text: 'Daily Assessment Record', style: 'tableHeader', alignment: 'center', fillColor: '#dddddd'},
                ],
                [
                    { text: 'Date'},
                ],
                [
                    { text: 'Shift'},
                ],
                [
                    { text: 'Dressing changed'},
                ],
                [
                    { text: 'Sign/Name'},
                ],
                [
                    { text: 'Reason for Removal', style: 'tableHeader', alignment: 'center', fillColor: '#dddddd'},
                ],
                [
                    { text: 'Phlebitis Grade'},
                ],
                [
                    { text: 'Infiltration'},
                ],
                [
                    { text: 'Hematoma'},
                ],
                [
                    { text: 'Extravasation'},
                ],
                [
                    { text: 'Occlusion'},
                ],
                [
                    { text: 'As per protocol'},
                ],
                [
                    { text: 'Pt discharged'},
                ],
                [
                    { text: 'Iv terminate'},
                ],
                [
                    { text: 'Catheter Removal Status', style: 'tableHeader', alignment: 'center', fillColor: '#dddddd'},
                ],
                [
                    { text: 'Fibrin Clot'},
                ],
                [
                    { text: 'Kinked Hub'},
                ],
                [
                    { text: 'Kinked Shaft'},
                ],
                [
                    { text: 'Tip damage'},
                ],
            ];

            thromboGrid.forEach(function(element, index){
                widths.push('*');
                
                body[0].push({ text: element.flushingDone});
                // body[1][1].colSpan += 1;
                body[1].push({text : '', fillColor: '#dddddd'});
                body[2].push({text : element.dateAssessment});
                body[3].push({text : element.shift});
                body[4].push({text : element.dressingChanged});
                body[5].push({text : element.staffId});
                body[6].push({text : '', fillColor: '#dddddd'});
                body[7].push({text : element.phlebitisGrade});
                body[8].push({text : element.infiltration});
                body[9].push({text : element.hematoma});
                body[10].push({text : element.extravasation});
                body[11].push({text : element.occlusion});
                body[12].push({text : element.asPerProtocol});
                body[13].push({text : element.ptDischarged});
                body[14].push({text : element.ivTerminate});
                body[15].push({text : '', fillColor: '#dddddd'});
                body[16].push({text : element.fibrinClot});
                body[17].push({text : element.kinkedHub});
                body[18].push({text : element.kinkedShaft});
                body[19].push({text : element.tipDamage});
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