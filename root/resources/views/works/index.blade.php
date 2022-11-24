@extends('base')

@section('title', '勤怠登録')
@section('subtitle', '一覧')
@section('css')

@endsection

@section('content')

@if($yearMonth === $now)
<div class="d-grid gap-2 d-md-flex justify-content-md-end" style="margin-bottom: 5px;">
  <a href="{{ route('works.register') }}" class="btn btn-outline-warning me-md-2" type="button">登録</a>
</div>
@endif
<div class="container">
  <div class="d-grid gap-2 d-md-flex justify-content-md-start" style="margin-bottom: 5px;">
    <form action="{{ route('works.index') }}" method="GET">
      <input type="month" id="inputYearMonth" name="yearMonth" value="{{ $yearMonth }}" max="{{ $now }}">
      <button class="btn btn-secondary" id="buttonDisplay" type="submit">表示</button>
    </form>
  </div>
  <div class="d-grid gap-2 d-md-flex justify-content-md-start" style="margin-bottom: 5px;">
    <label for="inputYearMonth" style="font-size: 20px;">
      <span id="year"></span>
      年
      <span id="month"></span>
      月
    </label>
  </div>
</div>
<table class="table table-bordered">
  <thead>
    <tr>
      <th scope="col">日</th>
      <th scope="col">曜日</th>
      <th scope="col">出社時間</th>
      <th scope="col">退社時間</th>
      <th scope="col">休憩時間</th>
      <th scope="col">勤務時間</th>
      <th scope="col">詳細</th>
    </tr>
  </thead>
  <tbody>
    @foreach($days as $day)
    <tr>
      <th>{{ $day }}</th>
      <th>{{ $weeks[$day-1] }}</th>
      <td>{{ $works[$day-1]['work_start_time']  }}</td>
      <td>{{ $works[$day-1]['work_end_time'] }}</td>
      <td>{{ $works[$day-1]['break_time'] }}</td>
      @if($works[$day-1]['work_start_time'] != '')
      <td>{{ $workedTimes[$day-1] }}</td>
      <td><a href="{{ route('works.show', ['work' => $ids[$day-1], 'yearMonth' => $yearMonth,]) }}" class="btn btn-primary">詳細</a></td>
      @else
      <td></td>
      <td></td>
      @endif
    </tr>
    @endforeach
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    <th style="text-align: center;">合計</th>
    <th>{{ $workedTimesSum }}</th>
  </tbody>
</table>
@if($yearMonth != $now)
<div class="d-grid gap-2 d-md-flex justify-content-md-end" style="margin-bottom: 5px;">
  <form action="{{ route('works.export') }}" method="POST">
    @csrf
    <input type="hidden" class="form-control" name="yearMonth" value="{{ $yearMonth }}">
    <button class="btn btn-info" name="export" type="submit">エクスポート</button>
  </form>
</div>
@endif
@endsection

@section('script')

@endsection