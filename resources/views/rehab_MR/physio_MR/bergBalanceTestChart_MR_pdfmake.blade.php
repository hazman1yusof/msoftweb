<!DOCTYPE html>
<html>
    <head>
        <title>Berg Balance Positions and Tests</title>
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
                    // {
                    //     text: '\nBERG BALANCE POSITIONS AND TESTS\n',
                    //     style: 'header',
                    //     alignment: 'center',
                    // },
                    // { canvas: [ { type: 'line', x1: 0, y1: 5, x2: 515, y2: 5, lineWidth: 0.5 } ] },
                    {
                        style: 'tableTitle',
                        table: {
                            widths: ['*'],
                            // headerRows: 5,
                            // keepWithHeaderRows: 5,
                            body: [
                                [
                                    { text: 'BERG BALANCE POSITIONS AND TESTS', alignment: 'center' },
                                ],
                            ]
                        },
                        layout: {
                            fillColor: function (rowIndex, node, columnIndex){
                                // return (rowIndex === 0) ? '#000000' : null;
                            }
                        }
                    },
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: ['*','*'], // panjang standard dia 515
                            body: [
                                [
                                    { text: `Name : \u200B\t{!!$bergtest->Name!!}` },
                                    { text: 'Date : \u200B\t{{\Carbon\Carbon::createFromFormat('Y-m-d',$bergtest->entereddate)->format('d-m-Y')}}' },
                                ],
                            ]
                        },
                        layout: 'noBorders',
                    },
                    {
                        style: 'tableExample',
                        table: {
                            widths: [15,15,210,15,15,210],
                            // headerRows: 5,
                            // keepWithHeaderRows: 5,
                            body: [
                                [
                                    { text: '1.' },
                                    {
                                        text: [
                                            'SITTING TO STANDING\n',
                                            { text: 'INSTRUCTIONS: ', decoration: 'underline' },
                                            { text: ' Please stand up. Try not to use your hands for support.' },
                                        ], colSpan: 2, alignment: 'left'
                                    },{},
                                    { text: '8.' },
                                    {
                                        text: [
                                            'REACHING FORWARD WITH OUTSTRETCHED ARM WHILE STANDING\n',
                                            { text: 'INSTRUCTIONS: ', decoration: 'underline' },
                                            { text: ' Lift arm to 90°. Stretch out your fingers and reach forward as far as you can.' },
                                        ], colSpan: 2, alignment: 'left'
                                    },{},
                                ],
                                [
                                    @if($bergtest->sitToStand == '4')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '4', alignment: 'center' },
                                    { text: 'Able to stand without using hands and stabilize independently.', alignment: 'left' },
                                    @if($bergtest->reachForward == '4')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '4', alignment: 'center' },
                                    { text: 'Can reach forward confidently > 25 cm.', alignment: 'left' },
                                ],
                                [
                                    @if($bergtest->sitToStand == '3')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '3', alignment: 'center' },
                                    { text: 'Able to stand independently using hands.', alignment: 'left' },
                                    @if($bergtest->reachForward == '3')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '3', alignment: 'center' },
                                    { text: 'Can reach forward > 12 cm.', alignment: 'left' },
                                ],
                                [
                                    @if($bergtest->sitToStand == '2')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '2', alignment: 'center' },
                                    { text: 'Able to stand using hands after several tries.', alignment: 'left' },
                                    @if($bergtest->reachForward == '2')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '2', alignment: 'center' },
                                    { text: 'Can reach forward > 5cm.', alignment: 'left' },
                                ],
                                [
                                    @if($bergtest->sitToStand == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '1', alignment: 'center' },
                                    { text: 'Needs minimal aid to stand or to stabilize.', alignment: 'left' },
                                    @if($bergtest->reachForward == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '1', alignment: 'center' },
                                    { text: 'Reaches forward but needs supervision.', alignment: 'left' },
                                ],
                                [
                                    @if($bergtest->sitToStand == '0')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '0', alignment: 'center' },
                                    { text: 'Needs moderate or maximal assist to stand.', alignment: 'left' },
                                    @if($bergtest->reachForward == '0')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '0', alignment: 'center' },
                                    { text: 'Loses balance while trying/requires external support.', alignment: 'left' },
                                ],
                                [
                                    { text: '2.' },
                                    {
                                        text: [
                                            'STANDING UNSUPPORTED\n',
                                            { text: 'INSTRUCTIONS: ', decoration: 'underline' },
                                            { text: ' Please stand for two minutes without holding.' },
                                        ], colSpan: 2, alignment: 'left'
                                    },{},
                                    { text: '9.' },
                                    {
                                        text: [
                                            'PICK UP OBJECT FROM THE FLOOR FROM A STANDING POSITION\n',
                                            { text: 'INSTRUCTIONS: ', decoration: 'underline' },
                                            { text: ' Pick up the shoe/slipper which is placed in front of your feet.' },
                                        ], colSpan: 2, alignment: 'left'
                                    },{},
                                ],
                                [
                                    @if($bergtest->standUnsupported == '4')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '4', alignment: 'center' },
                                    { text: 'Able to stand safely two minutes.', alignment: 'left' },
                                    @if($bergtest->pickUpObject == '4')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '4', alignment: 'center' },
                                    { text: 'Able to pick up slipper safely and easily.', alignment: 'left' },
                                ],
                                [
                                    @if($bergtest->standUnsupported == '3')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '3', alignment: 'center' },
                                    { text: 'Able to stand 2 minutes with supervision.', alignment: 'left' },
                                    @if($bergtest->pickUpObject == '3')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '3', alignment: 'center' },
                                    { text: 'Able to pick up slipper but needs supervision.', alignment: 'left' },
                                ],
                                [
                                    @if($bergtest->standUnsupported == '2')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '2', alignment: 'center' },
                                    { text: 'Able to stand 30 seconds unsupported.', alignment: 'left' },
                                    @if($bergtest->pickUpObject == '2')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '2', alignment: 'center' },
                                    { text: 'Unable to pick up but reaches 2-5 cm from slipper and keeps balance independently.', alignment: 'left' },
                                ],
                                [
                                    @if($bergtest->standUnsupported == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '1', alignment: 'center' },
                                    { text: 'Needs several tries to stand 30 seconds unsupported.', alignment: 'left' },
                                    @if($bergtest->pickUpObject == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '1', alignment: 'center' },
                                    { text: 'Unable to pick up and needs supervision while trying.', alignment: 'left' },
                                ],
                                [
                                    @if($bergtest->standUnsupported == '0')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '0', alignment: 'center' },
                                    { text: 'Unable to stand 30 seconds unassisted.', alignment: 'left' },
                                    @if($bergtest->pickUpObject == '0')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '0', alignment: 'center' },
                                    { text: 'Unable to try/needs assist to keep from losing balance or falling.', alignment: 'left' },
                                ],
                                [
                                    { text: '3.' },
                                    {
                                        text: [
                                            'SITTING WITH BACK UNSUPPORTED BUT FEET SUPPORTED ON FLOOR OR ON A STOOL\n',
                                            { text: 'INSTRUCTIONS: ', decoration: 'underline' },
                                            { text: ' Please sit with arms folded for 2 minutes.' },
                                        ], colSpan: 2, alignment: 'left'
                                    },{},
                                    { text: '10.' },
                                    {
                                        text: [
                                            'TURNING TO LOOK BEHIND OVER LEFT AND RIGHT SHOULDERS WHILE STANDING\n',
                                            { text: 'INSTRUCTIONS: ', decoration: 'underline' },
                                            { text: ' Turn to look directly behind you over toward left shoulder. Repeat to the right.' },
                                        ], colSpan: 2, alignment: 'left'
                                    },{},
                                ],
                                [
                                    @if($bergtest->sitBackUnsupported == '4')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '4', alignment: 'center' },
                                    { text: 'Able to sit safely and securely for 2 minutes.', alignment: 'left' },
                                    @if($bergtest->turnToLookBehind == '4')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '4', alignment: 'center' },
                                    { text: 'Look behind from both sides & weight shifts well.', alignment: 'left' },
                                ],
                                [
                                    @if($bergtest->sitBackUnsupported == '3')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '3', alignment: 'center' },
                                    { text: 'Able to sit 2 minutes under supervision.', alignment: 'left' },
                                    @if($bergtest->turnToLookBehind == '3')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '3', alignment: 'center' },
                                    { text: 'Looks behind 1 side, other side shows less shift.', alignment: 'left' },
                                ],
                                [
                                    @if($bergtest->sitBackUnsupported == '2')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '2', alignment: 'center' },
                                    { text: 'Able to sit 30 seconds.', alignment: 'left' },
                                    @if($bergtest->turnToLookBehind == '2')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '2', alignment: 'center' },
                                    { text: 'Turns sideways only, but maintains balance.', alignment: 'left' },
                                ],
                                [
                                    @if($bergtest->sitBackUnsupported == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '1', alignment: 'center' },
                                    { text: 'Able to sit 10 seconds.', alignment: 'left' },
                                    @if($bergtest->turnToLookBehind == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '1', alignment: 'center' },
                                    { text: 'Needs supervision when turning.', alignment: 'left' },
                                ],
                                [
                                    @if($bergtest->sitBackUnsupported == '0')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '0', alignment: 'center' },
                                    { text: 'Unable to sit without support 10 seconds.', alignment: 'left' },
                                    @if($bergtest->turnToLookBehind == '0')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '0', alignment: 'center' },
                                    { text: 'Needs assist to keep from losing balance/falling.', alignment: 'left' },
                                ],
                                [
                                    { text: '4.' },
                                    {
                                        text: [
                                            'STANDING TO SITTING\n',
                                            { text: 'INSTRUCTIONS: ', decoration: 'underline' },
                                            { text: ' Please sit down.' },
                                        ], colSpan: 2, alignment: 'left'
                                    },{},
                                    { text: '11.' },
                                    {
                                        text: [
                                            'TURN 360°\n',
                                            { text: 'INSTRUCTIONS: ', decoration: 'underline' },
                                            { text: ' Turn completely around in a full circle, pause, then turn a full circle in the other direction.' },
                                        ], colSpan: 2, alignment: 'left'
                                    },{},
                                ],
                                [
                                    @if($bergtest->standToSit == '4')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '4', alignment: 'center' },
                                    { text: 'Sits safely with minimal use of hands.', alignment: 'left' },
                                    @if($bergtest->turn360 == '4')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '4', alignment: 'center' },
                                    { text: 'Able to turn 360° safely in 4 seconds or less.', alignment: 'left' },
                                ],
                                [
                                    @if($bergtest->standToSit == '3')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '3', alignment: 'center' },
                                    { text: 'Controls descent by using hands.', alignment: 'left' },
                                    @if($bergtest->turn360 == '3')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '3', alignment: 'center' },
                                    { text: 'Able to turn 360° safely one side in 4 sec or less.', alignment: 'left' },
                                ],
                                [
                                    @if($bergtest->standToSit == '2')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '2', alignment: 'center' },
                                    { text: 'Uses back of legs against chair to control descent.', alignment: 'left' },
                                    @if($bergtest->turn360 == '2')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '2', alignment: 'center' },
                                    { text: 'Able to turn 360° safely but slowly.', alignment: 'left' },
                                ],
                                [
                                    @if($bergtest->standToSit == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '1', alignment: 'center' },
                                    { text: 'Sits independently but has uncontrolled descent.', alignment: 'left' },
                                    @if($bergtest->turn360 == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '1', alignment: 'center' },
                                    { text: 'Needs close supervision or verbal cueing.', alignment: 'left' },
                                ],
                                [
                                    @if($bergtest->standToSit == '0')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '0', alignment: 'center' },
                                    { text: 'Needs assistance to sit.', alignment: 'left' },
                                    @if($bergtest->turn360 == '0')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '0', alignment: 'center' },
                                    { text: 'Needs assistance while turning.', alignment: 'left' },
                                ],
                                [
                                    { text: '5.' },
                                    {
                                        text: [
                                            'TRANSFERS\n',
                                            { text: 'INSTRUCTIONS: ', decoration: 'underline' },
                                            { text: ' Arrange chair(s) for a pivot transfer. Ask subject to transfer one way toward a seat without armrests. You may use two chairs (one with and one without armrests) or a bed and a chair.' },
                                        ], colSpan: 2, alignment: 'left'
                                    },{},
                                    { text: '12.' },
                                    {
                                        text: [
                                            'PLACING ALTERNATE FOOT ON STEP OR STOOL WHILE STANDING UNSUPPORTED\n',
                                            { text: 'INSTRUCTIONS: ', decoration: 'underline' },
                                            { text: ' Place each foot alternately on the step stool. Continue until each foot has touched the step stool four times.' },
                                        ], colSpan: 2, alignment: 'left'
                                    },{},
                                ],
                                [
                                    @if($bergtest->transfer == '4')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '4', alignment: 'center' },
                                    { text: 'Able to transfer safely with minor use of hands.', alignment: 'left' },
                                    @if($bergtest->placeFootOnStep == '4')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '4', alignment: 'center' },
                                    { text: 'Able to stand alone & safely do 8 steps in 20 sec.', alignment: 'left' },
                                ],
                                [
                                    @if($bergtest->transfer == '3')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '3', alignment: 'center' },
                                    { text: 'Able to transfer safely definite need of hands.', alignment: 'left' },
                                    @if($bergtest->placeFootOnStep == '3')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '3', alignment: 'center' },
                                    { text: 'Able to stand alone & do 8 steps > 20 sec.', alignment: 'left' },
                                ],
                                [
                                    @if($bergtest->transfer == '2')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '2', alignment: 'center' },
                                    { text: 'Able to transfer with verbal cueing and/or supervision.', alignment: 'left' },
                                    @if($bergtest->placeFootOnStep == '2')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '2', alignment: 'center' },
                                    { text: 'Able to do 4 steps without aid, with supervision.', alignment: 'left' },
                                ],
                                [
                                    @if($bergtest->transfer == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '1', alignment: 'center' },
                                    { text: 'Needs one person to assist.', alignment: 'left' },
                                    @if($bergtest->placeFootOnStep == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '1', alignment: 'center' },
                                    { text: 'Able to do > 2 steps, need minimal assist.', alignment: 'left' },
                                ],
                                [
                                    @if($bergtest->transfer == '0')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '0', alignment: 'center' },
                                    { text: 'Needs two people to assist or supervise to be safe.', alignment: 'left' },
                                    @if($bergtest->placeFootOnStep == '0')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '0', alignment: 'center' },
                                    { text: 'Needs assistance to keep from falling/unable to try.', alignment: 'left' },
                                ],
                                [
                                    { text: '6.' },
                                    {
                                        text: [
                                            'STANDING UNSUPPORTED WITH EYES CLOSED\n',
                                            { text: 'INSTRUCTIONS: ', decoration: 'underline' },
                                            { text: ' Please close your eyes and stand still for 10 seconds.' },
                                        ], colSpan: 2, alignment: 'left'
                                    },{},
                                    { text: '13.' },
                                    {
                                        text: [
                                            'STANDING UNSUPPORTED ONE FOOT IN FRONT\n',
                                            { text: 'INSTRUCTIONS: ', decoration: 'underline' },
                                            { text: ' Place one foot directly in front of the other or place foot somewhat in front of the other.' },
                                        ], colSpan: 2, alignment: 'left'
                                    },{},
                                ],
                                [
                                    @if($bergtest->standEyesClosed == '4')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '4', alignment: 'center' },
                                    { text: 'Able to stand 10 seconds safely.', alignment: 'left' },
                                    @if($bergtest->oneFootInFront == '4')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '4', alignment: 'center' },
                                    { text: 'Able to place foot tandem alone and hold 30 sec.', alignment: 'left' },
                                ],
                                [
                                    @if($bergtest->standEyesClosed == '3')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '3', alignment: 'center' },
                                    { text: 'Able to stand 10 seconds with supervision.', alignment: 'left' },
                                    @if($bergtest->oneFootInFront == '3')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '3', alignment: 'center' },
                                    { text: 'Able to place foot ahead of other & hold 30 sec.', alignment: 'left' },
                                ],
                                [
                                    @if($bergtest->standEyesClosed == '2')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '2', alignment: 'center' },
                                    { text: 'Able to stand 3 seconds.', alignment: 'left' },
                                    @if($bergtest->oneFootInFront == '2')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '2', alignment: 'center' },
                                    { text: 'Able to take small step alone and hold 30 sec.', alignment: 'left' },
                                ],
                                [
                                    @if($bergtest->standEyesClosed == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '1', alignment: 'center' },
                                    { text: 'Unable to keep eyes closed 3 seconds but stays steady.', alignment: 'left' },
                                    @if($bergtest->oneFootInFront == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '1', alignment: 'center' },
                                    { text: 'Needs help to step but can hold 15 sec.', alignment: 'left' },
                                ],
                                [
                                    @if($bergtest->standEyesClosed == '0')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '0', alignment: 'center' },
                                    { text: 'Needs help to keep from falling.', alignment: 'left' },
                                    @if($bergtest->oneFootInFront == '0')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '0', alignment: 'center' },
                                    { text: 'Loses balance while stepping or standing.', alignment: 'left' },
                                ],
                                [
                                    { text: '7.' },
                                    {
                                        text: [
                                            'STANDING UNSUPPORTED WITH FEET TOGETHER\n',
                                            { text: 'INSTRUCTIONS: ', decoration: 'underline' },
                                            { text: ' Place your feet close together and stand without holding.' },
                                        ], colSpan: 2, alignment: 'left'
                                    },{},
                                    { text: '14.' },
                                    {
                                        text: [
                                            'STANDING ON ONE LEG\n',
                                            { text: 'INSTRUCTIONS: ', decoration: 'underline' },
                                            { text: ' Stand on one leg as long as you can without holding.' },
                                        ], colSpan: 2, alignment: 'left'
                                    },{},
                                ],
                                [
                                    @if($bergtest->standFeetTogether == '4')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '4', alignment: 'center' },
                                    { text: 'Able to place feet together independently and stand 1 minute safely.', alignment: 'left' },
                                    @if($bergtest->standOneLeg == '4')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '4', alignment: 'center' },
                                    { text: 'Able to lift leg alone and hold > 10 sec.', alignment: 'left' },
                                ],
                                [
                                    @if($bergtest->standFeetTogether == '3')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '3', alignment: 'center' },
                                    { text: 'Able to place feet together independently and stand for 1 minute with supervision.', alignment: 'left' },
                                    @if($bergtest->standOneLeg == '3')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '3', alignment: 'center' },
                                    { text: 'Able to lift leg alone and hold 5-10 sec.', alignment: 'left' },
                                ],
                                [
                                    @if($bergtest->standFeetTogether == '2')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '2', alignment: 'center' },
                                    { text: 'Able to place feet together independently but unable to hold for 30 seconds.', alignment: 'left' },
                                    @if($bergtest->standOneLeg == '2')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '2', alignment: 'center' },
                                    { text: 'Able to lift leg alone and hold = or > 3 sec.', alignment: 'left' },
                                ],
                                [
                                    @if($bergtest->standFeetTogether == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '1', alignment: 'center' },
                                    { text: 'Needs help to attain position but able to stand 15 seconds feet together.', alignment: 'left' },
                                    @if($bergtest->standOneLeg == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '1', alignment: 'center' },
                                    { text: 'Tried to lift leg, unable to hold 3 sec. But remains standing alone.', alignment: 'left' },
                                ],
                                [
                                    @if($bergtest->standFeetTogether == '0')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '0', alignment: 'center' },
                                    { text: 'Needs help to attain position and unable to hold for 15 seconds.', alignment: 'left' },
                                    @if($bergtest->standOneLeg == '0')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    { text: '0', alignment: 'center' },
                                    { text: 'Unable to try or needs assist to prevent fall.', alignment: 'left' },
                                ],
                            ]
                        },
                        layout: {
                            fillColor: function (rowIndex, node, columnIndex){
                                // return (rowIndex === 0) ? '#000000' : null;
                            }
                        }
                    },
                    {
                        style: 'tableTitle',
                        table: {
                            widths: ['*'],
                            // headerRows: 5,
                            // keepWithHeaderRows: 5,
                            body: [
                                [
                                    {
                                        text: [
                                            { text: 'TOTAL SCORE \u200B\t ' },
                                            { text: '{{$bergtest->totalScore}}', decoration: 'underline' },
                                            { text: '\u200B\t (56 MAXIMUM)' },
                                        ], alignment: 'center'
                                    },
                                ],
                            ]
                        },
                        layout: {
                            fillColor: function (rowIndex, node, columnIndex){
                                // return (rowIndex === 0) ? '#000000' : null;
                            }
                        }
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
                    tableTitle: {
                        fontSize: 8,
                        margin: [70, 0, 70, 0]
                    },
                    tableExample: {
                        fontSize: 7,
                        margin: [0, 5, 0, 5]
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