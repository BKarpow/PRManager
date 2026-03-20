@extends('layouts.app')

@section('title') {{$group->name}} | терміни @endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{$group->name}}</div>

                    <div class="card-body">
                        <div class="my-2 p-1">
                            <a href="{{route('date.create')}}" class="btn btn-dark btn-lg">
                            Додати новий термін
                        </a>
                        </div>
                        <!-- /.my-2 p-1 -->
                        <div class="mb-2 p-1">
                            <a href="{{route('date.expiredGroup',['group'=>$group->id])}}" class="btn btn-warning btn-lg">
                                Протерміновані продукти {{$group->name}} ({{$exps->count()}})
                            </a>
                            <!-- /.btn btn-success --></div>
                        <!-- /.mb-2 p-1 -->
                        @if ($data->count() == 0)
                            <div class="alert alert-info">
                                <strong>Ще немає в базі термінів, створіть новий!</strong>
                            </div>
                            <!-- /.alert -->
                        @else
                            <h3>Терміни</h3>
                            <div class="table-responsive">
                                <table class="table table-dark table-hover">
                                    <thead>
                                        <tr>
                                            <th>Інфо</th>
                                            <th>Залишиось</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $item)
                                            <tr >
                                                <th >

                                                    <div class="my-2">
                                                        <a href="{{route('date.show', ['dateProduct' => $item])}}" class="btn btn-primary">
                                                         {{ $item->product->name }} </a> <!-- /.btn btn-primary -->
                                                    </div>
                                                    <div class="mt-1">
                                                        {{$item->start}} -> {{$item->end}}
                                                    </div>
                                                    <!-- /.mt-1 -->
                                                    Штрих-код: {{$item->product->barcode}}
                                                    <div>Кількість: {{$item->count}}</div>
                                                    <image-modal  image-src="{{$item->product->mainImg()}}" alt-text="{{$item->name}}" ></image-modal>

                                                </th>
                                                <td @if ($item->is25()) style="background: red; color: white;" @endif class="display-2 text-center">{{$item->days_remaining}}</td>
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
