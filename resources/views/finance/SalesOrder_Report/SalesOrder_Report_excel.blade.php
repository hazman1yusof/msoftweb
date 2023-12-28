<table>
    <tr>
        <td style="font-weight:bold;text-align: left">Invoice No</td>
        <td style="font-weight:bold;text-align: left">Date</td>
        <td style="font-weight:bold;text-align: left">Store Dept</td>
        <td style="font-weight:bold;text-align: left">Debtor Code</td>
        <td style="font-weight:bold;text-align: left">Debtor Name</td>
        <td style="font-weight:bold;text-align: right">Amount</td>
    </tr>
    @foreach ($dbacthdr as $obj)
        <tr>
            <td>{{str_pad($obj->invno, 7, "0", STR_PAD_LEFT)}}</td>
            <td>{{\Carbon\Carbon::parse($obj->entrydate)->format('d/m/Y')}}</td>
            <td>{{$obj->deptcode}}</td>
            <td>{{$obj->dm_debtorcode}}</td>
            <td>{{$obj->debtorname}}</td>
            <td data-format="0.00" style="text-align: right">{{number_format($obj->amount, 2, '.', ',')}}</td>
        </tr>
    @endforeach
</table>
