<header class="header-top wfs-bg box-shadow" header-theme="light">
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <div class="d-flex justify-content-start align-items-center">
                <img class="mr-2" height="40" src="{{ asset('assets/img/pmc-logo-solo.png') }}">
                <h3 class="main-heading-text wfs-text-default">WORK<span class="primary-color">FLOW</span> SYSTEM</h3>
            </div>
            <div class="top-menu d-flex align-items-center">
                <!-- <button type="button" class="nav-link ml-10 right-sidebar-toggle"><i class="ik ik-message-square"></i><span class="badge bg-success">3</span></button> -->

                <nav id="main-menu-navigation" class="navigation-main d-flex align-items-center mr-4">
                    @if(auth()->user()->user_type != 'ict')
                         <div class="menu-item mr-2">
                            <!-- <a href="{{ route('transactions.index')}}"></i><span class="nav-text-class @if(\Route::current()->getName() == 'transactions.index') active @endif">Transactions</span></a> -->
                            <a href="javascript:void(0)"></i><span class="nav-text-class">Transactions</span></a>
                            @php
                                $subMenuItems = [
                                    'OREM'     => 'OREM',
                                    'IMP'      => 'IMP',
                                    'OSTR'     => 'OSTR',
                                    'GATEPASS' => 'Gatepass',
                                    'HK'       => 'Housekeeping',
                                    'VBS'      => 'VBS',
                                ];

                                $userTransTypes = [];
                                if (!empty(Auth::user()->trans_types)) {
                                    $userTransTypes = explode('|', Auth::user()->trans_types);
                                }
                            @endphp

                            <ul class="sub-menu" style="transform: translate(0px, 7px);">
                                @foreach($subMenuItems as $code => $label)
                                    @if(in_array($code, $userTransTypes))
                                        <li class="px-2">
                                            <a class="menu-link r-menu" href="{{ route('transactions.index_new', ['details' => $code]) }}">
                                                <b>{{ $label }}</b>
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>

                        </div> 
                        {{-- <div class="nav-item mr-2">
                            <a href="{{ route('transactions.index')}}"></i><span class="nav-text-class @if(\Route::current()->getName() == 'transactions.index') active @endif">Pending Transactions</span></a>
                        </div> --}}
                         <div class="nav-item mr-2">
                            <a href="{{ route('transactions.history')}}"></i><span class="nav-text-class @if(\Route::current()->getName() == 'transactions.history') active @endif">History</span></a>
                        </div>
                    @endif
                    @if(auth()->user()->user_type == 'ict')
                        <div class="nav-item mr-2">
                            <a href="{{ route('approvers.index')}}" class="@if(\Route::current()->getName() == 'tapprovers.index') active @endif"></i><span>Approvers</span></a>
                        </div>
                        <div class="nav-item mr-2">
                            <a href="{{ route('templates.index')}}" class="@if(\Route::current()->getName() == 'templates.index') active @endif"></i><span>Templates</span></a>
                        </div>
                        <div class="nav-item mr-2">
                            <a href="{{ route('allowed-transactions.index')}}" class="@if(\Route::current()->getName() == 'allowed-transactions.index') active @endif"></i><span>Allowed Transactions</span></a>
                        </div>
                    @endif
                    <div class="nav-item mr-2">
                        <a href="javascript:void(0)"></i><span class="nav-text-class">Documentation</span></a>
                    </div>
                </nav>

                <div class="dropdown">
                    <a class="dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <!-- <img class="avatar" src="{{ asset('assets/img/user.jpg') }}" alt=""> -->
                        <img class="avatar" src="{{ asset('assets/img/users/user.png') }}" style="height:35px; width: 35px; border-radius: 100%; background-color: #c7c7c7;" alt="">
                    </a>                            
                    <div class="dropdown-menu dropdown-menu-right profile-dropdown shadow" id="profileDropdown" aria-labelledby="userDropdown" style="padding: 14px; border-top: 1px solid #e1e1e1;">
                        <span>
                            <b>{{ auth()->user()->name }}</b>
                        </span>
                        <hr style="margin: 8px 0px;" />
                        <a class="dropdown-item" style="cursor: pointer;" id="navbar-fullscreen">
                            <i class="ik ik-maximize mr-2"></i>
                            Switch Screen Mode
                        </a>
                        <a class="dropdown-item right-sidebar-toggle" id="theme_customizer" style="cursor: pointer;">
                            <i class="ik ik-star dropdown-icon"></i>
                            Theme Customizer
                        </a>
                        <a class="dropdown-item" href="{{ route('change.password') }}">
                            <i class="ik ik-lock dropdown-icon"></i>
                            Change Password
                        </a>
                        <a class="dropdown-item" href="{{ route('logout') }}">
                            <i class="ik ik-power dropdown-icon"></i>
                            Logout
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</header>

<script type="text/javascript">

        document.getElementById("theme_customizer").addEventListener("click", function() {
            let dp = document.getElementById("profileDropdown");
            dp.classList.toggle('show');
        });

        function set_theme() {
            let theme = localStorage.getItem("theme");
            if (!theme) { theme = "sky-theme"; }
            document.getElementById("wfs-wrapper").setAttribute("data-theme", theme);
        }

        window.onload = set_theme();

</script>