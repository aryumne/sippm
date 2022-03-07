@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-header card-header-info card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">assignment</i>
                        </div>
                        <div class="row card-title">
                            <div class="col-md-6">
                                <h4 class="fw-400">Data Sumber Dana</h4>
                            </div>
                            <div class="col-md-6 text-right">
                                <button type="button" class="btn btn-rose btn-round mt-0" data-toggle="modal"
                                    data-target="#addForm">
                                    <span class="material-icons">add</span> Sumber Dana
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="material-datatables">
                            <table id="datatables-sumberDana" class="table table-striped table-no-bordered table-hover"
                                cellspacing="0" width="100%" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Sumber Dana</th>
                                        <th class="disabled-sorting text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Sumber Dana</th>
                                        <th class="disabled-sorting text-right">Actions</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    {{ $no = 1 }}
                                    @foreach ($sumberDana as $j)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $j->sumber }}</td>
                                            <td class="text-right">
                                                <button type="button" class="btn btn-link btn-warning btn-just-icon like"
                                                    data-toggle="modal" data-target="#editsumberDana{{ $j->id }}">
                                                    <span class="material-icons">mode_edit</span>
                                                </button>
                                                {{-- Modal Edit sumberDana --}}
                                                <div class="modal fade" id="editsumberDana{{ $j->id }}"
                                                    tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Edit
                                                                    Sumber Dana
                                                                </h5>
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form class="form"
                                                                action="{{ route('sumberDana.update', $j->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <label for="sumber">Nama Sumber
                                                                            Dana</label>
                                                                        <input type="text" class="form-control pt-4"
                                                                            id="sumber" name="sumber"
                                                                            value="{{ old('sumber', $j->sumber) }}"
                                                                            required>
                                                                        @error('sumber')
                                                                            <span id="category_id-error"
                                                                                class="error text-danger" for="input-id"
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
    </div>
@endsection


@section('modal')
    <!-- Tambah Data sumberDana -->
    <div class="modal fade" id="addForm" tabindex="-1" role="dialog" aria-labelledby="importForm" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Data Sumber Dana</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form" id="AddsumberDanaValidation" action="{{ route('sumberDana.store') }}"
                    method="POST">
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
                                <h6>Sumber Dana</h6>
                            </div>
                            <div class="col-9">
                                <div class="card-title">
                                    <input type="text" class="form-control pl-2" id="sumber" name="sumber" required
                                        value="{{ old('sumber') }}">
                                    @error('sumber')
                                        <span id="category_id-error" class="error text-danger" for="input-id"
                                            style="display: block;">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer pt-3">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
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
            $('#datatables-sumberDana').DataTable({
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
