<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWorkRequest;
use App\Http\Requests\UpdateWorkRequest;
use App\Http\Requests\WorkRequest;
use Illuminate\Http\Request;
use App\Models\Work;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

require_once '../vendor/autoload.php';



class WorkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(WorkRequest $request)
    {
        //現在の年月
        $now = Carbon::now()->format('Y-m');

        if ($request->input('yearMonth')) {
            $yearMonth = $request->input('yearMonth');
            $year = (int)mb_substr($yearMonth, 0, 4);
            $month = (int)mb_substr($yearMonth, 5, 2);
        } else {
            $yearMonth = $now;
            $year = (int)mb_substr($now, 0, 4);
            $month = (int)mb_substr($now, 5, 2);
        }
        //月末日を調べる
        $lastOfMonth = Carbon::create($year, $month, 1)->lastOfMonth();
        $lastDay = (int)mb_substr($lastOfMonth, 8, 2);

        [$days, $weeks, $works, $workedTimes, $ids, $workedTimesSum] = $this->workedData($lastDay, $year, $month, $yearMonth);

        return view('works.index', [
            'yearMonth' => $yearMonth,
            'now' => $now,
            'days' => $days,
            'weeks' => $weeks,
            'works' => $works,
            'workedTimes' => $workedTimes,
            'workedTimesSum' => $workedTimesSum,
            'ids' => $ids,
        ]);
    }

    /**
     * エクスポート機能
     *
     * @param Request $request
     * @return void
     */
    public function export(WorkRequest $request)
    {
        $fileName = 'work_log.xlsx';
        $filePath = 'public/' . $fileName;
        //前回の出力分を削除
        if ($filePath) {
            Storage::delete($filePath);
        }
        //テンプレート読み込み
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('../storage/app/public/work_log_template.xlsx');
        $sheet = $spreadsheet->getActiveSheet();

        $yearMonth = $request->input('yearMonth');
        $year = (int)mb_substr($yearMonth, 0, 4);
        $month = (int)mb_substr($yearMonth, 5, 2);

        //月末日を調べる
        $lastOfMonth = Carbon::create($year, $month, 1)->lastOfMonth();
        $lastDay = (int)mb_substr($lastOfMonth, 8, 2);

        [$days, $weeks, $works, $workedTimes, $ids, $workedTimesSum] = $this->workedData($lastDay, $year, $month, $yearMonth);

        //書き込み
        //〇年〇月
        $sheet->setCellValue('A2', $year . '年' . $month . '月');
        //日
        $day_array = array_chunk($days, 1);
        $sheet->fromArray($day_array, NULL, 'A5');
        //曜日
        $week_array = array_chunk($weeks, 1);
        $sheet->fromArray($week_array, NULL, 'B5');
        foreach ($works as $work) {
            $workStartTimes[] = $work['work_start_time'];
            $workEndTimes[] = $work['work_end_time'];
            $breakTimes[] = $work['break_time'];
        }
        //出社時間
        $workStartTime_array = array_chunk($workStartTimes, 1);
        $sheet->fromArray($workStartTime_array, NULL, 'C5');
        //退社時間
        $workEndTime_array = array_chunk($workEndTimes, 1);
        $sheet->fromArray($workEndTime_array, NULL, 'D5');
        //休憩時間
        $breakTime_array = array_chunk($breakTimes, 1);
        $sheet->fromArray($breakTime_array, NULL, 'E5');
        //勤務時間
        $workedTime_array =  array_chunk($workedTimes, 1);
        $sheet->fromArray($workedTime_array, NULL, 'F5');
        //合計時間
        $sheet->setCellValue('F36', $workedTimesSum);
        //休憩時間・勤務時間・合計時間は小数点第2位まで表示
        $sheet->getStyle(('E:F'))->getNumberFormat()->setFormatCode('0.00');
        //印刷設定
        $sheet->getPageSetup()->setPrintArea('A1:F36');
        $sheet->getPageSetup()->setHorizontalCentered(true);
        $sheet->getPageSetup()->setVerticalCentered(true);

        //保存
        \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx')->save('../storage/app/public/work_log.xlsx');
        $mimeType = Storage::mimeType($filePath);
        $headers = [['Content-Type' => $mimeType]];
        //ダウンロード
        return Storage::download($filePath, $fileName, $headers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Models\Work  $work
     * @return \Illuminate\Http\Response
     */
    public function register(Work $work)
    {
        $yearMonth = Carbon::now()->format('Y-m');
        $year = (int)mb_substr($yearMonth, 0, 4);
        $month = (int)mb_substr($yearMonth, 5, 2);
        $lastOfMonth = Carbon::create($year, $month, 1)->lastOfMonth();
        $lastDay = (int)mb_substr($lastOfMonth, 8, 2);
        return view('works.register', [
            'work' => $work,
            'yearMonth' => $yearMonth,
            'lastDay' => $lastDay,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreWorkRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreWorkRequest $request)
    {
        $work = Work::create([
            'work_content' => $request->work_content,
            'date' => $request->date,
            'work_start_time' => $request->work_start_time,
            'work_end_time' => $request->work_end_time,
            'break_time' => $request->break_time,
        ]);

        return redirect(
            route('works.index', ['work' => $work])
        )->with('successMessage', '新規登録が完了しました。');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Work  $work
     * @return \Illuminate\Http\Response
     */
    public function show(Work $work)
    {
        $yearMonth = substr($work->date, 0, 7);
        $year = (int)mb_substr($yearMonth, 0, 4);
        $month = (int)mb_substr($yearMonth, 5, 2);
        $lastOfMonth = Carbon::create($year, $month, 1)->lastOfMonth();
        $lastDay = (int)mb_substr($lastOfMonth, 8, 2);

        return view('works.show', [
            'work' => $work,
            'yearMonth' => $yearMonth,
            'lastDay' => $lastDay,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Work  $work
     * @return \Illuminate\Http\Response
     */
    public function edit(Work $work)
    {
        $yearMonth = mb_substr($work->date, 0, 7);
        $year = (int)mb_substr($yearMonth, 0, 4);
        $month = (int)mb_substr($yearMonth, 5, 2);
        $lastOfMonth = Carbon::create($year, $month, 1)->lastOfMonth();
        $lastDay = (int)mb_substr($lastOfMonth, 8, 2);

        return view('works.edit', [
            'work' => $work,
            'yearMonth' => $yearMonth,
            'lastDay' => $lastDay,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateWorkRequest  $request
     * @param  \App\Models\Work  $work
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateWorkRequest $request, Work $work)
    {
        $work->work_content = $request->work_content;
        $work->date = $request->date;
        $work->work_start_time = $request->work_start_time;
        $work->work_end_time = $request->work_end_time;
        $work->break_time = $request->break_time;
        $work->update();

        return redirect(
            route('works.index', [
                'work' => $work,
                'yearMonth' => $request->yearMonth,
            ])
        )->with('successMessage', '更新が完了しました。');
    }

    /**
     * 日付・曜日・勤務データ取得
     *
     * @param [int] $lastDay
     * @param [int] $year
     * @param [int] $month
     * @param [string] $yearMonth
     * @return array
     */
    private static function workedData($lastDay, $year, $month, $yearMonth): array
    {
        $days = [];
        $weeks = [];
        for ($i = 1; $i <= $lastDay; $i++) {
            //日付
            $days[] = $i;
            //曜日
            $weeks[] = Carbon::create($year, $month, $i)->isoFormat('ddd');
            //DBからデータ取得して$worksへ格納
            $workTime = Work::whereDate('date', $yearMonth . '-' . $i)->first(['id', 'work_start_time', 'work_end_time', 'break_time']);
            if ($workTime) {
                $ids[] = $workTime['id'];
                $workStartTime = $workTime['work_start_time'];
                $workEndTime = $workTime['work_end_time'];
                if ($workTime['break_time'] === '01:00:00') {
                    $breakTime = number_format(1, 2);
                } else {
                    $breakTime = number_format((int)mb_substr($workTime['break_time'], 3, 2) / 60, 2);
                }
                $works[] = [
                    'work_start_time' => mb_substr($workStartTime, 0, 5),
                    'work_end_time' => mb_substr($workEndTime, 0, 5),
                    'break_time' => $breakTime
                ];
                //勤務時間算出して$workedTimesへ格納
                $workStartDate = Carbon::create($year, $month, $i, (int)mb_substr($workStartTime, 0, 2), (int)mb_substr($workStartTime, 3, 2));
                $workEndDate = Carbon::create($year, $month, $i, (int)mb_substr($workEndTime, 0, 2), (int)mb_substr($workEndTime, 3, 2));
                $workStartToEnd = (int)$workStartDate->diffInMinutes($workEndDate);
                $workedTimes[] = number_format($workStartToEnd / 60 - $breakTime, 2);
            } else {
                $ids[] = "";
                $works[] = [
                    'work_start_time' => "",
                    'work_end_time' => "",
                    'break_time' => ""
                ];
                $workedTimes[] = 0;
            }
            //勤務時間合計
            $workedTimesSum = number_format(array_sum($workedTimes), 2);
        }
        return [
            $days,
            $weeks,
            $works,
            $workedTimes,
            $ids,
            $workedTimesSum,
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Work  $work
     * @return \Illuminate\Http\Response
     */
    /**public function destroy(Work $work)
    {
        $work->delete();
        return redirect(route('works.index'));
    }
     */
}
