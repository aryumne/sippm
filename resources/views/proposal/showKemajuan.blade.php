@extends('layouts.main')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header card-header-text card-header-info">
                        <div class="card-text">
                            <h4 class="fw-400">Detail Laporan</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td>
                                            <p>Judul Proposal</p>
                                        </td>
                                        <td class="text-left">
                                            <h5>
                                                {{ $kemajuan->proposal->judul }}
                                            </h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>Tanggal Upload</p>
                                        </td>
                                        <td class="text-left">
                                            <h5>
                                                {{ $kemajuan->tanggal_upload }}
                                            </h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>File kemajuan</p>
                                        </td>
                                        <td class="text-left">
                                            <a href="{{ asset('storage/' . $kemajuan->path_kemajuan) }}" target="_blank"
                                                class="badge badge-success">{{ substr($kemajuan->path_kemajuan, 17) }}</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-right">
                            <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                                data-target="#EditLapKemajuan{{ $kemajuan->id }}">Edit</button>
                            <!-- Modal Edit Laporan Kemajuan -->
                            <div class="modal fade" id="EditLapKemajuan{{ $kemajuan->id }}" tabindex="-1"
                                role="dialog" aria-labelledby="formProposal" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Edit
                                                Laporan Kemajuan</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form class="form" id="EditKemajuanValidation"
                                            action="{{ route('laporan-kemajuan.update', $kemajuan->id) }}" method="POST"
                                            enctype="multipart/form-data">
                                            <div class="modal-body">
                                                @csrf
                                                @method('PUT')
                                                <div class="form-group">
                                                    <select class="form-control selectpicker" data-style="btn btn-link"
                                                        id="proposal_id" name="proposal_id" required>
                                                        <option value="{{ $kemajuan->proposal_id }}">
                                                            {{ $kemajuan->proposal->judul }}
                                                        </option>
                                                    </select>
                                                </div>
                                                @error('proposal_id')
                                                    <span id="category_id-error" class="error text-danger" for="input-id"
                                                        style="display: block;">{{ $message }}</span>
                                                @enderror
                                                @if (Auth::user()->role_id == 1)
                                                    <div class="form-group mt-3">
                                                        <input type="date" class="form-control" id="tanggal_upload"
                                                            name="tanggal_upload" placeholder="Tanggal Upload"
                                                            value="{{ old('tanggal_upload', $kemajuan->tanggal_upload) }}"
                                                            required>
                                                    </div>
                                                    @error('tanggal_upload')
                                                        <span id="category_id-error" class="error text-danger" for="input-id"
                                                            style="display: block;">{{ $message }}</span>
                                                    @enderror
                                                @endif
                                                <div class="form-group form-file-upload form-file-multiple">
                                                    <input type="file" name="path_kemajuan" class="inputFileHidden">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control inputFileVisible"
                                                            placeholder="Single File">
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
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-rose">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header card-header-text card-header-info">
                        <div class="card-icon">
                            <i class="material-icons">assignment</i>
                        </div>
                        <div class="row card-title">
                            <div class="col-md-6">
                                <h4 class="fw-400">Hasil Monitoring dan Evaluasi</h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        The place is close to Barceloneta Beach and bus stop just 2 min by walk and near to "Naviglio" where
                        you can enjoy the main night life in Barcelona...
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
