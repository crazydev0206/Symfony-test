$(document).ready(function() {
    $('.addfavorites').on('click', function () {
        var fruitId = $(this).data('fruit-id');
        var url = '{{ path("app_fruit_add_favorite", {"id": "__id__"}) }}'.replace('__id__', fruitId);
        var button = $(this);

        $.ajax({
            url: url,
            method: 'POST',
            success: function () {
                button.addClass('disabled').attr('disabled', true).text('Added to favorites');
            },
            error: function () {
                alert('An error occurred while adding the fruit to favorites.');
            }
        });
    });
});

