<table>
    <tr>
    </tr>
    <tr>
        <td style="font-weight:bold;text-align: left">CODE</td>
        <td style="font-weight:bold;text-align: left">COMPANY</td>
        <td style="font-weight:bold;text-align: left">DOCUMENT NO</td>
        <td style="font-weight:bold;text-align: right">DATE</td>
        <td style="font-weight:bold;text-align: right">1-30 DAYS</td>
        <td style="font-weight:bold;text-align: right">31-60 DAYS</td>
        <td style="font-weight:bold;text-align: right">61-90 DAYS</td>
        <td style="font-weight:bold;text-align: right">91-120 DAYS</td>
        <td style="font-weight:bold;text-align: right">> 120 DAYS</td>
        <td style="font-weight:bold;text-align: right">TOTAL</td>
        <td style="font-weight:bold;text-align: left">UNIT</td>
    </tr>
    <tr></tr>
    @foreach($debtortype as $db_type)
        <tr>
            <td style="font-weight:bold;text-align: left">{{strtoupper($db_type->debtortycode)}}</td>
            <td colspan="3" style="font-weight:bold;text-align: left">{{strtoupper($db_type->description)}}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @foreach($debtorcode as $db_code)
            @if($db_code->debtortype == $db_type->debtortycode)
                <tr>
                    <td style="text-align: left">{{strtoupper($db_code->debtorcode)}}</td>
                    <td colspan="3" style="text-align: left">{{strtoupper($db_code->name)}}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @foreach($array_report as $obj)
                    @if($obj->debtorcode == $db_code->debtorcode)
                    <tr>
                        <td></td>
                        <td style="text-align: left">{{$obj->remark}}</td>
                        <td style="text-align: left">{{$obj->doc_no}}</td>
                        <td style="text-align: right">{{\Carbon\Carbon::parse($obj->posteddate)->format('d/m/Y')}}</td>
                        <td data-format="0.00" style="text-align: right"> </td>
                        <td data-format="0.00" style="text-align: right">{{$obj->newamt}}</td>
                        <td data-format="0.00" style="text-align: right"> </td>
                        <td data-format="0.00" style="text-align: right"> </td>
                        <td data-format="0.00" style="text-align: right"> </td>
                        <td data-format="0.00" style="text-align: right"> </td>
                        <td style="text-align: left">{{$obj->unit}}</td>
                    </tr>
                    @endif
                @endforeach
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td style="font-weight:bold">TOTAL</td>
                    <td data-format="0.00" style="text-align: right; font-weight:bold"> </td>
                    <td data-format="0.00" style="text-align: right; font-weight:bold"> </td>
                    <td data-format="0.00" style="text-align: right; font-weight:bold"> </td>
                    <td data-format="0.00" style="text-align: right; font-weight:bold"> </td>
                    <td data-format="0.00" style="text-align: right; font-weight:bold"> </td>
                    <td data-format="0.00" style="text-align: right; font-weight:bold"> </td>
                    <td></td>
                </tr>
            @endif
        @endforeach
    @endforeach
</table>