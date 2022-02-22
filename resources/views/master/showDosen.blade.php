@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card">
                    <div class="card-header card-header-info card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">perm_identity</i>
                        </div>
                        <div class="row card-title">
                            <h4 class="fw-400">Detail Data Dosen</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row pt-3 px-3" id="show">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-3">
                                        <h6>NIDN</h6>
                                    </div>
                                    <div class="col-9">
                                        <div class="card-title">
                                            {{ str_pad($dosen->nidn, 10, '0', STR_PAD_LEFT) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="row pt-2">
                                    <div class="col-3">
                                        <h6>NAMA</h6>
                                    </div>
                                    <div class="col-9">
                                        <div class="card-title">
                                            {{ $dosen->nama }}
                                        </div>
                                    </div>
                                </div>
                                <div class="row pt-2">
                                    <div class="col-3">
                                        <h6>Jabatan</h6>
                                    </div>
                                    <div class="col-9">
                                        <div class="card-title">
                                            {{ $dosen->jabatan_id == null ? 'Belum ada' : $dosen->jabatan->nama_jabatan }}
                                        </div>
                                    </div>
                                </div>
                                <div class="row pt-2">
                                    <div class="col-3">
                                        <h6>Fakultas</h6>
                                    </div>
                                    <div class="col-9">
                                        <div class="card-title">
                                            {{ $dosen->prodi->faculty->nama_faculty }}
                                        </div>
                                    </div>
                                </div>
                                <div class="row pt-2">
                                    <div class="col-3">
                                        <h6>Program Pendidikan</h6>
                                    </div>
                                    <div class="col-9">
                                        <div class="card-title">
                                            {{ $dosen->prodi->nama_prodi }}
                                        </div>
                                    </div>
                                </div>
                                <div class="row pt-2">
                                    <div class="col-3">
                                        <h6>Email</h6>
                                    </div>
                                    <div class="col-9">
                                        <div class="card-title">
                                            {{ $dosen->email == null ? 'Belum ada' : $dosen->email }}
                                        </div>
                                    </div>
                                </div>
                                <div class="row pt-2">
                                    <div class="col-3">
                                        <h6>Hp</h6>
                                    </div>
                                    <div class="col-9">
                                        <div class="card-title">
                                            {{ $dosen->handphone == null ? 'Belum ada' : $dosen->handphone }}
                                        </div>
                                    </div>
                                </div>
                                <div class="row pt-2 text-right">
                                    <div class="col-12">
                                        <button class="btn btn-warning btn-sm" onclick="changePanelToShow();">Edit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row pt-3 px-3" id="edit">
                            <div class="col-12">
                                <div class="row align-items-center">
                                    <div class="col-3">
                                        <h6>NIDN</h6>
                                    </div>
                                    <div class="col-9">
                                        <input type="text" class="form-control pl-2" id="nidn" name="nidn" readonly
                                            value="{{ str_pad($dosen->nidn, 10, '0', STR_PAD_LEFT) }}">
                                    </div>
                                </div>
                                <div class="row pt-2 align-items-center">
                                    <div class="col-3">
                                        <h6>NAMA</h6>
                                    </div>
                                    <div class="col-9">
                                        <div class="card-title">
                                            <input type="text" class="form-control pl-2" id="nama" name="nama" required
                                                value="{{ old('nama', $dosen->nama) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row pt-2 align-items-center">
                                    <div class="col-3">
                                        <h6>Jabatan</h6>
                                    </div>
                                    <div class="col-9">
                                        <div class="card-title">
                                            <select class="form-control selectpicker" data-style="btn btn-link"
                                                id="jabatan_id" name="jabatan_id" data-size="8" required>
                                                @foreach ($jabatans as $jbt)
                                                    <option value="{{ $jbt->id }}"
                                                        {{ $dosen->jabatan_id == $jbt->id ? 'Selected' : '' }}>
                                                        {{ $jbt->nama_jabatan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row pt-2 align-items-center">
                                    <div class="col-3">
                                        <h6>Program Studi</h6>
                                    </div>
                                    <div class="col-9">
                                        <div class="card-title">
                                            <select class="form-control selectpicker" data-style="btn btn-link"
                                                id="prodi_id" name="prodi_id" data-size="8" required>
                                                @foreach ($prodis as $pro)
                                                    <option value="{{ $pro->id }}"
                                                        {{ $dosen->prodi_id == $pro->id ? 'Selected' : '' }}>
                                                        {{ $pro->nama_prodi }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row pt-2 align-items-center">
                                    <div class="col-3">
                                        <h6>Email</h6>
                                    </div>
                                    <div class="col-9">
                                        <div class="card-title">
                                            <input type="email" class="form-control pl-2" id="email" name="email" required
                                                value="{{ old('email', $dosen->email) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row pt-2 align-items-center">
                                    <div class="col-3">
                                        <h6>Hp</h6>
                                    </div>
                                    <div class="col-9">
                                        <div class="card-title">
                                            <input type="number" class="form-control pl-2" id="handphone" name="handphone"
                                                required value="{{ old('handphone', $dosen->handphone) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer pt-3">
                                    <button class="btn btn-secondary btn-sm" onclick="changePanelToEdit()">Batal</button>
                                    <a class="btn btn-warning btn-sm"
                                        href="{{ route('dosen.update', str_pad($dosen->nidn, 10, '0', STR_PAD_LEFT)) }}">Update</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('customSCript')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#edit').hide();
            $('#fakultas').on('change', function() {
                var prodis = @json($prodis);
                var nilai = this.value;
                prodis.forEach(function(data, index) {
                    if (data['faculty_id'] == nilai) {
                        $('#prodi').append("<option value='" + data['id'] + "'>" + data[
                            'nama_prodi'] + "</option>");
                    }
                });
            });
        });

        function changePanelToEdit() {
            $('#show').show();
            $('#edit').hide();
        }

        function changePanelToShow() {
            $('#show').hide();
            $('#edit').show();
        }
    </script>
@endsection
