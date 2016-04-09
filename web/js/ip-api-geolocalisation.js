$(document).ready(function() {
    $('#btn-geo').on('click', function(event){
        event.preventDefault();
        event.stopPropagation();
        $.getJSON('http://ip-api.com/json').done (function(location){
            $('#search_form_city').val(location.city);
        });
    });
});