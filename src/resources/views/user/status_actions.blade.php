@if (!$attendance)
    <form action="{{ route('clock_in') }}" method="POST">
        @csrf
        <button class="btn btn-primary">出勤</button>
    </form>

@else
    @switch($attendance->status)

        @case(1)
            <form action="{{ route('clock_out') }}" method="POST">
                @csrf
                <button class="btn btn-danger">退勤</button>
            </form>

            <form action="{{ route('break_in') }}" method="POST">
                @csrf
                <button class="btn btn--warning">休憩入</button>
            </form>
        @break

        @case(2)
            <form action="{{ route('break_out') }}" method="POST">
                @csrf
                <button class="btn btn--warning">休憩戻</button>
            </form>
        @break

        @case(3)
            {{-- 退勤済はボタンなし --}}
        @break

    @endswitch
@endif
