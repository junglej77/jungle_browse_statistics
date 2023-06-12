import utils from "./utils";
(function ($) {
    'use strict';
    var ws = new WebSocket('ws://127.0.0.1:8088');
    let ipInfo = jungle_browse_statistics

    ws.onopen = () => {
        // 连接成功后发送消息给前端
        ws.send(utils.getCookie('cache_ip') + '-' + ipInfo.page_url);
    };

    ws.onclose = (event) => {
        console.log('WebSocket连接已关闭');
    };

    ws.onerror = (error) => {
        console.log('WebSocket连接错误:', error);
    };

    ws.onmessage = (event) => {
        console.log('接收到服务器消息:', event.data);
    };
    // ws.initWebSocket()
    // 记录访客的所有浏览信息
    // 发送Ajax请求到服务器端
    $.post(ipInfo.ajax_url, {
        action: 'user_cache',
        page_url: ipInfo.page_url,
        _ajax_nonce: ipInfo.nonce,
    });

    window.requestIdleCallback = window.requestIdleCallback || function (callback) {
        let start = Date.now();
        return setTimeout(function () {
            callback({
                didTimeout: false,
                timeRemaining: function () {
                    return Math.max(0, 50 - (Date.now() - start));
                }
            });
        }, 1);
    }

    window.cancelIdleCallback = window.cancelIdleCallback || function (id) {
        clearTimeout(id);
    }

    function sendData() {
        let userIsActive = false;
        let timeoutId;
        /*****监听用户一切可能的行为， */
        // 1. 为了避免用户正在活跃中，突然断网（这种类似的用户行为）所以在活跃中的的用户，
        function resetTimer(action) {
            // if (action || action.type == 'click') {
            //     ws.send(action.type + ': ' + utils.getCurentTime());
            // }
            clearTimeout(timeoutId);
            userIsActive = true;
            timeoutId = setTimeout(() => {
                userIsActive = false;
                ws.send('用户没操作啦：' + utils.getCurentTime());
            }, 4000);
        }

        window.addEventListener('click', resetTimer);
        window.addEventListener('mousemove', resetTimer);
        window.addEventListener('keydown', resetTimer);
        window.addEventListener('scroll', resetTimer);
        document.addEventListener('visibilitychange', function () {
            if (document.hidden) {
                ws.send('离开页面：' + ipInfo.page_url);
            } else {
                ws.send('进入页面：' + ipInfo.page_url);
            }
        });

        resetTimer();  // Start the timer
    }

    function scheduleDataSend() {
        // 空闲的时候开始执行，不占用客户一切资源，
        window.requestIdleCallback(() => {
            sendData();
        });
    }
    scheduleDataSend();
})(jQuery);