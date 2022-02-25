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
                        <div class="col-6">
                            <h4 class="fw-400">Data Jabatan</h4>
                        </div>
                        <div class="col-6 text-right">
                            <button type="button" class="btn btn-rose mt-0" data-toggle="modal" data-target="#addForm">
                                <span class="material-icons">add</span> Tambah Jabatan
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="material-datatables">
                        <table id="datatables-Jabatan" class="table table-striped table-no-bordered table-hover"
                            cellspacing="0" width="100%" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Nomor</th>
                                    <th>Jabatan</th>
                                    <th class="disabled-sorting text-right">Actions</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Nomor</th>
                                    <th>Jabatan</th>
                                    <th class="disabled-sorting text-right">Actions</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                {{ $no = 1 }}
                                @foreach ($jabatans as $j)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $j->nama_jabatan }}</td>
                                    <td class="text-right">
                                        {{-- <a href="" class="btn btn-link btn-info btn-just-icon like"><i
                                                class="material-icons">mode_edit</i></a> --}}

                                        <a href="" class="btn btn-link btn-danger btn-just-icon remove">
                                            <form class="form" action="{{ route('jabatan.destroy', $j->id) }}"
                                                method="POST" id="DeteleJabatanValidation">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-link btn-danger btn-just-icon remove"
                                                    onclick="return confirm('Anda Yakin Menghapus Data ini?');">
                                                    <i class="material-icons">close</i>
                                                </button>
                                            </form>
                                        </a>
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
<!-- Tambah Data Jabatan -->
<div class="modal fade" id="addForm" tabindex="-1" role="dialog" aria-labelledby="importForm" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Jabatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form" id="AddJabatanValidation" action="{{ route('jabatan.store') }}" method="POST">
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
                    <div class="row pt-2 align-items-center">
                        <div class="col-3">
                            <h6>Jabatan</h6>
                        </div>
                        <div class="col-9">
                            <div class="card-title">
                                <input type="text" class="form-control pl-2" id="jabatan" name="nama_jabatan" required
                                    value="{{ old('jabatan') }}">
                                @error('jabatan')
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
            $('#datatables-Jabatan').DataTable({
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