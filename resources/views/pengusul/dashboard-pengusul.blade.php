@extends('layouts.main')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-lg-8 col-xl-7">
                    <h4 class="fw-400 text-uppercase mb-5">Informasi Jadwal Pembukaan Akses</h4>
                    @foreach ($notifications as $notif)
                        @if ($notif->finished_at > now())
                            <div class="alert alert-info alert-with-icon mt-1" data-notify="container">
                                <i class="material-icons" data-notify="icon">notifications</i>
                                <span data-notify="icon" class="now-ui-icons ui-1_bell-53"></span>
                                <h5 class="fw-400">{{ $notif->jadwal->nama_jadwal }}</h5>
                                <span data-notify="message">Mulai :
                                    <strong>{{ $notif->started_at->format('d M Y') }}</strong> Sampai
                                    <strong>{{ $notif->finished_at->format('d M Y') }}</strong></span>
                            </div>
                        @else
                            <div class="alert alert-default alert-with-icon mt-1" data-notify="container">
                                <i class="material-icons" data-notify="icon">notifications</i>
                                <h5 class="fw-400">{{ $notif->jadwal->nama_jadwal }}</h5>
                                <span data-notify="message ">Belum dibuka</span>
                            </div>
                        @endif
                    @endforeach
                </div>
                <div class="col-md-12 col-lg-4 col-xl-5 mt-lg-5">
                    <div class="card card-profile">
                        <div class="card-avatar">
                            <a href="#pablo">
                                <img class="img" src="{{ asset('img/profile.png') }}" />
                            </a>
                        </div>
                        <div class="card-body">
                            <h6 class="card-category text-gray">{{ Auth::user()->role->nama_role }}</h6>
                            <h4 class="card-title">{{ Auth::user()->dosen->nama }}</h4>
                            <div class="row mt-4">
                                <div class="col-3 text-right pt-1">
                                    <h6>Email</h6>
                                </div>
                                <div class="col-9 text-left">
                                    <p class="card-text">{{ Auth::user()->email }}</p>
                                </div>
                            </div>
                            <div class="row pt-2">
                                <div class="col-3 text-right pt-1">
                                    <h6>Fakultas</h6>
                                </div>
                                <div class="col-9 text-left">
                                    <p class="card-text">
                                        {{ Auth::user()->dosen->prodi->faculty->nama_faculty }}</p>
                                </div>
                            </div>
                            <div class="row pt-2">
                                <div class="col-3 text-right pt-1">
                                    <h6>Prodi</h6>
                                </div>
                                <div class="col-9 text-left">
                                    <p class="card-text">
                                        {{ Auth::user()->dosen->prodi->nama_prodi }}</p>
                                </div>
                            </div>
                            <div class="row pt-2">
                                <div class="col-3 text-right pt-1">
                                    <h6>Jabatan</h6>
                                </div>
                                <div class="col-9 text-left">
                                    <p class="card-text">
                                        {{ Auth::user()->dosen->jabatan->nama_jabatan ?? 'Belum diupdate' }}</p>
                                </div>
                            </div>
                            <div class="row pt-2">
                                <div class="col-3 text-right pt-1">
                                    <h6>Nomor Hp.</h6>
                                </div>
                                <div class="col-9 text-left">
                                    <p class="card-text">{{ Auth::user()->dosen->handphone ?? 'Belum diupdate' }}
                                    </p>
                                </div>
                            </div>
                            <a href="{{ route('editProfile') }}" class="btn btn-sm btn-rose btn-round">Update</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
