@extends('layouts.app')

@section('title')
    Редагувати відділ {{$item->name}}
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card">
                    <div class="card-header">Зміна інформації про відділ</div>

                    <div class="card-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6">
                                    <form action="{{route('group.edit.update', ['groupProduct' => $item->id])}}" method="POST">
                                        @csrf

                                        <!-- /.mb-1 -->
                                        <div class="mb-2">
                                            <input type="text" placeholder="Ім'я відповідального" required name="name"
                                                maxlength="250" value="{{$item->name}}" class="form-control">
                                            @error('name')
                                                <div class="mt-1 alert alert-danger">
                                                    {{ $message }}
                                                </div>
                                                <!-- /.mt-1 alert alert-danger -->
                                            @enderror
                                        </div>
                                        <!-- /.form-group -->
                                        <div class="mb-2">
                                            <textarea name="comment" class="form-control" placeholder="Що входить у відділ..." maxlength="250"> {{$item->comment}}</textarea>
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
