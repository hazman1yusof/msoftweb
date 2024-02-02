<table>
    <tr>
    </tr>
    <tr>
        <td style="font-weight:bold;text-align: left">CODE</td>
        <td style="font-weight:bold;text-align: left">NAME</td>
        <td style="font-weight:bold;text-align: right">BALANCE</td>
    </tr>
    <tr></tr>
    @foreach($debtormast as $index => $debtor)
    <tr>
        <td style="text-align: left">{{$debtor->debtorcode}}</td>
        <td>{!!$debtor->name!!}</td>
        <td></td>
    </tr>
    @endforeach
    <div style="page-break-after:always" />
</table>