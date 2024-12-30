document.addEventListener("DOMContentLoaded", function () {
    const stars = document.querySelectorAll(".star");
    let selectedRating = 0; // To track the selected rating
    const bookIdField = document.getElementById("book_id");
    const bookNameField = document.getElementById("book_name");

    // Retrieve book_id and book_name from URL query parameters
    const urlParams = new URLSearchParams(window.location.search);
    const bookIdFromQuery = urlParams.get("book_id");
    const bookNameFromQuery = urlParams.get("book_name");

    if (bookIdFromQuery && bookNameFromQuery) {
        bookIdField.value = bookIdFromQuery;
        bookNameField.value = bookNameFromQuery;
    } else {
        console.error("Book ID or Book Name is missing from the URL query parameters.");
    }

    // Star click logic
    stars.forEach((star, index) => {
        star.addEventListener("click", function () {
            const currentRating = index + 1;

            if (currentRating === selectedRating) {
                resetStars();
                selectedRating = 0;
            } else {
                highlightStars(currentRating);
                selectedRating = currentRating;
            }

            // Update the hidden field for star rating
            const ratingField = document.createElement("input");
            ratingField.type = "hidden";
            ratingField.name = "rating";
            ratingField.value = selectedRating;
            const form = document.querySelector(".review-form");
            form.appendChild(ratingField);
        });
    });

    function highlightStars(rating) {
        resetStars();
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.add("selected");
            }
        });
    }

    function resetStars() {
        stars.forEach(star => {
            star.classList.remove("selected");
        });
    }
});
