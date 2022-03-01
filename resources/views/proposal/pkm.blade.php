@extends('layouts.main')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-info card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">assignment</i>
                        </div>
                        <div class="row card-title">
                            <div class="col-6">
                                <h4 class="fw-400">Daftar Pengabdian Kepada Masyarakat (PkM)</h4>
                            </div>
                            <div class="col-6 text-right">
                                <button type="button" class="btn btn-secondary text-rose mt-0" data-toggle="modal"
                                    data-target="#formKegiatan">
                                    <span class=" material-icons">add</span> Laporan baru
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="toolbar mb-3 px-3">
                            <div class="card-collapse">
                                <div class="card-header" role="tab" id="headingOne">
                                    <h5 class="mb-0">
                                        <a data-toggle="collapse" href="#collapseOne"
                                            aria-expanded="{{ request('tahun_kegiatan') != null || request('faculty_id') != null || request('sumber_dana') != null? 'true': 'false' }}"
                                            aria-controls="collapseOne" class="collapsed">
                                            Filter data
                                            <i class="material-icons">filter_alt</i>
                                        </a>
                                    </h5>
                                </div>
                                <div id="collapseOne"
                                    class="collapse {{ request('tahun_kegiatan') != null || request('faculty_id') != null || request('sumber_dana') != null? 'show': '' }}"
                                    role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion" style="">
                                    <div class="card-body">
                                        <form action="{{ route('kegiatan.index', 'pkm') }}" method="GET">
                                            @csrf
                                            <div class="row align-items-center">
                                                <div class="col-md-3">
                                                    <h6>Fakultas</h6>
                                                </div>
                                                <div class="col-md-9 px-0">
                                                    <div class="form-group m-0">
                                                        <select class="form-control selectpicker" data-style="btn btn-link"
                                                            id="faculty_id" name="faculty_id">
                                                            <option value="">Semua</option>
                                                            @foreach ($faculties as $faculty)
                                                                <option value="{{ $faculty->id }}"
                                                                    {{ request('faculty_id') == $faculty->id ? 'Selected' : '' }}>
                                                                    {{ $faculty->nama_faculty }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row align-items-center">
                                                <div class="col-md-3">
                                                    <h6>Sumber Dana</h6>
                                                </div>
                                                <div class="col-md-9 px-0">
                                                    <div class="form-group m-0">
                                                        <select class="form-control selectpicker" data-style="btn btn-link"
                                                            id="sumber_dana" name="sumber_dana">
                                                            <option value="">Semua</option>
                                                            @foreach ($sumberDana as $sumber)
                                                                <option value="{{ $sumber->sumber }}"
                                                                    {{ request('sumber_dana') == $sumber->sumber ? 'Selected' : '' }}>
                                                                    {{ $sumber->sumber }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row align-items-center">
                                                <div class="col-md-3">
                                                    <h6>Tahun</h6>
                                                </div>
                                                <div class="col-md-9 px-0">
                                                    <div class="form-group m-0">
                                                        <input type="year" class="form-control pl-3" id="onlyYear"
                                                            value="{{ request('tahun_kegiatan') }}"
                                                            name="tahun_kegiatan" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                </div>
                                                <div class="col-md-9 text-left pl-0">
                                                    <button type="submit" class="btn btn-sm btn-rose">Filter</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @if (request('sumber_dana'))
                                <div class="row py-2 align-items-center bg-light">
                                    <div class="col-md-3 col-sm-6 text-md-right pt-3">
                                        <h5 class="fw-400">TOTAL SUMBER DANA :</h5>
                                    </div>
                                    <div class="col-md-9 col-sm-7 pl-md-0">
                                        <h5 class="fw-500 pt-3">
                                            {{ 'Rp ' . number_format($pkm->sum('jumlah_dana'), 2, ',', '.') }}
                                        </h5>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="material-datatables">
                            <table id="datatables-pkm" class="table table-striped table-no-bordered table-hover"
                                cellspacing="0" width="100%" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Nama Kegiatan</th>
                                        <th>Pengusul</th>
                                        <th>Fakultas</th>
                                        <th>Sumber Dana</th>
                                        <th>Jumlah Dana</th>
                                        <th>Tahun</th>
                                        <th class="disabled-sorting text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Nama Kegiatan</th>
                                        <th>Pengusul</th>
                                        <th>Fakultas</th>
                                        <th>Sumber Dana</th>
                                        <th>Jumlah Dana</th>
                                        <th>Tahun</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach ($pkm as $p)
                                        <tr>
                                            <td>{{ $p->judul_kegiatan }}</td>
                                            <td>{{ $p->user->dosen->nama }} </td>
                                            <td>{{ $p->prodi->faculty->nama_faculty }}</td>
                                            <td>{{ $p->sumberDana->sumber }}</td>
                                            <td>{{ 'Rp ' . number_format($p->jumlah_dana, 2, ',', '.') }}</td>
                                            <td>{{ $p->tanggal_kegiatan->format('Y') }}</td>
                                            <td class="text-center">
                                                <!-- <a href="#" class="btn btn-link btn-info btn-just-icon like"><i class="material-icons">read_more</i></a> -->
                                                <a href="{{ asset('storage/' . $p->path_kegiatan) }}" target="_blank"
                                                    class="btn btn-link btn-success btn-just-icon edit">
                                                    <i class="material-icons">file_download</i></a>
                                                <button type="button" class="btn btn-link btn-warning btn-just-icon edit"
                                                    data-toggle="modal" data-target="#ubahKegiatan{{ $p->id }}">
                                                    <i class="material-icons">mode_edit</i></a>
                                                </button>
                                                <!-- Ubah data menggunakan Modal -->
                                                <div class="modal fade" id="ubahKegiatan{{ $p->id }}"
                                                    tabindex="-1" role="dialog"
                                                    aria-labelledby="ubahKegiatan{{ $p->id }}" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Form Ubah
                                                                    Data Kegiatan Penelitian</h5>
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form class="form" id="EditKegiatanValidation"
                                                                action="{{ route('kegiatan.update', $p->id) }}"
                                                                method="POST" enctype="multipart/form-data">
                                                                <div class="modal-body">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <input type="hidden" name="jenis_kegiatan" value="2">
                                                                    <input type="hidden" name="path_kegiatan"
                                                                        value="{{ $p->path_kegiatan }}">
                                                                    <div class="form-group">
                                                                        <label for="judul" class="bmd-label-floating">Nama
                                                                            Kegiatan</label>
                                                                        <input type="text" class="form-control" id="judul"
                                                                            name="judul"
                                                                            value="{{ old('judul', $p->judul_kegiatan) }}"
                                                                            required>
                                                                    </div>
                                                                    @error('judul')
                                                                        <span id="category_id-error" class="error text-danger"
                                                                            for="input-id"
                                                                            style="display: block;">{{ $message }}</span>
                                                                    @enderror

                                                                    <div class="form-group pt-2">
                                                                        <label for="dana"
                                                                            class="bmd-label-floating pt-2">Jumlah
                                                                            Dana</label>
                                                                        <input type="text" class="form-control dana"
                                                                            id="dana" name="dana"
                                                                            value="{{ old('dana', $p->jumlah_dana) }}"
                                                                            required>
                                                                    </div>
                                                                    @error('dana')
                                                                        <span id="category_id-error" class="error text-danger"
                                                                            for="input-id"
                                                                            style="display: block;">{{ $message }}</span>
                                                                    @enderror

                                                                    <div class="form-group mt-3">
                                                                        <label for="tanggal_kegiatan"
                                                                            class="bmd-label-floating">Tanggal
                                                                            Kegiatan</label>
                                                                        <input type="date" class="form-control"
                                                                            id="tanggal_kegiatan" name="tanggal_kegiatan"
                                                                            value="{{ old('tanggal_kegiatan', $p->tanggal_kegiatan->format('Y-m-d')) }}"
                                                                            required>
                                                                    </div>
                                                                    @error('tanggal_kegiatan')
                                                                        <span id="category_id-error" class="error text-danger"
                                                                            for="input-id"
                                                                            style="display: block;">{{ $message }}</span>
                                                                    @enderror

                                                                    <div class="form-group text-left">
                                                                        <label for="sumberDana">Sumber Dana</label>
                                                                        <select class="form-control selectpicker"
                                                                            data-style="btn btn-link" id="sumberDana"
                                                                            name="sumberDana" required>
                                                                            @foreach ($sumberDana as $SD)
                                                                                <option value="{{ $SD->id }}"
                                                                                    {{ $p->sumber_id == $SD->id ? 'Selected' : '' }}>
                                                                                    {{ $SD->sumber }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    @error('sumberDana')
                                                                        <span id="category_id-error" class="error text-danger"
                                                                            for="input-id"
                                                                            style="display: block;">{{ $message }}</span>
                                                                    @enderror

                                                                    <div
                                                                        class="form-group form-file-upload form-file-multiple">
                                                                        <input type="file" name="path_kegiatan"
                                                                            class="inputFileHidden">
                                                                        <div class="input-group">
                                                                            <input type="text"
                                                                                class="form-control inputFileVisible"
                                                                                placeholder="Single File">
                                                                            <span class="input-group-btn">
                                                                                <button type="button"
                                                                                    class="btn btn-fab btn-round btn-primary">
                                                                                    <i
                                                                                        class="material-icons">attach_file</i>
                                                                                </button>
                                                                            </span>
                                                                        </div>
                                                                        <small class="form-text text-muted text-left"><cite>Maksimal
                                                                                2Mb dengan format file
                                                                                .pdf</cite></small>
                                                                    </div>
                                                                    @error('path_kegiatan')
                                                                        <span id="category_id-error" class="error text-danger"
                                                                            for="input-id"
                                                                            style="display: block;">{{ $message }}</span>
                                                                    @enderror

                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Batal</button>
                                                                    <button type="submit"
                                                                        class="btn btn-rose">Simpan</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- <a href="" class="btn btn-link btn-danger btn-just-icon remove">
                                            <form class="form" action="{{ route('kegiatan.destroy', $p->id) }}"
                                                method="POST" id="DeteleKegiatan<alidation">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-link btn-danger btn-just-icon remove"
                                                    onclick="return confirm('Anda Yakin Menghapus Data ini?');">
                                                    <i class="material-icons">close</i>
                                                </button>
                                            </form>
                                        </a> --}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- end content-->
                </div>
                <!--  end card  -->
            </div>
            <!-- end col-md-12 -->
        </div>
    </div>
@endsection

@section('modal')
    <!-- Tambah data menggunakan Modal -->
    <div class="modal fade" id="formKegiatan" tabindex="-1" role="dialog" aria-labelledby="formKegiatan"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Form Tambah Data Kegiatan PKM</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form" id="AddKegiatan<alidation" action="{{ route('kegiatan.store') }}"
                    method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="jenis_kegiatan" value="2">
                        <div class="form-group">
                            <label for="judul" class="bmd-label-floating">Nama Kegiatan</label>
                            <input type="text" class="form-control" id="judul" name="judul" value="{{ old('judul') }}"
                                required>
                        </div>
                        @error('judul')
                            <span id="category_id-error" class="error text-danger" for="input-id"
                                style="display: block;">{{ $message }}</span>
                        @enderror

                        <div class="form-group">
                            <label for="dana" class="bmd-label-floating">Jumlah Dana</label>
                            <input type="text" class="form-control dana" id="dana" name="dana" value="{{ old('dana') }}"
                                required>
                        </div>
                        @error('dana')
                            <span id="category_id-error" class="error text-danger" for="input-id"
                                style="display: block;">{{ $message }}</span>
                        @enderror

                        <div class="form-group mt-3">
                            <label for="judul" class="bmd-label-floating">Tanggal Kegiatan</label>
                            <input type="text" class="form-control datepicker" id="tanggal_kegiatan"
                                name="tanggal_kegiatan" placeholder="Tanggal Kegiatan"
                                value="{{ now()->toDateString('Y-m-d') }}" value="{{ old('tanggal_kegiatan') }}"
                                required>
                        </div>
                        @error('tanggal_kegiatan')
                            <span id="category_id-error" class="error text-danger" for="input-id"
                                style="display: block;">{{ $message }}</span>
                        @enderror

                        <div class="form-group">
                            <label for="sumberDana">Sumber Dana</label>
                            <select class="form-control selectpicker" data-style="btn btn-link" id="sumberDana"
                                name="sumberDana" required>
                                @foreach ($sumberDana as $SD)
                                    <option value="{{ $SD->id }}" selected>
                                        {{ $SD->sumber }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('sumberDana')
                            <span id="category_id-error" class="error text-danger" for="input-id"
                                style="display: block;">{{ $message }}</span>
                        @enderror

                        <div class="form-group form-file-upload form-file-multiple">
                            <input type="file" name="path_kegiatan" class="inputFileHidden" required>
                            <div class="input-group">
                                <input type="text" class="form-control inputFileVisible" placeholder="Single File">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-fab btn-round btn-primary">
                                        <i class="material-icons">attach_file</i>
                                    </button>
                                </span>
                            </div>
                            <small class="form-text text-muted text-left"><cite>Maksimal 2Mb dengan format file
                                    .pdf</cite></small>
                        </div>
                        @error('path_kegiatan')
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
@endsection

@section('customSCript')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#datatables-pkm').DataTable({
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

            // Format mata uang.
            $('.dana').mask('000.000.000', {
                reverse: true
            });

        })
    </script>
@endsection
