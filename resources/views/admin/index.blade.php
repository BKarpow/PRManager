@extends('layouts.app')

@section('title') Панель @endsection

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Адмін панель</div>

                    <div class="card-body">
                        <div class="mb-2 row">
                            <a href="{{route('admin.runMigrate')}}" class="btn btn-primary">
                                Запустити міграції
                            </a> <!-- /.btn btn-primary -->
                        </div>
                        <!-- /.mb-2 row -->
                       <div class="row">
                        <div class="col-md-3">
                            <h3>Дані на сайті</h3>
                            <div class="mt-2">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        Продукти: {{$products}}
                                    </li>
                                    <!-- /.list-group-item -->
                                    <li class="list-group-item">
                                        Терміни: {{$exps}}
                                    </li>
                                    <!-- /.list-group-item -->
                                    <li class="list-group-item">
                                        Користувачів: {{$users}}
                                    </li>
                                    <!-- /.list-group-item -->
                                    <li class="list-group-item"></li>
                                    <!-- /.list-group-item -->
                                </ul>
                                <!-- /.list-group -->
                            </div>
                            <!-- /.mt-2 -->
                        </div>
                        <!-- /.col-md-3 -->
                        <div class="col-md-6">
                            <h3>Останні запмси</h3>
                            <ul class="list-group">
                                <li class="list-group-item">

                                    <div class="mt-1 d-lfex justify-content-around">
                                        <div>{{$lastDate->product->name}}</div>
                                        <div>{{$lastDate->user->name}}</div>
                                        <div>{{$lastDate->end->format('d.m.Y')}}</div>
                                        <div>{{$lastDate->created_at->format('d.m.Y H:i')}}</div>
                                    </div>
                                    <!-- /.mt-1 d-lfex -->
                                </li>
                                <!-- /.list-group-item -->
                                <li class="list-group-item">
                                    <div class="mt-1 d-lfex justify-content-around">
                                        <div>{{$lastProduct->name}}</div>
                                        <div>{{$lastProduct->created_at->format('d.m.Y')}}</div>
                                    </div>
                                    <!-- /.mt-1 d-lfex -->
                                </li>
                                <!-- /.list-group-item -->
                                <li class="list-group-item"></li>
                                <!-- /.list-group-item -->
                                <li class="list-group-item"></li>
                                <!-- /.list-group-item -->
                            </ul>
                            <!-- /.list-group -->
                        </div>
                        <!-- /.col-md-3 -->
                       </div>
                       <!-- /.row -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
