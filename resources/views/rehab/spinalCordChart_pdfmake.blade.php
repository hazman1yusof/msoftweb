<!DOCTYPE html>
<html>
    <head>
        <title>Spinal Cord Injury</title>
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
                // footer: function (currentPage, pageCount){
                //     return [
                //         { text: currentPage.toString() + ' of ' + pageCount, alignment: 'center' }
                //     ]
                // },
                pageSize: 'A4',
                pageOrientation: 'landscape',
                content: [
                    // {
                    //     image: 'letterhead', width: 500, style: 'tableHeader', colSpan: 5, alignment: 'center'
                    // },
                    {
                        text: 'ASIA A\nSPINAL CORD INJURY\n',
                        style: 'header',
                        alignment: 'center',
                    },
                    // { canvas: [ { type: 'line', x1: 0, y1: 5, x2: 515, y2: 5, lineWidth: 0.5 } ] },
                    {
                        alignment: 'justify',
                        columns: [
                            {
                                // Left table
                                width: 'auto', // Or a fixed width like 200
                                style: 'tableLeft',
                                table: {
                                    widths: [53,105,35,35,35,160],
                                    // headerRows: 1,
                                    body: [
                                        [
                                            { text: 'RIGHT', bold: true, fontSize: 12, alignment: 'center', rowSpan: 2, border: [false, false, false, false] },
                                            {
                                                text: [
                                                    { text: 'MOTOR\n', bold: true },
                                                    { text: 'KEY MUSCLES', bold: true, fontSize: 6 },
                                                ], alignment: 'center', colSpan: 2, border: [false, false, false, false]
                                            },
                                            { text: '' },
                                            {
                                                text: [
                                                    { text: 'SENSORY\n', bold: true },
                                                    { text: 'KEY SENSORY POINTS', bold: true, fontSize: 6 },
                                                ], alignment: 'center', colSpan: 2, border: [false, false, false, false]
                                            },
                                            { text: '' },
                                            {
                                                image: 'spinalcord',
                                                width: 150,
                                                rowSpan: 30,
                                                border: [false, false, false, false]
                                            },
                                        ],
                                        [
                                            { text: '' },
                                            { text: '', border: [false, false, false, false] },
                                            { text: '', border: [false, false, false, false] },
                                            { text: 'Light Touch (LTR)', bold: true, fontSize: 6, alignment: 'center', border: [false, false, false, false] },
                                            { text: 'Pin Prick (PPR)', bold: true, fontSize: 6, alignment: 'center', border: [false, false, false, false] },
                                            { text: '' },
                                        ],
                                        [
                                            { text: '', border: [false, false, false, false] },
                                            { text: '', border: [false, false, false, false] },
                                            { text: 'C2', bold: true, alignment: 'right', border: [false, false, false, false] },
                                            @if($spinalcord->ltrC2 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pprC2 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            { text: '' },
                                        ],
                                        [
                                            { text: '', border: [false, false, false, false] },
                                            { text: '', border: [false, false, false, false] },
                                            { text: 'C3', bold: true, alignment: 'right', border: [false, false, false, false] },
                                            @if($spinalcord->ltrC3 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pprC3 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            { text: '' },
                                        ],
                                        [
                                            { text: '', border: [false, false, false, false] },
                                            { text: '', border: [false, false, false, false] },
                                            { text: 'C4', bold: true, alignment: 'right', border: [false, false, false, false] },
                                            @if($spinalcord->ltrC4 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pprC4 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            { text: '' },
                                        ],
                                        [
                                            {
                                                text: [
                                                    { text: 'UER\n', bold: true },
                                                    { text: '(Upper Extremity Right)', bold: true, fontSize: 6 },
                                                ], alignment: 'center', rowSpan: 5, border: [false, false, false, false], fillColor: '#dddddd'
                                            },
                                            {
                                                text: [
                                                    { text: 'Elbow flexors', italics: true, fontSize: 7 },
                                                    { text: '\u200B\t C5', bold: true },
                                                ], alignment: 'right', border: [false, false, false, false], fillColor: '#dddddd'
                                            },
                                            @if($spinalcord->motorRC5 == '1')
                                                { text: '√', alignment: 'center', fillColor: '#dddddd' },
                                            @else
                                                { text: '', fillColor: '#dddddd' },
                                            @endif
                                            @if($spinalcord->ltrC5 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pprC5 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            { text: '' },
                                        ],
                                        [
                                            { text: '' },
                                            {
                                                text: [
                                                    { text: 'Wrist extensors', italics: true, fontSize: 7 },
                                                    { text: '\u200B\t C6', bold: true },
                                                ], alignment: 'right', border: [false, false, false, false], fillColor: '#dddddd'
                                            },
                                            @if($spinalcord->motorRC6 == '1')
                                                { text: '√', alignment: 'center', fillColor: '#dddddd' },
                                            @else
                                                { text: '', fillColor: '#dddddd' },
                                            @endif
                                            @if($spinalcord->ltrC6 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pprC6 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            { text: '' },
                                        ],
                                        [
                                            { text: '' },
                                            {
                                                text: [
                                                    { text: 'Elbow extensors', italics: true, fontSize: 7 },
                                                    { text: '\u200B\t C7', bold: true },
                                                ], alignment: 'right', border: [false, false, false, false], fillColor: '#dddddd'
                                            },
                                            @if($spinalcord->motorRC7 == '1')
                                                { text: '√', alignment: 'center', fillColor: '#dddddd' },
                                            @else
                                                { text: '', fillColor: '#dddddd' },
                                            @endif
                                            @if($spinalcord->ltrC7 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pprC7 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            { text: '' },
                                        ],
                                        [
                                            { text: '' },
                                            {
                                                text: [
                                                    { text: 'Finger flexors', italics: true, fontSize: 7 },
                                                    { text: '\u200B\t C8', bold: true },
                                                ], alignment: 'right', border: [false, false, false, false], fillColor: '#dddddd'
                                            },
                                            @if($spinalcord->motorRC8 == '1')
                                                { text: '√', alignment: 'center', fillColor: '#dddddd' },
                                            @else
                                                { text: '', fillColor: '#dddddd' },
                                            @endif
                                            @if($spinalcord->ltrC8 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pprC8 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            { text: '' },
                                        ],
                                        [
                                            { text: '' },
                                            {
                                                text: [
                                                    { text: 'Finger abductors', italics: true, fontSize: 7 },
                                                    { text: ' (little finger)', italics: true, fontSize: 6 },
                                                    { text: '\u200B\t T1', bold: true },
                                                ], alignment: 'right', border: [false, false, false, false], fillColor: '#dddddd'
                                            },
                                            @if($spinalcord->motorRT1 == '1')
                                                { text: '√', alignment: 'center', fillColor: '#dddddd' },
                                            @else
                                                { text: '', fillColor: '#dddddd' },
                                            @endif
                                            @if($spinalcord->ltrT1 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pprT1 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            { text: '' },
                                        ],
                                        [
                                            {
                                                text: [
                                                    { text: 'Comments ', bold: true, italics: true },
                                                    { text: ' (Non-key Muscle? Reason for NT? Pain?):', italics: true },
                                                    { text: `{!!$spinalcord->comments!!}` },
                                                ], colSpan: 2, rowSpan: 12
                                            },
                                            { text: '' },
                                            { text: 'T2', bold: true, alignment: 'right', border: [false, false, false, false] },
                                            @if($spinalcord->ltrT2 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pprT2 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            { text: '' },
                                        ],
                                        [
                                            { text: '' },
                                            { text: '' },
                                            { text: 'T3', bold: true, alignment: 'right', border: [false, false, false, false] },
                                            @if($spinalcord->ltrT3 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pprT3 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            { text: '' },
                                        ],
                                        [
                                            { text: '' },
                                            { text: '' },
                                            { text: 'T4', bold: true, alignment: 'right', border: [false, false, false, false] },
                                            @if($spinalcord->ltrT4 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pprT4 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            { text: '' },
                                        ],
                                        [
                                            { text: '' },
                                            { text: '' },
                                            { text: 'T5', bold: true, alignment: 'right', border: [false, false, false, false] },
                                            @if($spinalcord->ltrT5 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pprT5 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            { text: '' },
                                        ],
                                        [
                                            { text: '' },
                                            { text: '' },
                                            { text: 'T6', bold: true, alignment: 'right', border: [false, false, false, false] },
                                            @if($spinalcord->ltrT6 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pprT6 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            { text: '' },
                                        ],
                                        [
                                            { text: '' },
                                            { text: '' },
                                            { text: 'T7', bold: true, alignment: 'right', border: [false, false, false, false] },
                                            @if($spinalcord->ltrT7 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pprT7 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            { text: '' },
                                        ],
                                        [
                                            { text: '' },
                                            { text: '' },
                                            { text: 'T8', bold: true, alignment: 'right', border: [false, false, false, false] },
                                            @if($spinalcord->ltrT8 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pprT8 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            { text: '' },
                                        ],
                                        [
                                            { text: '' },
                                            { text: '' },
                                            { text: 'T9', bold: true, alignment: 'right', border: [false, false, false, false] },
                                            @if($spinalcord->ltrT9 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pprT9 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            { text: '' },
                                        ],
                                        [
                                            { text: '' },
                                            { text: '' },
                                            { text: 'T10', bold: true, alignment: 'right', border: [false, false, false, false] },
                                            @if($spinalcord->ltrT10 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pprT10 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            { text: '' },
                                        ],
                                        [
                                            { text: '' },
                                            { text: '' },
                                            { text: 'T11', bold: true, alignment: 'right', border: [false, false, false, false] },
                                            @if($spinalcord->ltrT11 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pprT11 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            { text: '' },
                                        ],
                                        [
                                            { text: '' },
                                            { text: '' },
                                            { text: 'T12', bold: true, alignment: 'right', border: [false, false, false, false] },
                                            @if($spinalcord->ltrT12 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pprT12 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            { text: '' },
                                        ],
                                        [
                                            { text: '' },
                                            { text: '' },
                                            { text: 'L1', bold: true, alignment: 'right', border: [false, false, false, false] },
                                            @if($spinalcord->ltrL1 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pprL1 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            { text: '' },
                                        ],
                                        [
                                            {
                                                text: [
                                                    { text: 'LER\n', bold: true },
                                                    { text: '(Lower Extremity Right)', bold: true, fontSize: 6 },
                                                ], alignment: 'center', rowSpan: 5, border: [false, false, false, false], fillColor: '#dddddd'
                                            },
                                            {
                                                text: [
                                                    { text: 'Hip flexors', italics: true, fontSize: 7 },
                                                    { text: '\u200B\t L2', bold: true },
                                                ], alignment: 'right', border: [false, false, false, false], fillColor: '#dddddd'
                                            },
                                            @if($spinalcord->motorRL2 == '1')
                                                { text: '√', alignment: 'center', fillColor: '#dddddd' },
                                            @else
                                                { text: '', fillColor: '#dddddd' },
                                            @endif
                                            @if($spinalcord->ltrL2 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pprL2 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            { text: '' },
                                        ],
                                        [
                                            { text: '' },
                                            {
                                                text: [
                                                    { text: 'Knee extensors', italics: true, fontSize: 7 },
                                                    { text: '\u200B\t L3', bold: true },
                                                ], alignment: 'right', border: [false, false, false, false], fillColor: '#dddddd'
                                            },
                                            @if($spinalcord->motorRL3 == '1')
                                                { text: '√', alignment: 'center', fillColor: '#dddddd' },
                                            @else
                                                { text: '', fillColor: '#dddddd' },
                                            @endif
                                            @if($spinalcord->ltrL3 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pprL3 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            { text: '' },
                                        ],
                                        [
                                            { text: '' },
                                            {
                                                text: [
                                                    { text: 'Ankle dorsiflexors', italics: true, fontSize: 7 },
                                                    { text: '\u200B\t L4', bold: true },
                                                ], alignment: 'right', border: [false, false, false, false], fillColor: '#dddddd'
                                            },
                                            @if($spinalcord->motorRL4 == '1')
                                                { text: '√', alignment: 'center', fillColor: '#dddddd' },
                                            @else
                                                { text: '', fillColor: '#dddddd' },
                                            @endif
                                            @if($spinalcord->ltrL4 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pprL4 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            { text: '' },
                                        ],
                                        [
                                            { text: '' },
                                            {
                                                text: [
                                                    { text: 'Long toe extensors', italics: true, fontSize: 7 },
                                                    { text: '\u200B\t L5', bold: true },
                                                ], alignment: 'right', border: [false, false, false, false], fillColor: '#dddddd'
                                            },
                                            @if($spinalcord->motorRL5 == '1')
                                                { text: '√', alignment: 'center', fillColor: '#dddddd' },
                                            @else
                                                { text: '', fillColor: '#dddddd' },
                                            @endif
                                            @if($spinalcord->ltrL5 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pprL5 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            { text: '' },
                                        ],
                                        [
                                            { text: '' },
                                            {
                                                text: [
                                                    { text: 'Ankle plantar flexors', italics: true, fontSize: 7 },
                                                    { text: '\u200B\t S1', bold: true },
                                                ], alignment: 'right', border: [false, false, false, false], fillColor: '#dddddd'
                                            },
                                            @if($spinalcord->motorRS1 == '1')
                                                { text: '√', alignment: 'center', fillColor: '#dddddd' },
                                            @else
                                                { text: '', fillColor: '#dddddd' },
                                            @endif
                                            @if($spinalcord->ltrS1 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pprS1 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            { text: '' },
                                        ],
                                        [
                                            { text: '', border: [false, false, false, false] },
                                            { text: '', border: [false, false, false, false] },
                                            { text: 'S2', bold: true, alignment: 'right', border: [false, false, false, false] },
                                            @if($spinalcord->ltrS2 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pprS2 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            { text: '' },
                                        ],
                                        [
                                            { text: '', border: [false, false, false, false] },
                                            { text: '', border: [false, false, false, false] },
                                            { text: 'S3', bold: true, alignment: 'right', border: [false, false, false, false] },
                                            @if($spinalcord->ltrS3 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pprS3 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            { text: '' },
                                        ],
                                        [
                                            {
                                                table: {
                                                    widths: ['*',15],
                                                    body: [
                                                        [
                                                            { text: '(VAC) Voluntary Anal Contraction (Yes/No)', bold: true, italics: true, fontSize: 7, border: [false, false, false, false] },
                                                            @if($spinalcord->vac == '1')
                                                                { text: 'YES', bold: true, fontSize: 7, alignment: 'center' },
                                                            @elseif($spinalcord->vac == '0')
                                                                { text: 'NO', bold: true, fontSize: 7, alignment: 'center' },
                                                            @else
                                                                { text: '' },
                                                            @endif
                                                        ],
                                                    ]
                                                }, alignment: 'right', colSpan: 2, border: [false, false, false, false]
                                            },
                                            { text: '', border: [false, false, false, false] },
                                            { text: 'S4-5', bold: true, alignment: 'right', border: [false, false, false, false] },
                                            @if($spinalcord->ltrS4 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pprS4 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            { text: '' },
                                        ],
                                    ]
                                },
                                // layout: 'noBorders'
                            },
                            {
                                // Right table
                                width: '*', // Takes remaining space
                                style: 'tableRight',
                                table: {
                                    widths: [35,35,35,105,53],
                                    headerRows: 1,
                                    body: [
                                        [
                                            {
                                                text: [
                                                    { text: 'SENSORY\n', bold: true },
                                                    { text: 'KEY SENSORY POINTS', bold: true, fontSize: 6 },
                                                ], alignment: 'center', colSpan: 2, border: [false, false, false, false]
                                            },
                                            { text: '' },
                                            {
                                                text: [
                                                    { text: 'MOTOR\n', bold: true },
                                                    { text: 'KEY MUSCLES', bold: true, fontSize: 6 },
                                                ], alignment: 'center', colSpan: 2, border: [false, false, false, false]
                                            },
                                            { text: '' },
                                            { text: 'LEFT', bold: true, fontSize: 12, alignment: 'center', rowSpan: 2, border: [false, false, false, false] },
                                        ],
                                        [
                                            { text: 'Light Touch (LTL)', bold: true, fontSize: 6, alignment: 'center', border: [false, false, false, false] },
                                            { text: 'Pin Prick (PPL)', bold: true, fontSize: 6, alignment: 'center', border: [false, false, false, false] },
                                            { text: '', border: [false, false, false, false] },
                                            { text: '', border: [false, false, false, false] },
                                            { text: '' },
                                        ],
                                        [
                                            @if($spinalcord->ltlC2 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pplC2 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            { text: 'C2', bold: true, alignment: 'left', border: [false, false, false, false] },
                                            { text: '', border: [false, false, false, false] },
                                            { text: '', border: [false, false, false, false] },
                                        ],
                                        [
                                            @if($spinalcord->ltlC3 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pplC3 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            { text: 'C3', bold: true, alignment: 'left', border: [false, false, false, false] },
                                            { text: '', border: [false, false, false, false] },
                                            { text: '', border: [false, false, false, false] },
                                        ],
                                        [
                                            @if($spinalcord->ltlC4 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pplC4 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            { text: 'C4', bold: true, alignment: 'left', border: [false, false, false, false] },
                                            { text: '', border: [false, false, false, false] },
                                            { text: '', border: [false, false, false, false] },
                                        ],
                                        [
                                            @if($spinalcord->ltlC5 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pplC5 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->motorLC5 == '1')
                                                { text: '√', alignment: 'center', fillColor: '#dddddd' },
                                            @else
                                                { text: '', fillColor: '#dddddd' },
                                            @endif
                                            {
                                                text: [
                                                    { text: 'C5 \u200B\t', bold: true },
                                                    { text: 'Elbow flexors', italics: true, fontSize: 7 },
                                                ], alignment: 'left', border: [false, false, false, false], fillColor: '#dddddd'
                                            },
                                            {
                                                text: [
                                                    { text: 'UEL\n', bold: true },
                                                    { text: '(Upper Extremity Left)', bold: true, fontSize: 6 },
                                                ], alignment: 'center', rowSpan: 5, border: [false, false, false, false], fillColor: '#dddddd'
                                            },
                                        ],
                                        [
                                            @if($spinalcord->ltlC6 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pplC6 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->motorLC6 == '1')
                                                { text: '√', alignment: 'center', fillColor: '#dddddd' },
                                            @else
                                                { text: '', fillColor: '#dddddd' },
                                            @endif
                                            {
                                                text: [
                                                    { text: 'C6 \u200B\t', bold: true },
                                                    { text: 'Wrist extensors', italics: true, fontSize: 7 },
                                                ], alignment: 'left', border: [false, false, false, false], fillColor: '#dddddd'
                                            },
                                            { text: '' },
                                        ],
                                        [
                                            @if($spinalcord->ltlC7 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pplC7 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->motorLC7 == '1')
                                                { text: '√', alignment: 'center', fillColor: '#dddddd' },
                                            @else
                                                { text: '', fillColor: '#dddddd' },
                                            @endif
                                            {
                                                text: [
                                                    { text: 'C7 \u200B\t', bold: true },
                                                    { text: 'Elbow extensors', italics: true, fontSize: 7 },
                                                ], alignment: 'left', border: [false, false, false, false], fillColor: '#dddddd'
                                            },
                                            { text: '' },
                                        ],
                                        [
                                            @if($spinalcord->ltlC8 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pplC8 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->motorLC8 == '1')
                                                { text: '√', alignment: 'center', fillColor: '#dddddd' },
                                            @else
                                                { text: '', fillColor: '#dddddd' },
                                            @endif
                                            {
                                                text: [
                                                    { text: 'C8 \u200B\t', bold: true },
                                                    { text: 'Finger flexors', italics: true, fontSize: 7 },
                                                ], alignment: 'left', border: [false, false, false, false], fillColor: '#dddddd'
                                            },
                                            { text: '' },
                                        ],
                                        [
                                            @if($spinalcord->ltlT1 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pplT1 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->motorLT1 == '1')
                                                { text: '√', alignment: 'center', fillColor: '#dddddd' },
                                            @else
                                                { text: '', fillColor: '#dddddd' },
                                            @endif
                                            {
                                                text: [
                                                    { text: 'T1 \u200B\t', bold: true },
                                                    { text: 'Finger abductors', italics: true, fontSize: 7 },
                                                    { text: ' (little finger)', italics: true, fontSize: 6 },
                                                ], alignment: 'left', border: [false, false, false, false], fillColor: '#dddddd'
                                            },
                                            { text: '' },
                                        ],
                                        [
                                            @if($spinalcord->ltlT2 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pplT2 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            { text: 'T2', bold: true, alignment: 'left', border: [false, false, false, false] },
                                            {
                                                text: [
                                                    { text: '\n\nMOTOR\n', bold: true, fontSize: 8, alignment: 'center' },
                                                    { text: '(SCORING ON REVERSE SIDE)\n', bold: true, italics: true, fontSize: 7, alignment: 'center' },
                                                    { text: '\u200B\t \u200B\t 0 = total paralysis\n \u200B\t \u200B\t 1 = palpable or visible contraction\n \u200B\t \u200B\t 2 = active movement, gravity eliminated\n \u200B\t \u200B\t 3 = active movement, against gravity\n \u200B\t \u200B\t 4 = active movement, against some resistance\n \u200B\t \u200B\t 5 = active movement, against full resistance\n \u200B\t \u200B\t 5* = normal corrected for pain/disuse\n \u200B\t \u200B\t NT = not testable\n', italics: true, fontSize: 7 },
                                                    { text: '\n\nSENSORY\n', bold: true, fontSize: 8, alignment: 'center' },
                                                    { text: '(SCORING ON REVERSE SIDE)\n', bold: true, italics: true, fontSize: 7, alignment: 'center' },
                                                    { text: '\u200B\t \u200B\t 0 = absent \u200B\t \u200B\t \u200B\t \u200B\t 2 = normal\n \u200B\t \u200B\t 1 = altered \u200B\t \u200B\t \u200B\t \u200B\t NT = not testable\n', italics: true, fontSize: 7 },
                                                ], colSpan: 2, rowSpan: 12, border: [false, false, false, false]
                                            },
                                            { text: '' },
                                        ],
                                        [
                                            @if($spinalcord->ltlT3 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pplT3 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            { text: 'T3', bold: true, alignment: 'left', border: [false, false, false, false] },
                                            { text: '' },
                                            { text: '' },
                                        ],
                                        [
                                            @if($spinalcord->ltlT4 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pplT4 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            { text: 'T4', bold: true, alignment: 'left', border: [false, false, false, false] },
                                            { text: '' },
                                            { text: '' },
                                        ],
                                        [
                                            @if($spinalcord->ltlT5 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pplT5 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            { text: 'T5', bold: true, alignment: 'left', border: [false, false, false, false] },
                                            { text: '' },
                                            { text: '' },
                                        ],
                                        [
                                            @if($spinalcord->ltlT6 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pplT6 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            { text: 'T6', bold: true, alignment: 'left', border: [false, false, false, false] },
                                            { text: '' },
                                            { text: '' },
                                        ],
                                        [
                                            @if($spinalcord->ltlT7 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pplT7 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            { text: 'T7', bold: true, alignment: 'left', border: [false, false, false, false] },
                                            { text: '' },
                                            { text: '' },
                                        ],
                                        [
                                            @if($spinalcord->ltlT8 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pplT8 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            { text: 'T8', bold: true, alignment: 'left', border: [false, false, false, false] },
                                            { text: '' },
                                            { text: '' },
                                        ],
                                        [
                                            @if($spinalcord->ltlT9 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pplT9 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            { text: 'T9', bold: true, alignment: 'left', border: [false, false, false, false] },
                                            { text: '' },
                                            { text: '' },
                                        ],
                                        [
                                            @if($spinalcord->ltlT10 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pplT10 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            { text: 'T10', bold: true, alignment: 'left', border: [false, false, false, false] },
                                            { text: '' },
                                            { text: '' },
                                        ],
                                        [
                                            @if($spinalcord->ltlT11 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pplT11 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            { text: 'T11', bold: true, alignment: 'left', border: [false, false, false, false] },
                                            { text: '' },
                                            { text: '' },
                                        ],
                                        [
                                            @if($spinalcord->ltlT12 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pplT12 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            { text: 'T12', bold: true, alignment: 'left', border: [false, false, false, false] },
                                            { text: '' },
                                            { text: '' },
                                        ],
                                        [
                                            @if($spinalcord->ltlL1 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pplL1 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            { text: 'L1', bold: true, alignment: 'left', border: [false, false, false, false] },
                                            { text: '' },
                                            { text: '' },
                                        ],
                                        [
                                            @if($spinalcord->ltlL2 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pplL2 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->motorLL2 == '1')
                                                { text: '√', alignment: 'center', fillColor: '#dddddd' },
                                            @else
                                                { text: '', fillColor: '#dddddd' },
                                            @endif
                                            {
                                                text: [
                                                    { text: 'L2 \u200B\t', bold: true },
                                                    { text: 'Hip flexors', italics: true, fontSize: 7 },
                                                ], alignment: 'left', border: [false, false, false, false], fillColor: '#dddddd'
                                            },
                                            {
                                                text: [
                                                    { text: 'LEL\n', bold: true },
                                                    { text: '(Lower Extremity Left)', bold: true, fontSize: 6 },
                                                ], alignment: 'center', rowSpan: 5, border: [false, false, false, false], fillColor: '#dddddd'
                                            },
                                        ],
                                        [
                                            @if($spinalcord->ltlL3 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pplL3 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->motorLL3 == '1')
                                                { text: '√', alignment: 'center', fillColor: '#dddddd' },
                                            @else
                                                { text: '', fillColor: '#dddddd' },
                                            @endif
                                            {
                                                text: [
                                                    { text: 'L3 \u200B\t', bold: true },
                                                    { text: 'Knee extensors', italics: true, fontSize: 7 },
                                                ], alignment: 'left', border: [false, false, false, false], fillColor: '#dddddd'
                                            },
                                            { text: '' },
                                        ],
                                        [
                                            @if($spinalcord->ltlL4 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pplL4 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->motorLL4 == '1')
                                                { text: '√', alignment: 'center', fillColor: '#dddddd' },
                                            @else
                                                { text: '', fillColor: '#dddddd' },
                                            @endif
                                            {
                                                text: [
                                                    { text: 'L4 \u200B\t', bold: true },
                                                    { text: 'Ankle dorsiflexors', italics: true, fontSize: 7 },
                                                ], alignment: 'left', border: [false, false, false, false], fillColor: '#dddddd'
                                            },
                                            { text: '' },
                                        ],
                                        [
                                            @if($spinalcord->ltlL5 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pplL5 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->motorLL5 == '1')
                                                { text: '√', alignment: 'center', fillColor: '#dddddd' },
                                            @else
                                                { text: '', fillColor: '#dddddd' },
                                            @endif
                                            {
                                                text: [
                                                    { text: 'L5 \u200B\t', bold: true },
                                                    { text: 'Long toe extensors', italics: true, fontSize: 7 },
                                                ], alignment: 'left', border: [false, false, false, false], fillColor: '#dddddd'
                                            },
                                            { text: '' },
                                        ],
                                        [
                                            @if($spinalcord->ltlS1 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pplS1 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->motorLS1 == '1')
                                                { text: '√', alignment: 'center', fillColor: '#dddddd' },
                                            @else
                                                { text: '', fillColor: '#dddddd' },
                                            @endif
                                            {
                                                text: [
                                                    { text: 'S1 \u200B\t', bold: true },
                                                    { text: 'Ankle plantar flexors', italics: true, fontSize: 7 },
                                                ], alignment: 'left', border: [false, false, false, false], fillColor: '#dddddd'
                                            },
                                            { text: '' },
                                        ],
                                        [
                                            @if($spinalcord->ltlS2 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pplS2 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            { text: 'S2', bold: true, alignment: 'left', border: [false, false, false, false] },
                                            { text: '', border: [false, false, false, false] },
                                            { text: '', border: [false, false, false, false] },
                                        ],
                                        [
                                            @if($spinalcord->ltlS3 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pplS3 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            { text: 'S3', bold: true, alignment: 'left', border: [false, false, false, false] },
                                            { text: '', border: [false, false, false, false] },
                                            { text: '', border: [false, false, false, false] },
                                        ],
                                        [
                                            @if($spinalcord->ltlS4 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            @if($spinalcord->pplS4 == '1')
                                                { text: '√', alignment: 'center' },
                                            @else
                                                { text: '' },
                                            @endif
                                            { text: 'S4-5', bold: true, alignment: 'left', border: [false, false, false, false] },
                                            {
                                                table: {
                                                    widths: [15,'*'],
                                                    body: [
                                                        [
                                                            @if($spinalcord->dap == '1')
                                                                { text: 'YES', bold: true, fontSize: 7, alignment: 'center' },
                                                            @elseif($spinalcord->dap == '0')
                                                                { text: 'NO', bold: true, fontSize: 7, alignment: 'center' },
                                                            @else
                                                                { text: '' },
                                                            @endif
                                                            { text: '(DAP) Deep Anal Pressure (Yes/No)', bold: true, italics: true, fontSize: 7, border: [false, false, false, false] },
                                                        ],
                                                    ]
                                                }, alignment: 'left', colSpan: 2, border: [false, false, false, false]
                                            },
                                            { text: '', border: [false, false, false, false] },
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
                        fontSize: 12,
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
                        margin: [0, 5, 0, 0]
                    },
                    tableRight: {
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
                    },
                    spinalcord: {
                        url: "{{asset('patientcare/img/spinalCordDiag.jpg')}}",
                    },
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