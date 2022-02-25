@extends('layouts.main')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-lg-10 col-xl-6">
                    <h4 class="fw-400 text-uppercase">Informasi Jadwal Pembukaan Akses</h4>
                    @foreach ($notifications as $notif)
                        @if ($notif->finished_at > now())
                            <div class="alert alert-info alert-with-icon" data-notify="container">
                                <i class="material-icons" data-notify="icon">notifications</i>
                                <span data-notify="icon" class="now-ui-icons ui-1_bell-53"></span>
                                <h5 class="fw-400">{{ $notif->jadwal->nama_jadwal }}</h5>
                                <span data-notify="message">Mulai :
                                    <strong>{{ $notif->started_at->format('d M Y') }}</strong> Sampai
                                    <strong>{{ $notif->finished_at->format('d M Y') }}</strong></span>
                            </div>
                        @else
                            <div class="alert alert-default alert-with-icon" data-notify="container">
                                <i class="material-icons" data-notify="icon">notifications</i>
                                <h5 class="fw-400">{{ $notif->jadwal->nama_jadwal }}</h5>
                                <span data-notify="message ">Belum dibuka</span>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
