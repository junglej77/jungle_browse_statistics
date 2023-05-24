(function ($) {
    'use strict';
    let ipInfo = jungle_browse_statistics
    // 发送Ajax请求到服务器端
    $.post(ipInfo.ajax_url, {
        action: 'user_cache',
        _ajax_nonce: ipInfo.nonce,
    }, function (response) {
        // 处理响应
    });

})(jQuery);
