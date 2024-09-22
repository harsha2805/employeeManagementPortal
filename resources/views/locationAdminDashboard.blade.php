@extends('main')
@section('content')
    <div class="container">
        <div class="card">
            <h2>Existing Employees</h2>

            <table id="allEmployee" class="hover" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                </thead>
                <tbody id="allEmployeeData"></tbody>
            </table>

        </div>

        <div class="card">
            <h2>Total Attendance</h2>

            <table id="allAttendance" class="display" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <th>Clock In</th>
                    <th>Clock Out</th>
                </thead>
                <tbody id="allAttendanceData"></tbody>
            </table>
            <p id="totalAttendance"></p>
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
                    var allEmployeeData = $('#allEmployeeData');
                    allEmployeeData.empty();

                    var flattenedResponse = [].concat.apply([], response);

                    flattenedResponse.forEach(function(user) {
                        allEmployeeData.append(
                            '<tr>' +
                            '<td>' + user.name + '</td>' +
                            '<td>' + user.email + '</td>' +
                            '<td>' + user.role.name + '</td>' +
                            '</tr>'
                        );
                    });
                    $('#allEmployee').DataTable({
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
                    var allAttendanceData = $('#allAttendanceData')
                    allAttendanceData.empty();
                    response.forEach(function(data) {
                        allAttendanceData.append(
                            '<tr>' +
                            '<td>' + data.clock_in + '</td>' +
                            '<td>' + data.clock_out + '</td>' +
                            '</tr>'
                        );
                    });

                    $('#allAttendance').DataTable({
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
