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
                                <h4 class="fw-400">Daftar HKI</h4>
                            </div>
                            <div class="col-6 text-right">
                                <button type="button" class="btn btn-secondary text-rose mt-0" data-toggle="modal"
                                    data-target="#formHaki">
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
                            <table id="datatables-akhir" class="table table-striped table-no-bordered table-hover"
                                cellspacing="0" width="100%" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Judul Proposal</th>
                                        <th>Pengusul</th>
                                        <th>Jenis HKI</th>
                                        <th>File HKI</th>
                                        <th class="disabled-sorting text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Judul Proposal</th>
                                        <th>Pengusul</th>
                                        <th>Jenis HKI</th>
                                        <th>File HKI</th>
                                        <th class="text-right">Actions</th>
                                    </tr>
                                </tfoot>

                                <tbody>

                                    @foreach ($Hki as $h)
                                        <tr>
                                            <td>{{ $h->proposal->judul }}</td>
                                            <td>
                                                @foreach ($h->proposal->dosen as $pvt)
                                                    @if ($pvt->pivot->isLeader == true)
                                                        {{ $pvt->nama }}
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>{{ $h->jenis_hki->hki }}</td>
                                            <td><a href="{{ asset('storage/' . $h->path_hki) }}" target="_blank"
                                                    class="badge badge-success">{{ substr($h->path_hki, 9) }}</a>
                                            </td>
                                            <td class="text-right">
                                                <!-- <a href="#" class="btn btn-link btn-info btn-just-icon like"><i class="material-icons">read_more</i></a> -->
                                                <button type="button" class="btn btn-link btn-warning btn-just-icon edit"
                                                    data-toggle="modal" data-target="#ubahHaki{{ $h->id }}">
                                                    <i class="material-icons">mode_edit</i></a>
                                                </button>
                                                <!-- Ubah data menggunakan Modal -->
                                                <div class="modal fade" id="ubahHaki{{ $h->id }}" tabindex="-1"
                                                    role="dialog" aria-labelledby="ubahHaki{{ $h->id }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Form Ubah
                                                                    Data HKI</h5>
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form class="form" id="EditHakiValidation"
                                                                action="{{ route('hki.update', $h->id) }}" method="POST"
                                                                enctype="multipart/form-data">
                                                                <div class="modal-body">
                                                                    @csrf
                                                                    @method('PUT')
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
                                                                                value="{{ old('tanggal_upload') }}"
                                                                                required>
                                                                        </div>
                                                                        @error('tanggal_upload')
                                                                            <span id="category_id-error"
                                                                                class="error text-danger" for="input-id"
                                                                                style="display: block;">{{ $message }}</span>
                                                                        @enderror
                                                                    @endif
                                                                    <input type="hidden" name="path_hki"
                                                                        value="{{ $h->path_hki }}">
                                                                    <div class="form-group text-left">
                                                                        <label for="proposal_id ">Judul Proposal</label>
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
                                                                    <div class="form-group text-left">
                                                                        <label for="jenis">Jenis HKI</label>
                                                                        <select class="form-control selectpicker"
                                                                            data-style="btn btn-link" id="jenis"
                                                                            name="jenis"
                                                                            data-style="btn btn-primary btn-round" required>
                                                                            @foreach ($jenisHki as $j)
                                                                                <option value="{{ $j->id }}"
                                                                                    selected>
                                                                                    {{ $j->hki }}
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
                                                                        <input type="file" name="path_hki"
                                                                            class="inputFileHidden">
                                                                        <div class="input-group">
                                                                            <input type="text" class="form-control"
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
                                                                    @error('path_hki')
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
                                                    <form class="form"
                                                        action="{{ route('hki.destroy', $h->id) }}" method="POST"
                                                        id="DeteleHakiValidation">
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
    <!-- Modal Haki -->
    <!-- Tambah data menggunakan Modal -->
    <div class="modal fade" id="formHaki" tabindex="-1" role="dialog" aria-labelledby="formHaki" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Form Tambah Data HKI</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form" id="AddHakiValidation" action="{{ route('hki.store') }}" method="POST"
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
                            <label for="jenis">Jenis HKI</label>
                            <select class="form-control selectpicker" data-style="btn btn-link" id="jenis" name="jenis"
                                data-style="btn btn-primary btn-round" required>
                                @foreach ($jenisHki as $j)
                                    <option value="{{ $j->id }}" selected>
                                        {{ $j->hki }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('jenis')
                            <span id="category_id-error" class="error text-danger" for="input-id"
                                style="display: block;">{{ $message }}</span>
                        @enderror
                        <div class="form-group form-file-upload form-file-multiple">
                            <input type="file" name="path_hki" class="inputFileHidden" required>
                            <div class="input-group">
                                <input type="text" class="form-control inputFileVisible" placeholder="Single File">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-fab btn-round btn-primary">
                                        <i class="material-icons">attach_file</i>
                                    </button>
                                </span>
                            </div>
                        </div>
                        @error('path_hki')
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
