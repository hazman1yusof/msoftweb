<!DOCTYPE html>
<html>
    <head>
        <title>Debit Note AR</title>
    </head>
    
    <!-- <script src="https://unpkg.com/@jsreport/browser-client/dist/jsreport.umd.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="mydata.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha512-a9NgEEK7tsCvABL7KqtUTQjl69z7091EVPpw5KxPlZ93T141ffe1woLtbXTX+r2/8TtTvRX/v4zTL2UlMUPgwg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.min.js" integrity="sha512-P0bOMePRS378NwmPDVPU455C/TuxDS+8QwJozdc7PGgN8kLqR4ems0U/3DeJkmiE31749vYWHvBOtR+37qDCZQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
    </object>
    
    <script>

        var totamt_eng = '{{$totamt_eng}}';
        
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
                        image: 'letterhead',width:275, style: 'tableHeader', colSpan: 5, alignment: 'center'
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
                            widths: [70,1,'*',70,1,70], // panjang standard dia 515
                            body: [
                                [
                                    { text: 'Debtor' },
                                    { text: ':' },
                                    { text: `{{$dbacthdr->debtorcode}} - {!!str_replace('`', '', $dbacthdr->debt_name)!!}` ,colSpan:4},
                                    {},{},{}
                                ],
                                [
                                    { text: 'Address' },
                                    { text: ':' },
                                    { text: `{!!str_replace('`', '', $dbacthdr->cust_address1)!!}` },
                                    { text: 'Document No.' },
                                    { text: ':' },
                                    @if(!empty($dbacthdr->auditno))
                                        { text: 'DN-{{str_pad($dbacthdr->auditno, 5, "0", STR_PAD_LEFT)}}' },
                                    @else
                                        { text: '' },
                                    @endif
                                ],
                                [
                                    { text: '' },
                                    { text: ':' },
                                    { text: `{!!str_replace('`', '', $dbacthdr->cust_address2)!!}` },
                                    { text: 'Date' },
                                    { text: ':' },
                                    { text: '{{\Carbon\Carbon::parse($dbacthdr->entrydate)->format('d/m/Y')}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: ':' },
                                    { text: `{!!str_replace('`', '', $dbacthdr->cust_address3)!!}` },
                                    { text: 'Reference No.' },
                                    { text: ':' },
                                    { text: '{{$dbacthdr->reference}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: ':' },
                                    { text: `{!!str_replace('`', '', $dbacthdr->cust_address4)!!}` },
                                    {},{},{}
                                ],
                            ]
                        },
                        layout: 'noBorders',
                    },
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: [70,'*','*'], // panjang standard dia 515
                            body: [
                                [
                                    { text: 'Date', style: 'tableHeader' },
                                    { text: 'Description', style: 'tableHeader' },
                                    { text: 'Amount', style: 'tableHeader', alignment: 'right' },
                                ],
                                @foreach ($dbactdtl as $obj)
                                [
                                    { text: '{{\Carbon\Carbon::parse($obj->entrydate)->format('d/m/Y')}}' },
                                    { text: '{{$obj->dept_description}}' },
                                    { text: '{{number_format($obj->amount,2)}}', alignment: 'right' },
                                ],
                                @endforeach
                            ]
                        },
                        layout: 'lightHorizontalLines',
                    },
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: ['*'], // panjang standard dia 515
                            body: [
                                [
                                    { text: `REMARK : {!!str_replace('`', '', $dbacthdr->remark)!!}` },
                                ],
                            ]
                        },
                        layout: 'noBorders',
                    },
                    { canvas: [ { type: 'line', x1: 0, y1: 5, x2: 515, y2: 5, lineWidth: 0.5 } ] },
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: ['*', '*'], // panjang standard dia 515
                            body: [
                                [
                                    { text: 'Ringgit Malaysia', style: 'tableHeader' },
                                    { text: 'Total Amount', style: 'tableHeader', alignment: 'right' }
                                ],
                                [
                                    { text: '{{$totamt_eng}}' },
                                    { text: '{{number_format($dbacthdr->amount,2)}}', alignment: 'right' }
                                ],
                            ]
                        },
                        layout: 'noBorders',
                    },
                    {
                        text: 'Note:', fontSize: 9,
                    },
                    {
                        text: '1. Please quote document number when making payments.', fontSize: 9,
                    },
                    {
                        text: '2. Recipient Copy is to be returned with payment.', fontSize: 9,
                    },
                    {
                        text: '3. All cheque / money order should be crossed and payable to \n {{$company->name}} / ACCOUNT NO: {{$sysparam->pvalue2}}', fontSize: 9,
                    },
                    {
                        text: '4. This invoice must be paid within 14 days after its date of issue.', fontSize: 9,
                    },
                    {
                        text: '5. Please ignore this invoice if payment has been made.', fontSize: 9,
                    },
                    {
                        text: '6. Please inform us with payment proof for EFT / direct payment.', fontSize: 9,
                    },
                    {
                        text: '\nTHIS IS COMPUTER GENERATED DOCUMENT. NO SIGNATURE IS REQUIRED.', fontSize: 10, alignment: 'center'
                    },
                    {
                        text: 'Date printed: {{\Carbon\Carbon::now("Asia/Kuala_Lumpur")->format('d/m/Y')}} {{\Carbon\Carbon::now("Asia/Kuala_Lumpur")->format('H:i')}} by {{session('username')}}', fontSize: 7, alignment: 'center'
                    },
                ],
                styles: {
                    header: {
                        fontSize: 14,
                        bold: true,
                        margin: [0, 0, 0, 10]
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
                    },
                    body_sign: {
                        fontSize: 9,
                        margin: [0, 0, 0, 20]
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