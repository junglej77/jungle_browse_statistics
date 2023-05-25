(function ($) {
    'use strict';
    // 记录访客的所有浏览信息
    let ipInfo = jungle_browse_statistics
    // 发送Ajax请求到服务器端
    $.post(ipInfo.ajax_url, {
        action: 'user_cache',
        page_url: ipInfo.page_url,
        _ajax_nonce: ipInfo.nonce,
    });

})(jQuery);
