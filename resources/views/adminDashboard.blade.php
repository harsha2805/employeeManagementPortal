@extends('main')
@section('content')
    <div class="container">
        <button type="button" class="btn btn-success" onclick="window.location.href='{{ route('addEmployee') }}'">
            Add User
        </button>
        <hr>
        <div class="card">
            <h2>Existing Employees</h2>

            <table id="existingUsers" class="hover" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Location</th>
                    </tr>
                </thead>
                <tbody id="existingUserData"></tbody>
            </table>
        </div>
        <hr>
        <div class="card">
            <h2>Attendance</h2>

            <table id="attendanceTable" class="display" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Clock In</th>
                        <th>Clock Out</th>
                    </tr>
                </thead>
                <tbody id="totalAttendance"></tbody>
            </table>
        </div>
    </div>
@endsection

@push('customScripts')
    <script>
        $(document).ready(function() {
            $.ajax({
                url: "{{ route('viewEmployee') }}",
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    var existingUserData = $('#existingUserData');
                    existingUserData.empty();

                    var flattenedResponse = [].concat.apply([], response);

                    flattenedResponse.forEach(function(user) {
                        existingUserData.append(
                            '<tr>' +
                            '<td>' + user.name + '</td>' +
                            '<td>' + user.email + '</td>' +
                            '<td>' + (user.role ? user.role.name : 'Admin') +
                            '</td>' +
                            '<td>' + (user.location ? user.location.name : '') +
                            '</td>' +
                            '</tr>'
                        );
                    });

                    $('#existingUsers').DataTable({
                        autoFill: true
                    });
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                }
            });

            $.ajax({
                url: "{{ route('totalAttendance') }}",
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    var allAttendanceData = $('#totalAttendance');
                    allAttendanceData.empty();

                    response.forEach(function(data) {
                        allAttendanceData.append(
                            '<tr>' +
                            '<td>' + data.name + '</td>' +
                            '<td>' + data.clock_in + '</td>' +
                            '<td>' + (data.clock_out || 'Not yet clocked out') + '</td>' +
                            '</tr>'
                        );
                    });

                    $('#attendanceTable').DataTable({
                        autoFill: true
                    });
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                }
            });
        });
    </script>
@endpush
