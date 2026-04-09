@extends('layouts.app')

@section('title') Налаштування сайту @endsection

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Налаштування</div>

                    <div class="card-body">
                        <div class="mb-2 row">
                            <div class="btn-group">
                                <a href="{{route('admin.runMigrate')}}" class="btn btn-primary">
                                Запустити міграції
                            </a> <!-- /.btn btn-primary -->
                            <a href="{{route('admin.mail.test')}}" class="btn btn-primary">
                                Тестовий лист адміну
                            </a> <!-- /.btn-primary -->
                            <a href="{{route('admin.cache.clear')}}" class="btn btn-warning">
                                Очистити кеш конфігурацій
                            </a> <!-- /.btn btn-warning -->
                            <a href="{{route('admin.mail.telegram.test')}}" class="btn btn-success">
                                Відправити в telegram те
                            </a> <!-- /.btn btn-success -->
                            </div>
                            <!-- /.btn-group -->

                        </div>
                        <!-- /.mb-2 row -->

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
