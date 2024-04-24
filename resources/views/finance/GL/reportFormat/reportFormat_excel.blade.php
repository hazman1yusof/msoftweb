<table>
    <tr></tr>
    <tr>
        <td style="font-weight: bold" colspan="2">Report Name</td>
        <td style="font-weight: bold" colspan="3">: {{$glrpthdr->rptname}}</td>
    </tr>
    <tr>
        <td style="font-weight: bold" colspan="2">Description</td>
        <td style="font-weight: bold" colspan="3">: {{$glrpthdr->description}}</td>
    </tr>
    <tr>
        <td style="font-weight: bold" colspan="2">Category</td>
        <td style="font-weight: bold" colspan="3">: {{$glrpthdr->rpttype}}</td>
    </tr>
    <tr></tr>
    <tr>
        <td style="font-weight: bold; text-align: right">NO.</td>
        <td style="font-weight: bold">PRINT FLAG</td>
        <td style="font-weight: bold">ROW DEF</td>
        <td style="font-weight: bold">CODE</td>
        <td style="font-weight: bold; text-align: right">NOTE</td>
        <td style="font-weight: bold">DESCRIPTION</td>
        <td style="font-weight: bold; text-align: right">FORMULA</td>
        <td style="font-weight: bold">COST CODE FROM</td>
        <td style="font-weight: bold">COST CODE TO</td>
        <td style="font-weight: bold">REVERSE SIGN</td>
    </tr>
    <tr></tr>
    @foreach ($glrptfmt as $obj)
    <tr>
        <td style="text-align: right">{{$obj->lineno_}}</td>
        @if($obj->printflag == 'Y')
            <td>YES</td>
        @else
            <td>NO</td>
        @endif
        @if($obj->rowdef == 'H')
            <td>Header</td>
        @elseif($obj->rowdef == 'D')
            <td>Detail</td>
        @elseif($obj->rowdef == 'S')
            <td>Spacing</td>
        @else
            <td>Total</td>
        @endif
        <td>{{$obj->code}}</td>
        <td style="text-align: right">{{$obj->note}}</td>
        <td>{{$obj->description}}</td>
        <td style="text-align: right">{{$obj->formula}}</td>
        <td>{{$obj->costcodefr}}</td>
        <td>{{$obj->costcodeto}}</td>
        <td>{{$obj->revsign}}</td>
    </tr>
    @endforeach
</table>