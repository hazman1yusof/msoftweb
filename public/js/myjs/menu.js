/* Write here your custom javascript codes */
var dialogArray=[];
var Menu = function () {

    function create_menu(){
		$('#menu').metisMenu();
    }
		
	function deleteDialog(programid){
		$("a[programid='"+programid+"']").removeClass('clickactive');
		if(typeof last !== 'undefined'){
			last.find('i').remove()
		}
		$.each(dialogArray, function( index, obj ) {
			if(obj.id==programid){
				dialogArray.splice(index, 1);
				return false;
			}
		});
		if(dialogArray.length>0){
			$(".clickable[programid='"+dialogArray[dialogArray.length-1].id+"']").append( "<i class='fa fa-caret-right fa-lg'></i>" );
			last=$(".clickable[programid='"+dialogArray[dialogArray.length-1].id+"']");
		}
	}
	
	function searchDialog(programid){
		var object={got:false};
		$.each(dialogArray, function( index, obj ) {
			if(obj.id==programid){
				object=obj;
				return false;//bila return false, skip .each terus pegi return
			}
		});
		return object;
	}

	var colCounter = 0; 
	function coloringHeader_title(programid,title){
		var curr = $("iframe[programid="+programid+"]");
		curr.prev().addClass("headerColor"+colCounter%6);
		curr.prev().attr("title",title);
		colCounter++;
	}

	function dialog_title(obj_array,obj){
		var title = '';
		obj_array.each(function( index ) {
			title = ' > ' + $(this).children('a').find('span.lilabel').text() + title;
		});

		if(title.replace(/ +/g, '') == '>'){
			return obj.text();
		}else{
			return title.substring(3);
		}
	}
	
	var last;
	$("#myNavmenu a.clickable").click(function(){
		if($(this).is("[newtab]")){
			window.open($(this).attr('targetURL'),"_self");
		}else if(cntrlIsPressed){
			cntrlIsPressed=false;
			window.open($(this).attr('targetURL'));
		}else if($(this).is("[cntrlIsPressed]")){
			cntrlIsPressed=false;
			window.open($(this).attr('targetURL'));
		}else{
			$( ".lilabel" ).hide();
			$( "#myNavmenu" ).animate({ width:"8%" }, "fast");
			window.scrollTo(0,0);
			$(this).append( "<i class='fa fa-caret-right fa-lg'></i>" );
			if(typeof last !== 'undefined' && $(this).attr('programid') != last.attr('programid')){
				last.find('i').remove()
			}
			last=$(this);
			$(this).addClass( "clickactive" );
			var programid=$(this).attr('programid');
			if(dialogArray.length>0){
				var obj = searchDialog(programid);
				if(obj.got===false){
					makeNewDialog(
						$(this).attr('programid'),
						$(this).attr('targetURL'),
						dialog_title($(this).parents('li'),$(this)),
					);
				}else{
					obj.dialog.dialog( "moveToTop" );
					obj.dialog.dialogExtend("restore");
				}
			}else{
				makeNewDialog(
					$(this).attr('programid'),
					$(this).attr('targetURL'),
					dialog_title($(this).parents('li'),$(this)),
				);
			}
		}
		
	});

	var cntrlIsPressed;
	$(document).keydown(function(event){
	    if(event.which=="17")
	        cntrlIsPressed = true;
	});

	$(document).keyup(function(){
	    cntrlIsPressed = false;
	});
		
	function makeNewDialog(programid,targetURL,title){
		window.scrollTo(0,0);
		var dialogObj = {id:programid,dialog:{}};
		
		dialogObj.dialog=$("<iframe src='"+targetURL+"' programid='"+programid+"' ></iframe>")
		  .dialog({ 
			title : title,
			position: { my: "left bottom", at: "left+500px bottom"},
			width: 9.2/10 * $(window).width(),
			height: $(window).height() - 50,
			close: function( event, ui ) {
				deleteDialog(programid);
				get_authdtl_alert();
			},
		  })
		  .dialogExtend({
				"closable" : true,
				"maximizable" : false,
				"minimizable" : true,
				"collapsable" : false,
				"dblclick" : "minimize",
				"restore" : function(evt) { 
					$(this).dialog( "moveToTop" );
				}
		  });
		  dialogArray.push(dialogObj);
		  coloringHeader_title(programid,title);
		$( dialogObj.dialog ).mouseenter(function() {
			window.scrollTo(0,0);
			$('body').addClass('stop-scrolling')
		}).mouseleave(function() {
			$('body').removeClass('stop-scrolling')
		});
	}
	
	function announce(){
		$.getJSON( "announcement/generate", function(data){
            
			$("#announcement").append(data.res);

        }).done(function() {
			$("#myCarousel").carousel({interval: 5000});
		});
	}

	// function loadCard(){
	// 	var tillcard={
	// 		id:'#cardTill',
	// 		urlParam:{
	// 			action:'get_value_default',
	// 			field:['t.tillstatus','dtl.tillcode','dtl.opendate','dtl.tillno'],
	// 			table_name:['debtor.tilldetl dtl','debtor.till t'],
	// 			table_id:'dtl.tillcode',
	// 			join_type:['LEFT JOIN'],
	// 			join_onCol:['t.tillcode'],
	// 			join_onVal:['dtl.tillcode'],
	// 			filterCol:['dtl.compcode','dtl.adduser','closedate'],
	// 			filterVal:['session.company','session.username','IS NULL'],
	// 		},
	// 		saveParam:{
	// 			action:'save_table_default_arr',
	// 			array:[{
	// 				oper:'edit',
	// 				field:['closedate'],
	// 				table_name:'debtor.tilldetl',
	// 				table_id:'tillcode',
	// 				filterCol:['tillno'],
	// 				filterVal:[''],
	// 			},{
	// 				oper:'edit',
	// 				field:['tillstatus'],
	// 				table_name:'debtor.till',
	// 				table_id:'tillcode',
	// 			}],
	// 		},
	// 		post:{
	// 			closedate:'NOW()',tillstatus:'C',tillcode:''
	// 		},
	// 	}

	// 	function callback(obj,data){
	// 		switch(obj.id){
	// 			case '#cardTill':
	// 				if($.isEmptyObject(data.rows)){
	// 					$("#cardTill a[name='closetill']").hide();
	// 					$("#cardTill a[name='opentill']").show();

	// 					$("#cardTill a[name='opentill']").on( "click", function() {
	// 						$("#myNavmenu a[programid='ARreceipt']").click();
	// 					});

	// 					$("#cardTill span[name]").html('-');
	// 				}else{
	// 					$("#cardTill a[name='closetill']").show();
	// 					$("#cardTill a[name='opentill']").hide();
	// 					obj.saveParam.array[0].filterVal[0]=data.rows[0].tillno;
	// 					obj.post.tillcode=data.rows[0].tillcode;
	// 					obj.populate(data.rows[0]);

	// 					$("#cardTill a[name='closetill']").on( "click", function() {
	// 						obj.cardPost();
	// 					});
	// 				}
	// 				break;
	// 		}
	// 	}

	// 	function Card(obj){
	// 		this.id=obj.id;
	// 		this.urlParam=obj.urlParam;
	// 		this.saveParam=obj.saveParam;
	// 		this.post=obj.post;
	// 	}

	// 	(function() {
	// 		this.get = function() {
	// 			var self=this
	// 			$.getJSON("assets/php/entry.php?"+$.param(this.urlParam), function(data){

	// 			}).done(function(data) {
	// 				callback(self,data);
	// 			});	
	// 		}
	// 		this.cardPost = function(){
	// 			var self=this;
	// 			$.post( "assets/php/entry.php?"+$.param(this.saveParam),this.post,
	// 			function( data ) {
				
	// 			}).fail(function(data) {

	// 			}).success(function(data){
	// 				self.get();
	// 			});	
	// 		}
	// 		this.populate=function(obj){
	// 			var self=this;
	// 			$.each(obj, function( index, value ) {
	// 				$(self.id+" [name='"+index+"']").html(value);
	// 			});
	// 		}
	// 		this.setRefresh=function(){
	// 			var self=this
	// 			$(this.id+" button[name='refresh']").on( "click", function() {
	// 				self.get();
	// 			});
	// 		}

	// 	}).call(Card.prototype);

	// 	var Card_tillcard = new Card(tillcard);
	// 	Card_tillcard.get();
	// 	Card_tillcard.setRefresh();
	// }

    return {
		new_dialog: function (programid,targetURL,title) {
            makeNewDialog(programid,targetURL,title)
        },
        init_menu: function () {
            create_menu();
        },
		init_announce: function () {
            announce();
        },
        // init_card: function(){
        // 	loadCard();
        // }
    };

}();