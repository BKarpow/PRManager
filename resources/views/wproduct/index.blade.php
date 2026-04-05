@extends('layouts.app')

@section('title') Вагові продукти @endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card">
                    <div class="card-header">Штрихкоди та продукти</div>

                    <div class="card-body">
                        <div class="my-2 p-1">
                            <a href="{{ route('product.create') }}" class="btn btn-primary">
                                Створити продукт
                            </a> <!-- /.btn -->
                        </div>
                        <!-- /.my-2 p-1 -->
                        @if ($data->count() == 0)
                            <div class="alert alert-info">
                                <strong>Ще немає в базі продуктів, створіть новий!</strong>
                            </div>
                            <!-- /.alert -->
                        @else
                            <h3>Продукти</h3>
                            <div class="table-responsive">
                                <table class="table table-striped align-middle">
                                    <thead>
                                        <tr>

                                            <th>Ім'я</th>
                                            <th>штрихкод</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $item)
                                            <tr>

                                                <th> {{$item->name}}</th>
                                                <td>{{$item->barcode}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="mt-2 flex hustify-content-center">
                                    {{$data->links()}}
                                </div>
                                <!-- /.mt-2 flex hustify-content-center -->
                            </div>
                            <!-- /.table-responsive -->
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
