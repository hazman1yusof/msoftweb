<table>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align: center" colspan="4">{{$comp_name}}</td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align: center" colspan="4">Collection Ageing</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align: center" colspan="4">Date From {{$date_from}} to {{$date_to}}</td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
    <tr>
        <td style="font-weight:bold; text-align: left">Payer Code</td>
        <td style="font-weight:bold; text-align: left">Payer Name</td>
        <td style="font-weight:bold; text-align: left">Unallocated Amount</td>
        @foreach ($grouping as $key => $group)
            @if($key+1 < count($grouping))
            <td style="font-weight:bold; text-align: left">{{$group+1}}-{{$grouping[$key+1]}} Days</td>
            @else
            <td style="font-weight:bold; text-align: left">> {{$group}} Days</td>
            @endif
        @endforeach
        <td style="font-weight:bold; text-align: left">Total</td>
        @if($type == 'SUMMARY')
            <td style="font-weight:bold; text-align: left"></td>
            <td style="font-weight:bold; text-align: left"></td>
            <td style="font-weight:bold; text-align: left"></td>
        @else
            <td style="font-weight:bold; text-align: left">Receipt No.</td>
            <td style="font-weight:bold; text-align: left">Date</td>
            <td style="font-weight:bold; text-align: left">Unit</td>
        @endif
    </tr>      

    @php
        $grandtotal = 0;
    @endphp

    @if($type == 'SUMMARY')
        @foreach ($debtorcode as $dcode)
            @php
                $pun_total = 0;
                $groupOne_total = 0;
                $groupTwo_total = 0;
                $groupThree_total = 0;
                $groupFour_total = 0;
                $groupFive_total = 0;
                $groupSix_total = 0;

                foreach ($array_report_1 as $obj_ar) {
                    if($obj_ar->debtorcode == $dcode->debtorcode){
                        $pun_total = $pun_total + $obj_ar->punallocamt;

                        $groupOne_total = $groupOne_total + $obj_ar->groupOne;
                        $groupTwo_total = $groupTwo_total + $obj_ar->groupTwo;
                        $groupThree_total = $groupThree_total + $obj_ar->groupThree;
                        $groupFour_total = $groupFour_total + $obj_ar->groupFour;
                        $groupFive_total = $groupFive_total + $obj_ar->groupFive;
                        $groupSix_total = $groupSix_total + $obj_ar->groupSix;
                    }else{

                    }
                }
            @endphp
            <tr>
                <td style="text-align: left">{{$dcode->debtorcode}}</td>
                <td style="text-align: left">{{$dcode->name}}</td>
                <td style="text-align: left">{{$pun_total}}</td>

                @php($total = $pun_total + $groupOne_total + $groupTwo_total + $groupThree_total + $groupFour_total + $groupFive_total + $groupSix_total)
                @foreach ($grouping as $key => $obj)
                    @if($key == 0)
                        <td>{{$groupOne_total}}</td>
                    @elseif($key == 1)
                        <td>{{$groupTwo_total}}</td>
                    @elseif($key == 2)
                        <td>{{$groupThree_total}}</td>
                    @elseif($key == 3)
                        <td>{{$groupFour_total}}</td>
                    @elseif($key == 4)
                        <td>{{$groupFive_total}}</td>
                    @elseif($key == 5)
                        <td>{{$groupSix_total}}</td>
                    @else
                        <td></td>
                    @endif 
                @endforeach

                <td>{{$total}}</td>

            </tr>

            @php($grandtotal = $grandtotal + $total)
        @endforeach

    @else

        @foreach ($unit as $unit_)
            <tr>
                <td style="font-weight:bold; text-align: left">{{$unit_->unit}}</td>
                <td style="font-weight:bold; text-align: left">{{$unit_->unit_desc}}</td>
            </tr>
            @php($unit_total = 0)
            @foreach ($array_report_1 as $ar_1)
                @if($unit_->unit == $ar_1->unit)
                    <tr>
                        <td style="text-align: left">{{$ar_1->debtorcode}}</td>
                        <td style="text-align: left">{{$ar_1->name}}</td>
                        <td style="text-align: left">{{$ar_1->punallocamt}}</td>

                    @php($total = $ar_1->punallocamt + $ar_1->groupOne + $ar_1->groupTwo + $ar_1->groupThree + $ar_1->groupFour + $ar_1->groupFive + $ar_1->groupSix)
                    @foreach ($grouping as $key => $obj)
                        @if($key == 0)
                            <td>{{$ar_1->groupOne}}</td>
                        @elseif($key == 1)
                            <td>{{$ar_1->groupTwo}}</td>
                        @elseif($key == 2)
                            <td>{{$ar_1->groupThree}}</td>
                        @elseif($key == 3)
                            <td>{{$ar_1->groupFour}}</td>
                        @elseif($key == 4)
                            <td>{{$ar_1->groupFive}}</td>
                        @elseif($key == 5)
                            <td>{{$ar_1->groupSix}}</td>
                        @else
                            <td></td>
                        @endif 
                    @endforeach
                        <td>{{$total}}</td>
                        <td style="text-align: left">{{$ar_1->recptno}}</td>
                        <td style="text-align: left">{{$ar_1->posteddate}}</td>
                        <td style="text-align: left">{{$ar_1->unit}}</td>

                    </tr>

                    @php($grandtotal = $grandtotal + $total)
                    @php($unit_total = $unit_total + $total)
                @endif
            @endforeach
            <tr>
                <td ></td>
                <td ></td>
                <td style="font-weight:bold; text-align: left">UNIT TOTAL</td>
                @foreach ($grouping as $key => $obj)
                    <td></td>
                @endforeach
                <td>{{$unit_total}}</td>
            </tr>
            <tr></tr>
        @endforeach

    @endif

    <tr></tr>

    <tr>
        <td ></td>
        <td ></td>
        <td style="font-weight:bold; text-align: left">GRAND TOTAL</td>
        @foreach ($grouping as $key => $obj)
            <td></td>
        @endforeach
        <td>{{$grandtotal}}</td>
    </tr>
    <tr></tr>
</table>