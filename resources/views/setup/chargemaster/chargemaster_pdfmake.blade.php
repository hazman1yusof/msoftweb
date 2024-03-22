<!DOCTYPE html>
<html>
    <head>
        <title>Charge Price List</title>
    </head>
    
    <!-- <script src="https://unpkg.com/@jsreport/browser-client/dist/jsreport.umd.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="mydata.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha512-a9NgEEK7tsCvABL7KqtUTQjl69z7091EVPpw5KxPlZ93T141ffe1woLtbXTX+r2/8TtTvRX/v4zTL2UlMUPgwg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.min.js" integrity="sha512-P0bOMePRS378NwmPDVPU455C/TuxDS+8QwJozdc7PGgN8kLqR4ems0U/3DeJkmiE31749vYWHvBOtR+37qDCZQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    </object>
    
    <script>
        var chggroup=[
            @foreach($chggroup as $key => $chggroup1)
            {
                @foreach($chggroup1 as $key2 => $val)
                    '{{$key2}}' : `{{$val}}`,
                @endforeach
            },
            @endforeach
        ];

        var chgtype=[
            @foreach($chgtype as $key => $chgtype1)
            {
                @foreach($chgtype1 as $key2 => $val)
                    '{{$key2}}' : `{!!$val!!}`,
                @endforeach
            },
            @endforeach
        ];

        var chgmast=[
            @foreach($chgmast as $key => $chgmast1)
            {
                '{{$key2}}' : `{!!str_replace('`', '', $val)!!}`,
            },
            @endforeach
        ];

        var array_report=[
            @foreach($array_report as $key => $array_report1)
            {
                @foreach($array_report1 as $key2 => $val)
                    '{{$key2}}' : `{!!str_replace('`', '', $val)!!}`,
                @endforeach
            },
            @endforeach
        ];

        $(document).ready(function () {
            var docDefinition = {
                footer: function(currentPage, pageCount) {
                    return [
                        { text: currentPage.toString() + ' of ' + pageCount, alignment: 'center' }
                    ]
                },
                pageSize: 'A4',
                pageOrientation: 'landscape',
                content: [
                    {
                        image: 'letterhead',width:200, height:40, style: 'tableHeader', colSpan: 5, alignment: 'center'
                    },
                    {
                        text: '\nCHARGE PRICE LIST\n',
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
                                    {text: 'DATE : {{\Carbon\Carbon::now("Asia/Kuala_Lumpur")->format('d/m/Y')}}' },
                                    {text: 'TIME : {{\Carbon\Carbon::now("Asia/Kuala_Lumpur")->format('h:i A')}}'},
                                ],
                            ]
                        },
                        layout: 'noBorders',
                    },
                    {
                        style: 'tableExample',
                        table: make_table(),
                        layout: 'lightHorizontalLines',
                    },
                  

                   
                ],
                styles: {
                    header: {
                        fontSize: 14,
                        bold: true,
                        margin: [0, 0, 0, 3]
                    },
                    tableExample: {
                        fontSize: 9,
                        margin: [0, 5, 0, 15]
                    },
                    tableHeader: {
                        bold: true,
                        fontSize: 9,
                        color: 'black'
                    },
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

            let widths = ['*','*','*','*','*','*','*','*','*'];

            var table = {
                widths: widths,
                body: null,
            };
            var retval = [];
            var header = init_header();
            retval.push(header);

            chggroup.forEach(function(e_cg,i_cg){
                if(e_cg.chggroup != ''){
                    var arr_cg = [
                        { text: e_cg.chggroup, bold:true },
                        { text: ' '},
                        { text: ' '},
                        { text: ' '},
                        { text: ' '},
                        { text: ' '},
                        { text: ' '},
                        { text: ' '},
                        { text: ' '},
                    ];
                    retval.push(arr_cg);

                    chgtype.forEach(function(e_ct,i_ct){
                        if(e_ct.chggroup == e_cg.chggroup){
                            var arr_ct = [
                                { text: e_ct.chgtype, bold:true},
                                { text: ' '},
                                { text: ' '},
                                { text: ' '},
                                { text: ' '},
                                { text: ' '},
                                { text: ' '},
                                { text: ' '},
                                { text: ' '},
                            ];
                            retval.push(arr_ct);

                            array_report.forEach(function(e,i){
                                if(e.chgtype == e_ct.chgtype){
                                    var arr =[
                                        { text: e.description},
                                        { text: e.uom_cm},
                                        { text: e.packqty },
                                        { text: e.chgcode },
                                        { text: myparseFloat(e.amt1), alignment: 'right' },
                                        { text: myparseFloat(e.amt2), alignment: 'right' },
                                        { text: myparseFloat(e.amt3), alignment: 'right' },
                                        { text: myparseFloat(e.costprice), alignment: 'right' },
                                        { text: ' '},
                                    ];
                                    retval.push(arr);
                                }
                            });
                        }
                    });
                }
            });

            table.body = retval;
            console.log(table);

            return table;
        }

        function init_header(){

            let header = [
                { text: 'Description', style: 'tableHeader' },
                { text: 'UOM', style: 'tableHeader' },
                { text: 'Packing', style: 'tableHeader' },
                { text: 'Code', style: 'tableHeader' },
                { text: 'I/P Price', style: 'tableHeader', alignment: 'right' },
                { text: 'O/P Price', style: 'tableHeader', alignment: 'right' },
                { text: 'Other Price', style: 'tableHeader', alignment: 'right' },
                { text: 'A/Cost Price', style: 'tableHeader', alignment: 'right' },
                { text: 'Current Price', style: 'tableHeader', alignment: 'right' },
            ];

            return header;
        }

        function myparseFloat(val){
            if(val == null) return '0.00';
            if(val == '') return '0.00';
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