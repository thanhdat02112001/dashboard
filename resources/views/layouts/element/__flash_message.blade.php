{{--Warning message--}}
@if (session()->has('warning'))
    @if(is_array(session('warning')))
        <ul class="alert alert-warning">
            @foreach(session('warning') as $message)
                <li>{{$message}}</li>
            @endforeach
        </ul>
    @else
        <div class="alert alert-warning">{{session('warning')}}</div>
    @endif
@endif

{{--Error messages--}}
@if ($errors->all())
    <ul class="alert alert-danger">
        @foreach($errors->all() as $error)
            <li>{{$error}}</li>
        @endforeach
    </ul>
@endif

@if (session()->has('error'))
    @if(is_array(session('error')))
        <ul class="alert alert-danger">
            @foreach(session('error') as $error)
                <li>{{$error}}</li>
            @endforeach
        </ul>
    @else
        <div class="alert alert-danger">{{session('error')}}</div>
    @endif
@endif

{{--Success messages--}}
@if (session()->has('success'))
    @if(is_array(session('success')))
        <ul class="alert alert-success">
            @foreach(session('success') as $message)
                <li>{{$message}}</li>
            @endforeach
        </ul>
    @else
        <div class="alert alert-success">{{session('success')}}</div>
    @endif
@endif
