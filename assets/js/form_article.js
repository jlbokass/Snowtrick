$(document).ready(function(){


    // recupere prototype html créer par symfony
    var $container = $('#article_images');

    // recuprere le nombre d'input keyword
    var index = $container.find(':input').length;

    $container.find('.col-form-label').remove();

    // si 0 input Keyword ajoute 1
    if(index == 0) {
        addImage($container);
    } else {
        // S'il existe déjà des catégories, on ajoute un lien de suppression pour chacune d'entre elles
        $container.children('div').each(function () {
            //  deleteButton($(this));
        });
    }

    // Event click pout ajouter un input keyword
    $('.addImage').click(function(e) {
        e.preventDefault();
        addImage($container);
    })


    // creer l'input keyword pour l'index courant et l'ajoute dans la div id="car_keywords" avec la méthode append
    function addImage($container) {

        var template = $container.attr('data-prototype')
            .replace(/__name__label__/g, 'Image n°' + (index + 1))
            .replace(/__name__/g, index)
        ;

        var $prototype = $(template);

        deleteButton($prototype);

        $container.append($prototype);

        index ++;
    }

    function deleteButton($prototype) {
        var $deleteLink = $('<a href="#" class="btn btn-warning">Cancel</a>');

        $prototype.append($deleteLink);

        $deleteLink.click(function(e) {
            $prototype.remove();
            e.preventDefault(); // évite qu'un # apparaisse dans l'URL
            return false;
        });
    }
    $('.delete-images').click(function (e) {
        $('.responsive-img').remove()

    });
    $('.delete-image').click(function (e) {
        e.preventDefault();
        var divImageArea = $(this).closest('.imageArea');
        var url = $(this).attr('data-delete-path');
        var imageId = $(this).attr('data-image-id');

        $.ajax({
            method: "POST",
            url: url,
            data: { id: imageId },
            success: function (response) {
                divImageArea.remove();
            },
            error: function () {
                $('.error-delete-image').css('display','block')
            }
        })
    })

});