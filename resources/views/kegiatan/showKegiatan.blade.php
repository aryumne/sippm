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
                                            {{ $kegiatan->jenis_kegiatan ? 'Penelitian' : 'Pengabdian kepada Masyarakat' }}
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
                                        <p>Tahun Kegiatan</p>
                                    </td>
                                    <td class="text-left">
                                        <h5>
                                            {{ $kegiatan->tahun }}
                                        </h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p>File Laporan</p>
                                    </td>
                                    <td class="text-left">
                                        <a href="{{ asset('storage/' . $kegiatan->path_kegiatan) }}" target="_blank" class="badge badge-success">download file laporan</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    @foreach ($kegiatan->timIntern as $ketua)
                    @if($ketua->pivot->isLeader == true)
                    @if($ketua->nidn == Auth::user()->nidn || Auth::user()->role_id == 1)
                    <div>
                        <form action="{{ route('kegiatan.destroy', $kegiatan) }}" method="POST">
                            @csrf
                            @method("DELETE")
                            <button type="submit" onclick="return confirm('Yakin akan menghapus data ini ?')" data-bs-toggle="tooltip" data-bs-original-title="Delete" class="btn btn-sm btn-danger text-right">Hapus</button>
                        </form>
                    </div>
                    <a href="{{ route('kegiatan.edit', $kegiatan) }}" class="btn btn-sm btn-warning text-right">Edit</a>
                    @endif
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title fw-400">Tim Pelaksana</h4>
                </div>
                <div class="card-body">
                    @foreach ($kegiatan->timIntern as $dsn)
                    <div class="row">
                        <div class="col-sm-4 col-lg-3 text-left">
                            <h6>{{ $dsn->pivot->isLeader == true ? 'Ketua ' : 'Anggota '.$loop->index }}</h6>
                        </div>
                        <div class="col-sm-8 col-lg-9">
                            <p class="mb-1">{{ $dsn->nama }}</p>
                            <p class="mb-1">{{ str_pad($dsn->nidn, 10, '0', STR_PAD_LEFT) }}</p>
                            <p class="mb-1">{{ $dsn->prodi->faculty->nama_faculty }}</p>
                            <p>{{ $dsn->prodi->nama_prodi }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
