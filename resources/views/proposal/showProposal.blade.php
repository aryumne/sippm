@extends('layouts.main')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header card-header-text card-header-info">
                        <div class="card-text">
                            <h4 class="fw-400">Detail Usulan Proposal</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td>
                                            <p>Judul Proposal</p>
                                        </td>
                                        <td class="text-left">
                                            <h5>
                                                {{ $proposal->judul }}
                                            </h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>Tanggal Pengusulan</p>
                                        </td>
                                        <td class="text-left">
                                            <h5>
                                                {{ $proposal->tanggal_usul }}
                                            </h5>
                                        </td>
                                    </tr>
                                    @foreach ($proposal->dosen as $dsn)
                                        @if ($dsn->pivot->isLeader == true)
                                            <tr>
                                                <td>
                                                    <p>Fakultas</p>
                                                </td>
                                                <td class="text-left">
                                                    <h5>
                                                        {{ $dsn->prodi->faculty->nama_faculty }}
                                                    </h5>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p>Prodi</p>
                                                </td>
                                                <td class="text-left">
                                                    <h5>
                                                        {{ $dsn->prodi->nama_prodi }}
                                                    </h5>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    <tr>
                                        <td>
                                            <p>File Proposal</p>
                                        </td>
                                        <td class="text-left">
                                            <a href="{{ asset('storage/' . $proposal->path_proposal) }}" target="_blank"
                                                class="badge badge-success">{{ substr($proposal->path_proposal, 9) }}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>Status</p>
                                        </td>
                                        <td class="text-left">
                                            <h5>
                                                @if ($proposal->status == 1)
                                                    Menunggu
                                                @elseif ($proposal->status == 2)
                                                    Lanjut
                                                @else
                                                    Tidak Lanjut
                                                @endif
                                            </h5>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class=""></div>
                        <a href="{{ route('usulan.edit', $proposal->id) }}"
                            class="btn btn-sm btn-warning text-right">Edit</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title fw-400">Tim Pengusul</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4 col-lg-3 text-right">
                                <h6>Pengusul :</h6>
                            </div>
                            <div class="col-sm-8 col-lg-9">
                                @foreach ($proposal->dosen as $dsn)
                                    @if ($dsn->pivot->isLeader == true)
                                        <p class="mb-1">{{ $dsn->nama }}</p>
                                        <p class="mb-1">{{ str_pad($dsn->nidn, 10, '0', STR_PAD_LEFT) }}</p>
                                        <p class="mb-1">{{ $dsn->prodi->faculty->nama_faculty }}</p>
                                        <p>{{ $dsn->prodi->nama_prodi }}</p>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="row">
                            @foreach ($proposal->dosen as $dsn)
                                @if ($dsn->pivot->isLeader == false)
                                    <div class="col-sm-4 col-lg-3 text-right">
                                        <h6>Anggota {{ $loop->index }} :</h6>
                                    </div>
                                    <div class="col-sm-8 col-lg-9">
                                        <p class="mb-1">{{ $dsn->nama }}</p>
                                        <p class="mb-1">{{ str_pad($dsn->nidn, 10, '0', STR_PAD_LEFT) }}</p>
                                        <p class="mb-1">{{ $dsn->prodi->faculty->nama_faculty }}</p>
                                        <p>{{ $dsn->prodi->nama_prodi }}</p>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach ($audits as $audit)
                <div class="col-md-12 col-lg-6">
                    <div class="card">
                        <div class="card-header card-header-text card-header-info">
                            <div class="card-icon">
                                <i class="material-icons">assignment</i>
                            </div>
                            <div class="row card-title">
                                <div class="col-md-6">
                                    <h4 class="fw-400">Penilaian Reviewer {{ $loop->iteration }}</h4>
                                </div>
                            </div>
                        </div>
                        @if ($audit->hasil != null)
                            <div class="card-body">
                                {{-- head --}}
                                <div class="row px-2">
                                    <div class="col-12">
                                        <div class="row py-1">
                                            <div class="col-4 fw-400">
                                                <h6>Nama Reviewer</h6>
                                            </div>
                                            <div class="col-8">
                                                <p class="card-text">
                                                    {{ $audit->user->dosen->nama }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="row py-1">
                                            <div class="col-4 fw-400">
                                                <h6>Tanggal Penilaian</h6>
                                            </div>
                                            <div class="col-8">
                                                <p class="card-text">
                                                    {{ $audit->hasil->created_at->format('d M Y') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="row py-1">
                                            <div class="col-4 fw-400">
                                                <h6>Catatan</h6>
                                            </div>
                                            <div class="col-8">
                                                <p class="card-tex text-justify">
                                                    {{ $audit->hasil->komentar }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- end head --}}
                                @if ($audit->hasil != null)
                                    <div class="row pt-3 px-1">
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-9">
                                                    <h6>KRITERIA PENILAIAN</h6>
                                                </div>
                                                <div class="col-3 text-center">
                                                    <h6>NILAI</h6>
                                                </div>
                                            </div>
                                            <div class="row pt-2 bg-light align-items-center">
                                                <div class="col-9">
                                                    <p class="card-text fw-400 mb-0">Perumusan
                                                        masalah:</p>
                                                    <ul class="mt-0">
                                                        <li>Ketajaman perumusan masalah</li>
                                                        <li>Tujuan Penelitian</li>
                                                    </ul>
                                                </div>
                                                <div class="col-3 text-center">
                                                    <h6 class="fw-400">
                                                        {{ $audit->hasil->perumusan }}</h6>
                                                </div>
                                            </div>
                                            <div class="row pt-2 align-items-center">
                                                <div class="col-9">
                                                    <p class="card-text fw-400 mb-0">Peluang
                                                        luaran penelitian:</p>
                                                    <ul class="mt-0">
                                                        <li>Publikasi ilmiah</li>
                                                        <li>Pengembangan iptek-sosbud</li>
                                                        <li>Pengayaan bahan ajar</li>
                                                    </ul>
                                                </div>
                                                <div class="col-3 text-center">
                                                    <h6 class="fw-400">
                                                        {{ $audit->hasil->peluang }}</h6>
                                                </div>
                                            </div>
                                            <div class="row pt-2 bg-light align-items-center">
                                                <div class="col-9">
                                                    <p class="card-text fw-400 mb-0">Metode
                                                        penelitian:</p>
                                                    <ul class="mt-0">
                                                        <li>Ketepatan dan kesesuaian metode yang
                                                            digunakan</li>
                                                    </ul>
                                                </div>
                                                <div class="col-3 text-center">
                                                    <h6 class="fw-400">
                                                        {{ $audit->hasil->metode }}</h6>
                                                </div>
                                            </div>
                                            <div class="row pt-2 align-items-center">
                                                <div class="col-9">
                                                    <p class="card-text fw-400 mb-0">Tinjauan
                                                        pustaka:</p>
                                                    <ul class="mt-0">
                                                        <li>Relevansi</li>
                                                        <li>Kemutakhiran</li>
                                                        <li>Penyusunan daftar pustaka</li>
                                                    </ul>
                                                </div>
                                                <div class="col-3 text-center">
                                                    <h6 class="fw-400">
                                                        {{ $audit->hasil->tinjauan }}</h6>
                                                </div>
                                            </div>
                                            <div class="row pt-2 bg-light align-items-center">
                                                <div class="col-9">
                                                    <p class="card-text fw-400 mb-0">Kelayakan
                                                        penelitian:</p>
                                                    <ul class="mt-0">
                                                        <li>Kesesuaian waktu</li>
                                                        <li>Kesesuaian biaya</li>
                                                        <li>Kesesuaian personalia</li>
                                                    </ul>
                                                </div>
                                                <div class="col-3 text-center">
                                                    <h6 class="fw-400">
                                                        {{ $audit->hasil->kelayakan }}</h6>
                                                </div>
                                            </div>
                                            <div class="row pt-2 align-items-center">
                                                <div class="col-9">
                                                    <h5 class="fw-500">TOTAL NILAI</h5>
                                                </div>
                                                <div class="col-3 text-center">
                                                    <h5 class="fw-400">
                                                        {{ $audit->hasil->total }}</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="card-body">
                                <div class="card-text">
                                    Belum ada hasil review 2
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    </div>
@endsection
