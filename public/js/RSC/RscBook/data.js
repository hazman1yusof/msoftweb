
$(document).ready(function() {
        var count=0;
        var liveDate=new Date();
        var dat = new Date();
        var d = dat.getDate();
        var m = dat.getMonth();
        var y = dat.getFullYear();
        $.getJSON("<?php echo base_url(); ?>names",function(data){
            var select = $('#AgentName'); //combo/select/dropdown list
            if (select.prop) {
                var options = select.prop('options');
            }
            else {
                var options = select.attr('options');
            }
            $('option', select).remove();
            $.each(data, function(key, value){
                options[options.length] = new Option(value['name'], value['id']);
            });
        });
        var calendar = $('#calendar').fullCalendar({
           header: {
               left: 'prev,next today',
               center: 'title',
               right: 'month,agendaWeek,agendaDay'
           },
           selectable: true,
           selectHelper: true,
           select: function(start, end, allDay) {
               $("#popup").show();
               $("#eventName").focus();
               $("#submit").click(function(){
                   var title=$("#eventName").val();
                   if (title) {
                       calendar.fullCalendar('renderEvent',{
                           title: title,
                           start: start,
                           end: end,
                           allDay: false
                           },
                       true // make the event "stick"
                       );
                       var dataString={};
                       dataString['eventName']=title;
                       dataString['startTime']=$.fullCalendar.formatDate(start, "yyyy-MM-dd H:mm:ss");
                       dataString['endTime']=$.fullCalendar.formatDate(end, "yyyy-MM-dd H:mm:ss");
                       $.ajax({
                           type : 'POST',
                           dataType : 'json',
                           url : '<?php echo base_url(); ?>data/insert',
                           data: dataString,
                           success: function(data) {
                               alert("Data Insert SuccessFully");
                               if(data.success)
                                   alert("Data Insert SuccessFully");
                           }
                       });
                   }
               });
           },
           editable: true,
           viewDisplay: function(view) {
               if(view.name=="month" && count==0){
                   var a=$(".fc-day-number").prepend("<img src='/assets/images/add.jpg' width='20' height='20'style='margin-right:80px;' name='date'>");                                    
                   count++;
               }
           },
           eventSources: [
               {
                   url: '<?php echo base_url(); ?>data/read',
                   type: 'POST',
                   id:id,
                   title:title,
                   start:new Date(start),
                   end:new Date(end),// use the `url` property
                   color: '#65a9d7',    // an option!
                   textColor: '#3c3d3d'  // an option!
                }                    
            ],
            eventClick : function (start,end){
                $("#popup").open();
                $("#submit").click(function(){
                    var title=$("#eventName").val();
                    if (title) {
                        calendar.fullCalendar('renderEvent',{
                            title: title,
                            start: start,
                            end: end,
                            allDay: false
                            },
                            true // make the event "stick"
                        );
                        var dataString={};
                        dataString['eventName']=title;
                        dataString['startTime']=$.fullCalendar.formatDate(start, "yyyy-MM-dd H:mm:ss");
                        dataString['endTime']=$.fullCalendar.formatDate(end, "yyyy-MM-dd H:mm:ss");
                        $.ajax({
                            type : 'POST',
                            dataType : 'json',
                            url : '<?php echo base_url(); ?>data/update',
                            data: dataString,
                            success: function(data) {
                                alert("Data Insert SuccessFully");
                                if(data.success)
                                    alert("Data Insert SuccessFully");
                            }
                        });
                    }
                    calendar.fullCalendar('unselect');
                    calendar.fullCalendar('refetchEvents');
                    $("#popup").hide();
                });
            }

        });
    });