<table>
    @foreach ($dballoc as $obj)
        <tr>
            <td>{{$obj->doctrantype}}</td>
            <td>{{\Carbon\Carbon::parse($obj->doc_entrydate)->format('d/m/Y')}}</td>
            <td>{{\Carbon\Carbon::parse($obj->allocdate)->format('d/m/Y')}}</td>
            <td>{{$obj->da_recptno}}</td>
            <td>{{$obj->reference}}</td>
            <td data-format="0.00">{{number_format($obj->dh_amount,2)}}</td>
            <td>{{$obj->refauditno}}</td>
            <td>{{\Carbon\Carbon::parse($obj->ref_entrydate)->format('d/m/Y')}}</td>
            <td data-format="0.00">{{number_format($obj->allocamount,2)}}</td>
            <td>{{$obj->debtorcode}}</td>
            <td>{{$obj->debtorname}}</td>
        </tr>
    @endforeach
</table>
