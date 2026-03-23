@extends('layouts.app')

@section('title') Помилка 503 @endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card">
                <div class="card-header">Помилка 503</div>

                <div class="card-body">

                    <div class="container">
                        <div class="row mb-2">
                            <div class="col-md-11">
                                <h2>503 😢 Упс!! Серверу стало погано, вибачте!</h2>
                            </div>
                            <!-- /.col-md-11 -->
                        </div>
                        <!-- /.row mb-2 -->

                    </div>
                    <!-- /.container -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

