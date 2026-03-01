@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h5 class="mb-0">Завершення реєстрації</h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted">З міркувань безпеки ми переходимо на вхід за номером телефону. Будь ласка, вкажіть ваш діючий номер.</p>

                    <form method="POST" action="{{ route('profile.complete.update') }}">
                        @csrf

                        {{-- Тут може бути твій Vue компонент або звичайний Input --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">Ваш номер телефону</label>
                            <input type="text" name="phone"
                                   class="form-control form-control-lg @error('phone') is-invalid @enderror"
                                   placeholder="+38 (0XX) XXX-XX-XX"
                                   id="phone-input"
                                   required>

                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                Зберегти та продовжити
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Простий JS для маски, якщо ти не хочеш підключати Vue на цю сторінку
    document.getElementById('phone-input').addEventListener('input', function (e) {
        let x = e.target.value.replace(/\D/g, '').match(/(\d{0,2})(\d{0,3})(\d{0,3})(\d{0,2})(\d{0,2})/);
        e.target.value = !x[2] ? x[1] : '+' + x[1] + ' (' + x[2] + (x[3] ? ') ' + x[3] : '') + (x[4] ? '-' + x[4] : '') + (x[5] ? '-' + x[5] : '');
    });
</script>
@endsection
