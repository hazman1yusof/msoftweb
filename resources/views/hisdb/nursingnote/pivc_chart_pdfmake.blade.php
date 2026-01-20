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
                pageOrientation: 'landscape',
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
                            ]
                        },
                        layout: 'noBorders',
                    },
                    {
                        style: 'tableExampleHeader',
                        table: make_table_pivc(),
                    },
                    {
                        style: 'tableExampleHeader',
                        table: {
                            widths: [20,300],
                            body: [
                                [
                                    { text: 'AS PER INS GUIDELINES - ACL OF FLUSHING:\n** In case of Intermittent infusion, flushing need to be done every 6 hours', colSpan:2, bold:true},{},
                                ],
                                [
                                    { text: 'ASSESS: AFTER INSERTION OF LINE\nCLEAR: BEFORE, AFTER MEDICATION, BLOOD TRANSFUSION\nLOCK: AFTER INFUSIONS, BLOOD DRAWS, TRANSFUSIONS', colSpan:2, bold:true},{},
                                ],
                            ],
                        },layout: 'noBorders'
                    },  
                ],
                styles: {
                    header: {
                        fontSize: 10,
                        bold: true,
                        margin: [0, 10, 0, 0]
                    },
                    subheader: {
                        fontSize: 16,
                        bold: true,
                        margin: [0, 10, 0, 5]
                    },
                    tableExampleHeader: {
                        fontSize: 6.5,
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
                        fontSize: 6.5,
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
                    },
                    table1: {
                        fontSize: 6.5,
                        margin: [0, 0, 0, 0] //ltrb
                    },
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
            
            pdfMake.createPdf(docDefinition).getDataUrl(function(dataURL) {
                $('#pdfiframe').attr('src',dataURL);
            });
        });

        function make_table_pivc(){

            var widths = [20,250];
            var body = [
                [
                    { text: 'DATE', style: 'tableHeader', alignment: 'center',colSpan:2},{ text: ''}
                ],
                [
                    { text: 'CONSULTANT', style: 'tableHeader', alignment: 'center',colSpan:2},{ text: ''}
                ],
                [
                    { text: 'No'},
                    { text: '', fillColor: '#dddddd'}
                ],
                [
                    { text: '1'},
                    { text: 'Hand hygiene with all 7 steps before IV care tasks.'},
                ],
                [
                    { text: '2'},
                    { text: 'Dressing is changed as per protocol\nTransparent dressing every 3-4 days. If condition of dressing not good (damp, loosened, soiled) than immediately.'},
                ],
                [
                    { text: '3'},
                    { text: 'Alcohol swab used for site prep during dressing changes.'}
                ],
                [
                    { text: '4'},
                    { text: 'Site labelled - Date and time marked on dressing.'},
                ],
                [
                    { text: '5'},
                    { text: 'Correct solution, correct drop, tubing clear of bubble or blood.'},
                ],
                [
                    { text: '6'},
                    { text: 'Multi Dose Vial/bags used for single patient only\nLabelled with patient name, date of opening - to be discarded as per protocol.'},
                ],
                [
                    { text: '7'},
                    { text: 'Clean/wipe the top of vials/bags before withdrawing medication.'},
                ],
                [
                    { text: '8'},
                    { text: 'Use of split septum closed connectors (Qsyte: Stand-alone/Extensions).'},
                ],
                [
                    { text: '9'},
                    { text: 'Clean/wipe the site hub before each access.'},
                ],
                [
                    { text: '10'},
                    { text: 'Change split septum closed connectors at 72-96 hours.'},
                ],
                [
                    { text: '11'},
                    { text: 'Flushing according to ACL protocols\nAll tubing clear of blood/drugs - Single Use Prefilled 0.9% NS Flushing Device (POSIFLUSH).'},
                ],
                [
                    { text: '12'},
                    { text: 'Clamping of unused lines.'},
                ],
                [
                    { text: '13'},
                    { text: 'Date on admit set - Administration set change according to guidelines\nIntermittent IV Set - 24 hours\nContinuous IV Set - 96 hours to 7 days\nBlood Set with single ext Site - 4 hours.'},
                ],
                [
                    { text: '14'},
                    { text: 'Removal of PIVC when clinically indicated.'},
                ],
                [
                    { text: 'Name:', style: 'tableHeader', colSpan:2},{}
                ],
                [
                    { text: 'Time:', style: 'tableHeader', colSpan:2},{},
                ],
            ];

            pivc_date.forEach(function(element, index){
                widths.push('*');
                
                body[0][1].colSpan += 1;
                body[0].push({text: element.date, alignment: 'center'});

                body[1].push({text: element.consultant, noWrap:false});
                
                body[2].push(
                    { 
                        alignment: 'justify',
                        columns: [
                            {
                                width: 'auto',
                                style: 'table1',
                                table: {
                                    widths: [40,40,40],
                                    body: [
                                        [
                                            {text:'M',alignment: 'center',}, {text:'E',alignment: 'center',}, {text:'N',alignment: 'center',}
                                        ],
                                    ]
                                },layout: 'noBorders'
                            }
                        ],
                       
                    },
                );

                if(element.hygiene_M == '1' && element.hygiene_E == '1' && element.hygiene_N == '1'){
                    body[3].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:'√',alignment: 'center',}, 
                                                {text:'√',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );
                }else if(element.hygiene_M == '1' && element.hygiene_E == '1' && element.hygiene_N == '0'){
                    body[3].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:'√',alignment: 'center',}, 
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );
                }else if(element.hygiene_M == '1' && element.hygiene_E == '0' && element.hygiene_N == '0'){
                    body[3].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else if(element.hygiene_M == '1' && element.hygiene_E == '0' && element.hygiene_N == '1'){
                    body[3].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                               
                }else if(element.hygiene_M == '0' && element.hygiene_E == '0' && element.hygiene_N == '1'){
                    body[3].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else if(element.hygiene_M == '0' && element.hygiene_E == '1' && element.hygiene_N == '0'){
                    body[3].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else if(element.hygiene_M == '0' && element.hygiene_E == '1' && element.hygiene_N == '1'){
                    body[3].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else{
                    body[3].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                    
                }

                if(element.dressing_M == '1' && element.dressing_E == '1' && element.dressing_N == '1'){
                    body[4].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:'√',alignment: 'center',}, 
                                                {text:'√',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );
                }else if(element.dressing_M == '1' && element.dressing_E == '1' && element.dressing_N == '0'){
                    body[4].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:'√',alignment: 'center',}, 
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );
                }else if(element.dressing_M == '1' && element.dressing_E == '0' && element.dressing_N == '0'){
                    body[4].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else if(element.dressing_M == '1' && element.dressing_E == '0' && element.dressing_N == '1'){
                    body[4].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                               
                }else if(element.dressing_M == '0' && element.dressing_E == '0' && element.dressing_N == '1'){
                    body[4].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else if(element.dressing_M == '0' && element.dressing_E == '1' && element.dressing_N == '0'){
                    body[4].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else if(element.dressing_M == '0' && element.dressing_E == '1' && element.dressing_N == '1'){
                    body[4].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else{
                    body[4].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                    
                }

                if(element.alcoholSwab_M == '1' && element.alcoholSwab_E == '1' && element.alcoholSwab_N == '1'){
                    body[5].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:'√',alignment: 'center',}, 
                                                {text:'√',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );
                }else if(element.alcoholSwab_M == '1' && element.alcoholSwab_E == '1' && element.alcoholSwab_N == '0'){
                    body[5].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:'√',alignment: 'center',}, 
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );
                }else if(element.alcoholSwab_M == '1' && element.alcoholSwab_E == '0' && element.alcoholSwab_N == '0'){
                    body[5].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else if(element.alcoholSwab_M == '1' && element.alcoholSwab_E == '0' && element.alcoholSwab_N == '1'){
                    body[5].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                               
                }else if(element.alcoholSwab_M == '0' && element.alcoholSwab_E == '0' && element.alcoholSwab_N == '1'){
                    body[5].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else if(element.alcoholSwab_M == '0' && element.alcoholSwab_E == '1' && element.alcoholSwab_N == '0'){
                    body[5].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else if(element.alcoholSwab_M == '0' && element.alcoholSwab_E == '1' && element.alcoholSwab_N == '1'){
                    body[5].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else{
                    body[5].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                    
                }

                if(element.siteLabelled_M == '1' && element.siteLabelled_E == '1' && element.siteLabelled_N == '1'){
                    body[6].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:'√',alignment: 'center',}, 
                                                {text:'√',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );
                }else if(element.siteLabelled_M == '1' && element.siteLabelled_E == '1' && element.siteLabelled_N == '0'){
                    body[6].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:'√',alignment: 'center',}, 
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );
                }else if(element.siteLabelled_M == '1' && element.siteLabelled_E == '0' && element.siteLabelled_N == '0'){
                    body[6].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else if(element.siteLabelled_M == '1' && element.siteLabelled_E == '0' && element.siteLabelled_N == '1'){
                    body[6].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                               
                }else if(element.siteLabelled_M == '0' && element.siteLabelled_E == '0' && element.siteLabelled_N == '1'){
                    body[6].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else if(element.siteLabelled_M == '0' && element.siteLabelled_E == '1' && element.siteLabelled_N == '0'){
                    body[6].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else if(element.siteLabelled_M == '0' && element.siteLabelled_E == '1' && element.siteLabelled_N == '1'){
                    body[6].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else{
                    body[6].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                    
                }

                if(element.correct_M == '1' && element.correct_E == '1' && element.correct_N == '1'){
                    body[7].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:'√',alignment: 'center',}, 
                                                {text:'√',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );
                }else if(element.correct_M == '1' && element.correct_E == '1' && element.correct_N == '0'){
                    body[7].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:'√',alignment: 'center',}, 
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );
                }else if(element.correct_M == '1' && element.correct_E == '0' && element.correct_N == '0'){
                    body[7].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else if(element.correct_M == '1' && element.correct_E == '0' && element.correct_N == '1'){
                    body[7].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                               
                }else if(element.correct_M == '0' && element.correct_E == '0' && element.correct_N == '1'){
                    body[7].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else if(element.correct_M == '0' && element.correct_E == '1' && element.correct_N == '0'){
                    body[7].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else if(element.correct_M == '0' && element.correct_E == '1' && element.correct_N == '1'){
                    body[7].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else{
                    body[7].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                    
                }

                if(element.multiDoseVial_M == '1' && element.multiDoseVial_E == '1' && element.multiDoseVial_N == '1'){
                    body[8].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:'√',alignment: 'center',}, 
                                                {text:'√',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );
                }else if(element.multiDoseVial_M == '1' && element.multiDoseVial_E == '1' && element.multiDoseVial_N == '0'){
                    body[8].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:'√',alignment: 'center',}, 
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );
                }else if(element.multiDoseVial_M == '1' && element.multiDoseVial_E == '0' && element.multiDoseVial_N == '0'){
                    body[8].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else if(element.multiDoseVial_M == '1' && element.multiDoseVial_E == '0' && element.multiDoseVial_N == '1'){
                    body[8].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                               
                }else if(element.multiDoseVial_M == '0' && element.multiDoseVial_E == '0' && element.multiDoseVial_N == '1'){
                    body[8].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else if(element.multiDoseVial_M == '0' && element.multiDoseVial_E == '1' && element.multiDoseVial_N == '0'){
                    body[8].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else if(element.multiDoseVial_M == '0' && element.multiDoseVial_E == '1' && element.multiDoseVial_N == '1'){
                    body[8].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else{
                    body[8].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                    
                }

                if(element.cleanVial_M == '1' && element.cleanVial_E == '1' && element.cleanVial_N == '1'){
                    body[9].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:'√',alignment: 'center',}, 
                                                {text:'√',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );
                }else if(element.cleanVial_M == '1' && element.cleanVial_E == '1' && element.cleanVial_N == '0'){
                    body[9].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:'√',alignment: 'center',}, 
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );
                }else if(element.cleanVial_M == '1' && element.cleanVial_E == '0' && element.cleanVial_N == '0'){
                    body[9].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else if(element.cleanVial_M == '1' && element.cleanVial_E == '0' && element.cleanVial_N == '1'){
                    body[9].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                               
                }else if(element.cleanVial_M == '0' && element.cleanVial_E == '0' && element.cleanVial_N == '1'){
                    body[9].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else if(element.cleanVial_M == '0' && element.cleanVial_E == '1' && element.cleanVial_N == '0'){
                    body[9].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else if(element.cleanVial_M == '0' && element.cleanVial_E == '1' && element.cleanVial_N == '1'){
                    body[9].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else{
                    body[9].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                    
                }

                if(element.splitSeptum_M == '1' && element.splitSeptum_E == '1' && element.splitSeptum_N == '1'){
                    body[10].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:'√',alignment: 'center',}, 
                                                {text:'√',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );
                }else if(element.splitSeptum_M == '1' && element.splitSeptum_E == '1' && element.splitSeptum_N == '0'){
                    body[10].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:'√',alignment: 'center',}, 
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );
                }else if(element.splitSeptum_M == '1' && element.splitSeptum_E == '0' && element.splitSeptum_N == '0'){
                    body[10].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else if(element.splitSeptum_M == '1' && element.splitSeptum_E == '0' && element.splitSeptum_N == '1'){
                    body[10].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                               
                }else if(element.splitSeptum_M == '0' && element.splitSeptum_E == '0' && element.splitSeptum_N == '1'){
                    body[10].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else if(element.splitSeptum_M == '0' && element.splitSeptum_E == '1' && element.splitSeptum_N == '0'){
                    body[10].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else if(element.splitSeptum_M == '0' && element.splitSeptum_E == '1' && element.splitSeptum_N == '1'){
                    body[10].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else{
                    body[10].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                    
                }

                if(element.cleanSite_M == '1' && element.cleanSite_E == '1' && element.cleanSite_N == '1'){
                    body[11].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:'√',alignment: 'center',}, 
                                                {text:'√',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );
                }else if(element.cleanSite_M == '1' && element.cleanSite_E == '1' && element.cleanSite_N == '0'){
                    body[11].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:'√',alignment: 'center',}, 
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );
                }else if(element.cleanSite_M == '1' && element.cleanSite_E == '0' && element.cleanSite_N == '0'){
                    body[11].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else if(element.cleanSite_M == '1' && element.cleanSite_E == '0' && element.cleanSite_N == '1'){
                    body[11].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                               
                }else if(element.cleanSite_M == '0' && element.cleanSite_E == '0' && element.cleanSite_N == '1'){
                    body[11].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else if(element.cleanSite_M == '0' && element.cleanSite_E == '1' && element.cleanSite_N == '0'){
                    body[11].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else if(element.cleanSite_M == '0' && element.cleanSite_E == '1' && element.cleanSite_N == '1'){
                    body[11].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else{
                    body[11].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                    
                }

                if(element.chgSplitSeptum_M == '1' && element.chgSplitSeptum_E == '1' && element.chgSplitSeptum_N == '1'){
                    body[12].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:'√',alignment: 'center',}, 
                                                {text:'√',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );
                }else if(element.chgSplitSeptum_M == '1' && element.chgSplitSeptum_E == '1' && element.chgSplitSeptum_N == '0'){
                    body[12].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:'√',alignment: 'center',}, 
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );
                }else if(element.chgSplitSeptum_M == '1' && element.chgSplitSeptum_E == '0' && element.chgSplitSeptum_N == '0'){
                    body[12].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else if(element.chgSplitSeptum_M == '1' && element.chgSplitSeptum_E == '0' && element.chgSplitSeptum_N == '1'){
                    body[12].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                               
                }else if(element.chgSplitSeptum_M == '0' && element.chgSplitSeptum_E == '0' && element.chgSplitSeptum_N == '1'){
                    body[12].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else if(element.chgSplitSeptum_M == '0' && element.chgSplitSeptum_E == '1' && element.chgSplitSeptum_N == '0'){
                    body[12].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else if(element.chgSplitSeptum_M == '0' && element.chgSplitSeptum_E == '1' && element.chgSplitSeptum_N == '1'){
                    body[12].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else{
                    body[12].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                    
                }

                if(element.flushingACL_M == '1' && element.flushingACL_E == '1' && element.flushingACL_N == '1'){
                    body[13].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:'√',alignment: 'center',}, 
                                                {text:'√',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );
                }else if(element.flushingACL_M == '1' && element.flushingACL_E == '1' && element.flushingACL_N == '0'){
                    body[13].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:'√',alignment: 'center',}, 
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );
                }else if(element.flushingACL_M == '1' && element.flushingACL_E == '0' && element.flushingACL_N == '0'){
                    body[13].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else if(element.flushingACL_M == '1' && element.flushingACL_E == '0' && element.flushingACL_N == '1'){
                    body[13].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                               
                }else if(element.flushingACL_M == '0' && element.flushingACL_E == '0' && element.flushingACL_N == '1'){
                    body[13].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else if(element.flushingACL_M == '0' && element.flushingACL_E == '1' && element.flushingACL_N == '0'){
                    body[13].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else if(element.flushingACL_M == '0' && element.flushingACL_E == '1' && element.flushingACL_N == '1'){
                    body[13].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else{
                    body[13].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                    
                }

                if(element.clamping_M == '1' && element.clamping_E == '1' && element.clamping_N == '1'){
                    body[14].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:'√',alignment: 'center',}, 
                                                {text:'√',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );
                }else if(element.clamping_M == '1' && element.clamping_E == '1' && element.clamping_N == '0'){
                    body[14].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:'√',alignment: 'center',}, 
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );
                }else if(element.clamping_M == '1' && element.clamping_E == '0' && element.clamping_N == '0'){
                    body[14].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else if(element.clamping_M == '1' && element.clamping_E == '0' && element.clamping_N == '1'){
                    body[14].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                               
                }else if(element.clamping_M == '0' && element.clamping_E == '0' && element.clamping_N == '1'){
                    body[14].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else if(element.clamping_M == '0' && element.clamping_E == '1' && element.clamping_N == '0'){
                    body[14].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else if(element.clamping_M == '0' && element.clamping_E == '1' && element.clamping_N == '1'){
                    body[14].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else{
                    body[14].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                    
                }

                if(element.set_M == '1' && element.set_E == '1' && element.set_N == '1'){
                    body[15].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:'√',alignment: 'center',}, 
                                                {text:'√',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );
                }else if(element.set_M == '1' && element.set_E == '1' && element.set_N == '0'){
                    body[15].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:'√',alignment: 'center',}, 
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );
                }else if(element.set_M == '1' && element.set_E == '0' && element.set_N == '0'){
                    body[15].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else if(element.set_M == '1' && element.set_E == '0' && element.set_N == '1'){
                    body[15].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                               
                }else if(element.set_M == '0' && element.set_E == '0' && element.set_N == '1'){
                    body[15].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else if(element.set_M == '0' && element.set_E == '1' && element.set_N == '0'){
                    body[15].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else if(element.set_M == '0' && element.set_E == '1' && element.set_N == '1'){
                    body[15].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else{
                    body[15].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                    
                }

                if(element.removalPIVC_M == '1' && element.removalPIVC_E == '1' && element.removalPIVC_N == '1'){
                    body[16].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:'√',alignment: 'center',}, 
                                                {text:'√',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );
                }else if(element.removalPIVC_M == '1' && element.removalPIVC_E == '1' && element.removalPIVC_N == '0'){
                    body[16].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:'√',alignment: 'center',}, 
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );
                }else if(element.removalPIVC_M == '1' && element.removalPIVC_E == '0' && element.removalPIVC_N == '0'){
                    body[16].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else if(element.removalPIVC_M == '1' && element.removalPIVC_E == '0' && element.removalPIVC_N == '1'){
                    body[16].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                               
                }else if(element.removalPIVC_M == '0' && element.removalPIVC_E == '0' && element.removalPIVC_N == '1'){
                    body[16].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else if(element.removalPIVC_M == '0' && element.removalPIVC_E == '1' && element.removalPIVC_N == '0'){
                    body[16].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else if(element.removalPIVC_M == '0' && element.removalPIVC_E == '1' && element.removalPIVC_N == '1'){
                    body[16].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:'√',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                
                }else{
                    body[16].push(
                        { 
                            alignment: 'justify',
                            columns: [
                                {
                                    width: 'auto',
                                    style: 'table1',
                                    table: {
                                        widths: [40,40,40],
                                        body: [
                                            [
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',},
                                                {text:' ',alignment: 'center',}
                                            ],
                                        ]
                                    },layout: 'noBorders'
                                }
                            ],
                        
                        },
                    );                    
                }

                body[17].push(
                    { 
                        alignment: 'justify',
                        columns: [
                            {
                                width: 'auto',
                                style: 'table1',
                                table: {
                                    widths: [40,40,40],
                                    body: [
                                        [
                                            {text:element.name_M,alignment: 'center',},
                                            {text:element.name_E,alignment: 'center',}, 
                                            {text:element.name_N,alignment: 'center',}
                                        ],
                                    ]
                                },layout: 'noBorders'
                            }
                        ],
                    
                    },
                );
                
                body[18].push(
                    { 
                        alignment: 'justify',
                        columns: [
                            {
                                width: 'auto',
                                style: 'table1',
                                table: {
                                    widths: [40,40,40],
                                    body: [
                                        [
                                            {text:element.datetime_M,alignment: 'center',},
                                            {text:element.datetime_E,alignment: 'center',}, 
                                            {text:element.datetime_N,alignment: 'center',}
                                        ],
                                    ]
                                },layout: 'noBorders'
                            }
                        ],
                    
                    },
                );

            });

            // console.log(widths);
            // console.log(body);

            return {
                // headerRows: 1,
                widths: widths, // panjang standard dia 515
                body: body,
            };
        }
        
    </script>
    
    <body style="margin: 0px;">
        <iframe id="pdfiframe" width="100%" height="100%" src="" frameborder="0" style="width: 99vw;height: 99vh;"></iframe>
    </body>
</html>