<!DOCTYPE html>
<html>
    <head>
        <title>The Mini-Mental State Exam</title>
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
                        text: 'THE MINI-MENTAL STATE EXAM',
                        style: 'header',
                        alignment: 'center',
                    },
                    {
                        style: 'tableExample',
                        table: {
                            // headerRows: 1,
                            widths: [70,3,'*',60,3,'*'], // panjang standard dia 515
                            body: [
                                [
                                    { text: 'Name' },
                                    { text: ':' },
                                    { text: `{!!$mmse->Name!!}`,},
                                    { text: 'MRN' },
                                    { text: ':' },
                                    { text: '{{str_pad($mmse->mrn, 7, "0", STR_PAD_LEFT)}}' },
                                ],
                                [
                                    { text: 'Examiner' },
                                    { text: ':' },
                                    { text: '{{$mmse->examiner}}' },
                                    { text: 'Date' },
                                    { text: ':' },
                                    { text: '{{\Carbon\Carbon::createFromFormat('Y-m-d',$mmse->dateofexam)->format('d-m-Y')}}' },
                                ],
                            ]
                            
                        },
                        layout: 'noBorders',
                    },
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: [50,30,'*'], // panjang standard dia 515
                            body: [
                                [
                                    { text: 'Maximum', style: 'tableHeader', alignment: 'center' },
                                    { text: 'Score', style: 'tableHeader', alignment: 'center' },
                                    {}
                                ],
                                [
                                    {},{},
                                    {text: 'Orientation', bold:true}
                                ],
                                [
                                    { text: '5', alignment: 'center' },
                                    { text: '({{$mmse->orientation1}})', alignment: 'center' },
                                    { text: 'What is the (year) (season) (date) (day) (month)?' },
                                ],
                                [
                                    { text: '5', alignment: 'center' },
                                    { text: '({{$mmse->orientation2}})', alignment: 'center' },
                                    { text: 'Where are we (state) (country) (town) (hospital) (floor)?' },
                                ],
                                [
                                    {},{},
                                    {text: 'Registration', bold:true}
                                ],
                                [
                                    { text: '3', alignment: 'center' },
                                    { text: '({{$mmse->registration}})', alignment: 'center' },
                                    { text: 'Name 3 objects. 1 second to say each. Then ask the patient all 3 after you have said them. Give 1 point for each correct answer. Then repeat them until he/she learns all 3. Count trials and record.\nTrials : {{$mmse->registrationTrials}} ' },
                                ],
                                [
                                    {},{},
                                    {text: 'Attention and Calculation', bold:true}
                                ],
                                [
                                    { text: '5', alignment: 'center' },
                                    { text: '({{$mmse->attnCalc}})', alignment: 'center' },
                                    { text: `Serial 7's. 1 point for each correct answer. Stop after 5 answers. Alternatively spell "world" backward.` },
                                ],
                                [
                                    {},{},
                                    {text: 'Recall', bold:true}
                                ],
                                [
                                    { text: '3', alignment: 'center' },
                                    { text: '({{$mmse->recall}})', alignment: 'center' },
                                    { text: 'Ask for the 3 objects repeated above. Give 1 point for each correct answer.' },
                                ],
                                [
                                    {},{},
                                    {text: 'Language', bold:true}
                                ],
                                [
                                    { text: '2', alignment: 'center' },
                                    { text: '({{$mmse->language1}})', alignment: 'center' },
                                    { text: 'Name a pencil and watch.' },
                                ],
                                [
                                    { text: '1', alignment: 'center' },
                                    { text: '({{$mmse->language2}})', alignment: 'center' },
                                    { text: 'Repeat the following "No ifs, ands, or buts"' },
                                ],
                                [
                                    { text: '3', alignment: 'center' },
                                    { text: '({{$mmse->language3}})', alignment: 'center' },
                                    { text: 'Follow a 3-stage command:\n"Take a paper in your hand, fold it in a half, and put it on the floor."' },
                                ],
                                [
                                    { text: '1', alignment: 'center' },
                                    { text: '({{$mmse->language4}})', alignment: 'center' },
                                    { text: 'Read and obey the following: CLOSE YOUR EYES' },
                                ],
                                [
                                    { text: '1', alignment: 'center' },
                                    { text: '({{$mmse->language5}})', alignment: 'center' },
                                    { text: 'Write a sentence.' },
                                ],
                                [
                                    { text: '1', alignment: 'center' },
                                    { text: '({{$mmse->language6}})', alignment: 'center' },
                                    { text: 'Copy the design shown.' }
                                ],
                                [
                                    {},{},
                                    {image: 'mmse', width: 150},
                                ],
                                [
                                    {},
                                    { text: '{{$mmse->tot_mmse}}', alignment: 'center' },
                                    { text: 'Total Score\n ASSESS level of consciousness along a continuum (Alert, Drowsy, Stupor, Coma) - {{$mmse->assess_lvl}} ' }
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
                        fontSize: 9,
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
                    },
                    mmse: {
                        url: "{{asset('/img/mmse.jpg')}}",
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