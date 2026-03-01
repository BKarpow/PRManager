@extends('layouts.app')

@section('title') Налаштування @endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card">
                    <div class="card-header">Налаштування</div>

                    <div class="card-body">
                        <form action="{{route('options.index')}}" method="POST">
                            @csrf
                        <select-shop></select-shop>
                        <div class="my-2 p-1">
                            <button type="submit" class="btn btn-primary">
                                Зберегти конфіг та закрити
                            </button> <!-- /.btn -->
                        </div>
                        <!-- /.my-2 p-1 -->


                        <div class="my-2 p-1">
                            <label> Магазин за замовчуванням id: {{$configShop}}
                                <select required name='shop' class="form-select" aria-label="Вибір магазину за замовчуванням">
                                    <option disabled @if ($configShop == 0) selected @endif >Обрати магазин ...</option>
                                    @foreach ($shops as $shop)

                                        <option value="{{$shop->id}}" @if ($configShop == $shop->id) selected @endif>{{$shop->name}}</option>
                                    @endforeach
                                </select>
                            </label>

                        </div>
                        <!-- /.my-2 p-1 -->
                        <div class="my-2 p-1">
                            <label> Група товарів за замовчуванням id: {{$configGroup}}
                                <select name='group' class="form-select" aria-label="Вибір магазину за замовчуванням">
                                    <option disabled @if ($configGroup == 0) selected @endif >Обрати магазин ...</option>
                                    @foreach ($groups as $shop)

                                        <option value="{{$shop->id}}" @if ($configGroup == $shop->id) selected @endif>{{$shop->name}}</option>
                                    @endforeach
                                </select>
                            </label>

                        </div>
                        <!-- /.my-2 p-1 -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
