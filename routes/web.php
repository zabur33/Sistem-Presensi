<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return view('login');
});

Route::get('/login', function () {
    return view('login');
});

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
