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
        <td style="text-align: center" colspan="4">AP Ageing {{$type}}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align: center" colspan="4">As at : {{$date_at}}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr></tr>
    <tr>
        <td style="font-weight:bold; text-align: left">Code</td>
        <td style="font-weight:bold; text-align: left">Company</td>
        @foreach ($grouping as $key => $group)
            @if($key+1 < count($grouping))
            <td style="font-weight:bold; text-align: left">{{$group+1}}-{{$grouping[$key+1]}} Days</td>
            @else
            <td style="font-weight:bold; text-align: left">> {{$group}} Days</td>
            @endif
        @endforeach
        <td style="font-weight:bold; text-align: left">Total</td>
    </tr>      

    @php
        $grandtotal = 0;
    @endphp
    @foreach ($suppgroup as $obj_sg)
    <tr></tr>
    <tr>
        <td style="font-weight:bold; text-align: left">{{$obj_sg->suppgroup}}</td>
        <td style="font-weight:bold; text-align: left">{{$obj_sg->description}}</td>
    </tr>
        @php
            $grouping_type_total = [];
            foreach ($grouping as $key => $value) {
                $grouping_type_total[$key] = 0;
            }
        @endphp

        @foreach ($suppcode as $obj_sc)
            @if($obj_sc->suppgroup == $obj_sg->suppgroup)
                <tr>
                    <td style="font-weight:bold; text-align: left">{{$obj_sc->suppcode}}</td>
                    <td style="font-weight:bold; text-align: left">{{$obj_sc->name}}</td>

                @php
                    $grouping_total = [];

                    foreach ($grouping as $key => $value) {
                        $grouping_total[$key] = 0;
                    }

                    foreach ($array_report as $obj_ar) {
                        if($obj_ar->suppcode == $obj_sc->suppcode){
                            foreach ($grouping as $key => $value) {
                                if($obj_ar->group == $key){
                                    $grouping_total[$key] = $grouping_total[$key] + $obj_ar->newamt;
                                }
                            }
                        }
                    }


                    foreach ($grouping as $key => $value) {
                        $grouping_type_total[$key] = $grouping_type_total[$key] + $grouping_total[$key];
                    }

                @endphp

                @php($total = 0)
                @foreach ($grouping_total as $key => $obj)
                    @php($total = $total + $grouping_total[$key])
                    <td>{{$grouping_total[$key]}}</td>
                @endforeach
                <td>{{$total}}</td>
                </tr>

            @endif
        @endforeach
    <tr>
        <td ></td>
        <td style="font-weight:bold; text-align: left">SUB TOTAL</td>
        @php($type_total = 0)
        @foreach ($grouping_type_total as $key => $obj)
            @php($type_total = $type_total + $grouping_type_total[$key])
            <td>{{$grouping_type_total[$key]}}</td>
        @endforeach
        <td>{{$type_total}}</td>
        @php($grandtotal = $grandtotal + $type_total)
    </tr>
    <tr></tr>
    @endforeach
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