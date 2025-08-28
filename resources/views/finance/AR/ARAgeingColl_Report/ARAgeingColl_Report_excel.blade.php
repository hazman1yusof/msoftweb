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
        <td style="font-weight:bold; text-align: left">Receipt No.</td>
        <td style="font-weight:bold; text-align: left">Date</td>
        <td style="font-weight:bold; text-align: left">Unit</td>
    </tr>      

    @php
        $grandtotal = 0;
    @endphp

    @foreach ($array_report_1 as $ar_1)
        <tr>
            <td style="text-align: left">{{$ar_1->debtorcode}}</td>
            <td style="text-align: left">{{$ar_1->name}}</td>
            <td style="text-align: left">{{$ar_1->punallocamt}}</td>

        @php($total = $ar_1->groupOne + $ar_1->groupTwo + $ar_1->groupThree + $ar_1->groupFour + $ar_1->groupFive + $ar_1->groupSix)
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
    @endforeach

    <tr></tr>

    <tr>
        <td ></td>
        <td style="font-weight:bold; text-align: left">GRAND TOTAL</td>
        @foreach ($grouping as $key => $obj)
            <td></td>
        @endforeach
        <td>{{$grandtotal}}</td>
    </tr>
    <tr></tr>
</table>