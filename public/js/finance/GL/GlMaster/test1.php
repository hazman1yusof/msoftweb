<script src="https://code.jquery.com/jquery-1.11.2.min.js"></script>
<script language="javascript" type="text/javascript">
$( document ).ready(function() {
  $.ajax({
    //url: "19_time.php",
    cache: false // Ensure each request will hit the server
  })
  .done(function( serverDate ) {
    $( "#time" ).html( serverDate ); // Update the HTML with the server response
  });
});
</script>

<?php
  echo date('Y-m-d H:i:s')
?>