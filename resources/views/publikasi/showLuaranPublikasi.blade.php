@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-7 col-md-12">
            <div class="card">
                <div class="card-header card-header-text card-header-info">
                    <div class="card-text">
                        <h4 class="fw-400">Detail Luaran Publikasi</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>
                                        <p>Judul Artikel</p>
                                    </td>
                                    <td class="text-left">
                                        <h5>
                                            {{ $lapPublikasi->judul }}
                                        </h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p>Nama Jurnal</p>
                                    </td>
                                    <td class="text-left">
                                        <h5>
                                            {{ $lapPublikasi->nama }}
                                        </h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p>Laman Jurnal</p>
                                    </td>
                                    <td class="text-left">
                                        <h5>
                                            {{ $lapPublikasi->laman }}
                                        </h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p>Media Publikasi</p>
                                    </td>
                                    <td class="text-left">
                                        <h5>
                                            {{ $lapPublikasi->jenis_jurnal->jurnal }}
                                        </h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p>Tahun Publikasi</p>
                                    </td>
                                    <td class="text-left">
                                        <h5>
                                            {{ $lapPublikasi->tahun }}
                                        </h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p>File Unggahan</p>
                                    </td>
                                    <td class="text-left">
                                        <a href="{{ asset('storage/' . $lapPublikasi->path_publikasi) }}" target="_blank" class="badge badge-success">{{ substr($lapPublikasi->path_publikasi, 18) }}</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    @foreach ($lapPublikasi->timIntern as $ketua)
                    @if($ketua->pivot->isLeader == true)
                    @if($ketua->nidn == Auth::user()->nidn || Auth::user()->role_id == 1)
                    <div>
                        <form action="{{ route('luaran-publikasi.destroy', $lapPublikasi->id) }}" method="POST">
                            @csrf
                            @method("DELETE")
                            <button type="submit" onclick="return confirm('Yakin menghapus data ini ?')" data-bs-toggle="tooltip" data-bs-original-title="Delete" class="btn btn-sm btn-danger text-right">Hapus</button>
                        </form>
                    </div>
                    <a href="{{ route('luaran-publikasi.edit', $lapPublikasi->id) }}" class="btn btn-sm btn-warning text-right">Edit</a>
                    @endif
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-xl-5 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title fw-400">Tim Penulis</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4 col-lg-3 text-right">
                            <h6>Penulis 1 :</h6>
                        </div>
                        <div class="col-sm-8 col-lg-9">
                            @foreach ($lapPublikasi->timIntern as $ketuaIntern)
                            @if($ketuaIntern->pivot->isLeader == true)
                            <p class="mb-1">{{ $ketuaIntern->nama }}</p>
                            <p class="mb-1">{{ str_pad($ketuaIntern->nidn, 10, '0', STR_PAD_LEFT) }}</p>
                            <p class="mb-1">{{ $ketuaIntern->prodi->faculty->nama_faculty }}</p>
                            <p>{{ $ketuaIntern->prodi->nama_prodi }}</p>
                            @endif
                            @endforeach
                            @foreach ($lapPublikasi->timExtern as $ketuaExtern)
                            @if($ketuaExtern->isLeader == true)
                            <p class="mb-1">{{ $ketuaExtern->nama }}</p>
                            <p class="mb-1">{{ $ketuaExtern->asal_institusi }}</p>
                            @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="row">
                        @php($i = 0)
                        {{-- Anggota dari UNIPA --}}
                        @foreach ($lapPublikasi->timIntern as $anggotaIntern)
                        @if ($anggotaIntern->pivot->isLeader == false)
                        <div class="col-sm-4 col-lg-3 text-right">
                            <h6>Penulis {{ $loop->iteration+1 }} :</h6>
                        </div>
                        <div class="col-sm-8 col-lg-9">
                            <p class="mb-1">{{ $anggotaIntern->nama }}</p>
                            <p class="mb-1">{{ str_pad($anggotaIntern->nidn, 10, '0', STR_PAD_LEFT) }}</p>
                            <p class="mb-1">{{ $anggotaIntern->prodi->faculty->nama_faculty }}</p>
                            <p>{{ $anggotaIntern->prodi->nama_prodi }}</p>
                        </div>
                        @php($i = $loop->iteration+1)
                        @endif
                        @endforeach

                        {{-- Anggota Luar UNIPA --}}
                        @foreach ($lapPublikasi->timExtern as $anggotaExtern)
                        @if ($anggotaExtern->isLeader == false)
                        <div class="col-sm-4 col-lg-3 text-right">
                            <h6>Penulis {{ $i+1 }} :</h6>
                        </div>
                        <div class="col-sm-8 col-lg-9">
                            <p class="mb-1">{{ $anggotaExtern->nama }}</p>
                            <p class="mb-1">{{ $anggotaExtern->asal_institusi }}</p>
                        </div>
                        @php($i++)
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
