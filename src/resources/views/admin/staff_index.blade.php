@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/staff_index.css') }}">
@endsection

@section('content')
<header class="admin-header">
    <div class="admin-header__left">
        <img src="{{ asset('img/COACHTECHヘッダーロゴ.png') }}" alt="COACHTECH" class="admin-header__logo">
    </div>

    <ul class="admin-header__nav">
        <li><a href="/admin/attendance/list">勤怠一覧</a></li>
        <li><a href="/admin/staff/list">スタッフ一覧</a></li>
        <li><a href="/admin/attendance_request">申請一覧</a></li>
        <li>
            <form action="/logout" method="post">
                @csrf
                <button class="logout-btn">ログアウト</button>
            </form>
        </li>
    </ul>
</header>

<div class="staff-list">

    <h1 class="staff-list__title">スタッフ一覧</h1>

    <table class="staff-table">
        <thead>
            <tr>
                <th>名前</th>
                <th>メールアドレス</th>
                <th>月次勤怠</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($staffs as $staff)
                <tr>
                    <td>{{ $staff->name }}</td>
                    <td>{{ $staff->email }}</td>
                    <td>
                        <a href="{{ route('admin.staff.detail', $staff->id) }}" class="staff-table__detail-link">
                            詳細
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
