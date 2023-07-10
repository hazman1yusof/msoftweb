<!DOCTYPE html>
<html>
    <head>
        <title>Referral Letter</title>
    </head>
    
    <!-- <script src="https://unpkg.com/@jsreport/browser-client/dist/jsreport.umd.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="mydata.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha512-a9NgEEK7tsCvABL7KqtUTQjl69z7091EVPpw5KxPlZ93T141ffe1woLtbXTX+r2/8TtTvRX/v4zTL2UlMUPgwg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.min.js" integrity="sha512-P0bOMePRS378NwmPDVPU455C/TuxDS+8QwJozdc7PGgN8kLqR4ems0U/3DeJkmiE31749vYWHvBOtR+37qDCZQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
    </object>
    
    <script>
        var patreferral = {
            @foreach($patreferral as $key => $val)
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
                    // {
                    //     text: [
                    //         {text: '{{\Carbon\Carbon::parse($patreferral->refdate)->format('d/m/Y')}}\n\n', margin: [0, 50, 0, 0]},
                    //         '{{$patreferral->refaddress}}\n\n',
                    //         'Dear Dr. {{$patreferral->refdoc}}\n\n',
                    //         '{{$patreferral->reftitle}}\n\n',
                    //         'Diagnosis: {{$patreferral->refdiag}}\n\n',
                    //         'Plan: {{$patreferral->refplan}}\n\n',
                    //         'Prescription: {{$patreferral->refprescription}}\n\n',
                    //         'If I may be of any further assistance in the care of your patient, please let me know. Thank you for providing me the opportunity to participate in the care of your patients.\n\n',
                    //         'Sincerely,\n\n',
                    //         'Dr. {{ucwords(strtolower($patreferral->adduser))}}\n\n',
                    //     ]
                    // },
                    {
                        text: '{{\Carbon\Carbon::parse($patreferral->refdate)->format('d/m/Y')}}\n\n',
                        style: 'refdate'
                    },
                    {
                        text: [
                            '{{$patreferral->refaddress}}\n\n'
                        ]
                    },
                    'Dear Dr. {{$patreferral->refdoc}}\n\n',
                    {
                        text: [
                            '{{$patreferral->reftitle}}\n\n'
                        ]
                    },
                    {
                        text: [
                            'Diagnosis: {{$patreferral->refdiag}}\n\n'
                        ]
                    },
                    {
                        text: [
                            'Plan: {{$patreferral->refplan}}\n\n'
                        ]
                    },
                    {
                        text: [
                            'Prescription: {{$patreferral->refprescription}}\n\n'
                        ]
                    },
                    {
                        text: [
                            'If I may be of any further assistance in the care of your patient, please let me know. Thank you for providing me the opportunity to participate in the care of your patients.\n\n'
                        ]
                    },
                    'Sincerely,\n\n',
                    'Dr. {{ucwords(strtolower($patreferral->adduser))}}\n\n',
                ],
                styles: {
                    refdate: {
                        margin: [0, 50, 0, 0]
                    },
                    header: {
                        fontSize: 18,
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
                        fontSize: 13,
                        color: 'black'
                    },
                    totalbold: {
                        bold: true,
                        fontSize: 10,
                    }
                },
                images: {
                    letterhead: {
                        url: 'http://msoftweb.test/img/MSLetterHead.jpg',
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