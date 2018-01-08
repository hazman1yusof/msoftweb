<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>Untitled 1</title>
<link href="../../../../assets/plugins/form-validator/theme-default.css" rel="stylesheet" />
<link href="../../../../assets/plugins/jquery-ui-1.11.4.custom/jquery-ui.css" rel="stylesheet">
<link href="../../../../assets/plugins/font-awesome-4.4.0/css/font-awesome.min.css" rel="stylesheet">
<link href="../../../../assets/plugins/ionicons-2.0.1/css/ionicons.min.css" rel="stylesheet">
<link href="../../../../assets/plugins/AccordionMenu/dist/metisMenu.min.css" rel="stylesheet">
<link href="../../../../assets/plugins/bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet">
<link href="../../../../assets/plugins/jasny-bootstrap/css/jasny-bootstrap.min.css" rel="stylesheet">
<link href="../../../../assets/plugins/css/trirand/ui.jqgrid-bootstrap.css" rel="stylesheet" />
<link href="../../../../assets/plugins/searchCSS/stylesSearch.css" rel="stylesheet">
<link href="../../../../assets/plugins/fullcalendar-2.6.0/fullcalendar.css" rel="stylesheet" />
<link href="../../../../assets/plugins/fullcalendar-2.6.0/fullcalendar.print.css" media="print" rel="stylesheet" />


<style>
table {
        border     : 1px solid gray;
    width      : 50%;
    text-align : center;
}
 
table#sourcetable tbody tr {
    background-color : #ffccff;
}
 
table#sourcetable tbody  tr {
    cursor : pointer;
}
</style>
</head>

<body>

<h3>The source table</h3>
 
<table id="sourcetable">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Url</th>
            <th>Country</th>
            <th>Item</th>                       
        </tr>
    </thead>
 
    <tbody>
        <tr id="sour0">
            <td>1</td>
            <td>Name 1</td>
            <td>url 1</td>
            <td>Country 1</td>
            <td>Item 1</td>
        </tr>
        <tr id="sour1">
            <td>2</td>
            <td>Name 2</td>
            <td>url 2</td>
            <td>Country 2</td>
            <td>Item 2</td>
        </tr>
        <tr id="sour2">
            <td>3</td>
            <td>Name 3</td>
            <td>url 3</td>
            <td>Country 3</td>
            <td>Item 3</td>
        </tr>
        <tr id="sour3">
            <td>4</td>
            <td>Name 4</td>
            <td>url 4</td>
            <td>Country 4</td>
            <td>Item 4</td>
        </tr>
        <tr id="sour4">
            <td>5</td>
            <td>Name 5</td>
            <td>url 5</td>
            <td>Country 5</td>
            <td>Item 5</td>
        </tr>     
    </tbody>
</table>
 
 
<h3>The second table :</h3>
 
<form method="POST" action="">
<table id="destinationtable">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Url</th>
            <th>Country</th>
            <th>Item</th>                       
        </tr>
    </thead>
 
     
     
</table>
 
</form>   


<script src="../../../../assets/plugins/jquery.min.js" type="text/ecmascript"></script>
<script src="../../../../assets/plugins/trirand/i18n/grid.locale-en.js" type="text/ecmascript"></script>
<script src="../../../../assets/plugins/trirand/jquery.jqGrid.min.js" type="text/ecmascript"></script>
<script src="../../../../assets/plugins/bootstrap-3.3.5-dist/js/bootstrap.min.js" type="text/ecmascript"></script>
<script src="../../../../assets/plugins/jasny-bootstrap/js/jasny-bootstrap.min.js" type="text/ecmascript"></script>
<script src="../../../../assets/plugins/AccordionMenu/dist/metisMenu.min.js" type="text/ecmascript"></script>
<script src="../../../../assets/plugins/jquery-ui-1.11.4.custom/jquery-ui.min.js" type="text/ecmascript"></script>
<script src="../../../../assets/plugins/form-validator/jquery.form-validator.min.js" type="text/ecmascript"></script>
<script src="../../../../assets/plugins/jquery.dialogextend.js" type="text/ecmascript"></script>
<!-- JS Implementing Plugins -->
<script src="../../../../assets/plugins/fullcalendar-2.6.0/lib/moment.min.js"></script>
<script src="../../../../assets/plugins/fullcalendar-2.6.0/fullcalendar.min.js"></script>
<!-- JS Customization -->
<script src="../../../../assets/js/doctor.js"></script>
<script src="../../../../assets/js/cmbautoselect.js"></script>
<script src="http://cdn.jsdelivr.net/qtip2/2.2.1/jquery.qtip.min.js"></script>

</body>
<script>

var addedrows = new Array();
 
$(document).ready(function() {
    $( "#sourcetable tbody tr" ).on( "click", function( event ) {
   
    var ok = 0;
    var theid = $( this ).attr('id').replace("sour","");    
 
    var newaddedrows = new Array();
     
    for (index = 0; index < addedrows.length; ++index) {
 
        // if already selected then remove
        if (addedrows[index] == theid) {
                
            $( this ).css( "background-color", "#ffccff" );
             
            // remove from second table :
            var tr = $( "#dest" + theid );
            tr.css("background-color","#FF3700");
            tr.fadeOut(400, function(){
                tr.remove();
            });
             
            //addedrows.splice(theid, 1);   
             
            //the boolean
            ok = 1;
        } else {
         
            newaddedrows.push(addedrows[index]);
        } 
    }   
     
    addedrows = newaddedrows;
     
    // if no match found then add the row :
    if (!ok) {
        // retrieve the id of the element to match the id of the new row :
         
         
        addedrows.push( theid);
         
        $( this ).css( "background-color", "#cacaca" );
                 
        $('#destinationtable tr:last').after('<tr id="dest' + theid + '"><td>'
                                       + $(this).find("td").eq(0).html() + '</td><td>'
                                       + $(this).find("td").eq(1).html() + '</td><td>'
                                       + $(this).find("td").eq(2).html() + '</td><td>'
                                       + $(this).find("td").eq(3).html() + '</td><td>'
                                       + $(this).find("td").eq(4).html() + '</td></tr>');         
         
    }
 
     
    });
});     
</script>
</html>
