@extends('layouts.app')

@section('title') Вхід в систему @endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Вхід</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row mb-3 justify-content-center align-items-center">
                            <div class="col-md-6">
                                <phone-input></phone-input>
                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                            </div>
                            <!-- /.col-md-6 -->

                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">
                                Пароль
                            </label>

                            <div class="col-md-6">
                                <p-input name="password"></p-input>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        {{-- @include('recaptcha') --}}
                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        Пам'ятати мене
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-4 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Увійти
                                </button>
                            </div>

                        </div>

                        <div class="row mb-2">
                                <a href="/register" class="btn btn-primary">
                                    Створити акаунт
                                </a> <!-- /.btn btn-primary -->
                            </div>
                            <!-- /.col-md-4 -->
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
