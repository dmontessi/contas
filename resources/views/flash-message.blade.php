<div class="alert-container">
    @if ($message = Session::get('success'))
    <div class="alert alert-success alert-dismissible fade show alert-notification" role="alert" id="success-alert">
        {{ $message }}
    </div>
    <script>
        setTimeout(function() {
            document.getElementById('success-alert').classList.remove('show');
            setTimeout(function() {
                document.getElementById('success-alert').remove();
            }, 300);
        }, 5000);

    </script>
    @endif

    @if ($message = Session::get('error'))
    <div class="alert alert-danger alert-dismissible fade show alert-notification" role="alert" id="danger-alert">
        {{ $message }}
    </div>
    <script>
        setTimeout(function() {
            document.getElementById('danger-alert').classList.remove('show');
            setTimeout(function() {
                document.getElementById('danger-alert').remove();
            }, 300);
        }, 5000);

    </script>
    @endif

    @if ($message = Session::get('warning'))
    <div class="alert alert-warning alert-dismissible fade show alert-notification" role="alert" id="warning-alert">
        {{ $message }}
    </div>
    <script>
        setTimeout(function() {
            document.getElementById('warning-alert').classList.remove('show');
            setTimeout(function() {
                document.getElementById('warning-alert').remove();
            }, 300);
        }, 5000);

    </script>
    @endif

    @if ($message = Session::get('info'))
    <div class="alert alert-info alert-dismissible fade show alert-notification" role="alert" id="info-alert">
        {{ $message }}
    </div>
    <script>
        setTimeout(function() {
            document.getElementById('info-alert').classList.remove('show');
            setTimeout(function() {
                document.getElementById('info-alert').remove();
            }, 300);
        }, 5000);

    </script>
    @endif
</div>


@if ($errors->any() && !Route::is('login'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>{{ Auth::user()->primeiro_nome }}!</strong> Verifique os erros abaixo:
    <ul class="mb-1">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
