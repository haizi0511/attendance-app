<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'clock_in'  => ['required', 'date_format:H:i'],
            'clock_out' => ['required', 'date_format:H:i'],

            'break_start.*' => ['nullable', 'date_format:H:i'],
            'break_end.*'   => ['nullable', 'date_format:H:i'],

            'note' => ['required', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'note.required' => '備考を記入してください',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $clockIn  = $this->clock_in;
            $clockOut = $this->clock_out;

            // 1. 出勤 > 退勤
            if ($clockIn && $clockOut && $clockIn > $clockOut) {
                $validator->errors()->add('clock_in', '出勤時間もしくは退勤時間が不適切な値です');
            }

            // 2. 休憩開始
            if ($this->break_start) {
                foreach ($this->break_start as $i => $start) {
                    if ($start && ($start < $clockIn || $start > $clockOut)) {
                        $validator->errors()->add("break_start.$i", '休憩開始時間が不適切な値です');
                    }
                }
            }

            // 3. 休憩終了
            if ($this->break_end) {
                foreach ($this->break_end as $i => $end) {
                    if ($end && $end > $clockOut) {
                        $validator->errors()->add("break_end.$i", '休憩終了時間もしくは退勤時間が不適切な値です');
                    }
                }
            }
        });
    }
}