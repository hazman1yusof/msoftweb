<!DOCTYPE html>
<html>
    <head>
        <title>AP Ageing Details</title>
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

        var grouping_name = [
            @foreach($grouping as $key => $val)
                @if($key+1 < count($grouping))
               '{{$val+1}}-{{$grouping[$key+1]}}',
                @else
                '> {{$val}}',
                @endif
            @endforeach
        ];

        var grouping = [
            @foreach($grouping as $key => $val)
            '{{$val}}',
            @endforeach
        ];
        
        var array_report=[
            @foreach($array_report as $key => $array_report1)
            {
                @foreach($array_report1 as $key2 => $val)
                    '{{$key2}}' : `{!!$val!!}`,
                @endforeach
            },
            @endforeach
        ];

        var suppgroup=[
            @foreach($suppgroup as $key => $suppgroup1)
            {
                @foreach($suppgroup1 as $key2 => $val)
                    '{{$key2}}' : `{{$val}}`,
                @endforeach
            },
            @endforeach
        ];

        var suppcode=[
            @foreach($suppcode as $key => $suppcode1)
            {
                @foreach($suppcode1 as $key2 => $val)
                    '{{$key2}}' : `{!!$val!!}`,
                @endforeach
            },
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
                pageOrientation: 'landscape',
                pageMargins: [15, 30, 15, 30],
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
                    {
                        style: 'tableExample',
                        table: make_table(),
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
                        margin: [-5, 5, -5, 15]
                    },
                    table_td: {
                        fontSize: 8,
                        margin: [-2, 0, -5, 0]
                    },
                    totalbold: {
                        bold: true,
                        fontSize: 9,
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

            let widths = [60,80,80,48];
            grouping.forEach(function(e,i){
                widths.push('*');
            });
            widths.push('*');
            widths.push(40);

            var table = {
                widths: widths,
                body: null,
            };
            var retval = [];
            var header = init_header();
            retval.push(header);

            suppgroup.forEach(function(e_sg,i_sg){
                if(e_sg.suppgroup != ''){
                    var arr_sg = [
                        { text: e_sg.suppgroup, style: 'table_td',bold:true },
                        { text: e_sg.description, colSpan: 3, style: 'table_td',bold:true },
                        { text: ' ' },
                        { text: ' ' },
                        //xxxxx
                        { text: ' ', alignment: 'right' },
                        { text: ' ' },
                    ];
                    retval.push(init_range(arr_sg,''));

                    suppcode.forEach(function(e_scode,i_scode){
                        if(e_scode.suppgroup == e_sg.suppgroup){
                            var arr_scode = [
                                { text: e_scode.suppcode, style:'table_td' },
                                { text: e_scode.name, colSpan: 2, style:'table_td' },
                                { text: ' ', style:'table_td' },
                                { text: ' ', style:'table_td' },
                                //xxxxx
                                { text: ' ', alignment: 'right', style:'table_td' },
                                { text: ' ', style:'table_td' },
                            ];
                            retval.push(init_range(arr_scode,''));

                            var total = 0.00;
                            array_report.forEach(function(e,i){
                                if(e.suppcode == e_scode.suppcode){
                                    total += parseFloat(e.newamt);
                                    var arr =[
                                        { text: '', style:'table_td'},
                                        { text: e.remarks, style:'table_td' },
                                        { text: e.document, style:'table_td' },
                                        { text: dateFormatter(e.postdate), style:'table_td' },
                                        /*
                                        { text: ' ', alignment: 'right' },
                                        { text: myparseFloat(e.newamt), alignment: 'right' },
                                        { text: ' ', alignment: 'right' },
                                        { text: ' ', alignment: 'right' },
                                        { text: ' ', alignment: 'right' },
                                        */
                                        { text: ' ', alignment: 'right', style:'table_td' },
                                        { text: e.unit, style:'table_td' },
                                    ];
                                    retval.push(init_range(arr,e));
                                }
                            });

                            var arr_tot =[
                                { text: ' ', style:'table_td' },
                                { text: ' ', style:'table_td' },
                                { text: ' ', style:'table_td' },
                                { text: 'TOTAL', bold: true , alignment: 'right', style:'table_td'},
                                // { text: myparseFloat(total), alignment: 'right' },
                                // { text: ' ', alignment: 'right' },
                                // { text: ' ', alignment: 'right' },
                                // { text: ' ', alignment: 'right' },
                                // { text: ' ', alignment: 'right' },
                                { text: myparseFloat(total), alignment: 'right', style:'table_td' },
                                { text: ' ' },
                            ];
                            retval.push(init_range(arr_tot,'total'));
                        }

                    });

                }
            });

            table.body = retval;

            return table;

        }

        function init_range(arr,type){
            if(type == ''){
                var newarr = arr.slice(0,4);

                grouping.forEach(function(e,i){
                    newarr.push({ text: ' ' });
                });

                newarr.push(arr[4]);
                newarr.push(arr[5]);

                return newarr;
            }else if(type == 'total'){
                var newarr = arr.slice(0,3);
                arr[3].colSpan = grouping.length+1;

                newarr.push(arr[3]);
                grouping.forEach(function(e,i){
                    newarr.push({ text: ' ' });
                });

                newarr.push(arr[4]);
                newarr.push(arr[5]);

                return newarr;
            }else{
                var newarr = arr.slice(0,4);

                grouping.forEach(function(e,i){
                    if(parseInt(type.group) == i){
                        newarr.push({text: myparseFloat(type.newamt), alignment: 'right', style:'table_td'});
                    }else{
                        newarr.push({ text: myparseFloat(0), style:'table_td', alignment: 'right' });
                    }
                });

                newarr.push(arr[4]);
                newarr.push(arr[5]);

                return newarr;
            }

            return arr;
        }

        function init_header(){

            let header = [{ text: 'Code', style: 'tableHeader' },
                        { text: 'Company', style: 'tableHeader' },
                        { text: 'Document No', style: 'tableHeader' },
                        { text: 'Date', style: 'tableHeader' }];

            grouping_name.forEach(function(e,i){
                header.push({ text: e+' days', style: 'tableHeader', alignment: 'right' });
            });

            header.push({ text: 'Total', style: 'tableHeader', alignment: 'right' });
            header.push({ text: 'Units', style: 'tableHeader' });

            return header;
        }
        
        function dateFormatter(val){
            if(val == null) return '';
            if(val.trim() == '') return '';
            return moment(val).format("DD-MM-YYYY");
        } 

        function myparseFloat(val){
            if(val == null) return '0.00';
            if(val == '') return '0.00';
            return numeral(val).format('0,0.00');
        } 

        function myparseFloatVV(unitcost,dspqty){
            if(dspqty == null) return '0.00';
            if(dspqty == '') return '0.00';
            return numeral(unitcost*dspqty).format('0,0.00');
        } 
        
    </script>
    
    <body style="margin: 0px;">
        <iframe id="pdfiframe" width="100%" height="100%" src="" frameborder="0" style="width: 99vw;height: 99vh;"></iframe>
    </body>
</html>