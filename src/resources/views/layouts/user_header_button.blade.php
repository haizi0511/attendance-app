      <form class="header__search" action="{{ url()->current() }}" method="get">
              @csrf
        <input class=header__search-box type="text" name="keyword" value="{{ request('keyword') }}" placeholder="なにをお探しですか？">
      </form>
      <ul  class="header-nav">
        <li class="header-nav__item">
          @auth
            <form class="form" action="/logout" method="post">
                @csrf
              <button class="header-nav__button">ログアウト</button>
            </form>
          @endauth
          @guest
            <form class="form" action="/logout" method="post">
                @csrf
              <a href="/login">ログイン</a>
            </form>
          @endguest
        </li>
        <li><a href="/mypage" class="header__mypage-button">マイページ</a></li>
        <li><a href="/sell" class="header__sell-button">出品</a></li>
      </ul>