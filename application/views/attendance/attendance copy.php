<!-- Start Page content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card-box text-center">
                    <h3>Attendance System</h3>
                    <button id="attendance-btn" 
                            class="btn <?php echo (isset($attendance_list->check_in_time) && $attendance_list->check_in_time && $attendance_list->check_out_time == '0000-00-00 00:00:00') ? 'btn-danger' : 'btn-success'; ?>" 
                            onclick="toggleAttendance()">
                        <?php echo (isset($attendance_list->check_in_time) && $attendance_list->check_in_time && $attendance_list->check_out_time == '0000-00-00 00:00:00') ? 'Check Out' : 'Mark Attendance'; ?>
                    </button>
                </div>
            </div>
        </div>
        <!-- end row -->
    </div> <!-- container -->
</div> <!-- content -->

<script>
// Track attendance state (initially checked out)
let isCheckedIn = <?php echo (isset($attendance_list->check_in_time) && $attendance_list->check_out_time == '0000-00-00 00:00:00') ? 'true' : 'false'; ?>;

function toggleAttendance() {
    let url = isCheckedIn ? site_url + 'attendance/check_out' : site_url + 'attendance/check_in';

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(position => {
            fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude,
                })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.status === 'success') {
                    // Toggle the isCheckedIn state and update the button
                    isCheckedIn = !isCheckedIn;
                    updateButton();
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }
}

function updateButton() {
    const btn = document.getElementById('attendance-btn');
    if (isCheckedIn) {
        btn.className = 'btn btn-danger';
        btn.textContent = 'Check Out';
    } else {
        btn.className = 'btn btn-success';
        btn.textContent = 'Mark Attendance';
    }
}

</script>
