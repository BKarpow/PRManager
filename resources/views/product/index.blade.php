@extends('layouts.app')

@section('title') Продукти @endsection

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
                                <table class="table table-dark table-hover">
                                    <thead>
                                        <tr>
                                            <th>Фото</th>
                                            <th>Ім'я</th>
                                            <th>штрихкод</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $item)
                                            <tr>
                                                <th>
                                                    <image-modal  image-src="{{$item->mainImg()}}" alt-text="{{$item->name}}" ></image-modal>
                                                </th>
                                                <th>
                                                    <div class="mb-1">
                                                        <a href="{{route('shop.show', ['shop'=>$item])}}" class="btn btn-primary">
                                                         {{ $item->name }} </a> <!-- /.btn btn-primary -->

                                                        </div>
                                                    <div class="btn-group">
                                                        <a href="{{ route('product.edit', ['product' => $item]) }}"
                                                            class="btn btn-dark">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                height="16" fill="currentColor" class="bi bi-pencil-fill"
                                                                viewBox="0 0 16 16">
                                                                <path
                                                                    d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z" />
                                                            </svg> Редагувати
                                                        </a> <!-- /.btn btn-dark -->
                                                        <a href="{{ route('shop.delete', ['shop' => $item]) }}"
                                                            class="btn btn-danger">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                height="16" fill="currentColor" class="bi bi-recycle"
                                                                viewBox="0 0 16 16">
                                                                <path
                                                                    d="M9.302 1.256a1.5 1.5 0 0 0-2.604 0l-1.704 2.98a.5.5 0 0 0 .869.497l1.703-2.981a.5.5 0 0 1 .868 0l2.54 4.444-1.256-.337a.5.5 0 1 0-.26.966l2.415.647a.5.5 0 0 0 .613-.353l.647-2.415a.5.5 0 1 0-.966-.259l-.333 1.242zM2.973 7.773l-1.255.337a.5.5 0 1 1-.26-.966l2.416-.647a.5.5 0 0 1 .612.353l.647 2.415a.5.5 0 0 1-.966.259l-.333-1.242-2.545 4.454a.5.5 0 0 0 .434.748H5a.5.5 0 0 1 0 1H1.723A1.5 1.5 0 0 1 .421 12.24zm10.89 1.463a.5.5 0 1 0-.868.496l1.716 3.004a.5.5 0 0 1-.434.748h-5.57l.647-.646a.5.5 0 1 0-.708-.707l-1.5 1.5a.5.5 0 0 0 0 .707l1.5 1.5a.5.5 0 1 0 .708-.707l-.647-.647h5.57a1.5 1.5 0 0 0 1.302-2.244z" />
                                                            </svg> Видалити
                                                        </a> <!-- /.btn btn-dark -->
                                                    </div>
                                                    <!-- /.button-group -->
                                                </th>
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
