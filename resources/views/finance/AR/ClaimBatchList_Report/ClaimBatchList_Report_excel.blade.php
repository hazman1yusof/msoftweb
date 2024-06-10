<table>
    <tr>
        <td colspan="2">{{\Carbon\Carbon::parse($date1)->format('d/m/Y')}}</td>
    </tr>
    <tr></tr>
    <tr>
        <td colspan="3">{{$debtormast->address1}}</td>
    </tr>
    @if(!empty($debtormast->address2))
        <tr>
            <td colspan="3">{{$debtormast->address2}}</td>
        </tr>
    @endif
    @if(!empty($debtormast->address3))
        <tr>
            <td colspan="3">{{$debtormast->address3}}</td>
        </tr>
    @endif
    @if(!empty($debtormast->address4))
        <tr>
            <td colspan="3">{{$debtormast->address4}}</td>
        </tr>
    @endif
    <tr></tr>
    <tr>
        <td colspan="3">Attention: {{$debtormast->name}}</td>
    </tr>
    <tr></tr>
    <tr>
        <td colspan="4">{{$title}}</td>
    </tr>
    <tr></tr>
    <tr>
        <td colspan="8">{{nl2br($content)}}</td>
    </tr>
    <tr></tr>
    <tr>
        <td colspan="2">{{$officer}}</td>
    </tr>
    <tr></tr>
    <tr>
        <td colspan="2">{{$designation}}</td>
    </tr>
    <tr></tr>
</table>
