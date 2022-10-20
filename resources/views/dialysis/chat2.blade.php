<!DOCTYPE html>
<html lang="en" >
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
        <title>Mesibo Messenger</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css" integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
        
        <script type="text/javascript">
            
            var MESIBO_ACCESS_TOKEN = "{{Auth::user()->mesibo}}"; 

            /* App ID used to create a user token. */
            var MESIBO_APP_ID = "com.patientcare";
        </script>


        <!--SCRIPTINCLUDESTART-->
        <script src="https://code.jquery.com/jquery-3.3.1.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.7.9/angular.min.js"></script>
        <script type="text/javascript" src="https://webrtc.github.io/adapter/adapter-latest.js"></script>
	    <script type="text/javascript" src="https://api.mesibo.com/mesibo.js" crossorigin="anonymous"></script> 
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
        <script type="text/javascript" src="{{asset('mesibo/mesibo/config.js')}}"></script>
        <script type="text/javascript" src="{{asset('mesibo/scripts/controller.js')}}"></script>
        <script type="text/javascript" src="{{asset('mesibo/scripts/ui.js')}}"></script>
        <script type="text/javascript" src="{{asset('mesibo/mesibo/utils.js')}}"></script>
        <script type="text/javascript" src="{{asset('mesibo/mesibo/calls.js')}}"></script>
        <script type="text/javascript" src="{{asset('mesibo/mesibo/files.js')}}"></script>
        <script type="text/javascript" src="{{asset('mesibo/mesibo/config.js')}}"></script>
        <link rel="stylesheet" href="{{asset('mesibo/styles/messenger.css')}}">
        <!--SCRIPTINCLUDEEND-->


    <style type="text/css">
        table.pickcontacts {
          font-family: arial, sans-serif;
          border-collapse: collapse;
          width: 100%;
        }

        table.pickcontacts td, table.pickcontacts th {
          opacity: .7;
          border: 1px solid #dddddd;
          text-align: left;
          padding: 8px;
        }

        table.pickcontacts td:hover {
          cursor: pointer;
          opacity: 1;
          background-color: #616161;
          color: white;
        }

        table.pickcontacts tr:nth-child(even) {
          background-color: #dddddd;
        }

    </style>

    <script type="text/javascript">
        // $( document ).ready(function() {
        //     $('table.pickcontacts td').click(function(){
        //         $('#contact-name').val($(this).data('username'));
        //         $('#contact-address').val($(this).data('username'));
        //     });
        // });
    </script>
    </head>
    <body ng-app="MesiboWeb" id="mesibowebapp" ng-controller="AppController" style="overflow: hidden; background-color: hsl(0, 0%, 70%);">
        <div style="max-width: 100%;  background-color: hsl(0, 0%, 70%);" onscroll="console.log('scroll fk app')">
            <div ng-cloak="isConnected" class="container-fluid" id="main-container" style="width: 75%; padding-left: 20px; padding-top: 20px; padding-bottom: 20px; background-color: hsl(0, 0%, 70%);">
                <div ng-show="isLoggedIn" class="row h-100" style=" margin: auto; background-color: white; overflow: hidden;" id="app-area">
                    <div class="col-10 col-sm-5 col-md-5 col-lg-4 col-xl-4 d-flex flex-column" id="chat-list-area">
                        <!-- Navbar -->
                        <div class="row d-flex flex-row align-items-center p-2" id="navbar">
                            <img ng-src ="@{{getUserPicture(getSelfProfile())}}" class="img-fluid rounded-circle mr-2" style="max-height:48px; cursor:pointer;" ng-click="showProfile(getSelfProfile())" onerror="imgError(this)" id="display-pic">
                            <div>
                                <div class="text-white font-weight-bold" id="username">{{Auth::user()->username}}</div>
                                <div class="text-white small">@{{connection_status}}</div>
                            </div>
                            <div class = "col-sm col-xs heading-compose  float-xs-right">
                                <i class="fa fa-plus text-white" aria-hidden="true" ng-click="showAvailableUsers()" style="cursor:pointer; float: right; padding-left: 2px; font-size: 10px;" title = "New Chat"></i>
                                <i class="fas fa-comment-alt text-white" aria-hidden="true" ng-click="showAvailableUsers()" style="cursor:pointer; float: right;" title="New Chat"></i>
                            </div>
                            <!--- <i class="fa fa-times text-white" aria-hidden="true" ng-click="toggleConnection()" style="cursor:pointer; float: right; padding-left: 2px; font-size: 10px; padding-top: 5px;" title = "Stop"></i> -->
                            <div class="nav-item dropdown ml-auto">
                                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v text-white"></i></a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="https://mesibo.com" >Mesibo Home</a>
                                    <a class="dropdown-item" href="https://mesibo.com/documentation" >Documentation</a>
                                    <a class="dropdown-item" href="https://github.com/mesibo/messenger-javascript" >Source Code</a>
                                    <a class="dropdown-item" href="#" ng-click = "logout();">Log Out</a>
                                </div>
                            </div>
                        </div>


                        <!-- Chat List = Left Side -->
                        <span ng-show="!(summarySession && summarySession.getMessages().length)" style="text-align: center"> <i style="color: grey">You have no messages </i></span>  
                        <div ng-repeat="u in summarySession.getMessages()" class="row" id="chat-list" style="overflow-y:auto;" >
                            <!-- ng-style="{'background-color': 'hsl(0, 0%, 90%)' -->
                            <div class="chat-list-item d-flex flex-row w-100 p-2 border-bottom" ng-click="generateMessageArea(getProfileFromMessage(u));" tabindex="0">
				    <div style="margin:0px;  padding 0px; position: relative;">
				    <span style="color: #0f0; position: absolute; left: 35px; top: 25px; padding: 0px; font-size: 25px; width:25px; height:25px;" ng-show="isOnlineFromMessage(u)">●</span>
				    <img ng-show="hasPicture(u)" ng-src= '@{{getPictureFromMessage(u)}}' ng-click="showProfileFromMessage(u)" onerror="imgError(this)" class="img-fluid rounded-circle mr-2" style="height:50px;">
				    <div ng-show="!hasPicture(u)" ng-click="showProfileFromMessage(u)" onerror="imgError(this)" class="img-fluid rounded-circle mr-2" style="height:50px; width:50px; color: White; font-size: 35px; vertical-align:middle; text-align:center;" ng-style = "{'background':getLetterColor(u)}">@{{getFirstLetter(u)}}</div>
				    </div>

                                <div class="w-50">
                                    <div class="name">@{{getNameFromMessage(u)}}</div>
                                    <div class="small last-message" ng-attr-title="@{{getUserLastMessage(u)}}">
                                        <i ng-class="getMessageStatusClass(u)" ng-style = "{'color':getMessageStatusColor(u)}"></i>
                                        <i ng-class="getFileIcon(u)" style="color: grey;"></i>
					<span ng-style = "{'color':getLastMessageColor(u)}">@{{getUserLastMessage(u)}}</span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 text-right">
                                    <div class="small time">@{{getUserLastMessageTime(u)}}</div>
                                    <div class="badge badge-success badge-pill small" id="unread_count_@{{$index}}">@{{getUserUnreadCount(u, $index)}}</div>
                                </div>
                            </div>
                        </div>


                        <div ng-show="users_panel_show" class="d-flex flex-column w-100 h-100" id="available-users-panel" ng-style = "{'left': users_panel_show ? 0:'-110%'}" style="background: white;">
                            <div class="row d-flex flex-row align-items-center p-2 pr-5 m-0" style="background:#00868b; min-height:72px;">
                                <i class="fas fa-arrow-left p-2 mx-2 my-1 text-white" style="cursor: pointer; align-self: left;" ng-click="hideAvailableUsers()" id="hideuser"></i>
                                <div class="text-white font-weight-bold">Select a Contact to Chat</div>
				<div class="ml-auto">
                                <i class="fa fa-user-plus" aria-hidden="true" style="color: white; cursor:pointer; float: right; margin-left: 50%;" title="Add Contact" ng-click="showContactForm()"></i>
				</div>
                            </div>
                            <div class="d-flex flex-column" style="overflow-x: hidden;">
                                <div class="row" id="available-userlist" style=" background:white;">
                                    <span ng-show="!hasContacts()" style="text-align: center"> <i style="color: grey; padding-left: 70px; margin-top: 10px;">You have no existing contacts</i></span>  
                                    <div ng-repeat="u in getContacts() track by $index" class="chat-list-item d-flex flex-row w-100 p-2 border-bottom " style="margin-left: 15px" ng-click="hideAvailableUsers(); generateMessageArea(u);">
                                        <img ng-src='@{{getUserPicture(u)}}' class="img-fluid rounded-circle mr-2" style="height:50px;" onerror="imgError(this)" ng-click="showProfile(u)">
                                        <div class="w-50">
                                            <div class="name">@{{getUserName(u)}}</div>
                                            <div class="small last-message" ng-attr-title="@{{getUserStatus(u)}}">@{{getUserStatus(u)}}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Display Profile-->
                        <div ng-show="display_profile" class="d-flex flex-column w-100 h-100" id="available-users-panel" ng-style = "{'left': display_profile ? 0:'-110%'}" style="background: white;">
                            <div class="row d-flex flex-row align-items-center p-2 m-0" style="background:#00868b; min-height:72px;">
                                <i class="fas fa-arrow-left p-2 mx-2 my-1 text-white" style="cursor: pointer; align-self: left;" ng-click="hideProfileSettings()"></i>
                                <div class="text-white font-weight-bold">Profile</div>
                            </div>
                            <div class="d-flex flex-column" style="overflow:auto;">
                              <img alt="Profile Photo" class="img-fluid" id="profile-pic" ng-src="@{{getProfileImage(display_profile)}}">
			      <!-- <img alt="Profile Photo" class="img-fluid rounded-circle my-5 justify-self-center mx-auto" id="profile-pic" ng-src="@{{getUserPicture(display_profile)}}"> -->
                              <input type="file" id="profile-pic-input" class="d-none">
                              <div class="bg-white px-2 py-1">
                                <div class="text-muted mb-2 border-bottom"><label for="input-name"><h3>@{{getUserName(display_profile)}}</h3></label></div>
                                <!-- <input type="text" name="name" id="input-name" class="w-100 border-0 py-2 profile-input"> -->
                              </div>
                              <!-- <div class="text-muted p-3 small">
                                This name will be visible to your Mesibo contacts.
                              </div> -->
                              <div class="bg-white px-2 py-1">
                                <div class="text-muted mb-2"><label for="input-about">Status</label></div>
                                <div class="small border-bottom">@{{display_profile.getStatus()}}</div>

                                <div class="text-muted mb-2" ng-if="display_profile.getAddress()"><label for="input-about">Phone</label></div>
                                <div class="small border-bottom" ng-if="display_profile.getAddress()">@{{display_profile.getAddress()}}</div>
                                <!-- <input type="text" name="name" id="input-about" value="" class="w-100 border-0 py-2 profile-input"> -->
                              </div>
                                                      
                              <span ng-if="membersList.length" class="px-2" style="color: grey">@{{membersList.length}} Members</span>
                              <div class="d-flex flex-column" style="overflow-x: hidden;" ng-show="membersList.length">

                                <div class="row" id="group-members" style=" background:white;">                                   
                                    <div ng-repeat="u in membersList" class="chat-list-item d-flex flex-row w-100 p-2 border-bottom " style="margin-left: 15px" >
                                        <img ng-src="@{{getUserPicture(u['member'])}}" class="img-fluid rounded-circle mr-2" style="height:50px;" onerror="imgError(this)" ng-click="showProfile(u['member'])">
                                        <div class="w-50" ng-click="hideAvailableUsers(); generateMessageArea(u['member']);">
                                            <div class="name">@{{getUserName(u['member'])}}</div>
                                            <div class="small last-message">@{{getMemberType(u['type'])}}</div>
                                        </div>
                                    </div>
                                </div>
                              </div>

                            </div>
                        </div>

                        <!-- Display Forward List-->
                        <div ng-show="forward_message" class="d-flex flex-column w-100 h-100" id="available-users-panel" ng-style = "{'left': forward_message ? 0:'-110%'}" style="background: white;">
                            <div class="row d-flex flex-row align-items-center p-2 m-0" style="background:#00868b; min-height:72px;">
                                <i class="fas fa-arrow-left p-2 mx-2 my-1 text-white" style="cursor: pointer; align-self: left;" ng-click="hideForwardList(); "></i>
                                <div class="text-white font-weight-bold">Forward Message to..</div>
                            </div>
                            <div class="d-flex flex-column" style="overflow-x: hidden;">
                                <div class="row" id="available-userlist" style=" background:white;">
                                    <span ng-show="hasContacts()" style="text-align: center"> <i style="color: grey; padding-left: 70px; margin-top: 10px;">You have no existing contacts</i></span>  
                                    <div ng-repeat="u in getContacts() track by $index" class="chat-list-item d-flex flex-row w-100 p-2 border-bottom " style="margin-left: 15px" ng-click="forwardMessageTo(u); hideForwardList()">
                                        <img ng-src='@{{getUserPicture(u)}}' class="img-fluid rounded-circle mr-2" style="height:50px;" onerror="imgError(this)" ng-click="showProfile(u)">
                                        <div class="w-50">
                                            <div class="name">@{{getUserName(u)}}</div>
                                            <div class="small last-message" ng-attr-title="getUserStatus(u)">@{{getUserStatus(u)}}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- Message Area -->
                    <div class="d-none d-sm-flex flex-column col-12 col-sm-7 col-md-7 col-lg-8 col-xl-8 p-0 h-100" id="message-area" >
                        <div ng-class = "{'d-none w-100 h-100 overlay': message_area_show, 'w-100 h-100 overlay': !message_area_show}"></div>
                        <!-- Navbar -->
                        <div class="row d-flex flex-row align-items-center p-2 m-0 w-100" id="navbar">
                            <div class="d-block d-sm-none">
                                <i class="fas fa-arrow-left p-2 mr-2 text-white" style="font-size: 1.5rem; cursor: pointer;"></i>
                            </div>
                            <a href="#"><img ng-src='@{{getUserPicture(selected_user)}}' ng-click="showProfile(selected_user)" class="img-fluid rounded-circle mr-2" style="height:50px;" id="pic" onerror="imgError(this)" ></a>
                            <div class="d-flex flex-column">
                                <div class="text-white font-weight-bold" id="name">@{{getUserName(selected_user)}}</div>
                                <div class="text-white small" id="details" style="cursor: default;" ng-show="getUserActivity(selected_user)">@{{ getUserActivity(selected_user)}}</div> 
                            </div>
                            <div class="d-flex flex-row align-items-center ml-auto">
                                <a href="#"><i ng-click="clickUploadFile()" id="file-upload" class="fas fa-paperclip mx-2 text-white d-none d-md-block"></i></a>
                                <a href="#"><i ng-show="selected_user.getGroupId()==0" id="video-button" class="fas fa-video mx-2 text-white" ng-click="makeVideoCall()"></i></a>
                                <a href="#"><i ng-show="selected_user.getGroupId()==0" id="audio-button" class="fas fa-phone fa-flip-horizontal mx-2 text-white" ng-click="makeVoiceCall()"></i></a>
                                <input id="upload" type="file" onchange="angular.element(this).scope().onFileSelect(this)"
                                    style="display: none;">
                                <a href="#"><i ng-click="openPictureRecorder()" id="capture-photo" class="fas fa-camera mx-2 text-white d-none d-md-block"></i></a>
                                <a href="#"><i ng-click="openAudioRecorder()" id="capture-audio" class="fas fa-microphone mx-2 text-white d-none d-md-block"></i></a>
                            </div>
                        </div>
                        <!-- Messages -->
                        <div class="message-list" id="messages" style="overflow: auto;">
                            <img ng-show="!(selected_user.getAddress() || selected_user.getGroupId() || summarySession && summarySession.getMessages().length)" style="position: fixed; z-index: 100; width: 100px; left: 62%; top: 40%;" src="{{asset('mesibo/images/paper-plane.png')}}"></img>
                            <button ng-show="!(selected_user.getAddress() || selected_user.getGroupId() || summarySession && summarySession.getMessages().length)" ng-click="showAvailableUsers()" type="button" class="btn btn-info" style="background-color: #00868b; position: fixed;z-index: 100; left: 58%;top:60%;">Open a new chat &nbsp;  
                            <i class="fa fa-plus text-white" aria-hidden="true" ng-click="showAvailableUsers()" style="cursor:pointer; float: right; padding-left: 2px; font-size: 10px; padding-top: 5px;" title = "New Chat"></i>
                            <i class="fas fa-comment-alt text-white" aria-hidden="true" ng-click="showAvailableUsers()" style="cursor:pointer; float: right;padding-top: 5px;" title="New Chat"></i>
                            </button>
                            <div ng-repeat="m in getMessages()" on-finish-render="onMessagesRendered" ng-show="m.message || isFileMsg(m)" ng-class="{'align-self-end self p-1 mx-4 rounded bg-white shadow-sm message-item': isSent(m), 'align-self-start p-1 mx-4 rounded bg-white shadow-sm message-item': isReceived(m)}" style="margin-top: 4px">
                                <div class="small font-weight-bold text-primary" ng-style = "{'display': m.groupid && m.peer != selected_user.getAddress()? 'block':'none'}">
                                    @{{getSenderNameFromMessage(m)}}
                                </div>
                                <div class="options" style="z-index: 100">
                                    <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fas fa-angle-down text-muted px-2"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" style="cursor: pointer;" ng-click="showForwardList(m)">Forward</a>
                                        <a class="dropdown-item" style="cursor: pointer;" ng-click="deleteSelectedMessage(m)">Delete</a>
                                        <a class="dropdown-item" style="cursor: pointer;" ng-show="isFailedMessage(m)" ng-click="resendSelectedMessage(m)">Resend</a> 
                                    </div>
                                </div>
                                <!-- Link Preview Start -->
                                <a ng-show="@{{isUrlMsg(m)}}" ng-href="@{{m.launchurl}}" style="text-decoration: none;color: black;" target="_blank" >
                                    <div ng-show="@{{isUrlMsg(m)}}" class="d-flex flex-row">
                                        <div>
                                            <img ng-src="https://mesibo.com/assets/images/mesibo-favicon.png" style="height:100px;width:100px"/ >
                                        </div>
                                        <div style="background-color:hsl(0,0%,0%, 0.1); max-width: 500px;max-height: 100px; min-width: 200px">
                                            <div style="font-size: 15px;font-weight: 600;padding: 5px 5px 5px 5px;">@{{m.title}}</div>
                                            <div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: 15px;padding: 0px 5px 0px 5px;">Description
                                            </div>
                                            <p style="color:grey;font-size: 15px; padding: 0px 5px 0px 5px;">@{{hostnameFromUrl(m.launchurl)}}</p>
                                        </div>
                                    </div>
                                </a>
                                <!-- Link Preview End -->
                                <!-- File Preview Start -->
                                <!-- Check m.fileurl!="" -->
                                <div ng-show='@{{isFileMsg(m)}}' ng-class = "{'': isFileMsg(m), 'd-flex flex-row': !isFileMsg(m)}">
                                    <a ng-href="@{{m.launchurl? m.launchurl: m.fileurl}}" target="_blank" >
                                        <div ng-show='isImageMsg(m)'><img imageonload ng-src= "@{{m.fileurl}}" ng-style="{'height': MAX_MEDIA_HEIGHT, 'max-width': MAX_MEDIA_WIDTH, 'min-width': MIN_MEDIA_WIDTH}" onload="adjustImageDims(this)" style="cursor:pointer;"/>
                                        </div>
                                        <!-- Show URL Custom Thumbnail -->
                                        <div ng-show='isUrlMsg(m)' class= "image-holder"><img ng-src= "https://mesibo.com/assets/images/mesibo-favicon.png" style="cursor:pointer;"/>
                                        </div>
                                        <div ng-show='isVideoMsg(m)'>
                                            <video videoonload controls="controls" ng-src= "@{{m.fileurl}}" ng-style="{'height': MAX_MEDIA_HEIGHT, 'max-width': MAX_MEDIA_WIDTH, 'min-width': MIN_MEDIA_WIDTH}" onloadedmetadata="adjustVideoDims(this)" style="cursor:pointer;"/>
                                        </div>
                                        <div ng-show='isAudioMsg(m)' class= "image-holder">
                                            <br>
                                            <audio controls="controls" ng-src= "@{{m.fileurl}}" style="cursor:pointer; object-fit: cover; width: 250px;"/>
                                        </div>
                                        <div ng-show='isOtherMsg(m)' class= "image-holder"><i class="fas fa-file" style="font-size: 30px;"></i><span style="font-size: 15px; padding-left: 5px; text-overflow: ellipsis; max-width: 100px; color: grey;text-decoration: none;">@{{getFileName(m)}}</span>
                                        </div>                                    
                                    </a>
                                </div>
                                <!-- File Preview End -->
                                <div class="d-flex justify-content-between">
                                    <div ng-show="m.message" class="body m-1 mr-2" style="cursor: default;">@{{m.message}}</div>
                                    <div  class="time ml-auto small text-right flex-shrink-0 align-self-end text-muted" style="width:75px; cursor: default;">
                                        @{{m.date.time}}
                                        <i ng-class= "getMessageStatusClass(m)" ng-style = "{'color':getMessageStatusColor(m) }" ></i>
                                    </div>
                                </div>
                            </div>
                            <!-- Anchor to Scroll -->
                            <span id="messages_end">&nbsp;</span>
                        </div>
                        <!--Loading ANIMATION-->
                        <!--END LOADING ANIMATION-->
                        <!-- Real-Time Link Preview Start -->
                        <a ng-show="link_preview" style="text-decoration: none;color: black;background-color: hsl(0, 0%, 95%); padding: 5px 5px 0px 65px" target="_blank" >
                            <div class="d-flex flex-row">
                                <div>
                                    <img ng-src="@{{link_preview.image}}" style="height:95px;width:95px; background-color: white"/>
                                </div>
                                <div style="background-color:hsl(0,0%,0%, 0.1);max-height: 95px;width: 550px">
                                    <div style="font-size: 15px;font-weight: 600;padding: 5px 5px 2px 5px;">@{{link_preview.title}}</div>
                                    <div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: 15px;padding: 0px 5px 1px 5px;">@{{link_preview.description}}</div>
                                    <p style="color:grey;font-size: 15px; padding: 0px 1px 3px 5px;">@{{link_preview.hostname}}
                                    </p>
                                </div>
                                <div>
                                    <!-- Close -->
                                    <i class="fas fa-times" ng-click="closeLinkPreview()" style="padding-left: 5px; padding-right: 5px; color: grey; cursor:pointer;"></i>
                                </div>
                            </div>
                        </a>
                        <!--Real-Time Link Preview End -->
                        <!-- Input -->
                        <div ng-class = "{'d-flex justify-self-end align-items-center flex-row': message_area_show, 'd-none justify-self-end align-items-center flex-row': !message_area_show}" id="input-area" >
                            <a href="#" style="display: none;"><i class="far fa-smile text-muted px-3" style="font-size:1.5rem;"></i></a>
                            <input style="margin-left: 10px;" ng-model="input_message_text" ng-change="isLinkPreview && inputTextChanged()" ng-keydown="onKeydown($event)" type="text" name="message" id="input" placeholder="Type a message" class="flex-grow-1 border-0 px-3 py-2 my-3 rounded shadow-sm">
                            <i class="fas fa-paper-plane text-muted px-3" style="cursor:pointer;" ng-click="sendMessage()"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Login Modal Start-->
                <div id="ModalLoginForm" class="modal fade">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title">Login</h1>
                            </div>
                            <div class="modal-body">
                                <form role="form" method="POST" action="">
                                    <input type="hidden" name="_token" value="">
                                    <div class="form-group">
                                        <label class="control-label">Phone</label>                                                                             
                                        <span style="color: grey; font-size: 12px;">
                                          <br>
                                          Enter phone number starting with country code (Example +91XXXXXXXXXX) to login with an OTP (Use 123456)                                         
                                        </span>
                                        <div>
                                            <input type="phone" class="form-control input-lg" id="phone" name="phone" value="">
                                        </div>
                                        <span style="color: grey; font-size: 12px;">
                                          You can also login by <a href="https://mesibo.com/documentation/tutorials/get-started/first-app/#create-users-endpoints"> creating a user in the console or using backend API</a> and setting the token in <strong>config.js</strong> 
                                        </span>
                                    </div>
                                    <div class="form-group" id="otp-input">
                                        <label class="control-label">One Time Password (OTP)</label>

                                        <i style="color: grey; font-size: 15px;">
                                          <br>
                                          Use OTP: 123456
                                        </i>
                                        <div>
                                            <input type="number"  class="form-control input-lg" id="otp" name="password">
                                        </div>
                                    </div>
                                    <div class="form-group" style="text-align: center;">
                                        <div>
						<button type="button" class="btn btn-success" ng-click="getToken()">Login</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->
                <!--Login Modal End -->


                <!-- Add Contact Modal Start-->
                <div id="ModalContactForm" class="modal fade">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title">Add a Contact</h3>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form role="form" method="POST" action="">                                    
                                    <div class="form-group">
                                        <label class="control-label">Name</label>                                        
                                        <div>
                                            <input ng-model="new_contact_name" type="name" class="form-control input-lg" id="contact-name" value="">
                                        </div>

                                        <div>
                                            <input ng-model="new_contact_phone" type="phone" class="form-control input-lg" id="contact-address" value="" style="display: none;">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <table class="pickcontacts">
                                          <tr>
                                            <th>Name</th>
                                          </tr>
                                          <tr>
                                            <td data-username='Alfreds'>Alfreds Futterkiste</td>
                                          </tr>
                                          <tr>
                                            <td data-username='Centro'>Centro comercial Moctezuma</td>
                                          </tr>
                                          <tr>
                                            <td data-username='Ernst'>Ernst Handel</td>
                                          </tr>
                                          <tr>
                                            <td data-username='Island'>Island Trading</td>
                                          </tr>
                                          <tr>
                                            <td data-username='Bacchus'>Laughing Bacchus Winecellars</td>
                                          </tr>
                                          <tr>
                                            <td data-username='Alimentari'>Magazzini Alimentari Riuniti</td>
                                          </tr>
                                        </table>
                                    </div>
                                    <div class="form-group">
                                        <div style="width: 100%; text-align: center;">
                                            <button type="button" class="btn btn-success" ng-click="addContact()">Add
                                              <i class="fa fa-user-plus" aria-hidden="true" style="color: white; padding-left: 5px;" title="Add Contact"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->
                <!--Add Contact Modal End -->

                <!-- Prompt Add Contact Modal Start-->
  <!--               <div id="promptAddContact" class="modal fade show" style="z-index: 1040; display: block;" aria-modal="true" style="display: hidden">
                  <div class="modal-dialog modal-dialog-centered" role="document">
                      <div class="modal-content">
                          <div class="modal-header">
                              <h5 class="modal-title mx-auto" style="text-align: center;width: 100%">No existing contacts..</h5>
                              <button type="button" class="close" data-dismiss="modal" ng-click="closePromptAddContact()">×</button>
                          </div>
                          <div class="modal-body">
                              <form role="form" action="" class="ng-pristine ng-valid">                          
                                  <div class="form-group">
                                      <div style="text-align: center;">
                                          <button type="button" class="btn btn-success" ng-click="addContact()">Add Contact</button>
                                          <button type="button" class="btn btn-danger" data-dismiss="modal" ng-click="closePromptAddContact()">Cancel</button>
                                      </div>
                                      
                                  </div>
                              </form>            
                          </div>
                      </div>
                  </div>
                </div> -->
                <!--Prompt Add Contact Modal End -->

                <!--Logout Modal -->
                <div class="container">
                    <div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true" role="dialog" style="width: 100%;height: 100%; background-color: #00868b">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h7 class="modal-title" style="width:100% ;text-align: center;">You have been logged-out</h7>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" style="width:100% ;text-align: center;" data-dismiss="modal" onclick="location.reload();">Login</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="container">
                    <div ng-show="is_answer_call" class="modal fade" id="answerModal" tabindex="-1" role="dialog" aria-labelledby="answerModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="answerModalLabel">Incoming Call</h5>
                                    <button type="button" class="close" data-dismiss="modal" ng-click="HangupCall();" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" id="ansBody">@{{call_alert_message}}</div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal" ng-click="hangupCall();">Hang-up</button>
                                    <button type="button" class="btn btn-success" data-dismiss="modal" ng-click="answerCall();">Answer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container">
                    <div ng-show="is_video_call" class="modal fade" id="videoModal" tabindex="-1" role="dialog" aria-labelledby="videoModalLabel" aria-hidden="true" style="background-color: #00868b">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h6 class="modal-title" id="videoModalLabel">
                                    Mesibo Video Call </h5>
                                    <button type="button" class="btn btn-info" id="vcstatus" style="float:center;">@{{call_status}} </button>
                                    <button type="button" class="btn btn-danger" data-dismiss="modal" ng-click="hangupVideoCall();">Hang-up
                                    </button>
                                </div>
                                <div class="modal-body" id="videoAnsBody" style="background-color: grey">
                                    <div id="videoHolder"style="transition: height 450ms ease 0s; height: 400px;">
                                        <div class="remote-video-holder">
                                            <video id="remoteVideo" playsinline autoplay></video>
                                        </div>
                                        <div class="local-video-holder">
                                            <video class="local-video" id="localVideo" playsinline autoplay muted ></video>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container">
                    <div ng-show="is_voice_call" class="modal fade" id="voiceModal" tabindex="-1" role="dialog" aria-labelledby="voiceModalLabel" aria-hidden="true" style="background-color: #00868b">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="voiceModalLabel">Mesibo Voice Call </h5>
                                    <button type="button" class="btn btn-info" id="acstatus" style="float:center;">@{{call_status}} </button>
                                    <button type="button" class="btn btn-danger" data-dismiss="modal" ng-click="hangupAudioCall();">Hang-up</button>
                                    </button>
                                </div>
                                <div class="modal-body" id="audioAnsBody" style="visibility:hidden; height:5px;">
                                    <audio id="audioPlayer" autoplay="autoplay" controls="controls" style="visibility:hidden; height:5px;"></audio>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <div class="container">
                <div class="modal fade" id="fileModal" tabindex="-1" role="dialog" aria-labelledby="fileModalLabel" aria-hidden="true" >
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header" id="file-preview-header">
                                <h7 class="modal-title" id="fileModalLabel">Selected File: </h7>
                                <button type="button" class="close" data-dismiss="modal" ng-click="closeFilePreview();" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" id="fileBody">
                                <div  id="preview_container" style="text-align: center;">
                                        <img id ='image-preview' 
                                        style="display: none; object-fit: scale-down; max-height: 240px; max-width: 320px;" src="">
                                        <video id ='video-preview' controls="controls" preload="true" style="display: none; object-fit: scale-down; max-height: 240px; max-width: 320px;"></video>
                                    <!-- Image/video/Docs custom holder elements -->
                                </div>
                                <div class="justify-self-end align-items-center flex-row d-flex" id="file-preview-footer" style="width: 100%; text-align: center;">                               
                                    <input ng-model="input_file_caption" type="text" name="caption" id="file-caption" placeholder="Add caption" class="flex-grow-1 border-0 px-3 py-2 my-3 rounded shadow-sm">
                                    <i class="fas fa-paper-plane text-muted px-3" style="cursor:pointer;" ng-click="closeFilePreview();sendFile()"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
              </div>

                <!--=============== Recorder modal start===============-->
                <div class="container">
                    <div class="modal fade" id="recorderModal" tabindex="-1" role="dialog" aria-labelledby="recorderModalLabel" aria-hidden="true" >
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h7 class="modal-title" id="recorderModalLabel">Recorder</h7>
                                    <button type="button" class="close" data-dismiss="modal" ng-click="closeRecorder();" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" id="recordBody">
                                    <section class="main-controls">
                                        <canvas class="visualizer" height="60px" style="width: 100%; display: none;"></canvas>
                                        <div id ="recording_area" class="camera" style="display: none; text-align: center; margin-bottom: 5px;">
                                            <video id="capture-video">Video stream not available.</video>
                                            <img style="display: none;" id="captured-photo" alt="The screen capture will appear in this box.">
                                            <br>
                                            <button id="photo_button" class="btn btn-success"> 
                                            <i class="fas fa-camera"></i> &nbsp; Take Photo 
                                            </button>
                                        </div>
                                        <div id="buttons" style="text-align: center;">
                                            <button class="record btn btn-default" style="display: none;">
                                            <i class="fa fa-circle"></i>&nbsp; Record
                                            </button>
                                            <button class="record btn btn-default stop" style="display: none;">
                                            <i class="fas fa-stop"></i>&nbsp; Stop </span>
                                            </button>
                                        </div>
                                    </section>
                                    <section class="sound-clips" style="display: none;">
                                    </section>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--=============== Recorder modal End===============-->
            </div>
        </div>
        </div>      
        <script type="text/javascript"> 
            	  function launchMessenger(){
                   angular.element(document.getElementById('mesibowebapp')).scope().initMesibo('messenger'); 
                  }
            
                  $(document).ready(function(){
			  console.log("doc ready");
                       //launchMessenger();
                       //loadLoginWindow();
                  });

		function onControllerReady() {
                       launchMessenger();

		}
            
                  
        </script> 
    </body>
</html>
