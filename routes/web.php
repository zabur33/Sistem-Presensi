<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use App\Http\Controllers\ComplaintController as UserComplaintController;
use App\Http\Controllers\Admin\ComplaintController as AdminComplaintController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\RecapController;
use App\Http\Controllers\Admin\MonitoringController;
use App\Http\Controllers\Admin\OvertimeController;
use App\Http\Controllers\UserOvertimeController;
use App\Http\Controllers\AttendanceSyncController;
use App\Http\Controllers\UserRecapController;
use App\Http\Controllers\Admin\EmployeeRegistrationController;
use App\Http\Controllers\ReverseGeocodeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
// ProfileController already imported above; ensure only one import exists

Route::get('/', function () {
    return view('login');
});

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// User pages (protected + idle timeout)
Route::middleware(['auth','idle'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    });

    // Presensi routes
    Route::get('/presensi/kantor', function () {
        return view('presensi-kantor');
    })->name('presensi.kantor');

    Route::get('/presensi/luar-kantor', function () {
        return view('presensi-luar-kantor');
    })->name('presensi.luar-kantor');

    // Lembur route
    Route::get('/lembur', function () {
        return view('lembur');
    })->name('lembur');

    // Overtime submit + user notifications
    Route::post('/overtime/submit', [UserOvertimeController::class, 'submit'])->name('user.overtime.submit');
    Route::get('/overtime/notifications', [UserOvertimeController::class, 'notifications'])->name('user.overtime.notifications');

    // Rekap Keseluruhan route (dynamic from DB)
    Route::get('/rekap-keseluruhan', [UserRecapController::class, 'index'])->name('rekap.keseluruhan');

    // Profile route
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');

    // Dashboard metrics API (JSON for charts)
    Route::get('/api/dashboard/metrics', [DashboardController::class, 'metrics'])->name('dashboard.metrics');

    // Edit Profile route
    Route::get('/edit-profile', function () {
        return view('edit-profile');
    })->name('edit.profile');

    // Profile update (avatar + fields)
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Reports
    Route::get('/reports/attendance', [ReportController::class, 'attendance'])->name('reports.attendance');

    // Attendance API (sync)
    Route::post('/attendance/check-in', [AttendanceSyncController::class, 'checkIn'])->name('attendance.checkin');
    Route::post('/attendance/check-out', [AttendanceSyncController::class, 'checkOut'])->name('attendance.checkout');
    Route::post('/attendance/proof', [AttendanceSyncController::class, 'proof'])->name('attendance.proof');

    // Reverse geocoding (server-side via Google Maps)
    Route::get('/api/reverse-geocode', [ReverseGeocodeController::class, 'lookup'])->name('reverse.geocode');
    
    // Free reverse geocoding (OpenStreetMap/Nominatim)
    Route::get('/api/free-reverse-geocode', [FreeGeocodeController::class, 'lookup'])->name('free.reverse.geocode');

    // User complaints: submit pengaduan masalah (DB)
    Route::post('/complaints', [UserComplaintController::class, 'store'])->name('user.complaints.submit');
});

// Admin routes (tampilan sama, halaman berbeda)
Route::prefix('admin')->middleware(['auth', 'idle', 'admin'])->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/kelola-presensi', [AttendanceController::class, 'index'])->name('admin.kelola-presensi');
    Route::post('/kelola-presensi/{attendance}/verify', [AttendanceController::class, 'verify'])->name('admin.attendance.verify');
    Route::post('/kelola-presensi/{attendance}/reject', [AttendanceController::class, 'reject'])->name('admin.attendance.reject');

    Route::get('/kelola-pegawai', [EmployeeController::class, 'index'])->name('admin.kelola-pegawai');
    Route::patch('/kelola-pegawai/{employee}', [EmployeeController::class, 'update'])->name('admin.employees.update');
    Route::delete('/kelola-pegawai/{employee}', [EmployeeController::class, 'destroy'])->name('admin.employees.destroy');

    // Admin dashboard metrics API
    Route::get('/api/dashboard/metrics', [AdminDashboardController::class, 'metrics'])->name('admin.dashboard.metrics');

    Route::get('/rekap-pegawai', [RecapController::class, 'index'])->name('admin.rekap-pegawai');
    Route::get('/rekap-pegawai/export-csv', [RecapController::class, 'exportCsv'])->name('admin.rekap-pegawai.export.csv');
    Route::get('/rekap-pegawai/print', [RecapController::class, 'print'])->name('admin.rekap-pegawai.print');

    Route::get('/validasi-monitoring', [MonitoringController::class, 'index'])->name('admin.validasi-monitoring');

    // Registrasi Pegawai
    Route::get('/registrasi-pegawai', [EmployeeRegistrationController::class, 'create'])->name('admin.registrasi-pegawai');
    Route::post('/registrasi-pegawai', [EmployeeRegistrationController::class, 'store'])->name('admin.registrasi-pegawai.store');

    Route::get('/validasi-lembur', function () {
        return redirect()->route('admin.overtime');
    });

    // Overtime notifications
    Route::get('/notifikasi-lembur', [OvertimeController::class, 'index'])->name('admin.overtime');
    Route::post('/notifikasi-lembur/{overtime}/read', [OvertimeController::class, 'markRead'])->name('admin.overtime.read');
    Route::post('/notifikasi-lembur/read-all', [OvertimeController::class, 'markAllRead'])->name('admin.overtime.readAll');
    Route::post('/notifikasi-lembur/{overtime}/approve', [OvertimeController::class, 'approve'])->name('admin.overtime.approve');
    Route::post('/notifikasi-lembur/{overtime}/reject', [OvertimeController::class, 'reject'])->name('admin.overtime.reject');

    Route::get('/profile', function () {
        return view('admin.profile');
    })->name('admin.profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');

    // Admin: complaint notifications feed (DB)
    Route::get('/complaints/notifications', [AdminComplaintController::class, 'notifications'])->name('admin.complaints.notifications');
});
