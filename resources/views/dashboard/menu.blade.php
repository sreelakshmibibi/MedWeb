<nav class="main-nav" role="navigation">

    <!-- Mobile menu toggle button (hamburger/x icon) -->
    <input id="main-menu-state" type="checkbox" />
    <label class="main-menu-btn" for="main-menu-state">
        <span class="main-menu-btn-icon"></span> Toggle main menu visibility
    </label>

    <!-- Sample menu definition -->
    {{-- <ul id="main-menu" class="sm sm-blue">
        <li><a href="{{ route('home') }}"><i class="icon-Layout-4-blocks"><span class="path1"></span><span
                        class="path2"></span></i>Dashboard</a>
        </li>
        <li><a href="#"><i class="fa-regular fa-calendar-check"><span
                        class="path1"></span><span class="path2"></span></i>Appointments</a></li>
        <li><a href="">
                <i class="fa-solid fa-user-nurse">
                    <span class="path1"></span><span class="path2"></span><span class="path3"></span></i>Staffs</a>
            <ul>
                <li><a href="{{ route("staff.staff_list")}}"><i class="icon-Commit"><span class="path1"></span><span
                                class="path2"></span></i>Staff list</a></li>
                <li><a href="doctors.html"><i class="icon-Commit"><span class="path1"></span><span
                                class="path2"></span></i>Staff Details</a></li>
            </ul>
        </li>
        <li><a href=""><i class="fa-solid fa-hospital-user"><span class="path1"></span><span
                        class="path2"></span></i>Patients</a>
            <ul>
                <li><a href="#"><i class="icon-Commit"><span
                                class="path1"></span><span class="path2"></span></i>Patient List</a></li>
                <li><a href="patient_details.html"><i class="icon-Commit"><span class="path1"></span><span
                                class="path2"></span></i>Patient Details</a></li>
            </ul>
        </li>
        <li><a href="#"><i class="fa-solid fa-file-lines">
                    </span><span class="path2"></span></i>Reports</a>
            <ul>
                <li><a href="patients.html"><i class="icon-Commit"><span class="path1"></span><span
                                class="path2"></span></i>Patients</a></li>
                <li><a href="patient_details.html"><i class="icon-Commit"><span class="path1"></span><span
                                class="path2"></span></i>Billing</a></li>
            </ul>
        </li>

        <li><a href="reports.html"><i class="icon-Settings-1"><span class="path1"></span><span
                        class="path2"></span></i>Billing</a></li>

        <li><a href="#">
                <i class="fa-solid fa-gear">
                    <span class="path1"></span><span class="path2"></span></i>Settings
            </a>
            <ul>
                <li><a href="{{ route('settings.clinic') }}"><i class="icon-Commit"><span class="path1"></span><span
                                class="path2"></span></i>Clinics</a></li>
                <li><a href="{{ route('settings.department') }}"><i class="icon-Commit"><span
                                class="path1"></span><span class="path2"></span></i>Departments</a></li>
                <li><a href="{{ route('settings.medicine') }}"><i class="icon-Commit"><span class="path1"></span><span
                                class="path2"></span></i>Medicines</a></li>
                <li><a href="{{ route('settings.treatment_cost') }}"><i class="icon-Commit"><span
                                class="path1"></span><span class="path2"></span></i>Treatment Cost</a></li>
                <li><a href="patient_details.html"><i class="icon-Commit"><span class="path1"></span><span
                                class="path2"></span></i>Combo Offers</a></li>
            </ul>
        </li>
    </ul> --}}
    <ul id="main-menu" class="sm sm-blue">
        @if (auth()->check())
            @foreach ($menuItems as $item)
                @if (auth()->user()->hasAnyRole($item->roles->pluck('name')->toArray()))
                    <li>
                        <a href="{{ $item->route_name != '#' ? route($item->route_name) : '#' }}">
                            <i class="{{ $item->icon }}">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            {{ $item->name }}
                        </a>
                        @if ($item->name == 'Dashboard')
                            <ul>
                                @if(auth()->user()->is_admin)
                                    <li>
                                        <a href="{{route('dashboard.userType', 'admin')}}">
                                            <i class="icon-Commit">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        Admin Dashboard
                                        </a>
                                    </li>
                                @endif
                                @if(auth()->user()->is_doctor)
                                <li>
                                    <a href="{{route('dashboard.userType', 'doctor')}}">
                                        <i class="icon-Commit">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    Doctor Dashboard
                                    </a>
                                </li>
                                @endif
                                @if(auth()->user()->is_nurse)
                                <li>
                                    <a href="{{route('dashboard.userType', 'nurse')}}">
                                        <i class="icon-Commit">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    Nurse Dashboard
                                    </a>
                                </li>
                                @endif
                                @if(auth()->user()->is_reception)
                                <li>
                                    <a href="{{route('dashboard.userType', 'reception')}}">
                                        <i class="icon-Commit">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    Reception Dashboard
                                    </a>
                                </li>
                                @endif
                            </ul>
                        @else
                            @if ($item->children->count())
                                <ul>
                                    @foreach ($item->children as $child)
                                        @if (auth()->user()->hasAnyRole($child->roles->pluck('name')->toArray()))
                                            <li>
                                                <a href="{{ $child->route_name != '#' ? route($child->route_name) : '#' }}">
                                                    <i class="{{ $child->icon }}">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                    {{ $child->name }}
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            @endif
                        @endif
                    </li>
                @endif
            @endforeach
        @else
            <script type="text/javascript">
                window.location.href = "{{ route('login') }}"; // Redirect to login page
            </script>

        @endif
    </ul>
</nav>
