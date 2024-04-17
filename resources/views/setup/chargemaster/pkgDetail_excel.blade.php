<table>
    <tr>
    </tr>
        <tr>
            <td style="font-weight:bold">{{$chgmast->chgcode}}</td>
            <td style="font-weight:bold">{{$chgmast->description}}</td> 
            <td></td> 
        </tr>
        <tr></tr>
        <tr>
            <td style="font-weight:bold">CHARGE CODE</td>
            <td style="font-weight:bold">DESCRIPTION</td>
            <td style="font-weight:bold; text-align: right">QTY</td>

        </tr>
        <tr></tr>
        @foreach ($pkgdet as $obj)
            <tr>
                <td style="text-align: left">{{$obj->chgcode}}</td>
                <td>{{$obj->cc_desc}}</td>               
                <td data-format="0.00" style="text-align: right">{{$obj->quantity}}</td>              
            </tr>
        @endforeach
</table>