<div class="row mb-3">
    <div class="col-md-6 offset-md-4">
        <div class="g-recaptcha" data-sitekey="{{ env('GOOGLE_RECAPTCHA_KEY') }}"></div>
    </div>
    <!-- /.col-md-6 m-2 -->
    @if ($errors->has('g-recaptcha-response'))
        <span class="text-danger">{{ $errors->first('g-recaptcha-response') }}</span>
    @endif
</div>
<!-- /.row mb-3 -->
