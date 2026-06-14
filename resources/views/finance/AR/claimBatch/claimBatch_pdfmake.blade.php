<!DOCTYPE html>
<html>
    <head>
        <title>Claim Batch</title>
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
                pageMargins: [25, 25, 25, 25],
                content: [
                    {
                        columns: [
                            {image: 'letterhead',style:'tableHeader',width:50,alignment: 'left'},
                            {
                                width: '*',alignment: 'right',
                                text: `{!!$company->address1!!} \n {!!$company->address2!!} \n {!!$company->address3!!} \n {!!$company->address4!!}`,
                                fontSize:9,margin: [0, 0, 0, 0]
                            },
                        ],
                    },
                    {
                        text: '\n\n{{\Carbon\Carbon::parse($datesend)->format('d/m/Y')}}\n\n',style: 'basic'
                    },
                    {
                        text: '{!!$debtormast->debtorname!!}\n',style: 'basic'
                    },
                    {
                        text: '{{$debtormast->address1}}\n',style: 'basic'
                    },
                    {
                        text: '{{$debtormast->address2}}\n',style: 'basic'
                    },
                    {
                        text: '{{$debtormast->address3}}\n',style: 'basic'
                    },
                    {
                        text: '{{$debtormast->address4}}\n',style: 'basic'
                    },
                    {
                        text: '\nAttention {{$debtormast->contact}}, \n\nMay this letter reach you in the best of health and Self Esteem\n',style: 'basic\n',style: 'basic'
                    },
                    {
                        text: [
                            'The total attached invoice amounting RM ',
                            { text: 'RM {{$totamount}}', bold: true }
                        ],style: 'basic'
                    },
                    {
                        text: `\n{{$comment_}}\n`,style: 'basic',preserveLeadingSpaces: true
                    },

                    {
                      text: '',
                      pageBreak: 'after'
                    },


                    {
                        text: '\nDate/Term: {{$datesend}} / {{$debtormast->termdays}}',style: 'basic'
                    },
                    {
                        text: `\nOur Reference: \n\n`,style: 'basic'
                    },
                    {
                        text: `Invoice To: {!!$debtormast->debtorname!!}`,style: 'basic'
                    },
                    {
                        text: `{{$debtormast->address1}}`,style: 'basic'
                    },
                    {
                        text: `{{$debtormast->address2}}`,style: 'basic'
                    },
                    {
                        text: `{{$debtormast->address3}}`,style: 'basic'
                    },
                    {
                        text: `{{$debtormast->address4}}\n\n`,style: 'basic'
                    },
                    {
                        style: 'tableExample',
                        table: {
                            widths: [28,50,55,65,55,65,55,50,50], //515
                            body: [
                                [
                                    {text:'Bill No.', style:'totalbold',border: [false, true, false, true]},
                                    {text:'Bill Date', style:'totalbold', alignment: 'right',border: [false, true, false, true]},
                                    {text:'Staff ID', style:'totalbold',border: [false, true, false, true]},
                                    {text:'Staff Name', style:'totalbold',border: [false, true, false, true]},
                                    {text:'GL Ref. No.', style:'totalbold',border: [false, true, false, true]},
                                    {text:'Patient Name', style:'totalbold',border: [false, true, false, true]},
                                    {text:'Membership', style:'totalbold',border: [false, true, false, true]},
                                    {text:'Register Date', style:'totalbold', alignment: 'right',border: [false, true, false, true]},
                                    {text:'Actual Amount', style:'totalbold', alignment: 'right',border: [false, true, false, true]},
                                ],

                                @php($totalAmount = 0)
                                @foreach ($dbacthdr as $obj)
                                @php($totalAmount += $obj->amount)
                                [
                                    {text:`{{$obj->invno}}`,style:'tablesmall',border: [false, false, false, false]},
                                    {text:`{{\Carbon\Carbon::parse($obj->posteddate)->format('d/m/Y')}}`,style:'tablesmall', alignment: 'right',border: [false, false, false, false]},
                                    {text:`{{$obj->Staffid}}`,style:'tablesmall',border: [false, false, false, false]},
                                    {text:`{{$obj->gr_name}}`,style:'tablesmall',border: [false, false, false, false]},
                                    {text:`{{$obj->refno}}`,style:'tablesmall',border: [false, false, false, false]},
                                    {text:`{{$obj->Name}}`,style:'tablesmall',border: [false, false, false, false]},
                                    {text:`{{$obj->Staffid}}`,style:'tablesmall',border: [false, false, false, false]},
                                    {text:`{{\Carbon\Carbon::parse($obj->reg_date)->format('d/m/Y')}}`, alignment: 'right',style:'tablesmall',border: [false, false, false, false]},
                                    {text:`{{number_format($obj->amount, 2, '.', ',')}}`, alignment: 'right',style:'tablesmall',border: [false, false, false, false]}
                                ],
                                @endforeach

                                [
                                    {text:``, border: [false, false, false, false]},
                                    {text:``, border: [false, false, false, false]},
                                    {text:``, border: [false, false, false, false]},
                                    {text:``, border: [false, false, false, false]},
                                    {text:``, border: [false, false, false, false]},
                                    {text:``, border: [false, false, false, false]},
                                    {text:`Total Amount`,style:'totalbold', border: [false, false, false, false]},
                                    {text:``, border: [false, false, false, false]},
                                    {text:`{{number_format($totalAmount, 2, '.', ',')}}`, alignment: 'right',style:'totalbold', border: [false, false, false, false]},
                                ]
                            ]
                        }
                    },
                    {
                        text: `Please make Crossed Cheque payable to\n`,style: 'basic'
                    },
                    {
                        text: `{!!$company->name!!} \n{!!$company->address1!!} \n {!!$company->address2!!} \n {!!$company->address3!!} \n {!!$company->address4!!}`,style: 'basic'
                    },
                    {
                        text: `\nPayment must be made as below:\n\n`,style: 'basic'
                    },
                    {
                        text: `Company name: {!!$company->name!!}\nAccount Number: {!!$company->bankaccno!!}\nAddress:  {!!$company->bankaddress!!}\n`,style: 'basic'
                    },
                    {
                        text: `\nIf payment through a bank transfer, please inform / send payment voucher to us via\n`,style: 'basic'
                    },
                    {
                        text: `1) Phone: {!!$company->telno!!}\n2) Fax: {!!$company->faxno!!}\n3) Email To: {!!$company->email!!}\n`,style: 'basic'
                    },
                    {
                        text: `{!!$company->name!!}\n`,style: 'basic'
                    },
                    {
                        columns: [
                            {
                                width: '*',alignment: 'left',
                                text: `Prepared By:\n\n\n\n______________________________`,
                                fontSize:9,margin: [0, 0, 0, 0]
                            },
                            {
                                width: '*',alignment: 'right',
                                text: `Approved By:\n\n\n\n______________________________`,
                                fontSize:9,margin: [0, 0, 0, 0]
                            },
                        ],
                    },
                ],
                styles: {
                    basic: {
                        fontSize: 10,
                    },
                    tableExample: {
                        fontSize: 9,
                        margin: [0, 5, 0, 15]
                    },
                    totalbold: {
                        bold: true,
                        fontSize: 9,
                    },
                    tablesmall: {
                        bold: false,
                        fontSize: 8,
                        margin: [0, 0, 0, 0]
                    }
                },
                 images: {
                    letterhead: {
                        url: '{{asset('/img/logo.jpg')}}',
                    }
                }
            };
            
            pdfMake.createPdf(docDefinition).getDataUrl(function(dataURL) {
                $('#pdfiframe').attr('src',dataURL);
            });
        });
        
    </script>
    
    <body style="margin: 0px;">
        <iframe id="pdfiframe" width="100%" height="100%" src="" frameborder="0" style="width: 99vw;height: 99vh;"></iframe>
    </body>
</html>