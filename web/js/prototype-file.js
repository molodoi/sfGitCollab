$(document).ready(function() {
    var $prototype = $('#prototype-file');
    var currentNbFile = $('img.advertfile').size();
    var index = $('input.file').length;
    var nbFile = (5 - currentNbFile);
    if(nbFile <= 1)
    {
        $('#add-file').text('Ajouter un fichier');
    }else{
        $('#add-file').text('Ajouter '+nbFile+' fichiers');
    }

    $prototype.children().html().replace(/__name__/g,0);

    $('#add-file').on('click', function(event){
        event.preventDefault();
        event.stopPropagation();
        if(index <= ( 4 - currentNbFile )) {
            var $prototypeFile = $prototype.clone();

            $prototypeFile.children().addClass('input-group');
            $prototypeFile = $($prototypeFile.html().replace(/__name__/g, $('input.file').length));

            var $linkDelete = $('<span class="input-group-btn"><a href="#" class="btn btn-default delete-file"><span class="glyphicon glyphicon-minus"></span></a></span>');
            $prototypeFile.append($linkDelete);

            $('#container-file').append($prototypeFile);
            nbFile--;
            $('#add-file').text('Ajouter '+nbFile+' fichiers');
            index++;

            $linkDelete.on('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                $prototypeFile.remove();
                nbFile++;
                $('#add-file').text('Ajouter '+nbFile+' fichiers');
                index--;
            });
        }
    });

    $('#files-list').on('click', '.btn-danger', function(event) {
        event.preventDefault();
        if (confirm("Etes-vous sure?")) {
            currentLink = $(this);
            currentLinkHref = currentLink.attr('href');
            currentLink.closest('li').fadeOut(600,function(){
                $(this).remove();
                nbTag++;
                $('#add-tag').text('Ajouter '+nbTag+' tags');
                indexT--;
            });
            $.ajax({
                type:"GET",
                url: currentLinkHref
            }).done(function(){
                currentLink.parents('li').fadeOut(600,function(){
                    $(this).remove();

                });
            });
        }
    });


});