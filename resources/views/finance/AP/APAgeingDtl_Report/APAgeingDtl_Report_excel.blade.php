<table>
    <tr>
    </tr>
        <tr>
            <td style="font-weight:bold">CODE</td>
            <td style="font-weight:bold">COMPANY</td>
            <td style="font-weight:bold">DOCUMENT NO</td>
            <td style="font-weight:bold">DATE</td>
            <td style="font-weight:bold">1-30 DAYS</td>
            <td style="font-weight:bold">31-60 DAYS</td>
            <td style="font-weight:bold">61-90 DAYS</td>
            <td style="font-weight:bold">91-120 DAYS</td>
            <td style="font-weight:bold">>120 DAYS</td>
            <td style="font-weight:bold">TOTAL</td>
            <td style="font-weight:bold">UNITS</td>
        </tr>
        <tr></tr>
        @foreach ($supp_group as $suppgroup)
            <tr>
                <td style="font-weight:bold">{{strtoupper($suppgroup->suppgroup)}}</td>
                <td style="font-weight:bold">{{strtoupper($suppgroup->sg_desc)}}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>         
            </tr>

            @foreach ($supp_code as $suppcode)
                @if($suppcode->suppgroup == $suppgroup->suppgroup)
                    <tr>
                        <td>{{strtoupper($suppcode->suppcode)}}</td>
                        <td>{{strtoupper($suppcode->supplier_name)}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>         
                    </tr>

                    @php($tot = 0)
                    @foreach ($array_report as $obj) 
                        @if($obj->suppcode == $suppcode->suppcode)
                            <tr>
                                <td></td>                      
                                <td style="text-align: left">{{strtoupper($obj->remarks)}}</td>                    
                                <td>{{strtoupper($obj->trantype)}}/{{strtoupper($obj->docno)}}</td>
                                <td>{{\Carbon\Carbon::parse($obj->postdate)->format('d/m/Y')}}</td>
                                <td data-format="0.00" style="text-align: right">{{number_format($outamt, 2, '.', ',')}}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>{{$obj->unit}}</td>         
                            </tr>
                        @php($tot += $outamt)
                        @endif
                    @endforeach
                    <tr>
                        <td></td>                      
                        <td></td>                    
                        <td></td>
                        <td style="font-weight:bold">TOTAL</td>
                        <td data-format="0.00" style="text-align: right">{{number_format($tot, 2, '.', ',')}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>         
                    </tr>
                @endif
            @endforeach
        @endforeach
        <tr>
            <td></td>                      
            <td></td>                    
            <td></td>
            <td style="font-weight:bold">SUB TOTAL</td>
            <td data-format="0.00" style="text-align: right"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>         
        </tr>
</table>
