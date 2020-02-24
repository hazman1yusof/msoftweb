
	$(document).ready(function () {

		function getQueryVariable(variable) {
			var query = window.location.search.substring(1);
			var parms = query.split('&');
			for (var i = 0; i < parms.length; i++) {
				var pos = parms[i].indexOf('=');
				if (pos > 0 && variable == parms[i].substring(0, pos)) {
					return parms[i].substring(pos + 1);;
				}
			}
			return "";
		}

		getQueryVariable("name_show, newic_show, sex_show, age_show, race_show");

		$(function () {
			$('#name_show').text(getQueryVariable('name_show'))
			$('#newic_show').text(getQueryVariable('newic_show'))
			$('#sex_show').text(getQueryVariable('sex_show'))
			$('#age_show').text(getQueryVariable('age_show'))
			$('#race_show').text(getQueryVariable('race_show'))
		});

		
	});