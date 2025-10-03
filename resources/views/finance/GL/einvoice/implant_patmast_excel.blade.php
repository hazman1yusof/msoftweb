<table>
    <tr></tr>
    <tr>
        <td style="font-weight:bold; text-align: left">MRN</td>
        <td style="font-weight:bold; text-align: left">Name</td>
        <td style="font-weight:bold; text-align: left">Newic</td>
        <td style="font-weight:bold; text-align: left">Address1</td>
        <td style="font-weight:bold; text-align: left">Address2</td>
        <td style="font-weight:bold; text-align: left">Address3</td>
        <td style="font-weight:bold; text-align: left">Postcode</td>
        <td style="font-weight:bold; text-align: left">Telephone</td>
    </tr>
        @foreach ($pat_mast as $pm)
            <tr>
                <td>{{$pm->NewMrn}}</td>
                <td>{{$pm->Name}}</td>
                <td data-format="0">{{$pm->Newic}}</td>
                <td>{{$pm->Address1}}</td>
                <td>{{$pm->Address2}}</td>
                <td>{{$pm->Address3}}</td>
                <td>{{$pm->Postcode}}</td>
                <td>{{$pm->telhp}}</td>
            </tr>
        @endforeach
</table>