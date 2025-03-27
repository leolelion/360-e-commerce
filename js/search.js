document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("search-input");
    const searchBtn = document.getElementById("search-btn");

    const products = {
        blueberries: "blueberries",
        raspberries: "raspberries",
        eggs: "eggs",
        "baby spinach": "baby_spinach"
    };

    searchBtn.addEventListener("click", function () {
        const query = searchInput.value.trim().toLowerCase();

        if (products[query]) {
            window.location.href = "product.php?item=" + products[query];
        } else {
            alert("Try searching for 'blueberries', 'raspberries', 'eggs', or 'baby spinach'");
        }
    });

    searchInput.addEventListener("keypress", function (event) {
        if (event.key === "Enter") {
            searchBtn.click();
        }
    });
});