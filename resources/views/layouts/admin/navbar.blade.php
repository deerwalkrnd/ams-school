<!-- Navbar start -->
<header class="header" id="header">
    <div class="header_toggle" onclick="onNavToggleClick(event)"><i id="header-toggle" class="bx bx-menu"></i> </div>
    {{-- <div class="header_img"> <img src="https://osdeibi.dev/assets/img/faces/logoround.png" alt=""> </div> --}}
</header>
<div class="l-navbar" id="nav-bar">
    <nav class="leftnav">
        <div class="nav_list">

            <a href="/home" class="nav_link">
                <i class='bx bxs-dashboard bx-sm'></i><span class="nav_name">&nbsp;Dashboard</span>
            </a>
            <a href="{{ route('attendance.index') }}" class="nav_link">
                <i class='bx bx-spreadsheet bx-sm' aria-hidden="true"></i>
                <span class="nav_name">Attendance</span>
            </a>
            <a href="{{ route('user.index') }}" class="nav_link">
                <i class='bx bx-file bx-sm' aria-hidden="true"></i><span class="nav_name">&nbsp;Forms</span>
            </a>

            <a href="{{ route('report.index') }}" class="nav_link">
                <i class='bx bx-bar-chart-alt-2 bx-sm'></i><span class="nav_name">&nbsp;Report</span>
            </a>

            <a href="{{ route('change-password') }}" class="nav_link">
                <i class="bx bx-key bx-sm" aria-hidden="true"></i><span class="nav_name">&nbsp;Change Password</span>
            </a>

            <div class="logout_button">
                <form action="{{ route('logout') }}" method="post">
                    @csrf
                    <button type="submit" class="btn nav_link "><i class='bx bx-log-out bx-sm'></i><span
                            class="nav_name">&nbsp;&nbsp;Logout</span></button>
                </form>
            </div>

        </div>
    </nav>
</div>
<!-- Navbar end -->
