@extends('layouts.app')

@section('title') Всі користувачі сайту @endsection

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Користувачі</div>

                    <div class="card-body">

                        @if ($data->count() == 0)
                            <div class="alert alert-info">
                                <strong>Ще немає користувачів</strong>
                            </div>
                            <!-- /.alert -->
                        @else
                            <div class="table-responsive">
                                <table class="table ">
                                    <thead>
                                        <tr>
                                            <th>Інфо</th>
                                            <th>Дата створення</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $item)
                                            <tr >
                                                <th >

                                                    <div class="my-2">
                                                        <a href="{{route('date.show', ['dateProduct' => $item])}}" class="btn btn-primary">
                                                         {{ $item->name }} </a> <!-- /.btn btn-primary -->
                                                    </div>
                                                    <div class="mt-1">
                                                        {{$item->phone}}
                                                    </div>
                                                    <!-- /.mt-1 -->

                                                </th>
                                                <td >{{$item->created_at->format('d.m.Y')}}</td>
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
