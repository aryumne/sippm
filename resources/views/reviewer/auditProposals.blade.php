@extends('layouts.main')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-lg-8">
                <div class="card">
                    <div class="card-header card-header-text card-header-info">
                        <div class="card-text">
                            <h4 class="card-title">Penilaian Proposal</h4>
                            <p class="card-category">Batas waktu penilaian: 3 Maret 2022</p>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-hover">
                            <thead class="text-dark">
                                <th>ID</th>
                                <th>Judul Proposal</th>
                                <th>File Proposal</th>
                                <th>Review</th>
                            </thead>
                            <tbody>
                                @foreach ($userAudit as $ua)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $ua->proposal->judul }}</td>
                                        <td>
                                            <a href="{{ asset('storage/' . $ua->proposal->path_proposal) }}"
                                                target="_blank" class="btn btn-sm btn-secondary text-rose">lihat
                                                Proposal</a>
                                        </td>
                                        <td>
                                            @if ($ua->hasil == null)
                                                @can('penilaian_proposal')
                                                    <a href="{{ route('reviewer.audit.form', $ua->id) }}"
                                                        class="btn btn-sm btn-danger">Belum dinilai</a>
                                                @endcan
                                            @else
                                                <button type="button" class="btn btn-sm btn-success" data-toggle="modal"
                                                    data-target="#detail{{ $ua->id }}">Lihat nilai</button>
                                                <div class="modal fade" id="detail{{ $ua->id }}" tabindex="-1"
                                                    role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="card-header card-header-text card-header-info">
                                                                <div class="card-text">
                                                                    <h4 class="card-title">Hasil Penilaian</h4>
                                                                </div>
                                                            </div>
                                                            <div class="card-body">
                                                                {{-- head --}}
                                                                <div class="row px-2">
                                                                    <div class="col-12">
                                                                        <div class="row pt-3 pb-1">
                                                                            <div class="col-3 fw-400">
                                                                                Judul Penelitian
                                                                            </div>
                                                                            <div class="col-9">
                                                                                <p class="card-text">
                                                                                    {{ $ua->proposal->judul }}</p>
                                                                            </div>
                                                                        </div>
                                                                        @foreach ($ua->proposal->dosen as $dsn)
                                                                            @if ($dsn->pivot->isLeader == true)
                                                                                <div class="row py-1">
                                                                                    <div class="col-3 fw-300">
                                                                                        Ketua Peneliti
                                                                                    </div>
                                                                                    <div class="col-9">
                                                                                        <p class="card-text">
                                                                                            {{ $dsn->nama }}
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row py-1">
                                                                                    <div class="col-3 fw-400">
                                                                                        Fakultas
                                                                                    </div>
                                                                                    <div class="col-9">
                                                                                        <p class="card-text">
                                                                                            {{ $dsn->prodi->faculty->nama_faculty }}
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row py-1">
                                                                                    <div class="col-3 fw-400">
                                                                                        Catatan
                                                                                    </div>
                                                                                    <div class="col-9">
                                                                                        <p class="card-tex text-justify">
                                                                                            {{ $ua->hasil->komentar }}
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row py-1">
                                                                                    <div class="col-3 fw-400">
                                                                                        Tanggal Monev
                                                                                    </div>
                                                                                    <div class="col-9">
                                                                                        <p class="card-tex text-justify">
                                                                                            {{ $ua->hasil->created_at->format('d M Y') }}
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                            @endif
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                                {{-- end head --}}
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
                                                                                    {{ $ua->hasil->perumusan }}</h6>
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
                                                                                    {{ $ua->hasil->peluang }}</h6>
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
                                                                                    {{ $ua->hasil->metode }}</h6>
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
                                                                                    {{ $ua->hasil->tinjauan }}</h6>
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
                                                                                    {{ $ua->hasil->kelayakan }}</h6>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row pt-2 align-items-center">
                                                                            <div class="col-9">
                                                                                <h5 class="fw-500">TOTAL NILAI</h5>
                                                                            </div>
                                                                            <div class="col-3 text-center">
                                                                                <h5 class="fw-400">
                                                                                    {{ $ua->hasil->total }}</h5>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--  end card  -->
            </div>
            <!-- end col-md-12 -->
        </div>
    </div>
    </div>
@endsection
