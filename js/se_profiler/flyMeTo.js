
if (window.jQuery) {
    jQuery(document).ready(function ($) {

        $("#fly-me-directly-button").click(function(){
            var type = $('input[name="fly-me-to-type"]:checked').val();
            var text = $('input[name="fly-me-to-text"]').val();
            var destination = $('input[name="fly-me-to-destination"]:checked').val();
            $.post('/profiler/flyMeTo/index', { type: type, text: text, destination: destination, directly: true }, function(response){
                debugger;
                if (destination == 1) {
                    location.href = response[0]['frontend-url'];
                }
            });
        });

        $("#fly-me-to-button").click(function(){
            $.post('/profiler/flyMeTo/index', { type: type, text: text, destination: destination, directly: false }, function(response){
                console.log(response.length);
                var $table = $("<table/>");
                var $header = $("<tr/>");
                var firstRound = true;
                for (var key in response) {
                    if (!response.hasOwnProperty(key)) continue;
                    var $body = $("<tr/>");
                    var obj = response[key];
                    for (var prop in obj) {
                        if(!obj.hasOwnProperty(prop)) continue;

                        if (firstRound) {
                            $header.append($("<th/>").text(prop));
                        }
                        if (prop == "frontend-link") {
                            $body.append($("<td/>").html($("<a />").attr("href", obj[prop]).text('View')));
                        } else if (prop == "backend-link") {
                            $body.append($("<td/>").html($("<a />").attr("href", obj[prop]).text('Edit')));
                        } else {
                            $body.append($("<td/>").text(obj[prop]));
                        }
                    }
                    if (firstRound) {
                        $table.append($header);
                        firstRound = false;
                    }
                    $table.append($body);
                }
                $("#fly-me-to-results").html($table);
            });
        });
    });
}
