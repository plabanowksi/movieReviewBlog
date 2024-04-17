const searchInput = document.getElementById('searchInput');
const searchButton = document.getElementById('searchButton');
const clearButton = document.getElementById('clearButton');

document.getElementById('searchButton').addEventListener('click', function () {
    var input = document.getElementById('searchInput').value.toLowerCase();
    var items = document.querySelectorAll('#resultsList div');
    items.forEach(function(item) {
        if (item.textContent.toLowerCase().includes(input)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
});

function clearSearchResults() {
    var items = document.querySelectorAll('#resultsList div');
    items.forEach(function(item) {
        item.style.display = 'block';
    });
}

document.getElementById('clearButton').addEventListener('click', function() {
    document.getElementById('searchInput').value = '';
    clearSearchResults();
});

console.log('searchjs');
