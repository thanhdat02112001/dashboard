{{-- @php
    $ApproveConfigCache = ApproveConfigCache::getCache();
    $ApproveConstants = app(\Modules\approveRequest\constants\Approve::class);
@endphp --}}

<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element text-center">
                    <img alt="image" class="rounded-circle" src="{{ asset('images/profile_small.jpg') }}"/>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="javascript:void(0)">
                        <span class="block m-t-xs font-bold">{{ \Illuminate\Support\Facades\Auth::user() ? \Illuminate\Support\Facades\Auth::user()->name : '' }}</span>
                        <span class="text-muted text-xs block">{{ \Illuminate\Support\Facades\Auth::user() ?  \Illuminate\Support\Facades\Auth::user()->email : '' }} <b class="caret"></b></span>
                    </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a class="dropdown-item" href="{{ route('user.profile.index') }}">Profile</a></li>
                        <li class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                        </li>
                    </ul>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
                <div class="logo-element">
                    Brand
                </div>
            </li>
            <li class="{{ request()->is('dashboard*') ? 'active' : null }}">
                <a href="#"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboards</span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                    <li class="{{ request()->is('dashboard/index*') ? 'active' : null }}"><a href="">Overview</a></li>
                    {{-- {{ route('dashboard.index') }} --}}
                    @can('canViewDashboardMerchant')
                        <li class="{{ request()->is('dashboard/merchant*') ? 'active' : null }}"><a href="{{ route('dashboard.merchant') }}">Merchant</a></li>
                    @endcan
                </ul>
            </li>

            @canany(['canViewListTransaction', 'canViewListTransactionInstallment'])
                <li class="{{ request()->is(['transaction*']) ? 'active' : null }}">
                    <a href="#"><i class="fa fa-money"></i> <span class="nav-label">Qu???n l?? giao d???ch</span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                        @can('canViewListTransaction')
                            <li class="{{ request()->is('transaction') || request()->is('transaction/view*') ? 'active' : null }}"><a href="{{ route('transaction.transaction.index') }}">Tra c???u</a></li>
                        @endcan
                        @can('canViewListTransactionInstallment')
                            <li class="{{ request()->is('transaction/installment*') ? 'active' : null }}"><a href="{{ route('transaction.installment.index') }}">Danh s??ch tr??? g??p</a></li>
                        @endcan
                        @can('canViewListOrder')
                            <li class="{{ request()->is('transaction/order/*') ? 'active' : null }}"><a href="{{ route('transaction.order.index') }}">Tra c???u m?? y??u c???u</a></li>
                            <li class="{{ request()->is('transaction/order-information') ? 'active' : null }}"><a href="{{ route('transaction.order.information') }}">Th??ng tin ????n h??ng BNPL</a></li>
                        @endcan
                        @can('canViewListTransactionDisbursement')
                            <li class="{{ request()->is('transaction/disbursement*') ? 'active' : null }}"><a href="{{ route('transaction.disbursement.index') }}">Danh s??ch giao d???ch chi h???</a></li>
                        @endcan
                        @can('canViewListDisburseRick')
                            <li class="{{ request()->is('transaction/approve-risk*') ? 'active' : null }}"><a href="{{ route('transaction.disbursement.approveRisk') }}">Duy???t giao d???ch chi h??? r???i ro</a></li>
                        @endcan

                    </ul>
                </li>
            @endcanany

            
        </ul>
    </div>
</nav>
