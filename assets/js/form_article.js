$(document).ready(function(){

    // recupere prototype html créer par symfony
    var $container = $('#article_images');

    // recuprere le nombre d'input keyword
    var index = $container.find(':input').length;

    $container.find('label.required').remove();

    // si 0 input Keyword ajoute 1
    if(index == 0) {
        addImage($container);
    }

    // Event click pout ajouter un input keyword
    $('.addImage').click(function(e) {
        e.preventDefault();

        addImage($container);
    });

    // creer l'input keyword pour l'index courant et l'ajoute dans la div id="car_keywords" avec la méthode append
    function addImage($container) {

        var template = $container.attr('data-prototype')
            .replace(/__name__label__/g, 'Image n°' + (index + 1))
            .replace(/__name__/g, index)
        ;

        var $prototype = $(template);

        deleteButton($prototype);

        $container.append($prototype);

        index++;
    }

    function deleteButton($prototype) {
        var $deleteLink = $('<a href="#" class="btn btn-warning">Annuler</a>');

        $prototype.append($deleteLink);

        $deleteLink.click(function(e) {
            $prototype.remove();

            e.preventDefault(); // évite qu'un # apparaisse dans l'URL
            return false;
        });
    }
});
