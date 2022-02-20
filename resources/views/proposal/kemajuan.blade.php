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
                                <h4 class="fw-400">Laporan Kemajuan</h4>
                            </div>
                            <div class="col-md-6 text-right">
                                <button type="button" class="btn btn-secondary text-rose mt-0" data-toggle="modal"
                                    data-target="#formKemajuan">
                                    <span class="material-icons">add</span> Laporan Baru
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="toolbar">
                            <!--        Here you can write extra buttons/actions for the toolbar              -->
                        </div>
                        <div class="material-datatables">
                            <table id="datatables-kemajuan" class="table table-striped table-no-bordered table-hover"
                                cellspacing="0" width="100%" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Judul Proposal</th>
                                        <th>Pengusul</th>
                                        <th>Fakultas</th>
                                        <th>tanggal Upload</th>
                                        <th>Berkas Laporan</th>
                                        <th class="disabled-sorting text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Judul Proposal</th>
                                        <th>Pengusul</th>
                                        <th>Fakultas</th>
                                        <th>tanggal Upload</th>
                                        <th>Berkas Laporan</th>
                                        <th class="text-right">Actions</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach ($kemajuans as $lap)
                                        <tr>
                                            <td>{{ $lap->proposal->judul }}</td>
                                            @foreach ($lap->proposal->dosen as $dsn)
                                                @if ($dsn->pivot->isLeader == true)
                                                    <td>{{ $dsn->nama }}</td>
                                                    <td>{{ $dsn->prodi->faculty->nama_faculty }}</td>
                                                @endif
                                            @endforeach
                                            <td>{{ $lap->tanggal_upload }}</td>
                                            <td><a href="{{ asset('storage/' . $lap->path_kemajuan) }}" target="_blank"
                                                    class="badge badge-success">{{ substr($lap->path_kemajuan, 17) }}</a>
                                            </td>
                                            <td class="text-right">
                                                @if (Auth::user()->role_id == 1)
                                                    <a href="{{ route('laporan-kemajuan.show', $lap->id) }}"
                                                        class="btn btn-link btn-info btn-just-icon like"><i
                                                            class="material-icons">read_more</i></a>
                                                @endif
                                                <button type="button" class="btn btn-link btn-warning btn-just-icon edit"
                                                    data-toggle="modal" data-target="#EditLapKemajuan{{ $lap->id }}"><i
                                                        class="material-icons">mode_edit</i></button>
                                                <!-- Modal Edit Laporan Kemajuan -->
                                                <div class="modal fade" id="EditLapKemajuan{{ $lap->id }}"
                                                    tabindex="-1" role="dialog" aria-labelledby="formProposal"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Edit
                                                                    Laporan Kemajuan</h5>
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form class="form" id="EditKemajuanValidation"
                                                                action="{{ route('laporan-kemajuan.update', $lap->id) }}"
                                                                method="POST" enctype="multipart/form-data">
                                                                <div class="modal-body">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="form-group">
                                                                        <select class="form-control selectpicker"
                                                                            data-style="btn btn-link" id="proposal_id"
                                                                            name="proposal_id" required>
                                                                            <option value="{{ $lap->proposal_id }}">
                                                                                {{ $lap->proposal->judul }}
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
                                                                                value="{{ old('tanggal_upload', $lap->tanggal_upload) }}"
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
                                                                        <input type="file" name="path_kemajuan"
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
                                                                    @error('path_kemajuan')
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
    <div class="modal fade" id="formKemajuan" tabindex="-1" role="dialog" aria-labelledby="formProposal"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Form Laporan Kemajuan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form" id="AddKemajuanValidation" action="{{ route('laporan-kemajuan.store') }}"
                    method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        {{-- <span class="form-group bmd-form-group email-error ">
                            @if ($errors->any())
                                @foreach ($errors->all() as $e)
                                    <p class="
                                description text-center text-danger">
                                        {{ $e }}</p>
                                @endforeach
                            @endif
                        </span> --}}
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
                            <input type="file" name="path_kemajuan" class="inputFileHidden" required>
                            <div class="input-group">
                                <input type="text" class="form-control inputFileVisible" placeholder="Single File">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-fab btn-round btn-primary">
                                        <i class="material-icons">attach_file</i>
                                    </button>
                                </span>
                            </div>
                        </div>
                        @error('path_kemajuan')
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
            $('#datatables-kemajuan').DataTable({
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
