@extends('base')

@section('css')

@endsection

@section('content')

<form action="{{ route('works.update', ['work' => $work]) }}" method="POST">
    @method('PUT')
    @csrf
    <input type="hidden" class="form-control" name="yearMonth" value="{{ $yearMonth }}">
    @include('works.form-controls', ['readOnly' => false])
    <div class="form-group row">
        <div class="col-5 text-right">
            <button type="submit" class="btn btn-primary">更新</button>
        </div>
        <div class="col-5 text-right" style="margin-top: 10px;">
            <a href="{{ route('works.index', ['yearMonth' => $yearMonth]) }}" class="btn btn-outline-secondary">戻る</a>
        </div>
    </div>
</form>



@endsection

@section('script')

@endsection