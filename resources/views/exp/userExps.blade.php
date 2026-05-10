@extends('layouts.app')

@section('title')
    {{ $user->name }} > Терміни
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Терміни</div>

                    <div class="card-body">
                        <h2> {{ $user->name }} > Терміни</h2>

                        @if ($data->count() == 0)
                            <div class="alert alert-info">
                                <strong>Ще немає в базі термінів, створіть новий!</strong>
                            </div>
                            <!-- /.alert -->
                        @else
                            <div class="table-responsive">
                                <table class="table  table-striped">
                                    <thead>
                                        <tr>
                                            <th>Інфо</th>
                                            <th>Залишиось</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $item)
                                            <tr @if ($item->is25()) class="table-warning" @endif>
                                                <th>

                                                    <div class="my-2">
                                                        <a href="{{ route('date.show', ['dateProduct' => $item]) }}"
                                                            class="btn btn btn-success">
                                                            {{ $item->product->name }} </a> <!-- /.btn btn-primary -->
                                                    </div>
                                                    <div class="mt-1">
                                                        До: {{ $item->end->format('d.m.Y') }}
                                                    </div>
                                                    <!-- /.mt-1 -->

                                                    <!-- /.mb-1 -->
                                                    <div class="my-1">
                                                        <img src="{{ $item->barcodeSvgUrl() }}" alt="">
                                                    </div>
                                                    <!-- /.my-1 -->
                                                    {{ $item->product->barcode }}

                                                    <div>Кількість: {{ $item->count }}</div>
                                                    <image-modal image-src="{{ $item->product->mainImg() }}"
                                                        alt-text="{{ $item->name }}"></image-modal>

                                                </th>
                                                <th class="display-2 text-center">{{ $item->days_remaining }}</th>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="mt-2 flex hustify-content-center">
                                    {{ $data->links() }}
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
