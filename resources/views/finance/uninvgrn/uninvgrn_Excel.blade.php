<table>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align: center" colspan="4">{{$compname}}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align: center" colspan="4">UnInvoice GRN REPORT</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align: center" colspan="4">Date From {{$fromdate}} to {{$todate}}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr></tr>
    <tr>
        <td style="font-weight: bold">Rec No.</td>
        <td style="font-weight: bold">GRN No.</td>
        <td style="font-weight: bold">Date</td>
        <td style="font-weight: bold">PO No.</td>
        <td style="font-weight: bold">DO No.</td>
        <td style="font-weight: bold">Delivery Dept</td>
        <td style="font-weight: bold">GRN Amount</td>
        <td style="font-weight: bold">GRT Amount</td>
        <td style="font-weight: bold">Invoice Amount</td>
        <td style="font-weight: bold">Outstanding Amount</td>
        <td style="font-weight: bold">Supplier Code</td>
        <td style="font-weight: bold">Supplier Name</td>
        <td style="font-weight: bold">Invoice No.</td>
        <td style="font-weight: bold">Invoice Date</td>
    </tr>
    @foreach ($table as $obj)
        <tr>
            <td>{{$obj->recno}}</td>
            <td>{{$obj->grnno}}</td>
            <td>{{$obj->trandate}}</td>
            <td>{{$obj->pono}}</td>
            <td>{{$obj->delordno}}</td>
            <td>{{$obj->deldept}}</td>
            <td>{{$obj->grn_amt}}</td>
            <td>{{$obj->grt_amt}}</td>
            <td>{{$obj->invoice_amt}}</td>
            <td>{{$obj->total_bal}}</td>
            <td>{{$obj->suppcode}}</td>
            <td>{{$obj->suppname}}</td>
            <td>{{$obj->invoiceno}}</td>
            <td>{{$obj->inv_postdate}}</td>
        </tr>
    @endforeach
    <tr></tr>
</table>