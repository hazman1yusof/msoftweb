<!DOCTYPE html>
<html>
    <head>
        <title>Referral Letter</title>
    </head>
    
    <!-- <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha512-a9NgEEK7tsCvABL7KqtUTQjl69z7091EVPpw5KxPlZ93T141ffe1woLtbXTX+r2/8TtTvRX/v4zTL2UlMUPgwg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.min.js" integrity="sha512-P0bOMePRS378NwmPDVPU455C/TuxDS+8QwJozdc7PGgN8kLqR4ems0U/3DeJkmiE31749vYWHvBOtR+37qDCZQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    </object>
    
    <script>
        var mydata = {
            docname:`{!!$ini_array['docname']!!}`,
            name:`{!!str_replace("`", '', $ini_array['name'])!!}`,
            newic:`{!!$ini_array['newic']!!}`,
            reftitle:`{!!$ini_array['reftitle']!!}`,
            reffor:`{!!$ini_array['reffor']!!}`,
            exam:`{!!$ini_array['exam']!!}`,
            invest:`{!!$ini_array['invest']!!}`,
            refdate:`{!!$ini_array['refdate']!!}`
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
                    //     text: ['Date : ',{text: mydata.refdate+'\n', style: 'nobold'}],
                    //     style: 'date'
                    // },
                    // {
                    //     text: ['To : ',{text: 'Dr. '+mydata.docname+'\n\n', style: 'to'}],
                    //     style: 'totalbold'
                    // },
                    {
                        image: 'letterhead', width: 430, style: 'tableHeader', colSpan: 5, alignment: 'center'
                    },
                    {
                      style:'date',
                      columns: [
                            { width: '15%', text: 'Date', bold: true },
                            { width: '85%', text: ': '+mydata.refdate, bold: false }
                        ],
                    },
                    {
                      columns: [
                            { width: '15%', text: 'To', bold: true },
                            { width: '85%', text: ': Dr. '+mydata.docname, bold: false }
                        ],
                    },
                    {
                      columns: [
                            { width: '15%', text: 'Patient Name', bold: true },
                            { width: '85%', text: ': '+mydata.name+'\n', bold: false }
                        ],
                    },
                    {
                      columns: [
                            { width: '15%', text: 'I/C No.', bold: true },
                            { width: '85%', text: ': '+mydata.newic+'\n\n', bold: false }
                        ],
                    },
                    'Dear Dr,',
                    {
                        text: [ mydata.reftitle ],
                        style: 'text'
                    },
                    // {
                    //     text: ['Patient Name : ',{text: mydata.name+'\n', style: 'patname'}],
                    //     style: 'totalbold'
                    // },
                    // {
                    //     text: ['I/C No : ',{text: mydata.newic+'\n\n', style: 'newic'}],
                    //     style: 'totalbold'
                    // },
                    {
                        text: [ 'The Above is referred for' ],
                        style: 'totalbold'
                    },
                    {
                        text: [ mydata.reffor ],
                        style: 'text'
                    },
                    {
                        text: [ 'Physical examination' ],
                        style: 'totalbold'
                    },
                    {
                        text: [ mydata.exam ],
                        style: 'text'
                    },
                    {
                        text: [ 'Investigation' ],
                        style: 'totalbold'
                    },
                    {
                        text: [ mydata.invest ],
                        style: 'text'
                    },
                    'Thank you,\n\n',
                    'Yours Faithfully,\n\n\n\n\n',
                    '____________________________________\n',
                    'Dr',
                ],
                styles: {
                    date: {
                        margin: [0, 20, 0, 0],
                    },
                    to: {
                        margin: [0, 0, 0, 0],
                        bold: false
                    },
                    patname: {
                        margin: [0, 0, 0, 0],
                        bold: false
                    },
                    newic: {
                        margin: [0, 0, 0, 0],
                        bold: false
                    },
                    text: {
                        margin: [0, 2, 0, 15],
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
                        bold: true
                    },
                    nobold:{
                        bold: false
                    },
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
        //         data:mydata
        //     });
        //     document.getElementById('pdfPreview').data = await report.toObjectURL()
        // }
        
        // preview().catch(console.error)
    </script>
    
    <body style="margin: 0px;">
        <iframe id="pdfiframe" width="100%" height="100%" src="" frameborder="0" style="width: 99vw;height: 99vh;"></iframe>
    </body>
</html>