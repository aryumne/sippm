@extends('layouts.main')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-lg-8">
                <div class="card">
                    <div class="card-header card-header-text card-header-info">
                        <div class="card-text">
                            <h4 class="card-title">Monitoring dan Evaluasi Laporan Kemajuan</h4>
                            <p class="card-category">Batas waktu monev: 3 Maret 2022</p>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-hover">
                            <thead class="text-dark">
                                <th>ID</th>
                                <th>Judul Proposal</th>
                                <th>File Laporan Kemajuan</th>
                                <th>monev</th>
                            </thead>
                            <tbody>
                                @foreach ($userAudit as $ua)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $ua->kemajuan->proposal->judul }}</td>
                                        <td>
                                            <a href="{{ asset('storage/' . $ua->kemajuan->path_kemajuan) }}"
                                                target="_blank" class="btn btn-sm btn-secondary text-rose">lihat
                                                Laporan Kemajuan</a>
                                        </td>
                                        <td>
                                            @if ($ua->hasil == null)
                                                @can('monev_laporan_kemajuan')
                                                    <a href="{{ route('reviewer.monev.form', $ua->id) }}"
                                                        class="btn btn-sm btn-danger">Belum dinilai</a>
                                                @endcan
                                            @else
                                                <button type="button" class="btn btn-sm btn-success" data-toggle="modal"
                                                    data-target="#detail{{ $ua->id }}">Lihat nilai</button>
                                                <div class="modal fade" id="detail{{ $ua->id }}" tabindex="-1"
                                                    role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="card-header card-header-text card-header-warning">
                                                                <div class="card-text">
                                                                    <h4 class="card-title">Hasil Monitoring dan Evaluasi
                                                                    </h4>
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
                                                                                    {{ $ua->kemajuan->proposal->judul }}
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                        @foreach ($ua->kemajuan->proposal->dosen as $dsn)
                                                                            @if ($dsn->pivot->isLeader == true)
                                                                                <div class="row py-1">
                                                                                    <div class="col-3 fw-400">
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
                                                                                <div class="row pt-1">
                                                                                    <div class="col-3 fw-400">
                                                                                        Catatan Penilaian
                                                                                    </div>
                                                                                    <div class="col-9">
                                                                                        <p class="card-text">
                                                                                            {{ $ua->hasil->komentar }}
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row pt-1">
                                                                                    <div class="col-3 fw-400">
                                                                                        Tanggal Penilaian
                                                                                    </div>
                                                                                    <div class="col-9">
                                                                                        <p class="card-text">
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
                                                                            <div class="col-5">
                                                                                <h6>KRITERIA PENILAIAN</h6>
                                                                            </div>
                                                                            <div class="col-2 text-center">
                                                                                <h6>NILAI</h6>
                                                                            </div>
                                                                            <div class="col-5">
                                                                                <h6>KOMENTAR</h6>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row pt-2 bg-light align-items-center">
                                                                            <div class="col-5">
                                                                                <p class="card-text fw-400 mb-0">Kemajuan
                                                                                    ketercapaian luaran wajib</p>
                                                                            </div>
                                                                            <div class="col-2 text-center">
                                                                                <h6 class="fw-400">
                                                                                    {{ $ua->hasil->luaran_wajib['nilai'] }}
                                                                                </h6>
                                                                            </div>
                                                                            <div class="col-5">
                                                                                <p class="card-text">
                                                                                    {{ $ua->hasil->luaran_wajib['komentar'] }}
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row pt-2 align-items-center">
                                                                            <div class="col-5">
                                                                                <p class="card-text fw-400 mb-0">Kemajuan
                                                                                    ketercapaian luaran tambahan
                                                                                    yang dijanjikan</p>
                                                                            </div>
                                                                            <div class="col-2 text-center">
                                                                                <h6 class="fw-400">
                                                                                    {{ $ua->hasil->luaran_tambahan['nilai'] }}
                                                                                </h6>
                                                                            </div>
                                                                            <div class="col-5">
                                                                                <p class="card-text">
                                                                                    {{ $ua->hasil->luaran_tambahan['komentar'] }}
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row pt-2 bg-light align-items-center">
                                                                            <div class="col-5">
                                                                                <p class="card-text fw-400 mb-0">Kesesuaian
                                                                                    penelitian dengan usulan</p>
                                                                            </div>
                                                                            <div class="col-2 text-center">
                                                                                <h6 class="fw-400">
                                                                                    {{ $ua->hasil->kesesuaian['nilai'] }}
                                                                                </h6>
                                                                            </div>
                                                                            <div class="col-5">
                                                                                <p class="card-text">
                                                                                    {{ $ua->hasil->kesesuaian['komentar'] }}
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row pt-2 align-items-center">
                                                                            <div class="col-5">
                                                                                <h5 class="fw-500">TOTAL NILAI</h5>
                                                                            </div>
                                                                            <div class="col-2 text-center">
                                                                                <h5 class="fw-400">
                                                                                    {{ $ua->hasil->total }}</h5>
                                                                            </div>
                                                                            <div class="col-5 text-center">
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
