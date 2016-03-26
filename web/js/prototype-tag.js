$(document).ready(function() {

    var $prototypeT = $('#prototype-tag');
    var currentNbTag = $('li.list-tag').size();
    var indexT = $('input.tag').length;
    var nbTag = (5 - currentNbTag);
    if(nbTag <= 1)
    {
        $('#add-tag').text('Ajouter un tag');
    }else{
        $('#add-tag').text('Ajouter '+nbTag+' tags');
    }
    console.log($prototypeT.children().children().html().replace(/__name__/g,0));
    $prototypeT.children().children().html().replace(/__name__/g,0);

    $('#add-tag').on('click', function(event){

        event.preventDefault();
        event.stopPropagation();
        if(indexT <= ( 4 - currentNbTag )) {
            var $prototypeTag = $prototypeT.clone();

            $prototypeTag.children().addClass('input-group');
            $prototypeTag = $($prototypeTag.html().replace(/__name__/g, $('input.tag').length));

            var $linkDelete = $('<span class="input-group-btn"><a href="#" class="btn btn-default delete-tag"><span class="glyphicon glyphicon-minus"></span></a></span>');
            $prototypeTag.append($linkDelete);

            $('#container-tag').append($prototypeTag);
            nbTag--;
            $('#add-tag').text('Ajouter '+nbTag+' tags');
            indexT++;

            $linkDelete.on('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                $prototypeTag.remove();
                nbTag++;
                $('#add-tag').text('Ajouter '+nbTag+' tags');
                indexT--;
            });
        }
    });

    $('#tags-list').on('click', '.btn-danger', function(event) {
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