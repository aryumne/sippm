@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header card-header-info card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">assignment</i>
                        </div>
                        <div class="row card-title">
                            <div class="col-md-6">
                                <h4 class="fw-400">Data Dosen</h4>
                            </div>
                            <div class="col-md-6 text-right">
                                <button type="button" class="btn btn-secondary text-rose mt-0" data-toggle="modal"
                                    data-target="#importForm">
                                    <span class="material-icons">file_upload</span> Import
                                </button>
                                <button type="button" class="btn btn-rose mt-0" data-toggle="modal" data-target="#addForm">
                                    <span class="material-icons">person_add</span> Tambah
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="material-datatables">
                            <table id="datatables-dosen" class="table table-striped table-no-bordered table-hover"
                                cellspacing="0" width="100%" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>NIDN</th>
                                        <th>Nama</th>
                                        <th>Jabatan</th>
                                        <th>Fakultas</th>
                                        <th>Prodi</th>
                                        <th class="disabled-sorting text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>NIDN</th>
                                        <th>Nama</th>
                                        <th>Jabatan</th>
                                        <th>Fakultas</th>
                                        <th>Prodi</th>
                                        <th class="disabled-sorting text-right">Actions</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach ($dosens as $dsn)
                                        <tr>
                                            <td>{{ str_pad($dsn->nidn, 10, '0', STR_PAD_LEFT) }}</td>
                                            <td>{{ $dsn->nama }}</td>
                                            <td>{{ $dsn->jabatan_id != null ? $dsn->jabatan->nama_jabatan : 'belum ada' }}
                                            </td>
                                            <td>{{ $dsn->prodi->faculty->nama_faculty }}</td>
                                            <td>{{ $dsn->prodi->nama_prodi }}</td>
                                            <td class="text-right">
                                                <a href="{{ route('dosen.show', str_pad($dsn->nidn, 10, '0', STR_PAD_LEFT)) }}"
                                                    class="btn btn-link btn-info btn-just-icon like"><i
                                                        class="material-icons">read_more</i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('modal')
    <!-- Import Data Dosen -->
    <div class="modal fade" id="importForm" tabindex="-1" role="dialog" aria-labelledby="importForm" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Import Data Dosen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form" action="{{ route('importDosen') }}" method="POST"
                    enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group form-file-upload form-file-multiple">
                            <input type="file" name="file_excel" class="inputFileHidden" required>
                            <div class="input-group">
                                <input type="text" class="form-control inputFileVisible" placeholder="Single File">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-fab btn-round btn-primary">
                                        <i class="material-icons">attach_file</i>
                                    </button>
                                </span>
                            </div>
                        </div>
                        @error('file_excel')
                            <span id="category_id-error" class="error text-danger" for="input-id"
                                style="display: block;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-rose">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Tambah Data Dosen -->
    <div class="modal fade" id="addForm" tabindex="-1" role="dialog" aria-labelledby="importForm" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Data Dosen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form" id="AddDosenValidation" action="{{ route('dosen.store') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <span class="form-group bmd-form-group email-error ">
                            @if ($errors->any())
                                @foreach ($errors->all() as $e)
                                    <p class="
                                description text-center text-danger">
                                        {{ $e }}</p>
                                @endforeach
                            @endif
                        </span>
                        <div class="row align-items-center">
                            <div class="col-3">
                                <h6>NIDN</h6>
                            </div>
                            <div class="col-9">
                                <input type="text" class="form-control pl-2" id="nidn" name="nidn"
                                    value="{{ old('nidn') }}" required>
                                @error('nidn')
                                    <span id="category_id-error" class="error text-danger" for="input-id"
                                        style="display: block;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row pt-2 align-items-center">
                            <div class="col-3">
                                <h6>NAMA</h6>
                            </div>
                            <div class="col-9">
                                <div class="card-title">
                                    <input type="text" class="form-control pl-2" id="nama" name="nama" required
                                        value="{{ old('nama') }}">
                                    @error('nama')
                                        <span id="category_id-error" class="error text-danger" for="input-id"
                                            style="display: block;">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row pt-2 align-items-center">
                            <div class="col-3">
                                <h6>Jabatan</h6>
                            </div>
                            <div class="col-9">
                                <div class="card-title">
                                    <select class="form-control selectpicker" data-style="btn btn-link" id="jabatan_id"
                                        name="jabatan_id" required>
                                        @foreach ($jabatans as $pro)
                                            <option value="{{ $pro->id }}"
                                                {{ old('jabatan_id') == $pro->id ? 'Selected' : '' }}>
                                                {{ $pro->nama_jabatan }}
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
                                    <select class="form-control selectpicker" data-style="btn btn-link" id="prodi_id"
                                        name="prodi_id" data-size="8" required>
                                        @foreach ($prodis as $pro)
                                            <option value="{{ $pro->id }}"
                                                {{ old('prodi_id') == $pro->id ? 'Selected' : '' }}>
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
                                        value="{{ old('email') }}">
                                    @error('email')
                                        <span id="category_id-error" class="error text-danger" for="input-id"
                                            style="display: block;">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row pt-2 align-items-center">
                            <div class="col-3">
                                <h6>Hp</h6>
                            </div>
                            <div class="col-9">
                                <div class="card-title">
                                    <input type="number" class="form-control pl-2" id="handphone" name="handphone" required
                                        value="{{ old('handphone') }}">
                                    @error('handphone')
                                        <span id="category_id-error" class="error text-danger" for="input-id"
                                            style="display: block;">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer pt-3">
                        <button class="btn btn-secondary" type="button">Batal</button>
                        <button type="submit" class="btn btn-rose">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('customSCript')
    <script>
        $(document).ready(function() {
            //datatables
            $('#datatables-dosen').DataTable({
                //pagingType documentation : "https://datatables.net/reference/option/pagingType"
                "pagingType": "first_last_numbers",
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                responsive: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search records",
                }
            });
        });
    </script>
@endsection
