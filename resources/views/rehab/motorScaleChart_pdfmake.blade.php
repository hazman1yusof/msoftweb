<!DOCTYPE html>
<html>
    <head>
        <title>Motor Assessment Scale</title>
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
                        text: 'MOTOR ASSESSMENT SCALE\n',
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
                                    { text: 'Name : \u200B\t{{$motorscale->Name}}' },
                                    { text: 'Date : \u200B\t{{\Carbon\Carbon::createFromFormat('Y-m-d',$motorscale->entereddate)->format('d-m-Y')}}' },
                                ],
                            ]
                        },
                        layout: 'noBorders',
                    },
                    {
                        text: '\nMOVEMENT SCORING SHEET\n\n',
                        style: 'subheader',
                        alignment: 'center',
                    },
                    {
                        style: 'tableExample',
                        table: {
                            widths: [150,40,40,40,40,40,40,40],
                            // headerRows: 5,
                            // keepWithHeaderRows: 5,
                            body: [
                                [
                                    { text: ' ', style: 'tableHeader', alignment: 'center' },
                                    { text: '0', style: 'tableHeader', alignment: 'center' },
                                    { text: '1', style: 'tableHeader', alignment: 'center' },
                                    { text: '2', style: 'tableHeader', alignment: 'center' },
                                    { text: '3', style: 'tableHeader', alignment: 'center' },
                                    { text: '4', style: 'tableHeader', alignment: 'center' },
                                    { text: '5', style: 'tableHeader', alignment: 'center' },
                                    { text: '6', style: 'tableHeader', alignment: 'center' },
                                ],
                                [
                                    { text: '1. Supine to side lying' },
                                    @if($motorscale->sideLie == '0')
                                        { text: '√', alignment: 'center' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                    @elseif($motorscale->sideLie == '1')
                                        { text: ' ' },
                                        { text: '√', alignment: 'center' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                    @elseif($motorscale->sideLie == '2')
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: '√', alignment: 'center' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                    @elseif($motorscale->sideLie == '3')
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: '√', alignment: 'center' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                    @elseif($motorscale->sideLie == '4')
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: '√', alignment: 'center' },
                                        { text: ' ' },
                                        { text: ' ' },
                                    @elseif($motorscale->sideLie == '5')
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: '√', alignment: 'center' },
                                        { text: ' ' },
                                    @elseif($motorscale->sideLie == '6')
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                    @endif
                                ],
                                [
                                    { text: '2. Supine to sitting over side of bed' },
                                    @if($motorscale->sitOverBed == '0')
                                        { text: '√', alignment: 'center' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                    @elseif($motorscale->sitOverBed == '1')
                                        { text: ' ' },
                                        { text: '√', alignment: 'center' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                    @elseif($motorscale->sitOverBed == '2')
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: '√', alignment: 'center' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                    @elseif($motorscale->sitOverBed == '3')
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: '√', alignment: 'center' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                    @elseif($motorscale->sitOverBed == '4')
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: '√', alignment: 'center' },
                                        { text: ' ' },
                                        { text: ' ' },
                                    @elseif($motorscale->sitOverBed == '5')
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: '√', alignment: 'center' },
                                        { text: ' ' },
                                    @elseif($motorscale->sitOverBed == '6')
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                    @endif
                                ],
                                [
                                    { text: '3. Balanced sitting' },
                                    @if($motorscale->balancedSit == '0')
                                        { text: '√', alignment: 'center' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                    @elseif($motorscale->balancedSit == '1')
                                        { text: ' ' },
                                        { text: '√', alignment: 'center' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                    @elseif($motorscale->balancedSit == '2')
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: '√', alignment: 'center' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                    @elseif($motorscale->balancedSit == '3')
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: '√', alignment: 'center' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                    @elseif($motorscale->balancedSit == '4')
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: '√', alignment: 'center' },
                                        { text: ' ' },
                                        { text: ' ' },
                                    @elseif($motorscale->balancedSit == '5')
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: '√', alignment: 'center' },
                                        { text: ' ' },
                                    @elseif($motorscale->balancedSit == '6')
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                    @endif
                                ],
                                [
                                    { text: '4. Sitting to standing' },
                                    @if($motorscale->sitToStand == '0')
                                        { text: '√', alignment: 'center' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                    @elseif($motorscale->sitToStand == '1')
                                        { text: ' ' },
                                        { text: '√', alignment: 'center' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                    @elseif($motorscale->sitToStand == '2')
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: '√', alignment: 'center' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                    @elseif($motorscale->sitToStand == '3')
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: '√', alignment: 'center' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                    @elseif($motorscale->sitToStand == '4')
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: '√', alignment: 'center' },
                                        { text: ' ' },
                                        { text: ' ' },
                                    @elseif($motorscale->sitToStand == '5')
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: '√', alignment: 'center' },
                                        { text: ' ' },
                                    @elseif($motorscale->sitToStand == '6')
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                    @endif
                                ],
                                [
                                    { text: '5. Walking' },
                                    @if($motorscale->walking == '0')
                                        { text: '√', alignment: 'center' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                    @elseif($motorscale->walking == '1')
                                        { text: ' ' },
                                        { text: '√', alignment: 'center' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                    @elseif($motorscale->walking == '2')
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: '√', alignment: 'center' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                    @elseif($motorscale->walking == '3')
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: '√', alignment: 'center' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                    @elseif($motorscale->walking == '4')
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: '√', alignment: 'center' },
                                        { text: ' ' },
                                        { text: ' ' },
                                    @elseif($motorscale->walking == '5')
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: '√', alignment: 'center' },
                                        { text: ' ' },
                                    @elseif($motorscale->walking == '6')
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                    @endif
                                ],
                                [
                                    { text: '6. Upper-arm function' },
                                    @if($motorscale->upperArmFunc == '0')
                                        { text: '√', alignment: 'center' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                    @elseif($motorscale->upperArmFunc == '1')
                                        { text: ' ' },
                                        { text: '√', alignment: 'center' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                    @elseif($motorscale->upperArmFunc == '2')
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: '√', alignment: 'center' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                    @elseif($motorscale->upperArmFunc == '3')
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: '√', alignment: 'center' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                    @elseif($motorscale->upperArmFunc == '4')
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: '√', alignment: 'center' },
                                        { text: ' ' },
                                        { text: ' ' },
                                    @elseif($motorscale->upperArmFunc == '5')
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: '√', alignment: 'center' },
                                        { text: ' ' },
                                    @elseif($motorscale->upperArmFunc == '6')
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                    @endif
                                ],
                                [
                                    { text: '7. Advanced hand activities' },
                                    @if($motorscale->advHandActvt == '0')
                                        { text: '√', alignment: 'center' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                    @elseif($motorscale->advHandActvt == '1')
                                        { text: ' ' },
                                        { text: '√', alignment: 'center' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                    @elseif($motorscale->advHandActvt == '2')
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: '√', alignment: 'center' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                    @elseif($motorscale->advHandActvt == '3')
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: '√', alignment: 'center' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                    @elseif($motorscale->advHandActvt == '4')
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: '√', alignment: 'center' },
                                        { text: ' ' },
                                        { text: ' ' },
                                    @elseif($motorscale->advHandActvt == '5')
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: '√', alignment: 'center' },
                                        { text: ' ' },
                                    @elseif($motorscale->advHandActvt == '6')
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                    @endif
                                ],
                                [
                                    { text: '8. General tonus' },
                                    @if($motorscale->generalTonus == '0')
                                        { text: '√', alignment: 'center' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                    @elseif($motorscale->generalTonus == '1')
                                        { text: ' ' },
                                        { text: '√', alignment: 'center' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                    @elseif($motorscale->generalTonus == '2')
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: '√', alignment: 'center' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                    @elseif($motorscale->generalTonus == '3')
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: '√', alignment: 'center' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                    @elseif($motorscale->generalTonus == '4')
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: '√', alignment: 'center' },
                                        { text: ' ' },
                                        { text: ' ' },
                                    @elseif($motorscale->generalTonus == '5')
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: '√', alignment: 'center' },
                                        { text: ' ' },
                                    @elseif($motorscale->generalTonus == '6')
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                        { text: ' ' },
                                    @endif
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
                        text: [
                            { text: '\nTotal: ', bold: true },
                            { text: '\u200B\t{{$motorscale->movementScore}}' },
                        ],
                        style: 'totalscore',
                    },
                    {
                        text: [
                            { text: '\n\nComments (if applicable)', bold: true },
                            { text: `\n\n{!!str_replace('`', '', $motorscale->comments)!!}` },
                        ],
                        style: 'tableExample',
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