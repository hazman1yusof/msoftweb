<table>
    <tr>
    </tr>
        <tr>
            <td style="font-weight:bold">CODE</td>
            <td style="font-weight:bold">NAME</td>
            @foreach ($years as $year)
                <td style="font-weight:bold; text-align: right">{{$years}}</td>
            @endforeach
        </tr>
        <tr></tr>
        @foreach ($array_report as $obj)
            <tr>
                <td style="text-align: left">{{strtoupper($obj->suppcode)}}</td>               
                <td style="text-align: left">{!!strtoupper($obj->supplier_name)!!}</td>               
                <td data-format="0.00" style="text-align: right">{{number_format($obj->years_bal_all, 2, '.', ',')}}</td>
            </tr>
        @endforeach
        <div style="page-break-after:always">
</table>