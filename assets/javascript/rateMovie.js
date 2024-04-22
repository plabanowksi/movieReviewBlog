$(document).ready(function() {
    initializeStars();

    $('.rate-button').click(function() {
        $(this).addClass('checked').prevAll().addClass('checked');
        $(this).nextAll().removeClass('checked');

        var filmId = $(this).closest('.film').data('film-id');
        var rating = $(this).data('rating');

        $.ajax({
            url: '/movie/rateMovie/' + filmId,
            type: 'POST',
            data: { rating: rating },
            success: function(response) {
                if (response.success) {
                    alert('Film rated successfully');
                    // Here you can update the UI as needed, for example, show the updated average rating
                    // Assuming you have a function to update UI, call it here
                    updateUI(response.averageRating);
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                alert('Error: ' + error);
            }
        });
    });

    function updateUI(averageRating) {
        $('.average-rating').text('Average Rating: ' + averageRating +' / 5');
    }

    function initializeStars() {
        var existingRating = parseInt($('#existingRating').val());
        var starsContainer = $('.film .rate-button');
        starsContainer.removeClass('checked');
        for (var i = 0; i < existingRating; i++) {
            $(starsContainer[i]).addClass('checked');
        }
    }
});

console.log('ratemoviejs')