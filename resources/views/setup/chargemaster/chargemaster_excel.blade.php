<table>
    <tr>
    <tr>
        <td style="font-weight:bold; text-align: left">Description</td>
        <td style="font-weight:bold; text-align: left">UOM</td>
        <td style="font-weight:bold; text-align: left">Packing</td>
        <td style="font-weight:bold; text-align: left">Code</td>
        <td style="font-weight:bold; text-align: left">I/P Price</td>
        <td style="font-weight:bold; text-align: left">O/P Price</td>
        <td style="font-weight:bold; text-align: left">Other Price</td>
        <td style="font-weight:bold; text-align: left">A/Cost Price</td>
        <td style="font-weight:bold; text-align: left">Current Price</td>
    </tr>      

    @foreach ($chggroup as $obj_cg)
    <tr>
        <td style="font-weight:bold; text-align: left">{{$obj_cg->chggroup}} {{$obj_cg->cg_desc}}</td>
    </tr>

        @foreach ($chgtype as $obj_ct)
            @if($obj_ct->chggroup == $obj_cg->chggroup)
                <tr>
                    <td style="font-weight:bold; text-align: left">{{$obj_ct->chgtype}} {{$obj_ct->ct_desc}}</td>
                </tr>

                @foreach ($array_report as $obj_ar)
                    @if($obj_ar->chgtype == $obj_ct->chgtype)
                        <tr>
                            <td>{{$obj_ar->description}}</td>
                            <td>{{$obj_ar->uom_cm}}</td>
                            <td>{{$obj_ar->packqty}}</td>
                            <td>{{$obj_ar->chgcode}}</td>
                            <td>{{number_format($obj_ar->amt1, 2, '.', ',')}}</td>
                            <td>{{number_format($obj_ar->amt2, 2, '.', ',')}}</td>
                            <td>{{number_format($obj_ar->amt3, 2, '.', ',')}}</td>
                            <td>{{number_format($obj_ar->costprice, 2, '.', ',')}}</td>
                            <td></td>
                        </tr>
                    @endif
                @endforeach
            @endif
        @endforeach
    @endforeach
</table>