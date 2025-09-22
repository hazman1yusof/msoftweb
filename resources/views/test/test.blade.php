<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>jQuery Button Disable Test</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
  <button id="btn">Click Me</button>

  <script>
    // --- Example 1: direct .click binding ---
    $("#btn").click(function () {
      $(this).attr('disabled',true);   // disable after first click
      console.log('clicked');
    });

    /*
    // --- Example 2: delegated binding (uncomment to test) ---
    $(document).on("click", "#btn", function (e) {
      if ($(this).prop("disabled")) {
        e.preventDefault();
        return; // do nothing if disabled
      }
      $(this).prop("disabled", true);
      alert("Delegated binding: clicked once!");
    });
    */
  </script>
</body>
</html>
