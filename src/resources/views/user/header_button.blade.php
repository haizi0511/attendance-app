<form class="header__search" action="{{ url()->current() }}" method="get">
    @csrf
    <input class=header__search-box type="text" name="keyword" value="{{ request('keyword') }}" placeholder="なにをお探しですか？">
</form>
<ul  class="header-nav">
    @auth
    <li><a href="/attendance/list" class="header__mypage-button">勤怠一覧</a></li>
    <li><a href="/attendance_request/list" class="header__request-button">申請</a></li>
    <li class="header-nav__item">
        <form class="form" action="/logout" method="post">
            @csrf
            <button class="header-nav__button">ログアウト</button>
        </form>
    </li>
    @endauth
</ul>