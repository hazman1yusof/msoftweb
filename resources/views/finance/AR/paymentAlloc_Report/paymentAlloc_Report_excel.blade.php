<table>
    <tr>
        <td style="font-weight:bold;text-align: left">TRX TYPE</td>
        <td style="font-weight:bold;text-align: right">RECEIPT DATE</td>
        <td style="font-weight:bold;text-align: right">ALLOCATION DATE</td>
        <td style="font-weight:bold;text-align: right">RECEIPT NO</td>
        <td style="font-weight:bold;text-align: right">PAYMENT DETAILS</td>
        <td style="font-weight:bold;text-align: right">RECEIPT AMT</td>
        <td style="font-weight:bold;text-align: right">BILL NO</td>
        <td style="font-weight:bold;text-align: right">BILL DATE</td>
        <td style="font-weight:bold;text-align: right">ALLOCATED AMT</td>
        <td style="font-weight:bold;text-align: right">DEBTOR CODE</td>
        <td style="font-weight:bold;text-align: right">NAME</td>
    </tr>
    @foreach ($dballoc as $obj)
        <tr>
            <td>{{$obj->doctrantype}}</td>
            <td>{{\Carbon\Carbon::parse($obj->doc_entrydate)->format('d/m/Y')}}</td>
            <td>{{\Carbon\Carbon::parse($obj->allocdate)->format('d/m/Y')}}</td>
            <td>{{$obj->da_recptno}}</td>
            <td>{{$obj->reference}}</td>
            <td data-format="0.00">{{number_format($obj->dh_amount,2)}}</td>
            <td>{{$obj->refauditno}}</td>
            <td>{{\Carbon\Carbon::parse($obj->ref_entrydate)->format('d/m/Y')}}</td>
            <td data-format="0.00">{{number_format($obj->allocamount,2)}}</td>
            <td>{{$obj->debtorcode}}</td>
            <td>{{$obj->debtorname}}</td>
        </tr>
    @endforeach
</table>
