<table>
    <tr>
        <td colspan="2">{{\Carbon\Carbon::parse($date1)->format('dS F Y')}}</td>
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
    <tr></tr>
    <tr>
        <td colspan="4" style="font-weight: bold">{{$title}}</td>
    </tr>
    <tr></tr>
    @foreach($content_array as $content)
        <tr>
            <td colspan="8">{{$content}}</td>
        </tr>
    @endforeach
    <tr></tr>
    <tr></tr>
    <tr>
        <td colspan="2" style="font-weight: bold">{{$sign_off}}</td>
    </tr>
    <tr></tr>
    <tr>
        <td colspan="2" style="font-weight: bold">{{$officer}}</td>
    </tr>
    <tr></tr>
    <tr>
        <td colspan="2" style="font-weight: bold">{{$designation}}</td>
    </tr>
    <tr></tr>
</table>
