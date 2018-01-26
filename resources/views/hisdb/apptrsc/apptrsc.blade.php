<?php 
	include_once('../../../../header.php'); 
?>

<body style="display:none">

<input id="Class2" name="Class" type="hidden" value="<?php echo $_GET['TYPE'];?>">

	<div class='row'>
		<form id="searchForm" class="formclass" style='width:99%'>
			<fieldset>
				<div class='col-md-12' style='padding: 0 0 5px 0;'>
					<div class='form-group'>
						<div class='col-md-2'>
							<label class="control-label" for="Scol">Search By : </label>
							<input class="form-control input-sm" id="Scol" type="text" readonly>
						</div>
						<div class='col-md-3'>
							<label class="control-label" for="resourcecode">&nbsp</label>
							<div class='input-group'>
								<input class="form-control input-sm" id="resourcecode" name="resourcecode" type="text" maxlength="12" data-validation="required">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>								
							</div>
							<span class='help-block'></span>
						</div>
					</div>
				</div>
			 </fieldset> 
		</form>

		<!-- Dialog -->
		<div id="dialogForm" title="Add Form">
			<div class="panel panel-info">
				<div class="panel-heading">Appointment Header</div>
					<form action="addEvent.php" method="post" class="form-horizontal" style="width: 99%" id="addForm" >
						<div class="panel-body" style="position: relative;" >
							<div class="form-group">
								<label for="Title" class="col-md-2 control-label">Title</label>
								<div class="col-md-3">
									<div class="input-group">
										<input type="text" class="form-control input-sm" id="title" name="title" placeholder="Title of appointment" data-validation="required" >
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="Doctor" class="col-md-2 control-label">Doctor</label>
								<div class="col-md-3">
									<div class="input-group">
										<input type="text" class="form-control input-sm" placeholder="Doctor" id="doctor" name="doctor" maxlength="12" data-validation="required">	
										<a class="input-group-addon btn btn-primary"><span class='fa fa-ellipsis-h'></span></a>
									</div>
									<span class='help-block'></span>
								</div>								
							</div>
							<div class="form-group">
								<label for="title" class="col-md-2 control-label">MRN</label>
								<div class="col-md-3">
									<div class="input-group">
										<input type="text" class="form-control input-sm" placeholder="MRN No" id="mrn" name="mrn" maxlength="12" data-validation="required">
										<a class="input-group-addon btn btn-primary"><span class='fa fa-ellipsis-h'></span></a>
									</div>
									<span class='help-block'></span>
								</div>
								<div class="col-md-4">
									<input type="text" class="form-control input-sm" placeholder="" id="patname" name="patname">
								</div>
							</div>
							<div class="form-group">
								<label for="start" class="col-md-2 control-label">Start Date</label>
								<div class="col-md-2">
									<input type="text" class="form-control input-sm" placeholder="Start Date" id="start" name="start" data-validation="required">	
								</div>
								<label for="end" class="col-md-2 control-label">End Date</label>
								<div class="col-md-2">
									<input type="text" class="form-control input-sm" placeholder="End Date" id="end" name="end" data-validation="required">	
								</div>
								<!-- <label for="color" class="col-md-2 control-label">Color</label>
								<div class="col-md-2">
									<select name="color" id="color" class="form-control input-sm">
										<option value="">Choose</option>
										<option style="color:#0071c5;" value="#0071c5">&#9724; Dark blue</option>
										<option style="color:#40E0D0;" value="#40E0D0">&#9724; Turquoise</option>
										<option style="color:#008000;" value="#008000">&#9724; Green</option>						  
										<option style="color:#FFD700;" value="#FFD700">&#9724; Yellow</option>
										<option style="color:#FF8C00;" value="#FF8C00">&#9724; Orange</option>
										<option style="color:#FF0000;" value="#FF0000">&#9724; Red</option>
										<option style="color:#000;" value="#000">&#9724; Black</option>
									</select>
								</div> -->
							</div>
							<div class="form-group">
								<label for="telno" class="col-md-2 control-label">Tel No</label>
								<div class="col-md-2">
									<input type="text" class="form-control input-sm" placeholder="Telephone No" id="telno" name="telno" data-validation="required">	
								</div>
								<label for="status" class="col-md-2 control-label">Status</label>
								<div class="col-md-2">
									<select name="status" id="status" class="form-control input-sm" data-validation="required">
										<option value="attend">Attend</option>	
										<option value="notattend">Not Attend</option>
									</select>	
								</div>
							</div>
							<div class="form-group">
								<label for="telhp" class="col-md-2 control-label">Tel Hp</label>
								<div class="col-md-2">
									<input type="text" class="form-control input-sm" placeholder="Telephone Hp" id="telhp" name="telhp" data-validation="required">	
								</div>
								<label for="Doctor" class="col-md-2 control-label">Case</label>
								<div class="col-md-2">
									<div class="input-group">
										<input type="text" class="form-control input-sm" placeholder="Case" id="case" name="case" maxlength="12" data-validation="required">	
										<a class="input-group-addon btn btn-primary"><span class='fa fa-ellipsis-h'></span></a>
									</div>
									<span class='help-block'></span>
								</div>							
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label" for="remarks">Remarks</label>   
									<div class="col-md-6">
										<textarea rows="5" id='remarks' name='remarks' class="form-control input-sm" ></textarea>
									</div>
							</div>
						</div>
						<div class="panel-footer">
							<button type="button" class="btn btn-primary" id="submit">Save changes</button>
						</div>
					</form>
			</div>
		</div>

		<!-- Modal -->
		<!-- <div class="modal fade" id="ModalAdd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		  <div class="modal-dialog" role="document">
			<div class="modal-content">
			<form id="addForm" class="form-horizontal" style="width:99%" method="POST" action="addEvent.php">
			
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Add Event</h4>
			  </div>
			  <div class="modal-body">
				  <div class="form-group">
					<label for="title" class="col-sm-2 control-label">Title</label>
					<div class="col-sm-10">
					  <input type="text" name="title" class="form-control" id="title" placeholder="Title">
					</div>
				  </div>
					<div class="form-group">
					<label for="doctor" class="col-sm-2 control-label">Doctor</label>
					<div class="col-sm-10">
					  <input type="text" name="doctor" class="form-control" id="doctor" placeholder="Doctor" readonly>
					</div>
				  </div>
				  <div class="form-group">
					<label for="color" class="col-sm-2 control-label">Color</label>
					<div class="col-sm-10">
					  <select name="color" class="form-control" id="color">
						  <option value="">Choose</option>
						  <option style="color:#0071c5;" value="#0071c5">&#9724; Dark blue</option>
						  <option style="color:#40E0D0;" value="#40E0D0">&#9724; Turquoise</option>
						  <option style="color:#008000;" value="#008000">&#9724; Green</option>						  
						  <option style="color:#FFD700;" value="#FFD700">&#9724; Yellow</option>
						  <option style="color:#FF8C00;" value="#FF8C00">&#9724; Orange</option>
						  <option style="color:#FF0000;" value="#FF0000">&#9724; Red</option>
						  <option style="color:#000;" value="#000">&#9724; Black</option>
						</select>
					</div>
				  </div>
				  <div class="form-group">
					<label for="start" class="col-sm-2 control-label">Start date</label>
					<div class="col-sm-10">
					  <input type="text" name="start" class="form-control" id="start" >
					</div>
				  </div>
				  <div class="form-group">
					<label for="end" class="col-sm-2 control-label">End date</label>
					<div class="col-sm-10">
					  <input type="text" name="end" class="form-control" id="end" >
					</div>
				  </div>
				
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Save changes</button>
			  </div>
			</form>
			</div>
		  </div>
		</div> -->

		<!-- Modal -->
		<div class="modal fade" id="ModalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		  <div class="modal-dialog" role="document">
			<div class="modal-content">
			<form class="form-horizontal" method="POST" action="editEventTitle.php">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Edit Event</h4>
			  </div>
			  <div class="modal-body">
				  <div class="form-group">
					<label for="title" class="col-sm-2 control-label">Title</label>
					<div class="col-sm-10">
					  <input type="text" name="title" class="form-control" id="title" placeholder="Title">
					</div>
				  </div>
				  <div class="form-group">
					<label for="color" class="col-sm-2 control-label">Color</label>
					<div class="col-sm-10">
					  <select name="color" class="form-control" id="color">
						  <option value="">Choose</option>
						  <option style="color:#0071c5;" value="#0071c5">&#9724; Dark blue</option>
						  <option style="color:#40E0D0;" value="#40E0D0">&#9724; Turquoise</option>
						  <option style="color:#008000;" value="#008000">&#9724; Green</option>						  
						  <option style="color:#FFD700;" value="#FFD700">&#9724; Yellow</option>
						  <option style="color:#FF8C00;" value="#FF8C00">&#9724; Orange</option>
						  <option style="color:#FF0000;" value="#FF0000">&#9724; Red</option>
						  <option style="color:#000;" value="#000">&#9724; Black</option>
						</select>
					</div>
				  </div>
				    <div class="form-group"> 
						<div class="col-sm-offset-2 col-sm-10">
						  <div class="checkbox">
							<label class="text-danger"><input type="checkbox"  name="delete"> Delete event</label>
						  </div>
						</div>
					</div>
				  
				  <input type="hidden" name="id" class="form-control" id="id">
				
				
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Save changes</button>
			  </div>
			</form>
			</div>
		  </div>
		</div>


		<!-- JQGRID -->
		<div class="panel panel-default">
		    		<div class="panel-body">
		    			<div class='col-md-12' style="padding:0 0 15px 0">
            				<div id="calendar"></div>
        				</div>
		    		</div>
		</div>

		<div id="panel" class="panel panel-default">
		    		<div class="panel-body">
		    			<div class='col-md-12' style="padding:0 0 15px 0">
            				<table id="jqGrid" class="table table-striped"></table>
            					<div id="jqGridPager"></div>
        				</div>
		    		</div>
		</div>

 		<div id="panel2" class="panel panel-default">
		    <div class="panel-body">
		    	<div class='col-md-8'>
            		<table id="detail" class="table table-striped"></table>
            			<div id="jqGridPager2"></div>
        		</div>

        		<div class='col-md-4'>
            		<table id="itemExpiry" class="table table-striped"></table>
            			<div id="jqGridPager3"></div>
        		</div>
		    </div>
		</div>

    </div>

	<?php 
		include_once('../../../../footer.php');
	?>
	<script src="appointment2.js"></script>
	<script src="../../../../assets/js/utility.js"></script>
	<script src="../../../../assets/js/dialogHandler.js"></script>

	<!-- Calendar Script -->
	<script>

	$(document).ready(function() {
		
		$("body").show();
		var d = new Date();

		$("#dialogForm").dialog({
			autoOpen: false,
			width: 9.5 / 10 * $(window).width(),
			modal: true
		});
		
		$('#calendar').fullCalendar({
			header: {
				left: 'prev,next today myCustomButton',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			customButtons: {
        myCustomButton: {
            text: 'Add',
            click: function() {
                // var temp = $('#resourcecode').val();
				
								// $('#ModalAdd #doctor').val(temp);
								
								// $('#ModalAdd #start').datetimepicker({
									// 	format: 'YYYY-MM-DD HH:mm:ss'
									// }).val(moment(start).format('YYYY-MM-DD HH:mm:ss'));
									
									// $('#ModalAdd #end').datetimepicker({
										// 	format: 'YYYY-MM-DD HH:mm:ss'
										// }).val(moment(end).format('YYYY-MM-DD HH:mm:ss'));
										
										// $('#ModalAdd #start').val(moment(start).format('YYYY-MM-DD HH:mm:ss'));
										// $('#ModalAdd #end').val(moment(end).format('YYYY-MM-DD HH:mm:ss'));

								 var temp = $('#resourcecode').val();
								
								 var start = $(".fc-myCustomButton-button").data( "start");
								 var end = $(".fc-myCustomButton-button").data( "end");

									$('#dialogForm #doctor').val(temp);
									
									$('#dialogForm #start').datetimepicker({
											format: 'YYYY-MM-DD HH:mm:ss',
											stepping: 15
										}).val(moment(start).format('YYYY-MM-DD HH:mm:ss'));
									
								$('#dialogForm #end').datetimepicker({
										format: 'YYYY-MM-DD HH:mm:ss',
										stepping: 15
									}).val(moment(end).format('YYYY-MM-DD HH:mm:ss'));
									
									$('#dialogForm #start').val(moment(start).format('YYYY-MM-DD HH:mm:ss'));
									$('#dialogForm #end').val(moment(end).format('YYYY-MM-DD HH:mm:ss'));
									
									$("#dialogForm").dialog("open");
									// $('#ModalAdd').modal('show');
            }
        }
    },
			// defaultDate: '2016-01-12',
			defaultDate: d,
			navLinks: true, // can click day/week names to navigate views
			editable: true,
			eventLimit: true, // allow "more" link when too many events
			selectable: true,
			selectHelper: true,
			select: function(start, end) {
				$('#calendar').fullCalendar('changeView', 'agendaDay', moment(start).format('YYYY-MM-DD'));
				$(".fc-myCustomButton-button").data( "start", start );
				$(".fc-myCustomButton-button").data( "end", end );
			},
			eventRender: function(event, element) {
				element.bind('dblclick', function() {
					$('#ModalEdit #id').val(event.id);
					$('#ModalEdit #title').val(event.title);
					$('#ModalEdit #color').val(event.color);
					$('#ModalEdit').modal('show');
				});
			},
			eventDrop: function(event, delta, revertFunc) { // si changement de position

				edit(event);

			},
			eventResize: function(event,dayDelta,minuteDelta,revertFunc) { // si changement de longueur

				edit(event);

			},
			events: {
				url:'getEvent.php',
				type:'POST',
				data:{
					drrsc:''
				}
			}
			
		});
		
		function edit(event){
			start = event.start.format('YYYY-MM-DD HH:mm:ss');
			if(event.end){
				end = event.end.format('YYYY-MM-DD HH:mm:ss');
			}else{
				end = start;
			}
			
			id =  event.id;
			
			Event = [];
			Event[0] = id;
			Event[1] = start;
			Event[2] = end;
			
			$.ajax({
			 url: 'editEventDate.php',
			 type: "POST",
			 data: {Event:Event},
			 success: function(rep) {
					if(rep == 'OK'){
						// alert('Saved');
					}else{
						alert('Could not be saved. try again.'); 
					}
				}
			});
		}
		
	});

	$('#submit').click(function(){
		$.post("addEvent.php", $("#addForm").serialize(), function (data) {
		}).fail(function (data) {
			//////////////////errorText(dialog,data.responseText);
		}).done(function (data) {
			$("#dialogForm").dialog('close');
			var events = {
							url: "getEvent.php",
							type: 'POST',
							data: {
								drrsc: $('#resourcecode').val()
							}
						}
				
			$('#calendar').fullCalendar( 'removeEventSource', events);
			$('#calendar').fullCalendar( 'addEventSource', events);         
			$('#calendar').fullCalendar( 'refetchEvents' );
		});
	});

</script>

</body>
</html>