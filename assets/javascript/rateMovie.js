$(document).ready(function() {
    $('.rate-button').click(function() {
        var filmId = $(this).closest('.film').data('film-id');
        var rating = $(this).data('rating');
        $(this).toggleClass('checked');
        $.ajax({
            url: '/movie/rateMovie/' + filmId,
            type: 'POST',
            data: { rating: rating },
            success: function(response) {
                if (response.success) {
                    alert('Film rated successfully');
                    // Here you can update the UI as needed, for example, show the updated average rating
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                alert('Error: ' + error);
            }
        });
    });
});

console.log('ratemoviejs')
function setColor(btn){
    var property = document.getElementById(btn);
    property.classList.add('active');
}