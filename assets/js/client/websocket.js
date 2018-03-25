//lancement serveur chat
function NotifServer(){
    notif = new WebSocket("ws://127.0.0.1:8080");

    notif.onmessage = function (event) {
        console.log("onmessage");
        console.log(event.data);
        $('.content').append('<p>'+ event.data +'</p>');

    };

    notif.onopen = function() {
        console.log("onopen");
    };

    notif.onerror = function(error) {
        console.log("onerror");
    };
}

NotifServer();