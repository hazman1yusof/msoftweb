<!DOCTYPE html>
<html>
    <head>
        <title>Neurological Physiotherapy Assessment</title>
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
                        text: '\nNEUROLOGICAL PHYSIOTHERAPY ASSESSMENT\n\n',
                        style: 'header',
                        alignment: 'center',
                    },
                    // { canvas: [ { type: 'line', x1: 0, y1: 5, x2: 515, y2: 5, lineWidth: 0.5 } ] },
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: [70,'*'], // panjang standard dia 515
                            body: [
                                [
                                    { text: 'OBJECTIVE AX:' },
                                    { text: `{!!$neuroassessment->objective!!}` },
                                ],
                            ]
                        },
                        layout: 'noBorders',
                    },
                    { text: '1) BODY CHART: ', fontSize: 9 },
                    {
                        style: 'tableExample1',
                        table: {
                            headerRows: 1,
                            widths: [80,120],
                            body: [
                                [
                                    { text: 'A) Pain Score: ' },
                                    { text: '({{$neuroassessment->painscore}}/10)', alignment: 'left' },
                                ],
                                [
                                    { text: 'B) Type of Pain: ' },
                                    { text: '{{$neuroassessment->painType}}', alignment: 'left' },
                                ],
                                [
                                    { text: 'C) Severity: ' },
                                    { text: '{{$neuroassessment->severityBC}}', alignment: 'left' },
                                ],
                                [
                                    { text: 'D) Irritability: ' },
                                    { text: '{{$neuroassessment->irritabilityBC}}', alignment: 'left' },
                                ],
                                [
                                    { text: 'E) Location of Pain: ' },
                                    @if($neuroassessment->painLocation == 'Deep')
                                        { text: '[ √ ] Deep \u200B\t [\u200B\t] Superficial', alignment: 'left' },
                                    @elseif($neuroassessment->painLocation == 'Superficial')
                                        { text: '[\u200B\t] Deep \u200B\t [ √ ] Superficial', alignment: 'left' },
                                    @else
                                        { text: '[\u200B\t] Deep \u200B\t [\u200B\t] Superficial', alignment: 'left' },
                                    @endif
                                ],
                                [
                                    { text: 'F) Subluxation: ' },
                                    { text: '(comment: {{$neuroassessment->subluxation}})', alignment: 'left' },
                                ],
                                [
                                    { text: 'G) Palpation: ' },
                                    { text: '{{$neuroassessment->palpationBC}}', alignment: 'left' },
                                ],
                            ]
                        },
                        layout: 'noBorders',
                    },
                    {
                        style: 'tableExample1',
                        table: {
                            headerRows: 1,
                            widths: [60,'*'], // panjang standard dia 515
                            body: [
                                [
                                    { text: 'Impression:' },
                                    { text: `{!!$neuroassessment->impressionBC!!}` },
                                ],
                            ]
                        },
                        layout: 'noBorders',
                    },
                    { text: '2) SENSATION: ', fontSize: 9 },
                    {
                        style: 'tableExample1',
                        table: {
                            headerRows: 1,
                            widths: [80,80,80,80],
                            body: [
                                [
                                    { text: 'Sensitivity ', style: 'tableHeader' },
                                    { text: 'R', style: 'tableHeader', alignment: 'center' },
                                    { text: 'L', style: 'tableHeader', alignment: 'center' },
                                    { text: '(Specification)', style: 'tableHeader', alignment: 'left' },
                                ],
                                [
                                    { text: 'Superficial' },
                                    @if($neuroassessment->superficialR == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    @if($neuroassessment->superficialL == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '{{$neuroassessment->superficialSpec}}', alignment: 'left' },
                                ],
                                [
                                    { text: 'Deep' },
                                    @if($neuroassessment->deepR == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    @if($neuroassessment->deepL == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '{{$neuroassessment->deepSpec}}', alignment: 'left' },
                                ],
                                [
                                    { text: 'Numbness' },
                                    @if($neuroassessment->numbnessR == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    @if($neuroassessment->numbnessL == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '{{$neuroassessment->numbnessSpec}}', alignment: 'left' },
                                ],
                                [
                                    { text: 'Paresthesia' },
                                    @if($neuroassessment->paresthesiaR == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    @if($neuroassessment->paresthesiaL == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '{{$neuroassessment->paresthesiaSpec}}', alignment: 'left' },
                                ],
                                [
                                    { text: 'Other' },
                                    @if($neuroassessment->otherR == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    @if($neuroassessment->otherL == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '{{$neuroassessment->otherSpec}}', alignment: 'left' },
                                ],
                            ]
                        },
                        layout: {
                            fillColor: function (rowIndex, node, columnIndex){
                                // return (rowIndex === 0) ? '#000000' : null;
                            }
                        },
                    },
                    {
                        style: 'tableExample1',
                        table: {
                            headerRows: 1,
                            widths: [60,'*'], // panjang standard dia 515
                            body: [
                                [
                                    { text: 'Impression:' },
                                    { text: `{!!$neuroassessment->impressionSens!!}` },
                                ],
                            ]
                        },
                        layout: 'noBorders',
                    },
                    {
                        text: [
                            { text: '3) ROM\n\n', fontSize: 9 },
                            { text: '\u200B\t\u200B\ta) Affected Side', fontSize: 9 },
                            @if($romaffectedside->romAffectedSide == 'R')
                                { text: '\u200B\t[ √ ] R \u200B\t [\u200B\t] L', fontSize: 9 },
                            @elseif($romaffectedside->romAffectedSide == 'L')
                                { text: '\u200B\t[\u200B\t] R \u200B\t [ √ ] L', fontSize: 9 },
                            @else
                                { text: '\u200B\t[\u200B\t] R \u200B\t [\u200B\t] L', fontSize: 9 },
                            @endif
                        ],
                    },
                    {
                        style: 'tableExample1',
                        table: {
                            headerRows: 1,
                            widths: [46,75,50,50,50,50,50,50],
                            body: [
                                [
                                    { text: 'JOINT ', style: 'tableHeader', alignment: 'left' },
                                    { text: 'MOVEMENT', style: 'tableHeader', alignment: 'left' },
                                    { text: 'INITIAL', style: 'tableHeader', alignment: 'left', colSpan: 2 },{},
                                    { text: 'PROGRESS', style: 'tableHeader', alignment: 'left', colSpan: 2 },{},
                                    { text: 'FINAL', style: 'tableHeader', alignment: 'left', colSpan: 2 },{},
                                ],
                                [
                                    { text: ' ', style: 'tableHeader' },
                                    { text: '', style: 'tableHeader' },
                                    { text: 'P', style: 'tableHeader', alignment: 'left' },
                                    { text: 'A', style: 'tableHeader', alignment: 'left' },
                                    { text: 'P', style: 'tableHeader', alignment: 'left' },
                                    { text: 'A', style: 'tableHeader', alignment: 'left' },
                                    { text: 'P', style: 'tableHeader', alignment: 'left' },
                                    { text: 'A', style: 'tableHeader', alignment: 'left' },
                                ],
                                [
                                    { text: 'SHOULDER' },
                                    { text: 'FLEXION' },
                                    { text: '{{$romaffectedside->aShlderFlxInitP}}' },
                                    { text: '{{$romaffectedside->aShlderFlxInitA}}' },
                                    { text: '{{$romaffectedside->aShlderFlxProgP}}' },
                                    { text: '{{$romaffectedside->aShlderFlxProgA}}' },
                                    { text: '{{$romaffectedside->aShlderFlxFinP}}' },
                                    { text: '{{$romaffectedside->aShlderFlxFinA}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'EXTENSION' },
                                    { text: '{{$romaffectedside->aShlderExtInitP}}' },
                                    { text: '{{$romaffectedside->aShlderExtInitA}}' },
                                    { text: '{{$romaffectedside->aShlderExtProgP}}' },
                                    { text: '{{$romaffectedside->aShlderExtProgA}}' },
                                    { text: '{{$romaffectedside->aShlderExtFinP}}' },
                                    { text: '{{$romaffectedside->aShlderExtFinA}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'ABDUCTION' },
                                    { text: '{{$romaffectedside->aShlderAbdInitP}}' },
                                    { text: '{{$romaffectedside->aShlderAbdInitA}}' },
                                    { text: '{{$romaffectedside->aShlderAbdProgP}}' },
                                    { text: '{{$romaffectedside->aShlderAbdProgA}}' },
                                    { text: '{{$romaffectedside->aShlderAbdFinP}}' },
                                    { text: '{{$romaffectedside->aShlderAbdFinA}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'ADDUCTION' },
                                    { text: '{{$romaffectedside->aShlderAddInitP}}' },
                                    { text: '{{$romaffectedside->aShlderAddInitA}}' },
                                    { text: '{{$romaffectedside->aShlderAddProgP}}' },
                                    { text: '{{$romaffectedside->aShlderAddProgA}}' },
                                    { text: '{{$romaffectedside->aShlderAddFinP}}' },
                                    { text: '{{$romaffectedside->aShlderAddFinA}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'INT. ROT' },
                                    { text: '{{$romaffectedside->aShlderIntRotInitP}}' },
                                    { text: '{{$romaffectedside->aShlderIntRotInitA}}' },
                                    { text: '{{$romaffectedside->aShlderIntRotProgP}}' },
                                    { text: '{{$romaffectedside->aShlderIntRotProgA}}' },
                                    { text: '{{$romaffectedside->aShlderIntRotFinP}}' },
                                    { text: '{{$romaffectedside->aShlderIntRotFinA}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'EXT. ROT' },
                                    { text: '{{$romaffectedside->aShlderExtRotInitP}}' },
                                    { text: '{{$romaffectedside->aShlderExtRotInitA}}' },
                                    { text: '{{$romaffectedside->aShlderExtRotProgP}}' },
                                    { text: '{{$romaffectedside->aShlderExtRotProgA}}' },
                                    { text: '{{$romaffectedside->aShlderExtRotFinP}}' },
                                    { text: '{{$romaffectedside->aShlderExtRotFinA}}' },
                                ],
                                [
                                    { text: 'ELBOW' },
                                    { text: 'FLEXION' },
                                    { text: '{{$romaffectedside->aElbowFlxInitP}}' },
                                    { text: '{{$romaffectedside->aElbowFlxInitA}}' },
                                    { text: '{{$romaffectedside->aElbowFlxProgP}}' },
                                    { text: '{{$romaffectedside->aElbowFlxProgA}}' },
                                    { text: '{{$romaffectedside->aElbowFlxFinP}}' },
                                    { text: '{{$romaffectedside->aElbowFlxFinA}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'EXTENSION' },
                                    { text: '{{$romaffectedside->aElbowExtInitP}}' },
                                    { text: '{{$romaffectedside->aElbowExtInitA}}' },
                                    { text: '{{$romaffectedside->aElbowExtProgP}}' },
                                    { text: '{{$romaffectedside->aElbowExtProgA}}' },
                                    { text: '{{$romaffectedside->aElbowExtFinP}}' },
                                    { text: '{{$romaffectedside->aElbowExtFinA}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'PRONATION' },
                                    { text: '{{$romaffectedside->aElbowProInitP}}' },
                                    { text: '{{$romaffectedside->aElbowProInitA}}' },
                                    { text: '{{$romaffectedside->aElbowProProgP}}' },
                                    { text: '{{$romaffectedside->aElbowProProgA}}' },
                                    { text: '{{$romaffectedside->aElbowProFinP}}' },
                                    { text: '{{$romaffectedside->aElbowProFinA}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'SUPINATION' },
                                    { text: '{{$romaffectedside->aElbowSupInitP}}' },
                                    { text: '{{$romaffectedside->aElbowSupInitA}}' },
                                    { text: '{{$romaffectedside->aElbowSupProgP}}' },
                                    { text: '{{$romaffectedside->aElbowSupProgA}}' },
                                    { text: '{{$romaffectedside->aElbowSupFinP}}' },
                                    { text: '{{$romaffectedside->aElbowSupFinA}}' },
                                ],
                                [
                                    { text: 'WRIST' },
                                    { text: 'FLEXION' },
                                    { text: '{{$romaffectedside->aWristFlxInitP}}' },
                                    { text: '{{$romaffectedside->aWristFlxInitA}}' },
                                    { text: '{{$romaffectedside->aWristFlxProgP}}' },
                                    { text: '{{$romaffectedside->aWristFlxProgA}}' },
                                    { text: '{{$romaffectedside->aWristFlxFinP}}' },
                                    { text: '{{$romaffectedside->aWristFlxFinA}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'EXTENSION' },
                                    { text: '{{$romaffectedside->aWristExtInitP}}' },
                                    { text: '{{$romaffectedside->aWristExtInitA}}' },
                                    { text: '{{$romaffectedside->aWristExtProgP}}' },
                                    { text: '{{$romaffectedside->aWristExtProgA}}' },
                                    { text: '{{$romaffectedside->aWristExtFinP}}' },
                                    { text: '{{$romaffectedside->aWristExtFinA}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'RADIAL DEVIATION' },
                                    { text: '{{$romaffectedside->aWristRadInitP}}' },
                                    { text: '{{$romaffectedside->aWristRadInitA}}' },
                                    { text: '{{$romaffectedside->aWristRadProgP}}' },
                                    { text: '{{$romaffectedside->aWristRadProgA}}' },
                                    { text: '{{$romaffectedside->aWristRadFinP}}' },
                                    { text: '{{$romaffectedside->aWristRadFinA}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'ULNAR DEVIATION' },
                                    { text: '{{$romaffectedside->aWristUlnarInitP}}' },
                                    { text: '{{$romaffectedside->aWristUlnarInitA}}' },
                                    { text: '{{$romaffectedside->aWristUlnarProgP}}' },
                                    { text: '{{$romaffectedside->aWristUlnarProgA}}' },
                                    { text: '{{$romaffectedside->aWristUlnarFinP}}' },
                                    { text: '{{$romaffectedside->aWristUlnarFinA}}' },
                                ],
                                [
                                    { text: 'HIP' },
                                    { text: 'FLEXION' },
                                    { text: '{{$romaffectedside->aHipFlxInitP}}' },
                                    { text: '{{$romaffectedside->aHipFlxInitA}}' },
                                    { text: '{{$romaffectedside->aHipFlxProgP}}' },
                                    { text: '{{$romaffectedside->aHipFlxProgA}}' },
                                    { text: '{{$romaffectedside->aHipFlxFinP}}' },
                                    { text: '{{$romaffectedside->aHipFlxFinA}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'EXTENSION' },
                                    { text: '{{$romaffectedside->aHipExtInitP}}' },
                                    { text: '{{$romaffectedside->aHipExtInitA}}' },
                                    { text: '{{$romaffectedside->aHipExtProgP}}' },
                                    { text: '{{$romaffectedside->aHipExtProgA}}' },
                                    { text: '{{$romaffectedside->aHipExtFinP}}' },
                                    { text: '{{$romaffectedside->aHipExtFinA}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'ABDUCTION' },
                                    { text: '{{$romaffectedside->aHipAbdInitP}}' },
                                    { text: '{{$romaffectedside->aHipAbdInitA}}' },
                                    { text: '{{$romaffectedside->aHipAbdProgP}}' },
                                    { text: '{{$romaffectedside->aHipAbdProgA}}' },
                                    { text: '{{$romaffectedside->aHipAbdFinP}}' },
                                    { text: '{{$romaffectedside->aHipAbdFinA}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'ADDUCTION' },
                                    { text: '{{$romaffectedside->aHipAddInitP}}' },
                                    { text: '{{$romaffectedside->aHipAddInitA}}' },
                                    { text: '{{$romaffectedside->aHipAddProgP}}' },
                                    { text: '{{$romaffectedside->aHipAddProgA}}' },
                                    { text: '{{$romaffectedside->aHipAddFinP}}' },
                                    { text: '{{$romaffectedside->aHipAddFinA}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'INT. ROT' },
                                    { text: '{{$romaffectedside->aHipIntRotInitP}}' },
                                    { text: '{{$romaffectedside->aHipIntRotInitA}}' },
                                    { text: '{{$romaffectedside->aHipIntRotProgP}}' },
                                    { text: '{{$romaffectedside->aHipIntRotProgA}}' },
                                    { text: '{{$romaffectedside->aHipIntRotFinP}}' },
                                    { text: '{{$romaffectedside->aHipIntRotFinA}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'EXT. ROT' },
                                    { text: '{{$romaffectedside->aHipExtRotInitP}}' },
                                    { text: '{{$romaffectedside->aHipExtRotInitA}}' },
                                    { text: '{{$romaffectedside->aHipExtRotProgP}}' },
                                    { text: '{{$romaffectedside->aHipExtRotProgA}}' },
                                    { text: '{{$romaffectedside->aHipExtRotFinP}}' },
                                    { text: '{{$romaffectedside->aHipExtRotFinA}}' },
                                ],
                                [
                                    { text: 'KNEE' },
                                    { text: 'EXTENSION' },
                                    { text: '{{$romaffectedside->aKneeExtInitP}}' },
                                    { text: '{{$romaffectedside->aKneeExtInitA}}' },
                                    { text: '{{$romaffectedside->aKneeExtProgP}}' },
                                    { text: '{{$romaffectedside->aKneeExtProgA}}' },
                                    { text: '{{$romaffectedside->aKneeExtFinP}}' },
                                    { text: '{{$romaffectedside->aKneeExtFinA}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'FLEXION' },
                                    { text: '{{$romaffectedside->aKneeFlxInitP}}' },
                                    { text: '{{$romaffectedside->aKneeFlxInitA}}' },
                                    { text: '{{$romaffectedside->aKneeFlxProgP}}' },
                                    { text: '{{$romaffectedside->aKneeFlxProgA}}' },
                                    { text: '{{$romaffectedside->aKneeFlxFinP}}' },
                                    { text: '{{$romaffectedside->aKneeFlxFinA}}' },
                                ],
                                [
                                    { text: 'ANKLE' },
                                    { text: 'DORSIFLEXION' },
                                    { text: '{{$romaffectedside->aAnkleDorsInitP}}' },
                                    { text: '{{$romaffectedside->aAnkleDorsInitA}}' },
                                    { text: '{{$romaffectedside->aAnkleDorsProgP}}' },
                                    { text: '{{$romaffectedside->aAnkleDorsProgA}}' },
                                    { text: '{{$romaffectedside->aAnkleDorsFinP}}' },
                                    { text: '{{$romaffectedside->aAnkleDorsFinA}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'PLANTARFLEXION' },
                                    { text: '{{$romaffectedside->aAnklePtarInitP}}' },
                                    { text: '{{$romaffectedside->aAnklePtarInitA}}' },
                                    { text: '{{$romaffectedside->aAnklePtarProgP}}' },
                                    { text: '{{$romaffectedside->aAnklePtarProgA}}' },
                                    { text: '{{$romaffectedside->aAnklePtarFinP}}' },
                                    { text: '{{$romaffectedside->aAnklePtarFinA}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'EVERSION' },
                                    { text: '{{$romaffectedside->aAnkleEverInitP}}' },
                                    { text: '{{$romaffectedside->aAnkleEverInitA}}' },
                                    { text: '{{$romaffectedside->aAnkleEverProgP}}' },
                                    { text: '{{$romaffectedside->aAnkleEverProgA}}' },
                                    { text: '{{$romaffectedside->aAnkleEverFinP}}' },
                                    { text: '{{$romaffectedside->aAnkleEverFinA}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'INVERSION' },
                                    { text: '{{$romaffectedside->aAnkleInverInitP}}' },
                                    { text: '{{$romaffectedside->aAnkleInverInitA}}' },
                                    { text: '{{$romaffectedside->aAnkleInverProgP}}' },
                                    { text: '{{$romaffectedside->aAnkleInverProgA}}' },
                                    { text: '{{$romaffectedside->aAnkleInverFinP}}' },
                                    { text: '{{$romaffectedside->aAnkleInverFinA}}' },
                                ],
                            ]
                        },
                        layout: {
                            fillColor: function (rowIndex, node, columnIndex){
                                // return (rowIndex === 0) ? '#000000' : null;
                            }
                        },
                    },
                    {
                        text: [
                            { text: '\u200B\t\u200B\tb) Sound Side', fontSize: 9 },
                            @if($romsoundside->romSoundSide == 'R')
                                { text: '\u200B\t[ √ ] R \u200B\t [\u200B\t] L', fontSize: 9 },
                            @elseif($romsoundside->romSoundSide == 'L')
                                { text: '\u200B\t[\u200B\t] R \u200B\t [ √ ] L', fontSize: 9 },
                            @else
                                { text: '\u200B\t[\u200B\t] R \u200B\t [\u200B\t] L', fontSize: 9 },
                            @endif
                        ],
                    },
                    {
                        style: 'tableExample1',
                        table: {
                            headerRows: 1,
                            widths: [46,75,50,50,50,50,50,50],
                            body: [
                                [
                                    { text: 'JOINT ', style: 'tableHeader', alignment: 'left' },
                                    { text: 'MOVEMENT', style: 'tableHeader', alignment: 'left' },
                                    { text: 'INITIAL', style: 'tableHeader', alignment: 'left', colSpan: 2 },{},
                                    { text: 'PROGRESS', style: 'tableHeader', alignment: 'left', colSpan: 2 },{},
                                    { text: 'FINAL', style: 'tableHeader', alignment: 'left', colSpan: 2 },{},
                                ],
                                [
                                    { text: ' ', style: 'tableHeader' },
                                    { text: '', style: 'tableHeader' },
                                    { text: 'P', style: 'tableHeader', alignment: 'left' },
                                    { text: 'A', style: 'tableHeader', alignment: 'left' },
                                    { text: 'P', style: 'tableHeader', alignment: 'left' },
                                    { text: 'A', style: 'tableHeader', alignment: 'left' },
                                    { text: 'P', style: 'tableHeader', alignment: 'left' },
                                    { text: 'A', style: 'tableHeader', alignment: 'left' },
                                ],
                                [
                                    { text: 'SHOULDER' },
                                    { text: 'FLEXION' },
                                    { text: '{{$romsoundside->sShlderFlxInitP}}' },
                                    { text: '{{$romsoundside->sShlderFlxInitA}}' },
                                    { text: '{{$romsoundside->sShlderFlxProgP}}' },
                                    { text: '{{$romsoundside->sShlderFlxProgA}}' },
                                    { text: '{{$romsoundside->sShlderFlxFinP}}' },
                                    { text: '{{$romsoundside->sShlderFlxFinA}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'EXTENSION' },
                                    { text: '{{$romsoundside->sShlderExtInitP}}' },
                                    { text: '{{$romsoundside->sShlderExtInitA}}' },
                                    { text: '{{$romsoundside->sShlderExtProgP}}' },
                                    { text: '{{$romsoundside->sShlderExtProgA}}' },
                                    { text: '{{$romsoundside->sShlderExtFinP}}' },
                                    { text: '{{$romsoundside->sShlderExtFinA}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'ABDUCTION' },
                                    { text: '{{$romsoundside->sShlderAbdInitP}}' },
                                    { text: '{{$romsoundside->sShlderAbdInitA}}' },
                                    { text: '{{$romsoundside->sShlderAbdProgP}}' },
                                    { text: '{{$romsoundside->sShlderAbdProgA}}' },
                                    { text: '{{$romsoundside->sShlderAbdFinP}}' },
                                    { text: '{{$romsoundside->sShlderAbdFinA}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'ADDUCTION' },
                                    { text: '{{$romsoundside->sShlderAddInitP}}' },
                                    { text: '{{$romsoundside->sShlderAddInitA}}' },
                                    { text: '{{$romsoundside->sShlderAddProgP}}' },
                                    { text: '{{$romsoundside->sShlderAddProgA}}' },
                                    { text: '{{$romsoundside->sShlderAddFinP}}' },
                                    { text: '{{$romsoundside->sShlderAddFinA}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'INT. ROT' },
                                    { text: '{{$romsoundside->sShlderIntRotInitP}}' },
                                    { text: '{{$romsoundside->sShlderIntRotInitA}}' },
                                    { text: '{{$romsoundside->sShlderIntRotProgP}}' },
                                    { text: '{{$romsoundside->sShlderIntRotProgA}}' },
                                    { text: '{{$romsoundside->sShlderIntRotFinP}}' },
                                    { text: '{{$romsoundside->sShlderIntRotFinA}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'EXT. ROT' },
                                    { text: '{{$romsoundside->sShlderExtRotInitP}}' },
                                    { text: '{{$romsoundside->sShlderExtRotInitA}}' },
                                    { text: '{{$romsoundside->sShlderExtRotProgP}}' },
                                    { text: '{{$romsoundside->sShlderExtRotProgA}}' },
                                    { text: '{{$romsoundside->sShlderExtRotFinP}}' },
                                    { text: '{{$romsoundside->sShlderExtRotFinA}}' },
                                ],
                                [
                                    { text: 'ELBOW' },
                                    { text: 'FLEXION' },
                                    { text: '{{$romsoundside->sElbowFlxInitP}}' },
                                    { text: '{{$romsoundside->sElbowFlxInitA}}' },
                                    { text: '{{$romsoundside->sElbowFlxProgP}}' },
                                    { text: '{{$romsoundside->sElbowFlxProgA}}' },
                                    { text: '{{$romsoundside->sElbowFlxFinP}}' },
                                    { text: '{{$romsoundside->sElbowFlxFinA}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'EXTENSION' },
                                    { text: '{{$romsoundside->sElbowExtInitP}}' },
                                    { text: '{{$romsoundside->sElbowExtInitA}}' },
                                    { text: '{{$romsoundside->sElbowExtProgP}}' },
                                    { text: '{{$romsoundside->sElbowExtProgA}}' },
                                    { text: '{{$romsoundside->sElbowExtFinP}}' },
                                    { text: '{{$romsoundside->sElbowExtFinA}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'PRONATION' },
                                    { text: '{{$romsoundside->sElbowProInitP}}' },
                                    { text: '{{$romsoundside->sElbowProInitA}}' },
                                    { text: '{{$romsoundside->sElbowProProgP}}' },
                                    { text: '{{$romsoundside->sElbowProProgA}}' },
                                    { text: '{{$romsoundside->sElbowProFinP}}' },
                                    { text: '{{$romsoundside->sElbowProFinA}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'SUPINATION' },
                                    { text: '{{$romsoundside->sElbowSupInitP}}' },
                                    { text: '{{$romsoundside->sElbowSupInitA}}' },
                                    { text: '{{$romsoundside->sElbowSupProgP}}' },
                                    { text: '{{$romsoundside->sElbowSupProgA}}' },
                                    { text: '{{$romsoundside->sElbowSupFinP}}' },
                                    { text: '{{$romsoundside->sElbowSupFinA}}' },
                                ],
                                [
                                    { text: 'WRIST' },
                                    { text: 'FLEXION' },
                                    { text: '{{$romsoundside->sWristFlxInitP}}' },
                                    { text: '{{$romsoundside->sWristFlxInitA}}' },
                                    { text: '{{$romsoundside->sWristFlxProgP}}' },
                                    { text: '{{$romsoundside->sWristFlxProgA}}' },
                                    { text: '{{$romsoundside->sWristFlxFinP}}' },
                                    { text: '{{$romsoundside->sWristFlxFinA}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'EXTENSION' },
                                    { text: '{{$romsoundside->sWristExtInitP}}' },
                                    { text: '{{$romsoundside->sWristExtInitA}}' },
                                    { text: '{{$romsoundside->sWristExtProgP}}' },
                                    { text: '{{$romsoundside->sWristExtProgA}}' },
                                    { text: '{{$romsoundside->sWristExtFinP}}' },
                                    { text: '{{$romsoundside->sWristExtFinA}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'RADIAL DEVIATION' },
                                    { text: '{{$romsoundside->sWristRadInitP}}' },
                                    { text: '{{$romsoundside->sWristRadInitA}}' },
                                    { text: '{{$romsoundside->sWristRadProgP}}' },
                                    { text: '{{$romsoundside->sWristRadProgA}}' },
                                    { text: '{{$romsoundside->sWristRadFinP}}' },
                                    { text: '{{$romsoundside->sWristRadFinA}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'ULNAR DEVIATION' },
                                    { text: '{{$romsoundside->sWristUlnarInitP}}' },
                                    { text: '{{$romsoundside->sWristUlnarInitA}}' },
                                    { text: '{{$romsoundside->sWristUlnarProgP}}' },
                                    { text: '{{$romsoundside->sWristUlnarProgA}}' },
                                    { text: '{{$romsoundside->sWristUlnarFinP}}' },
                                    { text: '{{$romsoundside->sWristUlnarFinA}}' },
                                ],
                                [
                                    { text: 'HIP' },
                                    { text: 'FLEXION' },
                                    { text: '{{$romsoundside->sHipFlxInitP}}' },
                                    { text: '{{$romsoundside->sHipFlxInitA}}' },
                                    { text: '{{$romsoundside->sHipFlxProgP}}' },
                                    { text: '{{$romsoundside->sHipFlxProgA}}' },
                                    { text: '{{$romsoundside->sHipFlxFinP}}' },
                                    { text: '{{$romsoundside->sHipFlxFinA}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'EXTENSION' },
                                    { text: '{{$romsoundside->sHipExtInitP}}' },
                                    { text: '{{$romsoundside->sHipExtInitA}}' },
                                    { text: '{{$romsoundside->sHipExtProgP}}' },
                                    { text: '{{$romsoundside->sHipExtProgA}}' },
                                    { text: '{{$romsoundside->sHipExtFinP}}' },
                                    { text: '{{$romsoundside->sHipExtFinA}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'ABDUCTION' },
                                    { text: '{{$romsoundside->sHipAbdInitP}}' },
                                    { text: '{{$romsoundside->sHipAbdInitA}}' },
                                    { text: '{{$romsoundside->sHipAbdProgP}}' },
                                    { text: '{{$romsoundside->sHipAbdProgA}}' },
                                    { text: '{{$romsoundside->sHipAbdFinP}}' },
                                    { text: '{{$romsoundside->sHipAbdFinA}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'ADDUCTION' },
                                    { text: '{{$romsoundside->sHipAddInitP}}' },
                                    { text: '{{$romsoundside->sHipAddInitA}}' },
                                    { text: '{{$romsoundside->sHipAddProgP}}' },
                                    { text: '{{$romsoundside->sHipAddProgA}}' },
                                    { text: '{{$romsoundside->sHipAddFinP}}' },
                                    { text: '{{$romsoundside->sHipAddFinA}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'INT. ROT' },
                                    { text: '{{$romsoundside->sHipIntRotInitP}}' },
                                    { text: '{{$romsoundside->sHipIntRotInitA}}' },
                                    { text: '{{$romsoundside->sHipIntRotProgP}}' },
                                    { text: '{{$romsoundside->sHipIntRotProgA}}' },
                                    { text: '{{$romsoundside->sHipIntRotFinP}}' },
                                    { text: '{{$romsoundside->sHipIntRotFinA}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'EXT. ROT' },
                                    { text: '{{$romsoundside->sHipExtRotInitP}}' },
                                    { text: '{{$romsoundside->sHipExtRotInitA}}' },
                                    { text: '{{$romsoundside->sHipExtRotProgP}}' },
                                    { text: '{{$romsoundside->sHipExtRotProgA}}' },
                                    { text: '{{$romsoundside->sHipExtRotFinP}}' },
                                    { text: '{{$romsoundside->sHipExtRotFinA}}' },
                                ],
                                [
                                    { text: 'KNEE' },
                                    { text: 'EXTENSION' },
                                    { text: '{{$romsoundside->sKneeExtInitP}}' },
                                    { text: '{{$romsoundside->sKneeExtInitA}}' },
                                    { text: '{{$romsoundside->sKneeExtProgP}}' },
                                    { text: '{{$romsoundside->sKneeExtProgA}}' },
                                    { text: '{{$romsoundside->sKneeExtFinP}}' },
                                    { text: '{{$romsoundside->sKneeExtFinA}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'FLEXION' },
                                    { text: '{{$romsoundside->sKneeFlxInitP}}' },
                                    { text: '{{$romsoundside->sKneeFlxInitA}}' },
                                    { text: '{{$romsoundside->sKneeFlxProgP}}' },
                                    { text: '{{$romsoundside->sKneeFlxProgA}}' },
                                    { text: '{{$romsoundside->sKneeFlxFinP}}' },
                                    { text: '{{$romsoundside->sKneeFlxFinA}}' },
                                ],
                                [
                                    { text: 'ANKLE' },
                                    { text: 'DORSIFLEXION' },
                                    { text: '{{$romsoundside->sAnkleDorsInitP}}' },
                                    { text: '{{$romsoundside->sAnkleDorsInitA}}' },
                                    { text: '{{$romsoundside->sAnkleDorsProgP}}' },
                                    { text: '{{$romsoundside->sAnkleDorsProgA}}' },
                                    { text: '{{$romsoundside->sAnkleDorsFinP}}' },
                                    { text: '{{$romsoundside->sAnkleDorsFinA}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'PLANTARFLEXION' },
                                    { text: '{{$romsoundside->sAnklePtarInitP}}' },
                                    { text: '{{$romsoundside->sAnklePtarInitA}}' },
                                    { text: '{{$romsoundside->sAnklePtarProgP}}' },
                                    { text: '{{$romsoundside->sAnklePtarProgA}}' },
                                    { text: '{{$romsoundside->sAnklePtarFinP}}' },
                                    { text: '{{$romsoundside->sAnklePtarFinA}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'EVERSION' },
                                    { text: '{{$romsoundside->sAnkleEverInitP}}' },
                                    { text: '{{$romsoundside->sAnkleEverInitA}}' },
                                    { text: '{{$romsoundside->sAnkleEverProgP}}' },
                                    { text: '{{$romsoundside->sAnkleEverProgA}}' },
                                    { text: '{{$romsoundside->sAnkleEverFinP}}' },
                                    { text: '{{$romsoundside->sAnkleEverFinA}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'INVERSION' },
                                    { text: '{{$romsoundside->sAnkleInverInitP}}' },
                                    { text: '{{$romsoundside->sAnkleInverInitA}}' },
                                    { text: '{{$romsoundside->sAnkleInverProgP}}' },
                                    { text: '{{$romsoundside->sAnkleInverProgA}}' },
                                    { text: '{{$romsoundside->sAnkleInverFinP}}' },
                                    { text: '{{$romsoundside->sAnkleInverFinA}}' },
                                ],
                            ]
                        },
                        layout: {
                            fillColor: function (rowIndex, node, columnIndex){
                                // return (rowIndex === 0) ? '#000000' : null;
                            }
                        },
                    },
                    {
                        style: 'tableExample1',
                        table: {
                            headerRows: 1,
                            widths: [60,'*'], // panjang standard dia 515
                            body: [
                                [
                                    { text: 'Impression:' },
                                    { text: `{!!$romsoundside->impressionROM!!}` },
                                ],
                            ]
                        },
                        layout: 'noBorders',
                    },
                    { text: '4) MUSCLE TONE (MAS): ', fontSize: 9 },
                    {
                        text: [
                            { text: 'UL: {{$neuroassessment->muscleUL}}\n' },
                            { text: 'LL: {{$neuroassessment->muscleLL}}' },
                        ],
                        style: 'tableExample1',
                    },
                    {
                        style: 'tableExample1',
                        table: {
                            headerRows: 1,
                            widths: [60,'*'], // panjang standard dia 515
                            body: [
                                [
                                    { text: 'Impression:' },
                                    { text: `{!!$neuroassessment->impressionMAS!!}` },
                                ],
                            ]
                        },
                        layout: 'noBorders',
                    },
                    { text: '5) DEEP TENDON REFLEX: ', fontSize: 9 },
                    {
                        style: 'tableExample1',
                        table: {
                            headerRows: 1,
                            widths: [80,80,80],
                            body: [
                                [
                                    { text: ' ', style: 'tableHeader' },
                                    { text: 'RT', style: 'tableHeader', alignment: 'center' },
                                    { text: 'LT', style: 'tableHeader', alignment: 'center' },
                                ],
                                [
                                    { text: 'BTR' },
                                    @if($neuroassessment->btrRT == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    @if($neuroassessment->btrLT == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                ],
                                [
                                    { text: 'TTR' },
                                    @if($neuroassessment->ttrRT == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    @if($neuroassessment->ttrLT == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                ],
                                [
                                    { text: 'KTR' },
                                    @if($neuroassessment->ktrRT == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    @if($neuroassessment->ktrLT == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                ],
                                [
                                    { text: 'ATR' },
                                    @if($neuroassessment->atrRT == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    @if($neuroassessment->atrLT == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                ],
                                [
                                    { text: 'Babinsky' },
                                    @if($neuroassessment->babinskyRT == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    @if($neuroassessment->babinskyLT == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                ],
                            ]
                        },
                        layout: {
                            fillColor: function (rowIndex, node, columnIndex){
                                // return (rowIndex === 0) ? '#000000' : null;
                            }
                        },
                    },
                    {
                        style: 'tableExample1',
                        table: {
                            headerRows: 1,
                            widths: [60,'*'], // panjang standard dia 515
                            body: [
                                [
                                    { text: 'Impression:' },
                                    { text: `{!!$neuroassessment->impressionDTR!!}` },
                                ],
                            ]
                        },
                        layout: 'noBorders',
                    },
                    {
                        text: [
                            { text: '6) MUSCLE POWER\n\n', fontSize: 9 },
                            { text: '\u200B\t\u200B\ta) Affected Side', fontSize: 9 },
                            @if($musclepower->affectedSide == 'R')
                                { text: '\u200B\t[ √ ] R \u200B\t [\u200B\t] L', fontSize: 9 },
                            @elseif($musclepower->affectedSide == 'L')
                                { text: '\u200B\t[\u200B\t] R \u200B\t [ √ ] L', fontSize: 9 },
                            @else
                                { text: '\u200B\t[\u200B\t] R \u200B\t [\u200B\t] L', fontSize: 9 },
                            @endif
                        ],
                    },
                    {
                        style: 'tableExample1',
                        table: {
                            headerRows: 1,
                            widths: [60,80,100,100,100],
                            body: [
                                [
                                    { text: 'JOINT ', style: 'tableHeader', alignment: 'left' },
                                    { text: 'MOVEMENT', style: 'tableHeader', alignment: 'left' },
                                    { text: 'INITIAL', style: 'tableHeader', alignment: 'left' },
                                    { text: 'PROGRESS', style: 'tableHeader', alignment: 'left' },
                                    { text: 'FINAL', style: 'tableHeader', alignment: 'left' },
                                ],
                                [
                                    { text: 'SHOULDER' },
                                    { text: 'FLEXION' },
                                    { text: '{{$musclepower->aShlderFlxInit}}' },
                                    { text: '{{$musclepower->aShlderFlxProg}}' },
                                    { text: '{{$musclepower->aShlderFlxFin}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'EXTENSION' },
                                    { text: '{{$musclepower->aShlderExtInit}}' },
                                    { text: '{{$musclepower->aShlderExtProg}}' },
                                    { text: '{{$musclepower->aShlderExtFin}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'ABDUCTION' },
                                    { text: '{{$musclepower->aShlderAbdInit}}' },
                                    { text: '{{$musclepower->aShlderAbdProg}}' },
                                    { text: '{{$musclepower->aShlderAbdFin}}' },
                                ],
                                [
                                    { text: 'ELBOW' },
                                    { text: 'FLEXION' },
                                    { text: '{{$musclepower->aElbowFlxInit}}' },
                                    { text: '{{$musclepower->aElbowFlxProg}}' },
                                    { text: '{{$musclepower->aElbowFlxFin}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'EXTENSION' },
                                    { text: '{{$musclepower->aElbowExtInit}}' },
                                    { text: '{{$musclepower->aElbowExtProg}}' },
                                    { text: '{{$musclepower->aElbowExtFin}}' },
                                ],
                                [
                                    { text: 'WRIST' },
                                    { text: 'FLEXION' },
                                    { text: '{{$musclepower->aWristFlxInit}}' },
                                    { text: '{{$musclepower->aWristFlxProg}}' },
                                    { text: '{{$musclepower->aWristFlxFin}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'EXTENSION' },
                                    { text: '{{$musclepower->aWristExtInit}}' },
                                    { text: '{{$musclepower->aWristExtProg}}' },
                                    { text: '{{$musclepower->aWristExtFin}}' },
                                ],
                                [
                                    { text: 'HIP' },
                                    { text: 'FLEXION' },
                                    { text: '{{$musclepower->aHipFlxInit}}' },
                                    { text: '{{$musclepower->aHipFlxProg}}' },
                                    { text: '{{$musclepower->aHipFlxFin}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'EXTENSION' },
                                    { text: '{{$musclepower->aHipExtInit}}' },
                                    { text: '{{$musclepower->aHipExtProg}}' },
                                    { text: '{{$musclepower->aHipExtFin}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'ABDUCTION' },
                                    { text: '{{$musclepower->aHipAbdInit}}' },
                                    { text: '{{$musclepower->aHipAbdProg}}' },
                                    { text: '{{$musclepower->aHipAbdFin}}' },
                                ],
                                [
                                    { text: 'KNEE' },
                                    { text: 'EXTENSION' },
                                    { text: '{{$musclepower->aKneeExtInit}}' },
                                    { text: '{{$musclepower->aKneeExtProg}}' },
                                    { text: '{{$musclepower->aKneeExtFin}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'FLEXION' },
                                    { text: '{{$musclepower->aKneeFlxInit}}' },
                                    { text: '{{$musclepower->aKneeFlxProg}}' },
                                    { text: '{{$musclepower->aKneeFlxFin}}' },
                                ],
                                [
                                    { text: 'ANKLE' },
                                    { text: 'DORSIFLEXION' },
                                    { text: '{{$musclepower->aAnkleDorsInit}}' },
                                    { text: '{{$musclepower->aAnkleDorsProg}}' },
                                    { text: '{{$musclepower->aAnkleDorsFin}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'PLANTARFLEXION' },
                                    { text: '{{$musclepower->aAnklePtarInit}}' },
                                    { text: '{{$musclepower->aAnklePtarProg}}' },
                                    { text: '{{$musclepower->aAnklePtarFin}}' },
                                ],
                            ]
                        },
                        layout: {
                            fillColor: function (rowIndex, node, columnIndex){
                                // return (rowIndex === 0) ? '#000000' : null;
                            }
                        },
                    },
                    {
                        text: [
                            { text: '\u200B\t\u200B\tb) Sound Side', fontSize: 9 },
                            @if($musclepower->soundSide == 'R')
                                { text: '\u200B\t[ √ ] R \u200B\t [\u200B\t] L', fontSize: 9 },
                            @elseif($musclepower->soundSide == 'L')
                                { text: '\u200B\t[\u200B\t] R \u200B\t [ √ ] L', fontSize: 9 },
                            @else
                                { text: '\u200B\t[\u200B\t] R \u200B\t [\u200B\t] L', fontSize: 9 },
                            @endif
                        ],
                    },
                    {
                        style: 'tableExample1',
                        table: {
                            headerRows: 1,
                            widths: [60,80,100,100,100],
                            body: [
                                [
                                    { text: 'JOINT ', style: 'tableHeader', alignment: 'left' },
                                    { text: 'MOVEMENT', style: 'tableHeader', alignment: 'left' },
                                    { text: 'INITIAL', style: 'tableHeader', alignment: 'left' },
                                    { text: 'PROGRESS', style: 'tableHeader', alignment: 'left' },
                                    { text: 'FINAL', style: 'tableHeader', alignment: 'left' },
                                ],
                                [
                                    { text: 'SHOULDER' },
                                    { text: 'FLEXION' },
                                    { text: '{{$musclepower->sShlderFlxInit}}' },
                                    { text: '{{$musclepower->sShlderFlxProg}}' },
                                    { text: '{{$musclepower->sShlderFlxFin}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'EXTENSION' },
                                    { text: '{{$musclepower->sShlderExtInit}}' },
                                    { text: '{{$musclepower->sShlderExtProg}}' },
                                    { text: '{{$musclepower->sShlderExtFin}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'ABDUCTION' },
                                    { text: '{{$musclepower->sShlderAbdInit}}' },
                                    { text: '{{$musclepower->sShlderAbdProg}}' },
                                    { text: '{{$musclepower->sShlderAbdFin}}' },
                                ],
                                [
                                    { text: 'ELBOW' },
                                    { text: 'FLEXION' },
                                    { text: '{{$musclepower->sElbowFlxInit}}' },
                                    { text: '{{$musclepower->sElbowFlxProg}}' },
                                    { text: '{{$musclepower->sElbowFlxFin}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'EXTENSION' },
                                    { text: '{{$musclepower->sElbowExtInit}}' },
                                    { text: '{{$musclepower->sElbowExtProg}}' },
                                    { text: '{{$musclepower->sElbowExtFin}}' },
                                ],
                                [
                                    { text: 'WRIST' },
                                    { text: 'FLEXION' },
                                    { text: '{{$musclepower->sWristFlxInit}}' },
                                    { text: '{{$musclepower->sWristFlxProg}}' },
                                    { text: '{{$musclepower->sWristFlxFin}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'EXTENSION' },
                                    { text: '{{$musclepower->sWristExtInit}}' },
                                    { text: '{{$musclepower->sWristExtProg}}' },
                                    { text: '{{$musclepower->sWristExtFin}}' },
                                ],
                                [
                                    { text: 'HIP' },
                                    { text: 'FLEXION' },
                                    { text: '{{$musclepower->sHipFlxInit}}' },
                                    { text: '{{$musclepower->sHipFlxProg}}' },
                                    { text: '{{$musclepower->sHipFlxFin}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'EXTENSION' },
                                    { text: '{{$musclepower->sHipExtInit}}' },
                                    { text: '{{$musclepower->sHipExtProg}}' },
                                    { text: '{{$musclepower->sHipExtFin}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'ABDUCTION' },
                                    { text: '{{$musclepower->sHipAbdInit}}' },
                                    { text: '{{$musclepower->sHipAbdProg}}' },
                                    { text: '{{$musclepower->sHipAbdFin}}' },
                                ],
                                [
                                    { text: 'KNEE' },
                                    { text: 'EXTENSION' },
                                    { text: '{{$musclepower->sKneeExtInit}}' },
                                    { text: '{{$musclepower->sKneeExtProg}}' },
                                    { text: '{{$musclepower->sKneeExtFin}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'FLEXION' },
                                    { text: '{{$musclepower->sKneeFlxInit}}' },
                                    { text: '{{$musclepower->sKneeFlxProg}}' },
                                    { text: '{{$musclepower->sKneeFlxFin}}' },
                                ],
                                [
                                    { text: 'ANKLE' },
                                    { text: 'DORSIFLEXION' },
                                    { text: '{{$musclepower->sAnkleDorsInit}}' },
                                    { text: '{{$musclepower->sAnkleDorsProg}}' },
                                    { text: '{{$musclepower->sAnkleDorsFin}}' },
                                ],
                                [
                                    { text: '' },
                                    { text: 'PLANTARFLEXION' },
                                    { text: '{{$musclepower->sAnklePtarInit}}' },
                                    { text: '{{$musclepower->sAnklePtarProg}}' },
                                    { text: '{{$musclepower->sAnklePtarFin}}' },
                                ],
                            ]
                        },
                        layout: {
                            fillColor: function (rowIndex, node, columnIndex){
                                // return (rowIndex === 0) ? '#000000' : null;
                            }
                        },
                    },
                    {
                        style: 'tableExample1',
                        table: {
                            headerRows: 1,
                            widths: [60,'*'], // panjang standard dia 515
                            body: [
                                [
                                    { text: 'Impression:' },
                                    { text: `{!!$musclepower->impressionSMP!!}` },
                                ],
                            ]
                        },
                        layout: 'noBorders',
                    },
                    { text: '7) COORDINATION: ', fontSize: 9 },
                    {
                        style: 'tableExample1',
                        table: {
                            headerRows: 1,
                            widths: [80,140,140],
                            body: [
                                [
                                    { text: ' ', style: 'tableHeader' },
                                    { text: 'R', style: 'tableHeader', alignment: 'center' },
                                    { text: 'L', style: 'tableHeader', alignment: 'center' },
                                ],
                                [
                                    { text: 'Finger Nose Test' },
                                    @if($neuroassessment->fingerTestR == 'Poor')
                                        { text: '[ √ ] Poor \u200B\t [\u200B\t] Fair \u200B\t [\u200B\t] Good' },
                                    @elseif($neuroassessment->fingerTestR == 'Fair')
                                        { text: '[\u200B\t] Poor \u200B\t [ √ ] Fair \u200B\t [\u200B\t] Good' },
                                    @elseif($neuroassessment->fingerTestR == 'Good')
                                        { text: '[\u200B\t] Poor \u200B\t [\u200B\t] Fair \u200B\t [ √ ] Good' },
                                    @else
                                        { text: '[\u200B\t] Poor \u200B\t [\u200B\t] Fair \u200B\t [\u200B\t] Good' },
                                    @endif
                                    @if($neuroassessment->fingerTestL == 'Poor')
                                        { text: '[ √ ] Poor \u200B\t [\u200B\t] Fair \u200B\t [\u200B\t] Good' },
                                    @elseif($neuroassessment->fingerTestL == 'Fair')
                                        { text: '[\u200B\t] Poor \u200B\t [ √ ] Fair \u200B\t [\u200B\t] Good' },
                                    @elseif($neuroassessment->fingerTestL == 'Good')
                                        { text: '[\u200B\t] Poor \u200B\t [\u200B\t] Fair \u200B\t [ √ ] Good' },
                                    @else
                                        { text: '[\u200B\t] Poor \u200B\t [\u200B\t] Fair \u200B\t [\u200B\t] Good' },
                                    @endif
                                ],
                                [
                                    { text: 'Heel Shin Test' },
                                    @if($neuroassessment->heelTestR == 'Poor')
                                        { text: '[ √ ] Poor \u200B\t [\u200B\t] Fair \u200B\t [\u200B\t] Good' },
                                    @elseif($neuroassessment->heelTestR == 'Fair')
                                        { text: '[\u200B\t] Poor \u200B\t [ √ ] Fair \u200B\t [\u200B\t] Good' },
                                    @elseif($neuroassessment->heelTestR == 'Good')
                                        { text: '[\u200B\t] Poor \u200B\t [\u200B\t] Fair \u200B\t [ √ ] Good' },
                                    @else
                                        { text: '[\u200B\t] Poor \u200B\t [\u200B\t] Fair \u200B\t [\u200B\t] Good' },
                                    @endif
                                    @if($neuroassessment->heelTestL == 'Poor')
                                        { text: '[ √ ] Poor \u200B\t [\u200B\t] Fair \u200B\t [\u200B\t] Good' },
                                    @elseif($neuroassessment->heelTestL == 'Fair')
                                        { text: '[\u200B\t] Poor \u200B\t [ √ ] Fair \u200B\t [\u200B\t] Good' },
                                    @elseif($neuroassessment->heelTestL == 'Good')
                                        { text: '[\u200B\t] Poor \u200B\t [\u200B\t] Fair \u200B\t [ √ ] Good' },
                                    @else
                                        { text: '[\u200B\t] Poor \u200B\t [\u200B\t] Fair \u200B\t [\u200B\t] Good' },
                                    @endif
                                ],
                            ]
                        },
                        layout: {
                            fillColor: function (rowIndex, node, columnIndex){
                                // return (rowIndex === 0) ? '#000000' : null;
                            }
                        },
                    },
                    {
                        style: 'tableExample1',
                        table: {
                            headerRows: 1,
                            widths: [60,'*'], // panjang standard dia 515
                            body: [
                                [
                                    { text: 'Impression:' },
                                    { text: `{!!$neuroassessment->impressionCoord!!}` },
                                ],
                            ]
                        },
                        layout: 'noBorders',
                    },
                    { text: '8) FUNCTIONAL ACTIVITY: ', fontSize: 9 },
                    {
                        style: 'tableExample1',
                        table: {
                            headerRows: 1,
                            widths: [20,80,80,80,80],
                            body: [
                                [
                                    { text: 'NO ', style: 'tableHeader' },
                                    { text: 'ACTIVITY', style: 'tableHeader', alignment: 'left' },
                                    { text: 'INTIAL', style: 'tableHeader', alignment: 'left' },
                                    { text: 'PROGRESS', style: 'tableHeader', alignment: 'left' },
                                    { text: 'FINAL', style: 'tableHeader', alignment: 'left' },
                                ],
                                [
                                    { text: '1' },
                                    { text: 'Transfer' },
                                    { text: '{{$neuroassessment->transferInit}}', alignment: 'left' },
                                    { text: '{{$neuroassessment->transferProg}}', alignment: 'left' },
                                    { text: '{{$neuroassessment->transferFin}}', alignment: 'left' },
                                ],
                                [
                                    { text: '2' },
                                    { text: 'Supto Side Ly.' },
                                    { text: '{{$neuroassessment->suptoSideInit}}', alignment: 'left' },
                                    { text: '{{$neuroassessment->suptoSideProg}}', alignment: 'left' },
                                    { text: '{{$neuroassessment->suptoSideFin}}', alignment: 'left' },
                                ],
                                [
                                    { text: '3' },
                                    { text: 'Side Ly. To Sitt' },
                                    { text: '{{$neuroassessment->sideToSitInit}}', alignment: 'left' },
                                    { text: '{{$neuroassessment->sideToSitProg}}', alignment: 'left' },
                                    { text: '{{$neuroassessment->sideToSitFin}}', alignment: 'left' },
                                ],
                                [
                                    { text: '4' },
                                    { text: 'Sitt' },
                                    { text: '{{$neuroassessment->sittInit}}', alignment: 'left' },
                                    { text: '{{$neuroassessment->sittProg}}', alignment: 'left' },
                                    { text: '{{$neuroassessment->sittFin}}', alignment: 'left' },
                                ],
                                [
                                    { text: '5' },
                                    { text: 'Sitt To Std' },
                                    { text: '{{$neuroassessment->sitToStdInit}}', alignment: 'left' },
                                    { text: '{{$neuroassessment->sitToStdProg}}', alignment: 'left' },
                                    { text: '{{$neuroassessment->sitToStdFin}}', alignment: 'left' },
                                ],
                                [
                                    { text: '6' },
                                    { text: 'Std' },
                                    { text: '{{$neuroassessment->stdInit}}', alignment: 'left' },
                                    { text: '{{$neuroassessment->stdProg}}', alignment: 'left' },
                                    { text: '{{$neuroassessment->stdFin}}', alignment: 'left' },
                                ],
                                [
                                    { text: '7' },
                                    { text: 'Shifting Ability' },
                                    { text: '{{$neuroassessment->shiftInit}}', alignment: 'left' },
                                    { text: '{{$neuroassessment->shiftProg}}', alignment: 'left' },
                                    { text: '{{$neuroassessment->shiftFin}}', alignment: 'left' },
                                ],
                                [
                                    { text: '8' },
                                    { text: 'Ambulation' },
                                    { text: '{{$neuroassessment->ambulationInit}}', alignment: 'left' },
                                    { text: '{{$neuroassessment->ambulationProg}}', alignment: 'left' },
                                    { text: '{{$neuroassessment->ambulationFin}}', alignment: 'left' },
                                ],
                            ]
                        },
                        layout: {
                            fillColor: function (rowIndex, node, columnIndex){
                                // return (rowIndex === 0) ? '#000000' : null;
                            }
                        },
                    },
                    {
                        style: 'tableExample1',
                        table: {
                            headerRows: 1,
                            widths: [60,'*'], // panjang standard dia 515
                            body: [
                                [
                                    { text: 'Impression:' },
                                    { text: `{!!$neuroassessment->impressionFA!!}` },
                                ],
                            ]
                        },
                        layout: 'noBorders',
                    },
                    {
                        style: 'tableExample1',
                        table: {
                            headerRows: 1,
                            widths: [60,'*'], // panjang standard dia 515
                            body: [
                                [
                                    { text: '\n\nSUMMARY:' },
                                    { text: `\n\n{!!$neuroassessment->summary!!}` },
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
                    subheader: {
                        fontSize: 12,
                        bold: true,
                        margin: [0, 10, 0, 5]
                    },
                    tableExample: {
                        fontSize: 9,
                        margin: [0, 5, 0, 15]
                    },
                    tableExample1: {
                        fontSize: 9,
                        margin: [15, 5, 0, 10]
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
                <p>Neurological Physiotherapy Assessment</p>
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