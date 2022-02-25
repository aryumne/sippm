@extends('layouts.main')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header card-header-text card-header-info">
                    <div class="card-text">
                        <h4 class="fw-400">Detail Laporan Kemajuan</h4>
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
                                        {{ $kemajuan->proposal->judul }}
                                    </p>
                                </div>
                            </div>
                            @foreach ($kemajuan->proposal->dosen as $dsn)
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
                            <div class="row py-1">
                                <div class="col-3 fw-400">
                                    Laporan Kemajuan
                                </div>
                                <div class="col-9">
                                    <a href="{{ asset('storage/' . $kemajuan->path_kemajuan) }}" target="_blank"
                                        class="badge badge-success">{{ substr($kemajuan->path_kemajuan, 17) }}</a>
                                </div>
                            </div>
                            <div class="row py-1">
                                <div class="col-3 fw-400">
                                    Tanggal Upload
                                </div>
                                <div class="col-9">
                                    <p class="card-text">
                                        {{ $kemajuan->tanggal_upload }}
                                    </p>
                                </div>
                            </div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                    {{-- end head --}}
                    <div class="text-right">
                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                            data-target="#EditLapKemajuan{{ $kemajuan->id }}">Edit</button>
                        <!-- Modal Edit Laporan Kemajuan -->
                        <div class="modal fade" id="EditLapKemajuan{{ $kemajuan->id }}" tabindex="-1" role="dialog"
                            aria-labelledby="formProposal" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Edit
                                            Laporan Kemajuan</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form class="form" id="EditKemajuanValidation"
                                        action="{{ route('laporan-kemajuan.update', $kemajuan->id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        <div class="modal-body">
                                            @csrf
                                            @method('PUT')
                                            <div class="form-group">
                                                <select class="form-control selectpicker" data-style="btn btn-link"
                                                    id="proposal_id" name="proposal_id" required>
                                                    <option value="{{ $kemajuan->proposal_id }}">
                                                        {{ $kemajuan->proposal->judul }}
                                                    </option>
                                                </select>
                                            </div>
                                            @error('proposal_id')
                                            <span id="category_id-error" class="error text-danger" for="input-id"
                                                style="display: block;">{{ $message }}</span>
                                            @enderror
                                            @if (Auth::user()->role_id == 1)
                                            <div class="form-group mt-3">
                                                <input type="date" class="form-control" id="tanggal_upload"
                                                    name="tanggal_upload" placeholder="Tanggal Upload"
                                                    value="{{ old('tanggal_upload', $kemajuan->tanggal_upload) }}"
                                                    required>
                                            </div>
                                            @error('tanggal_upload')
                                            <span id="category_id-error" class="error text-danger" for="input-id"
                                                style="display: block;">{{ $message }}</span>
                                            @enderror
                                            @endif
                                            <div class="form-group form-file-upload form-file-multiple">
                                                <input type="file" name="path_kemajuan" class="inputFileHidden">
                                                <div class="input-group">
                                                    <input type="text" class="form-control inputFileVisible"
                                                        placeholder="Single File">
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-fab btn-round btn-primary">
                                                            <i class="material-icons">attach_file</i>
                                                        </button>
                                                    </span>
                                                </div>
                                                <small class="form-text text-muted text-left"><cite>File yang diupload
                                                        Maximal 2mb dan file
                                                        harus berformat .pdf</cite></small>
                                            </div>
                                            @error('path_kemajuan')
                                            <span id="category_id-error" class="error text-danger" for="input-id"
                                                style="display: block;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-rose">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if (Auth::user()->role_id == 1)
            <div class="card">
                <div class="card-header card-header-text card-header-info">
                    <div class="card-icon">
                        <i class="material-icons">assignment</i>
                    </div>
                    <div class="row card-title">
                        <div class="col-md-6">
                            <h4 class="fw-400">Hasil Monitoring dan Evaluasi</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @isset($monev->hasil)
                    {{-- head --}}
                    <div class="row px-2">
                        <div class="col-12">
                            <div class="row pt-1">
                                <div class="col-3 fw-400">
                                    Catatan Penilaian
                                </div>
                                <div class="col-9">
                                    <p class="card-text">
                                        {{ $monev->hasil->komentar }}
                                    </p>
                                </div>
                            </div>
                            <div class="row pt-1">
                                <div class="col-3 fw-400">
                                    Tanggal Monev
                                </div>
                                <div class="col-9">
                                    <p class="card-text">
                                        {{ $monev->hasil->created_at->format('d M Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    {{-- end head --}}
                    <div class="row pt-2 px-1">
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
                                        {{ $monev->hasil->luaran_wajib['nilai'] }}
                                    </h6>
                                </div>
                                <div class="col-5">
                                    <p class="card-text">
                                        {{ $monev->hasil->luaran_wajib['komentar'] }}
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
                                        {{ $monev->hasil->luaran_tambahan['nilai'] }}
                                    </h6>
                                </div>
                                <div class="col-5">
                                    <p class="card-text">
                                        {{ $monev->hasil->luaran_tambahan['komentar'] }}
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
                                        {{ $monev->hasil->kesesuaian['nilai'] }}
                                    </h6>
                                </div>
                                <div class="col-5">
                                    <p class="card-text">
                                        {{ $monev->hasil->kesesuaian['komentar'] }}
                                    </p>
                                </div>
                            </div>
                            <div class="row pt-2 align-items-center">
                                <div class="col-5">
                                    <h5 class="fw-500">TOTAL NILAI</h5>
                                </div>
                                <div class="col-2 text-center">
                                    <h5 class="fw-400">
                                        {{ $monev->hasil->total }}</h5>
                                </div>
                                <div class="col-5 text-center">
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <p class="card-text text-center">Belum ada hasil monev</p>
                    @endisset
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
</div>
@endsection