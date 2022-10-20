//controller.js

/** Copyright (c) 2021 Mesibo
 * https://mesibo.com
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the terms and condition mentioned
 * on https://mesibo.com as well as following conditions are met:
 *
 * Redistributions of source code must retain the above copyright notice, this
 * list of conditions, the following disclaimer and links to documentation and
 * source code repository.
 *
 * Redistributions in binary form must reproduce the above copyright notice,
 * this list of conditions and the following disclaimer in the documentation
 * and/or other materials provided with the distribution.
 *
 * Neither the name of Mesibo nor the names of its contributors may be used to
 * endorse or promote products derived from this software without specific prior
 * written permission.
 *
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * Documentation
 * https://mesibo.com/documentation/
 *
 * Source Code Repository
 * https://github.com/mesibo/messenger-javascript
 *
 *
 */



//The number of messages loaded into the message area in one read call
const MAX_MESSAGES_READ = 100;

//The number of users to be loaded (summary)
const MAX_MESSAGES_READ_SUMMARY = 100;

const MAX_FILE_SIZE_SUPPORTED = 10000000;




var mesiboWeb = angular.module('MesiboWeb', []);

mesiboWeb.directive('imageonload', function() {
  return {
    restrict: 'A',
    link: function(scope, element, attrs) {
      if(!scope.$last)
        return;

      element.bind('load', function() {
        MesiboLog("image load")
        scrollToEnd(true);
      });

      element.bind('error', function(){
        ErrorLog('Error loading image');
      });                                   
    }
  };
});

mesiboWeb.directive('videoonload', function() {
  return {
    restrict: 'A',
    link: function(scope, element, attrs) {
      if(!scope.$last)
        return;

      element.bind('loadeddata', function() {
        MesiboLog("video loadeddata");
        scrollToEnd(true);
      });

      element.bind('error', function(){
        ErrorLog('Error loading video');
      });                                   
    }
  };
});


mesiboWeb.directive('onFinishRender', function($timeout) {
  return {
    link: function(scope, element, attr) {
      if (scope.$last === true) {
        $timeout(function() {
          scope.$emit(attr.onFinishRender);
        });
      }
    }
  };
});


mesiboWeb.controller('AppController', ['$scope', '$window', '$anchorScroll', function ($scope, $window, $anchorScroll) {

	console.log("AppController loaded");
	var token = getLoginToken();
	if(!token || token.length < 16) {
		window.location.replace("index.html");
		return;
	}

	$scope.isConnected = false;
	$scope.isLoggedIn = false;
	$scope.connection_status = '';
	$scope.summarySession = {};
	$scope.msg_read_limit_reached = false;
	$scope.users_synced = false;
	$scope.scroll_messages = null;
	$scope.is_shared = false;
	$scope.new_contact_name = '';
	$scope.new_contact_phone = '';

	$scope.mesibo_user_messages = [];

	$scope.selected_user = null;
	$scope.selected_user_count = 0; 

	$scope.forward_message = null;

	$scope.unread_count = {};

	$scope.mesibo = null; 

	//Main UI
	$scope.display_profile = null;
	$scope.membersList = [];
	$scope.users_panel_show = false;
	$scope.message_area_show = false;

	//Input Area
	$scope.input_message_text ="";
	$scope.link_preview = null

	//Calls  
	$scope.is_answer_call = false;
	$scope.is_video_call = false;
	$scope.is_voice_call = true;
	$scope.call_status = "Call Status: ";
	$scope.call_alert_message = "";

	//Files
	$scope.selected_file = {};
	$scope.input_file_caption = "";

	//Recorder
	$scope.recorder = null;

	$scope.MAX_MEDIA_WIDTH = '320px';
	$scope.MAX_MEDIA_HEIGHT = '240px';

	$scope.MIN_MEDIA_WIDTH = '160px';
	$scope.MIN_MEDIA_HEIGHT = '120px';

	$scope.refresh = function(){
		$scope.$applyAsync();
	}

	$scope.scrollToLastMsg = function() {
		$scope.$$postDigest(function () {
			//$anchorScroll("messages_end");
			scrollToEnd(false);
		});
	}

	$scope.updateMessagesScroll = function(){

	}

	$scope.$on('onMessagesRendered', function(e) {
		MesiboLog("onMessagesRendered");
		if($scope.scroll_messages && 
			$scope.scroll_messages.scrollTop == 0
			&& $scope.messageSession
			&& $scope.messageSession.getMessages().length){
			MesiboLog('onMessagesRendered');
		}

		$scope.scrollToLastMsg();

	});


	angular.element(document.getElementById('messages')).bind('scroll', function(e){
		//MesiboLog("scrolling");
		$scope.checkScroll(e);	
	})


	$scope.checkScroll = function(e) {
		if(!(e && e.target))
			return;

		$scope.scroll_messages = e.target;

		if($scope.scroll_messages.scrollTop == 0){
			if(!($scope.messageSession 
				&& $scope.messageSession.getMessages)){
				return;
			}

			var m = $scope.messageSession.getMessages().length;
			if(m == 0){
				return;
			}

			MesiboLog("checkScroll: Scrolled to top!");
			//Load more messages
			$scope.messageSession.read(MAX_MESSAGES_READ);
		}

	}


	$scope.getMesibo = function(){
		return $scope.mesibo;
	}

	$scope.showAvailableUsers = function() {
		MesiboLog('showAvailableUsers');
		$scope.users_panel_show = true;

		//prompt to add a contact if no contacts available
		if(!$scope.hasContacts())
			$scope.showContactForm();

		$scope.refresh();   
	}

	$scope.hideAvailableUsers = function() {
		MesiboLog('hideAvailableUsers');
		$scope.users_panel_show = false;
		$scope.refresh();
	}

	$scope.getContacts = function(){
		return $scope.mesibo.getSortedProfiles();
	}
	
	$scope.hasContacts = function(){
		var c = $scope.getContacts();
		if(c && c.length) return true;
		return false;
	}

	// [OPTIONAL] refer to the comment below
	$scope.Mesibo_onGroupMembers = function(p, members) {
		$scope.membersList = members;
		$scope.refresh();
	}

	$scope.showProfile = function(p) {
		MesiboLog("showProfile");
		if(!p)
			return;
		$scope.display_profile = p; 
		$scope.membersList = [];
		if(p.getGroupId() > 0) {
			// you can either pass a function or listenr, this code list 
			// both teh syntax for reference
			if(true) {
				p.getMembers(0, false, $scope);
			} else {
				p.getMembers(0, false, function(p, members) {
					$scope.membersList = members;
					$scope.refresh();
				});
			}
		}
		$scope.refresh();
	};
	
	$scope.showProfileFromMessage = function(m) {
		var p = $scope.getProfileFromMessage(m);
		if(!p)
			return;
		$scope.showProfile(p); 

		$scope.refresh();
	};

	$scope.hideProfileSettings = function() {
		MesiboLog("hideProfileSettings");
		$scope.display_profile = null; 
		$scope.membersList = [];
		$scope.refresh();
	};

	$scope.hideForwardList = function() {
		MesiboLog("hideForwardList");
		$scope.forward_message = null; 
		$scope.refresh();
	};

	//fm is the message to be forwarded
	$scope.showForwardList = function(fm){
		if(!fm)
			return;

		$scope.forward_message = fm;
		$scope.refresh();
	}


	$scope.isSent = function(msg){
		return isSentMessage(msg.status);
	}

	$scope.isReceived = function(msg){
		return !isSentMessage(msg.status);
	}


	$scope.generateMessageArea = function(contact){
		MesiboLog(contact);

		if($scope.selected_user && $scope.selected_user == contact){
			return 0;
		}

		$scope.selected_user = contact;

		// Stop read session for previous user 
		if($scope.messageSession && typeof $scope.messageSession.stop == 'function')
			$scope.messageSession.stop();

		$scope.messageSession = null;
		$scope.scroll_messages = null;
		$scope.sessionReadMessages($scope.selected_user, MAX_MESSAGES_READ);			
		$scope.message_area_show = true;
		$scope.refresh();
		$scope.scrollToLastMsg();
	}


	$scope.setSelectedUser = function(user){
		$scope.selected_user = user;
		$scope.refresh()
	}

	$scope.showContactForm = function(){
		$('#ModalContactForm').modal("show");
	}

	$scope.promptAddContact = function(){
		$('#promptAddContact').modal("show");
	}

	$scope.closePromptAddContact = function(){
		$('#promptAddContact').modal("hide");
	}

	$scope.hideContactForm = function(){
		$('#ModalContactForm').modal("hide");
		if(document.getElementById('contact-address'))
			document.getElementById('contact-address').value = "";

		if(document.getElementById('contact-name'))
			document.getElementById('contact-name').value = "";

	}

	$scope.addContact = function(){
		MesiboLog("Add Contact");			

		//cAddress = document.getElementById('contact-address').value;
		//cGroupid = document.getElementById('contact-group-id').value;
		var new_contact_phone = document.getElementById('contact-address').value;

		if(new_contact_phone.length < 2){
			alert("Enter valid phone number / address or a valid group id");	
			return;
		}

		$scope.new_contact_phone = new_contact_phone;

		if($scope.new_contact_phone[0] == "+")
			$scope.new_contact_phone = $scope.new_contact_phone.slice(1);

		var c = $scope.mesibo.getProfile($scope.new_contact_phone, 0);

		$scope.hideContactForm();
		$scope.new_contact_name = '';
		$scope.new_contact_phone = '';

		//TBD: After adding new contact, select that
		$scope.generateMessageArea(c);

		$scope.refresh();

		$('#hideuser').click();
	}


	$scope.isValidPreview = function(type){
		MesiboLog("isValidPreview", type);

		if(type == "image"){
			var e = document.getElementById("image-preview");
			if(!e)
				return;

			MesiboLog(e);
			var fname = e.src;

			MesiboLog(fname);
			if(!fname)
				return;

			return isValidImage(fname);
		}

		if(type == "video"){
			var e = document.getElementById("video-preview");
			if(!e)
				return;

			var fname = e.src;
			if(!fname)
				return;

			return isValidVideo(fname);

		}

		return false;
	}

	$scope.getProfileFromMessage = function(m) {
		if(!m) return null;
		var p = m['groupProfile'];
		if(!p) p = m['profile'];
		return p;
	}
	
	$scope.hasPicture = function(m) {
		var p = $scope.getProfileFromMessage(m);
		if(!p) return false;
		var pic = p.getThumbnail();
		if(pic && pic.length > 10) return true;
		return false;
	}

	$scope.getFirstLetter = function(m) {
		var p = $scope.getProfileFromMessage(m);
		if(!p) return '*';
		var name = p.getNameOrAddress();
		return name[0];
	}
	
	$scope.getLetterColor = function(m) {
		var p = $scope.getProfileFromMessage(m);
		var colors = ["#e6d200", "#f58559", "#f9a43e", "#e4c62e",
		            "#67bf74", "#59a2be", "#2093cd", "#ad62a7"];
		if(!p) return colors[0];
		var name = p.getNameOrAddress();
		var l = name.length;
		if(!l) return colors[0];
		var c = name.charCodeAt(l-1)&7;
		return colors[c];
	}

	$scope.getPictureFromMessage = function(m) {
		var p = $scope.getProfileFromMessage(m);
		return $scope.getUserPicture(p);
	}
	
	$scope.getNameFromMessage = function(m) {
		var p = $scope.getProfileFromMessage(m);
		return $scope.getUserName(p);
	}
	
	$scope.getSenderNameFromMessage = function(m) {
		return m['profile'].getNameOrAddress();
	}

	$scope.getUserPicture = function(user){
		if(!user) return '';
		// MesiboLog(user);
		var pic = user.getThumbnail();
		if(pic && pic.length > 10) return pic;

		return user.getGroupId() ? MESIBO_DEFAULT_GROUP_IMAGE:MESIBO_DEFAULT_PROFILE_IMAGE; 
	} 

	$scope.getProfileImage = function(user){
		if(!user) return '';
		// MesiboLog(user);
		var pic = user.getImageOrThumbnail();
		if(pic && pic.length > 10) return pic;

		return user.getGroupId() ? MESIBO_DEFAULT_GROUP_IMAGE:MESIBO_DEFAULT_PROFILE_IMAGE; 
	} 

	$scope.getUserName = function(user){
		if(!user) return "";
		return user.getNameOrAddress('+');
	}
	
	$scope.getUserStatus = function(user){
		// MesiboLog("getUserName", user);
		if(!user) return "";
		return user.getStatus();
	}
	
	$scope.getMemberType = function(type){
		if(type == 1) return "Group Owner";
		if(type == 2) return "Group Admin";
		return "Member";
	}

	$scope.getUserLastMessage = function(m){

		var profile = m['profile'];

		if(profile.isTypingInGroup(m['groupid'])) return "typing...";

		if(m.filetype)
			return getFileTypeDescription(m);

		var message = m.message;
		if(!isValidString(message))
			return "";

		return message;
	}

	$scope.getUserLastMessageTime = function(m){
		var date = m.date;
		if(!isValid(date))
			return "";

		var date_ = date.date;
		if(!isValidString(date_))
			return "";

		var time = date.time;
		if(!isValidString(time))
			return "";

		if(date_ != 'Today')
			time = date_;

		return time;
	}

	$scope.getUserUnreadCount = function(m, index){
		var p = $scope.getProfileFromMessage(m);

		var identifier = p.getGroupId() ? p.getGroupId() : p.getAddress();
		var ucount = $scope.unread_count[identifier];

		if(ucount != undefined){
			if(0 == ucount)
				ucount = "";

			//MesiboLog("getUserUnreadCount", "id: "+ identifier, "ucount: "+ ucount, "m:", $scope.unread_count);
			document.getElementById("unread_count_"+ index).innerHTML = ucount;
			return ucount;
		}

		//Restore from history
		var rs =  $scope.mesibo.readDbSession(p.getAddress(), p.getGroupId(), null,
			function on_read(count) {
			});

		rs.getUnreadCount( function on_unread(count){
			//console.log("getUnreadCount from db", "a: "+ user.getAddress(), "g: "+ user.getGroupId(), "c: "+ count);
			$scope.unread_count[identifier] = count;

			if(!count)
				count = "";

			document.getElementById("unread_count_"+ index).innerHTML = count;
		});
	} 

	$scope.getMessageStatusClass = function(m){
		if(!isValid(m))
			return "";

		if($scope.isReceived(m)){
			return "";
		}

		var status = m.status;
		var status_class = getStatusClass(status);
		if(!isValidString(status_class))
			return -1;

		return status_class;
	}

	$scope.getFileName = function(m){
		if(!m)
			return;

		if(m.title)
			return m.title;

		var fileUrl = m.fileurl;
		if(!fileUrl)
			return;

		var f = fileUrl.split("/");
		if(!(f && f.length))
			return;

		var fname = f[f.length - 1];
		return fname;
	}

	$scope.getVideoWidth = function(e){
		MesiboLog("getVideoWidth", e);
	}

	$scope.getVideoHeight = function(e){
		MesiboLog("getVideoHeight", e);
	}

	$scope.setLinkPreview = function(lp){
		$scope.link_preview = lp;
		$scope.refresh();
	}

	$scope.closeLinkPreview = function(){
		$scope.link_preview = null;
		$scope.refresh();
	}

	$scope.inputTextChanged = async function(){
		MesiboLog('inputTextChanged');
		//if enabled config isLinkPreview
		if(isLinkPreview){
			//xx Bug xx: If link_preview is already present doesn't update
			if(isValid($scope.link_preview) && isValidString($scope.link_preview.url)){
				var newUrl = getUrlInText($scope.input_message_text);
				if(newUrl == $scope.link_preview.url)
					return; //Make no changes to existing preview
			}

			var urlInMessage = getUrlInText($scope.input_message_text);
			if(isValidString(urlInMessage)){
				MesiboLog("Fetching preview for", urlInMessage)
				var lp = await $scope.file.getLinkPreviewJson(urlInMessage, LINK_PREVIEW_SERVICE, LINK_PREVIEW_KEY);
				// var lp = getSampleLinkPreview(); /*For testing */
				if(isValid(lp)){
					MesiboLog(lp);
					$scope.setLinkPreview(lp);
					$scope.refresh();
				}
			}
			else
				$scope.link_preview = null;
		}
	}

	$scope.getUserActivity = function(u) {
		if(!u) return "";
		if(u.getGroupId() > 0) return ""; // This is not correct/complete as we can still show a user typing

		if(u.isTyping()) return "typing...";
		if(u.isChatting()) return "chatting with you...";
		if(u.isOnline()) return "Online";
		return "";
	}

	$scope.getLastMessageColor = function(m) {
		var profile = m['profile'];
		if(profile.isTypingInGroup(m['groupid'])) return "#008800";
		return "#000000";
	}

	$scope.getMessageStatusColor = function(m){
		// MesiboLog("getMessageStatusColor", m);
		if(!isValid(m))
			return "";

		if($scope.isReceived(m))
			return "";

		var status = m.status;
		var status_color = getStatusColor(status);
		if(!isValidString(status_color))
			return "";

		return status_color;
	}

	$scope.isOnlineFromMessage = function(m){
		var profile = m['profile'];
		if(profile) return profile.isOnline();
		return false;
	}

	$scope.deleteTokenInStorage = function(){
		localStorage.removeItem("MESIBO_MESSENGER_TOKEN");
	}
	$scope.logout = function(){
		$('#logoutModal').modal("show");
		$scope.deleteTokenInStorage();
		$scope.mesibo.stop();
	}

	$scope.getFileIcon = function(f){
		return getFileIcon(f);
	}

	$scope.sessionReadSummary = function(){
		$scope.summarySession = $scope.mesibo.readDbSession(null, 0, null, 
			function on_read(result) {
				// Read handler
				// Provides a list of users that you have had conversations with
				// Along with their lastMessage
				MesiboLog("==> on_read summarySession", result);
				if(result == undefined || result == null)
					return;

				if(isMessageSync && !result && !$scope.users_synced){
					MesiboLog("Run out of users to display. Syncing..");
					$scope.users_synced = true;
					$scope.syncMessages(this, this.readCount - result);
				}

				var messages = this.getMessages();
				if(messages && messages.length > 0){
					var m = messages[0];
					$scope.generateMessageArea($scope.getProfileFromMessage(m));
				}  
				$scope.refresh()
			});

		if(!$scope.summarySession){
			MesiboLog("Invalid summarySession");
			return -1;
		}

		$scope.summarySession.enableSummary(true);
		$scope.summarySession.readCount = MAX_MESSAGES_READ_SUMMARY;
		$scope.summarySession.read(MAX_MESSAGES_READ_SUMMARY);
	}
	
	$scope.getMessages = function() {
		if($scope.messageSession)
			return $scope.messageSession.getMessages();
		return [];
	}

	$scope.syncMessages = function(readSession, count, type){
		if(!(readSession && count && readSession.sync)){
			MesiboLog("syncMessages", "Invalid Input", readSession, count);
			return;
		}

		MesiboLog("syncMessages called \n", readSession, count);	
		$scope.refresh();

		readSession.sync(count,
			function on_sync(i){
				MesiboLog("on_sync", i);
				if(i > 0){
					MesiboLog("Attempting to read "+ i + " messages");
					this.read(i);
				}
			});
	}

	$scope.sessionReadMessages = function(user, count){
		MesiboLog("sessionReadMessages", user);
		$scope.messageSession =  user.readDbSession(null, 
			function on_read(result) {
				// Read handler
				// result will be equal to the number of messages read
				MesiboLog("==> on_read messageSession", result);

				if(result == undefined || result == null || result == NaN)
					return;

				if(isMessageSync && this.readCount && result < this.readCount){
					MesiboLog("Run out of messages to display. Syncing..");
					$scope.msg_read_limit_reached = true;
					$scope.syncMessages(this, this.readCount - result, 1);
					return;
				}

				var msgs = this.getMessages();

				if(msgs && msgs.length){
					$scope.mesibo_user_messages = msgs;
				}

				if($scope.scroll_messages 
					&& $scope.scroll_messages.scrollTop == 0
					&& msgs.length){
					$scope.scroll_messages.scrollTop = result * 10; 
				}
				else{
					$scope.scrollToLastMsg();
				}

				$scope.refresh();
			});


		if(!$scope.messageSession){
			MesiboLog("Invalid messageSession");
			return -1;
		}

		$scope.messageSession.enableReadReceipt(true);
		$scope.messageSession.readCount = count;
		$scope.messageSession.read(count);

		var identifier = user.getGroupId() ? user.getGroupId() : user.getAddress();
		if($scope.unread_count[identifier])
			$scope.unread_count[identifier] = 0

	}

	$scope.readMessages = function(userScrolled){

		if($scope.messageSession)
			$scope.messageSession.read(MAX_MESSAGES_READ);
		else
			$scope.sessionReadMessages($scope.selected_user, MAX_MESSAGES_READ);
	}

	$scope.update_read_messages = function(m, rid){
		$scope.messageSession.getMessages = function(){
			return m;
		}

		$scope.$applyAsync(function()  {
			if($scope.scroll_messages){
				if($scope.mesibo_user_messages &&
					$scope.mesibo_user_messages.length == m.length)
					return;
				$scope.scroll_messages.scrollTop = 50;
			}
			else
				$scope.scrollToLastMsg();

		});

		$scope.mesibo_user_messages = m;
		MesiboLog("scope.update_read_messages", $scope.messageSession.getMessages());
		$scope.refresh();
	}

	$scope.deleteSelectedMessage = function(m){
		MesiboLog("deleteSelectedMessage", m);
		if(!m)
			return;

		var id = m.id;
		if(!id)
			return;

		if($scope.mesibo && $scope.mesibo.deleteMessage){
			MesiboLog("deleteSelectedMessage with id: ", id);	    		
			$scope.messageSession.deleteMessage(id);
			$scope.mesibo.deleteMessage(id);		
		}

		$scope.refresh();
	}

	$scope.forwardMessageTo = function(to){
		MesiboLog("forwardMessageTo", to);
		if(!to)
			return;

		var m = $scope.forward_message;
		MesiboLog(m, $scope.forward_message);
		if(!m)
			return;

		$scope.forwardSelectedMessage(m, to);

		$scope.forward_message = null;
		$scope.refresh();

		$scope.generateMessageArea(to);
	}

	$scope.forwardSelectedMessage = function(m, to){
		MesiboLog("forwardSelectedMessage", m, to);
		if(!(m && to))
			return;

		var p = {};	 

		p.peer =  to.getAddress();
		p.groupid = to.getGroupId();

		p.expiry = 3600;

		var id = m.id;
		if(!id)
			return;

		if($scope.mesibo && $scope.mesibo.forwardMessage){
			MesiboLog("forwardSelectedMessage with id: ", id, " with params:", p);
			$scope.mesibo.forwardMessage(p, $scope.mesibo.random(), id);	    			    		
		}

		$scope.refresh();
		$scope.scrollToLastMsg();
	}

	$scope.resendSelectedMessage = function(m){
		MesiboLog("resendSelectedMessage", m);
		if(!$scope.selected_user)
			return;

		var p = {};	 

		p.peer =  $scope.selected_user.getAddress();
		p.groupid = $scope.selected_user.getGroupId();

		p.expiry = 3600;

		var id = m.id;
		if(!id)
			return;

		if($scope.mesibo && $scope.mesibo.resendMessage){
			MesiboLog("resendSelectedMessage with id: ", id, " with params:", p);
			var r = $scope.mesibo.resendMessage(p, id);
			MesiboLog("resendSelectedMessage returned", r);	    			    		
		}
	}

	$scope.Mesibo_OnMessage = async function(m, data) {
		MesiboLog("$scope.prototype.OnMessage", m, data);
		if(!m.id || m.presence)
			return;


		if($scope.is_shared){
			//Modified message access in case of shared popup 
			for (var i = $scope.mesibo_user_messages.length - 1; i >= 0; i--) {
				if($scope.mesibo_user_messages[i].id == m.id){
					MesiboLog("Mesibo_OnMessage", "Message exists");
					return;
				}
			}
			$scope.mesibo_user_messages.push(m);
		}

		$scope.refresh();

		if($scope.selected_user
			&& $scope.selected_user.getAddress() == m.peer){
			$scope.scrollToLastMsg();
		}

		if(!$scope.selected_user) return 0;

		if((m.peer && $scope.selected_user.getAddress() != m.peer)
			|| (m.groupid && $scope.selected_user.getGroupId() != m.groupid)){
			var identifier = m.groupid ? m.groupid : m.peer;
			MesiboLog("update unread for ", identifier);
			if(!$scope.unread_count[identifier])
			$scope.unread_count[identifier] = 0;

			$scope.unread_count[identifier] += 1;
			$scope.refresh();
		}

		return 0;
	};

	$scope.Mesibo_OnActivity = async function(m, activity, value) {
		// calling refresh is not optimized but keep it for now
		$scope.refresh();
	}

	function getCurrentDate(){
		var d = {};
		const date = new Date();
		var h = date.getHours() + "";
		var m = date.getMinutes() + "";
		if(h.length < 2)
			h = "0" + h;
		if(m.length < 2)
			m = "0" + m;
		d.time = h + ":" + m;
		d.yd = "Today";

		return d;
	}

	$scope.onKeydown = function(event){
		MesiboLog("onKeydown". event);
		if(event.keyCode === 13) 
			$scope.sendMessage();
		else 
			$scope.selected_user.sendActivity($scope.mesibo.random(), MESIBO_ACTIVITY_TYPING, 0, 7500);

		//event.preventDefault();
	}

	//Send text message to peer(selected user) by reading text from input area
	$scope.sendMessage = function() {
		MesiboLog('sendMessage');

		var value = $scope.input_message_text;
		if(!value)	
			return -1;

		if(isLinkPreview && isValid($scope.link_preview)){
			//If link preview is enabled in configuration
			var urlInMessage = getUrlInText(value);
			if(isValidString(urlInMessage)){
				var m = {};
				//xx TODO xx Special code for link type is probably required
				m.filetype = MESIBO_FILETYPE_IMAGE;
				m.fileurl = linkPreview.image;
				// urlAsfile.tn = []; //Get Thumbnail if required
				m.title = linkPreview.title;
				m.launchurl = linkPreview.url;
				$scope.selected_user.sendFile($scope.mesibo.random(), m);

				this.scope.link_preview = null;	
				this.scope.refresh();	
			}
		}
		else{
			//$scope.mesibo.sendMessage(messageParams, messageParams.id, messageParams.message);
			$scope.selected_user.sendMessage($scope.mesibo.random(), value);
		}

		//$scope.mesibo_user_messages.push(messageParams);
		$scope.input_message_text = "";
		$scope.refresh();
		$scope.scrollToLastMsg();
		return 0;
	}

	$scope.makeVideoCall = function(){
		$scope.is_video_call = true;
		$scope.call.videoCall();
		$scope.refresh();
	}

	$scope.makeVoiceCall = function(){
		$scope.is_voice_call = true;
		$scope.call.voiceCall();
		$scope.refresh();
	}


	$scope.hideAnswerModal = function(){
		$('#answerModal').modal("hide");
		$scope.is_answer_call = false;
		$scope.refresh();
	}

	$scope.hangupCall = function(){
		$scope.mesibo.hangup(0);
		$scope.hideAnswerModal();
	}


	$scope.answerCall = function(){
		$scope.is_answer_call = true;
		$scope.call.answer();
		$scope.refresh();   
	}

	$scope.showRinging = function(){
		//$('#answerModal').modal({backdrop: 'static', keyboard: false});
		//$('#answerModal').modal({ show: true });
		$('#answerModal').modal("show");
		$scope.refresh();
	}

	$scope.hangupVideoCall = function(){
		$('#videoModal').modal("hide");
		$scope.is_video_call = false;
		$scope.call.hangup();
		$scope.refresh();
	}

	$scope.hangupAudioCall = function(){
		$('#voiceModal').modal("hide");
		$scope.is_voice_call = false;
		$scope.call.hangup();
		$scope.refresh();
	}

	$scope.showVideoCall = function(){
		$('#videoModal').modal("show");
		$scope.is_video_call = true;
		$scope.refresh();
	}

	$scope.showVoiceCall = function(){
		$('#voiceModal').modal("show");
		$scope.is_voice_call = true;
		$scope.refresh();
	}

	$scope.clickUploadFile = function(){
		setTimeout(function () {
			angular.element('#upload').trigger('click');
		}, 0);
	}

	$scope.onFileSelect = function(element){
		$scope.$apply(function(scope) {
			var file = element.files[0];
			if(!file){
				MesiboLog("Invalid file");
				return -1;
			}

			if(file.size > MAX_FILE_SIZE_SUPPORTED){
				MesiboLog("Uploaded file larger than supported(10 MB)");
				alert("Please select a file smaller than 10Mb");
				return;
			}

			MesiboLog("Selected File =====>", file);

			$scope.selected_file = file;
			$scope.showFilePreview(file);
			MesiboLog('Reset', element.value);
			element.value = '';

		});
	}

	$scope.showFilePreview = function(f) {
		var reader = new FileReader();
		$('#image-preview').attr('src', "");
		$('#video-preview').attr('src', "");
		$('#video-preview').hide();

		reader.onload = function(e) {
			if(isValidFileType(f.name, 'image')){
				$('#image-preview').attr('src', e.target.result);
				$('#image-preview').show();
			}
			else if(isValidFileType(f.name, 'video')){
				$('#video-preview').attr('src', e.target.result);
				$('#video-preview').show();
			}
		}

		reader.readAsDataURL(f);

		var s = document.getElementById("fileModalLabel");
		if (s) {
			s.innerText = "Selected File " + f.name;
		}

		$('#fileModal').modal("show");
	}

	$scope.openAudioRecorder = function(){
		$('#recorderModal').modal("show");
		document.getElementById("recorderModalLabel").innerHTML = "Audio Recorder";
		$scope.recorder = new MesiboRecorder($scope, "audio");
		$scope.recorder.initAudioRecording();
	}

	$scope.openPictureRecorder = function(){
		$('#recorderModal').modal("show");
		document.getElementById("recorderModalLabel").innerHTML = "Video Recorder";
		$scope.recorder = new MesiboRecorder($scope, "picture");
		$scope.recorder.initPictureRecording();
	}

	$scope.closeRecorder = function(){
		MesiboLog("Closing recorder.., shutting down streams.", $scope.recorder);
		$('#recorderModal').modal("hide");
		if(!$scope.recorder)
			return;
		$scope.recorder.close();
		$scope.recorder = null;			
	}

	$scope.closeFilePreview = function() {
		$('#fileModal').modal("hide");
		$('#image-preview').hide();
		$('#video-preview').hide();
		//Clear selected file button attr
	}

	$scope.sendFile = function(){
		var m = {};
		m.file = $scope.selected_file;
		m.message = $scope.input_file_caption;

		$scope.selected_user.sendFile($scope.mesibo.random(), m);
		$scope.input_file_caption = '';
	}

	$scope.isFileMsg = function(m){
		return isValid(m.filetype);
	}

	$scope.isFailedMessage = function(m){		    
		if(!m)
			return false;

		if(!(m['status'] & MESIBO_MSGSTATUS_FAIL))
			return false;

		return true;
	}

	$scope.hostnameFromUrl = function(pUrl){
		if(!isValidString(pUrl))
			return "";
		var hostname = pUrl.replace('http://','').replace('https://','').split(/[/?#]/)[0];
		if(!isValidString(hostname))
			return "";

		return hostname;
	}

	//Message contains URL Preview
	$scope.isUrlMsg = function(m){
		return ($scope.isFileMsg(m) && !isValidString(m.fileurl));
	}

	$scope.isImageMsg = function(m){
		if(!$scope.isFileMsg(m))
			return false;
		return (MESIBO_FILETYPE_IMAGE == m.filetype);
	}

	$scope.isVideoMsg = function(m){
		if(! $scope.isFileMsg(m))
			return false;
		return (MESIBO_FILETYPE_VIDEO == m.filetype);
	}


	$scope.isAudioMsg = function(m){
		if(! $scope.isFileMsg(m))
			return false;
		return (MESIBO_FILETYPE_AUDIO == m.filetype);
	}

	$scope.isOtherMsg = function(m){
		if(! $scope.isFileMsg(m))
			return false;
		return (m.filetype >= MESIBO_FILETYPE_LOCATION);
	}

	$scope.Mesibo_OnConnectionStatus = function(status){
		$scope.isConnected = false;

		MesiboLog("MesiboNotify.prototype.Mesibo_OnConnectionStatus: " + status);	
		if(MESIBO_STATUS_SIGNOUT == status || MESIBO_STATUS_AUTHFAIL == status ){
			$scope.logout();
		}

		var s ="";
		switch(status){
			case MESIBO_STATUS_ONLINE:
				s = "";
				$scope.isConnected = true;
				break;
			case MESIBO_STATUS_CONNECTING:
				s = "Connecting..";
				break;
			default: 
				s = "Not Connected";
		}

		$scope.connection_status = s;
		$scope.refresh();
	}
	
	$scope.Mesibo_OnProfileUpdated = function(p){
		$scope.refresh();
	}

	$scope.updateReadPrevious = function(index){
		MesiboLog("updateReadPrevious");
		for (var i = index; i >= 0; i--) {
			if($scope.mesibo_user_messages[i].status == MESIBO_MSGSTATUS_READ)
				return;

			if($scope.mesibo_user_messages[i].status == MESIBO_MSGSTATUS_DELIVERED)
				$scope.mesibo_user_messages[i].status = MESIBO_MSGSTATUS_READ;
		}
	}

	$scope.Mesibo_OnMessageStatus = function(m){
		MesiboLog("$scope.Mesibo_OnMessageStatus", m);

		//In case of shared popup, need to manually update message across all tabs
		for (var i = $scope.mesibo_user_messages.length - 1; i >= 0 && $scope.is_shared; i--) {
			if($scope.mesibo_user_messages[i].id == m.id){
				$scope.mesibo_user_messages[i].status = m.status;

				if(m.status == MESIBO_MSGSTATUS_READ && i
					&& $scope.mesibo_user_messages[i-1].status
					!= MESIBO_MSGSTATUS_READ){ //Make all previous delivered msgs to read
					$scope.updateReadPrevious(i - 1);
				}

				break;
			}
		}
		$scope.refresh();
	}


	$scope.Mesibo_OnCall = function(callid, from, video){
		if(video){
			$scope.is_video_call = true;
			$scope.mesibo.setupVideoCall("localVideo", "remoteVideo", true);
		}
		else{
			$scope.is_voice_call = true;
			$scope.mesibo.setupVoiceCall("audioPlayer");
		}

		$scope.call_alert_message = "Incoming "+(video ? "Video" : "Voice")+" call from: "+from;
		$scope.is_answer_call = true;

		$scope.showRinging();
	}

	$scope.Mesibo_OnCallStatus = function(callid, status){

		var s = "";

		switch (status) {
			case MESIBO_CALLSTATUS_RINGING:
				s = "Ringing";
				break;

			case MESIBO_CALLSTATUS_ANSWER:
				s = "Answered";
				break;

			case MESIBO_CALLSTATUS_BUSY:
				s = "Busy";
				break;

			case MESIBO_CALLSTATUS_NOANSWER:
				s = "No Answer";
				break;

			case MESIBO_CALLSTATUS_INVALIDDEST:
				s = "Invalid Destination";
				break;

			case MESIBO_CALLSTATUS_UNREACHABLE:
				s = "Unreachable";
				break;

			case MESIBO_CALLSTATUS_OFFLINE:
				s = "Offline";
				break;      

			case MESIBO_CALLSTATUS_COMPLETE:
				s = "Complete";
				break;      
		}

		if(s)
			$scope.call_status = "Call Status: " + s;
		$scope.refresh();

		if (status & MESIBO_CALLSTATUS_COMPLETE) {
			if ($scope.is_video_call)
				$scope.hangupVideoCall();
			else
				$scope.hangupAudioCall();
		}
	}

	$scope.setSelfProfile = function(u){
		if(!u)
			return;

		
		var c = $scope.mesibo.getSelfProfile();
		//c.picture = u.photo;
		c.setName(u.name);
		c.setStatus(u.status);
		c.save();

		$scope.refresh();
	}
	
	$scope.getSelfProfile = function(){
		return $scope.mesibo.getSelfProfile();
	}

	$scope.init_messenger = function(){
		MesiboLog("init_messenger called"); 
		$scope.sessionReadSummary();     
		$scope.call = new MesiboCall($scope);
		$scope.file = new MesiboFile($scope);
	}

	$scope.init_popup = function(){ 
		MesiboLog("init_popup called"); 
		$scope.selected_user = $scope.mesibo.getProfile(POPUP_DESTINATION_USER, 0); 
		$scope.activity = ""; 

		$scope.call = new MesiboCall($scope);
		$scope.file = new MesiboFile($scope);

		$scope.MAX_MEDIA_WIDTH = '180px';
		$scope.MAX_MEDIA_HEIGHT = '80px';

		$scope.MIN_MEDIA_WIDTH = '50px';
		$scope.MIN_MEDIA_HEIGHT = '50px';

		MesiboLog("sessionReadMessages", $scope.selected_user, MAX_MESSAGES_READ);
		$scope.sessionReadMessages($scope.selected_user, MAX_MESSAGES_READ); 
	} 

	$scope.toggleConnection = function(){
		if($scope.isConnected){
			MesiboLog("Stop Mesibo..");
			$scope.mesibo.stop();
		}
		else{
			MesiboLog("Start Mesibo..");
			$scope.mesibo.start();
		}
	}

	$scope.getToken = function() {
		if(null == $scope.mesibo)
			$scope.mesibo = Mesibo.getInstance();
		getMesiboDemoAppToken($scope.mesibo);
	}

	$scope.initMesibo = function(demo_app_name){

		if(demo_app_name == "multitab-popup"){
			// Instead of directly accessing Mesibo APIs like so,
			// $scope.mesibo = new Mesibo();
			// use a wrapper API that uses a shared worker 
			$scope.mesibo = new MesiboWorker($scope);
		}

		$scope.mesiboNotify = $scope;

		//Initialize Mesibo
		if(!MESIBO_APP_ID || !getLoginToken()){
			alert("Invalid token or app-id. Check config.js");
			return;
		}

		$scope.isLoggedIn = true;
			$scope.mesibo = Mesibo.getInstance();
		$scope.mesibo.setAppName(MESIBO_APP_ID);
		$scope.mesibo.setCredentials(getLoginToken());
		$scope.mesibo.setListener($scope.mesiboNotify);
		$scope.mesibo.setDatabase("mesibodb", function(init){
			MesiboLog("setDatabase", init);

			if(!init){
				ErrorLog("setDatabase failed");
				return;
			}

			//Database initialized successfully

			//Initialize Application
			if(demo_app_name == "messenger"){
				MesiboLog("Init messenger");
				$scope.init_messenger();
			}

			if(demo_app_name == "popup"){
				//Contact synchronization is not required for popup
				$scope.is_shared = false;
				$scope.init_popup();
			}

		});

		$scope.mesibo.start();   

		if(demo_app_name == "shared-popup"){
			//Contact synchronization is not required for shared-popup
			$scope.is_shared = true;
			$scope.init_popup();
		}

		$scope.refresh();
	}

	onControllerReady();
	console.log("AppController loading done");
}]);


