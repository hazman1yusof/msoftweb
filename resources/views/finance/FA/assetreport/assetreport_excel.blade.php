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

    @php($gtot_opencost = 0.00)
    @php($gtot_addicost = 0.00)
    @php($gtot_dispcost = 0.00)
    @php($gtot_closecost = 0.00)
    @php($gtot_opendepr = 0.00)
    @php($gtot_adddepr = 0.00)
    @php($gtot_dispdepr = 0.00)
    @php($gtot_closedepr = 0.00)
    @php($gtot_nbvamt = 0.00)

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

        @php($tot_opencost = 0.00)
        @php($tot_addicost = 0.00)
        @php($tot_dispcost = 0.00)
        @php($tot_closecost = 0.00)
        @php($tot_opendepr = 0.00)
        @php($tot_adddepr = 0.00)
        @php($tot_dispdepr = 0.00)
        @php($tot_closedepr = 0.00)
        @php($tot_nbvamt = 0.00)

        @foreach($faregister as $obj)
            @if($obj->skip == 0)
                @if($obj->assetcode == $assetcode_obj->assetcode)

                    @php($tot_dispcost=$tot_dispcost + $obj->dispcost)
                    @php($tot_closecost=$tot_closecost + $obj->closecost)
                    @php($tot_opendepr=$tot_opendepr + $obj->opendepr)
                    @php($tot_adddepr=$tot_adddepr + $obj->adddepr)
                    @php($tot_dispdepr=$tot_dispdepr + $obj->dispdepr)
                    @php($tot_closedepr=$tot_closedepr + $obj->closedepr)
                    @php($tot_nbvamt=$tot_nbvamt + $obj->nbvamt)

                    <tr>
                        <td>{{$obj->assetno}}</td>
                        <td>{{$obj->description}}</td>
                        <td>{{$obj->purdate}}</td>
                        <td>{{$obj->purprice}}</td>
                        <td>{{$obj->startdepdate}}</td>
                        <td>{{$obj->deptcode}}</td>

                        @if(\Carbon\Carbon::parse($obj->trandate)->lt(\Carbon\Carbon::parse($fdoydate)))
                            @php($tot_opencost=$tot_opencost + $obj->origcost)
                            <td>{{$obj->origcost}}</td>
                            <td>0.00</td>
                        @else
                            @php($tot_addicost=$tot_addicost + $obj->origcost)
                            <td>0.00</td>
                            <td>{{$obj->origcost}}</td>
                        @endif

                        <td>{{$obj->dispcost}}</td>
                        <td>{{$obj->closecost}}</td>
                        <td>{{$obj->opendepr}}</td>
                        <td>{{$obj->adddepr}}</td>
                        <td>{{$obj->dispdepr}}</td>
                        <td>{{$obj->closedepr}}</td>
                        <td>{{$obj->nbvamt}}</td>
                    </tr>
                @endif
            @endif
        @endforeach
        <tr>
            <td style="font-weight:bold"></td>
            <td style="font-weight:bold">SUB TOTAL {{$assetcode_obj->fc_desc}}</td>
            <td style="font-weight:bold"></td>
            <td style="font-weight:bold"></td>
            <td style="font-weight:bold"></td>
            <td style="font-weight:bold"></td>
            <td style="font-weight:bold">{{$tot_opencost}}</td>
            <td style="font-weight:bold">{{$tot_addicost}}</td>
            <td style="font-weight:bold">{{$tot_dispcost}}</td>
            <td style="font-weight:bold">{{$tot_closecost}}</td>
            <td style="font-weight:bold">{{$tot_opendepr}}</td>
            <td style="font-weight:bold">{{$tot_adddepr}}</td>
            <td style="font-weight:bold">{{$tot_dispdepr}}</td>
            <td style="font-weight:bold">{{$tot_closedepr}}</td>
            <td style="font-weight:bold">{{$tot_nbvamt}}</td>
        </tr>

        @php($gtot_opencost = $gtot_opencost + $tot_opencost)
        @php($gtot_addicost = $gtot_addicost + $tot_addicost)
        @php($gtot_dispcost = $gtot_dispcost + $tot_dispcost)
        @php($gtot_closecost = $gtot_closecost + $tot_closecost)
        @php($gtot_opendepr = $gtot_opendepr + $tot_opendepr)
        @php($gtot_adddepr = $gtot_adddepr + $tot_adddepr)
        @php($gtot_dispdepr = $gtot_dispdepr + $tot_dispdepr)
        @php($gtot_closedepr = $gtot_closedepr + $tot_closedepr)
        @php($gtot_nbvamt = $gtot_nbvamt + $tot_nbvamt)
    @endforeach
    <tr></tr>
    <tr>
        <td style="font-weight:bold"></td>
        <td style="font-weight:bold">GRAND TOTAL</td>
        <td style="font-weight:bold"></td>
        <td style="font-weight:bold"></td>
        <td style="font-weight:bold"></td>
        <td style="font-weight:bold"></td>
        <td style="font-weight:bold">{{$gtot_opencost}}</td>
        <td style="font-weight:bold">{{$gtot_addicost}}</td>
        <td style="font-weight:bold">{{$gtot_dispcost}}</td>
        <td style="font-weight:bold">{{$gtot_closecost}}</td>
        <td style="font-weight:bold">{{$gtot_opendepr}}</td>
        <td style="font-weight:bold">{{$gtot_adddepr}}</td>
        <td style="font-weight:bold">{{$gtot_dispdepr}}</td>
        <td style="font-weight:bold">{{$gtot_closedepr}}</td>
        <td style="font-weight:bold">{{$gtot_nbvamt}}</td>
    </tr>
</table>