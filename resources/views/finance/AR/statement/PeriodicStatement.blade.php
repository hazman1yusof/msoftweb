<table>
    <tr>
        <td></td>
        <td></td>
        <td colspan="3" style="font-weight: bold;text-align: center">{{$company->name}}</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td colspan="3" style="font-weight: bold;text-align: center">{{$company->address1}}</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td colspan="3" style="font-weight: bold;text-align: center">{{$company->address2}}</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td colspan="3" style="font-weight: bold;text-align: center">{{$company->address3}}</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td colspan="3" style="font-weight: bold;text-align: center">{{$company->address4}}</td>
    </tr>
    <tr></tr>
    <tr>
        <td></td>
        <td></td>
        <td colspan="3" style="font-weight: bold;text-align: center">PERIODIC STATEMENT</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td colspan="3" style="font-weight: bold;text-align: center">FROM {{$monthFrom}} TO {{$monthTo}}</td>
    </tr>
    <tr></tr>
    <tr></tr>
    <tr>
        <td colspan="4">{{$supp[0]->Name}} - ({{$supp[0]->suppcode}})</td>
    </tr>
    <tr>
        <td colspan="4">{{$supp[0]->addr1}}</td>
    </tr>
    <tr>
        <td colspan="4">{{$supp[0]->addr2}}</td>
    </tr>
    <tr>
        <td colspan="4">{{$supp[0]->addr3}}</td>
        <td colspan="3">DATE PRINTED: {{$datenow}} </td>
    </tr>
    <tr></tr>
    <tr>
        <td style="font-weight: bold;text-align: left">DOC DATE</td>
        <td style="font-weight: bold;text-align: left">AUDIT NO</td>
        <td style="font-weight: bold;text-align: left">DOCUMENT</td>
        <td style="font-weight: bold;text-align: left">REMARK</td>
        <td style="font-weight: bold;text-align: right">BALANCE AMOUNT</td>
        <td style="font-weight: bold;text-align: right">TOTAL</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align: right; font-weight: bold;">OPEN BALANCE</td>
        <td style="text-align: right; font-weight: bold">{{$openbalance}}</td>
        <td style="text-align: right; font-weight: bold">{{$openbalance}}</td>
    </tr>

    @php($totalAmount = $openbalance)
    @foreach($suppcode as $index => $supp)
        @foreach($array_report as $db_obj)
            @if($db_obj->suppcode == $supp->suppcode)
                @php($totalAmount += $db_obj->newamt)
                <tr>
                    <td>{{\Carbon\Carbon::parse($db_obj->postdate)->format('d/m/Y')}}</td>
                    <td data-format="@">{{$db_obj->source}}-{{$db_obj->trantype}}-{{str_pad($db_obj->auditno, 7, "0", STR_PAD_LEFT)}}</td>
                    <td data-format="@">{{$db_obj->document}}</td>
                    <td data-format="@">{{$db_obj->remarks}}</td>
                    <td style="text-align: right">{{$db_obj->newamt}}</td>
                    <td style="text-align: right">{{$totalAmount}}</td>
                </tr>
            @endif
        @endforeach

    @endforeach
    
    
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align: right; font-weight: bold;">TOTAL AMOUNT</td>
        <td></td>
        <td style="text-align: right; font-weight: bold;">{{$totalAmount}}</td>
    </tr>

    <tr></tr>
    <div style="page-break-after: always" />

    <tr></tr>
    <tr>
        <td></td>
        <td>Note:-</td>
    </tr>
    <tr>
        <td></td>
        <td colspan="4">    If you do not agree with the above balance please inform us in writing within 14 days.</td>
    </tr>
    <tr>
        <td></td>
        <td colspan="4">    Any payment received after the close of the month will appear in next month's statement.</td>
    </tr>
    <tr>
        <td></td>
        <td colspan="4">    Cheque payment is valid only when cleared by our bank.</td>
    </tr>
</table>