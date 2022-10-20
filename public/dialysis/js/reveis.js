$(document).ready(function() {

    document.getElementById('fromdate').valueAsDate = new Date();
    document.getElementById('todate').valueAsDate = new Date();

    var opts = {
        angle: -0.2, // The span of the gauge arc
        lineWidth: 0.2, // The line thickness
        radiusScale: 1, // Relative radius
        pointer: {
        length: 0.47, // // Relative to gauge radius
        strokeWidth: 0.026, // The thickness
        color: '#000000' // Fill color
        },
        limitMax: false,     // If false, max value increases automatically if value > maxValue
        limitMin: false,     // If true, the min value of the gauge will be fixed
        colorStart: '#6FADCF',   // Colors
        colorStop: '#8FC0DA',    // just experiment with them
        strokeColor: '#E0E0E0',  // to see which ones work best for you
        generateGradient: true,
        highDpiSupport: true,     // High resolution support
        staticZones: [
           {strokeStyle: "#F03E3E", min: 0, max: 50}, // Red from 100 to 130
           {strokeStyle: "#FFDD00", min: 50, max: 100}, // Yellow
           {strokeStyle: "#30B32D", min: 100, max: 200}, // Green
           {strokeStyle: "#FFDD00", min: 200, max: 250}, // Yellow
           {strokeStyle: "#F03E3E", min: 250, max: 300}  // Red
        ],
    };

    var target = document.getElementById('canvas-preview'); // your canvas element
    var gauge = new Gauge(target).setOptions(opts); // create sexy gauge!
    gauge.maxValue = 300; // set max gauge value
    gauge.setMinValue(0);  // Prefer setter over gauge.minValue = 0
    gauge.set(0); // set actual value

    var dis_rev = localforage.createInstance({name: "db_dis_rev"});
    var reg_rev = localforage.createInstance({name: "db_reg_rev"});

    dis_rev.removeItem(moment().format('YYYY-MM')); // remove current month
    reg_rev.removeItem(moment().format('YYYY-MM')); // remove current month

    var db_loaded = [];

    $('input[name="type"]').change(function(){
        // getDB($(this).val());
    });

    $('#fetch').click(function(){
        getDB($('input[type="radio"][name="type"]:checked').val());
    });

    // getDB('dis');

    $('#canvas-preview').dblclick(function(){
        deleteDB();
    });

    var x=0;
    function getDB(type){
        gauge.set(0);
        let db = (type == 'reg')?reg_rev:dis_rev;
        let dbname = makedbname();
        var dbtosearch = [];
        var dbnottosearch = [];
        x=0;
        
        dbname.forEach(function(e_db,i_db){
            searchandset(db,e_db,dbtosearch,function(e_db,obj,dbtosearch){ //execute after promise search
                if(obj == null){
                    dbtosearch.push(e_db);
                }else{
                    dbnottosearch.push(e_db);
                }
                x = x + 1;
                if(x>=dbname.length){
                    fetchjson(db,dbtosearch,dbnottosearch);
                }
            });
        });
    }

    function makedbname(){
        var datefrom = moment($('#fromdate').val());
        var dateto = moment($('#todate').val());

        var dbname = [];
        let cont = true;
        while(cont){
            dbname.push(datefrom.format('YYYY-MM'));
            datefrom = datefrom.add(1, 'month');
            if(datefrom.diff(dateto) > 0){
                cont = false;
            }
        }
        return dbname;
    }

    function fetchjson(db,dbtosearch,dbnottosearch){
        var type = $('input[type="radio"][name="type"]:checked').val();

        gauge.set(100);
        // $.getJSON("pivot_get?action=get_json_pivot_rev&datetype="+type+"&dbtosearch="+dbtosearch, function(mps) {
        //     loadDB(db,mps.data,dbtosearch,dbnottosearch);
        // });

        $.get( "pivot_get?action=get_json_pivot_rev&datetype="+type+"&dbtosearch="+dbtosearch, function() {
          
        },"json").done(function(mps){
            loadDB(db,mps.data,dbtosearch,dbnottosearch);
        });
    }

    var derivers = $.pivotUtilities.derivers;
    var renderers = $.extend($.pivotUtilities.renderers,$.pivotUtilities.plotly_renderers);

    function pivot(){
        var mps = db_loaded;
        var type = $('input[type="radio"][name="type"]:checked').val();

        mps.filter(function(e,i){
            if(e.datetype == type){
                return true;
            }
        });
        
        $("#output").pivotUI(mps, {
            renderers: renderers,
            unusedAttrsVertical: false,
            cols: ["year","month"], rows: ["units","groupdesc"],
            rendererName: "Table",
            aggregatorName: "Sum",
            vals: ["amount"],
            rowOrder: "key_a_to_z", colOrder: "key_a_to_z",
            exclusions: {
                "units": [
                  "DENTAL","FKL","IMP","KH","POLIKLINIK"
                ]
              },
            inclusions: {
                "units": [
                  "ABC",
                ]
              },
        }, true);
    }

    var y=0;
    function loadDB(db,mps,dbtosearch,dbnottosearch){
        let dbname = makedbname();
        var all_data = mps;
        dbtosearch.forEach(function(e,i){
            searchandstore(db,e,mps); // simpan yg dah search
        });
        y=0;
        dbnottosearch.forEach(function(e,i){ //e tu nama db, e.g(2021-3)
            searchandget(db,e,function(e,value){//value tu isi db, e.g({...})
                all_data = all_data.concat(value);
                y = y + 1;
                if(y>=dbnottosearch.length){
                    db_loaded = all_data;
                    pivot();
                    gauge.set(300);
                }
            }); // amik yg dah ada
        });
        
        if(dbnottosearch.length == 0){
            db_loaded = all_data;
            pivot();
            gauge.set(300);
        }
    }

    function str_pad(str, pad_length, pad_string, pad_type){
        var len = pad_length - str.length;
        if(len < 0) return str;
        for(var i = 0; i < len; i++){
            if(pad_type == "STR_PAD_LEFT"){
                str = pad_string + str;
            }else{
                str += pad_string;
            }
        }

        return str;

    }

    function searchandstore(db,e_db,value){
        let db_obj = e_db.split("-");
        let year = 'Y'+db_obj[0];
        let month = 'M'+db_obj[1];
        value = value.filter(function(e,i){ //search by month
            if(e.month == month && e.year == year){
                return true;
            }
            return false;
        });

        db.setItem(e_db,value);
    }

    function searchandset(db,e_db,dbtosearch,func){
        db.getItem(e_db).then(function(value) {
            func(e_db,value,dbtosearch);
        });
    }

    function searchandget(db,e,func){
        db.getItem(e).then(function(value) {
            func(e,value);
        });
    }

    function deleteDB(){
        var r = confirm("Delete local database?");
        if (r == true) {
            localforage.dropInstance({name: "db_dis_rev"});
            localforage.dropInstance({name: "db_reg_rev"});
            location.reload();
        }
    }

} );