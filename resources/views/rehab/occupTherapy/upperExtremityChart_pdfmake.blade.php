<!DOCTYPE html>
<html>
    <head>
        <title>Upper Extremity Assessment Form</title>
    </head>
    
    <!-- <script src="https://unpkg.com/@jsreport/browser-client/dist/jsreport.umd.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="mydata.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha512-a9NgEEK7tsCvABL7KqtUTQjl69z7091EVPpw5KxPlZ93T141ffe1woLtbXTX+r2/8TtTvRX/v4zTL2UlMUPgwg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.min.js" integrity="sha512-P0bOMePRS378NwmPDVPU455C/TuxDS+8QwJozdc7PGgN8kLqR4ems0U/3DeJkmiE31749vYWHvBOtR+37qDCZQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
    </object>
    
    <script>
        var rof = [
            @foreach($rof as $key => $dt)
            {
                @foreach($dt as $key2 => $val)
                    '{{$key2}}' : `{!!str_replace('`', '', $val)!!}`,
                @endforeach
            },
            @endforeach
        ];

        var hand = [
            @foreach($hand as $key => $dt)
            {
                @foreach($dt as $key2 => $val)
                    '{{$key2}}' : `{!!str_replace('`', '', $val)!!}`,
                @endforeach
            },
            @endforeach
        ];

        var imp_ROF = 
            @if(!empty($imp_ROF))
                { text: `{!!$imp_ROF->impressions!!}`};
            @else
                { text: ' ' };
            @endif
        var imp_hand = 
            @if(!empty($imp_hand))
                { text: `{!!$imp_hand->impressions!!}`};
            @else
                { text: ' ' };
            @endif

        $(document).ready(function (){
            var docDefinition = {
                footer: function (currentPage, pageCount){
                    return [
                        { text: currentPage.toString() + ' of ' + pageCount, alignment: 'center', fontSize: 9 }
                    ]
                },
                pageSize: 'A4',
                // pageOrientation: 'landscape',
                pageMargins: [10, 10, 10, 10],
                content: [
                    {
                        image: 'letterhead', width: 500, style: 'tableHeader', colSpan: 5, alignment: 'center'
                    },
                    {
                        text: 'UPPER EXTREMITY ASSESSMENT FORM\n\n',
                        style: 'header',
                        alignment: 'center'
                    },
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: [70,3,'*',60,3,'*'], // panjang standard dia 515
                            body: [
                                [
                                    { text: 'Name' },
                                    { text: ':' },
                                    { text: `{!!$upperExtremity->Name!!}`},
                                    { text: 'MRN' },
                                    { text: ':' },
                                    { text: '{{str_pad($upperExtremity->mrn, 7, "0", STR_PAD_LEFT)}}' },
                                ],
                                [
                                    { text: 'Hand Dominant' },
                                    { text: ':' },
                                    { text: '{{$upperExtremity->handDominant}}' },
                                    { text: 'Date' },
                                    { text: ':' },
                                    { text: '{{\Carbon\Carbon::createFromFormat('Y-m-d',$upperExtremity->dateAssess)->format('d-m-Y')}}' },
                                ],
                                [
                                   
                                    { text: 'Therapist' },
                                    { text: ':' },
                                    { text: '{{$upperExtremity->occupTherapist}}' },
                                    { text: 'Diagnosis' },
                                    { text: ':' },
                                    { text: `{!!$upperExtremity->diagnosis!!}`},
                                ],
                            ]
                        },
                        layout: 'noBorders',
                    },
                    //RANGE OF MOTION
                    {
                        style: 'tableExample',
                        table: make_table_rom(),
                    },
                    //HAND
                    {
                        style: 'tableExample',
                        table: make_table_hand(),
                    },
                    //MUSCLE STRENGTH
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: ['*','*'], // panjang standard dia 515
                            body: [
                                [
                                    { text: 'MUSCLE STRENGTH', style: 'tableHeader', alignment: 'center', colSpan:2, fillColor: '#dddddd'},{},
                                ],
                                [   
                                    @if(!empty($strength->mmt))
                                        { text: 'Oxford Manual Muscle Testing [ √ ]'},
                                    @else
                                        { text: 'Oxford Manual Muscle Testing [   ]'},
                                    @endif

                                    @if(!empty($strength->jamar))
                                        { text: 'Jamar Dynamometer [ √ ]'},
                                    @else
                                        { text: 'Jamar Dynamometer [   ]'},
                                    @endif
                                ],
                                [
                                    @if(!empty($strength->mmt_remarks))
                                        { text: `{!!$strength->mmt_remarks!!}`, rowSpan:2},
                                    @else
                                        { text: ''},
                                    @endif                                    
                                    {
                                        table: {
                                            widths: ['*','*'],
                                            body: [
                                                [{ text: 'GRIP STRENGTH', style: 'tableHeader', alignment: 'center', colSpan:2, fillColor: '#dddddd'},{}],
                                                [
                                                    @if(!empty($strength->jamarGripDate))
                                                        { text: 'Date: {{\Carbon\Carbon::createFromFormat('Y-m-d',$strength->jamarGripDate)->format('d-m-Y')}}', colSpan:2,},{}
                                                    @else
                                                        { text: 'Date: ', colSpan:2},{}
                                                    @endif
                                                ],
                                                [
                                                    { text: 'Rt Hand', bold:true},
                                                    @if(!empty($strength->jamarGrip_rt))
                                                        { text: '{{$strength->jamarGrip_rt}} kg'},
                                                    @else
                                                        { text: ''},
                                                    @endif
                                                ],
                                                [
                                                    { text: 'Lt Hand', bold:true},
                                                    @if(!empty($strength->jamarGrip_lt))
                                                        { text: '{{$strength->jamarGrip_lt}} kg'},
                                                    @else
                                                        { text: ''},
                                                    @endif
                                                ],
                                            ]
                                        },
							        }
                                ],
                                [
                                    @if(!empty($strength->mmt_remarks))
                                        { text: `{!!$strength->mmt_remarks!!}`},
                                    @else
                                        { text: ''},
                                    @endif
                                    {
                                        table: {
                                            widths: ['*','*','*'],
                                            body: [
                                                [{ text: 'PINCH STRENGTH', style: 'tableHeader', alignment: 'center', colSpan:3, fillColor: '#dddddd'},{},{}],
                                                [
                                                    @if(!empty($strength->jamarPinchDate))
                                                        { text: 'Date: {{\Carbon\Carbon::createFromFormat('Y-m-d',$strength->jamarPinchDate)->format('d-m-Y')}}', colSpan:3},{},{}
                                                    @else
                                                        { text: 'Date: ', colSpan:3},{},{}
                                                    @endif
                                                ],
                                                [{ text: ''},{ text: 'Rt Hand', bold:true, alignment: 'center' },{ text: 'Lt Hand', bold:true, alignment: 'center' }],
                                                [
                                                    { text: 'Lateral'},
                                                    @if(!empty($strength->jamarPinch_lateral_rt))
                                                        { text: '{{$strength->jamarPinch_lateral_rt}} kg' },
                                                    @else
                                                        { text: '' },
                                                    @endif

                                                    @if(!empty($strength->jamarPinch_lateral_lt))
                                                        { text: '{{$strength->jamarPinch_lateral_lt}} kg' },
                                                    @else
                                                        { text: '' },
                                                    @endif
                                                ],
                                                [
                                                    { text: 'Pad'},
                                                    @if(!empty($strength->jamarPinch_pad_rt))
                                                        { text: '{{$strength->jamarPinch_pad_rt}} kg' },
                                                    @else
                                                        { text: '' },
                                                    @endif

                                                    @if(!empty($strength->jamarPinch_pad_lt))
                                                        { text: '{{$strength->jamarPinch_pad_lt}} kg' },
                                                    @else
                                                        { text: '' },
                                                    @endif
                                                ],
                                                [
                                                    { text: '3-Jaw Chuck'},
                                                    @if(!empty($strength->jamarPinch_jaw_rt))
                                                        { text: '{{$strength->jamarPinch_jaw_rt}} kg' },
                                                    @else
                                                        { text: '' },
                                                    @endif

                                                    @if(!empty($strength->jamarPinch_jaw_lt))
                                                        { text: '{{$strength->jamarPinch_jaw_lt}} kg' },
                                                    @else
                                                        { text: '' },
                                                    @endif
                                                ],
                                            ]
                                        },
							        }
                                ],
                                [
                                    @if(!empty($strength->impressions))
                                        { text: `Impressions:\n{!!$strength->impressions!!}`, colSpan:2},{}
                                    @else
                                        { text: 'Impressions: ', colSpan:2},{}
                                    @endif
                                ],
                            ],
                        },
                    },
                    //SENSATION
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: [35,30,30,30,30,30,30,30,30,'*'], // panjang standard dia 515
                            body: [
                                [
                                    { text: 'SENSATION', style: 'tableHeader', alignment: 'center', colSpan:10, fillColor: '#dddddd'},{},{},{},{},{},{},{},{},{},
                                ],
                                [
                                    {},
                                    { text: 'Sharp', alignment: 'center', colSpan:2, bold:true},
                                    {},
                                    { text: 'Dull', alignment: 'center', colSpan:2, bold:true },
                                    {},
                                    { text: 'Light Touch', alignment: 'center', colSpan:2, bold:true },
                                    {},
                                    { text: 'Deep Touch', alignment: 'center', colSpan:2, bold:true },
                                    {},
                                    { text: 'Stereognosis', alignment: 'center', bold:true },
                                ],
                                [
                                    {},
                                    { text: 'Rt', alignment: 'center', bold:true},
                                    { text: 'Lt', alignment: 'center', bold:true},
                                    { text: 'Rt', alignment: 'center', bold:true},
                                    { text: 'Lt', alignment: 'center', bold:true},
                                    { text: 'Rt', alignment: 'center', bold:true},
                                    { text: 'Lt', alignment: 'center', bold:true},
                                    { text: 'Rt', alignment: 'center', bold:true},
                                    { text: 'Lt', alignment: 'center', bold:true},
                                    { text: '' },
                                ],
                                [
                                    { text: 'Intact'},
                                    @if(!empty($sensation->sens_sharpIntact_rt))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif

                                    @if(!empty($sensation->sens_sharpIntact_lt))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif

                                    @if(!empty($sensation->sens_dullIntact_rt))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif

                                    @if(!empty($sensation->sens_dullIntact_lt))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif

                                    @if(!empty($sensation->sens_lightIntact_rt))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif

                                    @if(!empty($sensation->sens_lightIntact_lt))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif

                                    @if(!empty($sensation->sens_deepIntact_rt))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif

                                    @if(!empty($sensation->sens_deepIntact_lt))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif

                                    @if(!empty($sensation->sens_stereoIntact))
                                        { text: `{!!$sensation->sens_stereoIntact!!}`},
                                    @else
                                        { text: ' ' },
                                    @endif
                                ],
                                [
                                    { text: 'Impaired'},
                                    @if(!empty($sensation->sens_sharpImpaired_rt))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif

                                    @if(!empty($sensation->sens_sharpImpaired_lt))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif

                                    @if(!empty($sensation->sens_dullImpaired_rt))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif

                                    @if(!empty($sensation->sens_dullImpaired_lt))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif

                                    @if(!empty($sensation->sens_lightImpaired_rt))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif

                                    @if(!empty($sensation->sens_lightImpaired_lt))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif

                                    @if(!empty($sensation->sens_deepImpaired_rt))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif

                                    @if(!empty($sensation->sens_deepImpaired_lt))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    
                                    @if(!empty($sensation->sens_stereoImpaired))
                                        { text: `{!!$sensation->sens_stereoImpaired!!}`},
                                    @else
                                        { text: ' ' },
                                    @endif
                                ],
                                [
                                    { text: 'Absent'},
                                    @if(!empty($sensation->sens_sharpAbsent_rt))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif

                                    @if(!empty($sensation->sens_sharpAbsent_lt))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif

                                    @if(!empty($sensation->sens_dullAbsent_rt))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif

                                    @if(!empty($sensation->sens_dullAbsent_lt))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif

                                    @if(!empty($sensation->sens_lightAbsent_rt))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif

                                    @if(!empty($sensation->sens_lightAbsent_lt))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif

                                    @if(!empty($sensation->sens_deepAbsent_rt))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif

                                    @if(!empty($sensation->sens_deepAbsent_lt))
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: ' ' },
                                    @endif
                                    
                                    @if(!empty($sensation->sens_stereoAbsent))
                                        { text: `{!!$sensation->sens_stereoAbsent!!}`},
                                    @else
                                        { text: ' ' },
                                    @endif
                                ],
                                [
                                    @if(!empty($sensation->impressions))
                                        { text: `Impressions:\n{!!$sensation->impressions!!}`, colSpan:10},{},{},{},{},{},{},{},{},{},
                                    @else
                                        { text: `Impressions: `, colSpan:10},{},{},{},{},{},{},{},{},{},
                                    @endif
                                ],
                            ],
                        },
                    },
                    //PREHENSIVE PATTERN & SKIN CONDITION/SCARRING
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: ['*','*'], // panjang standard dia 515
                            body: [
                                [
                                    { text: 'PREHENSIVE PATTERN', style: 'tableHeader', alignment: 'center', fillColor: '#dddddd'},
                                    { text: 'SKIN CONDITION/SCARRING', style: 'tableHeader', alignment: 'center', fillColor: '#dddddd'},
                                ],
                                [
                                    // 'Check which patient able to achieve (please tick at the appropriate box)',
                                    { 
                                        table: {
                                            widths: ['*','*','*'],
                                            body: [
                                                [{}, {text:'Rt Hand', alignment: 'center', bold:true}, {text:'Lt Hand', alignment: 'center', bold:true}],
                                                [
                                                    {text: 'Hook Grasp'}, 
                                                    @if(!empty($prehensive->prehensive_hook_rt))
                                                        { text: '√', alignment: 'center' },
                                                    @else
                                                        { text: ' ' },
                                                    @endif

                                                    @if(!empty($prehensive->prehensive_hook_lt))
                                                        { text: '√', alignment: 'center' },
                                                    @else
                                                        { text: ' ' },
                                                    @endif
                                                ],
                                                [
                                                    {text: 'Lateral Pinch'}, 
                                                    @if(!empty($prehensive->prehensive_lateral_rt))
                                                        { text: '√', alignment: 'center' },
                                                    @else
                                                        { text: ' ' },
                                                    @endif

                                                    @if(!empty($prehensive->prehensive_lateral_lt))
                                                        { text: '√', alignment: 'center' },
                                                    @else
                                                        { text: ' ' },
                                                    @endif
                                                ],
                                                [
                                                    {text: 'Tip Pinch'}, 
                                                    @if(!empty($prehensive->prehensive_tip_rt))
                                                        { text: '√', alignment: 'center' },
                                                    @else
                                                        { text: ' ' },
                                                    @endif

                                                    @if(!empty($prehensive->prehensive_tip_lt))
                                                        { text: '√', alignment: 'center' },
                                                    @else
                                                        { text: ' ' },
                                                    @endif
                                                ],
                                                [
                                                    {text: 'Cylindrical Grasp'}, 
                                                    @if(!empty($prehensive->prehensive_cylindrical_rt))
                                                        { text: '√', alignment: 'center' },
                                                    @else
                                                        { text: ' ' },
                                                    @endif

                                                    @if(!empty($prehensive->prehensive_cylindrical_lt))
                                                        { text: '√', alignment: 'center' },
                                                    @else
                                                        { text: ' ' },
                                                    @endif
                                                ],
                                                [
                                                    {text: 'Pad Pinch'}, 
                                                    @if(!empty($prehensive->prehensive_pad_rt))
                                                        { text: '√', alignment: 'center' },
                                                    @else
                                                        { text: ' ' },
                                                    @endif

                                                    @if(!empty($prehensive->prehensive_pad_lt))
                                                        { text: '√', alignment: 'center' },
                                                    @else
                                                        { text: ' ' },
                                                    @endif
                                                ],
                                                [
                                                    {text: '3-Jaw Chuck'}, 
                                                    @if(!empty($prehensive->prehensive_jaw_rt))
                                                        { text: '√', alignment: 'center' },
                                                    @else
                                                        { text: ' ' },
                                                    @endif

                                                    @if(!empty($prehensive->prehensive_jaw_lt))
                                                        { text: '√', alignment: 'center' },
                                                    @else
                                                        { text: ' ' },
                                                    @endif
                                                ],
                                                [
                                                    {text: 'Spherical Grasp'}, 
                                                    @if(!empty($prehensive->prehensive_spherical_rt))
                                                        { text: '√', alignment: 'center' },
                                                    @else
                                                        { text: ' ' },
                                                    @endif

                                                    @if(!empty($prehensive->prehensive_spherical_lt))
                                                        { text: '√', alignment: 'center' },
                                                    @else
                                                        { text: ' ' },
                                                    @endif
                                                ],
                                            ]
                                        },
                                    },
                                    @if(!empty($skin->skinCondition))
                                        { text: `Skin Condition:\n{!!$skin->skinCondition!!}`},
                                    @else
                                        { text: `Skin Condition: `},
                                    @endif
                                ],
                                [
                                    @if(!empty($prehensive->impressions))
                                        { text: `Impressions:\n{!!$prehensive->impressions!!}`},
                                    @else
                                        { text: `Impressions: `},
                                    @endif

                                    @if(!empty($skin->impressions))
                                        { text: `Impressions:\n{!!$skin->impressions!!}`},
                                    @else
                                        { text: `Impressions: `},
                                    @endif

                                ],
                            ],
                        },
                    },
                    //EDEMA
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: ['*','*'], // panjang standard dia 515
                            body: [
                                [
                                    { text: 'EDEMA', style: 'tableHeader', alignment: 'center', fillColor: '#dddddd', colSpan:2},{},
                                ],
                                [
                                    { 
                                        table: {
                                            widths: ['*','*','*'],
                                            body: [
                                                [{}, {text:'Rt Hand', alignment: 'center', bold:true}, {text:'Lt Hand', alignment: 'center', bold:true}],
                                                [
                                                    {text: 'Noted'},
                                                    @if(!empty($edema->edema_noted_rt))
                                                        { text: '√', alignment: 'center' },
                                                    @else
                                                        { text: ' ' },
                                                    @endif
                                                    @if(!empty($edema->edema_noted_lt))
                                                        { text: '√', alignment: 'center' },
                                                    @else
                                                        { text: ' ' },
                                                    @endif
                                                ],
                                                [
                                                    @if(!empty($edema->edema_new1))
                                                        {text: '{{$edema->edema_new1}}' },
                                                    @else
                                                        { text: ' ' },
                                                    @endif
                                                    @if(!empty($edema->edema_new1_rt))
                                                        { text: '√', alignment: 'center' },
                                                    @else
                                                        { text: ' ' },
                                                    @endif
                                                    @if(!empty($edema->edema_new1_lt))
                                                        { text: '√', alignment: 'center' },
                                                    @else
                                                        { text: ' ' },
                                                    @endif
                                                ],
                                            ]
                                        },
                                    },
                                    @if(!empty($edema->impressions))
                                        { text: `Impressions:\n{!!$edema->impressions!!}`},
                                    @else
                                        { text: `Impressions: `},
                                    @endif
                                ],
                            ],
                        },
                    },
                    //FUNCTIONAL ACTIVITIES
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: ['*','*'], // panjang standard dia 515
                            body: [
                                [
                                    { text: 'FUNCTIONAL ACTIVITIES', style: 'tableHeader', alignment: 'center', fillColor: '#dddddd', colSpan:2},{},
                                ],
                                [
                                    { 
                                        table: {
                                            widths: ['*','*','*'],
                                            body: [
                                                [{}, {text:'Rt Hand', alignment: 'center', bold:true}, {text:'Lt Hand', alignment: 'center', bold:true}],
                                                [
                                                    {text: 'Writing'}, 
                                                    @if(!empty($func->func_writing_rt))
                                                        { text: '√', alignment: 'center' },
                                                    @else
                                                        { text: ' ' },
                                                    @endif
                                                    @if(!empty($func->func_writing_lt))
                                                        { text: '√', alignment: 'center' },
                                                    @else
                                                        { text: ' ' },
                                                    @endif
                                                ],
                                                [
                                                    {text: 'Pick Up Coins'}, 
                                                    @if(!empty($func->func_pickCoins_rt))
                                                        { text: '√', alignment: 'center' },
                                                    @else
                                                        { text: ' ' },
                                                    @endif
                                                    @if(!empty($func->func_pickCoins_lt))
                                                        { text: '√', alignment: 'center' },
                                                    @else
                                                        { text: ' ' },
                                                    @endif
                                                ],
                                                [
                                                    {text: 'Pick Up Pins'}, 
                                                    @if(!empty($func->func_pickPins_rt))
                                                        { text: '√', alignment: 'center' },
                                                    @else
                                                        { text: ' ' },
                                                    @endif
                                                    @if(!empty($func->func_pickPins_lt))
                                                        { text: '√', alignment: 'center' },
                                                    @else
                                                        { text: ' ' },
                                                    @endif
                                                ],
                                                [
                                                    {text: 'Buttoning'}, 
                                                    @if(!empty($func->func_button_rt))
                                                        { text: '√', alignment: 'center' },
                                                    @else
                                                        { text: ' ' },
                                                    @endif
                                                    @if(!empty($func->func_button_lt))
                                                        { text: '√', alignment: 'center' },
                                                    @else
                                                        { text: ' ' },
                                                    @endif
                                                ],
                                                [
                                                    {text: 'Feeding-spoon'}, 
                                                    @if(!empty($func->func_feedSpoon_rt))
                                                        { text: '√', alignment: 'center' },
                                                    @else
                                                        { text: ' ' },
                                                    @endif
                                                    @if(!empty($func->func_feedSpoon_lt))
                                                        { text: '√', alignment: 'center' },
                                                    @else
                                                        { text: ' ' },
                                                    @endif
                                                ],
                                                [
                                                    {text: 'Feeding-hand'}, 
                                                    @if(!empty($func->func_feedHand_rt))
                                                        { text: '√', alignment: 'center' },
                                                    @else
                                                        { text: ' ' },
                                                    @endif
                                                    @if(!empty($func->func_feedHand_lt))
                                                        { text: '√', alignment: 'center' },
                                                    @else
                                                        { text: ' ' },
                                                    @endif
                                                ],
                                            ]
                                        },
                                    },
                                    @if(!empty($func->impressions))
                                        { text: `Impressions:\n{!!$func->impressions!!}`},
                                    @else
                                        { text: 'Impressions: ' },
                                    @endif
                                ],
                            ],
                        },
                    },
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
                        margin: [0, 5, 0, 0]
                    },
                    tableDetail: {
                        fontSize: 7.5,
                        margin: [0, 0, 0, 8]
                    },
                    tableHeader: {
                        bold: true,
                        fontSize: 9,
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
            
            pdfMake.createPdf(docDefinition).getDataUrl(function(dataURL) {
                $('#pdfiframe').attr('src',dataURL);
            });
        });

        function make_table_rom(){

            var widths = [50,50,50];
            var body = [
                        [
                            { text: 'RANGE OF MOTION', style: 'tableHeader', alignment: 'center', colSpan:3, fillColor: '#dddddd'},{},{},
                        ],
                        [
                            { text: 'Date', colSpan:3},{},{},
                        ],
                        [
                            { text: 'Indicate', colSpan:3},{},{},
                        ],
                        [
                            { text: 'Shoulder'},
                            { text: '0-50'},
                            { text: 'Ext'},
                        ],
                        [
                            { text: ''},
                            { text: '0-180'},
                            { text: 'Flex'},
                        ],
                        [
                            { text: ''},
                            { text: '0-180'},
                            { text: 'Add/Abd'},
                        ],
                        [
                            { text: ''},
                            { text: '0-90'},
                            { text: 'Internal Rotation'},
                        ],
                        [
                            { text: ''},
                            { text: '0-90'},
                            { text: 'External Rotation'},
                        ],
                        [
                            { text: 'Elbow'},
                            { text: '0-160'},
                            { text: 'Ext/Flex'},
                        ],
                        [
                            { text: 'Forearm'},
                            { text: '0-90'},
                            { text: 'Pronation'},
                        ],
                        [
                            { text: 'Forarm'},
                            { text: '0-90'},
                            { text: 'Supination'},
                        ],
                        [
                            { text: 'Impressions: ', colSpan:3},{},{},
                        ],
                    ];

            rof.forEach(function(element, index){
                widths.push('*');

                body[0][0].colSpan += 1;
                body[0].push({});

                body[1].push({text : element.daterof});

                body[2].push({text : element.dominant});

                body[3].push({text : element.shoulder_ext,noWrap:false});

                body[4].push({text : element.shoulder_flex,noWrap:false});

                body[5].push({text : element.shoulder_addAbd,noWrap:false});

                body[6].push({text : element.shoulder_intRotation,noWrap:false});

                body[7].push({text : element.shoulder_extRotation,noWrap:false});

                body[8].push({text : element.elbow_extFlex,noWrap:false});

                body[9].push({text : element.forearm_pronation,noWrap:false});

                body[10].push({text : element.forearm_pronation,noWrap:false});

                if(index == 0){
                    body[11].push({text: imp_ROF, colSpan:1});
                }else{
                    body[11][3].colSpan += 1;
                    body[11].push({});
                }
            });

            return {
                // headerRows: 1,
                widths: widths, // panjang standard dia 515
                body: body,
            };
        }

        function make_table_hand(){
            var widths = [50,50,50];
            var body = [
                        [
                            { text: 'HAND', style: 'tableHeader', alignment: 'center', colSpan:3, fillColor: '#dddddd'},{},{},
                        ],
                        [
                            { text: '', colSpan:3},{},{},
                        ],
                        [
                            { text: 'Indicate', colSpan:3},{},{},
                        ],
                        [
                            { text: 'Wrist'},
                            { text: '0-90'},
                            { text: 'Flex'},
                        ],
                        [
                            { text: ''},
                            { text: '0-90'},
                            { text: 'Ext'},
                        ],
                        [
                            { text: ''},
                            { text: '0-30'},
                            { text: 'Ulna/Radial Deviation'},
                        ],
                        [
                            { text: 'Thumb'},
                            { text: '0-50'},
                            { text: 'Ext/Flex MP'},
                        ],
                        [
                            { text: ''},
                            { text: '0-80'},
                            { text: 'Ext/Flex IP'},
                        ],
                        [
                            { text: ''},
                            { text: '0-15'},
                            { text: 'Ext/Flex CMC'},
                        ],
                        [
                            { text: ''},
                            { text: '0-75'},
                            { text: 'Palmar Abduction'},
                        ],
                        [
                            { text: ''},
                            { text: ''},
                            { text: 'Opposition record in inches:\nThumb to tip 5th Digit'},
                        ],
                        [
                            { text: ''},
                            { text: ''},
                            { text: 'Thumb to base 5th Digit'},
                        ],
                        [
                            { text: 'Index'},
                            { text: '0-90'},
                            { text: 'MCP'},
                        ], 
                        [
                            { text: ''},
                            { text: '0-110'},
                            { text: 'PIP'},
                        ], 
                        [
                            { text: ''},
                            { text: '0-90'},
                            { text: 'DIP'},
                        ], 
                        [
                            { text: 'Middle'},
                            { text: '0-90'},
                            { text: 'MCP'},
                        ], 
                        [
                            { text: ''},
                            { text: '0-110'},
                            { text: 'PIP'},
                        ], 
                        [
                            { text: ''},
                            { text: '0-90'},
                            { text: 'DIP'},
                        ], 
                        [
                            { text: 'Ring'},
                            { text: '0-90'},
                            { text: 'MCP'},
                        ], 
                        [
                            { text: ''},
                            { text: '0-110'},
                            { text: 'PIP'},
                        ], 
                        [
                            { text: ''},
                            { text: '0-90'},
                            { text: 'DIP'},
                        ], 
                        [
                            { text: 'Little'},
                            { text: '0-90'},
                            { text: 'MCP'},
                        ], 
                        [
                            { text: ''},
                            { text: '0-110'},
                            { text: 'PIP'},
                        ], 
                        [
                            { text: ''},
                            { text: '0-90'},
                            { text: 'DIP'},
                        ], 
                        [
                            { text: 'Impressions: ', colSpan:3},{},{},
                        ],
                    ];

            hand.forEach(function(element, index){
                widths.push('*');

                body[0][0].colSpan += 1;
                body[0].push({});

                body[1].push({text : element.datehand});

                body[2].push({text : element.dominants});

                body[3].push({text : element.wrist_flex,noWrap:false});

                body[4].push({text : element.wrist_ext,noWrap:false});

                body[5].push({text : element.wrist_ulna,noWrap:false});

                body[6].push({text : element.thumb_extFlexMP,noWrap:false});

                body[7].push({text : element.thumb_extFlexIP,noWrap:false});

                body[8].push({text : element.thumb_extFlexCMC,noWrap:false});

                body[9].push({text : element.thumb_palmar,noWrap:false});

                body[10].push({text : element.thumb_tip,noWrap:false});

                body[11].push({text : element.thumb_base,noWrap:false});

                body[12].push({text : element.index_MCP,noWrap:false});

                body[13].push({text : element.index_PIP,noWrap:false});

                body[14].push({text : element.index_DIP,noWrap:false});

                body[15].push({text : element.middle_MCP,noWrap:false});

                body[16].push({text : element.middle_PIP,noWrap:false});

                body[17].push({text : element.middle_DIP,noWrap:false});

                body[18].push({text : element.ring_MCP,noWrap:false});

                body[19].push({text : element.ring_PIP,noWrap:false});

                body[20].push({text : element.ring_DIP,noWrap:false});

                body[21].push({text : element.little_MCP,noWrap:false});

                body[22].push({text : element.little_PIP,noWrap:false});

                body[23].push({text : element.little_DIP,noWrap:false});

                if(index == 0){
                    body[24].push({text: imp_hand, colSpan:1});
                }else{
                    body[24][3].colSpan += 1;
                    body[24].push({});
                }
            });

            console.log(widths);

            console.log(body);

            return {
                // headerRows: 1,
                widths: widths, // panjang standard dia 515
                body: body,
            };
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