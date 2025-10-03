<!DOCTYPE html>
<html>
<head>
<title>E-Invoice</title>

</head>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/numeral@2.0.6/numeral.min.js"></script>

<script>
  

var invo = "{{$invno}}"
$(document).ready(function () {
  var url = 'http://175.143.1.33:8080/einvoice/einvoice_show?invno='+invo+'&compcode=medicare';
  console.log(url);

  $('#pdfiframe').attr('src',url);
});

</script>

<body style="margin: 0px;">

<iframe id="pdfiframe" width="100%" height="100%" src="" frameborder="0" style="width: 99vw;height: 99vh;"></iframe>

</body>
</html>