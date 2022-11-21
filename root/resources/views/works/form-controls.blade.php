<div class="form-group row">
    <label for="inputDate" class="col-sm-2 col-form-label">日付</label>
    <div class="col-sm-2">
        <select class="form-control @error('date') is-invalid @enderror" id="selectDate" name="date" {{ $readOnly ? ' disabled="disabled"' : '' }}>
            @for($i=1; $i<=$lastDay; $i++) @if($i<10) <option value="0{{(string)$i}}" {{ old('date', $work->date_to_day)==='0'.(string)$i ? 'selected' : '' }}>{{$i}}</option>
                @else
                <option value="{{(string)$i}}" {{ old('date', $work->date_to_day)===(string)$i ? 'selected' : '' }}>{{$i}}</option>
                @endif
            @endfor
        </select>
    </div>
    <div class="col-form-label">
        <span>日</span>
    </div>
</div>
<div class="form-group row">
    <label for="inputWorkStartHour" class="col-sm-2 col-form-label">出社時間</label>
    <div class="col-sm-2">
        <select class="form-control @error('work_start_time') is-invalid @enderror @error('workedTimes') is-invalid @enderror" id="selectWorkStartHour" name="work_start_hour" {{ $readOnly ? ' disabled="disabled"' : '' }}>
            @for($i=0; $i<=23; $i++) @if($i<10) <option value="0{{(string)$i}}" @if(old('work_start_hour', $work->work_start_hour)==='0'.(string)$i) selected @endif>{{$i}}</option>
                @else
                <option value="{{(string)$i}}" @if(old('work_start_hour', $work->work_start_hour)===(string)$i) selected @endif>{{$i}}</option>
                @endif
            @endfor
        </select>
    </div>
    <div class="col-form-label">
        <span>時</span>
    </div>
    <div class="col-sm-2">
        <select class="form-control @error('work_start_time') is-invalid @enderror @error('workedTimes') is-invalid @enderror" id="selectWorkStartMinutes" name="work_start_minutes" {{ $readOnly ? ' disabled="disabled"' : '' }}>
            @for($i=0; $i<=45; $i=$i+15) @if($i===0) <option value="0{{(string)$i}}" @if(old('work_start_minutes', $work->work_start_minute)==='0'.(string)$i) selected @endif>0{{$i}}</option>
                @else
                <option value="{{(string)$i}}" @if(old('work_start_minutes', $work->work_start_minute)===(string)$i) selected @endif>{{$i}}</option>
                @endif
            @endfor
        </select>
    </div>
    <div class="col-form-label">
        <span>分</span>
    </div>
</div>
<div class="form-group row">
    <label for="inputWorkEndHour" class="col-sm-2 col-form-label">退社時間</label>
    <div class="col-sm-2">
        <select class="form-control @error('work_end_time') is-invalid @enderror @error('workedTimes') is-invalid @enderror" id="selectWorkEndHour" name="work_end_hour" {{ $readOnly ? ' disabled="disabled"' : '' }}>
            @for($i=0; $i<=23; $i++) @if($i<10) <option value="0{{(string)$i}}" @if(old('work_end_hour', $work->work_end_hour)==='0'.(string)$i) selected @endif>{{$i}}</option>
                @else
                <option value="{{(string)$i}}" @if(old('work_end_hour', $work->work_end_hour)===(string)$i) selected @endif>{{$i}}</option>
                @endif
            @endfor
        </select>
    </div>
    <div class="col-form-label">
        <span>時</span>
    </div>
    <div class="col-sm-2">
        <select class="form-control @error('work_end_time') is-invalid @enderror @error('workedTimes') is-invalid @enderror" id="selectWorkEndMinutes" name="work_end_minutes" {{ $readOnly ? ' disabled="disabled"' : '' }}>
            @for($i=0; $i<=45; $i=$i+15) @if($i===0) <option value="0{{(string)$i}}" @if(old('work_end_minutes', $work->work_end_minute)==='0'.(string)$i) selected @endif>0{{$i}}</option>
                @else
                <option value="{{(string)$i}}" @if(old('work_end_minutes', $work->work_end_minute)===(string)$i) selected @endif>{{$i}}</option>
                @endif
            @endfor
        </select>
    </div>
    <div class="col-form-label">
        <span>分</span>
    </div>
</div>
<div class="form-group row">
    <label for="inputBreakTime" class="col-sm-2 col-form-label">休憩時間</label>
    <div class="col-sm-2">
        <select class="form-control @error('break_time', 'workedTimes') is-invalid @enderror @error('workedTimes') is-invalid @enderror" id="selectBreakTime" name="break_time" {{ $readOnly ? ' disabled="disabled"' : '' }}>
            @for($i=0; $i<=60; $i=$i+15) @if($i===0) <option value="0{{(string)$i}}" @if(old('break_time', $work->break_minute)==='0'.(string)$i) selected @endif>{{$i}}</option>
                @else
                <option value="{{(string)$i}}" @if(old('break_time', $work->break_minute)==$i) selected @endif>{{$i}}</option>
                @endif
            @endfor
        </select>
    </div>
    <div class="col-form-label">
        <span>分</span>
    </div>
</div>
<div class="form-group row">
    <label for="inputWorkContent" class="col-sm-2 col-form-label">業務内容</label>
    <div class="col-sm-10">
        <input type="textarea" class="form-control @error('work_content') is-invalid @enderror" id="inputWorkContent" name="work_content" placeholder="業務内容を入力してください。" value="{{ old('work_content', $work->work_content ?? '') }}" {{ $readOnly ? ' readonly="readonly"' : '' }}">
    </div>
</div>
<div class="form-group row">
    @foreach ($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
</div>