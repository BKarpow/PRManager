@extends('layouts.app')

@section('title') Інструменти @endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card">
                <div class="card-header">Telegram</div>

                <div class="card-body">

                    <div class="container">
                        <div class="row mb-2">
                            <div class="col-md-11">
                                <h2>😢 Упс!! У Вас не прив'заний telegram!</h2>
                            </div>
                            <!-- /.col-md-11 -->
                        </div>
                        <!-- /.row mb-2 -->
                        <div class="row">
                            <div class="col-md-6">
                                <h3>Пройдіть прості кроки щоб це виправити.</h3>
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        Перейдіть в Telegram бот.
                                    </li>
                                    <!-- /.list-group-item -->
                                    <li class="list-group-item">
                                        Натисніть розпочати, або "Start".
                                    </li>
                                    <!-- /.list-group-item -->
                                    <li class="list-group-item">
                                        Натисніть поділитися номером.
                                    </li>
                                    <!-- /.list-group-item -->
                                    <li class="list-group-item">
                                        Все можете оновити цю сторінку та користуватися термінами.
                                    </li>
                                    <!-- /.list-group-item -->
                                </ul>
                                <!-- /.list-group -->
                            </div>
                            <!-- /.col-md-6 -->
                            <div class="col-md-6 justify-content-center align-items-center">
                                <a href="https://t.me/productexpbot" class="btn btn-success btn-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telegram" viewBox="0 0 16 16">
  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.287 5.906q-1.168.486-4.666 2.01-.567.225-.595.442c-.03.243.275.339.69.47l.175.055c.408.133.958.288 1.243.294q.39.01.868-.32 3.269-2.206 3.374-2.23c.05-.012.12-.026.166.016s.042.12.037.141c-.03.129-1.227 1.241-1.846 1.817-.193.18-.33.307-.358.336a8 8 0 0 1-.188.186c-.38.366-.664.64.015 1.088.327.216.589.393.85.571.284.194.568.387.936.629q.14.092.27.187c.331.236.63.448.997.414.214-.02.435-.22.547-.82.265-1.417.786-4.486.906-5.751a1.4 1.4 0 0 0-.013-.315.34.34 0 0 0-.114-.217.53.53 0 0 0-.31-.093c-.3.005-.763.166-2.984 1.09"/>
</svg> Перейти до Telegram-боту
                                </a> <!-- /.btn btn-success -->
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
