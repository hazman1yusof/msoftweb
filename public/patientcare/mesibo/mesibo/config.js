// config.js

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

/* Refer following tutorial and API documentation to know how to create a user token
 * https://mesibo.com/documentation/tutorials/first-app/
 *
 * Note, that if you are using logging in with your phone, Mesibo will generate the token.
 * In that case, there is no need to configure token here
 * 
 */


/* If you are hosting Mesibo backend on your own server, change this accordingly.
 * Refer https://github.com/mesibo/messenger-app-backend
 */
var MESSENGER_API_URL = "https://messenger.mesibo.com";

/* Default images */
var MESIBO_DEFAULT_PROFILE_IMAGE = "./mesibo/images/profile/default-profile-icon.jpg";
var MESIBO_DEFAULT_GROUP_IMAGE = "./mesibo/images/profile/default-group-icon.jpg";

/************************ Messenger Config Start *****************************/

/* Toggle for synchronizing messages
*  See https://mesibo.com/documentation/tutorials/get-started/synchronization/
*/
var isMessageSync = false;

/*Optional link preview*/
var isLinkPreview = false; //Set to false if link preview not required
var LINK_PREVIEW_SERVICE = "http://api.linkpreview.net/";
var LINK_PREVIEW_KEY = ""; // Access Key
var LINK_DEFAULT_IMAGE = "./mesibo/images/file/default-link-icon.jpg"

/************************ Messenger Config End *****************************/

/************************ Popup Config Start *****************************/

/* A destination where the popup demo app will send message or make calls */
var POPUP_DESTINATION_USER = "<dest user>" 

/************************ Popup Config End *****************************/


/* Debug Mode Configuration */
isDebug = true ;// toggle this to turn on / off for global control
if (isDebug) var MesiboLog = console.log.bind(window.console);
else var MesiboLog = function() {}

var ErrorLog = console.log.bind(window.console);

function saveLoginToken(token){
	localStorage.setItem("MESIBO_MESSENGER_TOKEN", token);
	return 0;
}

function deleteTokenInStorage(){
	localStorage.removeItem("MESIBO_MESSENGER_TOKEN");
}

function getLoginToken(){
	if(MESIBO_ACCESS_TOKEN && MESIBO_ACCESS_TOKEN.length > 16)
		return MESIBO_ACCESS_TOKEN;

	var token = localStorage.getItem("MESIBO_MESSENGER_TOKEN");
	if(token && token.length > 16) return token;

	return null;
}

