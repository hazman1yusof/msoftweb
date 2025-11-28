<table>
    <tr>
        <td></td>
        <td></td>
        <td style="font-weight:bold;text-align: center" colspan="3">Sales By Category Summary</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td style="font-weight:bold;text-align: center" colspan="3">Department : {{$deptcode}}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td style="font-weight:bold;text-align: center" colspan="3">Date From {{Carbon\Carbon::parse($datefr)->format('d-m-Y')}} to {{Carbon\Carbon::parse($dateto)->format('d-m-Y')}}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr></tr>
    <tr>    
        <td style="font-weight:bold">CHARGE TYPE</td>
        <td style="font-weight:bold">DESCRIPTION</td>
        <td style="font-weight:bold; text-align: right">TOTAL QUANTITY</td>
        <td style="font-weight:bold; text-align: right">TOTAL AMOUNT</td>
        <td style="font-weight:bold; text-align: right">TOTAL COST</td>
        <td style="font-weight:bold; text-align: right">TOTAL TAX</td>
        <td style="font-weight:bold; text-align: right">TOTAL LINE</td>
    </tr>
    @php($qty_tot = 0)
    @php($amt_tot = 0)
    @php($cpr_tot = 0)
    @php($tax_tot = 0)
    @php($tot_tot = 0)
    @foreach($chgtype as $type)
        <tr>
            <td style="font-weight:bold">{{$type->chgtype}}</td>
            <td style="font-weight:bold">{{$type->ct_desc}}</td>
            <td>{{$type->qty}}</td>
            <td>{{$type->amt}}</td>
            <td>{{$type->cpr}}</td>
            <td>{{$type->tax}}</td>
            <td>{{$type->tot}}</td>
        </tr>
        @php($qty_tot += $type->qty)
        @php($amt_tot += $type->amt)
        @php($cpr_tot += $type->cpr)
        @php($tax_tot += $type->tax)
        @php($tot_tot += $type->tot)
    @endforeach
    <tr></tr>
    <tr> 
        <td></td>
        <td></td>
        <td data-format="0.00" style="text-align: right; font-weight:bold">{{$qty_tot}}</td>
        <td data-format="0.00" style="text-align: right; font-weight:bold">{{$amt_tot}}</td>
        <td data-format="0.00" style="text-align: right; font-weight:bold">{{$cpr_tot}}</td>
        <td data-format="0.00" style="text-align: right; font-weight:bold">{{$tax_tot}}</td>
        <td data-format="0.00" style="text-align: right; font-weight:bold">{{$tot_tot}}</td>
    </tr>
</table>

