<!DOCTYPE html>
<html>
    <head>
        <title>6-Minute Walking Test</title>
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
                        image: 'letterhead', width: 500, style: 'tableHeader', colSpan: 5, alignment: 'center'
                    },
                    {
                        text: '\n6-MINUTE WALKING TEST\n',
                        style: 'header',
                        alignment: 'center',
                    },
                    // { canvas: [ { type: 'line', x1: 0, y1: 5, x2: 515, y2: 5, lineWidth: 0.5 } ] },
                    {
                        text: 'The following elements should be present on the 6MWT worksheet and report:\n',
                        style: 'pagecontent',
                    },
                    {
                        text: [
                            { text: 'Lap counter:' },
                            { text: '\u200B\t{{$sixminwalk->lapCounter}}' },
                        ],
                        style: 'pagecontent',
                    },
                    {
                        text: [
                            { text: 'Patient name:' },
                            { text: '\u200B\t{{$sixminwalk->Name}}' },
                            { text: '\u200B\t\u200B\t\u200B\t\u200B\t MRN:' },
                            { text: '\u200B\t{{str_pad($sixminwalk->mrn, 7, "0", STR_PAD_LEFT)}}' },
                        ],
                        style: 'pagecontent',
                    },
                    {
                        text: [
                            { text: 'Date:' },
                            { text: '\u200B\t{{\Carbon\Carbon::createFromFormat('Y-m-d',$sixminwalk->entereddate)->format('d-m-Y')}}' },
                        ],
                        style: 'pagecontent',
                    },
                    {
                        text: [
                            { text: 'Gender:' },
                            { text: '\u200B\t{{$sixminwalk->Sex}}' },
                            { text: '\u200B\t\u200B\t\u200B\t\u200B\t Age:' },
                            { text: '\u200B\t{{$age}}' },
                            { text: '\u200B\t\u200B\t\u200B\t\u200B\t Race:' },
                            { text: '\u200B\t{{$sixminwalk->raceDesc}}' },
                            { text: '\u200B\t\u200B\t\u200B\t\u200B\t Height:' },
                            { text: '\u200B\t{{$sixminwalk->heightCM}} cm' },
                        ],
                        style: 'pagecontent',
                    },
                    {
                        text: [
                            { text: 'Weight:' },
                            { text: '\u200B\t{{$sixminwalk->weightKG}} kg' },
                            { text: '\u200B\t\u200B\t\u200B\t\u200B\t Blood pressure:' },
                            { text: '\u200B\t{{$sixminwalk->bpsys1}} / {{$sixminwalk->bpdias2}} mmHg' },
                        ],
                        style: 'pagecontent',
                    },
                    {
                        style: 'tableExample',
                        table: {
                            widths: [50,100,100],
                            // headerRows: 5,
                            // keepWithHeaderRows: 5,
                            body: [
                                [
                                    { text: ' ', style: 'tableHeader', alignment: 'center' },
                                    { text: 'Baseline', style: 'tableHeader', alignment: 'center' },
                                    { text: 'End of Test', style: 'tableHeader', alignment: 'center' },
                                ],
                                [
                                    { text: 'Time', style: 'tableHeader' },
                                    { text: '{{\Carbon\Carbon::createFromFormat('H:i:s',$sixminwalk->baselineTime)->format('h:i A')}}', alignment: 'center' },
                                    { text: '{{\Carbon\Carbon::createFromFormat('H:i:s',$sixminwalk->endTestTime)->format('h:i A')}}', alignment: 'center' },
                                ],
                                [
                                    { text: 'Heart Rate', style: 'tableHeader' },
                                    { text: '{{$sixminwalk->baselineHR}}', alignment: 'center' },
                                    { text: '{{$sixminwalk->endTestHR}}', alignment: 'center' },
                                ],
                                [
                                    { text: 'Borg Scale', style: 'tableHeader' },
                                    { text: '{{$sixminwalk->baselineBorgScale}}', alignment: 'center' },
                                    { text: '{{$sixminwalk->endTestBorgScale}}', alignment: 'center' },
                                ],
                                [
                                    { text: 'SpO2', style: 'tableHeader' },
                                    { text: '{{$sixminwalk->baselineSpO2}} %', alignment: 'center' },
                                    { text: '{{$sixminwalk->endTestSpO2}} %', alignment: 'center' },
                                ],
                            ]
                        },
                        layout: 'noBorders'
                    },
                    {
                        text: [
                            { text: 'Stopped or paused before 6 minutes?:' },
                            @if($sixminwalk->stopPaused == '0')
                                { text: '\u200B\t[ √ ] No \u200B\t [\u200B\t] Yes,' },
                            @elseif($sixminwalk->stopPaused == '1')
                                { text: '\u200B\t[\u200B\t] No \u200B\t [ √ ] Yes,' },
                            @else
                                { text: '\u200B\t[\u200B\t] No \u200B\t [\u200B\t] Yes,' },
                            @endif
                            { text: '\u200B\t reason:' },
                            { text: '\u200B\t{{$sixminwalk->reason}}' },
                        ],
                        style: 'pagecontent',
                    },
                    {
                        text: [
                            { text: 'Other symptoms at end of exercise:' },
                            @if($sixminwalk->othSymptoms == 'angina')
                                { text: '\u200B\t[ √ ] angina \u200B\t [\u200B\t] dizziness \u200B\t [\u200B\t] hip, leg, or calf pain' },
                            @elseif($sixminwalk->othSymptoms == 'dizziness')
                                { text: '\u200B\t[\u200B\t] angina \u200B\t [ √ ] dizziness \u200B\t [\u200B\t] hip, leg, or calf pain' },
                            @elseif($sixminwalk->othSymptoms == 'hipLegCalfPain')
                                { text: '\u200B\t[\u200B\t] angina \u200B\t [\u200B\t] dizziness \u200B\t [ √ ] hip, leg, or calf pain' },
                            @else
                                { text: '\u200B\t[\u200B\t] angina \u200B\t [\u200B\t] dizziness \u200B\t [\u200B\t] hip, leg, or calf pain' },
                            @endif
                        ],
                        style: 'pagecontent',
                    },
                    {
                        text: [
                            { text: 'Total distance walked in 6 minutes:' },
                            { text: '\u200B\t{{$sixminwalk->totDistance}} meters' },
                        ],
                        style: 'pagecontent',
                    },
                    {
                        text: 'Tech comments:',
                        style: 'pagecontent',
                    },
                    {
                        text: [
                            { text: '\u200B\t Interpretation (including comparison with a preintervention 6MWD):' },
                        ],
                        style: 'pagecontent',
                    },
                    {
                        text: [
                            { text: `{!!str_replace('`', '', $sixminwalk->comments)!!}` },
                        ],
                        style: 'pagecontent1',
                    },
                ],
                styles: {
                    header: {
                        fontSize: 14,
                        bold: true,
                        margin: [0, 0, 0, 10]
                    },
                    subheader: {
                        fontSize: 12,
                        bold: true,
                        margin: [0, 10, 0, 5]
                    },
                    tableExample: {
                        fontSize: 8,
                        margin: [60, 10, 0, 10]
                    },
                    tableHeader: {
                        bold: true,
                        fontSize: 9,
                        color: 'black'
                    },
                    pagecontent: {
                        fontSize: 9,
                        margin: [20, 6, 10, 0],
                    },
                    pagecontent1: {
                        fontSize: 9,
                        margin: [31, 6, 10, 0],
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
            //     document.getElementById('pdfPreview').data = base64data;
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