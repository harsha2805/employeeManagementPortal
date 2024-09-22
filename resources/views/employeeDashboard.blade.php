@extends('main')
@section('content')
    <div class="container">
        <div class="card">
            <h2>Mark your attendance</h2>

            <button type="button" class="btn btn-success mb-3" id="clockIn" style="width: 150px;"> Clock In </button>
            <small id="lastClockIn"></small>
            <button type="button" class="btn btn-success mb-3" id="clockOut" style="width: 150px;"> Clock Out </button>


        </div>

        <div class="card">
            <h2>Overall Attendance</h2>
            <table id="allAttendance" class="display" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <th>Clock In</th>
                    <th>Clock Out</th>
                </thead>
                <tbody id="allAttendanceData"></tbody>
            </table>
        </div>
    </div>
@endsection
@push('customScripts')
    <script>
        $(document).ready(function() {

            enableButton();
            overAllAttendance();

            function enableButton() {
                $.ajax({
                    url: "{{ route('lastClockIn') }}",
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        var lastClockIn = $('#lastClockIn')
                        lastClockIn.empty();
                        if (response) {
                            var clockInInfo = 'Your last clock in time ' + response.clock_in;
                            lastClockIn.html(clockInInfo);
                            document.getElementById("clockIn").disabled = true;
                            document.getElementById("clockOut").disabled = false;
                        } else {
                            var clockInInfo = 'You should Clock-In first to Clock-Out';
                            lastClockIn.html(clockInInfo);
                            document.getElementById("clockOut").disabled = true;
                            document.getElementById("clockIn").disabled = false;
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                    }
                });
            }

            function overAllAttendance() {
                $.ajax({
                    url: "{{ route('overAllAttendance') }}",
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

                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                    }
                });
            }
            $('#allAttendance').DataTable({
                paging: false,
                ordering: false,
                searching: false,
                info: false,
                processing: false,
            });
            $('#clockIn').on('click', function() {
                $.ajax({
                    url: "{{ route('clockIn') }}",
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        overAllAttendance();
                        enableButton();
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                    }
                });
            });
            $('#clockOut').on('click', function() {
                $.ajax({
                    url: "{{ route('clockOut') }}",
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        overAllAttendance();
                        enableButton();
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                    }
                });
            });
        });
    </script>
@endpush
