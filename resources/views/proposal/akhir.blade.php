@extends('layouts.main')
@section('content')
    .<div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header card-header-info card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">assignment</i>
                        </div>
                        <div class="row card-title">
                            <div class="col-md-6">
                                <h4 class="fw-400">Laporan Akhir</h4>
                            </div>
                            @can('upload_laporan_akhir')
                                <div class="col-md-6 text-right">
                                    <button type="button" class="btn btn-rose btn-round mt-0" data-toggle="modal"
                                        data-target="#formAkhir">
                                        <span class="material-icons">add</span> Laporan Baru
                                    </button>
                                </div>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="toolbar">
                            <!--        Here you can write extra buttons/actions for the toolbar              -->
                        </div>
                        <div class="material-datatables">
                            <table id="datatables-akhir" class="table table-striped table-no-bordered table-hover"
                                cellspacing="0" width="100%" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Judul Proposal</th>
                                        <th>Pengusul</th>
                                        <th>Fakultas</th>
                                        <th>tanggal Upload</th>
                                        <th>File Laporan Akhir</th>
                                        <th>File Laporan Keuangan</th>
                                        <th class="disabled-sorting text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Judul Proposal</th>
                                        <th>Pengusul</th>
                                        <th>Fakultas</th>
                                        <th>tanggal Upload</th>
                                        <th>File Laporan Akhir</th>
                                        <th>File Laporan Keuangan</th>
                                        <th class="text-right">Actions</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach ($akhirs as $akhr)
                                        <tr>
                                            <td>{{ $akhr->proposal->judul }}</td>
                                            @foreach ($akhr->proposal->dosen as $dsn)
                                                @if ($dsn->pivot->isLeader == true)
                                                    <td>{{ $dsn->nama }}</td>
                                                    <td>{{ $dsn->prodi->faculty->nama_faculty }}</td>
                                                @endif
                                            @endforeach
                                            <td>{{ $akhr->tanggal_upload }}</td>
                                            <td><a href="{{ asset('storage/' . $akhr->path_akhir) }}" target="_blank"
                                                    class="badge badge-success">download</a>
                                            </td>
                                            <td><a href="{{ asset('storage/' . $akhr->path_keuangan) }}" target="_blank"
                                                    class="badge badge-success">download</a>
                                            </td>
                                            <td class="text-right">
                                                @can('upload_laporan_akhir')
                                                    <button type="button" class="btn btn-link btn-warning btn-just-icon edit"
                                                        data-toggle="modal" data-target="#EditLapKemajuan{{ $akhr->id }}"><i
                                                            class="material-icons">mode_edit</i></button>
                                                    <!-- Modal Edit Laporan Kemajuan -->
                                                    <div class="modal fade" id="EditLapKemajuan{{ $akhr->id }}"
                                                        tabindex="-1" role="dialog" aria-labelledby="formProposal"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-lg" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Edit
                                                                        Laporan Akhir</h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <form class="form" id="EditAkhirValidation"
                                                                    action="{{ route('laporan-akhir.update', $akhr->id) }}"
                                                                    method="POST" enctype="multipart/form-data">
                                                                    <div class="modal-body">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <div class="form-group">
                                                                            <select class="form-control selectpicker"
                                                                                data-style="btn btn-link" id="proposal_id"
                                                                                name="proposal_id" required>
                                                                                <option value="{{ $akhr->proposal_id }}">
                                                                                    {{ $akhr->proposal->judul }}
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                        @error('proposal_id')
                                                                            <span id="category_id-error" class="error text-danger"
                                                                                for="input-id"
                                                                                style="display: block;">{{ $message }}</span>
                                                                        @enderror
                                                                        @if (Auth::user()->role_id == 1)
                                                                            <div class="form-group mt-3">
                                                                                <input type="date" class="form-control"
                                                                                    id="tanggal_upload" name="tanggal_upload"
                                                                                    placeholder="Tanggal Upload"
                                                                                    value="{{ old('tanggal_upload', $akhr->tanggal_upload) }}"
                                                                                    required>
                                                                            </div>
                                                                            @error('tanggal_upload')
                                                                                <span id="category_id-error"
                                                                                    class="error text-danger" for="input-id"
                                                                                    style="display: block;">{{ $message }}</span>
                                                                            @enderror
                                                                        @endif
                                                                        <div
                                                                            class="form-group form-file-upload form-file-multiple">
                                                                            <input type="file" name="path_akhir"
                                                                                class="inputFileHidden">
                                                                            <div class="input-group">
                                                                                <input type="text"
                                                                                    class="form-control inputFileVisible"
                                                                                    placeholder="File Laporan Akhir">
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
                                                                        @error('path_akhir')
                                                                            <span id="category_id-error" class="error text-danger"
                                                                                for="input-id"
                                                                                style="display: block;">{{ $message }}</span>
                                                                        @enderror
                                                                        <div
                                                                            class="form-group form-file-upload form-file-multiple">
                                                                            <input type="file" name="path_keuangan"
                                                                                class="inputFileHidden">
                                                                            <div class="input-group">
                                                                                <input type="text"
                                                                                    class="form-control inputFileVisible"
                                                                                    placeholder="File Laporan Keuangan">
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
                                                                        @error('path_keuangan')
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
                                                @endcan
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
    <!-- Modal Tambah laporan kemajuan -->
    <div class="modal fade" id="formAkhir" tabindex="-1" role="dialog" aria-labelledby="formProposal"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Form Laporan Akhir</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form" id="AddAkhirValidation" action="{{ route('laporan-akhir.store') }}"
                    method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        @if (Auth::user()->role_id == 1)
                            <div class="form-group">
                                <label for="proposal_id">Pilih Proposal</label>
                                <select class="form-control selectpicker" data-style="btn btn-link" id="proposal_id"
                                    name="proposal_id" required>
                                    @foreach ($proposal as $pps)
                                        <option value="{{ $pps->id }}">
                                            {{ $pps->judul }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('proposal_id')
                                <span id="category_id-error" class="error text-danger" for="input-id"
                                    style="display: block;">{{ $message }}</span>
                            @enderror
                            <div class="form-group mt-3">
                                <input type="text" class="form-control datepicker" id="tanggal_upload" name="tanggal_upload"
                                    placeholder="Tanggal Upload" value="{{ old('tanggal_upload') }}" required>
                            </div>
                            @error('tanggal_upload')
                                <span id="category_id-error" class="error text-danger" for="input-id"
                                    style="display: block;">{{ $message }}</span>
                            @enderror
                        @endif
                        @if (Auth::user()->role_id == 2)
                            <input type="hidden" name="tanggal_upload" value="{{ now()->toDateString('Y-m-d') }}">
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
                        @endif
                        <div class="form-group form-file-upload form-file-multiple">
                            <input type="file" name="path_akhir" class="inputFileHidden" required>
                            <div class="input-group">
                                <input type="text" class="form-control inputFileVisible" placeholder="File Laporan Akhir">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-fab btn-round btn-primary">
                                        <i class="material-icons">attach_file</i>
                                    </button>
                                </span>
                            </div>
                            <small class="form-text text-muted text-left"><cite>Maksimal 2Mb dengan format file
                                    .pdf</cite></small>
                        </div>
                        @error('path_akhir')
                            <span id="category_id-error" class="error text-danger" for="input-id"
                                style="display: block;">{{ $message }}</span>
                        @enderror
                        <div class="form-group form-file-upload form-file-multiple">
                            <input type="file" name="path_keuangan" class="inputFileHidden" required>
                            <div class="input-group">
                                <input type="text" class="form-control inputFileVisible"
                                    placeholder="File Laporan Keuangan">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-fab btn-round btn-primary">
                                        <i class="material-icons">attach_file</i>
                                    </button>
                                </span>
                            </div>
                            <small class="form-text text-muted text-left"><cite>Maksimal 2Mb dengan format file
                                    .pdf</cite></small>
                        </div>
                        @error('path_keuangan')
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
            $('#datatables-akhir').DataTable({
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
