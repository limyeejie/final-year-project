document.addEventListener('DOMContentLoaded', () => {
    loadParticipant();

    // Add event listener for the "Mark Attendance" button
    document.getElementById('markAttendanceBtn').addEventListener('click', updateAttendance);
    document.getElementById('exportBtn').addEventListener('click', exportToCSV);
});

function loadParticipant() {

    const urlParams = new URLSearchParams(window.location.search);
    const eventId = urlParams.get('eventId'); 

    fetch(`fetch_participant.php?eventId=${eventId}`)
        .then(response => response.json())
        .then(data => {
            const participants = data.participants;
            const tableBody = document.querySelector('.event-table tbody');

            tableBody.innerHTML = '';

            if (participants.length > 0) {
                participants.forEach(participant => {
                    const checked = participant.attendance ? 'checked' : '';
                    const row = `
                        <tr>
                            <td>${participant.eventId}</td>
                            <td>${participant.userId}</td>
                            <td>${participant.full_name}</td>
                            <td>${participant.email}</td>
                            <td>${participant.contact_number}</td>
                            <td>${participant.joined_at}</td>
                            <td><input type="checkbox" class="attendance-checkbox" data-id="${participant.userId}" ${checked}></td>
                        </tr>
                    `;
                    tableBody.innerHTML += row;
                });
            } else {
                tableBody.innerHTML = '<tr><td colspan="7">No participants found.</td></tr>';
            }
        })
        .catch(error => {
            console.error('Error fetching participants:', error);
            const tableBody = document.querySelector('.event-table tbody');
            tableBody.innerHTML = '<tr><td colspan="7">Error loading participants.</td></tr>';
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

    fetch('update_participant_attendance.php', {
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
    const headers = ["Event ID", "User ID", "Full Name", "Email", "Contact Number", "Joined At", "Attendance"];
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
    link.setAttribute("download", "participant_data.csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

}