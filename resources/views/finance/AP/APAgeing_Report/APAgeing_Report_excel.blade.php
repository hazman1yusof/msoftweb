<table>
    <tr>
    </tr>
        <tr>
            <td style="font-weight:bold">CODE</td>
            <td style="font-weight:bold">NAME</td>
            @foreach ($years as $year)
                <td style="font-weight:bold; text-align: right">{{$year}}</td>
            @endforeach
        </tr>
        <tr></tr>
        @foreach($array_report as $key => $array_report1)
            <tr>
                <td style="text-align: left">{{strtoupper($array_report1->suppcode)}}</td>               
                <td style="text-align: left">{{strtoupper($array_report1->supplier_name)}}</td> 
                @foreach($years as $years_key => $yearsval)
                    @foreach($years_bal_all as $bal_key => $bal_all)
                        @if($key == $bal_key)
                            @foreach($bal_all as $key2 => $bal_all2)
                                @if($years_key == $key2)
                                    <td data-format="0.00" style="text-align: right">{{number_format($bal_all2, 2, '.', ',')}}</td>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                @endforeach
            </tr>
        @endforeach
        <div style="page-break-after:always">
</table>