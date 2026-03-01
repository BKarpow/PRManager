@extends('layouts.app')

@section('title') Створити магазин  @endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card">
                    <div class="card-header">Магазини</div>

                    <div class="card-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6">
                                    <form action="{{route('shop.create.store')}}" method="POST">
                                        @csrf
                                        <div class="mb-2">
                                            <input type="text" placeholder="Ім'я магазину"
                                            required name="name"
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
                                            <input type="text" placeholder="Адреса магазину" name="address"
                                                maxlength="250"  class="form-control">
                                                @error('address')
                                                    <div class="mt-1 alert alert-danger">
                                                        {{ $message }}
                                                    </div>
                                                    <!-- /.mt-1 alert alert-danger -->
                                                @enderror
                                        </div>
                                        <!-- /.form-group -->
                                        <div class="mb-2">
                                            <input type="text" placeholder="Опис магазину" name="comment" maxlength="250"
                                                class="form-control" >
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
