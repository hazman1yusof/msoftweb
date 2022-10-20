$(document).ready(function() {

    $('.ui.checkbox').checkbox();

    // $('#reg').css('color','#db2828');
    // $('#reg').css('font-weight','bold');
    // $('input[name="type"]').change(function(){
    //     var checked=$(this).is(":checked");
    //     if(checked){
    //         $('#reg').css('color','#000000');
    //         $('#reg').css('font-weight','normal');
    //         $('#dis').css('color','#2185d0');
    //         $('#dis').css('font-weight','bold');
    //         fetchjson('reg');
    //     }else{
    //         $('#dis').css('color','#000000');
    //         $('#dis').css('font-weight','normal');
    //         $('#reg').css('color','#db2828');
    //         $('#reg').css('font-weight','bold');
    //         fetchjson('dis');
    //     }
    // })

    $('input[name="type"]').change(function(){
        fetchjson($(this).val())
    });

    var pivot_obj = null;
    var derivers = $.pivotUtilities.derivers;
    var renderers = $.extend($.pivotUtilities.renderers,$.pivotUtilities.plotly_renderers);

    fetchjson('reg');
    function fetchjson(type){
        if(pivot_obj == null){
            $.getJSON("pivot_get?type="+type, function(mps) {
                pivot_obj = mps;
                pivot(mps,type);
            });
        }else{
            pivot(pivot_obj,type);
        }
    }

    function pivot(mps,type){
        // let mps_ = mps.filter(function(e,i){
        //     if(e.type == type){
        //         return true;
        //     }
        // });
        let mps_ = mps;
        
        $("#output").pivotUI(mps_, {
            renderers: renderers,
            unusedAttrsVertical: false,
            cols: ["month"], rows: ["religion"],
            rendererName: "Table",
            rowOrder: "value_z_to_a", colOrder: "value_z_to_a"
        });
    }
} );