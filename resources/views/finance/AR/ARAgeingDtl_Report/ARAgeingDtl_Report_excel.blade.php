<table>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align: center" colspan="4">{{$comp_name}}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align: center" colspan="4">Ageing Details</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align: center" colspan="4">As at : {{$date_at}}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
    <tr>
        <td style="font-weight:bold; text-align: left">Code</td>
        <td style="font-weight:bold; text-align: left">Company</td>
        <td style="font-weight:bold; text-align: left">Document No.</td>
        <td style="font-weight:bold; text-align: left">Date</td>
        @foreach ($grouping as $key => $group)
            @if($key+1 < count($grouping))
            <td style="font-weight:bold; text-align: left">{{$group+1}}-{{$grouping[$key+1]}} Days</td>
            @else
            <td style="font-weight:bold; text-align: left">> {{$group}} Days</td>
            @endif
        @endforeach
        <td style="font-weight:bold; text-align: left">Total</td>
        <td style="font-weight:bold; text-align: left">Units</td>
    </tr>      

    @foreach ($debtortype as $obj_dt)
    <tr>
        <td style="font-weight:bold; text-align: left">{{$obj_dt->debtortycode}}</td>
        <td style="font-weight:bold; text-align: left">{{$obj_dt->description}}</td>
    </tr>

        @foreach ($debtorcode as $obj_dc)
            @if($obj_dc->debtortype == $obj_dt->debtortycode)
                <tr>
                    <td style="font-weight:bold; text-align: left">{{$obj_dc->debtorcode}}</td>
                    <td style="font-weight:bold; text-align: left">{{$obj_dc->name}}</td>
                </tr>

                @php($total = 0.00)
                @foreach ($array_report as $obj_ar)
                    @if($obj_ar->debtorcode == $obj_dc->debtorcode)
                        @php($total += $obj_ar->newamt)
                        <tr>
                            <td></td>
                            <td>{{$obj_ar->remark}}</td>
                            <td>{{$obj_ar->doc_no}}</td>
                            <td>{{$obj_ar->posteddate}}</td>

                            @foreach ($grouping as $key => $group)
                                @if($key == $obj_ar->group)
                                <td>{{$obj_ar->newamt}}</td>
                                @else
                                <td>{{0.00}}</td>
                                @endif
                            @endforeach

                            <td></td>
                            <td>{{$obj_ar->unit}}</td>
                        </tr>
                    @endif
                @endforeach
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>

                    @foreach ($grouping as $key => $group)
                        @if($key+1 == count($grouping))
                        <td style="font-weight:bold; text-align: left">Total</td>
                        @else
                        <td></td>
                        @endif
                    @endforeach

                    <td>{{$total}}</td>
                    <td></td>
                </tr>
            @endif
        @endforeach
    @endforeach
</table>