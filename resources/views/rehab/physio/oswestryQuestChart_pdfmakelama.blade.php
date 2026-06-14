<!DOCTYPE html>
<html>
    <head>
        <title>Oswestry Low Back Disability Questionnaire</title>
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
                        text: 'OSWESTRY LOW BACK DISABILITY QUESTIONNAIRE\n',
                        style: 'header',
                        alignment: 'center',
                    },
                    // { canvas: [ { type: 'line', x1: 0, y1: 5, x2: 515, y2: 5, lineWidth: 0.5 } ] },
                    {
                        text: [
                            { text: 'Instructions: this questionnaire has been designed to give us information as to how your back pain has affected your ability to manage everyday life. Please answer every section and mark in each section only the ONE box which applies to you at this time. We realize you may consider 2 of the statements in any section may relate to you, but ' },
                            { text: 'please mark the box which most closely describes your current condition.\n\n', decoration: 'underline' },
                        ], alignment: 'center', fontSize: 9
                    },
                    {
                        style: 'tableExample',
                        table: {
                            widths: [20,220,20,220],
                            // headerRows: 5,
                            // keepWithHeaderRows: 5,
                            body: [
                                [
                                    { text: '1.', bold: true },
                                    { text: 'PAIN INTENSITY', bold: true },
                                    { text: '6.', bold: true },
                                    { text: 'STANDING', bold: true },
                                ],
                                [
                                    @if($oswestryquest->painIntensity == '0')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'I can tolerate the pain I have without having to use pain killers.' },
                                    @if($oswestryquest->standing == '0')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'I can stand as long as I want without extra pain.' },
                                ],
                                [
                                    @if($oswestryquest->painIntensity == '1')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'The pain is bad but I manage without taking pain killers.' },
                                    @if($oswestryquest->standing == '1')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'I can stand as long as I want but it gives me extra pain.' },
                                ],
                                [
                                    @if($oswestryquest->painIntensity == '2')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'Pain killers give complete relief from pain.' },
                                    @if($oswestryquest->standing == '2')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'Pain prevents me from standing for more than one hour.' },
                                ],
                                [
                                    @if($oswestryquest->painIntensity == '3')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'Pain killers give moderate relief from pain.' },
                                    @if($oswestryquest->standing == '3')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'Pain prevents me from standing for more than 30 minutes.' },
                                ],
                                [
                                    @if($oswestryquest->painIntensity == '4')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'Pain killers give very little relief from pain.' },
                                    @if($oswestryquest->standing == '4')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'Pain prevents me from standing for more than 10 minutes.' },
                                ],
                                [
                                    @if($oswestryquest->painIntensity == '5')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'Pain killers have no effect on the pain and I do not use them.' },
                                    @if($oswestryquest->standing == '5')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'Pain prevents me from standing at all.' },
                                ],
                                [
                                    { text: '\n 2.', bold: true },
                                    { text: '\n PERSONAL CARE (e.g. Washing, Dressing)', bold: true },
                                    { text: '\n 7.', bold: true },
                                    { text: '\n SLEEPING', bold: true },
                                ],
                                [
                                    @if($oswestryquest->personalCare == '0')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'I can look after myself normally without causing extra pain.' },
                                    @if($oswestryquest->sleeping == '0')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'Pain does not prevent me from sleeping well.' },
                                ],
                                [
                                    @if($oswestryquest->personalCare == '1')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'I can look after myself normally but it causes extra pain.' },
                                    @if($oswestryquest->sleeping == '1')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'I can sleep well only by using medication.' },
                                ],
                                [
                                    @if($oswestryquest->personalCare == '2')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'It is painful to look after myself and I am slow and careful.' },
                                    @if($oswestryquest->sleeping == '2')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'Even when I take medication, I have less than 6 hrs sleep.' },
                                ],
                                [
                                    @if($oswestryquest->personalCare == '3')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'I need some help but manage most of my personal care.' },
                                    @if($oswestryquest->sleeping == '3')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'Even when I take medication, I have less than 4 hrs sleep.' },
                                ],
                                [
                                    @if($oswestryquest->personalCare == '4')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'I need help every day in most aspects of self care.' },
                                    @if($oswestryquest->sleeping == '4')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'Even when I take medication, I have less than 2 hrs sleep.' },
                                ],
                                [
                                    @if($oswestryquest->personalCare == '5')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'I dont get dressed, I was with difficulty and stay in bed.' },
                                    @if($oswestryquest->sleeping == '5')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'Pain prevents me from sleeping at all.' },
                                ],
                                [
                                    { text: '\n 3.', bold: true },
                                    { text: '\n LIFTING', bold: true },
                                    { text: '\n 8.', bold: true },
                                    { text: '\n SOCIAL LIFE', bold: true },
                                ],
                                [
                                    @if($oswestryquest->lifting == '0')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'I can lift heavy weights without extra pain.' },
                                    @if($oswestryquest->socialLife == '0')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'My social life is normal and gives me no extra pain.' },
                                ],
                                [
                                    @if($oswestryquest->lifting == '1')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'I can lift heavy weights but it gives extra pain.' },
                                    @if($oswestryquest->socialLife == '1')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'My social life is normal but increases the degree of pain.' },
                                ],
                                [
                                    @if($oswestryquest->lifting == '2')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'Pain prevents me from lifting heavy weights off the floor, but I can manage if they are conveniently positioned, i.e. on a table.' },
                                    @if($oswestryquest->socialLife == '2')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'Pain has no significant effect on my social life apart from limiting my more energetic interests, i.e. dancing, etc.' },
                                ],
                                [
                                    @if($oswestryquest->lifting == '3')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'Pain prevents me from lifting heavy weights, but I can manage light to medium weights if they are conveniently positioned.' },
                                    @if($oswestryquest->socialLife == '3')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'Pain has restricted my social life and I do not go out as often.' },
                                ],
                                [
                                    @if($oswestryquest->lifting == '4')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'I can lift very light weights.' },
                                    @if($oswestryquest->socialLife == '4')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'Pain has restricted my social life to my home.' },
                                ],
                                [
                                    @if($oswestryquest->lifting == '5')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'I cannot lift or carry anything at all.' },
                                    @if($oswestryquest->socialLife == '5')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'I have no social life because of pain.' },
                                ],
                                [
                                    { text: '\n 4.', bold: true },
                                    { text: '\n WALKING', bold: true },
                                    { text: '\n 9.', bold: true },
                                    { text: '\n TRAVELLING', bold: true },
                                ],
                                [
                                    @if($oswestryquest->walking == '0')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'Pain does not prevent me walking any distance.' },
                                    @if($oswestryquest->travelling == '0')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'I can travel anywhere without extra pain.' },
                                ],
                                [
                                    @if($oswestryquest->walking == '1')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'Pain prevents me walking more than one mile.' },
                                    @if($oswestryquest->travelling == '1')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'I can travel anywhere but it gives me extra pain.' },
                                ],
                                [
                                    @if($oswestryquest->walking == '2')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'Pain prevents me walking more than 1/2 mile.' },
                                    @if($oswestryquest->travelling == '2')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'Pain is bad, but I manage journeys over 2 hours.' },
                                ],
                                [
                                    @if($oswestryquest->walking == '3')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'Pain prevents me walking more than 1/4 mile.' },
                                    @if($oswestryquest->travelling == '3')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'Pain restricts me to journeys of less than 1 hour.' },
                                ],
                                [
                                    @if($oswestryquest->walking == '4')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'I can only walk using a stick or crutches.' },
                                    @if($oswestryquest->travelling == '4')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'Pain restricts me to short necessary journeys under 30 minutes.' },
                                ],
                                [
                                    @if($oswestryquest->walking == '5')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'I am in bed most of the time and have to crawl to the toilet.' },
                                    @if($oswestryquest->travelling == '5')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'Pain prevents me from travelling except to the doctor or hospital.' },
                                ],
                                [
                                    { text: '\n 5.', bold: true },
                                    { text: '\n SITTING', bold: true },
                                    { text: '\n 10.', bold: true },
                                    { text: '\n EMPLOYMENT / HOMEMAKING', bold: true },
                                ],
                                [
                                    @if($oswestryquest->sitting == '0')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'I can sit in any chair as long as I like.' },
                                    @if($oswestryquest->employHomemaking == '0')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'My normal homemaking / job activities do not cause pain.' },
                                ],
                                [
                                    @if($oswestryquest->sitting == '1')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'I can only sit in my favorite chair as long as I like.' },
                                    @if($oswestryquest->employHomemaking == '1')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'My normal homemaking / job activities increase my pain, but I can still perform all that is required of me.' },
                                ],
                                [
                                    @if($oswestryquest->sitting == '2')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'Pain prevents me from sitting more than one hour.' },
                                    @if($oswestryquest->employHomemaking == '2')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'I can perform most of my homemaking / job duties, but pain prevents me from performing more physically stressful activities (e.g. lifting, vacuuming).' },
                                ],
                                [
                                    @if($oswestryquest->sitting == '3')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'Pain prevents me from sitting more than 1/2 hour.' },
                                    @if($oswestryquest->employHomemaking == '3')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'Pain prevents me from doing anything but light duties.' },
                                ],
                                [
                                    @if($oswestryquest->sitting == '4')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'Pain prevents me from sitting more than 10 minutes.' },
                                    @if($oswestryquest->employHomemaking == '4')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'Pain prevents me from doing even light duties.' },
                                ],
                                [
                                    @if($oswestryquest->sitting == '5')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'Pain prevents me from sitting at all.' },
                                    @if($oswestryquest->employHomemaking == '5')
                                        { text: '[ √ ]' },
                                    @else
                                        { text: '[\u200B\t]' },
                                    @endif
                                    { text: 'Pain prevents me from performing any job or homemaking chores.' },
                                ],
                            ]
                        },
                        layout: 'noBorders'
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
                        margin: [0, 5, 0, 0]
                    },
                    tableHeader: {
                        bold: true,
                        fontSize: 9,
                        color: 'black'
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
        <iframe id="pdfiframe" width="100%" height="100%" src="" frameborder="0" style="width: 99vw; height: 99vh;"></iframe>
    </body>
</html>