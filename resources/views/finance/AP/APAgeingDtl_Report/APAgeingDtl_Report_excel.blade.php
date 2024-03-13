<table>
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

    @foreach ($suppgroup as $obj_sg)
    <tr>
        <td style="font-weight:bold; text-align: left">{{$obj_sg->suppgroup}}</td>
        <td style="font-weight:bold; text-align: left">{{$obj_sg->description}}</td>
    </tr>

        @foreach ($suppcode as $obj_sc)
            @if($obj_sc->suppgroup == $obj_sg->suppgroup)
                <tr>
                    <td style="font-weight:bold; text-align: left">{{$obj_sc->suppcode}}</td>
                    <td style="font-weight:bold; text-align: left">{{$obj_sc->name}}</td>
                </tr>

                @php($total = 0.00)
                @foreach ($array_report as $obj_ar)
                    @if($obj_ar->suppcode == $obj_sc->suppcode)
                        @php($total += $obj_ar->newamt)
                        <tr>
                            <td></td>
                            <td>{{$obj_ar->remarks}}</td>
                            <td>{{$obj_ar->document}}</td>
                            <td>{{$obj_ar->postdate}}</td>

                            @foreach ($grouping as $key => $group)
                                @if($key == $obj_ar->group)
                                <td>{{number_format($obj_ar->newamt, 2, '.', ',')}}</td>
                                @else
                                <td>{{number_format(0.00, 2, '.', ',')}}</td>
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

                    <td>{{number_format($total, 2, '.', ',')}}</td>
                    <td></td>
                </tr>
            @endif
        @endforeach
    @endforeach
</table>