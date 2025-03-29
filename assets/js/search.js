document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("search-input");
    const searchBtn = document.getElementById("search-btn");

    searchBtn.addEventListener("click", function () {
        const query = searchInput.value.trim().toLowerCase();

        if (query === "blueberries") {
            window.location.href = "product.php";
        } else {
            alert("Try searching for 'blueberries'");
        }
    });

    searchInput.addEventListener("keypress", function (event) {
        if (event.key === "Enter") {
            searchBtn.click();
        }
    });
});