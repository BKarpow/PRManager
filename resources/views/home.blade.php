@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card">
                <div class="card-header">Мої терміни</div>

                <div class="card-body">
                    <div class="my-2">
                        <a href="{{route('date.create')}}" class="btn btn-dark btn-lg">
                            Додати новий термін
                        </a>
                    </div>
                    <!-- /.my-2 -->
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
