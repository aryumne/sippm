@extends('layouts.main')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header card-header-text card-header-info">
                        <div class="card-text">
                            <h4 class="fw-400">Detail Kegiatan</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td>
                                            <p>Judul Kegiatan</p>
                                        </td>
                                        <td class="text-left">
                                            <h5>
                                                {{ $kegiatan->judul_kegiatan }}
                                            </h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>Jenis Kegiatan</p>
                                        </td>
                                        <td class="text-left">
                                            <h5>
                                                @if ($kegiatan->jenis_kegiatan == 1)
                                                    Penelitian
                                                @elseif ($kegiatan->jenis_kegiatan == 2)
                                                    Pengabdian kepada Masyarakat
                                                @endif
                                            </h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>Sumber Dana </p>
                                        </td>
                                        <td class="text-left">
                                            <h5>
                                                {{ $kegiatan->sumberDana->sumber }}
                                            </h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>Jumlah Dana</p>
                                        </td>
                                        <td class="text-left">
                                            <h5>
                                                {{ 'Rp ' . number_format($kegiatan->jumlah_dana, 2, ',', '.') }}
                                            </h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>Fakultas</p>
                                        </td>
                                        <td class="text-left">
                                            <h5>
                                                {{ $kegiatan->prodi->faculty->nama_faculty }}
                                            </h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>Prodi</p>
                                        </td>
                                        <td class="text-left">
                                            <h5>
                                                {{ $kegiatan->prodi->nama_prodi }}
                                            </h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>Tanggal Kegiatan</p>
                                        </td>
                                        <td class="text-left">
                                            <h5>
                                                {{ $kegiatan->tanggal_kegiatan->format('d M Y') }}
                                            </h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>File Laporan</p>
                                        </td>
                                        <td class="text-left">
                                            <a href="{{ asset('storage/' . $kegiatan->path_kegiatan) }}" target="_blank"
                                                class="badge badge-success">download file laporan</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class=""></div>
                        @if (Auth::user()->id == $kegiatan->user_id)
                            <a href="{{ route('kegiatan.edit', $kegiatan) }}"
                                class="btn btn-sm btn-warning text-right">Edit</a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title fw-400">Tim Pelaksana</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4 col-lg-3 text-left">
                                <h6>Ketua :</h6>
                            </div>
                            <div class="col-sm-8 col-lg-9">
                                <p class="mb-1">{{ $kegiatan->user->dosen->nama }}</p>
                                <p class="mb-1">
                                    {{ str_pad($kegiatan->user->dosen->nidn, 10, '0', STR_PAD_LEFT) }}</p>
                                <p class="mb-1">{{ $kegiatan->user->dosen->prodi->faculty->nama_faculty }}</p>
                                <p>{{ $kegiatan->user->dosen->prodi->nama_prodi }}</p>
                            </div>
                        </div>
                        <div class="row">
                            @foreach ($kegiatan->anggotaKegiatan as $dsn)
                                <div class="col-sm-4 col-lg-3 text-left">
                                    <h6>Anggota {{ $loop->iteration }} :</h6>
                                </div>
                                <div class="col-sm-8 col-lg-9">
                                    <p class="mb-1">{{ $dsn->nama }}</p>
                                    <p class="mb-1">{{ str_pad($dsn->nidn, 10, '0', STR_PAD_LEFT) }}</p>
                                    <p class="mb-1">{{ $dsn->prodi->faculty->nama_faculty }}</p>
                                    <p>{{ $dsn->prodi->nama_prodi }}</p>
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
