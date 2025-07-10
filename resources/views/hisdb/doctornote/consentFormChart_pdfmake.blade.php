<!DOCTYPE html>
<html>
    <head>
        <title>KEIZINAN MENJALANI PROSEDUR</title>
    </head>
    
    <!-- <script src="https://unpkg.com/@jsreport/browser-client/dist/jsreport.umd.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="mydata.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha512-a9NgEEK7tsCvABL7KqtUTQjl69z7091EVPpw5KxPlZ93T141ffe1woLtbXTX+r2/8TtTvRX/v4zTL2UlMUPgwg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.min.js" integrity="sha512-P0bOMePRS378NwmPDVPU455C/TuxDS+8QwJozdc7PGgN8kLqR4ems0U/3DeJkmiE31749vYWHvBOtR+37qDCZQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
    </object>
    
    <script>
        
        $(document).ready(function (){
            var docDefinition = {
                footer: function (currentPage, pageCount){
                    return [
                        { text: currentPage.toString() + ' of ' + pageCount, alignment: 'center' }
                    ]
                },
                pageSize: 'A4',
                // pageOrientation: 'landscape',
                content: [
                    {
                        image: 'letterhead', width: 430, style: 'tableHeader', colSpan: 5, alignment: 'center'
                    },
                    {
                        text: 'KEIZINAN MENJALANI PROSEDUR',
                        style: 'header',
                        alignment: 'center',
                    },
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: ['*','*'], // panjang standard dia 515
                            body: [
                                [
                                    { 
                                        text: [
                                            'Saya ', { text:'{{$consentForm->guardianName}}', decoration: 'underline', decorationStyle: 'solid', decorationColor: 'black'}, ' beralamat ', { text: `{!!$consentForm->address!!}`, decoration: 'underline', decorationStyle: 'solid', decorationColor: 'black'}, ' dengan ini memberi keizinan',
                                            { text: ' untuk menjalani prosedur ', bold: true }, { text: '{{$consentForm->procedureName}}', decoration: 'underline', decorationStyle: 'solid', decorationColor: 'black' },
                                            { text: ' menyerahkan ', bold: true }, { text: '{{$consentForm->guardianType}}', decoration: 'underline', decorationStyle: 'solid', decorationColor: 'black' },' saya ', { text: '{{$consentForm->patientName}}.', decoration: 'underline', decorationStyle: 'solid', decorationColor: 'black' },'\n\nUntuk menjalani prosedur radiologi ', { text: '{{$consentForm->procedureRadName}}.', decoration: 'underline', decorationStyle: 'solid', decorationColor: 'black' },'\n\nYang keadaan dan tujuan telah diterangkan kepada saya oleh Dr.', { text: '{{$consentForm->doctorName}}.\n\n', decoration: 'underline', decorationStyle: 'solid', decorationColor: 'black' },
                                        ], colSpan: 2, alignment:'justify'
                                    },{}
                                ],
                                [
                                    { text: 'Tarikh : {{\Carbon\Carbon::createFromFormat('Y-m-d',$consentForm->dateConsentGuardian)->format('d-m-Y')}}'},
                                    { text: 'Ditandatangani : {{$consentForm->guardianSign}}\n\n{{$consentForm->guardianSignType}}\n\nTali Persaudaraan : {{$consentForm->relationship}}\n\nNo. Kad Pengenalan : {{$consentForm->guardianICNum}}', alignment:'right'},
                                ],
                                [
                                    { 
                                        text: '\n\nPeringatan: Jika seseorang itu memberi keizinan sebagai penjaga, hendaklah tali persaudaraan itu dijelaskan di bawah tandatangannya.\n\nSaya mengaku bahawa saya telah menerangkan keadaan dan tujuan pembedahan ini kepada {{$consentForm->guardianSignTypeDoc}}.', colSpan: 2, alignment:'justify'
                                    },{}
                                ],
                                [
                                    { text: '\n\nTarikh : {{\Carbon\Carbon::createFromFormat('Y-m-d',$consentForm->dateConsentDoc)->format('d-m-Y')}}'},
                                    { text: '\n\nDitandatangani : {{$consentForm->doctorSign}}\n\n\n\n\n(Pengamal Perubatan)', alignment:'right'},
                                ],
                                [
                                    { 
                                        text: 'Sebarang pemotongan dan tambahan atau pindahan kepada borang ini hendaklah dibuat sebelum penerangan itu diberi dan borang itu dikemukakan untuk ditandatangani.',colSpan: 2, italics:'true', alignment:'justify'
                                    },{}
                                ],
                            ]
                        },
                        layout: 'noBorders',
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
                        fontSize: 10,
                        margin: [0, 5, 0, 0]
                    },
                    tableExample2: {
                        fontSize: 8,
                        margin: [0, 0, 0, 0]
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
                        url: "{{asset('/img/logo/IMSCletterhead.png')}}",
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
            //     // document.getElementById('pdfPreview').data = base64data;
            // });
            
            pdfMake.createPdf(docDefinition).getDataUrl(function (dataURL){
                $('#pdfiframe').attr('src',dataURL);
            });
        });
        
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