<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'check_in'  => ['required'],
            'check_out' => ['nullable'],
            'remarks'   => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'check_in.required' => '出勤時間を入力してください',
            'remarks.required'  => '備考を記入してください',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $checkIn    = $this->input('check_in');
            $checkOut   = $this->input('check_out');
            $restStarts = $this->input('rest_start', []);
            $restEnds   = $this->input('rest_end', []);

            // 出勤時間が退勤時間より後の場合
            if ($checkIn && $checkOut) {
                if (strtotime($checkIn) >= strtotime($checkOut)) {
                    $validator->errors()->add('check_in', '出勤時間もしくは退勤時間が不適切な値です');
                    return;
                }
            }

            // 休憩時間のバリデーション
            foreach ($restStarts as $index => $restStart) {
                $restEnd = $restEnds[$index] ?? null;

                if ($restStart) {
                    // 休憩開始が出勤より前
                    if (strtotime($restStart) < strtotime($checkIn)) {
                        $validator->errors()->add('rest_start', '休憩時間が不適切な値です');
                        return;
                    }
                    // 休憩開始が退勤より後
                    if ($checkOut && strtotime($restStart) > strtotime($checkOut)) {
                        $validator->errors()->add('rest_start', '休憩時間が不適切な値です');
                        return;
                    }
                }

                if ($restEnd) {
                    // 休憩終了が休憩開始より前
                    if ($restStart && strtotime($restEnd) <= strtotime($restStart)) {
                        $validator->errors()->add('rest_end', '休憩時間が不適切な値です');
                        return;
                    }
                    // 休憩終了が退勤より後
                    if ($checkOut && strtotime($restEnd) > strtotime($checkOut)) {
                        $validator->errors()->add('rest_end', '休憩時間もしくは退勤時間が不適切な値です');
                        return;
                    }
                }
            }
        });
    }
}