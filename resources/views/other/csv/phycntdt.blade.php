<table>
    <tr>
        <td>compcode</td>
        <td>srcdept</td>
        <td>phycntdate</td>
        <td>phycnttime</td>
        <td>lineno_</td>
        <td>itemcode</td>
        <td>uomcode</td>
        <td>adduser</td>
        <td>adddate</td>
        <td>upduser</td>
        <td>upddate</td>
        <td>unitcost</td>
        <td>phyqty</td>
        <td>thyqty</td>
        <td>recno</td>
        <td>expdate</td>
        <td>updtime</td>
        <td>stktime</td>
        <td>frzdate</td>
        <td>frztime</td>
        <td>dspqty</td>
        <td>batchno</td>
        <td>remark</td>
    </tr>
    @foreach ($table as $obj)
        <tr>
            <td>{{$obj->compcode}}</td>
            <td>{{$obj->srcdept}}</td>
            <td>{{Carbon\Carbon::parse($obj->phycntdate)->format('d/m/Y')}}</td>
            <td>{{$obj->phycnttime}}</td>
            <td>{{$obj->lineno_}}</td>
            <td>{{$obj->itemcode}}</td>
            <td>{{$obj->uomcode}}</td>
            <td>{{$obj->adduser}}</td>
            <td>{{Carbon\Carbon::parse($obj->adddate)->format('d/m/Y')}}</td>
            <td>{{$obj->upduser}}</td>
            <td>{{Carbon\Carbon::parse($obj->upddate)->format('d/m/Y')}}</td>
            <td>{{$obj->unitcost}}</td>
            <td>{{$obj->phyqty}}</td>
            <td>{{$obj->thyqty}}</td>
            <td>{{$obj->recno}}</td>
            <td>{{Carbon\Carbon::parse($obj->expdate)->format('d/m/Y')}}</td>
            <td>{{$obj->updtime}}</td>
            <td>{{$obj->stktime}}</td>
            <td>{{Carbon\Carbon::parse($obj->frzdate)->format('d/m/Y')}}</td>
            <td>{{$obj->frztime}}</td>
            <td>{{$obj->dspqty}}</td>
            <td>{{$obj->batchno}}</td>
            <td>{{$obj->remark}}</td>
        </tr>
    @endforeach
</table>