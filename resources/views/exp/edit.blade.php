@extends('layouts.app')

@section('title')
    {{ $item->product->name }} - редагувати
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card">
                    <div class="card-header">Змінити термін</div>

                    <div class="card-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <a href="https://www.google.com/search?q={{ $item->barcode }}&tbm=isch" target="__blanc" class="btn btn-success"> Google Images</a>
                                    </div>
                                    <!-- /.mb-2 -->
                                    <form action="{{ route('date.edit', ['dateProduct'=>$item]) }}" method="POST">
                                        @csrf
                                        <div class="mb-2">
                                            <label for="product">Продукт</label>
                                            <input type="text" id="product" disabled class="form-control"
                                                value="{{ $item->product->name }}">

                                        </div>
                                        <!-- /.form-group -->
                                        <input-date name-input="end" value="{{$item->getEndDate()}}"> </input-date>
                                        @error('end')
                                            <div class="my-2 alert alert-warning">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                            <!-- /.alert alert-warning -->
                                        @enderror

                                        <div class="mb-2">
                                            <button type="submit" class="btn btn-primary">
                                                Зберегти
                                            </button>
                                        </div>
                                        <!-- /.mb-2 -->


                                    </form>
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
