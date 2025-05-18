document.addEventListener("DOMContentLoaded", function() {
    const tncLink = document.getElementById("tnc-link");
    const modal = document.getElementById("tnc-modal");
    const closeModalBtn = document.querySelector(".close-btn");

    tncLink.addEventListener("click", function(event) {
        event.preventDefault();
        modal.style.display = "block";
    });

    closeModalBtn.addEventListener("click", function() {
        modal.style.display = "none";
    });

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };
});
