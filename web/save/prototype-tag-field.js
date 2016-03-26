$(document).ready(function() {
    // Supprimer le text des label sur les checkbox public ect..
    $('.material-switch label').text('');

    $('#add-tag').on('click', function(event){

        event.preventDefault();
        event.stopPropagation();

        var $prototypeTag = $('#prototype-tag').clone();

        console.log($prototypeTag.children().addClass('input-group'));
        $prototypeTag = $($prototypeTag.html().replace(/__name__/g, $('input.tag').length));

        var $linkDelete = $('<span class="input-group-btn"><a href="#" class="btn btn-default delete-tag"><span class="glyphicon glyphicon-minus"></span></a></span>');
        $prototypeTag.append($linkDelete);

        $linkDelete.on('click', function(e){
            e.preventDefault();
            e.stopPropagation();

            $prototypeTag.remove();
        });

        $('#container-tag').append($prototypeTag);
    });
});