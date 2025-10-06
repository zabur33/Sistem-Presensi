<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\RecapController;
use App\Http\Controllers\Admin\MonitoringController;
use App\Http\Controllers\Admin\OvertimeController;
use App\Http\Controllers\Admin\EmployeeRegistrationController;

Route::get('/', function () {
    return view('login');
});

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// User pages (protected)
Route::middleware('auth')->group(function () {
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

    // Rekap Keseluruhan route
    Route::get('/rekap-keseluruhan', function () {
        return view('rekap-keseluruhan');
    })->name('rekap.keseluruhan');

    // Profile route
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');

    // Edit Profile route
    Route::get('/edit-profile', function () {
        return view('edit-profile');
    })->name('edit.profile');

    // Reports
    Route::get('/reports/attendance', [ReportController::class, 'attendance'])->name('reports.attendance');
});

// Admin routes (tampilan sama, halaman berbeda)
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/kelola-presensi', [AttendanceController::class, 'index'])->name('admin.kelola-presensi');
    Route::post('/kelola-presensi/{attendance}/verify', [AttendanceController::class, 'verify'])->name('admin.attendance.verify');
    Route::post('/kelola-presensi/{attendance}/reject', [AttendanceController::class, 'reject'])->name('admin.attendance.reject');

    Route::get('/kelola-pegawai', [EmployeeController::class, 'index'])->name('admin.kelola-pegawai');

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

    Route::get('/profile', function () {
        return view('admin.profile');
    })->name('admin.profile');
});
