<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class UpdateWorkRequest extends FormRequest
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
            'work_content' => ['required', 'max:255'],
            'date' => ['required', 'date_format:Y-m-d', Rule::unique('works')->ignore($this->work->id)],
            'work_start_time' => ['required', 'date_format:H:i'],
            'work_end_time' => ['required', 'date_format:H:i', 'after:work_start_time'],
            'break_time' => ['required', 'date_format:H:i'],
            'workedTimes' => ['numeric', 'min:0.25'],
        ];
    }

    /**
     * バリデーションエラーのカスタム属性の取得
     *
     * @return array<string>
     */
    public function attributes()
    {
        return [
            'work_content' => '業務内容',
            'date' => '日付',
            'work_start_time' => '出社時間',
            'work_end_time' => '退社時間',
            'break_time' => '休憩時間',
            'workedTimes' => '勤務時間',
        ];
    }

    /**
     * 定義済みバリデーションルールのエラーメッセージ取得
     *
     * @return array<string>
     */
    public function messages()
    {
        return[
            'workedTimes.min' => '勤務時間が不正です。',
            'work_end_time.after' => '退社時間は出社時間より後の時間を指定してください。',
            'date.unique' => '登録済みの日付です。該当する日付の「詳細」から更新してください。',
        ];
    }

    /**
     * バリーデーションのためにデータを準備
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        //出社時間形成
        $work_start_time = $this->work_start_hour . ":" . $this->work_start_minutes;
        //退社時間形成
        $work_end_time = $this->work_end_hour . ":" . $this->work_end_minutes;
        //日付形成
        $date = $this->yearMonth . "-" . $this->date;
        //休憩時間形成
        if ($this->break_time === "60") {
            $break_time = "01:00";
        } else {
            $break_time = "00:" . $this->break_time;
        }
        //勤務時間算出
        $year = (int)mb_substr($this->yearMonth, 0, 4);
        $month = (int)mb_substr($this->yearMonth, 5, 2);
        $day = (int)$this->date;
        $workStartDate = Carbon::create($year, $month, $day, (int)$this->work_start_hour, (int)$this->work_start_minutes);
        $workEndDate = Carbon::create($year, $month, $day, (int)$this->work_end_hour, (int)$this->work_end_minutes);
        $workStartToEnd = (int)$workStartDate->diffInMinutes($workEndDate);
        $workedTimes = $workStartToEnd - (int)$this->break_time;

        $this->merge([
            'work_start_time' => $work_start_time,
            'work_end_time' => $work_end_time,
            'date' => $date,
            'break_time' => $break_time,
            'workedTimes' => $workedTimes,
        ]);
    }
}
