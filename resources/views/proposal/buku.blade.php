@extends('layouts.main')
@section('content')
    .<div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-info card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">assignment</i>
                        </div>
                        <div class="row card-title">
                            <div class="col-6">
                                <h4 class="fw-400">Daftar Buku</h4>
                            </div>
                            <div class="col-6 text-right">
                                <button type="button" class="btn btn-secondary text-rose mt-0" data-toggle="modal"
                                    data-target="#formBuku">
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
                            <table id="datatables-buku" class="table table-striped table-no-bordered table-hover"
                                cellspacing="0" width="100%" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Judul Proposal</th>
                                        <th>Pengusul</th>
                                        <th>Judul Buku</th>
                                        <th>Penerbit Buku</th>
                                        <th>Nomor ISBN</th>
                                        <th>File Buku</th>
                                        <th class="disabled-sorting text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Judul Proposal</th>
                                        <th>Pengusul</th>
                                        <th>Judul Buku</th>
                                        <th>Penerbit Buku</th>
                                        <th>Nomor ISBN</th>
                                        <th>File Buku</th>
                                        <th class="text-right">Actions</th>
                                    </tr>
                                </tfoot>

                                <tbody>
                                    @foreach ($buku as $b)
                                        <tr>
                                            <td>{{ $b->proposal->judul }}</td>
                                            <td>
                                                @foreach ($b->proposal->dosen as $pvt)
                                                    @if ($pvt->pivot->isLeader == true)
                                                        {{ $pvt->nama }}
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>{{ $b->judul_buku }}</td>
                                            <td>{{ $b->penerbit }}</td>
                                            <td>{{ $b->isbn }}</td>
                                            <td><a href="{{ asset('storage/' . $b->path_buku) }}" target="_blank"
                                                    class="badge badge-success">download</a>
                                            </td>
                                            <td class="text-right">
                                                <!-- <a href="#" class="btn btn-link btn-info btn-just-icon like"><i class="material-icons">read_more</i></a> -->
                                                <button type="button" class="btn btn-link btn-warning btn-just-icon edit"
                                                    data-toggle="modal" data-target="#ubahBuku{{ $b->id }}">
                                                    <i class="material-icons">mode_edit</i></a>
                                                </button>
                                                <!-- Ubah data menggunakan Modal -->
                                                <div class="modal fade" id="ubahBuku{{ $b->id }}" tabindex="-1"
                                                    role="dialog" aria-labelledby="ubahBuku{{ $b->id }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Form Ubah
                                                                    Data Buku</h5>
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form class="form" id="EditBukuValidation"
                                                                action="{{ route('buku.update', $b->id) }}" method="POST"
                                                                enctype="multipart/form-data">
                                                                <div class="modal-body">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <input type="hidden" name="path_buku"
                                                                        value="{{ $b->path_buku }}">
                                                                    <input type="hidden" name="tanggal_upload"
                                                                        value="{{ now()->toDateString('Y-m-d') }}">
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
                                                                                value="{{ old('tanggal_upload', $b->tanggal_upload) }}"
                                                                                required>
                                                                        </div>
                                                                        @error('tanggal_upload')
                                                                            <span id="category_id-error"
                                                                                class="error text-danger" for="input-id"
                                                                                style="display: block;">{{ $message }}</span>
                                                                        @enderror
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
                                                                        <label for="judul"
                                                                            class="bmd-label-floating pt-2">Judul
                                                                            Buku</label>
                                                                        <input type="text" class="form-control" id="judul"
                                                                            name="judul"
                                                                            value="{{ old('judul', $b->judul_buku) }}"
                                                                            required>
                                                                    </div>
                                                                    @error('judul')
                                                                        <span id="category_id-error" class="error text-danger"
                                                                            for="input-id"
                                                                            style="display: block;">{{ $message }}</span>
                                                                    @enderror

                                                                    <div class="form-group text-left">
                                                                        <label for="isbn" class="bmd-label-floating">Nomor
                                                                            ISBN</label>
                                                                        <input type="text" class="form-control" id="isbn"
                                                                            name="isbn"
                                                                            value="{{ old('isbn', $b->isbn) }}">
                                                                    </div>
                                                                    @error('isbn')
                                                                        <span id="category_id-error" class="error text-danger"
                                                                            for="input-id"
                                                                            style="display: block;">{{ $message }}</span>
                                                                    @enderror

                                                                    <div class="form-group pt-2">
                                                                        <label for="penerbit"
                                                                            class="bmd-label-floating pt-1">Penerbit
                                                                            Buku</label>
                                                                        <input type="text" class="form-control"
                                                                            id="penerbit" name="penerbit"
                                                                            value="{{ old('penerbit', $b->penerbit) }}"
                                                                            required>
                                                                    </div>
                                                                    @error('penerbit')
                                                                        <span id="category_id-error" class="error text-danger"
                                                                            for="input-id"
                                                                            style="display: block;">{{ $message }}</span>
                                                                    @enderror

                                                                    <div
                                                                        class="form-group form-file-upload form-file-multiple">
                                                                        <input type="file" name="path_buku"
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
                                                                    @error('path_buku')
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
                                            <form class="form" action="{{ route('buku.destroy', $b->id) }}"
                                                method="POST" id="DeteleBukuValidation">
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
    <div class="modal fade" id="formBuku" tabindex="-1" role="dialog" aria-labelledby="formBuku" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Form Tambah Data Buku</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form" id="AddBukuValidation" action="{{ route('buku.store') }}" method="POST"
                    enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="tanggal_upload" value="{{ now()->toDateString('Y-m-d') }}">
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
                            <label for="judul" class="bmd-label-floating">Judul Buku</label>
                            <input type="text" class="form-control" id="judul" name="judul" value="{{ old('judul') }}">
                        </div>
                        @error('judul')
                            <span id="category_id-error" class="error text-danger" for="input-id"
                                style="display: block;">{{ $message }}</span>
                        @enderror

                        <div class="form-group">
                            <label for="isbn" class="bmd-label-floating">Nomor ISBN</label>
                            <input type="text" class="form-control" id="isbn" name="isbn" value="{{ old('isbn') }}">
                        </div>
                        @error('isbn')
                            <span id="category_id-error" class="error text-danger" for="input-id"
                                style="display: block;">{{ $message }}</span>
                        @enderror

                        <div class="form-group">
                            <label for="penerbit" class="bmd-label-floating">Penerbit Buku</label>
                            <input type="text" class="form-control" id="penerbit" name="penerbit"
                                value="{{ old('penerbit') }}" required>
                        </div>
                        @error('penerbit')
                            <span id="category_id-error" class="error text-danger" for="input-id"
                                style="display: block;">{{ $message }}</span>
                        @enderror

                        <div class="form-group form-file-upload form-file-multiple">
                            <input type="file" name="path_buku" class="inputFileHidden" required>
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
                        @error('path_buku')
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
            //datatables
            $('#datatables-buku').DataTable({
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
