<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class StoreWorkRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'work_content' => 'required|max:255',
            'date' => 'date_format:Y-m-d|unique:works,date',
            'work_start_time' => 'date_format:H:i',
            'work_end_time' => 'date_format:H:i',
            'break_time' => 'date_format:H:i',
        ];
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function attributes()
    {
        return [
            'work_content' => '業務内容',
            'date' => '日付',
            'work_start_time' => '出社時間',
            'work_end_time' => '退社時間',
            'break_time' => '休憩時間',
        ];
    }

    protected function prepareForValidation()
    {
        $work_start_hour = $this->work_start_hour;
        $work_start_minutes = $this->work_start_minutes;
        $work_end_hour = $this->work_end_hour;
        $work_end_minutes = $this->work_start_minutes;

        $work_start_time = $work_start_hour . ":" . $work_start_minutes;
        $work_end_time = $work_end_hour . ":" . $work_end_minutes;

        $date = $this->yearMonth . "-" . $this->date;

        if ($this->break_time === "60") {
            $break_time = "01:00";
        } else {
            $break_time = "00:" . $this->break_time;
        }
        $this->merge([
            'work_start_time' => $work_start_time,
            'work_end_time' => $work_end_time,
            'date' => $date,
            'break_time' => $break_time,
        ]);
    }
}
