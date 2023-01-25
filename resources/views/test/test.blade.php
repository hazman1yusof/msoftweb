@extends('layouts.main')

@section('title', 'test fix column')

@section('js')

    <script type="text/javascript">
        obj= {
          "template": { "name" : "myinvoice" },
          "data" : {
    "to":"Hazman Yusof",
    "from":"Danial",
    "price":"34.44"
}
        };

        $.post( 'http://localhost:5488/api/report',obj, function( data ) {
            
        }).fail(function(data) {
        }).done(function(data){
            var blob = new Blob([data], {type: 'application/pdf'});
            var blobURL = URL.createObjectURL(blob);
            window.open(blobURL); 
        });

        

    </script>
@endsection

@section('body')
<h1>TEST</h1>
@endsection