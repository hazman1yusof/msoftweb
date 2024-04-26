<table>
    <tr></tr>
    <tr>
        <td style="font-weight: bold">USERNAME</td>
        <td style="font-weight: bold">NAME</td>
        <td style="font-weight: bold">GROUP</td>
        <td style="font-weight: bold">DEPARTMENT</td>
        <td style="font-weight: bold">CASHIER</td>
        <td style="font-weight: bold">BILLING</td>
        <td style="font-weight: bold">NURSE</td>
        <td style="font-weight: bold">DOCTOR</td>
        <td style="font-weight: bold">REGISTER</td>
        <td style="font-weight: bold">PRICE VIEW</td>
    </tr>
    <tr></tr>
    @foreach ($users as $obj)
    <tr>
        <td>{{$obj->username}}</td>
        <td>{{$obj->name}}</td>
        <td>{{$obj->groupid}}</td>
        <td>{{$obj->dept}}</td>
        @if($obj->cashier == '1')
            <td style="text-align: center">&#10003;</td>
        @else
            <td></td>
        @endif
        @if($obj->billing == '1')
            <td style="text-align: center">&#10003;</td>
        @else
            <td></td>
        @endif
        @if($obj->nurse == '1')
            <td style="text-align: center">&#10003;</td>
        @else
            <td></td>
        @endif
        @if($obj->doctor == '1')
            <td style="text-align: center">&#10003;</td>
        @else
            <td></td>
        @endif
        @if($obj->register == '1')
            <td style="text-align: center">&#10003;</td>
        @else
            <td></td>
        @endif
        @if($obj->priceview == '1')
            <td style="text-align: center">&#10003;</td>
        @else
            <td></td>
        @endif
    </tr>
    @endforeach
</table>