<!DOCTYPE html>
<html>
    <head>
        <title>Modified Barthel Index (Shah Version): Self Care Assessment</title>
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
                        image: 'letterhead', width: 430, style: 'tableHeader', colSpan: 5, alignment: 'center'
                    },
                    {
                        text: 'MODIFIED BARTHEL INDEX (SHAH VERSION): SELF CARE ASSESSMENT',
                        style: 'header',
                        alignment: 'center',
                    },
                    {
                        style: 'tableExample',
                        table: {
                            // headerRows: 1,
                            widths: [70,3,'*',60,3,'*'], // panjang standard dia 515
                            body: [
                                [
                                    { text: 'Name' },
                                    { text: ':' },
                                    { text: `{!!$pat_mast->Name!!}`,},
                                    { text: 'MRN' },
                                    { text: ':' },
                                    { text: '{{str_pad($pat_mast->MRN, 7, "0", STR_PAD_LEFT)}}' },
                                ],
                                [
                                    { text: 'NRIC/Passport No.' },
                                    { text: ':' },
                                    { text: '{{$pat_mast->Newic}}' },
                                    { text: 'Date' },
                                    { text: ':' },
                                    { text: '{{\Carbon\Carbon::createFromFormat('Y-m-d',$pat_mast->dateofAssessment)->format('d-m-Y')}}' },
                                ],
                            ]
                            
                        },
                        layout: 'noBorders',
                    },
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            // dontBreakRows: false,
				            // keepWithHeaderRows: 1,   
                            widths: [80,10,10,'*'], // panjang standard dia 515
                            body: [
                                [
                                    { text: 'Index Item', style: 'tableHeader', alignment: 'center', fillColor: '#dddddd' },
                                    { text: 'Score', style: 'tableHeader', alignment: 'center', fillColor: '#dddddd', colSpan:2},{},
                                    { text: 'Description', style: 'tableHeader', alignment: 'center', fillColor: '#dddddd' }
                                ],
                                //chairBedTrf
                                @foreach ($barthel as $obj)
                                    @if($obj->chairBedTrf == '0')
                                        [    
                                            { text: 'CHAIR/BED TRANSFERS', rowSpan:5, bold:true},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Unable to participate in a transfer. Two attendants are required to transfer the patient with or without a mechanical device.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '3', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Able to participate but maximum assistance of one other person is require in all aspects of the transfer.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '8', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The transfer requires the assistance of one other person. Assistance may be required in any aspect of the transfer.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '12', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The presence of another person is required either as a confidence measure, or to provide supervision for safety.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '15', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient can safely approach the bed walking or in a wheelchair, lock brakes, lift footrests, or position walking aid, move safely to bed, lie down, come to a sitting position on the side of the bed, change the position of the wheelchair, transfer back into it safely and/or grasp aid and stand. The patient must be independent in all phases of this activity.', alignment: 'justify' },
                                        ],
                                    @elseif($obj->chairBedTrf == '3')
                                        [
                                            { text: 'CHAIR/BED TRANSFERS', rowSpan:5, bold:true},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Unable to participate in a transfer. Two attendants are required to transfer the patient with or without a mechanical device.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '3', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Able to participate but maximum assistance of one other person is require in all aspects of the transfer.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '8', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The transfer requires the assistance of one other person. Assistance may be required in any aspect of the transfer.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '12', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The presence of another person is required either as a confidence measure, or to provide supervision for safety.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '15', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient can safely approach the bed walking or in a wheelchair, lock brakes, lift footrests, or position walking aid, move safely to bed, lie down, come to a sitting position on the side of the bed, change the position of the wheelchair, transfer back into it safely and/or grasp aid and stand. The patient must be independent in all phases of this activity.', alignment: 'justify' },
                                        ],
                                    @elseif($obj->chairBedTrf == '8')
                                        [
                                            { text: 'CHAIR/BED TRANSFERS', rowSpan:5, bold:true},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Unable to participate in a transfer. Two attendants are required to transfer the patient with or without a mechanical device.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '3', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Able to participate but maximum assistance of one other person is require in all aspects of the transfer.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '8', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The transfer requires the assistance of one other person. Assistance may be required in any aspect of the transfer.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '12', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The presence of another person is required either as a confidence measure, or to provide supervision for safety.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '15', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient can safely approach the bed walking or in a wheelchair, lock brakes, lift footrests, or position walking aid, move safely to bed, lie down, come to a sitting position on the side of the bed, change the position of the wheelchair, transfer back into it safely and/or grasp aid and stand. The patient must be independent in all phases of this activity.', alignment: 'justify' },
                                        ],
                                    @elseif($obj->chairBedTrf == '12')
                                        [
                                            { text: 'CHAIR/BED TRANSFERS', rowSpan:5, bold:true},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Unable to participate in a transfer. Two attendants are required to transfer the patient with or without a mechanical device.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '3', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Able to participate but maximum assistance of one other person is require in all aspects of the transfer.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '8', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The transfer requires the assistance of one other person. Assistance may be required in any aspect of the transfer.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '12', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The presence of another person is required either as a confidence measure, or to provide supervision for safety.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '15', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient can safely approach the bed walking or in a wheelchair, lock brakes, lift footrests, or position walking aid, move safely to bed, lie down, come to a sitting position on the side of the bed, change the position of the wheelchair, transfer back into it safely and/or grasp aid and stand. The patient must be independent in all phases of this activity.', alignment: 'justify' },
                                        ],
                                    @else
                                        [
                                            { text: 'CHAIR/BED TRANSFERS', rowSpan:5, bold:true},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Unable to participate in a transfer. Two attendants are required to transfer the patient with or without a mechanical device.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '3', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Able to participate but maximum assistance of one other person is require in all aspects of the transfer.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '8', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The transfer requires the assistance of one other person. Assistance may be required in any aspect of the transfer.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '12', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The presence of another person is required either as a confidence measure, or to provide supervision for safety.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '15', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient can safely approach the bed walking or in a wheelchair, lock brakes, lift footrests, or position walking aid, move safely to bed, lie down, come to a sitting position on the side of the bed, change the position of the wheelchair, transfer back into it safely and/or grasp aid and stand. The patient must be independent in all phases of this activity.', alignment: 'justify' },
                                        ],
                                    @endif
                                @endforeach

                                //ambulation
                                @foreach ($barthel as $obj)
                                    @if($obj->ambulation == '0')
                                        [    
                                            { text: 'AMBULATION', rowSpan:5, bold:true},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Dependent in ambulation.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '3', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Constant presence of one or more assistant is required during ambulation.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '8', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Assistance is required with reaching aids and/or their manipulation. One person is required to offer assistance.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '12', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is independent in ambulation but unable to walk 50 metres without help, or supervision is needed for confidence or safety in hazardous situations.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '15', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient must be able to wear braces if required, lock and unlock these braces assume standing position, sit down, and place the necessary aids into position for use. The patient must be able to crutches, canes, or a walkarette, and walk 50 metres without help or supervision.', alignment: 'justify' },
                                        ],
                                    @elseif($obj->ambulation == '3')
                                        [
                                            { text: 'AMBULATION', rowSpan:5, bold:true},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Dependent in ambulation.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '3', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Constant presence of one or more assistant is required during ambulation.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '8', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Assistance is required with reaching aids and/or their manipulation. One person is required to offer assistance.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '12', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is independent in ambulation but unable to walk 50 metres without help, or supervision is needed for confidence or safety in hazardous situations.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '15', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient must be able to wear braces if required, lock and unlock these braces assume standing position, sit down, and place the necessary aids into position for use. The patient must be able to crutches, canes, or a walkarette, and walk 50 metres without help or supervision.', alignment: 'justify' },
                                        ],
                                    @elseif($obj->ambulation == '8')
                                        [
                                            { text: 'AMBULATION', rowSpan:5, bold:true},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Dependent in ambulation.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '3', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Constant presence of one or more assistant is required during ambulation.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '8', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Assistance is required with reaching aids and/or their manipulation. One person is required to offer assistance.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '12', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is independent in ambulation but unable to walk 50 metres without help, or supervision is needed for confidence or safety in hazardous situations.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '15', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient must be able to wear braces if required, lock and unlock these braces assume standing position, sit down, and place the necessary aids into position for use. The patient must be able to crutches, canes, or a walkarette, and walk 50 metres without help or supervision.', alignment: 'justify' },
                                        ],
                                    @elseif($obj->ambulation == '12')
                                        [
                                            { text: 'AMBULATION', rowSpan:5, bold:true},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Dependent in ambulation.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '3', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Constant presence of one or more assistant is required during ambulation.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '8', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Assistance is required with reaching aids and/or their manipulation. One person is required to offer assistance.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '12', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is independent in ambulation but unable to walk 50 metres without help, or supervision is needed for confidence or safety in hazardous situations.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '15', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient must be able to wear braces if required, lock and unlock these braces assume standing position, sit down, and place the necessary aids into position for use. The patient must be able to crutches, canes, or a walkarette, and walk 50 metres without help or supervision.', alignment: 'justify' },
                                        ],
                                    @else
                                        [
                                            { text: 'AMBULATION', rowSpan:5, bold:true},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Dependent in ambulation.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '3', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Constant presence of one or more assistant is required during ambulation.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '8', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Assistance is required with reaching aids and/or their manipulation. One person is required to offer assistance.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '12', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is independent in ambulation but unable to walk 50 metres without help, or supervision is needed for confidence or safety in hazardous situations.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '15', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient must be able to wear braces if required, lock and unlock these braces assume standing position, sit down, and place the necessary aids into position for use. The patient must be able to crutches, canes, or a walkarette, and walk 50 metres without help or supervision.', alignment: 'justify' },
                                        ],
                                    @endif
                                @endforeach
                            
                                //ambulationWheelchair
                                @foreach ($barthel as $obj)
                                    @if($obj->ambulationWheelchair == '0')
                                        [    
                                            { text: 'AMBULATION/WHEELCHAIR\n\n*(If unable to walk)\nOnly use thís item if the patient is rated "0" for Ambulation, and then only if the patient has been trained in wheelchair management.', rowSpan:5, bold:true, alignment: 'justify'},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Dependent in wheelchair ambulation.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '1', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Patient can propel self short distances on flat surface, but assistance is required for all other steps of wheelchair management.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '3', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Presence of one person is necessary and constant assistance is required to manipulate chair to table, bed, etc.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '4', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient can propel self for a reasonable duration over regularly encountered terrain. Minimal assistance may still be required in "tight corners" or to negotiate a kerb 100mm high.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '5', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'To propel wheclchair independently, the patient must be able to go around comers, turn around, manoeuvre the chair to a table, bed, toilet, etc. The patient must be able to push a chair at least 50 metres and negotiate a kerb.', alignment: 'justify' },
                                        ],
                                    @elseif($obj->ambulationWheelchair == '1')
                                        [
                                            { text: 'AMBULATION/WHEELCHAIR\n\n*(If unable to walk)\nOnly use thís item if the patient is rated "0" for Ambulation, and then only if the patient has been trained in wheelchair management.', rowSpan:5, bold:true, alignment: 'justify'},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Dependent in wheelchair ambulation.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '1', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Patient can propel self short distances on flat surface, but assistance is required for all other steps of wheelchair management.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '3', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Presence of one person is necessary and constant assistance is required to manipulate chair to table, bed, etc.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '4', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient can propel self for a reasonable duration over regularly encountered terrain. Minimal assistance may still be required in "tight corners" or to negotiate a kerb 100mm high.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '5', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'To propel wheclchair independently, the patient must be able to go around comers, turn around, manoeuvre the chair to a table, bed, toilet, etc. The patient must be able to push a chair at least 50 metres and negotiate a kerb.', alignment: 'justify' },
                                        ],
                                    @elseif($obj->ambulationWheelchair == '3')
                                        [
                                            { text: 'AMBULATION/WHEELCHAIR\n\n*(If unable to walk)\nOnly use thís item if the patient is rated "0" for Ambulation, and then only if the patient has been trained in wheelchair management.', rowSpan:5, bold:true, alignment: 'justify'},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Patient can propel self short distances on flat surface, but assistance is required for all other steps of wheelchair management.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '1', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Constant presence of one or more assistant is required during ambulation.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '3', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Presence of one person is necessary and constant assistance is required to manipulate chair to table, bed, etc.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '4', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient can propel self for a reasonable duration over regularly encountered terrain. Minimal assistance may still be required in "tight corners" or to negotiate a kerb 100mm high.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '5', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'To propel wheclchair independently, the patient must be able to go around comers, turn around, manoeuvre the chair to a table, bed, toilet, etc. The patient must be able to push a chair at least 50 metres and negotiate a kerb.', alignment: 'justify' },
                                        ],
                                    @elseif($obj->ambulationWheelchair == '4')
                                        [
                                            { text: 'AMBULATION/WHEELCHAIR\n\n*(If unable to walk)\nOnly use thís item if the patient is rated "0" for Ambulation, and then only if the patient has been trained in wheelchair management.', rowSpan:5, bold:true, alignment: 'justify'},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Patient can propel self short distances on flat surface, but assistance is required for all other steps of wheelchair management.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '1', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Constant presence of one or more assistant is required during ambulation.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '3', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Presence of one person is necessary and constant assistance is required to manipulate chair to table, bed, etc.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '4', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient can propel self for a reasonable duration over regularly encountered terrain. Minimal assistance may still be required in "tight corners" or to negotiate a kerb 100mm high.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '5', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'To propel wheclchair independently, the patient must be able to go around comers, turn around, manoeuvre the chair to a table, bed, toilet, etc. The patient must be able to push a chair at least 50 metres and negotiate a kerb.', alignment: 'justify' },
                                        ],
                                    @elseif($obj->ambulationWheelchair == '5')
                                        [
                                            { text: 'AMBULATION/WHEELCHAIR\n\n*(If unable to walk)\nOnly use thís item if the patient is rated "0" for Ambulation, and then only if the patient has been trained in wheelchair management.', rowSpan:5, bold:true, alignment: 'justify'},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Dependent in wheelchair ambulation.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '1', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Patient can propel self short distances on flat surface, but assistance is required for all other steps of wheelchair management.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '3', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Presence of one person is necessary and constant assistance is required to manipulate chair to table, bed, etc.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '4', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient can propel self for a reasonable duration over regularly encountered terrain. Minimal assistance may still be required in "tight corners" or to negotiate a kerb 100mm high.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '5', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'To propel wheclchair independently, the patient must be able to go around comers, turn around, manoeuvre the chair to a table, bed, toilet, etc. The patient must be able to push a chair at least 50 metres and negotiate a kerb.', alignment: 'justify' },
                                        ],
                                        @else
                                        [
                                            { text: 'AMBULATION/WHEELCHAIR\n\n*(If unable to walk)\nOnly use thís item if the patient is rated "0" for Ambulation, and then only if the patient has been trained in wheelchair management.', rowSpan:5, bold:true, alignment: 'justify'},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Dependent in wheelchair ambulation.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '1', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Patient can propel self short distances on flat surface, but assistance is required for all other steps of wheelchair management.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '3', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Presence of one person is necessary and constant assistance is required to manipulate chair to table, bed, etc.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '4', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient can propel self for a reasonable duration over regularly encountered terrain. Minimal assistance may still be required in "tight corners" or to negotiate a kerb 100mm high.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '5', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'To propel wheclchair independently, the patient must be able to go around comers, turn around, manoeuvre the chair to a table, bed, toilet, etc. The patient must be able to push a chair at least 50 metres and negotiate a kerb.', alignment: 'justify' },
                                        ],
                                    @endif
                                @endforeach
                                
                                //stairClimbing
                                @foreach ($barthel as $obj)
                                    @if($obj->stairClimbing == '0')
                                        [    
                                            { text: 'STAIR CLIMBING', rowSpan:5, bold:true},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is unable to climb stairs.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '2', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Assistance is required in all aspects of chair climbing, including assistance with walking aids.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '5', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is able to ascend/descend but is unable to carry walking aids and needs supervision and assistance.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '8', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Generally no assistance is required. At times supervision is required for safety due to morning stiffness, shortness of breath, etc.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '10', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is able to go up and down a flight of stairs safely without help or supervision. The patient is able to use hand rails, cane or crutches when needed and is able to carry these devices as he/she ascends or descends.', alignment: 'justify' },
                                        ],
                                    @elseif($obj->stairClimbing == '2')
                                        [
                                            { text: 'STAIR CLIMBING', rowSpan:5, bold:true},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is unable to climb stairs.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '2', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Assistance is required in all aspects of chair climbing, including assistance with walking aids.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '5', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is able to ascend/descend but is unable to carry walking aids and needs supervision and assistance.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '8', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Generally no assistance is required. At times supervision is required for safety due to morning stiffness, shortness of breath, etc.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '10', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is able to go up and down a flight of stairs safely without help or supervision. The patient is able to use hand rails, cane or crutches when needed and is able to carry these devices as he/she ascends or descends.', alignment: 'justify' },
                                        ],
                                    @elseif($obj->stairClimbing == '5')
                                        [
                                            { text: 'STAIR CLIMBING', rowSpan:5, bold:true},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is unable to climb stairs.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '2', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Assistance is required in all aspects of chair climbing, including assistance with walking aids.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '5', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is able to ascend/descend but is unable to carry walking aids and needs supervision and assistance.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '8', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Generally no assistance is required. At times supervision is required for safety due to morning stiffness, shortness of breath, etc.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '10', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is able to go up and down a flight of stairs safely without help or supervision. The patient is able to use hand rails, cane or crutches when needed and is able to carry these devices as he/she ascends or descends.', alignment: 'justify' },
                                        ],
                                    @elseif($obj->stairClimbing == '8')
                                        [
                                            { text: 'STAIR CLIMBING', rowSpan:5, bold:true},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is unable to climb stairs.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '2', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Assistance is required in all aspects of chair climbing, including assistance with walking aids.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '5', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is able to ascend/descend but is unable to carry walking aids and needs supervision and assistance.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '8', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Generally no assistance is required. At times supervision is required for safety due to morning stiffness, shortness of breath, etc.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '10', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is able to go up and down a flight of stairs safely without help or supervision. The patient is able to use hand rails, cane or crutches when needed and is able to carry these devices as he/she ascends or descends.', alignment: 'justify' },
                                        ],
                                    @else
                                        [
                                            { text: 'STAIR CLIMBING', rowSpan:5, bold:true},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is unable to climb stairs.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '2', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Assistance is required in all aspects of chair climbing, including assistance with walking aids.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '5', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is able to ascend/descend but is unable to carry walking aids and needs supervision and assistance.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '8', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Generally no assistance is required. At times supervision is required for safety due to morning stiffness, shortness of breath, etc.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '10', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is able to go up and down a flight of stairs safely without help or supervision. The patient is able to use hand rails, cane or crutches when needed and is able to carry these devices as he/she ascends or descends.', alignment: 'justify' },
                                        ],
                                    @endif
                                @endforeach
                                
                                //toiletTrf
                                @foreach ($barthel as $obj)
                                    @if($obj->toiletTrf == '0')
                                        [    
                                            { text: 'TOILET TRANSFERS', rowSpan:5, bold:true, pageBreak: 'before'},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true], pageBreak: 'before'},
                                            { text: '0', alignment: 'left', border: [false, true, true, true], pageBreak: 'before' },//ltrb
                                            { text: 'Fully dependent in toileting.', alignment: 'justify', pageBreak: 'before' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '2', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Assistance required in all aspects of toileting.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '5', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Assistance may be required with management of clothing, transferring, or washing hands.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '8', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Supervision may be required for safety with normal toilet. A commode may be used at night but assistance is required for emptying and cleaning.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '10', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is able to get on/off the toilet, fasten clothing and use toilet paper without help. If necessary, the patient may use a bed pan or commode or urinal at night, but must be able to empty it and clean it.', alignment: 'justify' },
                                        ],
                                    @elseif($obj->toiletTrf == '2')
                                        [
                                            { text: 'TOILET TRANSFERS', rowSpan:5, bold:true, pageBreak: 'before'},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true], pageBreak: 'before'},
                                            { text: '0', alignment: 'left', border: [false, true, true, true], pageBreak: 'before' },//ltrb
                                            { text: 'Fully dependent in toileting.', pageBreak: 'before', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '2', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Assistance required in all aspects of toileting.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '5', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Assistance may be required with management of clothing, transferring, or washing hands.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '8', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Supervision may be required for safety with normal toilet. A commode may be used at night but assistance is required for emptying and cleaning.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '10', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is able to get on/off the toilet, fasten clothing and use toilet paper without help. If necessary, the patient may use a bed pan or commode or urinal at night, but must be able to empty it and clean it.', alignment: 'justify' },
                                        ],
                                    @elseif($obj->toiletTrf == '5')
                                        [
                                            { text: 'TOILET TRANSFERS', rowSpan:5, bold:true, pageBreak: 'before'},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true], pageBreak: 'before'},
                                            { text: '0', alignment: 'left', border: [false, true, true, true], pageBreak: 'before' },//ltrb
                                            { text: 'Fully dependent in toileting.', alignment: 'justify', pageBreak: 'before' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '2', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Assistance required in all aspects of toileting.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '5', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Assistance may be required with management of clothing, transferring, or washing hands.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '8', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Supervision may be required for safety with normal toilet. A commode may be used at night but assistance is required for emptying and cleaning.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '10', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is able to get on/off the toilet, fasten clothing and use toilet paper without help. If necessary, the patient may use a bed pan or commode or urinal at night, but must be able to empty it and clean it.', alignment: 'justify' },
                                        ],
                                    @elseif($obj->toiletTrf == '8')
                                        [
                                            { text: 'TOILET TRANSFERS', rowSpan:5, bold:true, pageBreak: 'before'},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true], pageBreak: 'before'},
                                            { text: '0', alignment: 'left', border: [false, true, true, true], pageBreak: 'before' },//ltrb
                                            { text: 'Fully dependent in toileting.', alignment: 'justify', pageBreak: 'before' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '2', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Assistance required in all aspects of toileting.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '5', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Assistance may be required with management of clothing, transferring, or washing hands.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '8', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Supervision may be required for safety with normal toilet. A commode may be used at night but assistance is required for emptying and cleaning.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '10', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is able to get on/off the toilet, fasten clothing and use toilet paper without help. If necessary, the patient may use a bed pan or commode or urinal at night, but must be able to empty it and clean it.', alignment: 'justify' },
                                        ],
                                    @else
                                        [
                                            { text: 'TOILET TRANSFERS', rowSpan:5, bold:true, pageBreak: 'before'},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true], pageBreak: 'before'},
                                            { text: '0', alignment: 'left', border: [false, true, true, true], pageBreak: 'before' },//ltrb
                                            { text: 'Fully dependent in toileting.', alignment: 'justify', pageBreak: 'before' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '2', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Assistance required in all aspects of toileting.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '5', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Assistance may be required with management of clothing, transferring, or washing hands.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '8', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Supervision may be required for safety with normal toilet. A commode may be used at night but assistance is required for emptying and cleaning.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '10', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is able to get on/off the toilet, fasten clothing and use toilet paper without help. If necessary, the patient may use a bed pan or commode or urinal at night, but must be able to empty it and clean it.', alignment: 'justify' },
                                        ],
                                    @endif
                                @endforeach
                               
                                //bowelControl
                                @foreach ($barthel as $obj)
                                    @if($obj->bowelControl == '0')
                                        [    
                                            { text: 'BOWEL CONTROL', rowSpan:5, bold:true},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is bowel incontinent.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '2', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient needs help to assume appropriate position, and with bowel movement facilitatory techniques.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '5', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient can assume appropriate position, but cannot use facilitatory techniques or clean self without assistance and has frequent accidents. Assistance is required with incontinence aids such as pad, etc.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '8', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient may require supervision with the use of suppository or enema and has occasional accidents.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '10', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient can control bowels and has no accidents, can use suppository, or take an enema when necessary.', alignment: 'justify' },
                                        ],
                                    @elseif($obj->bowelControl == '2')
                                        [
                                            { text: 'BOWEL CONTROL', rowSpan:5, bold:true},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is bowel incontinent.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '2', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient needs help to assume appropriate position, and with bowel movement facilitatory techniques.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '5', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient can assume appropriate position, but cannot use facilitatory techniques or clean self without assistance and has frequent accidents. Assistance is required with incontinence aids such as pad, etc.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '8', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient may require supervision with the use of suppository or enema and has occasional accidents.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '10', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient can control bowels and has no accidents, can use suppository, or take an enema when necessary.', alignment: 'justify' },
                                        ],
                                    @elseif($obj->bowelControl == '5')
                                        [
                                            { text: 'BOWEL CONTROL', rowSpan:5, bold:true},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is bowel incontinent.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '2', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient needs help to assume appropriate position, and with bowel movement facilitatory techniques.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '5', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient can assume appropriate position, but cannot use facilitatory techniques or clean self without assistance and has frequent accidents. Assistance is required with incontinence aids such as pad, etc.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '8', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient may require supervision with the use of suppository or enema and has occasional accidents.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '10', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient can control bowels and has no accidents, can use suppository, or take an enema when necessary.', alignment: 'justify' },
                                        ],
                                    @elseif($obj->bowelControl == '8')
                                        [
                                            { text: 'BOWEL CONTROL', rowSpan:5, bold:true},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is bowel incontinent.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '2', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient needs help to assume appropriate position, and with bowel movement facilitatory techniques.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '5', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient can assume appropriate position, but cannot use facilitatory techniques or clean self without assistance and has frequent accidents. Assistance is required with incontinence aids such as pad, etc.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '8', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient may require supervision with the use of suppository or enema and has occasional accidents.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '10', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient can control bowels and has no accidents, can use suppository, or take an enema when necessary.', alignment: 'justify' },
                                        ],
                                    @else
                                        [
                                            { text: 'BOWEL CONTROL', rowSpan:5, bold:true},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is bowel incontinent.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '2', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient needs help to assume appropriate position, and with bowel movement facilitatory techniques.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '5', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient can assume appropriate position, but cannot use facilitatory techniques or clean self without assistance and has frequent accidents. Assistance is required with incontinence aids such as pad, etc.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '8', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient may require supervision with the use of suppository or enema and has occasional accidents.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '10', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient can control bowels and has no accidents, can use suppository, or take an enema when necessary.', alignment: 'justify' },
                                        ],
                                    @endif
                                @endforeach
                                
                                //bladderControl
                                @foreach ($barthel as $obj)
                                    @if($obj->bladderControl == '0')
                                        [    
                                            { text: 'BLADDER CONTROL', rowSpan:5, bold:true},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is dependent in bladder management, is incontinent, or has indwelling catheter.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '2', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is incontinent but is able to assist with the application of an intermal or external device.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '5', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is generally dry by day, but not at night and needs some assistance with the devices.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '8', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is generally dry by day and night, but may have an occasional accident or need minimal assistance with internal or external devices.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '10', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is able to control bladder day and night, and/or is independent with internal or external devices.', alignment: 'justify' },
                                        ],
                                    @elseif($obj->bladderControl == '2')
                                        [
                                            { text: 'BLADDER CONTROL', rowSpan:5, bold:true},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is dependent in bladder management, is incontinent, or has indwelling catheter.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '2', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is incontinent but is able to assist with the application of an intermal or external device.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '5', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is generally dry by day, but not at night and needs some assistance with the devices.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '8', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is generally dry by day and night, but may have an occasional accident or need minimal assistance with internal or external devices.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '10', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is able to control bladder day and night, and/or is independent with internal or external devices.', alignment: 'justify' },
                                        ],
                                    @elseif($obj->bladderControl == '5')
                                        [
                                            { text: 'BLADDER CONTROL', rowSpan:5, bold:true},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is dependent in bladder management, is incontinent, or has indwelling catheter.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '2', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is incontinent but is able to assist with the application of an intermal or external device.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '5', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is generally dry by day, but not at night and needs some assistance with the devices.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '8', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is generally dry by day and night, but may have an occasional accident or need minimal assistance with internal or external devices.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '10', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is able to control bladder day and night, and/or is independent with internal or external devices.', alignment: 'justify' },
                                        ],
                                    @elseif($obj->bladderControl == '8')
                                        [
                                            { text: 'BLADDER CONTROL', rowSpan:5, bold:true},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is dependent in bladder management, is incontinent, or has indwelling catheter.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '2', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is incontinent but is able to assist with the application of an intermal or external device.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '5', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is generally dry by day, but not at night and needs some assistance with the devices.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '8', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is generally dry by day and night, but may have an occasional accident or need minimal assistance with internal or external devices.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '10', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is able to control bladder day and night, and/or is independent with internal or external devices.', alignment: 'justify' },
                                        ],
                                    @else
                                        [
                                            { text: 'BLADDER CONTROL', rowSpan:5, bold:true},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is dependent in bladder management, is incontinent, or has indwelling catheter.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '2', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is incontinent but is able to assist with the application of an intermal or external device.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '5', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is generally dry by day, but not at night and needs some assistance with the devices.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '8', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is generally dry by day and night, but may have an occasional accident or need minimal assistance with internal or external devices.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '10', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is able to control bladder day and night, and/or is independent with internal or external devices.', alignment: 'justify' },
                                        ],
                                    @endif
                                @endforeach
                                
                                //bathing
                                @foreach ($barthel as $obj)
                                    @if($obj->bathing == '0')
                                        [    
                                            { text: 'BATHING', rowSpan:5, bold:true},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Total dependence in bathing self.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '1', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Assistance is required in all aspects of bathing, but patient is able to make some contribution.' , alignment: 'justify'},
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '3', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Assistance is required with either transfer to shower/bath or with washing or drying; including inability to complete a task because of condition or disease, etc.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '4', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Supervision is required for safety in adjusting the water temperature, or in the transfer.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '5', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient may use a bathtub, a shower, or take a complete sponge bath. The patient must be able to do all the steps of whichever method is employed without another person being present.', alignment: 'justify' },
                                        ],
                                    @elseif($obj->bathing == '1')
                                        [
                                            { text: 'BATHING', rowSpan:5, bold:true},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Total dependence in bathing self.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '1', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Assistance is required in all aspects of bathing, but patient is able to make some contribution.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '3', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Assistance is required with either transfer to shower/bath or with washing or drying; including inability to complete a task because of condition or disease, etc.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '4', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Supervision is required for safety in adjusting the water temperature, or in the transfer.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '5', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient may use a bathtub, a shower, or take a complete sponge bath. The patient must be able to do all the steps of whichever method is employed without another person being present.', alignment: 'justify' },
                                        ],
                                    @elseif($obj->bathing == '3')
                                        [
                                            { text: 'BATHING', rowSpan:5, bold:true},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Total dependence in bathing self.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '1', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Assistance is required in all aspects of bathing, but patient is able to make some contribution.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '3', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Assistance is required with either transfer to shower/bath or with washing or drying; including inability to complete a task because of condition or disease, etc.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '4', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Supervision is required for safety in adjusting the water temperature, or in the transfer.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '5', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient may use a bathtub, a shower, or take a complete sponge bath. The patient must be able to do all the steps of whichever method is employed without another person being present.', alignment: 'justify' },
                                        ],
                                    @elseif($obj->bathing == '4')
                                        [
                                            { text: 'BATHING', rowSpan:5, bold:true},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Total dependence in bathing self.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '1', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Assistance is required in all aspects of bathing, but patient is able to make some contribution.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '3', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Assistance is required with either transfer to shower/bath or with washing or drying; including inability to complete a task because of condition or disease, etc.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '4', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Supervision is required for safety in adjusting the water temperature, or in the transfer.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '5', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient may use a bathtub, a shower, or take a complete sponge bath. The patient must be able to do all the steps of whichever method is employed without another person being present.', alignment: 'justify' },
                                        ],
                                    @else
                                        [
                                            { text: 'BATHING', rowSpan:5, bold:true},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Total dependence in bathing self.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '1', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Assistance is required in all aspects of bathing, but patient is able to make some contribution.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '3', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Assistance is required with either transfer to shower/bath or with washing or drying; including inability to complete a task because of condition or disease, etc.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '4', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Supervision is required for safety in adjusting the water temperature, or in the transfer.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '5', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient may use a bathtub, a shower, or take a complete sponge bath. The patient must be able to do all the steps of whichever method is employed without another person being present.', alignment: 'justify' },
                                        ],
                                    @endif
                                @endforeach
                                
                                //dressing
                                @foreach ($barthel as $obj)
                                    @if($obj->dressing == '0')
                                        [    
                                            { text: 'DRESSING', rowSpan:5, bold:true},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is dependent in all aspects of dressing and is unable to participate in the activity.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '2', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is able to participate to some degree, but is dependent in all aspects of dressing.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '5', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Assistance is needed in putting on, and/or removing any clothing.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '8', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Only minimal assistance is required with fastening clothing such as buttons, zips, bra, shoes, etc.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '10', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is able to put on, remove, corset, braces, as prescribed.', alignment: 'justify' },
                                        ],
                                    @elseif($obj->dressing == '2')
                                        [
                                            { text: 'DRESSING', rowSpan:5, bold:true},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is dependent in all aspects of dressing and is unable to participate in the activity.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '2', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is able to participate to some degree, but is dependent in all aspects of dressing.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '5', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Assistance is needed in putting on, and/or removing any clothing.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '8', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Only minimal assistance is required with fastening clothing such as buttons, zips, bra, shoes, etc.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '10', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is able to put on, remove, corset, braces, as prescribed.', alignment: 'justify' },
                                        ],
                                    @elseif($obj->dressing == '5')
                                        [
                                            { text: 'DRESSING', rowSpan:5, bold:true},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is dependent in all aspects of dressing and is unable to participate in the activity.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '2', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is able to participate to some degree, but is dependent in all aspects of dressing.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '5', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Assistance is needed in putting on, and/or removing any clothing.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '8', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Only minimal assistance is required with fastening clothing such as buttons, zips, bra, shoes, etc.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '10', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is able to put on, remove, corset, braces, as prescribed.', alignment: 'justify' },
                                        ],
                                    @elseif($obj->dressing == '8')
                                        [
                                            { text: 'DRESSING', rowSpan:5, bold:true},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is dependent in all aspects of dressing and is unable to participate in the activity.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '2', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is able to participate to some degree, but is dependent in all aspects of dressing.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '5', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Assistance is needed in putting on, and/or removing any clothing.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '8', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Only minimal assistance is required with fastening clothing such as buttons, zips, bra, shoes, etc.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '10', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is able to put on, remove, corset, braces, as prescribed.', alignment: 'justify' },
                                        ],
                                    @else
                                        [
                                            { text: 'DRESSING', rowSpan:5, bold:true},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is dependent in all aspects of dressing and is unable to participate in the activity.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '2', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is able to participate to some degree, but is dependent in all aspects of dressing.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '5', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Assistance is needed in putting on, and/or removing any clothing.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '8', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Only minimal assistance is required with fastening clothing such as buttons, zips, bra, shoes, etc.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '10', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is able to put on, remove, corset, braces, as prescribed.', alignment: 'justify' },
                                        ],
                                    @endif
                                @endforeach
                                
                                //personalHygiene
                                @foreach ($barthel as $obj)
                                    @if($obj->personalHygiene == '0')
                                        [    
                                            { text: 'PERSONAL HYGIENE\n\n(Grooming)', rowSpan:5, bold:true},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is unable to attend to personal hygiene and is dependent in all aspects.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '1', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Assistance is required in all steps of personal hygiene, but patient able to make some contribution.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '3', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Some assistance is required in one or more steps of personal hygiene.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '4', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Patient is able to conduct his/her own personal bygiene but requires minimal assistance before and/or after the operation.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '5', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient can wash his/her hands and face, comb hair, clean teeth and shave. A male patient may use any kind of razor but must insert the blade, or plug in the razor without help, as well as retrieve it from the drawer or cabinet. A female patient must apply her own make-up, if used, but need not braid or style her hair.', alignment: 'justify' },
                                        ],
                                    @elseif($obj->personalHygiene == '1')
                                        [
                                            { text: 'PERSONAL HYGIENE\n\n(Grooming)', rowSpan:5, bold:true},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is unable to attend to personal hygiene and is dependent in all aspects.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '1', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Assistance is required in all steps of personal hygiene, but patient able to make some contribution.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '3', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Some assistance is required in one or more steps of personal hygiene.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '4', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Patient is able to conduct his/her own personal bygiene but requires minimal assistance before and/or after the operation.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '5', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient can wash his/her hands and face, comb hair, clean teeth and shave. A male patient may use any kind of razor but must insert the blade, or plug in the razor without help, as well as retrieve it from the drawer or cabinet. A female patient must apply her own make-up, if used, but need not braid or style her hair.', alignment: 'justify' },
                                        ],
                                    @elseif($obj->personalHygiene == '3')
                                        [
                                            { text: 'PERSONAL HYGIENE\n\n(Grooming)', rowSpan:5, bold:true},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is unable to attend to personal hygiene and is dependent in all aspects.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '1', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Assistance is required in all steps of personal hygiene, but patient able to make some contribution.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '3', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Some assistance is required in one or more steps of personal hygiene.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '4', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Patient is able to conduct his/her own personal bygiene but requires minimal assistance before and/or after the operation.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '5', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient can wash his/her hands and face, comb hair, clean teeth and shave. A male patient may use any kind of razor but must insert the blade, or plug in the razor without help, as well as retrieve it from the drawer or cabinet. A female patient must apply her own make-up, if used, but need not braid or style her hair.', alignment: 'justify' },
                                        ],
                                    @elseif($obj->personalHygiene == '4')
                                        [
                                            { text: 'PERSONAL HYGIENE\n\n(Grooming)', rowSpan:5, bold:true},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is unable to attend to personal hygiene and is dependent in all aspects.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '1', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Assistance is required in all steps of personal hygiene, but patient able to make some contribution.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '3', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Some assistance is required in one or more steps of personal hygiene.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '4', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Patient is able to conduct his/her own personal bygiene but requires minimal assistance before and/or after the operation.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '5', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient can wash his/her hands and face, comb hair, clean teeth and shave. A male patient may use any kind of razor but must insert the blade, or plug in the razor without help, as well as retrieve it from the drawer or cabinet. A female patient must apply her own make-up, if used, but need not braid or style her hair.', alignment: 'justify' },
                                        ],
                                    @else
                                        [
                                            { text: 'PERSONAL HYGIENE\n\n(Grooming)', rowSpan:5, bold:true},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient is unable to attend to personal hygiene and is dependent in all aspects.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '1', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Assistance is required in all steps of personal hygiene, but patient able to make some contribution.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '3', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Some assistance is required in one or more steps of personal hygiene.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '4', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Patient is able to conduct his/her own personal bygiene but requires minimal assistance before and/or after the operation.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '5', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient can wash his/her hands and face, comb hair, clean teeth and shave. A male patient may use any kind of razor but must insert the blade, or plug in the razor without help, as well as retrieve it from the drawer or cabinet. A female patient must apply her own make-up, if used, but need not braid or style her hair.', alignment: 'justify' },
                                        ],
                                    @endif
                                @endforeach
                                
                                //feeding
                                @foreach ($barthel as $obj)
                                    @if($obj->feeding == '0')
                                        [    
                                            { text: 'FEEDING', rowSpan:5, bold:true},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Dependent in all aspects and needs to be fed, nasogastric needs to be administered.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '2', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Can manipulate an eating device, usually a spoon, but someone must provide active assistance during the meal.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '5', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Able to feed self with supervision. Assistance is required with associated tasks such as putting milk/sugar into tea, salt, pepper, spreading butter, turning a plate or other "set up" activities.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '8', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Independence in feeding with prepared tray, except may need meat cut, milk carton opened or jar lid etc. The presence of another person is not required.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '10', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient can feed self trom a tray or table when someone puts the food within reach. The patient must put on an assistive device if needed, cut food, and if desired use salt and pepper, spread butter, etc.', alignment: 'justify' },
                                        ],
                                    @elseif($obj->feeding == '2')
                                        [
                                            { text: 'FEEDING', rowSpan:5, bold:true},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Dependent in all aspects and needs to be fed, nasogastric needs to be administered.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '2', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Can manipulate an eating device, usually a spoon, but someone must provide active assistance during the meal.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '5', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Able to feed self with supervision. Assistance is required with associated tasks such as putting milk/sugar into tea, salt, pepper, spreading butter, turning a plate or other "set up" activities.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '8', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Independence in feeding with prepared tray, except may need meat cut, milk carton opened or jar lid etc. The presence of another person is not required.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '10', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient can feed self trom a tray or table when someone puts the food within reach. The patient must put on an assistive device if needed, cut food, and if desired use salt and pepper, spread butter, etc.', alignment: 'justify' },
                                        ],
                                    @elseif($obj->feeding == '5')
                                        [
                                            { text: 'FEEDING', rowSpan:5, bold:true},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Dependent in all aspects and needs to be fed, nasogastric needs to be administered.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '2', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Can manipulate an eating device, usually a spoon, but someone must provide active assistance during the meal.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '5', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Able to feed self with supervision. Assistance is required with associated tasks such as putting milk/sugar into tea, salt, pepper, spreading butter, turning a plate or other "set up" activities.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '8', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Independence in feeding with prepared tray, except may need meat cut, milk carton opened or jar lid etc. The presence of another person is not required.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '10', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient can feed self trom a tray or table when someone puts the food within reach. The patient must put on an assistive device if needed, cut food, and if desired use salt and pepper, spread butter, etc.', alignment: 'justify' },
                                        ],
                                    @elseif($obj->feeding == '8')
                                        [
                                            { text: 'FEEDING', rowSpan:5, bold:true},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Dependent in all aspects and needs to be fed, nasogastric needs to be administered.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '2', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Can manipulate an eating device, usually a spoon, but someone must provide active assistance during the meal.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '5', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Able to feed self with supervision. Assistance is required with associated tasks such as putting milk/sugar into tea, salt, pepper, spreading butter, turning a plate or other "set up" activities.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '8', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Independence in feeding with prepared tray, except may need meat cut, milk carton opened or jar lid etc. The presence of another person is not required.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '10', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient can feed self trom a tray or table when someone puts the food within reach. The patient must put on an assistive device if needed, cut food, and if desired use salt and pepper, spread butter, etc.', alignment: 'justify' },
                                        ],
                                    @else
                                        [
                                            { text: 'FEEDING', rowSpan:5, bold:true},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '0', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Dependent in all aspects and needs to be fed, nasogastric needs to be administered.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '2', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Can manipulate an eating device, usually a spoon, but someone must provide active assistance during the meal.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '5', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Able to feed self with supervision. Assistance is required with associated tasks such as putting milk/sugar into tea, salt, pepper, spreading butter, turning a plate or other "set up" activities.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'unchecked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '8', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'Independence in feeding with prepared tray, except may need meat cut, milk carton opened or jar lid etc. The presence of another person is not required.', alignment: 'justify' },
                                        ],
                                        [
                                            {},
                                            { image: 'checked', width: 10, alignment: 'right', border: [true, true, false, true]},
                                            { text: '10', alignment: 'left', border: [false, true, true, true] },//ltrb
                                            { text: 'The patient can feed self trom a tray or table when someone puts the food within reach. The patient must put on an assistive device if needed, cut food, and if desired use salt and pepper, spread butter, etc.', alignment: 'justify' },
                                        ],
                                    @endif
                                @endforeach

                                //tot_score
                                @foreach ($barthel as $obj)
                                    [    
                                        { text: 'TOTAL SCORE', bold:true},
                                        { text: '{{$obj->tot_score}}', colSpan:3},{},{},
                                    ],
                                @endforeach
                                
                                //interpretation
                                @foreach ($barthel as $obj)
                                    [    
                                        { text: 'INTERPRETATION', bold:true},
                                        { text: '{{$obj->interpretation}}', colSpan:3},{},{},
                                    ],
                                @endforeach
                                
                                //prediction
                                @foreach ($barthel as $obj)
                                    [    
                                        { text: 'PREDICTION', bold:true},
                                        { text: `{!!$obj->prediction!!}`, colSpan:3},{},{},
                                    ],
                                @endforeach
                            ],
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
                    header1: {
                        fontSize: 14,
                        bold: true,
                        margin: [60, 0, 0, 10]
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
                    tableExample2: {
                        fontSize: 8,
                        margin: [0, 0, 0, 0]
                    },
                    tableHeader: {
                        bold: true,
                        fontSize: 9,
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
                    },
                    checked: {
                        url: "{{asset('/img/checked.png')}}",
                        
                    },
                    unchecked: {
                        url: "{{asset('/img/unchecked.png')}}",
                        
                    }
                }
            };
            
            // pdfMake.createPdf(docDefinition).getBase64(function (data){
            //     var base64data = "data:base64"+data;
            //     console.log($('object#pdfPreview').attr('data',base64data));
            //     // document.getElementById('pdfPreview').data = base64data;
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