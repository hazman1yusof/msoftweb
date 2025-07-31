<!DOCTYPE html>
<html>
    <head>
        <title>Receipt</title>
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
                content: [
                    {
                        image: 'letterhead',width:275, style: 'tableHeader', colSpan: 5, alignment: 'center'
                    },
                    {
                        text: '\n{{$title}}\n',
                        style: 'header',
                        alignment: 'center'
                    },
                    // {
                    //     text: '{{$company->name}}\n{{$company->address1}}\n{{$company->address2}}\n{{$company->address3}}\n{{$company->address4}}\n\n\n',
                    //     alignment: 'center',
                    //     style: 'comp_header'
                    // },
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: [70,1,'*',70,1,70], // panjang standard dia 515
                            body: [
                                [
                                    { text: 'RECEIVED FROM' },
                                    { text: ':' },
                                    { text: '({!!$dbacthdr->payercode!!}) {!!$dbacthdr->payername!!}' },
                                    { text: 'DATE' },
                                    { text: ':' },
                                    @if(!empty($dbacthdr->posteddate))
                                        { text: '{{\Carbon\Carbon::parse($dbacthdr->posteddate)->format('d/m/Y')}}' },
                                    @else
                                        { text: '' },
                                    @endif
                                ],
                                [
                                    { text: 'RECEIPT NO' },
                                    { text: ':' },
                                    { text: '{{$dbacthdr->recptno}}' },
                                    { text: 'CASHIER' },
                                    { text: ':' },
                                    { text: '{{strtoupper($dbacthdr->entryuser)}}' },
                                ],
                                [
                                    { text: 'IC NO' },
                                    { text: ':' },
                                    { text: '{{$dbacthdr->Newic}}' },
                                    { text: 'PAY BY' },
                                    { text: ':' },
                                    { text: '{{$dbacthdr->paymode}}' },
                                ],
                                [
                                    { text: 'MRN' },
                                    { text: ':' },
                                    @if(empty($dbacthdr->mrn))
                                        { text: '-' },
                                    @else
                                        { text: `({{str_pad($dbacthdr->mrn, 7, "0", STR_PAD_LEFT)}}) {!!$dbacthdr->Name!!}` },
                                    @endif
                                    { text: 'AUTHORISED NO' },
                                    { text: ':' },
                                    { text: '{{$dbacthdr->authno}}' },
                                ],
                            ]
                        },
                        layout: 'noBorders',
                    },
                    @if(count($dballoc) > 0 )
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: [160,160,40,40,47], // panjang standard dia 515
                            body: [
                                [
                                    { text: 'Description', style: 'tableHeader' },
                                    { text: 'Name', style: 'tableHeader' },
                                    { text: 'MRN', style: 'tableHeader' },
                                    { text: 'EpisNo', style: 'tableHeader' },
                                    { text: 'Amount', style: 'tableHeader', alignment: 'right' },
                                ],
                                @foreach ($dballoc as $obj)
                                [
                                    @if(!empty($obj->allocdate))
                                        @if($dbacthdr->trantype == 'RF')
                                            { text: '{{str_pad($obj->recptno, 8, "0", STR_PAD_LEFT)}} dated {{\Carbon\Carbon::parse($obj->allocdate)->format('d/m/Y')}}' },
                                        @else
                                            { text: 'Inv No {{str_pad($obj->invno, 8, "0", STR_PAD_LEFT)}} dated {{\Carbon\Carbon::parse($obj->entrydate)->format('d/m/Y')}}' },
                                        @endif
                                    @else
                                        @if($dbacthdr->trantype == 'RF')
                                            { text: '{{str_pad($obj->recptno, 8, "0", STR_PAD_LEFT)}} dated {{\Carbon\Carbon::parse($obj->allocdate)->format('d/m/Y')}}' },
                                        @else
                                            { text: 'Inv No {{str_pad($obj->invno, 8, "0", STR_PAD_LEFT)}}' },
                                        @endif
                                    @endif
                                    @if(!empty($obj->pm_name))
                                    { text: `{!!str_replace('`', '', $obj->pm_name)!!}` },
                                    @else
                                    { text: `{!!str_replace('`', '', $obj->name)!!}` },
                                    @endif
                                    @if($obj->mrn == '0')
                                        { text: ' ' },
                                    @else
                                        { text: '{{$obj->mrn}}' },
                                    @endif
                                    @if($obj->episno == '0')
                                        { text: ' ' },
                                    @else
                                        { text: '{{$obj->episno}}' },
                                    @endif
                                    { text: '{{number_format($obj->amount,2)}}', alignment: 'right' },
                                ],
                                @endforeach
                            ]
                        },
                        layout: 'lightHorizontalLines',
                    },
                    @endif
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: ['*'], // panjang standard dia 515
                            body: [
                                [
                                    { text: `REFERENCE NO : {{$dbacthdr->reference}}` },
                                ],
                                [
                                    { text: `REMARK : {{$dbacthdr->remark}}` },
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
                    @if($dbacthdr->trantype == 'RD')
                        {
                            style: 'tableExample',
                            table: {
                                headerRows: 1,
                                widths: [65,1,80], // panjang standard dia 515
                                body: [
                                    [
                                        { text: 'REFUND INFORMATION', alignment: 'center', bold: true, colSpan: 3, border: [true, true, true, false] },{},{},
                                    ],
                                    [
                                        { text: 'PAYABLE TO', border: [true, false, false, false] },
                                        { text: ':', border: [false, false, false, false] },
                                        { text: '___________________', border: [false, false, true, false] },
                                    ],
                                    [
                                        { text: 'IC NO', border: [true, false, false, false] },
                                        { text: ':', border: [false, false, false, false] },
                                        { text: '___________________', border: [false, false, true, false] },
                                    ],
                                    [
                                        { text: 'BANK NAME', border: [true, false, false, false] },
                                        { text: ':', border: [false, false, false, false] },
                                        { text: '___________________', border: [false, false, true, false] },
                                    ],
                                    [
                                        { text: 'BANK ACCT NO', border: [true, false, false, false] },
                                        { text: ':', border: [false, false, false, false] },
                                        { text: '___________________', border: [false, false, true, false] },
                                    ],
                                    [
                                        { text: 'CONTACT NO', border: [true, false, false, false] },
                                        { text: ':', border: [false, false, false, false] },
                                        { text: '___________________', border: [false, false, true, false] },
                                    ],
                                    [
                                        { text: 'PATIENT / NEXT OF KIN', colSpan: 3, border: [true, false, true, true] },{},{},
                                    ],
                                ]
                            },
                            // layout: 'noBorders',
                        },
                    @endif
                    @if($dbacthdr->trantype == 'RF')
                        {
                            style: 'body_sign',
                            table: {
                                widths: [250,100,200],//panjang standard dia 515
                                dontBreakRows: true,
                                body: [
                                    [
                                        {text: 'Verified By\n\n\n\n_____________________________',bold: true,alignment: 'left',margin: [0, 0, 0, 0]},
                                        {text: 'Received By\n\nName\n\nI/C No.',bold: true,alignment: 'right'},
                                        {text: ': ________________________\n\n: ________________________\n\n: ________________________',bold: true,alignment: 'left'},
                                    ],
                                    [
                                        {text: 'Approved By\n\n\n\n_____________________________',bold: true,alignment: 'left',margin: [0, 0, 0, 0]},
                                        {},
                                        {},
                                    ],
                                ]
                            },
                            layout: 'noBorders',
                        },
                    @endif
                    {
                        text: 'Terms and Condition:', fontSize: 9,
                    },
                    {
                        text: '- Item listed are considered sold and neither returnable nor refundable.', fontSize: 9,
                    },
                    {
                        text: '\nNote: \nValidity of receipt subject to clearing of cheques. Any refund below RM2000.00 will be on the same day, otherwise within 2 weeks by cheque. Please bring official receipt upon collection of refund', fontSize: 9,
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