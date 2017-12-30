$(function () {
    $(window).bind('hashchange', function () {
		var url = window.location.href.replace('index.php', 'register.php');
        if (url.indexOf('#') !== -1) {
            LoadActionSmooth(url.replace('/#/', '/'), $("#body-content"));
        }
    });
    if (window.location.href.indexOf('#/')>=0 && window.location.href.indexOf('.php')>=0 && window.location.href.indexOf('#/index.php')<0) {
        LoadActionSmooth(window.location.href.replace('/#/', '/'), $("#body-content"));
    }else {
		if(window.location.href.indexOf('?')>=0) {
			var params = window.location.href.substr(window.location.href.indexOf('?'));
			LoadAction('register.php' + params, $('#body-content'));
		}
        LoadAction('register.php', $('#body-content'));
	}
});

function LoadModal(url, optionalArg) {
	if(typeof optionalArg !== 'undefined') {
		$("#ModalContent").load(url, optionalArg);
	}else {
		$("#ModalContent").load(url);
	}
	$("#ModalContainer").fadeIn();
}
function CloseModal() {
	$("#ModalContainer").fadeOut();
	$("#ModalContent").empty();
}

function LoadMessage(message) {
	$("#ModalContent").html("<p>" + message + "</p>");
	$("#ModalContainer").fadeIn();
}

function decodeUrl(str) {
	var keyValuePairs = str.split('&');
	var json = {};
	for(var i=0,len = keyValuePairs.length,tmp,key,value;i <len;i++) {
		tmp = keyValuePairs[i].split('=');
		key = decodeURIComponent(tmp[0]);
		value = decodeURIComponent(tmp[1]);
		if(key.search(/\[\]$/) != -1) {
			tmp = key.replace(/\[\]$/,'');
			json[tmp] = json[tmp] || [];
			json[tmp].push(value);
		}
		else {
			json[key] = value;
		}
	}
}

function LoadAction(res, container) {
	$(container).load(res);
}
function LoadActionSmooth(res, container, args) {
	$(container).fadeOut(150, function() {
		$(container).load(res, args, function() {
			$(container).fadeIn(150);
		});
	});
}

function getQueryVariable(variable) {
	var query = window.location.hash.substring(window.location.hash.indexOf('?')+1);
	var vars = query.split("&");
	for (var i=0;i<vars.length;i++) {
		var pair = vars[i].split("=");
		if (pair[0] == variable) {
			return pair[1];
		}
	}
}
