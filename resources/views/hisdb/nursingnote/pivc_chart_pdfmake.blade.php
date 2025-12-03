<!DOCTYPE html>
<html>
    <head>
        <title>PIVC</title>
    </head>
    
    <!-- <script src="https://unpkg.com/@jsreport/browser-client/dist/jsreport.umd.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="mydata.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha512-a9NgEEK7tsCvABL7KqtUTQjl69z7091EVPpw5KxPlZ93T141ffe1woLtbXTX+r2/8TtTvRX/v4zTL2UlMUPgwg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.min.js" integrity="sha512-P0bOMePRS378NwmPDVPU455C/TuxDS+8QwJozdc7PGgN8kLqR4ems0U/3DeJkmiE31749vYWHvBOtR+37qDCZQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
    </object>
    
    <script>

        var pivc_date = [
            @foreach($pivc_date as $key => $dt)
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
                        text: 'PERIPHERAL LINE MAINTENANCE BUNDLE CHECKLIST\n',
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
                                    { text: `{!!$pivc->Name!!}`},
                                    { text: 'MRN' },
                                    { text: ':' },
                                    { text: '{{str_pad($pivc->mrn, 7, "0", STR_PAD_LEFT)}}' },
                                ],
                                [
                                    { text: 'Consultant' },
                                    { text: ':' },
                                    { text: ``, colSpan:4},{},{},{},
                                ],
                            ]
                        },
                        layout: 'noBorders',
                    },
                    {
                        style: 'tableExampleHeader',
                        table: {
                            widths: [20,300,50,30,30,30],
                            body: [
                                [
                                    { text: 'PRACTICE', style: 'tableHeader', alignment: 'center', colSpan:3},{},{},
                                    { text: 'Yes/No', style: 'tableHeader', alignment: 'center', colSpan:3},{},{}
                                ],
                                [
                                    { text: 'DATE', style: 'tableHeader', alignment: 'center', colSpan:3},{},{},
                                    { text: '{{\Carbon\Carbon::createFromFormat('Y-m-d',$pivc->practiceDate)->format('d-m-Y')}}', colSpan:3, alignment: 'center'},{},{}
                                ],
                                [
                                    { text: 'No'},
                                    { text: '', colSpan:2, fillColor: '#dddddd'},{},
                                    { text: 'M', alignment: 'center'},{ text: 'E', alignment: 'center'},{ text: 'N', alignment: 'center'},
                                ],
                                [
                                    { text: '1'},
                                    { text: 'Hand hygiene with all 7 steps before IV care tasks.', colSpan:2},{},
                                    @if(!empty($pivc->hygiene_M))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    @if(!empty($pivc->hygiene_E))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    @if(!empty($pivc->hygiene_N))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                ],
                                [
                                    { text: '2'},
                                    { text: 'Dressing is changed as per protocol\nTransparent dressing every 3-4 days. If condition of dressing not good (damp, loosened, soiled) than immediately.', colSpan:2},{},
                                    @if(!empty($pivc->dressing_M))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    @if(!empty($pivc->dressing_E))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    @if(!empty($pivc->dressing_N))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                ],
                                [
                                    { text: '3'},
                                    { text: 'Alcohol swab used for site prep during dressing changes.', colSpan:2},{},
                                    @if(!empty($pivc->alcoholSwab_M))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    @if(!empty($pivc->alcoholSwab_E))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    @if(!empty($pivc->alcoholSwab_N))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                ],
                                [
                                    { text: '4'},
                                    { text: 'Site labelled - Date and time marked on dressing.', colSpan:2},{},
                                    @if(!empty($pivc->siteLabelled_M))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    @if(!empty($pivc->siteLabelled_E))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    @if(!empty($pivc->siteLabelled_N))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                ],
                                [
                                    { text: '5'},
                                    { text: 'Correct solution, correct drop, tubing clear of bubble or blood.', colSpan:2},{},
                                    @if(!empty($pivc->correct_M))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    @if(!empty($pivc->correct_E))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    @if(!empty($pivc->correct_N))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                ],
                                [
                                    { text: '6'},
                                    { text: 'Multi Dose Vial/bags used for single patient only\nLabelled with patient name, date of opening - to be discarded as per protocol.', colSpan:2},{},
                                    @if(!empty($pivc->multiDoseVial_M))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    @if(!empty($pivc->multiDoseVial_E))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    @if(!empty($pivc->multiDoseVial_N))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                ],
                                [
                                    { text: '7'},
                                    { text: 'Clean/wipe the top of vials/bags before withdrawing medication.', colSpan:2},{},
                                    @if(!empty($pivc->cleanVial_M))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    @if(!empty($pivc->cleanVial_E))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    @if(!empty($pivc->cleanVial_N))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                ],
                                [
                                    { text: '8'},
                                    { text: 'Use of split septum closed connectors (Qsyte: Stand-alone/Extensions).', colSpan:2},{},
                                    @if(!empty($pivc->splitSeptum_M))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    @if(!empty($pivc->splitSeptum_E))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    @if(!empty($pivc->splitSeptum_N))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                ],
                                [
                                    { text: '9'},
                                    { text: 'Clean/wipe the site hub before each access.', colSpan:2},{},
                                    @if(!empty($pivc->cleanSite_M))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    @if(!empty($pivc->cleanSite_E))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    @if(!empty($pivc->cleanSite_N))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                ],
                                [
                                    { text: '10'},
                                    { text: 'Change split septum closed connectors at 72-96 hours.', colSpan:2},{},
                                    @if(!empty($pivc->chgSplitSeptum_M))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    @if(!empty($pivc->chgSplitSeptum_E))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    @if(!empty($pivc->chgSplitSeptum_N))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                ],
                                [
                                    { text: '11'},
                                    { text: 'Flushing according to ACL protocols\nAll tubing clear of blood/drugs - Single Use Prefilled 0.9% NS Flushing Device (POSIFLUSH).', colSpan:2},{},
                                    @if(!empty($pivc->flushingACL_M))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    @if(!empty($pivc->flushingACL_E))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    @if(!empty($pivc->flushingACL_N))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                ],
                                [
                                    { text: '12'},
                                    { text: 'Clamping of unused lines.', colSpan:2},{},
                                    @if(!empty($pivc->clamping_M))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    @if(!empty($pivc->clamping_E))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    @if(!empty($pivc->clamping_N))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                ],
                                [
                                    { text: '13'},
                                    { text: 'Date on admit set - Administration set change according to guidelines\nIntermittent IV Set - 24 hours\nContinuous IV Set - 96 hours to 7 days\nBlood Set with single ext Site - 4 hours.', colSpan:2},{},
                                    @if(!empty($pivc->set_M))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    @if(!empty($pivc->set_E))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    @if(!empty($pivc->set_N))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                ],
                                [
                                    { text: '14'},
                                    { text: 'Removal of PIVC when clinically indicated.', colSpan:2},{},
                                    @if(!empty($pivc->removalPIVC_M))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    @if(!empty($pivc->removalPIVC_E))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    @if(!empty($pivc->removalPIVC_N))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                ],
                                [
                                    { text: 'Name:', colSpan:3},{},{},
                                    @if(!empty($pivc->name_M))
                                        { text: '{{$pivc->name_M}}', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    @if(!empty($pivc->name_E))
                                        { text: '{{$pivc->name_E}}', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    @if(!empty($pivc->name_N))
                                        { text: '{{$pivc->name_N}}', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                ],
                                [
                                    { text: 'Time:', colSpan:3},{},{},
                                    @if(!empty($pivc->datetime_M))
                                        { text: '{{\Carbon\Carbon::createFromFormat('H:i:s',$pivc->datetime_M)->format('H:i')}}', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    @if(!empty($pivc->datetime_E))
                                        { text: '{{\Carbon\Carbon::createFromFormat('H:i:s',$pivc->datetime_E)->format('H:i')}}', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    @if(!empty($pivc->datetime_N))
                                        { text: '{{\Carbon\Carbon::createFromFormat('H:i:s',$pivc->datetime_N)->format('H:i')}}', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                ],
                                [
                                    { text: 'AS PER INS GUIDELINES - ACL OF FLUSHING:', colSpan:3, alignment: 'center', bold:true},{},{},
                                    { text: '** In case of Intermittent infusion, flushing need to be done every 6 hours', colSpan:3},{},{},
                                ],
                                [
                                    { text: 'ASSESS: AFTER INSERTION OF LINE\nCLEAR: BEFORE, AFTER MEDICATION, BLOOD TRANSFUSION\nLOCK: AFTER INFUSIONS, BLOOD DRAWS, TRANSFUSIONS', colSpan:3, bold:true},{},{},
                                    { text: '', colSpan:3},{},{},
                                ],
                            ],
                        },
                    },
                    // {
                    //     style: 'tableExampleHeader',
                    //     table: make_table_pivc(),
                    // },
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
                        fontSize: 8,
                        margin: [30, 5, 30, 0] //ltrb
                    },
                    tableExample: {
                        fontSize: 8,
                        margin: [5, 5, 0, 0] //ltrb
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
            
            pdfMake.createPdf(docDefinition).getDataUrl(function(dataURL) {
                $('#pdfiframe').attr('src',dataURL);
            });
        });

        function make_table_pivc(){

            var widths = [20,300,50,30,30,30];
            var body = [
                [
                    { text: 'PRACTICE', style: 'tableHeader', alignment: 'center', colSpan:3},{},{},
                    { text: '', style: 'tableHeader', alignment: 'center', colSpan:3},{},{}
                ],
                [
                    { text: 'DATE', style: 'tableHeader', alignment: 'center', colSpan:3},{},{},
                    { text: '', colSpan:3},{},{}
                ],
                // [
                //     { text: 'No'},
                //     { text: '', colSpan:2, fillColor: '#dddddd'},{},
                //     { text: 'M', alignment: 'center'},{ text: 'E', alignment: 'center'},{ text: 'N', alignment: 'center'},
                // ],
                // [
                //     { text: '1'},
                //     { text: 'Hand hygiene with all 7 steps before IV care tasks.', colSpan:2},{},
                //     { text: '', alignment: 'center'},
                //     { text: '', alignment: 'center'},
                //     { text: '', alignment: 'center'},
                // ],
                // [
                //     { text: '2'},
                //     { text: 'Dressing is changed as per protocol\nTransparent dressing every 3-4 days. If condition of dressing not good (damp, loosened, soiled) than immediately.', colSpan:2},{},
                //     { text: '', alignment: 'center'},
                //     { text: '', alignment: 'center'},
                //     { text: '', alignment: 'center'},
                // ],
                // [
                //     { text: '3'},
                //     { text: 'Alcohol swab used for site prep during dressing changes.', colSpan:2},{},
                //     { text: '', alignment: 'center'},
                //     { text: '', alignment: 'center'},
                //     { text: '', alignment: 'center'},
                // ],
                // [
                //     { text: '4'},
                //     { text: 'Site labelled - Date and time marked on dressing.', colSpan:2},{},
                //     { text: '', alignment: 'center'},
                //     { text: '', alignment: 'center'},
                //     { text: '', alignment: 'center'},
                // ],
                // [
                //     { text: '5'},
                //     { text: 'Correct solution, correct drop, tubing clear of bubble or blood.', colSpan:2},{},
                //     { text: '', alignment: 'center'},
                //     { text: '', alignment: 'center'},
                //     { text: '', alignment: 'center'},
                // ],
                // [
                //     { text: '6'},
                //     { text: 'Multi Dose Vial/bags used for single patient only\nLabelled with patient name, date of opening - to be discarded as per protocol.', colSpan:2},{},
                //     { text: '', alignment: 'center'},
                //     { text: '', alignment: 'center'},
                //     { text: '', alignment: 'center'},
                // ],
                // [
                //     { text: '7'},
                //     { text: 'Clean/wipe the top of vials/bags before withdrawing medication.', colSpan:2},{},
                //     { text: '', alignment: 'center'},
                //     { text: '', alignment: 'center'},
                //     { text: '', alignment: 'center'},
                // ],
                // [
                //     { text: '8'},
                //     { text: 'Use of split septum closed connectors (Qsyte: Stand-alone/Extensions).', colSpan:2},{},
                //     { text: '', alignment: 'center'},
                //     { text: '', alignment: 'center'},
                //     { text: '', alignment: 'center'},
                // ],
                // [
                //     { text: '9'},
                //     { text: 'Clean/wipe the site hub before each access.', colSpan:2},{},
                //     { text: '', alignment: 'center'},
                //     { text: '', alignment: 'center'},
                //     { text: '', alignment: 'center'},
                // ],
                // [
                //     { text: '10'},
                //     { text: 'Change split septum closed connectors at 72-96 hours.', colSpan:2},{},
                //     { text: '', alignment: 'center'},
                //     { text: '', alignment: 'center'},
                //     { text: '', alignment: 'center'},
                // ],
                // [
                //     { text: '11'},
                //     { text: 'Flushing according to ACL protocols\nAll tubing clear of blood/drugs - Single Use Prefilled 0.9% NS Flushing Device (POSIFLUSH).', colSpan:2},{},
                //     { text: '', alignment: 'center'},
                //     { text: '', alignment: 'center'},
                //     { text: '', alignment: 'center'},
                // ],
                // [
                //     { text: '12'},
                //     { text: 'Clamping of unused lines.', colSpan:2},{},
                //     { text: '', alignment: 'center'},
                //     { text: '', alignment: 'center'},
                //     { text: '', alignment: 'center'},
                // ],
                // [
                //     { text: '13'},
                //     { text: 'Date on admit set - Administration set change according to guidelines\nIntermittent IV Set - 24 hours\nContinuous IV Set - 96 hours to 7 days\nBlood Set with single ext Site - 4 hours.', colSpan:2},{},
                //     { text: '', alignment: 'center'},
                //     { text: '', alignment: 'center'},
                //     { text: '', alignment: 'center'},
                // ],
                // [
                //     { text: '14'},
                //     { text: 'Removal of PIVC when clinically indicated.', colSpan:2},{},
                //     { text: '', alignment: 'center'},
                //     { text: '', alignment: 'center'},
                //     { text: '', alignment: 'center'},
                // ],
                // [
                //     { text: 'Name:', colSpan:3},{},{},
                //     { text: '', alignment: 'center'},
                //     { text: '', alignment: 'center'},
                //     { text: '', alignment: 'center'},
                // ],
                // [
                //     { text: 'Time:', colSpan:3},{},{},
                //     { text: '', alignment: 'center'},
                //     { text: '', alignment: 'center'},
                //     { text: '', alignment: 'center'},
                // ],
                // [
                //     { text: 'AS PER INS GUIDELINES - ACL OF FLUSHING:', colSpan:3, alignment: 'center', bold:true},{},{},
                //     { text: '** In case of Intermittent infusion, flushing need to be done every 6 hours', colSpan:3},{},{},
                // ],
                // [
                //     { text: 'ASSESS: AFTER INSERTION OF LINE\nCLEAR: BEFORE, AFTER MEDICATION, BLOOD TRANSFUSION\nLOCK: AFTER INFUSIONS, BLOOD DRAWS, TRANSFUSIONS', colSpan:3, bold:true},{},{},
                //     { text: '', colSpan:3},{},{},
                // ],
            ];

            pivc_date.forEach(function(element, index){
                widths.push('*');

                body[0][0].colSpan += 3;
                body[0].push({text: 'Yes/No'});

                body[1].push(
                    {},{},{ text: element.practiceDate}
                );

                // body[2].push(
                //     {},
                //     {},
                //     {}

                // );

                // body[3].push(
                //     { text: element.hygiene_M},
                //     { text: element.hygiene_E},
                //     { text: element.hygiene_N}

                // );
               
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