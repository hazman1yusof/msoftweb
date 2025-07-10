<!DOCTYPE html>
<html>
    <head>
        <title>Daftar Aset Tetap (Inventori)</title>
    </head>
    
    <!-- <script src="https://unpkg.com/@jsreport/browser-client/dist/jsreport.umd.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="mydata.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha512-a9NgEEK7tsCvABL7KqtUTQjl69z7091EVPpw5KxPlZ93T141ffe1woLtbXTX+r2/8TtTvRX/v4zTL2UlMUPgwg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.min.js" integrity="sha512-P0bOMePRS378NwmPDVPU455C/TuxDS+8QwJozdc7PGgN8kLqR4ems0U/3DeJkmiE31749vYWHvBOtR+37qDCZQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
    </object>
    
    <script>
        var faregister = {
            @foreach($faregister as $key => $val)
                '{{$key}}' : '{{$val}}',
            @endforeach
        };
        
        var movement = [
            @foreach($movement as $key => $movement1)
            {
                @foreach($movement1 as $key2 => $val)
                    '{{$key2}}' : `{{$val}}`,
                @endforeach
            },
            @endforeach
        ];
        
        var curloccode = [
            @foreach($curloccode as $key => $val)
                '{{$val}}',
            @endforeach
        ];
        
        var company = {
            @foreach($company as $key => $val)
                '{{$key}}' : '{{$val}}',
            @endforeach
        };
        
        $(document).ready(function (){
            var docDefinition = {
                footer: function (currentPage, pageCount){
                    return [
                        { text: currentPage.toString() + ' of ' + pageCount, alignment: 'center' }
                    ]
                },
                pageSize: 'A4',
                pageOrientation: 'landscape',
                pageMargins: [10, 10, 10, 10],
                content: [
                    {
                        image: 'letterhead', width: 150, style: 'tableHeader', colSpan: 5, alignment: 'center'
                    },
                    {
                        text: '\nDAFTAR ASET TETAP (INVENTORI)\n',
                        style: 'header',
                        alignment: 'center'
                    },
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: ['*', '*'], // panjang standard dia 515
                            body: [
                                [
                                    { text: 'BAHAGIAN: ' },
                                    { text: '' },
                                ],
                            ]
                        },
                        layout: 'noBorders',
                    },
                    { text: 'BUTIR-BUTIR HARTA', alignment: 'center', fontSize: 12, bold: true },
                    {
                        style: 'tableExample',
                        table: {
                            widths: ['*','*','*','*','*','*','*','*','*','*','*'],
                            // headerRows: 5,
                            // keepWithHeaderRows: 5,
                            body: [
                                [
                                    {
                                        text: 'Kategori:',
                                        colSpan: 1, alignment: 'left'
                                    },{
                                        text: '{{strtoupper($faregister->category_description)}}',
                                        colSpan: 5, alignment: 'left'
                                    },{},{},{},{},
                                    {
                                        text: 'Kos Dan Tarikh Dibeli/Diterima: RM {{number_format($faregister->origcost,2)}} & {{\Carbon\Carbon::parse($faregister->delorddate)->format('d/m/Y')}}',
                                        colSpan: 5, alignment: 'left'
                                    },{},{},{},{},
                                ],
                                [
                                    {
                                        text: 'Jenis:',
                                        colSpan: 1, alignment: 'left'
                                    },{
                                        text: '{{strtoupper($faregister->type_description)}}',
                                        colSpan: 5, alignment: 'left'
                                    },{},{},{},{},
                                    {
                                        text: 'No. Pesanan Rasmi: {{$faregister->invno}}',
                                        colSpan: 5, alignment: 'left'
                                    },{},{},{},{},
                                ],
                                [
                                    {
                                        text: 'Jenama Dan Model:',
                                        colSpan: 1, alignment: 'left'
                                    },{
                                        text: '{{$faregister->brand}} {{$faregister->model}}',
                                        colSpan: 5, alignment: 'left'
                                    },{},{},{},{},
                                    {
                                        text: 'Pembekal: {{strtoupper($faregister->supplier_name)}}',
                                        colSpan: 5, alignment: 'left'
                                    },{},{},{},{},
                                ],
                                [
                                    {
                                        text: 'Jenis Dan No. Enjin:',
                                        colSpan: 1, alignment: 'left'
                                    },{
                                        text: '{{$faregister->engineno}}',
                                        colSpan: 5, alignment: 'left'
                                    },{},{},{},{},
                                    {
                                        text: 'No. Rujukan Fail: ',
                                        colSpan: 5, alignment: 'left'
                                    },{},{},{},{},
                                ],
                                [
                                    {
                                        text: 'No. Casis / Siri Pembuat:',
                                        colSpan: 1, alignment: 'left'
                                    },{
                                        text: '{{$faregister->casisno}} {{$faregister->serialno}}',
                                        colSpan: 5, alignment: 'left'
                                    },{},{},{},{},
                                    {
                                        text: ' ',
                                        colSpan: 5, rowSpan: 3
                                    },{},{},{},{},
                                ],
                                [
                                    {
                                        text: 'No. Siri Pendaftaran:',
                                        colSpan: 1, alignment: 'left'
                                    },{
                                        text: '',
                                        colSpan: 5, alignment: 'left'
                                    },{},{},{},{},{},{},{},{},{},{},
                                ],
                                [
                                    {
                                        border: [false, false, false, false],
                                        text: [
                                            '\n\n\n\n _________________________________________ \n\n',
                                            'T/tangan Pegawai Bertanggungjawab \n\n',
                                        ], colSpan: 3,
                                    },{},{},
                                    {
                                        border: [false, false, false, false],
                                        text: '\n\n\n\n\n Tarikh: ', colSpan: 3,
                                    },{},{},{},{},{},{},{},
                                ],
                            ]
                        }
                    },
                    { text: 'PENEMPATAN', alignment: 'center', fontSize: 9, bold: true },
                    {
                        style: 'tableExample',
                        table: make_table(),
                        // table: {
                        //     headerRows: 1,
                        //     widths: ['*','*','*'], // panjang standard dia 515
                        //     body: [
                        //         [
                        //             { text: 'LOKASI', style: 'tableHeader' },
                        //             { text: 'TARIKH', style: 'tableHeader' },
                        //             { text: 'TANDATANGAN PENJAGA ASET', style: 'tableHeader' },
                        //         ],
                        //         @foreach ($movement as $obj)
                        //         [
                        //             { text: '{{$obj->loc_description}}' },
                        //             { text: '{{\Carbon\Carbon::parse($obj->trandate)->format('d/m/Y')}}' },
                        //             { text: ' ' },
                        //         ],
                        //         @endforeach
                        //     ]
                        // },
                    },
                    {
                        style: 'tableExample',
                        table: {
                            widths: ['*','*','*','*','*','*','*','*','*','*','*'],
                            // headerRows: 5,
                            // keepWithHeaderRows: 5,
                            body: [
                                [
                                    {
                                        text: 'PEMERIKSAAN HARTA MODAL', alignment: 'center', colSpan: 5, border: [false, false, false, false]
                                    },{},{},{},{},
                                    {
                                        text: ' ', border: [false, false, false, false],
                                    },
                                    {
                                        text: 'PELUPUSAN', alignment: 'center', colSpan: 5, border: [false, false, false, false]
                                    },{},{},{},{},
                                ],
                                [
                                    { text: 'Tarikh' },
                                    { text: ' ' },
                                    { text: ' ' },
                                    { text: ' ' },
                                    { text: ' ' },
                                    {
                                        text: ' ', border: [false, false, false, false],
                                    },
                                    // {
                                    //     text: [
                                    //         'Tarikh: _____________________________ \n',
                                    //         'Rujukan: _____________________________ \n',
                                    //         'Tandatangan: _____________________________ \n',
                                    //     ], colSpan: 5, rowSpan: 2
                                    // },
                                    {
                                        text: 'Tarikh: _____________________________ \n', colSpan: 5, border: [true, true, true, false],
                                    },{},{},{},{},
                                ],
                                [
                                    { text: 'T/tangan', rowSpan: 2 },
                                    { text: ' ', rowSpan: 2 },
                                    { text: ' ', rowSpan: 2 },
                                    { text: ' ', rowSpan: 2 },
                                    { text: ' ', rowSpan: 2 },
                                    {
                                        text: ' ', border: [false, false, false, false],
                                    },
                                    {
                                        text: 'Rujukan: _____________________________ \n', colSpan: 5, border: [true, false, true, false],
                                    },{},{},{},{},
                                ],
                                [
                                    {},{},{},{},{},
                                    {
                                        text: ' ', border: [false, false, false, false],
                                    },
                                    {
                                        text: 'Tandatangan: _____________________________ \n', colSpan: 5, border: [true, false, true, true],
                                    },{},{},{},{},
                                ],
                            ]
                        }
                    },
                ],
                styles: {
                    header: {
                        fontSize: 12,
                        bold: true,
                        margin: [0, 0, 0, 5]
                    },
                    tableExample: {
                        fontSize: 9,
                        margin: [0, 5, 0, 10]
                    },
                    tableHeader: {
                        bold: true,
                        fontSize: 10,
                        color: 'black'
                    },
                },
                images: {
                    letterhead: {
                        url: "{{asset('/img/letterheadukm.png')}}",
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
        
        function make_table(){
            var width = [50,'*'];
            curloccode.forEach(function (e,i){
                width.push('*');
            });
            var body = [[{ text: 'Lokasi', style: 'tableHeader' }],[{ text: 'Tarikh', style: 'tableHeader' }],[{ text: 'Tandantangan Penjaga Aset', style: 'tableHeader' }]];
            
            movement.forEach(function (e,i){
                let arr1 = [
                    [{ text: e.loc_description }],
                    [{ text: e.trandate }],
                    [{ text: '' }]
                ]
                
                body.push(arr1);
            });
            
            var ret_obj = {
                // headerRows: 1,
                widths: width,
                body: body,
            };
            
            return ret_obj;
        }
        
        function make_header(){
            
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