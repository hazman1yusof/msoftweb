<!DOCTYPE html>
<html>
    <head>
        <title>Inventory Request Report</title>
    </head>
    
    <!-- <script src="https://unpkg.com/@jsreport/browser-client/dist/jsreport.umd.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="mydata.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha512-a9NgEEK7tsCvABL7KqtUTQjl69z7091EVPpw5KxPlZ93T141ffe1woLtbXTX+r2/8TtTvRX/v4zTL2UlMUPgwg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.min.js" integrity="sha512-P0bOMePRS378NwmPDVPU455C/TuxDS+8QwJozdc7PGgN8kLqR4ems0U/3DeJkmiE31749vYWHvBOtR+37qDCZQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
    </object>
    
    <script>
        
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
                        image: 'letterhead', width: 200, height: 40, style: 'tableHeader', colSpan: 5, alignment: 'center'
                    },
                    {
                        text: '\nINVENTORY REQUEST REPORT\n',
                        style: 'header',
                        alignment: 'center',
                    },
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: ['*', '*'], //panjang standard dia 515
                            body: [
                                [
                                    {text: 'DATE : {{\Carbon\Carbon::now()->format('d/m/Y')}}' },
                                    {text: 'TIME : {{\Carbon\Carbon::now("Asia/Kuala_Lumpur")->format('h:i A')}}'},
                                ],
                            ]
                        },
                        layout: 'noBorders',
                    },
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: [45,20,40,40,110,40,40,25,35,35,35,35,43,45],  //panjang standard dia 515
                            body: [
                                [
                                    {text: 'REQUEST DATE', style: 'tableHeader'},
                                    {text: 'REQ NO', style: 'tableHeader'},
                                    {text: 'REQUEST\nDEPT', style: 'tableHeader'},
                                    {text: 'REQUEST\nTO DEPT', style: 'tableHeader'},
                                    {text: 'ITEMCODE', style: 'tableHeader'},
                                    {text: 'UOM CODE TO REQ DEPT', style: 'tableHeader'},
                                    {text: 'UOM CODE REQ MADE TO', style: 'tableHeader'},
                                    {text: 'MAX QTY', style: 'tableHeader', alignment: 'right'},
                                    {text: 'QOH REQ DEPT', style: 'tableHeader', alignment: 'right'},
                                    {text: 'QOH AT\nREQ TO DEPT', style: 'tableHeader', alignment: 'right'},
                                    {text: 'QTY REQ', style: 'tableHeader', alignment: 'right'},
                                    {text: 'QTY BAL', style: 'tableHeader', alignment: 'right'},
                                    {text: 'QTY\nSUPPLIED', style: 'tableHeader', alignment: 'right'},
                                    {text: 'NET\nPRICE', style: 'tableHeader', alignment: 'right'},
                                ],
                                @foreach ($ivrequest as $obj)
                                    [
                                        {text: '{{\Carbon\Carbon::createFromFormat('Y-m-d',$obj->reqdt)->format('d-m-Y')}}'},
                                        {text: '{{$obj->ivreqno}}'},
                                        {text: '{{$obj->reqdept}}'},
                                        {text: '{{$obj->reqtodept}}'},
                                        {text: `{!!str_replace('`', '', $obj->itemcode)!!}\n{!!str_replace('`', '', $obj->description)!!}`},
                                        {text: `{!!$obj->uomcode!!}`},
                                        {text: `{!!$obj->pouom!!}`},
                                        {text: '{{$obj->maxqty}}', alignment: 'right'},
                                        {text: '{{$obj->qtyonhand}}', alignment: 'right'},
                                        {text: '{{$obj->qohconfirm}}', alignment: 'right'},
                                        {text: '{{$obj->qtyrequest}}', alignment: 'right'},
                                        {text: '{{$obj->qtybalance}}', alignment: 'right'},
                                        {text: '{{$obj->qtytxn}}', alignment: 'right'},
                                        {text: '{{number_format($obj->netprice,2)}}', alignment: 'right' },
                                    ],
                                @endforeach
                            ]
                        },
                        layout: 'lightHorizontalLines',
                    },

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
                        fontSize: 8,
                        margin: [0, 5, 0, 15]
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
                        url: "{{asset('/img/MSLetterHead.jpg')}}",
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
        
        function make_header(){
            
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