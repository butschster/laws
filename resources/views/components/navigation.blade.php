<!-- Navigation -->
<div class="collapse navbar-collapse align-items-center flex-sm-row g-pt-10 g-pt-5--lg" id="navBar">
    <ul class="navbar-nav text-uppercase g-font-weight-600 ml-auto">
        <!-- Authentication Links -->
        @guest
        <li class="nav-item g-mx-20--lg">
            <a class="nav-link px-0" href="{{ route('login') }}">Войти</a>
        </li>
        <li class="nav-item g-mx-20--lg">
            <a class="nav-link px-0" href="{{ route('register') }}">Регистрация</a>
        </li>
        @else
        <li class="nav-item g-mx-20--lg">
            <a class="nav-link px-0" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                Выход
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        </li>
        @endguest
    </ul>
</div>
<!-- End Navigation -->