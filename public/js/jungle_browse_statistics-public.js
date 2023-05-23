import '/utils.js'
(function ($) {
	'use strict';
	var cookieName = 'jungle_browse_statistics_uid';
	var uid = getCookie(cookieName);

	// 如果不存在这个cookie，则生成一个新的uid并保存到cookie中
	if (!uid) {
		uid = generateUid();
		setCookie(cookieName, uid, 365);
	}

	// 发送Ajax请求到服务器端
	console.log(jungle_browse_statistics.nonce);
	$.post(jungle_browse_statistics.ajax_url, {
		action: 'user_cache',
		ip_address: jungle_browse_statistics.ip_address,
		location: jungle_browse_statistics.location,
		uid: uid,
		_ajax_nonce: jungle_browse_statistics.nonce,
	}, function (response) {
		// 处理响应
	});

	function generateUid() {
		return Math.random().toString(36).substr(2, 9);
	}

	function setCookie(name, value, days) {
		var expires = "";
		if (days) {
			var date = new Date();
			date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
			expires = "; expires=" + date.toUTCString();
		}
		document.cookie = name + "=" + (value || "") + expires + "; path=/";
	}

	function getCookie(name) {
		var nameEQ = name + "=";
		var ca = document.cookie.split(';');
		for (var i = 0; i < ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) == ' ') c = c.substring(1, c.length);
			if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
		}
		return null;
	}
})(jQuery);
