@extends('layouts.app')

@section('title') Імпорт даних @endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card">
                <div class="card-header">Імпорт даних</div>

                <div class="card-body">

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form action="{{route('import.csv')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="file" class="form-label">Оберіть CSV файл</label>
                            <input type="file" name="file" id="file" class="form-control" required>

                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">Завантажити та імпортувати</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
