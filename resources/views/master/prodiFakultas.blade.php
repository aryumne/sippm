@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-lg-7">
                <div class="card">
                    <div class="card-header card-header-info card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">assignment</i>
                        </div>
                        <div class="row card-title">
                            <div class="col-md-6">
                                <h4 class="fw-400">Data Program Pendidikan</h4>
                            </div>
                            <div class="col-md-6 text-right">
                                <button type="button" class="btn btn-sm btn-rose btn-round mt-0" data-toggle="modal"
                                    data-target="#addProdi">
                                    <span class="material-icons">add</span> Prodi Baru
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-no-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Prodi</th>
                                    <th>Fakultas</th>
                                    <th class="disabled-sorting text-right">Edit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($prodis as $prodi)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $prodi->nama_prodi }}</td>
                                        <td>{{ $prodi->faculty->nama_faculty }}</td>
                                        <td class="text-right">
                                            <button type="button" class="btn btn-link btn-warning btn-just-icon like"
                                                data-toggle="modal" data-target="#editProdi{{ $prodi->id }}">
                                                <span class="material-icons">mode_edit</span>
                                            </button>
                                            {{-- Modal Edit Prodi --}}
                                            <div class="modal fade" id="editProdi{{ $prodi->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Edit Prodi
                                                            </h5>
                                                            <button type="button" class="close"
                                                                data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form class="form"
                                                            action="{{ route('prodi.update', $prodi) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label for="nama_prodi">Nama Prodi</label>
                                                                    <input type="text" class="form-control"
                                                                        id="nama_prodi" name="nama_prodi"
                                                                        value="{{ old('nama_prodi', $prodi->nama_prodi) }}"
                                                                        required>
                                                                    @error('nama_prodi')
                                                                        <span id="category_id-error" class="error text-danger"
                                                                            for="input-id"
                                                                            style="display: block;">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                                <div class="form-group text-left">
                                                                    <label for="exampleFormControlSelect1">Fakultas</label>
                                                                    <select class="form-control selectpicker"
                                                                        data-style="btn btn-link" data-size="7"
                                                                        id="faculty_id" name="faculty_id">
                                                                        @foreach ($faculties as $fak)
                                                                            <option value="{{ $fak->id }}"
                                                                                {{ $prodi->faculty_id == $fak->id ? 'Selected' : '' }}>
                                                                                {{ $fak->nama_faculty }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-sm btn-secondary"
                                                                    data-dismiss="modal">Close</button>
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-rose">Simpan</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- End Modal --}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-5">
                <div class="card">
                    <div class="card-header card-header-info card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">assignment</i>
                        </div>
                        <div class="row card-title">
                            <div class="col-md-6">
                                <h4 class="fw-400">Data Fakultas</h4>
                            </div>
                            <div class="col-md-6 text-right">
                                <button type="button" class="btn btn-sm btn-rose btn-round mt-0" data-toggle="modal"
                                    data-target="#addFakultas">
                                    <span class="material-icons">add</span> Fakultas Baru
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-no-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Fakultas</th>
                                    <th class="disabled-sorting text-right">Edit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($faculties as $faculty)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $faculty->nama_faculty }}</td>
                                        <td class="text-right">
                                            <button type="button" class="btn btn-link btn-warning btn-just-icon like"
                                                data-toggle="modal" data-target="#editFakultas{{ $faculty->id }}">
                                                <span class="material-icons">mode_edit</span>
                                            </button>
                                            {{-- Modal Edit Fakultas --}}
                                            <div class="modal fade" id="editFakultas{{ $faculty->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Edit Fakultas
                                                            </h5>
                                                            <button type="button" class="close"
                                                                data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form class="form"
                                                            action="{{ route('faculty.update', $faculty) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label for="nama_faculty">Nama Fakultas</label>
                                                                    <input type="text" class="form-control"
                                                                        id="nama_faculty" name="nama_faculty"
                                                                        value="{{ old('nama_faculty', $faculty->nama_faculty) }}"
                                                                        required>
                                                                    @error('nama_faculty')
                                                                        <span id="category_id-error" class="error text-danger"
                                                                            for="input-id"
                                                                            style="display: block;">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-sm btn-secondary"
                                                                    data-dismiss="modal">Close</button>
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-rose">Simpan</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- End Modal --}}
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
@endsection

@section('modal')
    {{-- Add Prodi --}}
    <div class="modal fade" id="addProdi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Prodi Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form" action="{{ route('prodi.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama_prodi">Nama Prodi</label>
                            <input type="text" class="form-control" id="nama_prodi" name="nama_prodi" required>
                            @error('nama_prodi')
                                <span id="category_id-error" class="error text-danger" for="input-id"
                                    style="display: block;">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlSelect1">Fakultas</label>
                            <select class="form-control selectpicker" data-size="7" data-style="btn btn-link"
                                id="faculty_id" name="faculty_id">
                                @foreach ($faculties as $fak)
                                    <option value="{{ $fak->id }}">{{ $fak->nama_faculty }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-sm btn-rose">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- Add Fakultas --}}
    <div class="modal fade" id="addFakultas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Fakultas Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form" action="{{ route('faculty.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama_faculty">Nama Fakultas</label>
                            <input type="text" class="form-control" id="nama_faculty" name="nama_faculty" required>
                            @error('nama_faculty')
                                <span id="category_id-error" class="error text-danger" for="input-id"
                                    style="display: block;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-sm btn-rose">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
