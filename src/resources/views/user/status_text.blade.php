@if (!$attendance)
    <p class="status-text">勤務外</p>
@else
    @switch($attendance->status)
        @case(1)
            <p class="status-text">出勤中</p>
        @break

        @case(2)
            <p class="status-text">休憩中</p>
        @break

        @case(3)
            <p class="status-text">退勤済</p>
        @break
    @endswitch
@endif