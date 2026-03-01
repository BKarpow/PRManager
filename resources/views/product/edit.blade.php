@extends('layouts.app')

@section('title')
    {{ $item->name }} - редагувати
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card">
                    <div class="card-header">Оновити продукт</div>

                    <div class="card-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <a href="https://www.google.com/search?q={{ $item->barcode }}&tbm=isch" target="__blanc" class="btn btn-success"> Google Images</a>
                                    </div>
                                    <!-- /.mb-2 -->
                                    <form action="{{ route('product.edit', ['product'=>$item]) }}" method="POST">
                                        @csrf
                                        <div class="mb-2">
                                            <label for="barcode">Штрих-код</label>
                                            <input type="text" id="barcode" disabled class="form-control"
                                                value="{{ $item->barcode }}">

                                        </div>
                                        <!-- /.form-group -->

                                        <div class="mb-2">
                                            <label for="name">Ім'я продукту</label>
                                            <input type="text" id="name" name="name" class="form-control"
                                                placeholder="Ім'я продукту" value="{{ $item->name }}">
                                            @error('name')
                                                <div class="alert alert-warning">
                                                    <strong>{{ $message }}</strong>
                                                </div>
                                                <!-- /.alert alert-warning -->
                                            @enderror

                                        </div>
                                        <!-- /.form-group -->
                                        <div class="mb-2">
                                            <button type="submit" class="btn btn-primary">Зберегти</button>
                                        </div>
                                        <!-- /.mb-2 -->


                                    </form>
                                </div>
                                <!-- /.col-md-6 -->
                                <div class="col-md-6">

                                </div>
                                <!-- /.col-md-6 -->
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /.container -->

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
