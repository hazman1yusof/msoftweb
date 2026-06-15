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
                                        { text: 'Name', fontSize: 9, bold: true, alignment: 'left' },
                                        { text: ': {{$pat_mast->Name}}', fontSize: 9, alignment: 'left' },
                                    ],
                                    [
                                        { text: 'MRN', fontSize: 9, bold: true, alignment: 'left' },
                                        { text: ': {{$pat_mast->MRN}}', fontSize: 9, alignment: 'left' },
                                    ],
                                    [
                                        { text: 'Date', fontSize: 9, bold: true, alignment: 'left' },
                                        { text: ': {{\Carbon\Carbon::parse($rec_value->recorddate)->format('d/m/Y')}}', fontSize: 9, alignment: 'left' },
                                    ]
                                ]
                            },
                            layout: 'noBorders',
                        },
                        @php($sub1_oralamt = 0) // for first subtotal
                        @php($sub1_intraamt = 0)
                        @php($sub1_otheramt = 0)
                        @php($sub1_urineamt = 0)
                        @php($sub1_otherout = 0)
                        @php($sub1_vomitamt = 0)
                        @php($sub1_aspamt = 0)
                        
                        @php($tot_oralamt = 0) // for grand total
                        @php($tot_intraamt = 0)
                        @php($tot_otheramt = 0)
                        @php($tot_urineamt = 0)
                        @php($tot_otherout = 0)
                        @php($tot_vomitamt = 0)
                        @php($tot_aspamt = 0)
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
                                            { text: 'OUT', style: 'tableHeader', colSpan: 5, alignment: 'center' },{},{},{},{},
                                        ],
                                        [
                                            {},
                                            { text: 'ORAL', style: 'tableHeader', colSpan: 2, alignment: 'center' },{},
                                            { text: 'INTRA-VENA', style: 'tableHeader', colSpan: 2, alignment: 'center' },{},
                                            { text: 'MEDICATION/BLOOD', style: 'tableHeader', colSpan: 2, alignment: 'center' },{},
                                            { text: 'TIME', style: 'tableHeader', rowSpan: 2, alignment: 'center' },
                                            { text: 'PU \n(ml)', style: 'tableHeader', rowSpan: 2, alignment: 'center' },
                                            { text: 'BO \n(freq)', style: 'tableHeader', rowSpan: 2, alignment: 'center' },
                                            { text: 'VOMIT \n(freq)', style: 'tableHeader', rowSpan: 2, alignment: 'center' },
                                            { text: 'ASPIRATE/OTHERS (ml)', style: 'tableHeader', rowSpan: 2, alignment: 'center' },
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
                                            { text: '07:00', bold: true },
                                            { text: `{!!$io_value->oraltype1!!}` },
                                            { text: '{{$io_value->oralamt1}}', alignment: 'right' },
                                            { text: `{!!$io_value->intratype1!!}` },
                                            { text: '{{$io_value->intraamt1}}', alignment: 'right' },
                                            { text: `{!!$io_value->othertype1!!}` },
                                            { text: '{{$io_value->otheramt1}}', alignment: 'right' },
                                            { text: '07:00', bold: true },
                                            { text: '{{$io_value->urineamt1}}', alignment: 'right' },
                                            { text: '{{$io_value->otherout1}}', alignment: 'right' },
                                            { text: '{{$io_value->vomitamt1}}', alignment: 'right' },
                                            { text: '{{$io_value->aspamt1}}', alignment: 'right' },
                                        ],
                                        @php($sub1_oralamt += $io_value->oralamt1)
                                        @php($sub1_intraamt += $io_value->intraamt1)
                                        @php($sub1_otheramt += $io_value->otheramt1)
                                        @php($sub1_urineamt += $io_value->urineamt1)
                                        @php($sub1_otherout += $io_value->otherout1)
                                        @php($sub1_vomitamt += $io_value->vomitamt1)
                                        @php($sub1_aspamt += $io_value->aspamt1)
                                        [
                                            { text: '08:00', bold: true },
                                            { text: `{!!$io_value->oraltype2!!}` },
                                            { text: '{{$io_value->oralamt2}}', alignment: 'right' },
                                            { text: `{!!$io_value->intratype2!!}` },
                                            { text: '{{$io_value->intraamt2}}', alignment: 'right' },
                                            { text: `{!!$io_value->othertype2!!}` },
                                            { text: '{{$io_value->otheramt2}}', alignment: 'right' },
                                            { text: '08:00', bold: true },
                                            { text: '{{$io_value->urineamt2}}', alignment: 'right' },
                                            { text: '{{$io_value->otherout2}}', alignment: 'right' },
                                            { text: '{{$io_value->vomitamt2}}', alignment: 'right' },
                                            { text: '{{$io_value->aspamt2}}', alignment: 'right' },
                                        ],
                                        @php($sub1_oralamt += $io_value->oralamt2)
                                        @php($sub1_intraamt += $io_value->intraamt2)
                                        @php($sub1_otheramt += $io_value->otheramt2)
                                        @php($sub1_urineamt += $io_value->urineamt2)
                                        @php($sub1_otherout += $io_value->otherout2)
                                        @php($sub1_vomitamt += $io_value->vomitamt2)
                                        @php($sub1_aspamt += $io_value->aspamt2)
                                        [
                                            { text: '09:00', bold: true },
                                            { text: `{!!$io_value->oraltype3!!}` },
                                            { text: '{{$io_value->oralamt3}}', alignment: 'right' },
                                            { text: `{!!$io_value->intratype3!!}` },
                                            { text: '{{$io_value->intraamt3}}', alignment: 'right' },
                                            { text: `{!!$io_value->othertype3!!}` },
                                            { text: '{{$io_value->otheramt3}}', alignment: 'right' },
                                            { text: '09:00', bold: true },
                                            { text: '{{$io_value->urineamt3}}', alignment: 'right' },
                                            { text: '{{$io_value->otherout3}}', alignment: 'right' },
                                            { text: '{{$io_value->vomitamt3}}', alignment: 'right' },
                                            { text: '{{$io_value->aspamt3}}', alignment: 'right' },
                                        ],
                                        @php($sub1_oralamt += $io_value->oralamt3)
                                        @php($sub1_intraamt += $io_value->intraamt3)
                                        @php($sub1_otheramt += $io_value->otheramt3)
                                        @php($sub1_urineamt += $io_value->urineamt3)
                                        @php($sub1_otherout += $io_value->otherout3)
                                        @php($sub1_vomitamt += $io_value->vomitamt3)
                                        @php($sub1_aspamt += $io_value->aspamt3)
                                        [
                                            { text: '10:00', bold: true },
                                            { text: `{!!$io_value->oraltype4!!}` },
                                            { text: '{{$io_value->oralamt4}}', alignment: 'right' },
                                            { text: `{!!$io_value->intratype4!!}` },
                                            { text: '{{$io_value->intraamt4}}', alignment: 'right' },
                                            { text: `{!!$io_value->othertype4!!}` },
                                            { text: '{{$io_value->otheramt4}}', alignment: 'right' },
                                            { text: '10:00', bold: true },
                                            { text: '{{$io_value->urineamt4}}', alignment: 'right' },
                                            { text: '{{$io_value->otherout4}}', alignment: 'right' },
                                            { text: '{{$io_value->vomitamt4}}', alignment: 'right' },
                                            { text: '{{$io_value->aspamt4}}', alignment: 'right' },
                                        ],
                                        @php($sub1_oralamt += $io_value->oralamt4)
                                        @php($sub1_intraamt += $io_value->intraamt4)
                                        @php($sub1_otheramt += $io_value->otheramt4)
                                        @php($sub1_urineamt += $io_value->urineamt4)
                                        @php($sub1_otherout += $io_value->otherout4)
                                        @php($sub1_vomitamt += $io_value->vomitamt4)
                                        @php($sub1_aspamt += $io_value->aspamt4)
                                        [
                                            { text: '11:00', bold: true },
                                            { text: `{!!$io_value->oraltype5!!}` },
                                            { text: '{{$io_value->oralamt5}}', alignment: 'right' },
                                            { text: `{!!$io_value->intratype5!!}` },
                                            { text: '{{$io_value->intraamt5}}', alignment: 'right' },
                                            { text: `{!!$io_value->othertype5!!}` },
                                            { text: '{{$io_value->otheramt5}}', alignment: 'right' },
                                            { text: '11:00', bold: true },
                                            { text: '{{$io_value->urineamt5}}', alignment: 'right' },
                                            { text: '{{$io_value->otherout5}}', alignment: 'right' },
                                            { text: '{{$io_value->vomitamt5}}', alignment: 'right' },
                                            { text: '{{$io_value->aspamt5}}', alignment: 'right' },
                                        ],
                                        @php($sub1_oralamt += $io_value->oralamt5)
                                        @php($sub1_intraamt += $io_value->intraamt5)
                                        @php($sub1_otheramt += $io_value->otheramt5)
                                        @php($sub1_urineamt += $io_value->urineamt5)
                                        @php($sub1_otherout += $io_value->otherout5)
                                        @php($sub1_vomitamt += $io_value->vomitamt5)
                                        @php($sub1_aspamt += $io_value->aspamt5)
                                        [
                                            { text: '12:00', bold: true },
                                            { text: `{!!$io_value->oraltype6!!}` },
                                            { text: '{{$io_value->oralamt6}}', alignment: 'right' },
                                            { text: `{!!$io_value->intratype6!!}` },
                                            { text: '{{$io_value->intraamt6}}', alignment: 'right' },
                                            { text: `{!!$io_value->othertype6!!}` },
                                            { text: '{{$io_value->otheramt6}}', alignment: 'right' },
                                            { text: '12:00', bold: true },
                                            { text: '{{$io_value->urineamt6}}', alignment: 'right' },
                                            { text: '{{$io_value->otherout6}}', alignment: 'right' },
                                            { text: '{{$io_value->vomitamt6}}', alignment: 'right' },
                                            { text: '{{$io_value->aspamt6}}', alignment: 'right' },
                                        ],
                                        @php($sub1_oralamt += $io_value->oralamt6)
                                        @php($sub1_intraamt += $io_value->intraamt6)
                                        @php($sub1_otheramt += $io_value->otheramt6)
                                        @php($sub1_urineamt += $io_value->urineamt6)
                                        @php($sub1_otherout += $io_value->otherout6)
                                        @php($sub1_vomitamt += $io_value->vomitamt6)
                                        @php($sub1_aspamt += $io_value->aspamt6)
                                        [
                                            { text: '13:00', bold: true },
                                            { text: `{!!$io_value->oraltype7!!}` },
                                            { text: '{{$io_value->oralamt7}}', alignment: 'right' },
                                            { text: `{!!$io_value->intratype7!!}` },
                                            { text: '{{$io_value->intraamt7}}', alignment: 'right' },
                                            { text: `{!!$io_value->othertype7!!}` },
                                            { text: '{{$io_value->otheramt7}}', alignment: 'right' },
                                            { text: '13:00', bold: true },
                                            { text: '{{$io_value->urineamt7}}', alignment: 'right' },
                                            { text: '{{$io_value->otherout7}}', alignment: 'right' },
                                            { text: '{{$io_value->vomitamt7}}', alignment: 'right' },
                                            { text: '{{$io_value->aspamt7}}', alignment: 'right' },
                                        ],
                                        @php($sub1_oralamt += $io_value->oralamt7)
                                        @php($sub1_intraamt += $io_value->intraamt7)
                                        @php($sub1_otheramt += $io_value->otheramt7)
                                        @php($sub1_urineamt += $io_value->urineamt7)
                                        @php($sub1_otherout += $io_value->otherout7)
                                        @php($sub1_vomitamt += $io_value->vomitamt7)
                                        @php($sub1_aspamt += $io_value->aspamt7)
                                        [
                                            { text: '14:00', bold: true },
                                            { text: `{!!$io_value->oraltype8!!}` },
                                            { text: '{{$io_value->oralamt8}}', alignment: 'right' },
                                            { text: `{!!$io_value->intratype8!!}` },
                                            { text: '{{$io_value->intraamt8}}', alignment: 'right' },
                                            { text: `{!!$io_value->othertype8!!}` },
                                            { text: '{{$io_value->otheramt8}}', alignment: 'right' },
                                            { text: '14:00', bold: true },
                                            { text: '{{$io_value->urineamt8}}', alignment: 'right' },
                                            { text: '{{$io_value->otherout8}}', alignment: 'right' },
                                            { text: '{{$io_value->vomitamt8}}', alignment: 'right' },
                                            { text: '{{$io_value->aspamt8}}', alignment: 'right' },
                                        ],
                                        @php($sub1_oralamt += $io_value->oralamt8)
                                        @php($sub1_intraamt += $io_value->intraamt8)
                                        @php($sub1_otheramt += $io_value->otheramt8)
                                        @php($sub1_urineamt += $io_value->urineamt8)
                                        @php($sub1_otherout += $io_value->otherout8)
                                        @php($sub1_vomitamt += $io_value->vomitamt8)
                                        @php($sub1_aspamt += $io_value->aspamt8)
                                        
                                        @php($tot_oralamt += $sub1_oralamt)
                                        @php($tot_intraamt += $sub1_intraamt)
                                        @php($tot_otheramt += $sub1_otheramt)
                                        @php($tot_urineamt += $sub1_urineamt)
                                        @php($tot_otherout += $sub1_otherout)
                                        @php($tot_vomitamt += $sub1_vomitamt)
                                        @php($tot_aspamt += $sub1_aspamt)
                                        [
                                            { text: 'TOTAL', style: 'tableHeader', alignment: 'center', colSpan: 2 },
                                            {},
                                            { text: '{{$sub1_oralamt}}', alignment: 'right', bold: true },
                                            { text: '{{$sub1_intraamt}}', alignment: 'right', bold: true, colSpan: 2 },
                                            {},
                                            { text: '{{$sub1_otheramt}}', alignment: 'right', bold: true, colSpan: 2 },
                                            {},
                                            { text: 'TOTAL', style: 'tableHeader' },
                                            { text: '{{$sub1_urineamt}}', alignment: 'right', bold: true },
                                            { text: '{{$sub1_otherout}}', alignment: 'right', bold: true },
                                            { text: '{{$sub1_vomitamt}}', alignment: 'right', bold: true },
                                            { text: '{{$sub1_aspamt}}', alignment: 'right', bold: true },
                                        ],
                                        @php($tot_intake_morning = $sub1_oralamt + $sub1_intraamt + $sub1_otheramt)
                                        @php($tot_output_morning = $sub1_urineamt + $sub1_aspamt)
                                        [
                                            { text: 'TOTAL INTAKE\n(MORNING)', style: 'tableHeader', alignment: 'center', colSpan: 2 },
                                            {},
                                            { text: '{{$tot_intake_morning}}', alignment: 'center', bold: true, colSpan: 5 },
                                            {},
                                            {},
                                            {},
                                            {},
                                            { text: 'TOTAL\n OUTPUT', style: 'tableHeader' },
                                            @if(!empty($sub1_otherout))
                                                { text: '{{$tot_output_morning}}, BO (X{{$sub1_otherout}})', alignment: 'center', bold: true, colSpan: 4 },
                                            @else
                                                { text: '{{$tot_output_morning}}, BNO', alignment: 'center', bold: true, colSpan: 4 },
                                            @endif
                                            {},
                                            {},
                                            {},
                                        ],
                                        @php($sub2_oralamt = 0)
                                        @php($sub2_intraamt = 0)
                                        @php($sub2_otheramt = 0)
                                        @php($sub2_urineamt = 0)
                                        @php($sub2_otherout = 0)
                                        @php($sub2_vomitamt = 0)
                                        @php($sub2_aspamt = 0)
                                        [
                                            { text: '15:00', bold: true },
                                            { text: `{!!$io_value->oraltype9!!}` },
                                            { text: '{{$io_value->oralamt9}}', alignment: 'right' },
                                            { text: `{!!$io_value->intratype9!!}` },
                                            { text: '{{$io_value->intraamt9}}', alignment: 'right' },
                                            { text: `{!!$io_value->othertype9!!}` },
                                            { text: '{{$io_value->otheramt9}}', alignment: 'right' },
                                            { text: '15:00', bold: true },
                                            { text: '{{$io_value->urineamt9}}', alignment: 'right' },
                                            { text: '{{$io_value->otherout9}}', alignment: 'right' },
                                            { text: '{{$io_value->vomitamt9}}', alignment: 'right' },
                                            { text: '{{$io_value->aspamt9}}', alignment: 'right' },
                                        ],
                                        @php($sub2_oralamt += $io_value->oralamt9)
                                        @php($sub2_intraamt += $io_value->intraamt9)
                                        @php($sub2_otheramt += $io_value->otheramt9)
                                        @php($sub2_urineamt += $io_value->urineamt9)
                                        @php($sub2_otherout += $io_value->otherout9)
                                        @php($sub2_vomitamt += $io_value->vomitamt9)
                                        @php($sub2_aspamt += $io_value->aspamt9)
                                        [
                                            { text: '16:00', bold: true },
                                            { text: `{!!$io_value->oraltype10!!}` },
                                            { text: '{{$io_value->oralamt10}}', alignment: 'right' },
                                            { text: `{!!$io_value->intratype10!!}` },
                                            { text: '{{$io_value->intraamt10}}', alignment: 'right' },
                                            { text: `{!!$io_value->othertype10!!}` },
                                            { text: '{{$io_value->otheramt10}}', alignment: 'right' },
                                            { text: '16:00', bold: true },
                                            { text: '{{$io_value->urineamt10}}', alignment: 'right' },
                                            { text: '{{$io_value->otherout10}}', alignment: 'right' },
                                            { text: '{{$io_value->vomitamt10}}', alignment: 'right' },
                                            { text: '{{$io_value->aspamt10}}', alignment: 'right' },
                                        ],
                                        @php($sub2_oralamt += $io_value->oralamt10)
                                        @php($sub2_intraamt += $io_value->intraamt10)
                                        @php($sub2_otheramt += $io_value->otheramt10)
                                        @php($sub2_urineamt += $io_value->urineamt10)
                                        @php($sub2_otherout += $io_value->otherout10)
                                        @php($sub2_vomitamt += $io_value->vomitamt10)
                                        @php($sub2_aspamt += $io_value->aspamt10)
                                        [
                                            { text: '17:00', bold: true },
                                            { text: `{!!$io_value->oraltype11!!}` },
                                            { text: '{{$io_value->oralamt11}}', alignment: 'right' },
                                            { text: `{!!$io_value->intratype11!!}` },
                                            { text: '{{$io_value->intraamt11}}', alignment: 'right' },
                                            { text: `{!!$io_value->othertype11!!}` },
                                            { text: '{{$io_value->otheramt11}}', alignment: 'right' },
                                            { text: '17:00', bold: true },
                                            { text: '{{$io_value->urineamt11}}', alignment: 'right' },
                                            { text: '{{$io_value->otherout11}}', alignment: 'right' },
                                            { text: '{{$io_value->vomitamt11}}', alignment: 'right' },
                                            { text: '{{$io_value->aspamt11}}', alignment: 'right' },
                                        ],
                                        @php($sub2_oralamt += $io_value->oralamt11)
                                        @php($sub2_intraamt += $io_value->intraamt11)
                                        @php($sub2_otheramt += $io_value->otheramt11)
                                        @php($sub2_urineamt += $io_value->urineamt11)
                                        @php($sub2_otherout += $io_value->otherout11)
                                        @php($sub2_vomitamt += $io_value->vomitamt11)
                                        @php($sub2_aspamt += $io_value->aspamt11)
                                        [
                                            { text: '18:00', bold: true },
                                            { text: `{!!$io_value->oraltype12!!}` },
                                            { text: '{{$io_value->oralamt12}}', alignment: 'right' },
                                            { text: `{!!$io_value->intratype12!!}` },
                                            { text: '{{$io_value->intraamt12}}', alignment: 'right' },
                                            { text: `{!!$io_value->othertype12!!}` },
                                            { text: '{{$io_value->otheramt12}}', alignment: 'right' },
                                            { text: '18:00', bold: true },
                                            { text: '{{$io_value->urineamt12}}', alignment: 'right' },
                                            { text: '{{$io_value->otherout12}}', alignment: 'right' },
                                            { text: '{{$io_value->vomitamt12}}', alignment: 'right' },
                                            { text: '{{$io_value->aspamt12}}', alignment: 'right' },
                                        ],
                                        @php($sub2_oralamt += $io_value->oralamt12)
                                        @php($sub2_intraamt += $io_value->intraamt12)
                                        @php($sub2_otheramt += $io_value->otheramt12)
                                        @php($sub2_urineamt += $io_value->urineamt12)
                                        @php($sub2_otherout += $io_value->otherout12)
                                        @php($sub2_vomitamt += $io_value->vomitamt12)
                                        @php($sub2_aspamt += $io_value->aspamt12)
                                        [
                                            { text: '19:00', bold: true },
                                            { text: `{!!$io_value->oraltype13!!}` },
                                            { text: '{{$io_value->oralamt13}}', alignment: 'right' },
                                            { text: `{!!$io_value->intratype13!!}` },
                                            { text: '{{$io_value->intraamt13}}', alignment: 'right' },
                                            { text: `{!!$io_value->othertype13!!}` },
                                            { text: '{{$io_value->otheramt13}}', alignment: 'right' },
                                            { text: '19:00', bold: true },
                                            { text: '{{$io_value->urineamt13}}', alignment: 'right' },
                                            { text: '{{$io_value->otherout13}}', alignment: 'right' },
                                            { text: '{{$io_value->vomitamt13}}', alignment: 'right' },
                                            { text: '{{$io_value->aspamt13}}', alignment: 'right' },
                                        ],
                                        @php($sub2_oralamt += $io_value->oralamt13)
                                        @php($sub2_intraamt += $io_value->intraamt13)
                                        @php($sub2_otheramt += $io_value->otheramt13)
                                        @php($sub2_urineamt += $io_value->urineamt13)
                                        @php($sub2_otherout += $io_value->otherout13)
                                        @php($sub2_vomitamt += $io_value->vomitamt13)
                                        @php($sub2_aspamt += $io_value->aspamt13)
                                        [
                                            { text: '20:00', bold: true },
                                            { text: `{!!$io_value->oraltype14!!}` },
                                            { text: '{{$io_value->oralamt14}}', alignment: 'right' },
                                            { text: `{!!$io_value->intratype14!!}` },
                                            { text: '{{$io_value->intraamt14}}', alignment: 'right' },
                                            { text: `{!!$io_value->othertype14!!}` },
                                            { text: '{{$io_value->otheramt14}}', alignment: 'right' },
                                            { text: '20:00', bold: true },
                                            { text: '{{$io_value->urineamt14}}', alignment: 'right' },
                                            { text: '{{$io_value->otherout14}}', alignment: 'right' },
                                            { text: '{{$io_value->vomitamt14}}', alignment: 'right' },
                                            { text: '{{$io_value->aspamt14}}', alignment: 'right' },
                                        ],
                                        @php($sub2_oralamt += $io_value->oralamt14)
                                        @php($sub2_intraamt += $io_value->intraamt14)
                                        @php($sub2_otheramt += $io_value->otheramt14)
                                        @php($sub2_urineamt += $io_value->urineamt14)
                                        @php($sub2_otherout += $io_value->otherout14)
                                        @php($sub2_vomitamt += $io_value->vomitamt14)
                                        @php($sub2_aspamt += $io_value->aspamt14)
                                        [
                                            { text: '21:00', bold: true },
                                            { text: `{!!$io_value->oraltype15!!}` },
                                            { text: '{{$io_value->oralamt15}}', alignment: 'right' },
                                            { text: `{!!$io_value->intratype15!!}` },
                                            { text: '{{$io_value->intraamt15}}', alignment: 'right' },
                                            { text: `{!!$io_value->othertype15!!}` },
                                            { text: '{{$io_value->otheramt15}}', alignment: 'right' },
                                            { text: '21:00', bold: true },
                                            { text: '{{$io_value->urineamt15}}', alignment: 'right' },
                                            { text: '{{$io_value->otherout15}}', alignment: 'right' },
                                            { text: '{{$io_value->vomitamt15}}', alignment: 'right' },
                                            { text: '{{$io_value->aspamt15}}', alignment: 'right' },
                                        ],
                                        @php($sub2_oralamt += $io_value->oralamt15)
                                        @php($sub2_intraamt += $io_value->intraamt15)
                                        @php($sub2_otheramt += $io_value->otheramt15)
                                        @php($sub2_urineamt += $io_value->urineamt15)
                                        @php($sub2_otherout += $io_value->otherout15)
                                        @php($sub2_vomitamt += $io_value->vomitamt15)
                                        @php($sub2_aspamt += $io_value->aspamt15)
                                        
                                        @php($tot_oralamt += $sub2_oralamt)
                                        @php($tot_intraamt += $sub2_intraamt)
                                        @php($tot_otheramt += $sub2_otheramt)
                                        @php($tot_urineamt += $sub2_urineamt)
                                        @php($tot_otherout += $sub2_otherout)
                                        @php($tot_vomitamt += $sub2_vomitamt)
                                        @php($tot_aspamt += $sub2_aspamt)
                                        [
                                            { text: 'TOTAL', style: 'tableHeader', alignment: 'center', colSpan: 2 },
                                            {},
                                            { text: '{{$sub2_oralamt}}', alignment: 'right', bold: true },
                                            { text: '{{$sub2_intraamt}}', alignment: 'right', bold: true, colSpan: 2 },
                                            {},
                                            { text: '{{$sub2_otheramt}}', alignment: 'right', bold: true, colSpan: 2 },
                                            {},
                                            { text: 'TOTAL', style: 'tableHeader' },
                                            { text: '{{$sub2_urineamt}}', alignment: 'right', bold: true },
                                            { text: '{{$sub2_otherout}}', alignment: 'right', bold: true },
                                            { text: '{{$sub2_vomitamt}}', alignment: 'right', bold: true },
                                            { text: '{{$sub2_aspamt}}', alignment: 'right', bold: true },
                                        ],
                                        @php($tot_intake_evening = $sub2_oralamt + $sub2_intraamt + $sub2_otheramt)
                                        @php($tot_output_evening = $sub2_urineamt + $sub2_aspamt)
                                        [
                                            { text: 'TOTAL INTAKE\n(EVENING)', style: 'tableHeader', alignment: 'center', colSpan: 2 },
                                            {},
                                            { text: '{{$tot_intake_evening}}', alignment: 'center', bold: true, colSpan: 5 },
                                            {},
                                            {},
                                            {},
                                            {},
                                            { text: 'TOTAL\n OUTPUT', style: 'tableHeader' },
                                            @if(!empty($sub2_otherout))
                                                { text: '{{$tot_output_evening}}, BO (X{{$sub2_otherout}})', alignment: 'center', bold: true, colSpan: 4 },
                                            @else
                                                { text: '{{$tot_output_evening}}, BNO', alignment: 'center', bold: true, colSpan: 4 },
                                            @endif
                                            {},
                                            {},
                                            {},
                                        ],
                                        @php($sub3_oralamt = 0)
                                        @php($sub3_intraamt = 0)
                                        @php($sub3_otheramt = 0)
                                        @php($sub3_urineamt = 0)
                                        @php($sub3_otherout = 0)
                                        @php($sub3_vomitamt = 0)
                                        @php($sub3_aspamt = 0)
                                        [
                                            { text: '22:00', bold: true },
                                            { text: `{!!$io_value->oraltype16!!}` },
                                            { text: '{{$io_value->oralamt16}}', alignment: 'right' },
                                            { text: `{!!$io_value->intratype16!!}` },
                                            { text: '{{$io_value->intraamt16}}', alignment: 'right' },
                                            { text: `{!!$io_value->othertype16!!}` },
                                            { text: '{{$io_value->otheramt16}}', alignment: 'right' },
                                            { text: '22:00', bold: true },
                                            { text: '{{$io_value->urineamt16}}', alignment: 'right' },
                                            { text: '{{$io_value->otherout16}}', alignment: 'right' },
                                            { text: '{{$io_value->vomitamt16}}', alignment: 'right' },
                                            { text: '{{$io_value->aspamt16}}', alignment: 'right' },
                                        ],
                                        @php($sub3_oralamt += $io_value->oralamt16)
                                        @php($sub3_intraamt += $io_value->intraamt16)
                                        @php($sub3_otheramt += $io_value->otheramt16)
                                        @php($sub3_urineamt += $io_value->urineamt16)
                                        @php($sub3_otherout += $io_value->otherout16)
                                        @php($sub3_vomitamt += $io_value->vomitamt16)
                                        @php($sub3_aspamt += $io_value->aspamt16)
                                        [
                                            { text: '23:00', bold: true },
                                            { text: `{!!$io_value->oraltype17!!}` },
                                            { text: '{{$io_value->oralamt17}}', alignment: 'right' },
                                            { text: `{!!$io_value->intratype17!!}` },
                                            { text: '{{$io_value->intraamt17}}', alignment: 'right' },
                                            { text: `{!!$io_value->othertype17!!}` },
                                            { text: '{{$io_value->otheramt17}}', alignment: 'right' },
                                            { text: '23:00', bold: true },
                                            { text: '{{$io_value->urineamt17}}', alignment: 'right' },
                                            { text: '{{$io_value->otherout17}}', alignment: 'right' },
                                            { text: '{{$io_value->vomitamt17}}', alignment: 'right' },
                                            { text: '{{$io_value->aspamt17}}', alignment: 'right' },
                                        ],
                                        @php($sub3_oralamt += $io_value->oralamt17)
                                        @php($sub3_intraamt += $io_value->intraamt17)
                                        @php($sub3_otheramt += $io_value->otheramt17)
                                        @php($sub3_urineamt += $io_value->urineamt17)
                                        @php($sub3_otherout += $io_value->otherout17)
                                        @php($sub3_vomitamt += $io_value->vomitamt17)
                                        @php($sub3_aspamt += $io_value->aspamt17)
                                        [
                                            { text: '00:00', bold: true },
                                            { text: `{!!$io_value->oraltype18!!}` },
                                            { text: '{{$io_value->oralamt18}}', alignment: 'right' },
                                            { text: `{!!$io_value->intratype18!!}` },
                                            { text: '{{$io_value->intraamt18}}', alignment: 'right' },
                                            { text: `{!!$io_value->othertype18!!}` },
                                            { text: '{{$io_value->otheramt18}}', alignment: 'right' },
                                            { text: '00:00', bold: true },
                                            { text: '{{$io_value->urineamt18}}', alignment: 'right' },
                                            { text: '{{$io_value->otherout18}}', alignment: 'right' },
                                            { text: '{{$io_value->vomitamt18}}', alignment: 'right' },
                                            { text: '{{$io_value->aspamt18}}', alignment: 'right' },
                                        ],
                                        @php($sub3_oralamt += $io_value->oralamt18)
                                        @php($sub3_intraamt += $io_value->intraamt18)
                                        @php($sub3_otheramt += $io_value->otheramt18)
                                        @php($sub3_urineamt += $io_value->urineamt18)
                                        @php($sub3_otherout += $io_value->otherout18)
                                        @php($sub3_vomitamt += $io_value->vomitamt18)
                                        @php($sub3_aspamt += $io_value->aspamt18)
                                        [
                                            { text: '01:00', bold: true },
                                            { text: `{!!$io_value->oraltype19!!}` },
                                            { text: '{{$io_value->oralamt19}}', alignment: 'right' },
                                            { text: `{!!$io_value->intratype19!!}` },
                                            { text: '{{$io_value->intraamt19}}', alignment: 'right' },
                                            { text: `{!!$io_value->othertype19!!}` },
                                            { text: '{{$io_value->otheramt19}}', alignment: 'right' },
                                            { text: '01:00', bold: true },
                                            { text: '{{$io_value->urineamt19}}', alignment: 'right' },
                                            { text: '{{$io_value->otherout19}}', alignment: 'right' },
                                            { text: '{{$io_value->vomitamt19}}', alignment: 'right' },
                                            { text: '{{$io_value->aspamt19}}', alignment: 'right' },
                                        ],
                                        @php($sub3_oralamt += $io_value->oralamt19)
                                        @php($sub3_intraamt += $io_value->intraamt19)
                                        @php($sub3_otheramt += $io_value->otheramt19)
                                        @php($sub3_urineamt += $io_value->urineamt19)
                                        @php($sub3_otherout += $io_value->otherout19)
                                        @php($sub3_vomitamt += $io_value->vomitamt19)
                                        @php($sub3_aspamt += $io_value->aspamt19)
                                        [
                                            { text: '02:00', bold: true },
                                            { text: `{!!$io_value->oraltype20!!}` },
                                            { text: '{{$io_value->oralamt20}}', alignment: 'right' },
                                            { text: `{!!$io_value->intratype20!!}` },
                                            { text: '{{$io_value->intraamt20}}', alignment: 'right' },
                                            { text: `{!!$io_value->othertype20!!}` },
                                            { text: '{{$io_value->otheramt20}}', alignment: 'right' },
                                            { text: '02:00', bold: true },
                                            { text: '{{$io_value->urineamt20}}', alignment: 'right' },
                                            { text: '{{$io_value->otherout20}}', alignment: 'right' },
                                            { text: '{{$io_value->vomitamt20}}', alignment: 'right' },
                                            { text: '{{$io_value->aspamt20}}', alignment: 'right' },
                                        ],
                                        @php($sub3_oralamt += $io_value->oralamt20)
                                        @php($sub3_intraamt += $io_value->intraamt20)
                                        @php($sub3_otheramt += $io_value->otheramt20)
                                        @php($sub3_urineamt += $io_value->urineamt20)
                                        @php($sub3_otherout += $io_value->otherout20)
                                        @php($sub3_vomitamt += $io_value->vomitamt20)
                                        @php($sub3_aspamt += $io_value->aspamt20)
                                        [
                                            { text: '03:00', bold: true },
                                            { text: `{!!$io_value->oraltype21!!}` },
                                            { text: '{{$io_value->oralamt21}}', alignment: 'right' },
                                            { text: `{!!$io_value->intratype21!!}` },
                                            { text: '{{$io_value->intraamt21}}', alignment: 'right' },
                                            { text: `{!!$io_value->othertype21!!}` },
                                            { text: '{{$io_value->otheramt21}}', alignment: 'right' },
                                            { text: '03:00', bold: true },
                                            { text: '{{$io_value->urineamt21}}', alignment: 'right' },
                                            { text: '{{$io_value->otherout21}}', alignment: 'right' },
                                            { text: '{{$io_value->vomitamt21}}', alignment: 'right' },
                                            { text: '{{$io_value->aspamt21}}', alignment: 'right' },
                                        ],
                                        @php($sub3_oralamt += $io_value->oralamt21)
                                        @php($sub3_intraamt += $io_value->intraamt21)
                                        @php($sub3_otheramt += $io_value->otheramt21)
                                        @php($sub3_urineamt += $io_value->urineamt21)
                                        @php($sub3_otherout += $io_value->otherout21)
                                        @php($sub3_vomitamt += $io_value->vomitamt21)
                                        @php($sub3_aspamt += $io_value->aspamt21)
                                        [
                                            { text: '04:00', bold: true },
                                            { text: `{!!$io_value->oraltype22!!}` },
                                            { text: '{{$io_value->oralamt22}}', alignment: 'right' },
                                            { text: `{!!$io_value->intratype22!!}` },
                                            { text: '{{$io_value->intraamt22}}', alignment: 'right' },
                                            { text: `{!!$io_value->othertype22!!}` },
                                            { text: '{{$io_value->otheramt22}}', alignment: 'right' },
                                            { text: '04:00', bold: true },
                                            { text: '{{$io_value->urineamt22}}', alignment: 'right' },
                                            { text: '{{$io_value->otherout22}}', alignment: 'right' },
                                            { text: '{{$io_value->vomitamt22}}', alignment: 'right' },
                                            { text: '{{$io_value->aspamt22}}', alignment: 'right' },
                                        ],
                                        @php($sub3_oralamt += $io_value->oralamt22)
                                        @php($sub3_intraamt += $io_value->intraamt22)
                                        @php($sub3_otheramt += $io_value->otheramt22)
                                        @php($sub3_urineamt += $io_value->urineamt22)
                                        @php($sub3_otherout += $io_value->otherout22)
                                        @php($sub3_vomitamt += $io_value->vomitamt22)
                                        @php($sub3_aspamt += $io_value->aspamt22)
                                        [
                                            { text: '05:00', bold: true },
                                            { text: `{!!$io_value->oraltype23!!}` },
                                            { text: '{{$io_value->oralamt23}}', alignment: 'right' },
                                            { text: `{!!$io_value->intratype23!!}` },
                                            { text: '{{$io_value->intraamt23}}', alignment: 'right' },
                                            { text: `{!!$io_value->othertype23!!}` },
                                            { text: '{{$io_value->otheramt23}}', alignment: 'right' },
                                            { text: '05:00', bold: true },
                                            { text: '{{$io_value->urineamt23}}', alignment: 'right' },
                                            { text: '{{$io_value->otherout23}}', alignment: 'right' },
                                            { text: '{{$io_value->vomitamt23}}', alignment: 'right' },
                                            { text: '{{$io_value->aspamt23}}', alignment: 'right' },
                                        ],
                                        @php($sub3_oralamt += $io_value->oralamt23)
                                        @php($sub3_intraamt += $io_value->intraamt23)
                                        @php($sub3_otheramt += $io_value->otheramt23)
                                        @php($sub3_urineamt += $io_value->urineamt23)
                                        @php($sub3_otherout += $io_value->otherout23)
                                        @php($sub3_vomitamt += $io_value->vomitamt23)
                                        @php($sub3_aspamt += $io_value->aspamt23)
                                        [
                                            { text: '06:00', bold: true },
                                            { text: `{!!$io_value->oraltype24!!}` },
                                            { text: '{{$io_value->oralamt24}}', alignment: 'right' },
                                            { text: `{!!$io_value->intratype24!!}` },
                                            { text: '{{$io_value->intraamt24}}', alignment: 'right' },
                                            { text: `{!!$io_value->othertype24!!}` },
                                            { text: '{{$io_value->otheramt24}}', alignment: 'right' },
                                            { text: '06:00', bold: true },
                                            { text: '{{$io_value->urineamt24}}', alignment: 'right' },
                                            { text: '{{$io_value->otherout24}}', alignment: 'right' },
                                            { text: '{{$io_value->vomitamt24}}', alignment: 'right' },
                                            { text: '{{$io_value->aspamt24}}', alignment: 'right' },
                                        ],
                                        @php($sub3_oralamt += $io_value->oralamt24)
                                        @php($sub3_intraamt += $io_value->intraamt24)
                                        @php($sub3_otheramt += $io_value->otheramt24)
                                        @php($sub3_urineamt += $io_value->urineamt24)
                                        @php($sub3_otherout += $io_value->otherout24)
                                        @php($sub3_vomitamt += $io_value->vomitamt24)
                                        @php($sub3_aspamt += $io_value->aspamt24)
                                        
                                        @php($tot_oralamt += $sub3_oralamt)
                                        @php($tot_intraamt += $sub3_intraamt)
                                        @php($tot_otheramt += $sub3_otheramt)
                                        @php($tot_urineamt += $sub3_urineamt)
                                        @php($tot_otherout += $sub3_otherout)
                                        @php($tot_vomitamt += $sub3_vomitamt)
                                        @php($tot_aspamt += $sub3_aspamt)
                                        [
                                            { text: 'TOTAL', style: 'tableHeader', alignment: 'center', colSpan: 2 },
                                            {},
                                            { text: '{{$sub3_oralamt}}', alignment: 'right', bold: true },
                                            { text: '{{$sub3_intraamt}}', alignment: 'right', bold: true, colSpan: 2 },
                                            {},
                                            { text: '{{$sub3_otheramt}}', alignment: 'right', bold: true, colSpan: 2 },
                                            {},
                                            { text: 'TOTAL', style: 'tableHeader' },
                                            { text: '{{$sub3_urineamt}}', alignment: 'right', bold: true },
                                            { text: '{{$sub3_otherout}}', alignment: 'right', bold: true },
                                            { text: '{{$sub3_vomitamt}}', alignment: 'right', bold: true },
                                            { text: '{{$sub3_aspamt}}', alignment: 'right', bold: true },
                                        ],
                                        @php($tot_intake_night = $sub3_oralamt + $sub3_intraamt + $sub3_otheramt)
                                        @php($tot_output_night = $sub3_urineamt + $sub3_aspamt)
                                        [
                                            { text: 'TOTAL INTAKE\n(NIGHT)', style: 'tableHeader', alignment: 'center', colSpan: 2 },
                                            {},
                                            { text: '{{$tot_intake_night}}', alignment: 'center', bold: true, colSpan: 5 },
                                            {},
                                            {},
                                            {},
                                            {},
                                            { text: 'TOTAL\n OUTPUT', style: 'tableHeader' },
                                            @if(!empty($sub3_otherout))
                                                { text: '{{$tot_output_night}}, BO (X{{$sub3_otherout}})', alignment: 'center', bold: true, colSpan: 4 },
                                            @else
                                                { text: '{{$tot_output_night}}, BNO', alignment: 'center', bold: true, colSpan: 4 },
                                            @endif
                                            {},
                                            {},
                                            {},
                                        ],
                                    ]
                                },
                                // layout: 'lightHorizontalLines',
                            },
                            @endif
                        @endforeach
                        { text: '*BNO = BOWEL NO OUT; BO = BOWEL OUT', fontSize: 7 },
                        {
                            table: {
                                widths: [200,150,'*'], // panjang standard dia 515
                                body: [
                                    @php($daily_intake = $tot_intake_morning + $tot_intake_evening + $tot_intake_night)
                                    @php($daily_output = $tot_output_morning + $tot_output_evening + $tot_output_night)
                                    @php($tot_bo = $sub1_otherout + $sub2_otherout + $sub3_otherout)
                                    [
                                        { text: '', border: [false, false, false, false] },
                                        { text: 'DAILY INTAKE', fontSize: 8, bold: true, alignment: 'center' },
                                        { text: '{{$daily_intake}}', fontSize: 8, alignment: 'center' },
                                    ],
                                    @if(!empty($tot_bo))
                                    [
                                        { text: '', border: [false, false, false, false] },
                                        { text: 'DAILY OUTPUT', fontSize: 8, bold: true, alignment: 'center' },
                                        { text: '{{$daily_output}}, BO (X{{$tot_bo}})', fontSize: 8, alignment: 'center' },
                                    ],
                                    @else
                                    [
                                        { text: '', border: [false, false, false, false] },
                                        { text: 'DAILY OUTPUT', fontSize: 8, bold: true, alignment: 'center' },
                                        { text: '{{$daily_output}}, BNO', fontSize: 8, alignment: 'center' },
                                    ],
                                    @endif
                                    [
                                        { text: '', border: [false, false, false, false] },
                                        { text: 'BALANCE', fontSize: 8, bold: true, alignment: 'center' },
                                        { text: '{{$daily_intake - $daily_output}}', fontSize: 8, alignment: 'center' },
                                    ]
                                ]
                            },
                            // layout: 'noBorders',
                        },
                        // { text: '', alignment: 'left', fontSize: 9, pageBreak: 'after' },
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
                        fontSize: 8,
                        margin: [0, 5, 0, 5]
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