<!DOCTYPE html>
<html>
    <head>
        <title>Intake Output Chart</title>
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
                        // { text: 'This is computer-generated document. No signature is required.', italics: true, alignment: 'center', fontSize: 9 },
                        { text: currentPage.toString() + ' of ' + pageCount, alignment: 'center', fontSize: 9 }
                    ]
                },
                pageSize: 'A4',
                // pageMargins: [10, 20, 20, 30],
                content: [
                    {
                        image: 'letterhead', width: 430, style: 'tableHeader', colSpan: 5, alignment: 'center'
                    },
                    @foreach($recorddate as $rec_key => $rec_value)
                        {
                            table: {
                                widths: [25,'*'], // panjang standard dia 515
                                body: [
                                    [
                                        { text: 'Name', fontSize: 9,bold: true,alignment: 'left' },
                                        { text: ': {{$pat_mast->Name}}', fontSize: 9,alignment: 'left' },
                                    ],
                                    [
                                        { text: 'MRN', fontSize: 9,bold: true,alignment: 'left' },
                                        { text: ': {{$pat_mast->MRN}}', fontSize: 9,alignment: 'left' },
                                    ],
                                    [
                                        { text: 'Date', fontSize: 9,bold: true,alignment: 'left' },
                                        { text: ': {{\Carbon\Carbon::parse($rec_value->recorddate)->format('d/m/Y')}}', fontSize: 9,alignment: 'left' },
                                    ]
                                ]
                            },
                            layout: 'noBorders',
                        },
                        @php($sub1_oralamt = 0) // for first subtotal
                        @php($sub1_intraamt = 0)
                        @php($sub1_otheramt = 0)
                        @php($sub1_urineamt = 0)
                        @php($sub1_vomitamt = 0)
                        @php($sub1_aspamt = 0)
                        @php($sub1_otherout = 0)
                        
                        @php($tot_oralamt = 0) // for grand total
                        @php($tot_intraamt = 0)
                        @php($tot_otheramt = 0)
                        @php($tot_urineamt = 0)
                        @php($tot_vomitamt = 0)
                        @php($tot_aspamt = 0)
                        @php($tot_otherout = 0)
                        @foreach($intakeoutput as $io_key => $io_value)
                            @if($rec_value->recorddate == $io_value->recorddate)
                            {
                                style: 'tableExample',
                                table: {
                                    headerRows: 1,
                                    widths: [36,36,36,36,36,36,36,36,36,36,36,36], // panjang standard dia 515
                                    body: [
                                        [
                                            { text: 'INTAKE OUTPUT CHART', style: 'tblHeader', colSpan: 12, alignment: 'center', border: [false, false, false, false] },
                                            {},{},{},{},{},{},{},{},{},{},{},
                                        ],
                                        [
                                            { text: 'DATE/TIME', style: 'tableHeader', rowSpan: 3 },
                                            { text: 'IN (ml)', style: 'tableHeader', colSpan: 6, alignment: 'center' },{},{},{},{},{},
                                            { text: 'OUT (ml)', style: 'tableHeader', colSpan: 5, alignment: 'center' },{},{},{},{},
                                        ],
                                        [
                                            {},
                                            { text: 'ORAL', style: 'tableHeader', colSpan: 2, alignment: 'center' },{},
                                            { text: 'INTRA-VENA', style: 'tableHeader', colSpan: 2, alignment: 'center' },{},
                                            { text: 'OTHERS', style: 'tableHeader', colSpan: 2, alignment: 'center' },{},
                                            { text: 'TIME', style: 'tableHeader', rowSpan: 2, alignment: 'center' },
                                            { text: 'URINE', style: 'tableHeader', rowSpan: 2, alignment: 'center' },
                                            { text: 'VOMIT', style: 'tableHeader', rowSpan: 2, alignment: 'center' },
                                            { text: 'ASPIRATE', style: 'tableHeader', rowSpan: 2, alignment: 'center' },
                                            { text: 'OTHERS', style: 'tableHeader', rowSpan: 2, alignment: 'center' },
                                        ],
                                        [
                                            {},
                                            { text: 'TYPE', style: 'tableHeader' },
                                            { text: 'AMOUNT', style: 'tableHeader' },
                                            { text: 'TYPE', style: 'tableHeader' },
                                            { text: 'AMOUNT', style: 'tableHeader' },
                                            { text: 'TYPE', style: 'tableHeader' },
                                            { text: 'AMOUNT', style: 'tableHeader' },
                                            {},{},{},{},{},
                                        ],
                                        [
                                            { text: '07:00' },
                                            { text: `{!!$io_value->oraltype1!!}` },
                                            { text: '{{number_format($io_value->oralamt1,2)}}', alignment: 'right' },
                                            { text: `{!!$io_value->intratype1!!}` },
                                            { text: '{{number_format($io_value->intraamt1,2)}}', alignment: 'right' },
                                            { text: `{!!$io_value->othertype1!!}` },
                                            { text: '{{number_format($io_value->otheramt1,2)}}', alignment: 'right' },
                                            { text: '07:00' },
                                            { text: '{{number_format($io_value->urineamt1,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->vomitamt1,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->aspamt1,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->otherout1,2)}}', alignment: 'right' },
                                        ],
                                        @php($sub1_oralamt += $io_value->oralamt1)
                                        @php($sub1_intraamt += $io_value->intraamt1)
                                        @php($sub1_otheramt += $io_value->otheramt1)
                                        @php($sub1_urineamt += $io_value->urineamt1)
                                        @php($sub1_vomitamt += $io_value->vomitamt1)
                                        @php($sub1_aspamt += $io_value->aspamt1)
                                        @php($sub1_otherout += $io_value->otherout1)
                                        [
                                            { text: '08:00' },
                                            { text: `{!!$io_value->oraltype2!!}` },
                                            { text: '{{number_format($io_value->oralamt2,2)}}', alignment: 'right' },
                                            { text: `{!!$io_value->intratype2!!}` },
                                            { text: '{{number_format($io_value->intraamt2,2)}}', alignment: 'right' },
                                            { text: `{!!$io_value->othertype2!!}` },
                                            { text: '{{number_format($io_value->otheramt2,2)}}', alignment: 'right' },
                                            { text: '08:00' },
                                            { text: '{{number_format($io_value->urineamt2,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->vomitamt2,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->aspamt2,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->otherout2,2)}}', alignment: 'right' },
                                        ],
                                        @php($sub1_oralamt += $io_value->oralamt2)
                                        @php($sub1_intraamt += $io_value->intraamt2)
                                        @php($sub1_otheramt += $io_value->otheramt2)
                                        @php($sub1_urineamt += $io_value->urineamt2)
                                        @php($sub1_vomitamt += $io_value->vomitamt2)
                                        @php($sub1_aspamt += $io_value->aspamt2)
                                        @php($sub1_otherout += $io_value->otherout2)
                                        [
                                            { text: '09:00' },
                                            { text: `{!!$io_value->oraltype3!!}` },
                                            { text: '{{number_format($io_value->oralamt3,2)}}', alignment: 'right' },
                                            { text: `{!!$io_value->intratype3!!}` },
                                            { text: '{{number_format($io_value->intraamt3,2)}}', alignment: 'right' },
                                            { text: `{!!$io_value->othertype3!!}` },
                                            { text: '{{number_format($io_value->otheramt3,2)}}', alignment: 'right' },
                                            { text: '09:00' },
                                            { text: '{{number_format($io_value->urineamt3,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->vomitamt3,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->aspamt3,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->otherout3,2)}}', alignment: 'right' },
                                        ],
                                        @php($sub1_oralamt += $io_value->oralamt3)
                                        @php($sub1_intraamt += $io_value->intraamt3)
                                        @php($sub1_otheramt += $io_value->otheramt3)
                                        @php($sub1_urineamt += $io_value->urineamt3)
                                        @php($sub1_vomitamt += $io_value->vomitamt3)
                                        @php($sub1_aspamt += $io_value->aspamt3)
                                        @php($sub1_otherout += $io_value->otherout3)
                                        [
                                            { text: '10:00' },
                                            { text: `{!!$io_value->oraltype4!!}` },
                                            { text: '{{number_format($io_value->oralamt4,2)}}', alignment: 'right' },
                                            { text: `{!!$io_value->intratype4!!}` },
                                            { text: '{{number_format($io_value->intraamt4,2)}}', alignment: 'right' },
                                            { text: `{!!$io_value->othertype4!!}` },
                                            { text: '{{number_format($io_value->otheramt4,2)}}', alignment: 'right' },
                                            { text: '10:00' },
                                            { text: '{{number_format($io_value->urineamt4,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->vomitamt4,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->aspamt4,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->otherout4,2)}}', alignment: 'right' },
                                        ],
                                        @php($sub1_oralamt += $io_value->oralamt4)
                                        @php($sub1_intraamt += $io_value->intraamt4)
                                        @php($sub1_otheramt += $io_value->otheramt4)
                                        @php($sub1_urineamt += $io_value->urineamt4)
                                        @php($sub1_vomitamt += $io_value->vomitamt4)
                                        @php($sub1_aspamt += $io_value->aspamt4)
                                        @php($sub1_otherout += $io_value->otherout4)
                                        [
                                            { text: '11:00' },
                                            { text: `{!!$io_value->oraltype5!!}` },
                                            { text: '{{number_format($io_value->oralamt5,2)}}', alignment: 'right' },
                                            { text: `{!!$io_value->intratype5!!}` },
                                            { text: '{{number_format($io_value->intraamt5,2)}}', alignment: 'right' },
                                            { text: `{!!$io_value->othertype5!!}` },
                                            { text: '{{number_format($io_value->otheramt5,2)}}', alignment: 'right' },
                                            { text: '11:00' },
                                            { text: '{{number_format($io_value->urineamt5,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->vomitamt5,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->aspamt5,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->otherout5,2)}}', alignment: 'right' },
                                        ],
                                        @php($sub1_oralamt += $io_value->oralamt5)
                                        @php($sub1_intraamt += $io_value->intraamt5)
                                        @php($sub1_otheramt += $io_value->otheramt5)
                                        @php($sub1_urineamt += $io_value->urineamt5)
                                        @php($sub1_vomitamt += $io_value->vomitamt5)
                                        @php($sub1_aspamt += $io_value->aspamt5)
                                        @php($sub1_otherout += $io_value->otherout5)
                                        [
                                            { text: '12:00' },
                                            { text: `{!!$io_value->oraltype6!!}` },
                                            { text: '{{number_format($io_value->oralamt6,2)}}', alignment: 'right' },
                                            { text: `{!!$io_value->intratype6!!}` },
                                            { text: '{{number_format($io_value->intraamt6,2)}}', alignment: 'right' },
                                            { text: `{!!$io_value->othertype6!!}` },
                                            { text: '{{number_format($io_value->otheramt6,2)}}', alignment: 'right' },
                                            { text: '12:00' },
                                            { text: '{{number_format($io_value->urineamt6,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->vomitamt6,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->aspamt6,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->otherout6,2)}}', alignment: 'right' },
                                        ],
                                        @php($sub1_oralamt += $io_value->oralamt6)
                                        @php($sub1_intraamt += $io_value->intraamt6)
                                        @php($sub1_otheramt += $io_value->otheramt6)
                                        @php($sub1_urineamt += $io_value->urineamt6)
                                        @php($sub1_vomitamt += $io_value->vomitamt6)
                                        @php($sub1_aspamt += $io_value->aspamt6)
                                        @php($sub1_otherout += $io_value->otherout6)
                                        [
                                            { text: '13:00' },
                                            { text: `{!!$io_value->oraltype7!!}` },
                                            { text: '{{number_format($io_value->oralamt7,2)}}', alignment: 'right' },
                                            { text: `{!!$io_value->intratype7!!}` },
                                            { text: '{{number_format($io_value->intraamt7,2)}}', alignment: 'right' },
                                            { text: `{!!$io_value->othertype7!!}` },
                                            { text: '{{number_format($io_value->otheramt7,2)}}', alignment: 'right' },
                                            { text: '13:00' },
                                            { text: '{{number_format($io_value->urineamt7,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->vomitamt7,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->aspamt7,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->otherout7,2)}}', alignment: 'right' },
                                        ],
                                        @php($sub1_oralamt += $io_value->oralamt7)
                                        @php($sub1_intraamt += $io_value->intraamt7)
                                        @php($sub1_otheramt += $io_value->otheramt7)
                                        @php($sub1_urineamt += $io_value->urineamt7)
                                        @php($sub1_vomitamt += $io_value->vomitamt7)
                                        @php($sub1_aspamt += $io_value->aspamt7)
                                        @php($sub1_otherout += $io_value->otherout7)
                                        [
                                            { text: '14:00' },
                                            { text: `{!!$io_value->oraltype8!!}` },
                                            { text: '{{number_format($io_value->oralamt8,2)}}', alignment: 'right' },
                                            { text: `{!!$io_value->intratype8!!}` },
                                            { text: '{{number_format($io_value->intraamt8,2)}}', alignment: 'right' },
                                            { text: `{!!$io_value->othertype8!!}` },
                                            { text: '{{number_format($io_value->otheramt8,2)}}', alignment: 'right' },
                                            { text: '14:00' },
                                            { text: '{{number_format($io_value->urineamt8,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->vomitamt8,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->aspamt8,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->otherout8,2)}}', alignment: 'right' },
                                        ],
                                        @php($sub1_oralamt += $io_value->oralamt8)
                                        @php($sub1_intraamt += $io_value->intraamt8)
                                        @php($sub1_otheramt += $io_value->otheramt8)
                                        @php($sub1_urineamt += $io_value->urineamt8)
                                        @php($sub1_vomitamt += $io_value->vomitamt8)
                                        @php($sub1_aspamt += $io_value->aspamt8)
                                        @php($sub1_otherout += $io_value->otherout8)
                                        
                                        @php($tot_oralamt += $sub1_oralamt)
                                        @php($tot_intraamt += $sub1_intraamt)
                                        @php($tot_otheramt += $sub1_otheramt)
                                        @php($tot_urineamt += $sub1_urineamt)
                                        @php($tot_vomitamt += $sub1_vomitamt)
                                        @php($tot_aspamt += $sub1_aspamt)
                                        @php($tot_otherout += $sub1_otherout)
                                        [
                                            {},
                                            { text: 'Subtotal', style: 'tableHeader' },
                                            { text: '{{number_format($sub1_oralamt,2)}}', alignment: 'right', bold: true },
                                            { text: 'Subtotal', style: 'tableHeader' },
                                            { text: '{{number_format($sub1_intraamt,2)}}', alignment: 'right', bold: true },
                                            { text: 'Subtotal', style: 'tableHeader' },
                                            { text: '{{number_format($sub1_otheramt,2)}}', alignment: 'right', bold: true },
                                            { text: 'Subtotal', style: 'tableHeader' },
                                            { text: '{{number_format($sub1_urineamt,2)}}', alignment: 'right', bold: true },
                                            { text: '{{number_format($sub1_vomitamt,2)}}', alignment: 'right', bold: true },
                                            { text: '{{number_format($sub1_aspamt,2)}}', alignment: 'right', bold: true },
                                            { text: '{{number_format($sub1_otherout,2)}}', alignment: 'right', bold: true },
                                        ],
                                        @php($sub2_oralamt = 0)
                                        @php($sub2_intraamt = 0)
                                        @php($sub2_otheramt = 0)
                                        @php($sub2_urineamt = 0)
                                        @php($sub2_vomitamt = 0)
                                        @php($sub2_aspamt = 0)
                                        @php($sub2_otherout = 0)
                                        [
                                            { text: '15:00' },
                                            { text: `{!!$io_value->oraltype9!!}` },
                                            { text: '{{number_format($io_value->oralamt9,2)}}', alignment: 'right' },
                                            { text: `{!!$io_value->intratype9!!}` },
                                            { text: '{{number_format($io_value->intraamt9,2)}}', alignment: 'right' },
                                            { text: `{!!$io_value->othertype9!!}` },
                                            { text: '{{number_format($io_value->otheramt9,2)}}', alignment: 'right' },
                                            { text: '15:00' },
                                            { text: '{{number_format($io_value->urineamt9,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->vomitamt9,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->aspamt9,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->otherout9,2)}}', alignment: 'right' },
                                        ],
                                        @php($sub2_oralamt += $io_value->oralamt9)
                                        @php($sub2_intraamt += $io_value->intraamt9)
                                        @php($sub2_otheramt += $io_value->otheramt9)
                                        @php($sub2_urineamt += $io_value->urineamt9)
                                        @php($sub2_vomitamt += $io_value->vomitamt9)
                                        @php($sub2_aspamt += $io_value->aspamt9)
                                        @php($sub2_otherout += $io_value->otherout9)
                                        [
                                            { text: '16:00' },
                                            { text: `{!!$io_value->oraltype10!!}` },
                                            { text: '{{number_format($io_value->oralamt10,2)}}', alignment: 'right' },
                                            { text: `{!!$io_value->intratype10!!}` },
                                            { text: '{{number_format($io_value->intraamt10,2)}}', alignment: 'right' },
                                            { text: `{!!$io_value->othertype10!!}` },
                                            { text: '{{number_format($io_value->otheramt10,2)}}', alignment: 'right' },
                                            { text: '16:00' },
                                            { text: '{{number_format($io_value->urineamt10,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->vomitamt10,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->aspamt10,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->otherout10,2)}}', alignment: 'right' },
                                        ],
                                        @php($sub2_oralamt += $io_value->oralamt10)
                                        @php($sub2_intraamt += $io_value->intraamt10)
                                        @php($sub2_otheramt += $io_value->otheramt10)
                                        @php($sub2_urineamt += $io_value->urineamt10)
                                        @php($sub2_vomitamt += $io_value->vomitamt10)
                                        @php($sub2_aspamt += $io_value->aspamt10)
                                        @php($sub2_otherout += $io_value->otherout10)
                                        [
                                            { text: '17:00' },
                                            { text: `{!!$io_value->oraltype11!!}` },
                                            { text: '{{number_format($io_value->oralamt11,2)}}', alignment: 'right' },
                                            { text: `{!!$io_value->intratype11!!}` },
                                            { text: '{{number_format($io_value->intraamt11,2)}}', alignment: 'right' },
                                            { text: `{!!$io_value->othertype11!!}` },
                                            { text: '{{number_format($io_value->otheramt11,2)}}', alignment: 'right' },
                                            { text: '17:00' },
                                            { text: '{{number_format($io_value->urineamt11,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->vomitamt11,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->aspamt11,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->otherout11,2)}}', alignment: 'right' },
                                        ],
                                        @php($sub2_oralamt += $io_value->oralamt11)
                                        @php($sub2_intraamt += $io_value->intraamt11)
                                        @php($sub2_otheramt += $io_value->otheramt11)
                                        @php($sub2_urineamt += $io_value->urineamt11)
                                        @php($sub2_vomitamt += $io_value->vomitamt11)
                                        @php($sub2_aspamt += $io_value->aspamt11)
                                        @php($sub2_otherout += $io_value->otherout11)
                                        [
                                            { text: '18:00' },
                                            { text: `{!!$io_value->oraltype12!!}` },
                                            { text: '{{number_format($io_value->oralamt12,2)}}', alignment: 'right' },
                                            { text: `{!!$io_value->intratype12!!}` },
                                            { text: '{{number_format($io_value->intraamt12,2)}}', alignment: 'right' },
                                            { text: `{!!$io_value->othertype12!!}` },
                                            { text: '{{number_format($io_value->otheramt12,2)}}', alignment: 'right' },
                                            { text: '18:00' },
                                            { text: '{{number_format($io_value->urineamt12,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->vomitamt12,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->aspamt12,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->otherout12,2)}}', alignment: 'right' },
                                        ],
                                        @php($sub2_oralamt += $io_value->oralamt12)
                                        @php($sub2_intraamt += $io_value->intraamt12)
                                        @php($sub2_otheramt += $io_value->otheramt12)
                                        @php($sub2_urineamt += $io_value->urineamt12)
                                        @php($sub2_vomitamt += $io_value->vomitamt12)
                                        @php($sub2_aspamt += $io_value->aspamt12)
                                        @php($sub2_otherout += $io_value->otherout12)
                                        [
                                            { text: '19:00' },
                                            { text: `{!!$io_value->oraltype13!!}` },
                                            { text: '{{number_format($io_value->oralamt13,2)}}', alignment: 'right' },
                                            { text: `{!!$io_value->intratype13!!}` },
                                            { text: '{{number_format($io_value->intraamt13,2)}}', alignment: 'right' },
                                            { text: `{!!$io_value->othertype13!!}` },
                                            { text: '{{number_format($io_value->otheramt13,2)}}', alignment: 'right' },
                                            { text: '19:00' },
                                            { text: '{{number_format($io_value->urineamt13,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->vomitamt13,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->aspamt13,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->otherout13,2)}}', alignment: 'right' },
                                        ],
                                        @php($sub2_oralamt += $io_value->oralamt13)
                                        @php($sub2_intraamt += $io_value->intraamt13)
                                        @php($sub2_otheramt += $io_value->otheramt13)
                                        @php($sub2_urineamt += $io_value->urineamt13)
                                        @php($sub2_vomitamt += $io_value->vomitamt13)
                                        @php($sub2_aspamt += $io_value->aspamt13)
                                        @php($sub2_otherout += $io_value->otherout13)
                                        [
                                            { text: '20:00' },
                                            { text: `{!!$io_value->oraltype14!!}` },
                                            { text: '{{number_format($io_value->oralamt14,2)}}', alignment: 'right' },
                                            { text: `{!!$io_value->intratype14!!}` },
                                            { text: '{{number_format($io_value->intraamt14,2)}}', alignment: 'right' },
                                            { text: `{!!$io_value->othertype14!!}` },
                                            { text: '{{number_format($io_value->otheramt14,2)}}', alignment: 'right' },
                                            { text: '20:00' },
                                            { text: '{{number_format($io_value->urineamt14,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->vomitamt14,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->aspamt14,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->otherout14,2)}}', alignment: 'right' },
                                        ],
                                        @php($sub2_oralamt += $io_value->oralamt14)
                                        @php($sub2_intraamt += $io_value->intraamt14)
                                        @php($sub2_otheramt += $io_value->otheramt14)
                                        @php($sub2_urineamt += $io_value->urineamt14)
                                        @php($sub2_vomitamt += $io_value->vomitamt14)
                                        @php($sub2_aspamt += $io_value->aspamt14)
                                        @php($sub2_otherout += $io_value->otherout14)
                                        [
                                            { text: '21:00' },
                                            { text: `{!!$io_value->oraltype15!!}` },
                                            { text: '{{number_format($io_value->oralamt15,2)}}', alignment: 'right' },
                                            { text: `{!!$io_value->intratype15!!}` },
                                            { text: '{{number_format($io_value->intraamt15,2)}}', alignment: 'right' },
                                            { text: `{!!$io_value->othertype15!!}` },
                                            { text: '{{number_format($io_value->otheramt15,2)}}', alignment: 'right' },
                                            { text: '21:00' },
                                            { text: '{{number_format($io_value->urineamt15,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->vomitamt15,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->aspamt15,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->otherout15,2)}}', alignment: 'right' },
                                        ],
                                        @php($sub2_oralamt += $io_value->oralamt15)
                                        @php($sub2_intraamt += $io_value->intraamt15)
                                        @php($sub2_otheramt += $io_value->otheramt15)
                                        @php($sub2_urineamt += $io_value->urineamt15)
                                        @php($sub2_vomitamt += $io_value->vomitamt15)
                                        @php($sub2_aspamt += $io_value->aspamt15)
                                        @php($sub2_otherout += $io_value->otherout15)
                                        [
                                            { text: '22:00' },
                                            { text: `{!!$io_value->oraltype16!!}` },
                                            { text: '{{number_format($io_value->oralamt16,2)}}', alignment: 'right' },
                                            { text: `{!!$io_value->intratype16!!}` },
                                            { text: '{{number_format($io_value->intraamt16,2)}}', alignment: 'right' },
                                            { text: `{!!$io_value->othertype16!!}` },
                                            { text: '{{number_format($io_value->otheramt16,2)}}', alignment: 'right' },
                                            { text: '22:00' },
                                            { text: '{{number_format($io_value->urineamt16,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->vomitamt16,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->aspamt16,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->otherout16,2)}}', alignment: 'right' },
                                        ],
                                        @php($sub2_oralamt += $io_value->oralamt16)
                                        @php($sub2_intraamt += $io_value->intraamt16)
                                        @php($sub2_otheramt += $io_value->otheramt16)
                                        @php($sub2_urineamt += $io_value->urineamt16)
                                        @php($sub2_vomitamt += $io_value->vomitamt16)
                                        @php($sub2_aspamt += $io_value->aspamt16)
                                        @php($sub2_otherout += $io_value->otherout16)
                                        
                                        @php($tot_oralamt += $sub2_oralamt)
                                        @php($tot_intraamt += $sub2_intraamt)
                                        @php($tot_otheramt += $sub2_otheramt)
                                        @php($tot_urineamt += $sub2_urineamt)
                                        @php($tot_vomitamt += $sub2_vomitamt)
                                        @php($tot_aspamt += $sub2_aspamt)
                                        @php($tot_otherout += $sub2_otherout)
                                        [
                                            {},
                                            { text: 'Subtotal', style: 'tableHeader' },
                                            { text: '{{number_format($sub2_oralamt,2)}}', alignment: 'right', bold: true },
                                            { text: 'Subtotal', style: 'tableHeader' },
                                            { text: '{{number_format($sub2_intraamt,2)}}', alignment: 'right', bold: true },
                                            { text: 'Subtotal', style: 'tableHeader' },
                                            { text: '{{number_format($sub2_otheramt,2)}}', alignment: 'right', bold: true },
                                            { text: 'Subtotal', style: 'tableHeader' },
                                            { text: '{{number_format($sub2_urineamt,2)}}', alignment: 'right', bold: true },
                                            { text: '{{number_format($sub2_vomitamt,2)}}', alignment: 'right', bold: true },
                                            { text: '{{number_format($sub2_aspamt,2)}}', alignment: 'right', bold: true },
                                            { text: '{{number_format($sub2_otherout,2)}}', alignment: 'right', bold: true },
                                        ],
                                        @php($sub3_oralamt = 0)
                                        @php($sub3_intraamt = 0)
                                        @php($sub3_otheramt = 0)
                                        @php($sub3_urineamt = 0)
                                        @php($sub3_vomitamt = 0)
                                        @php($sub3_aspamt = 0)
                                        @php($sub3_otherout = 0)
                                        [
                                            { text: '23:00' },
                                            { text: `{!!$io_value->oraltype17!!}` },
                                            { text: '{{number_format($io_value->oralamt17,2)}}', alignment: 'right' },
                                            { text: `{!!$io_value->intratype17!!}` },
                                            { text: '{{number_format($io_value->intraamt17,2)}}', alignment: 'right' },
                                            { text: `{!!$io_value->othertype17!!}` },
                                            { text: '{{number_format($io_value->otheramt17,2)}}', alignment: 'right' },
                                            { text: '23:00' },
                                            { text: '{{number_format($io_value->urineamt17,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->vomitamt17,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->aspamt17,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->otherout17,2)}}', alignment: 'right' },
                                        ],
                                        @php($sub3_oralamt += $io_value->oralamt17)
                                        @php($sub3_intraamt += $io_value->intraamt17)
                                        @php($sub3_otheramt += $io_value->otheramt17)
                                        @php($sub3_urineamt += $io_value->urineamt17)
                                        @php($sub3_vomitamt += $io_value->vomitamt17)
                                        @php($sub3_aspamt += $io_value->aspamt17)
                                        @php($sub3_otherout += $io_value->otherout17)
                                        [
                                            { text: '00:00' },
                                            { text: `{!!$io_value->oraltype18!!}` },
                                            { text: '{{number_format($io_value->oralamt18,2)}}', alignment: 'right' },
                                            { text: `{!!$io_value->intratype18!!}` },
                                            { text: '{{number_format($io_value->intraamt18,2)}}', alignment: 'right' },
                                            { text: `{!!$io_value->othertype18!!}` },
                                            { text: '{{number_format($io_value->otheramt18,2)}}', alignment: 'right' },
                                            { text: '00:00' },
                                            { text: '{{number_format($io_value->urineamt18,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->vomitamt18,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->aspamt18,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->otherout18,2)}}', alignment: 'right' },
                                        ],
                                        @php($sub3_oralamt += $io_value->oralamt18)
                                        @php($sub3_intraamt += $io_value->intraamt18)
                                        @php($sub3_otheramt += $io_value->otheramt18)
                                        @php($sub3_urineamt += $io_value->urineamt18)
                                        @php($sub3_vomitamt += $io_value->vomitamt18)
                                        @php($sub3_aspamt += $io_value->aspamt18)
                                        @php($sub3_otherout += $io_value->otherout18)
                                        [
                                            { text: '01:00' },
                                            { text: `{!!$io_value->oraltype19!!}` },
                                            { text: '{{number_format($io_value->oralamt19,2)}}', alignment: 'right' },
                                            { text: `{!!$io_value->intratype19!!}` },
                                            { text: '{{number_format($io_value->intraamt19,2)}}', alignment: 'right' },
                                            { text: `{!!$io_value->othertype19!!}` },
                                            { text: '{{number_format($io_value->otheramt19,2)}}', alignment: 'right' },
                                            { text: '01:00' },
                                            { text: '{{number_format($io_value->urineamt19,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->vomitamt19,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->aspamt19,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->otherout19,2)}}', alignment: 'right' },
                                        ],
                                        @php($sub3_oralamt += $io_value->oralamt19)
                                        @php($sub3_intraamt += $io_value->intraamt19)
                                        @php($sub3_otheramt += $io_value->otheramt19)
                                        @php($sub3_urineamt += $io_value->urineamt19)
                                        @php($sub3_vomitamt += $io_value->vomitamt19)
                                        @php($sub3_aspamt += $io_value->aspamt19)
                                        @php($sub3_otherout += $io_value->otherout19)
                                        [
                                            { text: '02:00' },
                                            { text: `{!!$io_value->oraltype20!!}` },
                                            { text: '{{number_format($io_value->oralamt20,2)}}', alignment: 'right' },
                                            { text: `{!!$io_value->intratype20!!}` },
                                            { text: '{{number_format($io_value->intraamt20,2)}}', alignment: 'right' },
                                            { text: `{!!$io_value->othertype20!!}` },
                                            { text: '{{number_format($io_value->otheramt20,2)}}', alignment: 'right' },
                                            { text: '02:00' },
                                            { text: '{{number_format($io_value->urineamt20,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->vomitamt20,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->aspamt20,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->otherout20,2)}}', alignment: 'right' },
                                        ],
                                        @php($sub3_oralamt += $io_value->oralamt20)
                                        @php($sub3_intraamt += $io_value->intraamt20)
                                        @php($sub3_otheramt += $io_value->otheramt20)
                                        @php($sub3_urineamt += $io_value->urineamt20)
                                        @php($sub3_vomitamt += $io_value->vomitamt20)
                                        @php($sub3_aspamt += $io_value->aspamt20)
                                        @php($sub3_otherout += $io_value->otherout20)
                                        [
                                            { text: '03:00' },
                                            { text: `{!!$io_value->oraltype21!!}` },
                                            { text: '{{number_format($io_value->oralamt21,2)}}', alignment: 'right' },
                                            { text: `{!!$io_value->intratype21!!}` },
                                            { text: '{{number_format($io_value->intraamt21,2)}}', alignment: 'right' },
                                            { text: `{!!$io_value->othertype21!!}` },
                                            { text: '{{number_format($io_value->otheramt21,2)}}', alignment: 'right' },
                                            { text: '03:00' },
                                            { text: '{{number_format($io_value->urineamt21,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->vomitamt21,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->aspamt21,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->otherout21,2)}}', alignment: 'right' },
                                        ],
                                        @php($sub3_oralamt += $io_value->oralamt21)
                                        @php($sub3_intraamt += $io_value->intraamt21)
                                        @php($sub3_otheramt += $io_value->otheramt21)
                                        @php($sub3_urineamt += $io_value->urineamt21)
                                        @php($sub3_vomitamt += $io_value->vomitamt21)
                                        @php($sub3_aspamt += $io_value->aspamt21)
                                        @php($sub3_otherout += $io_value->otherout21)
                                        [
                                            { text: '04:00' },
                                            { text: `{!!$io_value->oraltype22!!}` },
                                            { text: '{{number_format($io_value->oralamt22,2)}}', alignment: 'right' },
                                            { text: `{!!$io_value->intratype22!!}` },
                                            { text: '{{number_format($io_value->intraamt22,2)}}', alignment: 'right' },
                                            { text: `{!!$io_value->othertype22!!}` },
                                            { text: '{{number_format($io_value->otheramt22,2)}}', alignment: 'right' },
                                            { text: '04:00' },
                                            { text: '{{number_format($io_value->urineamt22,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->vomitamt22,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->aspamt22,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->otherout22,2)}}', alignment: 'right' },
                                        ],
                                        @php($sub3_oralamt += $io_value->oralamt22)
                                        @php($sub3_intraamt += $io_value->intraamt22)
                                        @php($sub3_otheramt += $io_value->otheramt22)
                                        @php($sub3_urineamt += $io_value->urineamt22)
                                        @php($sub3_vomitamt += $io_value->vomitamt22)
                                        @php($sub3_aspamt += $io_value->aspamt22)
                                        @php($sub3_otherout += $io_value->otherout22)
                                        [
                                            { text: '05:00' },
                                            { text: `{!!$io_value->oraltype23!!}` },
                                            { text: '{{number_format($io_value->oralamt23,2)}}', alignment: 'right' },
                                            { text: `{!!$io_value->intratype23!!}` },
                                            { text: '{{number_format($io_value->intraamt23,2)}}', alignment: 'right' },
                                            { text: `{!!$io_value->othertype23!!}` },
                                            { text: '{{number_format($io_value->otheramt23,2)}}', alignment: 'right' },
                                            { text: '05:00' },
                                            { text: '{{number_format($io_value->urineamt23,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->vomitamt23,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->aspamt23,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->otherout23,2)}}', alignment: 'right' },
                                        ],
                                        @php($sub3_oralamt += $io_value->oralamt23)
                                        @php($sub3_intraamt += $io_value->intraamt23)
                                        @php($sub3_otheramt += $io_value->otheramt23)
                                        @php($sub3_urineamt += $io_value->urineamt23)
                                        @php($sub3_vomitamt += $io_value->vomitamt23)
                                        @php($sub3_aspamt += $io_value->aspamt23)
                                        @php($sub3_otherout += $io_value->otherout23)
                                        [
                                            { text: '06:00' },
                                            { text: `{!!$io_value->oraltype24!!}` },
                                            { text: '{{number_format($io_value->oralamt24,2)}}', alignment: 'right' },
                                            { text: `{!!$io_value->intratype24!!}` },
                                            { text: '{{number_format($io_value->intraamt24,2)}}', alignment: 'right' },
                                            { text: `{!!$io_value->othertype24!!}` },
                                            { text: '{{number_format($io_value->otheramt24,2)}}', alignment: 'right' },
                                            { text: '06:00' },
                                            { text: '{{number_format($io_value->urineamt24,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->vomitamt24,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->aspamt24,2)}}', alignment: 'right' },
                                            { text: '{{number_format($io_value->otherout24,2)}}', alignment: 'right' },
                                        ],
                                        @php($sub3_oralamt += $io_value->oralamt24)
                                        @php($sub3_intraamt += $io_value->intraamt24)
                                        @php($sub3_otheramt += $io_value->otheramt24)
                                        @php($sub3_urineamt += $io_value->urineamt24)
                                        @php($sub3_vomitamt += $io_value->vomitamt24)
                                        @php($sub3_aspamt += $io_value->aspamt24)
                                        @php($sub3_otherout += $io_value->otherout24)
                                        
                                        @php($tot_oralamt += $sub3_oralamt)
                                        @php($tot_intraamt += $sub3_intraamt)
                                        @php($tot_otheramt += $sub3_otheramt)
                                        @php($tot_urineamt += $sub3_urineamt)
                                        @php($tot_vomitamt += $sub3_vomitamt)
                                        @php($tot_aspamt += $sub3_aspamt)
                                        @php($tot_otherout += $sub3_otherout)
                                        [
                                            {},
                                            { text: 'Subtotal', style: 'tableHeader' },
                                            { text: '{{number_format($sub3_oralamt,2)}}', alignment: 'right', bold: true },
                                            { text: 'Subtotal', style: 'tableHeader' },
                                            { text: '{{number_format($sub3_intraamt,2)}}', alignment: 'right', bold: true },
                                            { text: 'Subtotal', style: 'tableHeader' },
                                            { text: '{{number_format($sub3_otheramt,2)}}', alignment: 'right', bold: true },
                                            { text: 'Subtotal', style: 'tableHeader' },
                                            { text: '{{number_format($sub3_urineamt,2)}}', alignment: 'right', bold: true },
                                            { text: '{{number_format($sub3_vomitamt,2)}}', alignment: 'right', bold: true },
                                            { text: '{{number_format($sub3_aspamt,2)}}', alignment: 'right', bold: true },
                                            { text: '{{number_format($sub3_otherout,2)}}', alignment: 'right', bold: true },
                                        ],
                                        [
                                            {},
                                            { text: 'Grand Total', style: 'tableHeader' },
                                            { text: '{{number_format($tot_oralamt,2)}}', alignment: 'right', bold: true },
                                            { text: 'Grand Total', style: 'tableHeader' },
                                            { text: '{{number_format($tot_intraamt,2)}}', alignment: 'right', bold: true },
                                            { text: 'Grand Total', style: 'tableHeader' },
                                            { text: '{{number_format($tot_otheramt,2)}}', alignment: 'right', bold: true },
                                            { text: 'Grand Total', style: 'tableHeader' },
                                            { text: '{{number_format($tot_urineamt,2)}}', alignment: 'right', bold: true },
                                            { text: '{{number_format($tot_vomitamt,2)}}', alignment: 'right', bold: true },
                                            { text: '{{number_format($tot_aspamt,2)}}', alignment: 'right', bold: true },
                                            { text: '{{number_format($tot_otherout,2)}}', alignment: 'right', bold: true },
                                        ],
                                    ]
                                },
                                // layout: 'lightHorizontalLines',
                            },
                            @endif
                        @endforeach
                        { text: '', alignment: 'left', fontSize: 9, pageBreak: 'after' },
                    @endforeach
                ],
                styles: {
                    header: {
                        fontSize: 14,
                        bold: true,
                        margin: [0, 10, 0, 0]
                    },
                    subheader: {
                        fontSize: 16,
                        bold: true,
                        margin: [0, 10, 0, 5]
                    },
                    tableExample: {
                        fontSize: 9,
                        margin: [0, 5, 0, 10]
                    },
                    tableDetail: {
                        fontSize: 7.5,
                        margin: [0, 0, 0, 8]
                    },
                    tableHeader: {
                        bold: true,
                        fontSize: 7.5,
                        margin: [0, 0, 0, 0],
                        color: 'black'
                    },
                    tblHeader: {
                        bold: true,
                        fontSize: 12,
                        margin: [0, 0, 0, 0],
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
            //     document.getElementById('pdfPreview').data = base64data;
            // });
            
            pdfMake.createPdf(docDefinition).getDataUrl(function (dataURL){
                $('#pdfiframe').attr('src',dataURL);
            });
        });
        
        function make_header(){
            
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
        <iframe id="pdfiframe" width="100%" height="100%" src="" frameborder="0" style="width: 99vw;height: 99vh;"></iframe>
    </body>
</html>