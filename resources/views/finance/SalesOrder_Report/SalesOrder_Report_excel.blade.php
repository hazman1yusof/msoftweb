<table>
    <tr>
        <td style="font-weight:bold;text-align: left">Invoice No</td>
        <td style="font-weight:bold;text-align: left">Date</td>
        <td style="font-weight:bold;text-align: left">Store Dept</td>
        <td style="font-weight:bold;text-align: left">Debtor Code</td>
        <td style="font-weight:bold;text-align: left">Debtor Name</td>
        <td style="font-weight:bold;text-align: left">Patient Name</td>
        <td style="font-weight:bold;text-align: right">Amount</td>
    </tr>
    @php($x = 2)
    @foreach ($dbacthdr as $obj)
        <tr>
            <td>{{str_pad($obj->invno, 7, "0", STR_PAD_LEFT)}}</td>
            <td>{{\Carbon\Carbon::parse($obj->posteddate)->format('d/m/Y')}}</td>
            <td>{{$obj->deptcode}}</td>
            <td style="text-align: left">{{$obj->dm_debtorcode}}</td>
            <td>{{$obj->debtorname}}</td>
            <td>{{$obj->pm_name}}</td>
            <!-- <td data-format="0.00" style="text-align: right">{{number_format($obj->amount, 2, '.', ',')}}</td> -->
            <td style="text-align: right">{{$obj->amount}}</td>
        </tr>
        @php($x++)
    @endforeach
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td style="font-weight:bold">Total Amount</td>
        <td style="text-align: right">=SUM(G2:G{{$x}})</td>
        <!-- <td data-format="0.00" style="text-align: right">{{number_format($totalAmount, 2, '.', ',')}}</td> -->
    </tr>
    <tr></tr>
    <tr></tr>
</table>
