document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const categorySelect = document.getElementById('categorySelect');

    $(categorySelect).select2();
    $(categorySelect).siblings('.select2-container').find('.select2-selection').addClass('js-example-basic-multiple js-states form-control');

    // Define a function to filter movies
    function filterFilms() {
        const searchText = searchInput.value.trim().toLowerCase();
        const selectedCategories = $(categorySelect).select2('data').map(option => option.text.toLowerCase());

        const films = document.querySelectorAll('.cards');
        films.forEach(film => {
            const title = film.querySelector('.card-title').textContent.toLowerCase();
            const categories = film.dataset.categories.toLowerCase().split(',');
            const isVisible = (!searchText || title.includes(searchText)) &&
                              (!selectedCategories.length || selectedCategories.every(category => categories.includes(category)));
            film.style.display = isVisible ? 'block' : 'none';
        });
    }

    // Trigger filtering when the search input or category select dropdown changes
    searchInput.addEventListener('input', filterFilms);
    $(categorySelect).on('change', filterFilms);
});
