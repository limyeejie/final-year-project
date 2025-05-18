
    //Pie Chart
    document.addEventListener("DOMContentLoaded", () => {
        // Fetch data from the backend
        fetch("../templates/fetch_perf_data.php")
            .then((response) => response.json())
            .then((data) => {
                if (data.error) {
                    console.error(data.error);
                    return;
                }
    
                // Create the Organisers & Students Chart
                new Chart(document.getElementById("organisersStudentsChart"), {
                    type: "pie",
                    data: {
                        labels: ["Organisers", "Students"],
                        datasets: [
                            {
                                data: [data.organisers, data.students],
                                backgroundColor: ["#2563eb", "#34d399"],
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true, // Ensures equal aspect ratio across charts
                    },
                });
    
                // Create the Events & Volunteers Chart
                new Chart(document.getElementById("eventsVolunteersChart"), {
                    type: "pie",
                    data: {
                        labels: ["Events", "Volunteers"],
                        datasets: [
                            {
                                data: [data.events, data.volunteers],
                                backgroundColor: ["#fbbf24", "#6366f1"],
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                    },
                });
    
                // Create the Registered Participants & Volunteers Chart
                new Chart(document.getElementById("registeredChart"), {
                    type: "pie",
                    data: {
                        labels: ["Participants", "Volunteers"],
                        datasets: [
                            {
                                data: [data.registeredParticipants, data.registeredVolunteers],
                                backgroundColor: ["#f43f5e", "#10b981"],
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                    },
                });
            })
            .catch((error) => console.error("Error fetching performance data:", error));
    });
    
    