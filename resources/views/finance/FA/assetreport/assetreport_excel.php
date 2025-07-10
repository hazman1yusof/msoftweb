<table>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td colspan="3" style="font-weight:bold;text-align: center;">{{$company->name}}</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td colspan="3" style="font-weight:bold;text-align: center;">{{$title}}</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td colspan="3" style="font-weight:bold;text-align: center;">AS AT {{$datefr_desc}}</td>
    </tr>
    <tr>
    </tr>
    @foreach($assetcode as $assetcode_obj)
        <tr>
            <td style="font-weight:bold">Item Code</td>
            <td style="font-weight:bold">Description</td>
            <td style="font-weight:bold">Purchase Date</td>
            <td style="font-weight:bold">Original Cost</td>
            <td style="font-weight:bold">Reg Date</td>
            <td style="font-weight:bold">Dept</td>
            <td style="font-weight:bold">Opening Cost</td>
            <td style="font-weight:bold">Addition Cost</td>
            <td style="font-weight:bold">Disposal Cost</td>
            <td style="font-weight:bold">Closing Cost</td>
            <td style="font-weight:bold">Opening Depr</td>
            <td style="font-weight:bold">Addition Depr</td>
            <td style="font-weight:bold">Disposal Depr</td>
            <td style="font-weight:bold">Closing Depr</td>
            <td style="font-weight:bold">NBV</td>
        </tr>

        <tr></tr>
        <tr>
            <td colspan="3" style="font-weight:bold">Category :{{$assetcode_obj->assetcode}} {{$assetcode_obj->fc_desc}} - Rate: {{$assetcode_obj->rate}}</td>
        </tr>
        @foreach($faregister as $obj)
            @if($obj->assetcode == $assetcode_obj->assetcode)
                $php($closecost = $obj->origcost)
                <tr></tr>
                <tr>
                    <td>{{$obj->itemcode}}</td>
                    <td>{{$obj->description}}</td>
                    <td>{{$obj->purdate}}</td>
                    <td>{{$obj->purprice}}</td>
                    <td>{{$obj->startdepdate}}</td>

                    @if($obj->startdepdate->lt($fdoydate))
                    <td>{{$obj->origcost}}</td>
                    <td>0.00</td>
                    @else
                    <td>0.00</td>
                    <td>{{$obj->origcost}}</td>
                    @endif

                    <td>{{$obj->dispcost}}</td>
                    <td>{{$obj->closecost}}<td/>

                    <td>{{$obj->opendepr}}</td>
                    <td>{{$obj->adddepr}}</td>
                    <td>{{$obj->dispdepr}}</td>
                    <td>{{$obj->closedepr}}</td>
                    <td>{{$obj->nbvamt}}</td>
                </tr>
            @endif
        @endforeach
</table>