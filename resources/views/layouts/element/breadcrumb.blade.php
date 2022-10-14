<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-7">
        <h2>@yield('title')</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="">{{ __('Dashboard') }}</a>
                {{-- {{ route('dashboard') }} --}}
            </li>
            @yield('breadcrumbs')

            @if(Request::route()->getName() !== 'dashboard')
                <li class="breadcrumb-item active">
                    <strong>@yield('title')</strong>
                </li>
            @endif
        </ol>
    </div>
    <div class="col-lg-5">
        <div class="title-action">
            @yield('action_title')
        </div>
    </div>
</div>
