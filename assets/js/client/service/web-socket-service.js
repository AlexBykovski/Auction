(function(appAuction) {
    'use strict';

    appAuction.factory('WebSocketService', [function() {
        var socket = new WebSocket("ws://127.0.0.1:8080");

        socket.onopen = function() {
            console.log("Соединение установлено.");
        };

        socket.onclose = function(event) {
            if (event.wasClean) {
                console.log('Соединение закрыто чисто');
            } else {
                console.log('Обрыв соединения'); // например, "убит" процесс сервера
            }
            console.log('Код: ' + event.code + ' причина: ' + event.reason);
        };

        socket.onmessage = function(event) {
            console.log("Получены данные " + event.data);
        };

        socket.onerror = function(error) {
            console.log("Ошибка " + error.message);
        };

        return {
            send: function(message) {
                socket.send(JSON.stringify(message));
            }
        };
    }]);

})(window.appAuction);