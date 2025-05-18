document.addEventListener('DOMContentLoaded', () => {
    loadVolunteer();

    // Add event listener for the "Mark Attendance" button
    document.getElementById('markAttendanceBtn').addEventListener('click', updateAttendance);
    document.getElementById('exportBtn').addEventListener('click', exportToCSV);
});


function loadVolunteer() {

    const urlParams = new URLSearchParams(window.location.search);
    const volunteerId = urlParams.get('volunteerId'); // Get volunteer ID from URL parameter

    fetch(`fetch_volunteer.php?volunteerId=${volunteerId}`)
        .then(response => response.json())
        .then(data => {
            const volunteers = data.volunteers;
            const tableBody = document.querySelector('.event-table tbody');

            tableBody.innerHTML = '';

            if (volunteers.length > 0) {
                volunteers.forEach(volunteer => {
                    const checked = volunteer.attendance ? 'checked' : '';
                    const row = `
                        <tr>
                            <td>${volunteer.volunteerId}</td>
                            <td>${volunteer.userId}</td>
                            <td>${volunteer.full_name}</td>
                            <td>${volunteer.email}</td>
                            <td>${volunteer.contact_number}</td>
                            <td>${volunteer.joined_at}</td>
                            <td><input type="checkbox" class="attendance-checkbox" data-id="${volunteer.userId}" ${checked}></td>
                        </tr>
                    `;
                    tableBody.innerHTML += row;
                });
            } else {
                tableBody.innerHTML = '<tr><td colspan="7">No volunteers found.</td></tr>';
            }
        })
        .catch(error => {
            console.error('Error fetching volunteers:', error);
            const tableBody = document.querySelector('.event-table tbody');
            tableBody.innerHTML = '<tr><td colspan="7">Error loading volunteers.</td></tr>';
        });
}


function updateAttendance() {
    const attendanceData = [];
    const checkboxes = document.querySelectorAll('.attendance-checkbox');

    checkboxes.forEach(checkbox => {
        attendanceData.push({
            userId: checkbox.dataset.id,
            attendance: checkbox.checked ? 1 : 0
        });
    });

    fetch('update_volunteer_attendance.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ attendance: attendanceData})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Attendance updated successfully.');
        } else {
            alert('Failed to update attendance.');
        }
    })
    .catch(error => {
        console.error('Error updating attendance:', error);
    });
}


function exportToCSV() {
    const rows = [];
    const headers = ["Volunteer ID", "User ID", "Full Name", "Email", "Contact Number", "Joined At", "Attendance"];
    rows.push(headers);

    const tableRows = document.querySelectorAll('.event-table tbody tr');

    tableRows.forEach(row => {
        const cells = row.querySelectorAll('td');
        if (cells.length > 0) {
            const rowData = [
                cells[0].textContent,
                cells[1].textContent,
                cells[2].textContent,
                cells[3].textContent,
                cells[4].textContent,
                cells[5].textContent,
                cells[6].querySelector('input').checked ? 'Present' : 'Absent'
            ];
            rows.push(rowData);
        }
    });
    let csvContent = "data:text/csv;charset=utf-8," + rows.map(e => e.join(",")).join("\n");

    const link = document.createElement("a");
    link.setAttribute("href", encodeURI(csvContent));
    link.setAttribute("download", "volunteer_data.csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

}