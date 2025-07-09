<table>
    <tr>
        <td></td>
        <td></td>
        <td colspan="3" style="font-weight: bold;text-align: center">{{$company->name}}</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td colspan="3" style="font-weight: bold;text-align: center">{{$product->itemcode}}</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td colspan="3" style="font-weight: bold;text-align: center">{{$product->description}}</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td colspan="3" style="font-weight: bold;text-align: center">{{$department->description}}</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td colspan="3" style="font-weight: bold;text-align: center">Date From {{$datefr}} Date To {{$dateto}}</td>
    </tr>
    <tr></tr>
    <tr>
        <td></td>
        <td></td>
        <td colspan="3" style="text-align: center">ITEM MOVEMENT</td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: left">Open Balance Quantity : {{$open_balqty}}</td>
        <td colspan="2" style="text-align: left">Open Balance Value : {{$open_balval}}</td>
    </tr>
    <tr></tr>
    <tr>
        <td style="font-weight: bold;text-align: left">DATE</td>
        <td style="font-weight: bold;text-align: left">TRANTYPE</td>
        <td style="font-weight: bold;text-align: left">DESCRIPTION</td>
        <td style="font-weight: bold;text-align: left">DEPT</td>
        <td style="font-weight: bold;text-align: right">QTY IN</td>
        <td style="font-weight: bold;text-align: right">QTY OUT</td>
        <td style="font-weight: bold;text-align: left">BALANCE QTY</td>
        <td style="font-weight: bold;text-align: left">AMOUNT</td>
        <td style="font-weight: bold;text-align: left">BALANCE AMOUNT</td>
        <td style="font-weight: bold;text-align: left">DOCUMENT</td>
        <td style="font-weight: bold;text-align: left">MRN</td>
    </tr>
    <tr></tr>
    @php($balqty = $open_balqty)
    @php($balval = $open_balval)

    @foreach($merged as $obj)
    <tr>
        <td>{{$obj->trandate}}</td>
        <td>{{$obj->trantype}}</td>
        <td>{{$obj->description}}</td>
        <td>{{$obj->deptcode}}</td>
        @if($obj->crdbfl == "In")
            @php($balqty = $balqty + $obj->txnqty)
            @php($balval = $balval + $obj->amount)

            <td style="text-align: right">{{$obj->txnqty}}</td>
            <td></td>
            <td>{{$balqty}}</td>
            <td>{{$obj->amount}}</td>
            <td>{{$balval}}</td>
        @else
            @php($balqty = $balqty - $obj->txnqty)
            @php($balval = $balval - $obj->amount)

            <td></td>
            <td style="text-align: right">{{$obj->txnqty}}</td>
            <td>{{$balqty}}</td>
            <td>{{$obj->amount}}</td>
            <td>{{$balval}}</td>
        @endif
        <td>{{$obj->recno}}</td>
        <td>{{$obj->mrn}}</td>
    </tr>

    @endforeach
    <tr></tr>
</table>