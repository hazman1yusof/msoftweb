<table>
    <tr>
        <td style="font-weight:bold;text-align: left">Debtor Code</td>
        <td style="font-weight:bold;text-align: left">Patient Name</td>
        <td style="font-weight:bold;text-align: left">Admit Date</td>
        <td style="font-weight:bold;text-align: left">Discharge Date</td>
        <td style="font-weight:bold;text-align: left">BillNo</td>
        <td style="font-weight:bold;text-align: left">Bill Date</td>
        <td style="font-weight:bold;text-align: right">Total Bill</td>
        <td style="font-weight:bold;text-align: right">Outstanding Bill</td>
        <td style="font-weight:bold;text-align: right">Hospital</td>
        <td style="font-weight:bold;text-align: right">Doctor</td>
        <td style="font-weight:bold;text-align: right">Tax</td> 
    </tr>
    @php($rowcount = 1)
    @foreach($dbacthdr1_unq as $main_obj)
        <tr>
            <td>{{$main_obj->payercode}}</td>
            <td>{{$main_obj->patient_name}}</td>
            <td>{{$main_obj->posteddate}}</td>
            <td>{{$main_obj->posteddate}}</td>
            <td>{{$main_obj->billno}}</td>
            <td>{{$main_obj->posteddate}}</td>
            <td>{{$main_obj->amount}}</td>
            <td>{{$main_obj->outamount}}</td>
            <td>{{$main_obj->toth}}</td>
            <td>{{$main_obj->totc}}</td>
            <td>{{$main_obj->tott}}</td>
        </tr>
        @php($rowcount++)
    @endforeach

    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td style="font-weight:bold">TOTAL</td>
        <td style="font-weight:bold">=SUM(G2:G{{$rowcount}})</td>
        <td style="font-weight:bold">=SUM(H2:H{{$rowcount}})</td>
        <td style="font-weight:bold">=SUM(I2:I{{$rowcount}})</td>
        <td style="font-weight:bold">=SUM(J2:J{{$rowcount}})</td>
        <td style="font-weight:bold">=SUM(K2:K{{$rowcount}})</td>
    </tr>
</table>