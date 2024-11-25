@component('mail::message')
<h1>{{$subject}}</h1>
<table>
  <tr>
    <th align="left">Document Type</th>
    <td align="left">: {{$type}}</td>
  </tr>
  <tr>
    <th align="left">Record No.</th>
    <td align="left">: {{$recno}}</td>
  </tr>
  <tr>
    <th align="left">Current Status</th>
    <td align="left">: {{$curr_stats}}</td>
  </tr>
  <tr>
    <th align="left">Request Dept</th>
    <td align="left">: {{$reqdept}}</td>
  </tr>
  <tr>
    <th align="left">Prepared On</th>
    <td align="left">: {{$prepredon}}</td>
  </tr>
</table>
<hr></hr>
Please click <a href="http://175.143.1.33:8080/medicare/">here</a> to proceed
@endcomponent