<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Work extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * 複数代入可能な属性
     *
     * @var array
     */
    protected $fillable = ['work_content', 'date', 'work_start_time', 'work_end_time', 'break_time'];


    /**
     * 日付取得
     *
     * @return string
     */
    public function getDateToDayAttribute()
    {
        return (string)mb_substr($this->date, 8, 2);
    }

    /**
     * 出社時間（時）取得
     *
     * @return string
     */
    public function getWorkStartHourAttribute()
    {
        return (string)mb_substr($this->work_start_time, 0, 2);
    }

    /**
     * 出社時間（分）取得
     *
     * @return string
     */
    public function getWorkStartMinuteAttribute()
    {
        return (string)mb_substr($this->work_start_time, 3, 2);
    }

    /**
     * 退社時間（時）取得
     *
     * @return string
     */
    public function getWorkEndHourAttribute()
    {
        return (string)mb_substr($this->work_end_time, 0, 2);
    }

    /**
     * 退社時間（分）取得
     *
     * @return string
     */
    public function getWorkEndMinuteAttribute()
    {
        return (string)mb_substr($this->work_end_time, 3, 2);
    }

    /**
     * 休憩時間取得
     *
     * @return string
     */
    public function getBreakMinuteAttribute()
    {
        if ($this->break_time === "01:00:00") {
            return "60";
        } elseif ($this->break_time === "00:00:00") {
            return "0";
        } else {
            return (string)mb_substr($this->break_time, 3, 2);
        }
    }
}
