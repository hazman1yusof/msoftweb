<table>
    <tr>
    </tr>
    <tr>
        <td style="font-weight:bold;text-align: left">CODE</td>
        <td style="font-weight:bold;text-align: left">NAME</td>
        @foreach($years as $year)
        <td style="font-weight:bold;text-align: right">{{$year}}</td>
        @endforeach
    </tr>
    <tr></tr>
    @foreach($array_report as $array_key => $array_val)
    <tr>
        <td style="text-align: left">{{$array_val->debtorcode}}</td>
        <td>{!!$array_val->name!!}</td>
        @foreach($years as $year_key => $year_val)
            @foreach($years_bal_all as $bal_key => $bal_val)
                @foreach($bal_val as $bal_key2 => $bal_val2)
                    @if($array_key == $bal_key)
                        @if($year_key == $bal_key2)
                            <td data-format="0.00" style="text-align: right">{{number_format($bal_val2, 2, '.', ',')}}</td>
                        @endif
                    @endif
                @endforeach
            @endforeach
        @endforeach
    </tr>
    @endforeach
</table>