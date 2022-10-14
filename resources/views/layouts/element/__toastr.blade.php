<script>
	toastr.options = {
		"closeButton": true,
		"progressBar": true
	}
</script>

{{--Warning message--}}
@if (session()->has('warning'))
    @if(is_array(session('warning')))
        @foreach(session('warning') as $message)
            <script>
				toastr.warning("{{ $message }}");
            </script>
        @endforeach
    @else
        <script>
			toastr.warning("{{ session('warning') }}");
        </script>
    @endif
@endif

{{--Error messages--}}
@if ($errors->all())
    @foreach($errors->all() as $error)
        <script>
			toastr.error("{{ $error }}");
        </script>
    @endforeach
@endif

@if (session()->has('error'))
    @if(is_array(session('error')))
        @foreach(session('error') as $error)
            <script>
				toastr.error("{{ $error }}");
            </script>
        @endforeach
    @else
        <script>
			toastr.error("{{ session('error') }}");
        </script>
    @endif
@endif

{{--Success messages--}}
@if (session()->has('success'))
    @if(is_array(session('success')))
        @foreach(session('success') as $message)
            <script>
				toastr.success("{{ $message }}");
            </script>
        @endforeach
    @else
        <script>
			toastr.success("{{ session('success') }}");
        </script>
    @endif
@endif


