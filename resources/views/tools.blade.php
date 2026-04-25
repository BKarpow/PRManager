@extends('layouts.app')

@section('title') Інструменти @endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card">
                <div class="card-header">Утиліти</div>

                <div class="card-body">

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="container">
                        <div class="row">
                            <div class="col-md-10">
                                <h2>Лупа</h2>
                                <p-input></p-input>
                            </div>
                            <!-- /.col-md-10 -->
                        </div>
                        <!-- /.row -->
                        <div class="row">
                            <div class="col-md-12">
                                <h3>ToDo</h3>
                                <todo></todo>
                            </div>
                            <!-- /.col-md-4 -->
                        </div>
                        <!-- /.row -->
                        <div class="row">
                            <div class="col-md-10">
                                <search-date></search-date>
                            </div>
                            <!-- /.col-md-10 -->
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
