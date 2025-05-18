document.addEventListener("DOMContentLoaded", () => {
    fetch('../templates/ai_insights.php')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json(); 
        })
        .then(data => {
            if (data.error) {
                console.error("Backend error:", data.error);
                return;
            }

            // Display Predicted Volunteers
            document.getElementById("predictedVolunteers").textContent = `Predicted Volunteers Next Month: ${data.predictedVolunteers}`;

            // Handle predictedEvents when it's an object
            if (typeof data.predictedEvents === 'object' && data.predictedEvents !== null) {
                console.log("predictedEvents is an object:", data.predictedEvents);

                // Extract the category from the predictedEvents object
                const predictedEvent = data.predictedEvents;
                document.getElementById("predictedEvents").textContent = `Predicted Popular Event Category: ${predictedEvent.category}`;
            } else {
                console.error("predictedEvents is not an object:", data.predictedEvents);
                document.getElementById("predictedEvents").textContent = "No predicted events available.";
            }

            // Events Chart (Existing)
            const ctxEvents = document.getElementById('popularEventsChart').getContext('2d');
            new Chart(ctxEvents, {
                type: 'bar',
                data: {
                    labels: data.popularEvents.map(event => event.category),
                    datasets: [{
                        label: 'Event Count',
                        data: data.popularEvents.map(event => event.count),
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                    },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });

            // Volunteer Trends Chart (Line Chart)
            const ctxTrends = document.getElementById('volunteerTrendsChart').getContext('2d');
            new Chart(ctxTrends, {
                type: 'line', // Line chart for volunteer trends
                data: {
                    labels: data.volunteerTrends.map(trend => trend.month), // X-axis: months
                    datasets: [{
                        label: 'New Volunteers',
                        data: data.volunteerTrends.map(trend => trend.new_volunteers), // Y-axis: new volunteers
                        fill: false,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Month'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number of New Volunteers'
                            }
                        }
                    }
                }
            });
        })
        .catch(error => {
            console.error("Error fetching AI Insights:", error);
            // Additional logging to see the status code
            if (error instanceof TypeError) {
                console.error("Network or server error:", error.message);
            } else {
                console.error("Unexpected error:", error);
            }
        });
});
