@extends('base')

@section('title', '勤怠登録')
@section('subtitle', '詳細')
@section('css')

@endsection

@section('content')

<form>
    @include('works.form-controls', ['readOnly' => true])
    <div class="form-group row">
        <div class="col-5 text-right">
            <a href="{{ route('works.edit', ['work' => $work]) }}" class="btn btn-primary">編集</a>
        </div>
        <div class="col-5 text-right" style="margin-top: 10px;">
            <a href="{{ route('works.index', ['yearMonth' => $yearMonth]) }}" class="btn btn-outline-secondary">戻る</a>
        </div>
    </div>
</form>



@endsection

@section('script')

@endsection