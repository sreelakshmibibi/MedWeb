@php
    $logoPath = session('logoPath');
    $clinicName = session('clinicName');
    $username = session('username');
    $role = session('role');
    $staffPhoto = session('staffPhoto');
    $pstaffidEncrypted = session('pstaffidEncrypted');
    $pstaffidEncrypted = isset($pstaffidEncrypted) ? $pstaffidEncrypted : '';
@endphp
<header class="main-header">
    <div class="inside-header">
        <div class="d-flex align-items-center logo-box justify-content-start">
            <!-- Logo -->
            <a href="#" class="logo">
                <!-- logo-->
                <div class="logo d-inline-flex align-items-center">
                    {{-- <span> --}}
                    @if ($logoPath)
                        <img src="{{ asset('storage/' . $logoPath) }}" alt="Logo"
                            style="width: 50px; margin-right:10px;">
                    @else
                        {{-- <img src="{{ asset('images/serieux_logo.png') }}" alt="Logo"
                            style="width: 50px; margin-right:10px; border-radius:50%;"> --}}
                        <img src="{{ asset('images/logo-lt.png') }}" alt="Logo">
                    @endif
                    @if ($clinicName)
                        <h4>{{ $clinicName }}</h4>
                    @else
                        <h4 class="align-self-center medweb_text"
                            style="margin-left:-5px; margin-bottom: 0; font-size: 1.75rem;">
                            MedWeb</h4>
                        {{-- <span >MedWeb</span> --}}
                    @endif
                </div>
            </a>
        </div>
        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <div class="app-menu">
                <ul class="header-megamenu nav">
                    <li class="btn-group d-lg-inline-flex d-none">
                        <div class="app-menu">
                            <div class="d-none search-bx mx-5">
                                <form>
                                    <div class="input-group">
                                        <input type="search" class="form-control" placeholder="Search"
                                            id="searchheader" aria-label="Search" aria-describedby="button-addon2">
                                        <div class="input-group-append">
                                            <button class="btn" type="submit" id="button-addon3"><i
                                                    class="icon-Search"><span class="path1"></span><span
                                                        class="path2"></span></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="navbar-custom-menu r-side">
                <ul class="nav navbar-nav">
                    <!-- User Account-->
                    <li class="dropdown user user-menu">
                        <a href="#"
                            class="waves-effect waves-light dropdown-toggle w-auto l-h-12 bg-transparent p-0 no-shadow"
                            data-bs-toggle="dropdown" title="User">
                            <div class="d-flex pt-1">
                                <div class="text-end me-10">
                                    {{-- <p class="pt-5 fs-14 mb-0 fw-700 text-primary">Johen Doe</p> --}}
                                    {{-- <small class="fs-10 mb-0 text-uppercase text-mute">Admin</small> --}}

                                    @if ($username)
                                        <p class="pt-5 fs-14 mb-0 fw-700 text-primary">{{ $username }}</p>
                                        <small class="fs-10 mb-0 text-uppercase text-mute">{{ $role }}</small>
                                    @else
                                        <p class="pt-5 fs-14 mb-0 fw-700 text-primary">Username</p>
                                        <small class="fs-10 mb-0 text-uppercase text-mute">Role</small>
                                    @endif

                                </div>
                                @if ($staffPhoto)
                                    <img src="{{ asset('storage/' . $staffPhoto) }}"
                                        class="avatar rounded-10 bg-primary-light h-40 w-40" alt="" />
                                @else
                                    <img src="{{ asset('images/svg-icon/user.svg') }}"
                                        class="avatar rounded-10 bg-primary-light h-40 w-40" alt="" />
                                @endif

                            </div>
                        </a>

                        <ul class="dropdown-menu animated flipInX">
                            <li class="user-body">
                                @if ($pstaffidEncrypted)
                                    {{-- <a class="dropdown-item"
                                        href="{{ route('staff.staff_list.view', $pstaffidEncrypted) }}"><i
                                            class="ti-user text-muted me-2"></i> Profile</a> --}}
                                    <a class="dropdown-item"
                                        href="{{ route('staff.staff_list.view', ['id' => $pstaffidEncrypted, 'from' => 'profile']) }}"><i
                                            class="ti-user text-muted me-2"></i> Profile</a>
                                @else
                                    <a class="dropdown-item" href="#"><i class="ti-user text-muted me-2"></i>
                                        Profile</a>
                                @endif
                                <!-- <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                this.closest('form').submit();"><i
                                            class="ti-lock text-muted me-2"></i>
                                        Logout</a>
                                </form> -->
                                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                    @csrf
                                    <a class="dropdown-item" href="#" id="logout-button">
                                        <i class="ti-lock text-muted me-2"></i> Logout
                                    </a>
                                </form>

                            </li>
                        </ul>
                    </li>
                    <li class="btn-group nav-item d-lg-inline-flex d-none">
                        <a href="#" data-provide="fullscreen"
                            class="waves-effect waves-light nav-link full-screen btn-warning-light" title="Full Screen">
                            <i class="icon-Position"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>
