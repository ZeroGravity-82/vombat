@if ($message = session('success'))
    <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        {{ $message }}
    </div>
@endif
@if ($message = session('error'))
    <div class="alert alert-danger alert-block">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        {{ $message }}
    </div>
@endif
@if ($message = session('warning'))
    <div class="alert alert-warning alert-block">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        {{ $message }}
    </div>
@endif
@if ($message = session('info'))
    <div class="alert alert-info alert-block">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        {{ $message }}
    </div>
@endif