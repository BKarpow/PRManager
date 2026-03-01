@extends('layouts.app')

@section('title')
    Відділи
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card">
                    <div class="card-header">Додавання нової групи</div>

                    <div class="card-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6">
                                    <form action="{{route('group.create')}}" method="POST">
                                        @csrf
                                        <div class="mb-1">
                                            <select name="shop" class="form-select form-select-lg mb-3"
                                                aria-label="Обрати магазин">
                                                <option selected>Оберіть магазин</option>
                                                @foreach ($shops as $s)
                                                <option value="{{$s->id}}">{{$s->name}}</option>

                                                @endforeach
                                            </select>
                                        </div>
                                        <!-- /.mb-1 -->
                                        <div class="mb-2">
                                            <input type="text" placeholder="Ім'я відповідального" required name="name"
                                                maxlength="250" class="form-control">
                                            @error('name')
                                                <div class="mt-1 alert alert-danger">
                                                    {{ $message }}
                                                </div>
                                                <!-- /.mt-1 alert alert-danger -->
                                            @enderror
                                        </div>
                                        <!-- /.form-group -->
                                        <div class="mb-2">
                                            <input type="text" placeholder="Опис групи" name="comment" maxlength="250"
                                                class="form-control">
                                            @error('comment')
                                                <div class="mt-1 alert alert-danger">
                                                    {{ $message }}
                                                </div>
                                                <!-- /.mt-1 alert alert-danger -->
                                            @enderror
                                        </div>
                                        <!-- /.form-group -->
                                        <div class="mb-2">
                                            <button class="btn btn-dark btn-lg" type="submit">
                                                Додати
                                            </button>
                                        </div>
                                        <!-- /.form-group -->
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
