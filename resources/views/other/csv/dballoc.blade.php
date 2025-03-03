<table>
    <tr>
        <td>compcode</td>
        <td>source</td>
        <td>trantype</td>
        <td>auditno</td>
        <td>lineno_</td>
        <td>docsource</td>
        <td>doctrantype</td>
        <td>docauditno</td>
        <td>refsource</td>
        <td>reftrantype</td>
        <td>refauditno</td>
        <td>refamount</td>
        <td>reflineno</td>
        <td>recptno</td>
        <td>mrn</td>
        <td>episno</td>
        <td>allocsts</td>
        <td>amount</td>
        <td>outamount</td>
        <td>tillcode</td>
        <td>debtortype</td>
        <td>debtorcode</td>
        <td>payercode</td>
        <td>paymode</td>
        <td>allocdate</td>
        <td>remark</td>
        <td>upddate</td>
        <td>upduser</td>
        <td>balance</td>
        <td>adddate</td>
        <td>adduser</td>
        <td>recstatus</td>
        <td>idno</td>
    </tr>
    @foreach ($table as $obj)
        <tr>
            <td>{{$obj->compcode}}</td>
            <td>{{$obj->source}}</td>
            <td>{{$obj->trantype}}</td>
            <td>{{$obj->auditno}}</td>
            <td>{{$obj->lineno_}}</td>
            <td>{{$obj->docsource}}</td>
            <td>{{$obj->doctrantype}}</td>
            <td>{{$obj->docauditno}}</td>
            <td>{{$obj->refsource}}</td>
            <td>{{$obj->reftrantype}}</td>
            <td>{{$obj->refauditno}}</td>
            <td>{{$obj->refamount}}</td>
            <td>{{$obj->reflineno}}</td>
            <td>{{$obj->recptno}}</td>
            <td>{{$obj->mrn}}</td>
            <td>{{$obj->episno}}</td>
            <td>{{$obj->allocsts}}</td>
            <td>{{$obj->amount}}</td>
            <td>{{$obj->outamount}}</td>
            <td>{{$obj->tillcode}}</td>
            <td>{{$obj->debtortype}}</td>
            <td>{{$obj->debtorcode}}</td>
            <td>{{$obj->payercode}}</td>
            <td>{{$obj->paymode}}</td>
            <td>{{Carbon\Carbon::parse($obj->allocdate)->format('d/m/Y')}}</td>
            <td>{{$obj->remark}}</td>
            <td>{{Carbon\Carbon::parse($obj->upddate)->format('d/m/Y')}}</td>
            <td>{{$obj->upduser}}</td>
            <td>{{$obj->balance}}</td>
            <td>{{Carbon\Carbon::parse($obj->adddate)->format('d/m/Y')}}</td>
            <td>{{$obj->adduser}}</td>
            <td>{{$obj->recstatus}}</td>
            <td>{{$obj->idno}}</td>
        </tr>
    @endforeach
</table>