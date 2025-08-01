<table>
    <tr>
        <td>compcode</td>
        <td>recno</td>
        <td>source</td>
        <td>reference</td>
        <td>txndept</td>
        <td>trantype</td>
        <td>docno</td>
        <td>srcdocno</td>
        <td>sndrcvtype</td>
        <td>sndrcv</td>
        <td>trandate</td>
        <td>datesupret</td>
        <td>dateactret</td>
        <td>trantime</td>
        <td>ivreqno</td>
        <td>amount</td>
        <td>respersonid</td>
        <td>remarks</td>
        <td>recstatus</td>
        <td>adduser</td>
        <td>adddate</td>
        <td>upduser</td>
        <td>upddate</td>
        <td>updtime</td>
        <td>unit</td>
        <td>DateInt</td>
    </tr>
    @foreach ($table as $obj)
        <tr>
            <td>{{$obj->compcode}}</td>
            <td>{{$obj->recno}}</td>
            <td>{{$obj->source}}</td>
            <td>{{$obj->reference}}</td>
            <td>{{$obj->txndept}}</td>
            <td>{{$obj->trantype}}</td>
            <td>{{$obj->docno}}</td>
            <td>{{$obj->srcdocno}}</td>
            <td>{{$obj->sndrcvtype}}</td>
            <td>{{$obj->sndrcv}}</td>
            @if(empty($obj->trandate))
            <td></td>
            @else
            <td>{{Carbon\Carbon::parse($obj->trandate)->format('d/m/Y')}}</td>
            @endif
            @if(empty($obj->datesupret))
            <td></td>
            @else
            <td>{{Carbon\Carbon::parse($obj->datesupret)->format('d/m/Y')}}</td>
            @endif
            @if(empty($obj->dateactret))
            <td></td>
            @else
            <td>{{Carbon\Carbon::parse($obj->dateactret)->format('d/m/Y')}}</td>
            @endif
            <td>{{$obj->trantime}}</td>
            <td>{{$obj->ivreqno}}</td>
            <td>{{$obj->amount}}</td>
            <td>{{$obj->respersonid}}</td>
            <td>{{$obj->remarks}}</td>
            <td>POSTED</td>
            <td>{{$obj->adduser}}</td>
            @if(empty($obj->adddate))
            <td></td>
            @else
            <td>{{Carbon\Carbon::parse($obj->adddate)->format('d/m/Y')}}</td>
            @endif
            <td>{{$obj->upduser}}</td>
            @if(empty($obj->upddate))
            <td></td>
            @else
            <td>{{Carbon\Carbon::parse($obj->upddate)->format('d/m/Y')}}</td>
            @endif
            <td>{{$obj->updtime}}</td>
            <td>{{$obj->unit}}</td>
            <td></td>
        </tr>
    @endforeach
</table>