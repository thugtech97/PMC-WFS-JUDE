<div class="app-sidebar colored">
    <div class="sidebar-header">
        <a class="header-brand" href="index.html">
            <div class="logo-img">
               <img src="{{ asset('assets/img/workflow.png') }}" style="height: 80px;" alt="">
            </div>
        </a>
    </div>

    <div class="sidebar-content">
        <div class="nav-container">
            <nav id="main-menu-navigation" class="navigation-main">
                <div class="nav-lavel">
                    <img class="avatar" src="{{ asset('assets/img/users/user.png') }}" style="height:50px;" alt=""></a>&nbsp;{{ auth()->user()->name }}</div>
                <div class="nav-lavel"></div>

                @if(auth()->user()->user_type != 'ict')
                    <div class="nav-lavel">menus</div>
                     <div class="nav-item">
                        <a href="{{ route('transactions.index')}}"><i class="ik ik-layers"></i><span>Transactions</span></a>
                    </div> 
                    {{-- <div class="nav-item">
                        <a href="{{ route('transactions.index')}}"><i class="ik ik-layers"></i><span>Pending Transactions</span></a>
                    </div> --}}
                     <div class="nav-item">
                        <a href="{{ route('transactions.history')}}"><i class="ik ik-file"></i><span>History</span></a>
                    </div>
                @endif
                
                @if(auth()->user()->user_type == 'ict')
                    <div class="nav-lavel">Maintenance</div>
                    <div class="nav-item">
                        <a href="{{ route('approvers.index')}}"><i class="ik ik-users"></i><span>Approvers</span></a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('templates.index')}}"><i class="ik ik-clipboard"></i><span>Templates</span></a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('allowed-transactions.index')}}"><i class="ik ik-monitor"></i><span>Allowed Transactions</span></a>
                    </div>
                @endif

                <div class="nav-lavel">Support</div>
                <div class="nav-item">
                    <a href="javascript:void(0)"><i class="ik ik-book-open"></i><span>Documentation</span></a>
                </div>
            </nav>
        </div>
    </div>
    </div>