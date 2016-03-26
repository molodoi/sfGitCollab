$(document).ready(function() {
    // Supprimer le text des label sur les checkbox public ect..
    $('.material-switch label').text('');
    //nombre d'image actuellement sur l'annonce
    var currentNbFile = $('img.advertfile').size();

    // On récupère la balise <div> en question qui contient l'attribut « data-prototype » qui nous intéresse.
    var $container = $('div#advert_files');
    // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
    var index = $container.find(':input').length;

    var nbFile = (5 - currentNbFile);
    if(nbFile <= 1)
    {
        var $addLink = $('<a href="#" id="add_category" class="btn btn-default">Ajouter un fichier</a><hr class="clearfix" />');
    }
    else
    {
        var $addLink = $('<a href="#" id="add_category" class="btn btn-default">Ajouter '+nbFile+' fichiers</a><hr class="clearfix" />');
    }


    // On ajoute un lien pour ajouter une nouvelle
    $container.prepend($addLink);

    // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
    $addLink.click(function(e) {
        if(index <= ( 4 - currentNbFile )) {
            addAdvertFile($container);
        }
        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        return false;
    });

    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
    if (index == 0) {
        addAdvertFile($container);
    } else {
        // Pour chaque catégorie déjà existante, on ajoute un lien de suppression
        $container.children('div').each(function() {
            addDeleteLink($(this));
        });
    }

    // La fonction qui ajoute un formulaire
    function addAdvertFile($container) {
        // Dans le contenu de l'attribut « data-prototype », on remplace :
        // - le texte "__name__label__" qu'il contient par le label du champ
        // - le texte "__name__" qu'il contient par le numéro du champ
        var $prototype = $($container.attr('data-prototype').replace(/__name__label__/g, 'Fichier n°' + (index+1)).replace(/__name__/g, index));
        // On ajoute au prototype un lien pour pouvoir supprimer la catégorie
        addDeleteLink($prototype);

        // On ajoute le prototype modifié à la fin de la balise <div>
        $container.append($prototype);

        // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
        $('label[for="advert_files_'+index+'_file"]').remove();
        index++;
    }

    // La fonction qui ajoute un lien de suppression d'une catégorie
    function addDeleteLink($prototype) {
        // Création du lien
        $deleteLink = $('<br /><a href="#" class="btn btn-danger">Supprimer le champ de téléchargement ci-dessus</a><hr class="clearfix" />');

        // Ajout du lien
        $prototype.append($deleteLink);

        // Ajout du listener sur le clic du lien
        $deleteLink.click(function(e) {
            $prototype.remove();
            e.preventDefault(); // évite qu'un # apparaisse dans l'URL
            return false;
        });
    }

    $('#thumbs-list').on('click', '.btn-danger', function(event) {
        event.preventDefault();
        if (confirm("Etes-vous sure?")) {
            currentLink = $(this);
            currentLinkHref = currentLink.attr('href');
            currentLink.closest('.fileproduct-thumb').fadeOut(600,function(){
                $(this).remove();
            });

            $.ajax({
                type:"GET",
                url: currentLinkHref
            }).done(function(){
                currentLink.parents('.fileproduct-thumb').fadeOut(600,function(){
                    $(this).remove();
                });
            });
        }
    });
});