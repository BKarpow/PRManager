@extends('layouts.app')

@section('title') Імпортувати вагові продукти через json @endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card">
                    <div class="card-header">Імпортувати вагові продукти через json </div>

                    <div class="card-body">
                        <form action="{{route('wproduct.import')}}" method="POST">
                            @csrf
                                <div class="mb-2">
                                    <textarea
                                    class=" form-control"
                                    name="js" id="js"
                                    cols="30" rows="30"
                                    placeholder="Вставте сюди JSON"
                                    ></textarea>
                                    @error('js')
                                        <div class="alert alert-warning">
                                            <strong>{{$messge}}</strong>
                                        </div>
                                        <!-- /.alert alert-warning -->
                                    @enderror
                                </div>
                                <!-- /.mb-2 -->


                        <div class="my-2 p-1">
                            <button type="submit" class="btn btn-primary">
                                Імпортувати
                            </button> <!-- /.btn -->
                        </div>
                        <!-- /.my-2 p-1 -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
