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
                                <h4 class="fw-400">Daftar Publikasi</h4>
                            </div>
                            <div class="col-6 text-right">
                                <button type="button" class="btn btn-secondary text-rose mt-0" data-toggle="modal"
                                    data-target="#formPublikasi">
                                    <span class="material-icons">add</span> Laporan baru
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="toolbar">
                            <!--        Here you can write extra buttons/actions for the toolbar              -->
                        </div>
                        <div class="material-datatables">
                            <table id="datatables-publikasi" class="table table-striped table-no-bordered table-hover"
                                cellspacing="0" width="100%" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Judul Proposal</th>
                                        <th>Pengusul</th>
                                        <th>Judul Artikel</th>
                                        <th>Nama Jurnal</th>
                                        <th>Jenis</th>
                                        <th>File</th>
                                        <th class="disabled-sorting text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Judul Proposal</th>
                                        <th>Pengusul</th>
                                        <th>Judul Artikel</th>
                                        <th>Nama Jurnal</th>
                                        <th>Jenis</th>
                                        <th>File</th>
                                        <th class="text-right">Actions</th>
                                    </tr>
                                </tfoot>

                                <tbody>
                                    @foreach ($publikasi as $publik)
                                        <tr>
                                            <td>{{ $publik->proposal->judul }}</td>
                                            <td>
                                                @foreach ($publik->proposal->dosen as $pvt)
                                                    @if ($pvt->pivot->isLeader == true)
                                                        {{ $pvt->nama }}
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>{{ $publik->judul_artikel }}</td>
                                            <td>{{ $publik->nama_jurnal }}</td>
                                            <td>{{ $publik->jenis_jurnal->jurnal }}</td>
                                            <td><a href="{{ asset('storage/' . $publik->path_jurnal) }}" target="_blank"
                                                    class="badge badge-success">{{ substr($publik->path_jurnal, 9) }}</a>
                                            </td>
                                            <td class="text-right">
                                                <button type="button" class="btn btn-link btn-warning btn-just-icon edit"
                                                    data-toggle="modal" data-target="#updatePublikasi{{ $publik->id }}">
                                                    <i class="material-icons">mode_edit</i>
                                                </button>
                                                <div class="modal fade" id="updatePublikasi{{ $publik->id }}"
                                                    tabindex="-1" role="dialog"
                                                    aria-labelledby="updatePublikasi{{ $publik->id }}" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Form Ubah
                                                                    Data Publikasi</h5>
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form class="form" id="EditPublikasiValidation"
                                                                    action="{{ route('publikasi.update', $publik->id) }}"
                                                                    method="POST" enctype="multipart/form-data">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <input type="hidden" name="path_jurnal"
                                                                        value="{{ $publik->path_jurnal }}">
                                                                    <input type="hidden" name="tanggal_upload"
                                                                        value="{{ now()->toDateString('Y-m-d') }}">
                                                                    @if (Auth::user()->role_id == 2)
                                                                        <input type="hidden" name="nidn_pengusul"
                                                                            value="{{ Auth::user()->nidn }}">
                                                                    @endif
                                                                    <div class="form-group text-left">
                                                                        <label for="proposal_id">Judul Proposal</label>
                                                                        <select class="form-control selectpicker"
                                                                            data-style="btn btn-link" id="proposal_id"
                                                                            name="proposal_id" required>
                                                                            @foreach ($proposal as $pps)
                                                                                <option value="{{ $pps->id }}"
                                                                                    selected>
                                                                                    {{ $pps->judul }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    @error('proposal_id')
                                                                        <span id="category_id-error" class="error text-danger"
                                                                            for="input-id"
                                                                            style="display: block;">{{ $message }}</span>
                                                                    @enderror

                                                                    <div class="form-group pt-2">
                                                                        <label for="judul">Judul Jurnal</label>
                                                                        <input type="text" class="form-control" id="judul"
                                                                            name="judul"
                                                                            value="{{ old('judul', $publik->judul_jurnal) }}"
                                                                            required>
                                                                    </div>
                                                                    @error('judul')
                                                                        <span id="category_id-error" class="error text-danger"
                                                                            for="input-id"
                                                                            style="display: block;">{{ $message }}</span>
                                                                    @enderror

                                                                    @if (Auth::user()->role_id == 1)
                                                                        <div class="form-group mt-3">
                                                                            <label for="judul"
                                                                                class="bmd-label-floating">Tanggal
                                                                                Upload</label>
                                                                            <input type="text"
                                                                                class="form-control datepicker"
                                                                                id="tanggal_upload" name="tanggal_upload"
                                                                                placeholder="Tanggal Pengusulan"
                                                                                value="{{ now()->toDateString('Y-m-d') }}"
                                                                                value="{{ old('tanggal_upload') }}"
                                                                                required>
                                                                        </div>
                                                                        @error('tanggal_upload')
                                                                            <span id="category_id-error"
                                                                                class="error text-danger" for="input-id"
                                                                                style="display: block;">{{ $message }}</span>
                                                                        @enderror
                                                                    @endif
                                                                    <!-- Dynamic Form Js -->
                                                                    <div class="form-group pt-2" id="dynamic_form">
                                                                        <div class="input-group baru-data">
                                                                            <label for="nama"
                                                                                class="bmd-label-floating">Nama
                                                                                Artikel</label>
                                                                            <input type="text" class="form-control"
                                                                                id="nama" name="nama"
                                                                                value="{{ old('nama', $publik->nama_artikel) }}"
                                                                                required>
                                                                        </div>
                                                                    </div>
                                                                    @error('nama')
                                                                        <span id="category_id-error" class="error text-danger"
                                                                            for="input-id"
                                                                            style="display: block;">{{ $message }}</span>
                                                                    @enderror
                                                                    <div class="form-group text-left">
                                                                        <label for="jenis">Jenis Publikasi</label>
                                                                        <select class="form-control selectpicker"
                                                                            data-style="btn btn-link" id="jenis"
                                                                            name="jenis"
                                                                            data-style="btn btn-primary btn-round" required>
                                                                            @foreach ($jj as $j)
                                                                                <option value="{{ $j->id }}">
                                                                                    {{ $j->jurnal }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    @error('jenis')
                                                                        <span id="category_id-error" class="error text-danger"
                                                                            for="input-id"
                                                                            style="display: block;">{{ $message }}</span>
                                                                    @enderror

                                                                    <div
                                                                        class="form-group form-file-upload form-file-multiple">
                                                                        <input type="file" name="path_publikasi"
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
                                                                    </div>
                                                                    @error('path_publikasi')
                                                                        <span id="category_id-error" class="error text-danger"
                                                                            for="input-id"
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
                                                {{-- <a href="" class="btn btn-link btn-danger btn-just-icon remove">
                                                    <form class="form"
                                                        action="{{ route('publikasi.destroy', $publik->id) }}"
                                                        method="POST" id="DetelePublikasiValidation">
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
    <!-- Modal Publikasi -->
    <div class="modal fade" id="formPublikasi" tabindex="-1" role="dialog" aria-labelledby="formPublikasi"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Form Tambah Data Publikasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form" id="AddPublikasiValidation" action="{{ route('publikasi.store') }}"
                    method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="tanggal_upload" value="{{ now()->toDateString('Y-m-d') }}">
                        @if (Auth::user()->role_id == 2)
                            <input type="hidden" name="nidn_pengusul" value="{{ Auth::user()->nidn }}">
                        @endif
                        <div class="form-group">
                            <label for="proposal_id">Judul Proposal</label>
                            <select class="form-control selectpicker" data-style="btn btn-link" id="proposal_id"
                                name="proposal_id" required>
                                @foreach ($proposal as $pps)
                                    <option value="{{ $pps->id }}" selected>
                                        {{ $pps->judul }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('proposal_id')
                            <span id="category_id-error" class="error text-danger" for="input-id"
                                style="display: block;">{{ $message }}</span>
                        @enderror
                        <div class="form-group">
                            <label for="judul" class="bmd-label-floating">Judul Artikel</label>
                            <input type="text" class="form-control" id="judul" name="judul" value="{{ old('judul') }}"
                                required>
                        </div>
                        @error('judul')
                            <span id="category_id-error" class="error text-danger" for="input-id"
                                style="display: block;">{{ $message }}</span>
                        @enderror

                        <!-- Dynamic Form Js -->
                        <div class="form-group" id="dynamic_form">
                            <div class="input-group baru-data">
                                <label for="nama" class="bmd-label-floating">Nama Jurnal</label>
                                <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama') }}"
                                    required>
                            </div>
                        </div>
                        @error('nama')
                            <span id="category_id-error" class="error text-danger" for="input-id"
                                style="display: block;">{{ $message }}</span>
                        @enderror
                        <div class="form-group">
                            <label for="jenis">Jenis Publikasi</label>
                            <select class="form-control selectpicker" data-style="btn btn-link" id="jenis" name="jenis"
                                data-style="btn btn-primary btn-round" required>
                                <option selected>{{ old('jenis') }}</option>
                                @foreach ($jj as $j)
                                    <option value="{{ $j->id }}">
                                        {{ $j->jurnal }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('jenis')
                            <span id="category_id-error" class="error text-danger" for="input-id"
                                style="display: block;">{{ $message }}</span>
                        @enderror
                        <div class="form-group form-file-upload form-file-multiple">
                            <input type="file" name="path_publikasi" class="inputFileHidden" required>
                            <div class="input-group">
                                <input type="text" class="form-control inputFileVisible" placeholder="Single File">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-fab btn-round btn-primary">
                                        <i class="material-icons">attach_file</i>
                                    </button>
                                </span>
                            </div>
                        </div>
                        @if (Auth::user()->role_id == 1)
                            <div class="form-group mt-3">
                                <label for="judul" class="bmd-label-floating">Tanggal Upload</label>
                                <input type="text" class="form-control datepicker" id="tanggal_upload" name="tanggal_upload"
                                    placeholder="Tanggal Pengusulan" value="{{ now()->toDateString('Y-m-d') }}"
                                    value="{{ old('tanggal_upload') }}" required>
                            </div>
                            @error('tanggal_upload')
                                <span id="category_id-error" class="error text-danger" for="input-id"
                                    style="display: block;">{{ $message }}</span>
                            @enderror
                        @endif
                        @error('path_publikasi')
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
    <script>
        $(document).ready(function() {
            $('#datatables-publikasi').DataTable({
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
