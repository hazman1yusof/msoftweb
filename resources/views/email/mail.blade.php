<head>
<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 50%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
</style>
</head>

Please <strong>{{ $data->status }}</strong> Purchase Request of Deptcode and RequestNo

<table>
  <tr>
    <th>Deptcode</th>
    <th>RequestNo</th>
  </tr>
  <tr>
    <td>{{$data->deptcode}}</td>
    <td>{{$data->purreqno}}</td>
  </tr>
</table>