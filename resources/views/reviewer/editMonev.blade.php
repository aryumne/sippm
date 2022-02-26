@extends('layouts.main')
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12 col-lg-11">
                <div class="card">
                    <div class="card-header text-center card-header-warning">
                        <h4 class="card-title fw-400">BORANG PENILAIAN MONEV</h4>
                        <h4 class="card-title fw-400">PENELITIAN DOSEN ASISTEN AHLI</h4>
                    </div>
                    <div class="card-body">
                        {{-- head --}}
                        <div class="row">
                            <div class="col-12">
                                <div class="row pt-3 pb-1">
                                    <div class="col-3 fw-400">
                                        Judul Penelitian
                                    </div>
                                    <div class="col-9">
                                        <p class="card-text">{{ $hasilMonev->monev->kemajuan->proposal->judul }}</p>
                                    </div>
                                </div>
                                @foreach ($hasilMonev->monev->kemajuan->proposal->dosen as $dsn)
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
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        {{-- end head --}}
                        {{-- form --}}
                        <div class="row pt-3 px-1">
                            <div class="col-12">
                                <form action="{{ route('reviewer.monev.update', $hasilMonev->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-lg-5 d-none d-md-none d-lg-block">
                                            <div class="row">
                                                <div class="col-9">
                                                    <h6>KOMPONEN PENILAIAN</h6>
                                                </div>
                                                <div class="col-3 text-center">
                                                    <h6>SKOR</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-7 d-none d-md-none d-lg-block">
                                            <div class="row">
                                                <div class="col-4 text-center">
                                                    <h6>Tingkat Ketercapaian</h6>
                                                </div>
                                                <div class="col-4 text-center">
                                                    <h6>NILAI</h6>
                                                </div>
                                                <div class="col-4 text-center">
                                                    <h6>KOMENTAR</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row bg-light">
                                        <div class="col-lg-5 pt-3">
                                            <div class="row">
                                                <div class="col-9">
                                                    <h6 class="d-sm-block d-md-block d-lg-none text-center">KOMPONEN
                                                        PENILAIAN
                                                    </h6>
                                                    <p class="card-text fw-400 mb-0">Kemajuan ketercapaian luaran wajib</p>
                                                </div>
                                                <div class="col-3 text-center">
                                                    <h6 class="d-sm-block d-md-block d-lg-none text-center">SKOR
                                                    </h6>
                                                    <p class="card-text fw-400 mb-0">50</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-7">
                                            <div class="row pt-2">
                                                <div class="col-12 col-lg-8 text-center">
                                                    <div class="row">
                                                        <div class="col-9 col-lg-8">
                                                            <div class="form-group">
                                                                <select required class="form-control selectpicker"
                                                                    data-style="btn btn-link" id="point1"
                                                                    title="Tingkat ketercapaian">
                                                                    <option value="50"
                                                                        {{ $hasilMonev->luaran_wajib['nilai'] / 50 == 50 ? 'Selected' : '' }}>
                                                                        Telah tercapai/terlaksana</option>
                                                                    <option value="40"
                                                                        {{ $hasilMonev->luaran_wajib['nilai'] / 50 == 40 ? 'Selected' : '' }}>
                                                                        Borpotensi besar dapat tercapai
                                                                    </option>
                                                                    <option value="25"
                                                                        {{ $hasilMonev->luaran_wajib['nilai'] / 50 == 25 ? 'Selected' : '' }}>
                                                                        Berpotensi dapat tercapai</option>
                                                                    <option value="15"
                                                                        {{ $hasilMonev->luaran_wajib['nilai'] / 50 == 15 ? 'Selected' : '' }}>
                                                                        Kemungkinan tercapai rendah</option>
                                                                    <option value="0"
                                                                        {{ $hasilMonev->luaran_wajib['nilai'] / 50 == 0 ? 'Selected' : '' }}>
                                                                        Tidak ada capaian</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-3 col-lg-4">
                                                            <div class="form-group">
                                                                <input type="number" class="form-control text-center"
                                                                    name="luaran_wajib"
                                                                    value="{{ old('luaran_wajib', $hasilMonev->luaran_wajib['nilai']) }}"
                                                                    id="luaran_wajib" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-4">
                                                    <div class="form-group">
                                                        <label for="exampleFormControlTextarea1">Komentar</label>
                                                        <textarea class="form-control" id="komentar"
                                                            name="komentar_luaran_wajib" rows="3"
                                                            required>{{ old('komentar_luaran_wajib', $hasilMonev->luaran_wajib['komentar']) }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-5 pt-3">
                                            <div class="row">
                                                <div class="col-9">
                                                    <h6 class="d-sm-block d-md-block d-lg-none text-center">KOMPONEN
                                                        PENILAIAN
                                                    </h6>
                                                    <p class="card-text fw-400 mb-0">Kemajuan ketercapaian luaran tambahan
                                                        yang dijanjikan</p>
                                                </div>
                                                <div class="col-3 text-center">
                                                    <h6 class="d-sm-block d-md-block d-lg-none text-center">SKOR
                                                    </h6>
                                                    <p class="card-text fw-400 mb-0">35</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-7">
                                            <div class="row pt-2">
                                                <div class="col-12 col-lg-8 text-center">
                                                    <div class="row">
                                                        <div class="col-9 col-lg-8">
                                                            <div class="form-group">
                                                                <select required class="form-control selectpicker "
                                                                    data-style="btn btn-link" id="point2"
                                                                    title="Tingkat ketercapaian">
                                                                    <option value="15"
                                                                        {{ $hasilMonev->luaran_tambahan['nilai'] / 35 == 15 ? 'Selected' : '' }}>
                                                                        Telah tercapai/terlaksana</option>
                                                                    <option value="10"
                                                                        {{ $hasilMonev->luaran_tambahan['nilai'] / 35 == 10 ? 'Selected' : '' }}>
                                                                        Draft</option>
                                                                    <option value="0"
                                                                        {{ $hasilMonev->luaran_tambahan['nilai'] / 35 == 0 ? 'Selected' : '' }}>
                                                                        Belum tercapai</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-3 col-lg-4">
                                                            <div class="form-group">
                                                                <input type="number" class="form-control text-center"
                                                                    name="luaran_tambahan"
                                                                    value="{{ old('luaran_tambahan', $hasilMonev->luaran_tambahan['nilai']) }}"
                                                                    id="luaran_tambahan" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-4">
                                                    <div class="form-group">
                                                        <label for="exampleFormControlTextarea1">Komentar</label>
                                                        <textarea class="form-control" id="komentar"
                                                            name="komentar_luaran_tambahan" rows="3"
                                                            required>{{ old('komentar_luaran_tambahan', $hasilMonev->luaran_tambahan['komentar']) }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row bg-light">
                                        <div class="col-lg-5 pt-3">
                                            <div class="row">
                                                <div class="col-9">
                                                    <h6 class="d-sm-block d-md-block d-lg-none text-center">KOMPONEN
                                                        PENILAIAN
                                                    </h6>
                                                    <p class="card-text fw-400 mb-0">Kesesuaian penelitian dengan usulan</p>
                                                </div>
                                                <div class="col-3 text-center">
                                                    <h6 class="d-sm-block d-md-block d-lg-none text-center">SKOR
                                                    </h6>
                                                    <p class="card-text fw-400 mb-0">15</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-7">
                                            <div class="row pt-2">
                                                <div class="col-12 col-lg-8 text-center">
                                                    <div class="row">
                                                        <div class="col-9 col-lg-8">
                                                            <div class="form-group">
                                                                <select required class="form-control selectpicker"
                                                                    data-style="btn btn-link" id="point3"
                                                                    title="Tingkat ketercapaian">
                                                                    <option value="10"
                                                                        {{ $hasilMonev->kesesuaian['nilai'] / 15 == 10 ? 'Selected' : '' }}>
                                                                        Sesuai</option>
                                                                    <option value="0"
                                                                        {{ $hasilMonev->kesesuaian['nilai'] / 15 == 0 ? 'Selected' : '' }}>
                                                                        Tidak sesuai</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-3 col-lg-4">
                                                            <div class="form-group">
                                                                <input type="number" class="form-control text-center"
                                                                    name="kesesuaian"
                                                                    value="{{ old('kesesuaian', $hasilMonev->kesesuaian['nilai']) }}"
                                                                    id="kesesuaian" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-4">
                                                    <div class="form-group">
                                                        <label for="exampleFormControlTextarea1">Komentar</label>
                                                        <textarea class="form-control" id="komentar"
                                                            name="komentar_kesesuaian" rows="3"
                                                            required>{{ old('komentar_kesesuaian', $hasilMonev->kesesuaian['komentar']) }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-8 col-lg-8">
                                            <h6 class="text-center pt-3">TOTAL NILAI
                                            </h6>
                                        </div>
                                        <div class="col-sm-4 col-lg-2 pr-lg-5 pl-lg-3 pl-md-5">
                                            <div class="form-group">
                                                <input type="number" class="form-control text-center" name="total"
                                                    value="{{ old('total', $hasilMonev->total) }}" id="total" readonly>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 col-lg-2">
                                        </div>
                                    </div>
                                    <div class="row pt-3">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="exampleFormControlTextarea1">Catatan</label>
                                                <textarea class="form-control" id="komentar" name="komentar" rows="3"
                                                    required>{{ old('komentar', $hasilMonev->komentar) }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <a href="{{ route('reviewer.monev.kemajuan') }}"
                                            class="btn btn-secondary">Batal</a>
                                        <button type="submit" class="btn btn-rose">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        {{-- end form --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('customSCript')
    <script>
        console.log('test');
        //luaran_wajib
        $('#point1').on('change', function() {
            $("#luaran_wajib").val(this.value * 50);
            var point1 = document.getElementById('luaran_wajib').value;
            let p1 = Number(point1);
            console.log(p1);
            var point2 = document.getElementById('luaran_tambahan').value;
            let p2 = Number(point2);
            var point3 = document.getElementById('kesesuaian').value;
            let p3 = Number(point3);
            total = p1 + p2 + p3;
            $("#total").val(total);
        });
        //luaran_tambahan
        $('#point2').on('change', function() {
            $("#luaran_tambahan").val(this.value * 35);
            var point1 = document.getElementById('luaran_wajib').value;
            let p1 = Number(point1);
            var point2 = document.getElementById('luaran_tambahan').value;
            let p2 = Number(point2);
            var point3 = document.getElementById('kesesuaian').value;
            let p3 = Number(point3);
            total = p1 + p2 + p3;
            $("#total").val(total);
        });
        //kesesuaian
        $('#point3').on('change', function() {
            $("#kesesuaian").val(this.value * 15);
            var point1 = document.getElementById('luaran_wajib').value;
            let p1 = Number(point1);
            var point2 = document.getElementById('luaran_tambahan').value;
            let p2 = Number(point2);
            var point3 = document.getElementById('kesesuaian').value;
            let p3 = Number(point3);
            total = p1 + p2 + p3;
            $("#total").val(total);
        });
    </script>
@endsection
