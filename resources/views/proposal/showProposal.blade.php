@extends('layouts.main')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
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
                                                    <p>Pengusul</p>
                                                </td>
                                                <td class="text-left">
                                                    <h5>
                                                        {{ $dsn->nama }}
                                                    </h5>
                                                </td>
                                            </tr>
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
                                            <p>Anggota</p>
                                        </td>
                                        <td class="text-left">
                                            @foreach ($proposal->dosen as $dsn)
                                                @if ($dsn->pivot->isLeader == false)
                                                    <h5> {{ $dsn->nama }}</h5>
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
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
                        <div class="text-right">
                            <a href="{{ route('usulan.edit', $proposal->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header card-header-text card-header-info">
                        <div class="card-icon">
                            <i class="material-icons">assignment</i>
                        </div>
                        <div class="row card-title">
                            <div class="col-md-6">
                                <h4 class="fw-400">Penilaian Reviewer 1</h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-pills nav-pills-warning" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#link1" role="tablist">
                                    Hasil review 1
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#link2" role="tablist">
                                    Hasil review 2
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content tab-space">
                            @foreach ($audits as $audit)
                                <div class="tab-pane {{ $loop->iteration == 1 ? 'active' : '' }}"
                                    id="link{{ $loop->iteration }}">
                                    {{-- head --}}
                                    <div class="row px-2">
                                        <div class="col-12">
                                            <div class="row py-1">
                                                <div class="col-3 fw-400">
                                                    Nama Reviewer
                                                </div>
                                                <div class="col-9">
                                                    <p class="card-text">
                                                        {{ $audit->user->dosen->nama }}
                                                    </p>
                                                </div>
                                            </div>
                                            @if ($audit->hasil == null)
                                                <div class="row py-1 bg-light">
                                                    <div class="col-12 card-text text-center fw-400">
                                                        Belum ada penilaian
                                                    </div>
                                                </div>
                                            @else
                                                <div class="row py-1">
                                                    <div class="col-3 fw-400">
                                                        Tanggal Reviewing
                                                    </div>
                                                    <div class="col-9">
                                                        <p class="card-text">
                                                            {{ $audit->hasil->created_at->format('d M Y') }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="row py-1">
                                                    <div class="col-3 fw-400">
                                                        Catatan
                                                    </div>
                                                    <div class="col-9">
                                                        <p class="card-tex text-justify">
                                                            {{ $audit->hasil->komentar }}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endif
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
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
