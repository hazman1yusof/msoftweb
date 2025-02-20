<table>
    <tr>
        <td>compcode</td>
        <td>recno</td>
        <td>prdept</td>
        <td>trantype</td>
        <td>docno</td>
        <td>delordno</td>
        <td>invoiceno</td>
        <td>suppcode</td>
        <td>srcdocno</td>
        <td>deldept</td>
        <td>subamount</td>
        <td>amtdisc</td>
        <td>perdisc</td>
        <td>totamount</td>
        <td>deliverydate</td>
        <td>trandate</td>
        <td>trantime</td>
        <td>respersonid</td>
        <td>checkpersonid</td>
        <td>checkdate</td>
        <td>postedby</td>
        <td>recstatus</td>
        <td>remarks</td>
        <td>adddate</td>
        <td>adduser</td>
        <td>upddate</td>
        <td>reason</td>
        <td>rtnflg</td>
        <td>reqdept</td>
        <td>credcode</td>
        <td>impflg</td>
        <td>allocdate</td>
        <td>postdate</td>
        <td>unit</td>
        <td>DoType</td>
        <td>TaxClaim</td>
        <td>TaxAmt</td>
    </tr>
    @foreach ($table as $obj)
        <tr>
            <td>{{$obj->compcode}}</td>
            <td>{{$obj->recno}}</td>
            <td>{{$obj->prdept}}</td>
            <td>{{$obj->trantype}}</td>
            <td>{{$obj->docno}}</td>
            <td>{{$obj->delordno}}</td>
            <td>{{$obj->invoiceno}}</td>
            <td>{{$obj->suppcode}}</td>
            <td>{{$obj->srcdocno}}</td>
            <td>{{$obj->deldept}}</td>
            <td>{{$obj->subamount}}</td>
            <td>{{$obj->amtdisc}}</td>
            <td>{{$obj->perdisc}}</td>
            <td>{{$obj->totamount}}</td>
            <td>{{Carbon\Carbon::parse($obj->deliverydate)->format('d/m/Y')}}</td>
            <td>{{Carbon\Carbon::parse($obj->trandate)->format('d/m/Y')}}</td>
            <td>{{$obj->trantime}}</td>
            <td>{{$obj->respersonid}}</td>
            <td>{{$obj->checkpersonid}}</td>
            <td>{{Carbon\Carbon::parse($obj->checkdate)->format('d/m/Y')}}</td>
            <td>{{$obj->postedby}}</td>
            <td>{{$obj->recstatus}}</td>
            <td>{{$obj->remarks}}</td>
            <td>{{Carbon\Carbon::parse($obj->adddate)->format('d/m/Y')}}</td>
            <td>{{$obj->adduser}}</td>
            <td>{{Carbon\Carbon::parse($obj->upddate)->format('d/m/Y')}}</td>
            <td>{{$obj->reason}}</td>
            <td>{{$obj->rtnflg}}</td>
            <td>{{$obj->reqdept}}</td>
            <td>{{$obj->credcode}}</td>
            <td>{{$obj->impflg}}</td>
            <td>{{Carbon\Carbon::parse($obj->allocdate)->format('d/m/Y')}}</td>
            <td>{{Carbon\Carbon::parse($obj->postdate)->format('d/m/Y')}}</td>
            <td>{{$obj->unit}}</td>
            <td></td>
            <td></td>           
            <td></td>
        </tr>
    @endforeach
</table>