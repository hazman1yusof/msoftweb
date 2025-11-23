<!DOCTYPE html>
<html>
    <head>
        <title>Reminder</title>
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
                        text: '\n{{$currentDate}}',style: 'basic'
                    },
                    {
                        text: `\n{{$debtormast->name}}`,style: 'basic'
                    },
                    {
                        text: `({{$debtormast->debtorcode}})`,style: 'basic'
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
                        text: `{{$debtormast->address4}}`,style: 'basic'
                    },
                    {
                        text: `\nAttn: {{$debtormast->contact}}`,style: 'basic'
                    },
                    {
                        text: `\nAccount: {{$debtormast->debtorcode}}`,style: 'basic'
                    },
                    {
                        text: '\nDear Sir Madam',style: 'basic'
                    },
                    {
                        text: `\nRe : Outstanding Balance RM{{number_format($days_greater_tot, 2, '.', ',')}}`,style: 'basic'
                    },

                    {
                        text: `\n{{$comment_}}\n`,style: 'basic',preserveLeadingSpaces: true
                    },

                    {
                      text: '',
                      pageBreak: 'after'
                    },


                    {
                        text: '\n{{$currentDate}}',style: 'basic'
                    },
                    {
                        text: `\n{{$debtormast->name}}`,style: 'basic'
                    },
                    {
                        text: `({{$debtormast->debtorcode}})`,style: 'basic'
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
                        text: `{{$debtormast->address4}}`,style: 'basic'
                    },
                    {
                        text: `\nAttn: {{$debtormast->contact}}`,style: 'basic'
                    },
                    {
                        text: `\nAccount: {{$debtormast->debtorcode}}\n\n`,style: 'basic'
                    },
                    {
                        style: 'tableExample',
                        table: {
                            widths: [50,50,70,70,50,50,50], //515
                            body: [
                                [
                                    {text:'DOC DATE', style:'totalbold',border: [false, true, false, true]},
                                    {text:'AUDIT NO', style:'totalbold',border: [false, true, false, true]},
                                    {text:'DOCUMENT', style:'totalbold',border: [false, true, false, true]},
                                    {text:'REMARK', style:'totalbold',border: [false, true, false, true]},
                                    {text:'DEBIT', style:'totalbold', alignment: 'right',border: [false, true, false, true]},
                                    {text:'CREDIT', style:'totalbold', alignment: 'right',border: [false, true, false, true]},
                                    {text:'TOTAL', style:'totalbold', alignment: 'right',border: [false, true, false, true]},
                                ],

                                @php($totalAmount = 0)
                                @foreach ($array_report as $obj)
                                [
                                    {text:`{{\Carbon\Carbon::parse($obj->posteddate)->format('d/m/Y')}}`,border: [false, false, false, false]},
                                    {text:`{{str_pad($obj->auditno, 7, "0", STR_PAD_LEFT)}}`,border: [false, false, false, false]},
                                    {text:`{{$obj->doc_no}}`,border: [false, false, false, false]},
                                    {text:`{{$obj->reference}}`,border: [false, false, false, false]},

                                    @if(isset($obj->amount_dr))

                                        @php($totalAmount += $obj->amount_dr)
                                        {text:`{{number_format($obj->amount_dr, 2, '.', ',')}}`, alignment: 'right',border: [false, false, false, false]},
                                        {text:``,border: [false, false, false, false]},
                                    @else

                                        @php($totalAmount += $obj->amount_dr)
                                        {text:``,border: [false, false, false, false]},
                                        {text:`{{number_format($obj->amount_cr, 2, '.', ',')}}`, alignment: 'right',border: [false, false, false, false]},
                                    @endif

                                    {text:`{{number_format($totalAmount, 2, '.', ',')}}`, alignment: 'right',border: [false, false, false, false]},
                                ],
                                @endforeach
                            ]
                        }
                    },
                    {
                        style: 'tableExample',
                        table: {
                            widths: [66,66,66,66,66,66], //515
                            body: [
                                [
                                    {text:'1-30 DAYS', style:'totalbold',border: [false, true, false, true]},
                                    {text:'31-60 DAYS', style:'totalbold',border: [false, true, false, true]},
                                    {text:'61-90 DAYS', style:'totalbold',border: [false, true, false, true]},
                                    {text:'91-120 DAYS', style:'totalbold',border: [false, true, false, true]},
                                    {text:'> 120 DAYS', style:'totalbold', alignment: 'right',border: [false, true, false, true]},
                                    {text:'TOTAL', style:'totalbold', alignment: 'right',border: [false, true, false, true]},
                                ],
                                [
                                    @php($total_line = 0)
                                    @foreach ($grouping_tot as $key => $val)
                                        @php($total_line = $total_line + $val)
                                        {text:`{{number_format($val, 2, '.', ',')}}`, alignment: 'right',border: [false, false, false, false]},
                                    @endforeach
                                    {text:`{{number_format($total_line, 2, '.', ',')}}`, alignment: 'right',border: [false, false, false, false]},
                                ]
                            ]
                        }
                    },
                    {
                        text: `Note:-\n`,style: 'basic'
                    },
                    {
                        text: `If you do not agree with the above balance please inform us in writing within 14 days.\n`,margin: [15, 0, 0, 0],style: 'basic'
                    },
                    {
                        text: `Any payment received after the close of the month will appear in next month's statement.\n`,margin: [15, 0, 0, 0],style: 'basic'
                    },
                    {
                        text: `Cheque payment is valid only when cleared by our bank.`,margin: [15, 0, 0, 0],style: 'basic'
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
                    }
                },
                 images: {
                    letterhead: {
                        url: "{{asset('/img/letterheadukm.png')}}",
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