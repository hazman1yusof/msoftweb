<!DOCTYPE html>
<html>
    <head>
        <title>Postural Assessment</title>
    </head>
    
    <!-- <script src="https://unpkg.com/@jsreport/browser-client/dist/jsreport.umd.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="mydata.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha512-a9NgEEK7tsCvABL7KqtUTQjl69z7091EVPpw5KxPlZ93T141ffe1woLtbXTX+r2/8TtTvRX/v4zTL2UlMUPgwg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.min.js" integrity="sha512-P0bOMePRS378NwmPDVPU455C/TuxDS+8QwJozdc7PGgN8kLqR4ems0U/3DeJkmiE31749vYWHvBOtR+37qDCZQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/numeral@2.0.6/numeral.min.js"></script>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fomantic-ui@2.9.2/dist/semantic.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/fomantic-ui@2.9.2/dist/semantic.min.js"></script>
    
    </object>
    
    <script>
        var merge_key = makeid(20);
        var base64_pr = null;
        
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
                        text: 'POSTURAL ASSESSMENT\n',
                        style: 'header',
                        alignment: 'center',
                    },
                    // { canvas: [ { type: 'line', x1: 0, y1: 5, x2: 515, y2: 5, lineWidth: 0.5 } ] },
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: ['*','*'], // panjang standard dia 515
                            body: [
                                [
                                    { text: `Name : \u200B\t{!!$posturalassessment->Name!!}` },
                                    { text: 'Date : \u200B\t{{\Carbon\Carbon::createFromFormat('Y-m-d',$posturalassessment->entereddate)->format('d-m-Y')}}' },
                                ],
                            ]
                        },
                        layout: 'noBorders',
                    },
                    {
                        alignment: 'justify',
                        columns: [
                            {
                                // Left table
                                width: 290, // Or a fixed width like 200
                                style: 'tableLeft',
                                table: {
                                    widths: [90,110,20,20],
                                    // headerRows: 1,
                                    body: [
                                        [
                                            { text: 'Anterior & Posterior View', style: 'tableHeader', alignment: 'center', colSpan: 4 },
                                            {},{},{},
                                        ],
                                        [
                                            { text: 'Tick where seen & refer to Movement Management Plan', alignment: 'right', italics: true, colSpan: 4 },
                                            {},{},{},
                                        ],
                                        [
                                            { text: 'Lower Body', bold: true, fontSize: 10 },
                                            { text: ' ' },
                                            { text: 'L', alignment: 'center' },
                                            { text: 'R', alignment: 'center' },
                                        ],
                                        [
                                            { text: 'Foot & ankle complex' },
                                            { text: 'Toe - Out' },
                                            @if($posturalassessment->FACToeOutL == '1')
                                                { text: '[ √ ]', alignment: 'center' },
                                            @else
                                                { text: '[\u200B\t]', alignment: 'center' },
                                            @endif
                                            @if($posturalassessment->FACToeOutR == '1')
                                                { text: '[ √ ]', alignment: 'center' },
                                            @else
                                                { text: '[\u200B\t]', alignment: 'center' },
                                            @endif
                                        ],
                                        [
                                            { text: ' ' },
                                            { text: 'Toe - In' },
                                            @if($posturalassessment->FACToeInL == '1')
                                                { text: '[ √ ]', alignment: 'center' },
                                            @else
                                                { text: '[\u200B\t]', alignment: 'center' },
                                            @endif
                                            @if($posturalassessment->FACToeInR == '1')
                                                { text: '[ √ ]', alignment: 'center' },
                                            @else
                                                { text: '[\u200B\t]', alignment: 'center' },
                                            @endif
                                        ],
                                        [
                                            { text: ' ' },
                                            { text: 'Pronation' },
                                            @if($posturalassessment->FACPronationL == '1')
                                                { text: '[ √ ]', alignment: 'center' },
                                            @else
                                                { text: '[\u200B\t]', alignment: 'center' },
                                            @endif
                                            @if($posturalassessment->FACPronationR == '1')
                                                { text: '[ √ ]', alignment: 'center' },
                                            @else
                                                { text: '[\u200B\t]', alignment: 'center' },
                                            @endif
                                        ],
                                        [
                                            { text: ' ' },
                                            { text: 'Flat Feet' },
                                            @if($posturalassessment->FACFlatFeetL == '1')
                                                { text: '[ √ ]', alignment: 'center' },
                                            @else
                                                { text: '[\u200B\t]', alignment: 'center' },
                                            @endif
                                            @if($posturalassessment->FACFlatFeetR == '1')
                                                { text: '[ √ ]', alignment: 'center' },
                                            @else
                                                { text: '[\u200B\t]', alignment: 'center' },
                                            @endif
                                        ],
                                        [
                                            { text: ' ' },
                                            { text: 'High Arch' },
                                            @if($posturalassessment->FACHighArchL == '1')
                                                { text: '[ √ ]', alignment: 'center' },
                                            @else
                                                { text: '[\u200B\t]', alignment: 'center' },
                                            @endif
                                            @if($posturalassessment->FACHighArchR == '1')
                                                { text: '[ √ ]', alignment: 'center' },
                                            @else
                                                { text: '[\u200B\t]', alignment: 'center' },
                                            @endif
                                        ],
                                        [
                                            { text: 'Knee/Hip' },
                                            { text: 'Knock Knees' },
                                            @if($posturalassessment->KHKnockKneesL == '1')
                                                { text: '[ √ ]', alignment: 'center' },
                                            @else
                                                { text: '[\u200B\t]', alignment: 'center' },
                                            @endif
                                            @if($posturalassessment->KHKnockKneesR == '1')
                                                { text: '[ √ ]', alignment: 'center' },
                                            @else
                                                { text: '[\u200B\t]', alignment: 'center' },
                                            @endif
                                        ],
                                        [
                                            { text: ' ' },
                                            { text: 'Bow Legs' },
                                            @if($posturalassessment->KHBowLegsL == '1')
                                                { text: '[ √ ]', alignment: 'center' },
                                            @else
                                                { text: '[\u200B\t]', alignment: 'center' },
                                            @endif
                                            @if($posturalassessment->KHBowLegsR == '1')
                                                { text: '[ √ ]', alignment: 'center' },
                                            @else
                                                { text: '[\u200B\t]', alignment: 'center' },
                                            @endif
                                        ],
                                        [
                                            { text: 'Upper Body', bold: true, fontSize: 10 },
                                            { text: ' ' },
                                            { text: 'L', alignment: 'center' },
                                            { text: 'R', alignment: 'center' },
                                        ],
                                        [
                                            { text: 'Spine' },
                                            { text: 'Scoliosis' },
                                            @if($posturalassessment->spineScoliosisL == '1')
                                                { text: '[ √ ]', alignment: 'center' },
                                            @else
                                                { text: '[\u200B\t]', alignment: 'center' },
                                            @endif
                                            @if($posturalassessment->spineScoliosisR == '1')
                                                { text: '[ √ ]', alignment: 'center' },
                                            @else
                                                { text: '[\u200B\t]', alignment: 'center' },
                                            @endif
                                        ],
                                        [
                                            { text: 'Scapula' },
                                            { text: 'Deviation' },
                                            @if($posturalassessment->scapulaDeviationL == '1')
                                                { text: '[ √ ]', alignment: 'center' },
                                            @else
                                                { text: '[\u200B\t]', alignment: 'center' },
                                            @endif
                                            @if($posturalassessment->scapulaDeviationR == '1')
                                                { text: '[ √ ]', alignment: 'center' },
                                            @else
                                                { text: '[\u200B\t]', alignment: 'center' },
                                            @endif
                                        ],
                                        [
                                            { text: 'Shoulder' },
                                            { text: 'Deviation' },
                                            @if($posturalassessment->shoulderDeviationL == '1')
                                                { text: '[ √ ]', alignment: 'center' },
                                            @else
                                                { text: '[\u200B\t]', alignment: 'center' },
                                            @endif
                                            @if($posturalassessment->shoulderDeviationR == '1')
                                                { text: '[ √ ]', alignment: 'center' },
                                            @else
                                                { text: '[\u200B\t]', alignment: 'center' },
                                            @endif
                                        ],
                                        [
                                            { text: 'Head' },
                                            { text: 'Tilt' },
                                            @if($posturalassessment->headTiltL == '1')
                                                { text: '[ √ ]', alignment: 'center' },
                                            @else
                                                { text: '[\u200B\t]', alignment: 'center' },
                                            @endif
                                            @if($posturalassessment->headTiltR == '1')
                                                { text: '[ √ ]', alignment: 'center' },
                                            @else
                                                { text: '[\u200B\t]', alignment: 'center' },
                                            @endif
                                        ],
                                        [
                                            { text: ' ' },
                                            { text: 'Rotation' },
                                            @if($posturalassessment->headRotateL == '1')
                                                { text: '[ √ ]', alignment: 'center' },
                                            @else
                                                { text: '[\u200B\t]', alignment: 'center' },
                                            @endif
                                            @if($posturalassessment->headRotateR == '1')
                                                { text: '[ √ ]', alignment: 'center' },
                                            @else
                                                { text: '[\u200B\t]', alignment: 'center' },
                                            @endif
                                        ],
                                    ]
                                },
                                layout: 'noBorders'
                            },
                            {
                                // Right table
                                width: 300, // Takes remaining space
                                style: 'tableRight',
                                table: {
                                    widths: [220],
                                    // headerRows: 1,
                                    body: [
                                        [
                                            {
                                                text: [
                                                    { text: 'Comments:', bold: true },
                                                    { text: `\n\n{!!$posturalassessment->anteriorPosteriorRmk!!}` },
                                                ],
                                            },
                                        ],
                                    ]
                                },
                                // layout: 'noBorders'
                            }
                        ]
                    },
                    {
                        alignment: 'justify',
                        columns: [
                            {
                                // Left table
                                width: 290, // Or a fixed width like 200
                                style: 'tableLeftBtm',
                                table: {
                                    widths: [90,110,20,20],
                                    // headerRows: 1,
                                    body: [
                                        [
                                            { text: 'Lateral View', style: 'tableHeader', alignment: 'center', colSpan: 4 },
                                            {},{},{},
                                        ],
                                        [
                                            { text: 'Tick where seen & refer to Movement Management Plan', alignment: 'right', italics: true, colSpan: 4 },
                                            {},{},{},
                                        ],
                                        [
                                            { text: 'Lower Body', bold: true, fontSize: 10 },
                                            { text: ' ' },
                                            { text: 'L', alignment: 'center' },
                                            { text: 'R', alignment: 'center' },
                                        ],
                                        [
                                            { text: 'Ankle' },
                                            { text: 'Dorsiflexion' },
                                            @if($posturalassessment->ankleDorsiflexL == '1')
                                                { text: '[ √ ]', alignment: 'center' },
                                            @else
                                                { text: '[\u200B\t]', alignment: 'center' },
                                            @endif
                                            @if($posturalassessment->ankleDorsiflexR == '1')
                                                { text: '[ √ ]', alignment: 'center' },
                                            @else
                                                { text: '[\u200B\t]', alignment: 'center' },
                                            @endif
                                        ],
                                        [
                                            { text: ' ' },
                                            { text: 'Plantarflexion' },
                                            @if($posturalassessment->anklePlantarL == '1')
                                                { text: '[ √ ]', alignment: 'center' },
                                            @else
                                                { text: '[\u200B\t]', alignment: 'center' },
                                            @endif
                                            @if($posturalassessment->anklePlantarR == '1')
                                                { text: '[ √ ]', alignment: 'center' },
                                            @else
                                                { text: '[\u200B\t]', alignment: 'center' },
                                            @endif
                                        ],
                                        [
                                            { text: 'Knee' },
                                            { text: 'Flexed' },
                                            @if($posturalassessment->kneeFlexedL == '1')
                                                { text: '[ √ ]', alignment: 'center' },
                                            @else
                                                { text: '[\u200B\t]', alignment: 'center' },
                                            @endif
                                            @if($posturalassessment->kneeFlexedR == '1')
                                                { text: '[ √ ]', alignment: 'center' },
                                            @else
                                                { text: '[\u200B\t]', alignment: 'center' },
                                            @endif
                                        ],
                                        [
                                            { text: ' ' },
                                            { text: 'Hyperextended' },
                                            @if($posturalassessment->kneeHyperextendL == '1')
                                                { text: '[ √ ]', alignment: 'center' },
                                            @else
                                                { text: '[\u200B\t]', alignment: 'center' },
                                            @endif
                                            @if($posturalassessment->kneeHyperextendR == '1')
                                                { text: '[ √ ]', alignment: 'center' },
                                            @else
                                                { text: '[\u200B\t]', alignment: 'center' },
                                            @endif
                                        ],
                                        [
                                            { text: 'Pelvis' },
                                            { text: 'Anterior translation' },
                                            @if($posturalassessment->pelvisAnterTransL == '1')
                                                { text: '[ √ ]', alignment: 'center' },
                                            @else
                                                { text: '[\u200B\t]', alignment: 'center' },
                                            @endif
                                            @if($posturalassessment->pelvisAnterTransR == '1')
                                                { text: '[ √ ]', alignment: 'center' },
                                            @else
                                                { text: '[\u200B\t]', alignment: 'center' },
                                            @endif
                                        ],
                                        [
                                            { text: ' ' },
                                            { text: ' ' },
                                            { text: 'Y', alignment: 'center' },
                                            { text: 'N', alignment: 'center' },
                                        ],
                                        [
                                            { text: ' ' },
                                            { text: 'Is the deviation symmetrical?' },
                                            @if($posturalassessment->devSymmetry == '1')
                                                { text: '[ √ ]', alignment: 'center' },
                                                { text: '[\u200B\t]', alignment: 'center' },
                                            @else
                                                { text: '[\u200B\t]', alignment: 'center' },
                                                { text: '[ √ ]', alignment: 'center' },
                                            @endif
                                        ],
                                        [
                                            { text: ' ' },
                                            { text: 'Tilt: Anterior' },
                                            @if($posturalassessment->tiltAnterior == '1')
                                                { text: '[ √ ]', alignment: 'center' },
                                                { text: '[\u200B\t]', alignment: 'center' },
                                            @else
                                                { text: '[\u200B\t]', alignment: 'center' },
                                                { text: '[ √ ]', alignment: 'center' },
                                            @endif
                                        ],
                                        [
                                            { text: ' ' },
                                            { text: 'Posterior' },
                                            @if($posturalassessment->tiltPosterior == '1')
                                                { text: '[ √ ]', alignment: 'center' },
                                                { text: '[\u200B\t]', alignment: 'center' },
                                            @else
                                                { text: '[\u200B\t]', alignment: 'center' },
                                                { text: '[ √ ]', alignment: 'center' },
                                            @endif
                                        ],
                                        [
                                            { text: 'Upper Body', bold: true, fontSize: 10 },
                                            { text: ' ' },
                                            { text: 'Y', alignment: 'center' },
                                            { text: 'N', alignment: 'center' },
                                        ],
                                        [
                                            { text: 'Lumbar spine' },
                                            { text: 'Lordosis' },
                                            @if($posturalassessment->LSLordosis == '1')
                                                { text: '[ √ ]', alignment: 'center' },
                                                { text: '[\u200B\t]', alignment: 'center' },
                                            @else
                                                { text: '[\u200B\t]', alignment: 'center' },
                                                { text: '[ √ ]', alignment: 'center' },
                                            @endif
                                        ],
                                        [
                                            { text: ' ' },
                                            { text: 'Flat' },
                                            @if($posturalassessment->LSFlat == '1')
                                                { text: '[ √ ]', alignment: 'center' },
                                                { text: '[\u200B\t]', alignment: 'center' },
                                            @else
                                                { text: '[\u200B\t]', alignment: 'center' },
                                                { text: '[ √ ]', alignment: 'center' },
                                            @endif
                                        ],
                                        [
                                            { text: 'Thorac spine' },
                                            { text: 'Kyphosis' },
                                            @if($posturalassessment->TSKyphosis == '1')
                                                { text: '[ √ ]', alignment: 'center' },
                                                { text: '[\u200B\t]', alignment: 'center' },
                                            @else
                                                { text: '[\u200B\t]', alignment: 'center' },
                                                { text: '[ √ ]', alignment: 'center' },
                                            @endif
                                        ],
                                        [
                                            { text: ' ' },
                                            { text: 'Flat' },
                                            @if($posturalassessment->TSFlat == '1')
                                                { text: '[ √ ]', alignment: 'center' },
                                                { text: '[\u200B\t]', alignment: 'center' },
                                            @else
                                                { text: '[\u200B\t]', alignment: 'center' },
                                                { text: '[ √ ]', alignment: 'center' },
                                            @endif
                                        ],
                                        [
                                            { text: 'Trunk' },
                                            { text: 'Rotation (Symmetry)' },
                                            @if($posturalassessment->trunkRotation == '1')
                                                { text: '[ √ ]', alignment: 'center' },
                                                { text: '[\u200B\t]', alignment: 'center' },
                                            @else
                                                { text: '[\u200B\t]', alignment: 'center' },
                                                { text: '[ √ ]', alignment: 'center' },
                                            @endif
                                        ],
                                        [
                                            { text: ' ' },
                                            { text: ' ' },
                                            { text: ' ' },
                                            { text: ' ' },
                                        ],
                                        [
                                            { text: 'Shoulders' },
                                            { text: 'Forward' },
                                            @if($posturalassessment->shoulderForward == '1')
                                                { text: '[ √ ]', alignment: 'center' },
                                                { text: '[\u200B\t]', alignment: 'center' },
                                            @else
                                                { text: '[\u200B\t]', alignment: 'center' },
                                                { text: '[ √ ]', alignment: 'center' },
                                            @endif
                                        ],
                                        [
                                            { text: 'Head position' },
                                            { text: 'Forward' },
                                            @if($posturalassessment->HPForward == '1')
                                                { text: '[ √ ]', alignment: 'center' },
                                                { text: '[\u200B\t]', alignment: 'center' },
                                            @else
                                                { text: '[\u200B\t]', alignment: 'center' },
                                                { text: '[ √ ]', alignment: 'center' },
                                            @endif
                                        ],
                                        [
                                            { text: ' ' },
                                            { text: 'Back' },
                                            @if($posturalassessment->HPBack == '1')
                                                { text: '[ √ ]', alignment: 'center' },
                                                { text: '[\u200B\t]', alignment: 'center' },
                                            @else
                                                { text: '[\u200B\t]', alignment: 'center' },
                                                { text: '[ √ ]', alignment: 'center' },
                                            @endif
                                        ],
                                    ]
                                },
                                layout: 'noBorders'
                            },
                            {
                                // Right table
                                width: 300, // Takes remaining space
                                style: 'tableRightBtm',
                                table: {
                                    widths: [220],
                                    // headerRows: 1,
                                    body: [
                                        [
                                            {
                                                text: [
                                                    { text: 'Comments:', bold: true },
                                                    { text: `\n\n{!!$posturalassessment->lateralRmk!!}` },
                                                ],
                                            },
                                        ],
                                    ]
                                },
                                // layout: 'noBorders'
                            }
                        ]
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
                    tableLeft: {
                        fontSize: 8,
                        margin: [0, 15, 0, 0]
                    },
                    tableRight: {
                        fontSize: 8,
                        margin: [0, 15, 0, 0]
                    },
                    tableLeftBtm: {
                        fontSize: 8,
                        margin: [0, 40, 0, 0]
                    },
                    tableRightBtm: {
                        fontSize: 8,
                        margin: [0, 40, 0, 0]
                    },
                    tableHeader: {
                        bold: true,
                        fontSize: 9,
                        color: 'black'
                    },
                    totalscore: {
                        alignment: 'right',
                        fontSize: 8,
                        margin: [0, 5, 15, 0]
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
            
            pdfMake.createPdf(docDefinition).getBase64(function (dataURL){
                base64_pr = dataURL;
                
                var obj = {
                    base64: dataURL,
                    _token: $('#_token').val(),
                    merge_key: merge_key,
                    lineno_: 1
                };
                
                $.post('../attachment_upload/form?page=merge_pdf', $.param(obj), function (data){
                }).done(function (data){
                });
            });
        });
        
        $(document).ready(function (){
            $('div.canclick').click(function (){
                $('div.canclick').removeClass('teal inverted');
                $(this).addClass('teal inverted');
                var goto = $(this).data('goto');
                
                if($(goto).offset() != undefined){
                    $('html, body').animate({
                        scrollTop: $(goto).offset().top
                    }, 500, function (){
                        
                    });
                }
            });
            
            $('#merge_btn').click(function (){
                let attach_array = [];
                $('input:checkbox:checked').each(function (){
                    attach_array.push($(this).data('src'));
                });
                
                if(attach_array.length > 0){
                    var obj = {
                        page: 'merge_pdf_with_attachment',
                        merge_key: merge_key,
                        attach_array: attach_array
                    };
                    
                    $('#pdfiframe_merge').attr('src',"../attachment_upload/table?"+$.param(obj));
                    $('#btn_merge,#pdfiframe_merge').show();
                    $('#btn_merge').click();
                }else{
                    alert('Select at least 1 PDF Attachment to merge with main PDF');
                }
            });
            
            $('#ref_dropdown.ui.dropdown')
            .dropdown({
                onChange: function (value, text, $selectedItem){
                    window.open(value);
                }
            });
        });
        
        function makeid(length){
            let result = '';
            const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            const charactersLength = characters.length;
            let counter = 0;
            while(counter < length){
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
                counter += 1;
            }
            return result;
        }
        
        function populate_attachmentfile(){
            $('#pdfiframe_1').attr('src',attachmentfiles);
        }
        
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
        <input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">
        <div class="ui segments" style="width: 18vw; height: 95vh; float: left; margin: 10px; position: fixed;">
            <div class="ui secondary segment">
                <h3>
                    <b>Navigation</b>
                    <button id="merge_btn" class="ui small primary button" style="font-size: 12px; padding: 6px 10px; float: right;">Merge</button>
                </h3>
            </div>
            <div class="ui segment teal inverted canclick" style="cursor: pointer;" data-goto='#pdfiframe'>
                <p>Postural Assessment</p>
            </div>
            @if($attachment_files1 != '')
            <div class="ui segment canclick" style="cursor: pointer;" data-goto='#pdfiframe_1'>
                <p>IMAGE<input type="checkbox" data-src="{{$attachment_files1}}" name="1" style="float: right; margin-right: 5px;"></p>
            </div>
            @endif
            @if($attachment_files2 != '')
            <div class="ui segment canclick" style="cursor: pointer;" data-goto='#pdfiframe_1'>
                <p>IMAGE<input type="checkbox" data-src="{{$attachment_files2}}" name="1" style="float: right; margin-right: 5px;"></p>
            </div>
            @endif
            <div id="btn_merge" class="ui segment canclick" style="cursor: pointer; display: none;" data-goto='#pdfiframe_merge'>
                <p>Merged File</p>
            </div>
        </div>
        <iframe id="pdfiframe" width="100%" height="100%" src="" frameborder="0" style="width: 79vw; height: 100vh; float: right;"></iframe>
        
        @if($attachment_files1 != '')
        <iframe id="pdfiframe_1" width="100%" height="100%" src="{{$attachment_files1}}" frameborder="0" style="width: 79vw; height: 100vh; float: right;"></iframe>
        @endif
        @if($attachment_files2 != '')
        <iframe id="pdfiframe_1" width="100%" height="100%" src="{{$attachment_files2}}" frameborder="0" style="width: 79vw; height: 100vh; float: right;"></iframe>
        @endif
        <iframe id="pdfiframe_merge" width="100%" height="100%" src="" frameborder="0" style="width: 79vw; height: 100vh; float: right; display: none;"></iframe>
        
        <!-- <iframe id="pdfiframe" width="100%" height="100%" src="" frameborder="0" style="width: 99vw; height: 99vh;"></iframe> -->
    </body>
</html>