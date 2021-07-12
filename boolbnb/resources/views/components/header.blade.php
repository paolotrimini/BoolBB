<header>
    <div class="container-header">

        <div class="logo-header">
            <a href="{{ route('index') }}"><img src="{{ asset('storage/images/logo.svg') }}" alt="Logo BoolB&B"></a>
        </div>

        <div class="searchbar-header">
            <form method="GET" action="{{ route('search') }}">

                @csrf
                @method('GET')

                <input id="searchString" type="text" class="form-control" name="searchString"
                    placeholder="Cerca una città" required>

                <button type="submit" class="searchButton">
                    <i class="fa fa-search"></i>
                </button>

            </form>

        </div>

        <div class="user-header">
            @guest
                <button onclick="myFunction()" class="hamburger dropbtn" id="hamburger">

                    <i class="fas fa-bars"></i>

                </button>
                <div class="nav-ul dropdown-content" id="nav-ul">

                    <a class="login-header-btn" href="{{ route('login') }}">Login</a>
                    <a class="register-header-btn" href="{{ route('register') }}">Register</a>

                </div>
            @else
                <div id="user-logged">
                    <button onclick="myFunction()" class="hamburger dropbtn" id="hamburger">

                        <i class="fas fa-bars"></i>

                    </button>
                    <ul class="nav-ul dropdown-content" id="nav-ul">
                        <li>
                            <h3 class="hello-user">
                                {{ Auth::user()->firstname }}
                            </h3>
                        </li>

                        <li>
                            <a class="dashboard-header-btn"
                                href="{{ route('dashboard', ['id' => Auth::id()]) }}">Dashboard</a>
                        </li>

                        <li>
                            <a class="btn logout-header-btn" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                        </li>
                    </ul>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            @endguest
        </div>
    </div>

</header>

<script>
    function myFunction() {
        document.getElementById("nav-ul").classList.toggle("show");
    }
</script>
