<!DOCTYPE html>
<html>
    <head>
        <title>AR Ageing Summary</title>
    </head>
    
    <!-- <script src="https://unpkg.com/@jsreport/browser-client/dist/jsreport.umd.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="mydata.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha512-a9NgEEK7tsCvABL7KqtUTQjl69z7091EVPpw5KxPlZ93T141ffe1woLtbXTX+r2/8TtTvRX/v4zTL2UlMUPgwg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.min.js" integrity="sha512-P0bOMePRS378NwmPDVPU455C/TuxDS+8QwJozdc7PGgN8kLqR4ems0U/3DeJkmiE31749vYWHvBOtR+37qDCZQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
    
    </object>
    
    <script>
        
        var array_report=[
            @foreach($array_report as $key => $array_report1)
            {
                @foreach($array_report1 as $key2 => $val)
                    '{{$key2}}' : `{{$val}}`,
                @endforeach
            },
            @endforeach
        ];
        
        var years_bal_all = [
            @foreach($years_bal_all as $key => $val)
            [
                @foreach($val as $key2 => $val2)
                    `{{$val2}}`,
                @endforeach
            ],
            @endforeach
        ];
        
        var years = [
            @foreach($years as $key => $val)
                '{{$val}}',
            @endforeach
        ];
        
        var title = {
            @foreach($company as $key => $val)
                '{{$key}}' : '{{$val}}',
            @endforeach
        };
        
        var company = {
            @foreach($company as $key => $val)
                '{{$key}}' : '{{$val}}',
            @endforeach
        };
        
        $(document).ready(function () {
            var docDefinition = {
                footer: function(currentPage, pageCount) {
                    return [
                        { text: currentPage.toString() + ' of ' + pageCount, alignment: 'center' }
                    ]
                },
                pageSize: 'A4',
                content: [
                    {
                        image: 'letterhead', width: 200, height: 40, style: 'tableHeader', colSpan: 5, alignment: 'center'
                    },
                    {
                        text: '\n{{$title}}\n',
                        style: 'header',
                        alignment: 'center'
                    },
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: ['*', '*'], //panjang standard dia 515
                            body: [
                                [
                                    { text: 'DATE : {{\Carbon\Carbon::now()->format('d/m/Y')}}' },
                                    { text: 'TIME : {{\Carbon\Carbon::now("Asia/Kuala_Lumpur")->format('h:i A')}}' },
                                ],
                            ]
                        },
                        layout: 'noBorders',
                    },
                    // {
                    //     text: '{{$company->name}}\n{{$company->address1}}\n{{$company->address2}}\n{{$company->address3}}\n{{$company->address4}}\n\n\n',
                    //     alignment: 'center',
                    //     style: 'comp_header'
                    // },
                    {
                        style: 'tableExample',
                        table: make_table(),
                        // table: {
                        //     // headerRows: 1,
                        //     widths: [50,'*','*','*'],  //panjang standard dia 515
                        //     body: [
                        //         [
                        //             { text: 'Code', style: 'tableHeader' },
                        //             { text: 'Name', style: 'tableHeader' },
                        //             @foreach ($years as $year)
                        //             { text: '{{$year}}', style: 'tableHeader', alignment: 'right' },
                        //             @endforeach
                        //         ],
                        //         @foreach ($array_report as $obj)
                        //         [
                        //             { text: '{{$obj->debtorcode}}' },
                        //             { text: '{!!$obj->name!!}' },
                        //             @foreach ($years as $year)
                        //             { text: ' ' },
                        //             @endforeach
                        //         ],
                        //         @endforeach
                        //     ]
                        // },
                        layout: 'lightHorizontalLines',
                    },
                    // { text: '', alignment: 'left', fontSize: 9, pageBreak: 'after' },
                    // { canvas: [ { type: 'line', x1: 0, y1: 5, x2: 515, y2: 5, lineWidth: 0.5 } ] },
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
                        margin: [0, 5, 0, 15]
                    },
                    tableHeader: {
                        bold: true,
                        fontSize: 10,
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
                        url: '{{asset('/img/MSLetterHead.jpg')}}',
                        headers: {
                            myheader: '123',
                            myotherheader: 'abc',
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
        
        function make_table(){
            var width = [70,'*'];
            years.forEach(function(e,i){
                width.push('*');
            });
            var body = [[{ text: 'Code', style: 'tableHeader' },{ text: 'Name', style: 'tableHeader' }]];
            years.forEach(function(e,i){
                body[0].push({ text: e, style: 'tableHeader', alignment: 'right' });
            });
            
            array_report.forEach(function(e,i){
                let arr1 = [
                    { text: e.debtorcode },
                    { text: e.name },
                ];
                years.forEach(function(ey,iy){
                    arr1[iy+2] = { text: myparseFloat(years_bal_all[i][iy]), alignment: 'right' };
                });
                body.push(arr1);
            });
            
            var ret_obj = {
                headerRows: 1,
                widths: width,
                body: body,
            };
            
            return ret_obj;
        }
        
        function myparseFloat(val){
            if(val == null) return '0.00';
            // if(val.trim() == '') return '0.00';
            return numeral(val).format('0,0.00');
        }
        
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