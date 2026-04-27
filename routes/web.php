<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\StampCorrectionRequestController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth')->group(function(){
Route::get('/attendance',[AttendanceController::class,'index'])->name('attendance.index');
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
