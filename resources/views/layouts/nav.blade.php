<nav class="navbar navbar-expand-md navbar-dark shadow-sm" style="background-color:var(--main-bg-color);">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="/icons/favicon.png" class=" img-fluid" /> {{ config('app.name', 'Laravel') }}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav me-auto">
@auth
                <li class="nav-item">
                    <a href="{{ route('date.index') }}" class="nav-link">
                        Терміни
                    </a>
                    <!-- /.nav-link -->
                </li>
                <!-- /.nav-item -->



                <li class="nav-item">
                    <a href="{{ route('product.index') }}" class="nav-link">
                        Продукти
                    </a>
                    <!-- /.nav-link -->
                </li>
                <!-- /.nav-item -->
                <li class="nav-item">
                    <a href="{{route('import.csv')}}" class="nav-link">
                        Імпортувати терміни
                    </a>
                    <!-- /.nav-link -->
                </li>
                <!-- /.nav-item -->

                    <li class="nav-item">
                        <a href="{{ route('group.index') }}" class="nav-link">
                            Відділи
                        </a>
                        <!-- /.nav-link -->
                    </li>
                    <!-- /.nav-item -->

                @if (auth()->user()->isAdmin())
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown1" class="nav-link dropdown-toggle" href="#" role="button"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            Адмін функції
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="{{route('admin.index')}}" class="dropdown-item">
                                Панедь
                            </a>
                            <a href="{{route('admin.user.index')}}" class="dropdown-item">
                                Користувачі
                            </a> <!-- /.dropdown-item -->
                            <a href="{{route('admin.options')}}" class="dropdown-item">
                                Налаштування
                            </a> <!-- /.dropdown-item -->
                        </div>
                        <!-- /.dropdown-menu dropdown-menu-end -->
                    </li>
                    <li class="nav-item">
                    <a href="{{ route('shop.index') }}" class="nav-link">
                        Магазини
                    </a>
                    <!-- /.nav-link -->
                </li>
                <!-- /.nav-item -->



                    <li class="nav-item">
                        <a href="{{route('tools')}}" class="nav-link">
                        Інструменти
                        </a> <!-- /.nav-link -->
                    </li>
                    <!-- /.nav-item -->
                @endif



@endauth

            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto">
                <!-- Authentication Links -->
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Вхід</a>
                        </li>
                    @endif

                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Створити акаунт</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <a href="{{route('date.expired')}}" class="dropdown-item">
                                Протермін
                            </a>
                            <!-- /.btn btn-success -->
                            <a href="{{ route('options.index') }}" class="dropdown-item">
                                Налаштування
                            </a> <!-- /.dropdown-item -->
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                Вихід
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
