@extends('base')

@section('css')

@endsection

@section('content')
@if ($errors->any())
<div class="alert alert-danger mt-3">
  <ul>
    @foreach ($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
  </ul>
</div>
@endif
@if($yearMonth === $now)
<div class="d-grid gap-2 d-md-flex justify-content-md-end" style="margin-bottom: 5px;">
  <a href="{{ route('works.register') }}" class="btn btn-outline-warning me-md-2" type="button">登録</a>
</div>
@endif
<div class="container">
  <div class="d-grid gap-2 d-md-flex justify-content-md-start" style="margin-bottom: 5px;">
    <form action="{{ route('works.index') }}" method="GET">
      <input type="month" name="yearMonth" value="{{$yearMonth}}">
      <button class="btn btn-secondary" type="submit">表示</button>
      @if($yearMonth != $now)
      <button class="btn btn-info" name="export" type="submit">エクスポート</button>
      @endif
    </form>
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
    @foreach($days as $day) <tr>
      <th>{{ $day }}</th>
      <th>{{ $weeks[$day-1] }}</th>
      <td>{{ $works[$day-1]['work_start_time']  }}</td>
      <td>{{ $works[$day-1]['work_end_time'] }}</td>
      <td>{{ $works[$day-1]['break_time'] }}</td>
      <td>{{ $workedTimes[$day-1] }}</td>
      @if($works[$day-1]['work_start_time'] != '')
      <td><a href="{{ route('works.show', [
        'work' => $ids[$day-1],
        'yearMonth' => $yearMonth,
        ]) }}" class="btn btn-primary">詳細</a></td>
      @else
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
@endsection

@section('script')

@endsection