@extends('layouts.main')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12 col-lg-11 col-xl-9">
            <div class="card">
                <div class="card-header text-center card-header-warning">
                    <h4 class="card-title fw-400">BORANG PENILAIAN</h4>
                    <h4 class="card-title fw-400">PROPOSAL PENELITIAN DOSEN ASISTEN AHLI</h4>
                </div>
                <div class="card-body">
                    {{-- head --}}
                    <div class="row">
                        <div class="col-12">
                            <div class="row pt-3 pb-1">
                                <div class="col-4 fw-400">
                                    Judul Penelitian
                                </div>
                                <div class="col-8">
                                    <p class="card-text">{{ $proposal->proposal->judul }}</p>
                                </div>
                            </div>
                            @foreach ($proposal->proposal->dosen as $dsn)
                            @if ($dsn->pivot->isLeader == true)
                            <div class="row py-1">
                                <div class="col-4 fw-400">
                                    Ketua Peneliti
                                </div>
                                <div class="col-8">
                                    <p class="card-text">
                                        {{ $dsn->nama }}
                                    </p>
                                </div>
                            </div>
                            <div class="row py-1">
                                <div class="col-4 fw-400">
                                    Fakultas
                                </div>
                                <div class="col-8">
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
                            <form action="{{ route('reviewer.audit.store', $id) }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-8 d-none d-md-none d-lg-block">
                                        <h6>KRITERIA PENILAIAN</h6>
                                    </div>
                                    <div class="col-lg-4 d-none d-md-none d-lg-block">
                                        <div class="row">
                                            <div class="col-4 text-center">
                                                <h6>BOBOT</h6>
                                            </div>
                                            <div class="col-4 text-center">
                                                <h6>SKOR</h6>
                                            </div>
                                            <div class="col-4 text-center">
                                                <h6>NILAI</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row bg-light">
                                    <div class="col-lg-8 pt-3">
                                        <h6 class="d-sm-block d-md-block d-lg-none text-center">KRITERIA PENILAIAN
                                        </h6>
                                        <p class="card-text fw-400 mb-0">Perumusan masalah:</p>
                                        <ul class="mt-0">
                                            <li>Ketajaman perumusan masalah</li>
                                            <li>Tujuan Penelitian</li>
                                        </ul>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="row pt-2">
                                            <div class="col-4 text-center">
                                                <h6 class="d-sm-block d-md-block d-lg-none">BOBOT</h6>
                                                <p class="fw-400 pt-2">25</p>
                                            </div>
                                            <div class="col-4 text-center">
                                                <h6 class="d-sm-block d-md-block d-lg-none">SKOR</h6>
                                                <div class="form-group ">
                                                    <select required class="form-control text-center" id="point1">
                                                        <option disabled selected></option>
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="5">5</option>
                                                        <option value="6">6</option>
                                                        <option value="7">7</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-4 text-center">
                                                <h6 class="d-sm-block d-md-block d-lg-none">NILAI</h6>
                                                <div class="form-group">
                                                    <input type="number" class="form-control text-center" name="perumusan" value="" id="perumusan" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-8 pt-3">
                                        <h6 class="d-sm-block d-md-block d-lg-none text-center">KRITERIA PENILAIAN
                                        </h6>
                                        <p class="card-text fw-400 mb-0">Peluang luaran penelitian:</p>
                                        <ul class="mt-0">
                                            <li>Publikasi ilmiah</li>
                                            <li>Pengembangan iptek-sosbud</li>
                                            <li>Pengayaan bahan ajar</li>
                                        </ul>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="row pt-2">
                                            <div class="col-4 text-center">
                                                <h6 class="d-sm-block d-md-block d-lg-none">BOBOT</h6>
                                                <p class="fw-400 pt-2">25</p>
                                            </div>
                                            <div class="col-4 text-center">
                                                <h6 class="d-sm-block d-md-block d-lg-none">SKOR</h6>
                                                <div class="form-group">
                                                    <select required class="form-control text-center" id="point2">
                                                        <option disabled selected></option>
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="5">5</option>
                                                        <option value="6">6</option>
                                                        <option value="7">7</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-4 text-center">
                                                <h6 class="d-sm-block d-md-block d-lg-none">NILAI</h6>
                                                <div class="form-group">
                                                    <input type="number" class="form-control text-center" name="peluang" value="" id="peluang" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row bg-light">
                                    <div class="col-lg-8 pt-3">
                                        <h6 class="d-sm-block d-md-block d-lg-none text-center ">KRITERIA PENILAIAN
                                        </h6>
                                        <p class="card-text fw-400 mb-0">Metode penelitian:</p>
                                        <ul class="mt-0">
                                            <li>Ketepatan dan kesesuaian metode yang digunakan</li>
                                        </ul>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="row pt-2">
                                            <div class="col-4 text-center">
                                                <h6 class="d-sm-block d-md-block d-lg-none">BOBOT</h6>
                                                <p class="fw-400 pt-2">25</p>
                                            </div>
                                            <div class="col-4 text-center">
                                                <h6 class="d-sm-block d-md-block d-lg-none">SKOR</h6>
                                                <div class="form-group">
                                                    <select required class="form-control text-center" id="point3">
                                                        <option disabled selected></option>
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="5">5</option>
                                                        <option value="6">6</option>
                                                        <option value="7">7</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-4 text-center">
                                                <h6 class="d-sm-block d-md-block d-lg-none">NILAI</h6>
                                                <div class="form-group">
                                                    <input type="number" class="form-control text-center" name="metode" value="" id="metode" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-8 pt-3">
                                        <h6 class="d-sm-block d-md-block d-lg-none text-center ">KRITERIA PENILAIAN
                                        </h6>
                                        <p class="card-text fw-400 mb-0">Tinjauan pustaka:</p>
                                        <ul class="mt-0">
                                            <li>Relevansi</li>
                                            <li>Kemutakhiran</li>
                                            <li>Penyusunan daftar pustaka</li>
                                        </ul>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="row pt-2">
                                            <div class="col-4 text-center">
                                                <h6 class="d-sm-block d-md-block d-lg-none">BOBOT</h6>
                                                <p class="fw-400 pt-2">15</p>
                                            </div>
                                            <div class="col-4 text-center">
                                                <h6 class="d-sm-block d-md-block d-lg-none">SKOR</h6>
                                                <div class="form-group">
                                                    <select required class="form-control text-center" id="point4">
                                                        <option disabled selected></option>
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="5">5</option>
                                                        <option value="6">6</option>
                                                        <option value="7">7</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-4 text-center">
                                                <h6 class="d-sm-block d-md-block d-lg-none">NILAI</h6>
                                                <div class="form-group">
                                                    <input type="number" class="form-control text-center" name="tinjauan" value="" id="tinjauan" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row bg-light">
                                    <div class="col-lg-8 pt-3">
                                        <h6 class="d-sm-block d-md-block d-lg-none text-center ">KRITERIA PENILAIAN
                                        </h6>
                                        <p class="card-text fw-400 mb-0">Kelayakan penelitian:</p>
                                        <ul class="mt-0">
                                            <li>Kesesuaian waktu</li>
                                            <li>Kesesuaian biaya</li>
                                            <li>Kesesuaian personalia</li>
                                        </ul>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="row pt-2">
                                            <div class="col-4 text-center">
                                                <h6 class="d-sm-block d-md-block d-lg-none">BOBOT</h6>
                                                <p class="fw-400 pt-2">10</p>
                                            </div>
                                            <div class="col-4 text-center">
                                                <h6 class="d-sm-block d-md-block d-lg-none">SKOR</h6>
                                                <div class="form-group">
                                                    <select required class="form-control text-center" id="point5">
                                                        <option disabled selected></option>
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="5">5</option>
                                                        <option value="6">6</option>
                                                        <option value="7">7</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-4 text-center">
                                                <h6 class="d-sm-block d-md-block d-lg-none">NILAI</h6>
                                                <div class="form-group">
                                                    <input type="number" class="form-control text-center" name="kelayakan" value="" id="kelayakan" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-8 col-md-8 col-lg-10">
                                        <h6 class="text-center pt-3">TOTAL NILAI
                                        </h6>
                                    </div>
                                    <div class="col-sm 4 col-md-4 col-lg-2">
                                        <div class="form-group">
                                            <input type="number" class="form-control text-center" name="total" value="" id="total" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row pt-3">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="exampleFormControlTextarea1">Catatan</label>
                                            <textarea class="form-control" id="komentar" name="komentar" rows="3" required>{{ old('komentar') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <a href="{{ route('reviewer.audit.proposals') }}" class="btn btn-secondary">Batal</a>
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
    //perumusan

    $('#point1').on('click', function() {
        $("#perumusan").val(this.value * 25);
        let point1 = document.getElementById('perumusan').value;
        let p1 = Number(point1);
        let point2 = document.getElementById('peluang').value;
        let p2 = Number(point2);
        let point3 = document.getElementById('metode').value;
        let p3 = Number(point3);
        let point4 = document.getElementById('tinjauan').value;
        let p4 = Number(point4);
        let point5 = document.getElementById('kelayakan').value;
        let p5 = Number(point5);
        total = p1 + p2 + p3 + p4 + p5;
        $("#total").val(total);
    });
    //peluang
    $('#point2').on('click', function() {
        $("#peluang").val(this.value * 25);
        let point1 = document.getElementById('perumusan').value;
        let p1 = Number(point1);
        let point2 = document.getElementById('peluang').value;
        let p2 = Number(point2);
        let point3 = document.getElementById('metode').value;
        let p3 = Number(point3);
        let point4 = document.getElementById('tinjauan').value;
        let p4 = Number(point4);
        let point5 = document.getElementById('kelayakan').value;
        let p5 = Number(point5);
        total = p1 + p2 + p3 + p4 + p5;
        $("#total").val(total);
    });
    //metode
    $('#point3').on('click', function() {
        $("#metode").val(this.value * 25);
        let point1 = document.getElementById('perumusan').value;
        let p1 = Number(point1);
        let point2 = document.getElementById('peluang').value;
        let p2 = Number(point2);
        let point3 = document.getElementById('metode').value;
        let p3 = Number(point3);
        let point4 = document.getElementById('tinjauan').value;
        let p4 = Number(point4);
        let point5 = document.getElementById('kelayakan').value;
        let p5 = Number(point5);
        total = p1 + p2 + p3 + p4 + p5;
        $("#total").val(total);
    });
    //tinjauan
    $('#point4').on('click', function() {
        $("#tinjauan").val(this.value * 15);
        let point1 = document.getElementById('perumusan').value;
        let p1 = Number(point1);
        let point2 = document.getElementById('peluang').value;
        let p2 = Number(point2);
        let point3 = document.getElementById('metode').value;
        let p3 = Number(point3);
        let point4 = document.getElementById('tinjauan').value;
        let p4 = Number(point4);
        let point5 = document.getElementById('kelayakan').value;
        let p5 = Number(point5);
        total = p1 + p2 + p3 + p4 + p5;
        $("#total").val(total);
    });
    //kelayakan
    $('#point5').on('click', function() {
        $("#kelayakan").val(this.value * 10);
        let point1 = document.getElementById('perumusan').value;
        let p1 = Number(point1);
        let point2 = document.getElementById('peluang').value;
        let p2 = Number(point2);
        let point3 = document.getElementById('metode').value;
        let p3 = Number(point3);
        let point4 = document.getElementById('tinjauan').value;
        let p4 = Number(point4);
        let point5 = document.getElementById('kelayakan').value;
        let p5 = Number(point5);
        total = p1 + p2 + p3 + p4 + p5;
        $("#total").val(total);
    });

</script>
@endsection
