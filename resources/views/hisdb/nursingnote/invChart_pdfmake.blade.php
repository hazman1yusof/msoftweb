<!DOCTYPE html>
<html>
    <head>
        <title>Investigation Chart</title>
    </head>
    
    <!-- <script src="https://unpkg.com/@jsreport/browser-client/dist/jsreport.umd.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="mydata.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha512-a9NgEEK7tsCvABL7KqtUTQjl69z7091EVPpw5KxPlZ93T141ffe1woLtbXTX+r2/8TtTvRX/v4zTL2UlMUPgwg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.min.js" integrity="sha512-P0bOMePRS378NwmPDVPU455C/TuxDS+8QwJozdc7PGgN8kLqR4ems0U/3DeJkmiE31749vYWHvBOtR+37qDCZQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
    </object>
    
    <script>
        var inv_type = [
            @foreach($inv_type as $key => $inv_type1)
            {
                @foreach($inv_type1 as $key2 => $val)
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
        
        var nurs_investigation = [
            @foreach($nurs_investigation as $key => $nurs_investigation1)
            {
                @foreach($nurs_investigation1 as $key2 => $val)
                    '{{$key2}}' : `{!!str_replace('`', '', $val)!!}`,
                @endforeach
            },
            @endforeach
        ];
        
        var inv_cat = [
            @foreach($inv_cat as $key => $inv_cat1)
            {
                @foreach($inv_cat1 as $key2 => $val)
                    '{{$key2}}' : `{{$val}}`,
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
                pageOrientation: 'landscape',
                // pageMargins: [10, 20, 20, 30],
                content: [
                    {
                        image: 'letterhead', width: 175, style: 'tableHeader', colSpan: 5, alignment: 'center'
                    },
                    {
                        text: '\nINVESTIGATION CHART\n\n',
                        style: 'header',
                        alignment: 'center'
                    },
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: [65,1,'*',35,1,'*'], // panjang standard dia 515
                            body: [
                                [
                                    { text: 'PATIENT NAME' },
                                    { text: ':' },
                                    { text: '{{$pat_mast->Name}}' },
                                    { text: 'MRN' },
                                    { text: ':' },
                                    { text: '{{str_pad($pat_mast->MRN, 7, "0", STR_PAD_LEFT)}}' }
                                ],
                            ]
                        },
                        layout: 'noBorders',
                    },
                    // { canvas: [{ type: 'line', x1: 0, y1: 5, x2: 515, y2: 5, lineWidth: 0.5 }] },
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
                        margin: [0, 10, 0, 0]
                    },
                    subheader: {
                        fontSize: 16,
                        bold: true,
                        margin: [0, 10, 0, 5]
                    },
                    tableExample: {
                        fontSize: 9,
                        margin: [0, 5, 0, 10]
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
                        url: '{{asset('/img/letterheadukm.png')}}',
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
            var width = [39,43];
            datetime.forEach(function (e,i){
                width.push(48);
            });
            var body = [[{ text: 'Date\nTime', style: 'tableHeader' },{ text: '', style: 'tableHeader' }]];
            datetime.forEach(function (e,i){
                var datetime = e.date+'\n'+e.time;
                body[0].push({ text: datetime, style: 'tableHeader', alignment: 'left' });
            });
            
            var ifsameshow = '_';
            inv_type.forEach(function (e,i){
                let arr1 = [];
                if(ifsameshow != e.inv_code){
                    arr1[0] = { text: e.inv_code };
                    arr1[1] = { text: e.inv_cat };
                    ifsameshow = e.inv_code;
                }else{
                    arr1[0] = { text: '' };
                    arr1[1] = { text: e.inv_cat };
                }
                let data_x = e.inv_code+'_'+e.inv_cat;
                datetime.forEach(function (ey,iy){
                    let data_y = ey.entereddate+'_'+ey.enteredtime;
                    let data_all = data_x+'_'+data_y;
                    
                    arr1[iy+2] = { text: search_data(data_all), alignment: 'right' };
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
        
        function search_data(data_all){
            var ret_data = '';
            nurs_investigation.forEach(function (ez,iz){
                let data_all_z = ez.inv_code+'_'+ez.inv_cat+'_'+ez.entereddate+'_'+ez.enteredtime
                if(data_all == data_all_z){
                    if(ret_data == ''){
                        ret_data = ez.values;
                    }
                }
            });
            
            return ret_data;
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