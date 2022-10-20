$(document).ready(function() {
	///////ni suma filter part////////
	if(location.search.substr(1) != "" && location.search.substr(1).search("page") != 0){
		$('#for_status, #for_priority, #for_category, #for_assignto, #for_reportby, #for_paginate').dropdown({});

		var params = parseParams(location.search.substr(1));
		(location.search.substr(1).search("status") != -1) ? $('#for_status').dropdown('set selected',params.status.split(",")): null;
		(location.search.substr(1).search("priority") != -1) ? $('#for_priority').dropdown('set selected',params.priority.split(",")): null;
		(location.search.substr(1).search("category") != -1) ? $('#for_category').dropdown('set selected',params.category.split(",")): null;
		(location.search.substr(1).search("assign_to") != -1) ? $('#for_assignto').dropdown('set selected',params.assign_to.split(",")): null;
		(location.search.substr(1).search("report_by") != -1) ? $('#for_reportby').dropdown('set selected',params.report_by.split(",")): null;

	}else{
		$('#for_status, #for_priority, #for_category, #for_assignto, #for_reportby, #for_paginate').dropdown({});
	}

	$("#reset").click(function(){
		$("#filterForm input").val(" ");
		$('#for_status, #for_priority, #for_category, #for_assignto, #for_reportby').dropdown('clear')
	});

	$('#toggleFilter').click(function(){
		$('#filterForm').transition('fade');
		set_filter_toggle_flag();
	});

	get_filter_toggle_flag();
    function get_filter_toggle_flag(){
    	let filter_flag = localStorage.getItem("my_filter_flag");
    	if(filter_flag == null){
    		set_filter_toggle_flag('off');
    	}else if(filter_flag == "off"){
			$('#filterForm').hide();
    	}else if(filter_flag == "on"){
			$('#filterForm').show();
    	}
    }

    function set_filter_toggle_flag(flag = 'toggle'){
    	if(flag == "toggle"){
    		let filter_flag_new = (localStorage.getItem("my_filter_flag") == "on")?"off":"on";
    		localStorage.setItem("my_filter_flag",filter_flag_new);
    	}else{
			localStorage.setItem("my_filter_flag",flag);
    	}
    }
    /////////////////////////

	$('#summernote').summernote({
		placeholder: 'Type Message Here..',
		tabsize: 2,
		height: 300,
		toolbar: [
		    // [groupName, [list of button]]
		    ['style', ['bold', 'italic', 'underline', 'clear']],
		    ['font', ['strikethrough', 'superscript', 'subscript']],
		    ['fontsize', ['fontsize']],
		    ['color', ['color']],
		    ['para', ['ul', 'ol', 'paragraph']],
		    ['insert', ['link', 'picture', 'video']],
		    ['height', ['height']],
		    ['undo'],['redo'],['fullscreen']
		  ]
	});

    $('#edit_form_segment').hide();
    $('#edit_form_toggle').click(function(){
		$('#edit_form_segment').transition('fade');
	});

	$('.card').click(function(){
        let url = $('#url').val();
		location.assign(url+"/ticket/"+$(this).data('id'));
	});

	$('#submitMessage').dropdown({
		action: 'combo',
		onChange: function(value, text, $selectedItem) {
			$("#messageForm [name='status']").val(value);
	    }
	});

	$('.note-btn-group.note-fullscreen button').click(function(){
		$('#summernote').summernote('focus')
		$('html, body').scrollTop( $(document).height() );
	});

	///////////////edit message/////////
	var current_edit_id=null;
	$('button[edit]').click(function(){
		if(current_edit_id!=null){
			$(current_edit_id).summernote('reset');
			$(current_edit_id).summernote('destroy');
			$(current_edit_id+"_button").hide();
		}

		let summernoteid='#'+$(this).data('type')+'_'+$(this).data('id');
		current_edit_id = summernoteid;
		$(summernoteid).summernote({
			tabsize: 2,
			height: 300,
			toolbar: [
			    // [groupName, [list of button]]
			    ['style', ['bold', 'italic', 'underline', 'clear']],
			    ['font', ['strikethrough', 'superscript', 'subscript']],
			    ['fontsize', ['fontsize']],
			    ['color', ['color']],
			    ['para', ['ul', 'ol', 'paragraph']],
			    ['height', ['height']],
		    	['undo'],['redo']
			  ],
			  focus: true
		});
		$(summernoteid+"_button").show();
	});

	$('button[cancel]').click(function(){
		$(current_edit_id).summernote('reset');
		$(current_edit_id).summernote('destroy');
		$(current_edit_id+"_button").hide();
		current_edit_id=null;
	});


	$('button[save]').click(function(){
		$(current_edit_id+'_text').val($(current_edit_id).summernote('code'));
	});
	/////////////////////////////////

	$('.ui.form').form({
      fields: {
        message : ['empty']
      },
      onSuccess : function(event, fields){
		$('body') .dimmer('show');
      }
    });

	function checkToScrollDown(){
		var target = $('#scroll_btm').val();
		if(target != ''){
	        $('html, body').stop().animate({
	            'scrollTop': $(target).offset().top - 90
	        }, 500, 'swing')
		}
	}
	checkToScrollDown();

	$('.ui.checkbox.column').popup();
	$('.ui.checkbox').checkbox();

});