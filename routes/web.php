<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\StampCorrectionRequestController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\StampCorrectionRequestController as AdminStampCorrectionRequestController;

Route::middleware(['auth','verified'])->group(function(){
Route::get('/attendance',[AttendanceController::class,'index'])->name('attendance.index');
Route::get('/', function () {
    return redirect()->route('login');
});
Route::post('/attendance/check-in',[AttendanceController::class,'checkIn'])->name('attendance.check-in');
Route::post('/attendance/check-out',[AttendanceController::class,'checkOut'])->name('attendance.check-out');
Route::post('/attendance/rest-in',[AttendanceController::class,'restIn'])->name('attendance.rest-in');
Route::post('/attendance/rest-out',[AttendanceController::class,'restOut'])->name('attendance.rest-out');

Route::get('attendance/list',[AttendanceController::class,'attendanceList'])->name('attendance.list');

Route::get('attendance/{attendance}',[AttendanceController::class,'attendanceDetail'])->name('attendance.detail');

//修正申請実行
Route::post('/stamp-correction-request/{attendance}',
[StampCorrectionRequestController::class,'store'])->name('stamp-correction-request.store');

//申請一覧画面
Route::get('/stamp-correction-requests',[StampCorrectionRequestController::class,'index'])->name('stamp-correction-requests.index');

//申請詳細画面
Route::get('/stamp-correction-request/{stampCorrectionRequest}',[StampCorrectionRequestController::class,'show'])->name('stamp-correction-request.show');
});


// 管理者認証不要ルート
Route::prefix('admin')->group(function(){
    Route::get('/login', [AdminAuthController::class, 'index'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'store'])->name('admin.login.store');
});

// 管理者認証必要ルート
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function(){
    Route::post('/logout', [AdminAuthController::class, 'destroy'])->name('admin.logout');

    // 勤怠一覧・詳細
    Route::get('/attendance', [AdminAttendanceController::class, 'index'])->name('admin.attendance.index');
    Route::get('/attendance/{attendance}', [AdminAttendanceController::class, 'detail'])->name('admin.attendance.detail');

    //勤怠修正
    Route::put('/attendance/{attendance}', [AdminAttendanceController::class, 'update'])->name('admin.attendance.update');

    // スタッフ一覧・月次勤怠
    Route::get('/staff', [StaffController::class, 'index'])->name('admin.staff.index');
    Route::get('/staff/{user}/attendance', [StaffController::class, 'attendance'])->name('admin.staff.attendance');

    //csv出力
    Route::get('/staff/{user}/csv', [StaffController::class, 'csv'])->name('admin.staff.csv');

    // 申請一覧・詳細・承認
    Route::get('/stamp-correction-requests', [AdminStampCorrectionRequestController::class, 'index'])->name('admin.stamp-correction-requests.index');
    Route::get('/stamp-correction-request/{stampCorrectionRequest}', [AdminStampCorrectionRequestController::class, 'show'])->name('admin.stamp-correction-request.show');
    Route::post('/stamp-correction-request/{stampCorrectionRequest}/approve', [AdminStampCorrectionRequestController::class, 'approve'])->name('admin.stamp-correction-request.approve');
});