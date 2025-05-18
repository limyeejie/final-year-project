document.addEventListener("DOMContentLoaded", function() {
    function fetchOpportunities() {
        fetch('fetch_opportunities.php')
            .then(response => response.json())
            .then(data => renderOpportunities(data))
            .catch(error => console.error('Error fetching opportunities:', error));
    }

    function renderOpportunities(opportunities) {
        const container = document.getElementById("opportunity-container");
        container.innerHTML = ""; //clear previous content
        opportunities.forEach((opportunity, index) => {
            const opportunityCard = document.createElement("div");
            opportunityCard.classList.add("opportunity-card");

            const title = document.createElement("h3");
            title.textContent = opportunity.title;

            // Dropdown for more details
            const detailsButton = document.createElement("button");
            detailsButton.textContent = "\u2193";
            detailsButton.classList.add("dropdown-toggle");

            const details = document.createElement("div");
            details.classList.add("opportunity-details");
            details.innerHTML = `
                <p><strong>Title : </strong> ${opportunity.title}</p>
                <p><strong>Role Description : </strong> ${opportunity.description}</p>
                <p><strong>Date : </strong> ${opportunity.date}</p>
                <p><strong>Location : </strong> ${opportunity.location}</p>
                <p><strong>Requirements : </strong> ${opportunity.requirement}</p>
                <div class="apply-btn-container">
                    <button class="apply-btn" data-title="${opportunity.title}">Apply Now</button>
                </div>
            `;
            details.style.display = "none"; //Hide details by default

            // Toggle dropdown
            detailsButton.addEventListener("click", function() {
                const isExpanded = this.getAttribute("aria-expanded") === "true";
                this.setAttribute("aria-expanded", !isExpanded);
                details.style.display = isExpanded ? "none" : "block";
                this.textContent = isExpanded ? "\u2193" : "\u2191";
            });

            opportunityCard.appendChild(title);
            opportunityCard.appendChild(detailsButton);
            opportunityCard.appendChild(details);
            container.appendChild(opportunityCard);
        });
    }

    fetchOpportunities();

    // Form Handling
    const modal = document.getElementById("applyFormModal");
    const closeModalBtn = document.querySelector(".close-btn");

    document.addEventListener("click", function(event) {
        if(event.target.classList.contains("apply-btn")) {
            modal.style.display = "flex";
            const formTitle = event.target.getAttribute("data-title");
            document.querySelector("#applyForm h2").textContent = `Apply for ${formTitle}`;
        }
    });

    closeModalBtn.addEventListener("click", function() {
        modal.style.display = "none";
    });

    window.onclick = function(event) {
        if(event.target == modal) {
            modal.style.display = "none";
        }
    };

    // Form submission logic
    document.getElementById("applyForm").addEventListener("submit", function(event) {
        event.preventDefault();
        alert("Your application has been submitted successfully!");
        modal.style.display = "none";
        this.reset();
    });
});
