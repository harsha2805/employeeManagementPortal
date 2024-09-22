<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Admin;
use App\Models\Location;
use App\Models\Role;

class LoginController extends Controller
{
    public function checkUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:4'
        ]);

        $userEmail = $request->email;
        $userPassword = $request->password;

        $employee = Employee::with('role')
            ->where('email', $userEmail)->first();

        if ($employee && Hash::check($userPassword, $employee->password)) {
            session()->put('user_id', $employee->id);
            session()->put('user_type', $employee->role->name);
            return redirect()->route('dashboard');
        }

        $admin = Admin::where('email', $userEmail)->first();

        if ($admin && Hash::check($userPassword, $admin->password)) {
            session()->put('user_id', $admin->id);
            session()->put('user_type', $admin->role->name);
            return redirect()->route('dashboard');
        }
        return redirect()->route('login')->withErrors('Invalid credentials or User does not exist.');
    }
    public function dashboard()
    {
        if (session('user_type') === 'Admin') {
            return view('adminDashboard');
        } else {
            if (session('user_type') === 'Location Admin') {
                return view('locationAdminDashboard');
            }
            return view('employeeDashboard');
        }
    }
    public function addEmployee()
    {
        $locations = Location::all();
        $roles = Role::where('id', '!=', '1')->get()->toArray();
        return view('addEmployee', compact('locations', 'roles'));
    }
    public function saveEmployee(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:employees,email|unique:admins,email',
            'password' => 'required|min:4',
            'role' => 'required|integer|exists:roles,id',
            'location' => 'required|integer|exists:locations,id',
        ]);

        try {
            if ($request->input('role') === '3') {
                $locationAdminExists = Employee::where('role_id', '2')
                    ->where('location_id', $request->input('location'))
                    ->exists();

                if (!$locationAdminExists) {
                    return redirect()->back()->withErrors(['location' => 'No Location Admin exists for the selected location. Please assign a Location Admin first.']);
                }
            }
            $hashedPassword = Hash::make($request->input('password'));

            Employee::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => $hashedPassword,
                'role_id' => $request->input('role'),
                'location_id' => $request->input('location'),
            ]);

            return redirect()->route('dashboard')->with('success', 'Employee added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    public function viewEmployee()
    {
        $userId = session('user_id');
        $userType = session('user_type');
        try {
            if ($userType === 'Admin') {
                $admins = Admin::all();
                $employees = Employee::with(['role', 'location'])->get();
                $allUsers = $employees->concat($admins);
            } else {
                $locationAdmin = Employee::where('id', $userId)
                    ->select('location_id')
                    ->first();
                if (!$locationAdmin) {
                    return response()->json(['error' => 'User location not found.'], 404);
                }
                $allUsers = Employee::with(['role', 'location'])->where('location_id', $locationAdmin->location_id)->get();
            }
            return response()->json($allUsers);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
    public function clockIn()
    {
        $userId = session('user_id');
        $currentDateTime = now();
        $clockIn = AttendanceRecord::create([
            'employee_id' => $userId,
            'clock_in' => $currentDateTime,
        ]);
        if ($clockIn) {
            return response()->json(['message' => 'Clock-in successful!']);
        } else {
            return response()->json(['message' => 'Something went wrong']);
        }
    }
    public function lastClockIn()
    {
        $userId = session('user_id');
        $lastClockIn = AttendanceRecord::where('employee_id', $userId)
            ->whereNull('clock_out')
            ->first();
        if (!$lastClockIn) {
            $lastClockIn = false;
        }
        return response()->json($lastClockIn);
    }
    public function clockOut()
    {
        $userId = session('user_id');
        $currentDateTime = now();

        $attendanceRecord = AttendanceRecord::where('employee_id', $userId)
            ->whereNull('clock_out')
            ->first();

        if ($attendanceRecord) {
            $attendanceRecord->update([
                'clock_out' => $currentDateTime,
            ]);
            return response()->json(['message' => 'Clock-out successful!']);
        }
        return response()->json(['message' => 'Something went wrong']);
    }
    public function overAllAttendance()
    {
        $userId = session('user_id');
        $allAttendanceData = AttendanceRecord::where('employee_id', $userId)
            ->orderByDesc('clock_in')
            ->get()
            ->toArray();
        return response()->json($allAttendanceData);
    }
    public function totalAttendance()
    {
        $userId = session('user_id');
        $userType = session('user_type');
        try {
            if ($userType === 'Admin') {
                $allUserIds = Employee::all()
                    ->pluck('id')
                    ->toArray();
            } else {
                $locationAdmin = Employee::where('id', $userId)->select('location_id')->first();
                if (!$locationAdmin) {
                    return response()->json(['error' => 'User not found'], 404);
                }
                $allUserIds = Employee::where('location_id', $locationAdmin->location_id)
                    ->pluck('id')
                    ->toArray();
            }
            $allAttendanceData = AttendanceRecord::join('employees', 'attendance_records.employee_id', '=', 'employees.id')
                ->whereIn('attendance_records.employee_id', $allUserIds)
                ->orderByDesc('attendance_records.clock_in')
                ->select('employees.name', 'attendance_records.clock_in', 'attendance_records.clock_out')
                ->get();

            return response()->json($allAttendanceData);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
    public function logout()
    {
        session()->flush();
        return redirect()->route('login');
    }
}
