@extends('layouts.main')

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="
@if (Auth::user()->role_id == 2) col-lg-8 col-xl-7
@else
col-12 @endif
                ">
                    <div class="card">
                        <div class="card-header card-header-text card-header-info">
                            <div class="card-icon">
                                <i class="material-icons">assignment</i>
                            </div>
                            <div class="row card-title">
                                <div class="col-md-6">
                                    <h4 class="fw-400">Daftar Proposal</h4>
                                </div>
                                @can('pengusulan_proposal')
                                    <div class="col-md-6 text-right">
                                        <button type="button" class="btn btn-rose btn-round mt-0" data-toggle="modal"
                                            data-target="#formProposal">
                                            <span class="material-icons">add</span> Proposal Baru
                                        </button>
                                    </div>
                                @endcan
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            @if (Auth::user()->role_id == 2)
                                @foreach ($usulan as $pgs)
                                    @foreach ($pgs->proposal as $proposal)
                                        <div class="card">
                                            <div class="card-body">
                                                <h3 class="card-title fw-400">{{ $proposal->judul }}</h3>
                                                <h6 class="card-subtitle mb-2 text-muted">
                                                    {{ $proposal->tanggal_usul }}
                                                </h6>
                                                <div class="row pb-2">
                                                    <div class="col-lg-8">
                                                        <h5 class="fw-300">Pengusul:
                                                            @foreach ($proposal->dosen as $agt)
                                                                @if ($agt->pivot->isLeader == true)
                                                                    {{ $agt->nama }}
                                                                @endif
                                                            @endforeach
                                                        </h5>
                                                        <h6 class="fw-300">Anggota:</h6>
                                                        <div class="row">
                                                            @foreach ($proposal->dosen as $agt)
                                                                @if ($agt->pivot->isLeader == false)
                                                                    <div class="col-md-6">
                                                                        <p class="h5">{{ $agt->nama }}</p>
                                                                        <p class="card-text"
                                                                            style="margin-top: -15px;">
                                                                            {{ str_pad($agt->nidn, 10, '0', STR_PAD_LEFT) }}
                                                                        </p>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-8">
                                                        <a href="{{ asset('storage/' . $proposal->path_proposal) }}"
                                                            target="_blank" class="card-link text-rose">Download</a>
                                                        @foreach ($proposal->dosen as $agt)
                                                            @if ($agt->pivot->isLeader == true)
                                                                @if ($agt->pivot->nidn == Auth::user()->nidn)
                                                                    @can('pengusulan_proposal')
                                                                        <a href="{{ route('usulan.edit', $proposal->id) }}"
                                                                            class="card-link text-rose">Edit Berkas</a>
                                                                    @endcan
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                    <div class="col-4 text-right">
                                                        <cite class="card-text text-capitalize ">
                                                            @if ($proposal->status == 1)
                                                                Menunggu
                                                            @elseif($proposal->status == 2)
                                                                Lanjut
                                                            @elseif($proposal->status == 3)
                                                                Tidak Lanjut
                                                            @endif
                                                        </cite>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endforeach
                            @elseif(Auth::user()->role_id == 1)
                                <div class="toolbar mb-3 px-3">
                                    <div class="card-collapse">
                                        <div class="card-header" role="tab" id="headingOne">
                                            <h5 class="mb-0">
                                                <a data-toggle="collapse" href="#collapseOne"
                                                    aria-expanded="{{ request('tahun_usul') != null || request('faculty_id') != null ? 'true' : 'false' }}"
                                                    aria-controls="collapseOne" class="collapsed">
                                                    Filter data
                                                    <i class="material-icons">filter_alt</i>
                                                </a>
                                            </h5>
                                        </div>
                                        <div id="collapseOne"
                                            class="collapse {{ request('tahun_usul') != null || request('faculty_id') != null ? 'show' : '' }}"
                                            role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion" style="">
                                            <div class="card-body">
                                                <form action="{{ route('usulan.index') }}" method="GET">
                                                    @csrf
                                                    <div class="row align-items-center">
                                                        <div class="col-md-3">
                                                            <h6>Fakultas</h6>
                                                        </div>
                                                        <div class="col-md-9 px-0">
                                                            <div class="form-group m-0">
                                                                <select class="form-control selectpicker"
                                                                    data-style="btn btn-link" id="faculty_id"
                                                                    name="faculty_id">
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
                                                            <h6>Tahun</h6>
                                                        </div>
                                                        <div class="col-md-9 px-0">
                                                            <div class="form-group m-0">
                                                                <input type="year" class="form-control pl-3" id="onlyYear"
                                                                    value="{{ request('tahun_usul') }}"
                                                                    name="tahun_usul" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                        </div>
                                                        <div class="col-md-9 text-left pl-0">
                                                            <button type="submit"
                                                                class="btn btn-sm btn-rose">Filter</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="material-datatables">
                                    <table id="datatables-usulan" class="table table-striped table-no-bordered table-hover"
                                        cellspacing="0" width="100%" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Judul Proposal</th>
                                                <th>Pengusul</th>
                                                <th>Fakultas</th>
                                                <th>Tahun</th>
                                                <th>Berkas Laporan</th>
                                                <th class="disabled-sorting text-right">Actions</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>Judul Proposal</th>
                                                <th>Pengusul</th>
                                                <th>Fakultas</th>
                                                <th>Tahun</th>
                                                <th>Berkas Laporan</th>
                                                <th class="text-right">Actions</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            @foreach ($usulan as $lap)
                                                <tr>
                                                    <td>{{ $lap->judul }}</td>
                                                    <td>
                                                        @foreach ($lap->dosen as $pvt)
                                                            @if ($pvt->pivot->isLeader == true)
                                                                {{ $pvt->nama }}
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>{{ $lap->prodi->faculty->nama_faculty }}</td>
                                                    <td>{{ $lap->tanggal_usul->format('Y') }}</td>
                                                    <td><a href="{{ asset('storage/' . $lap->path_proposal) }}"
                                                            target="_blank" class="badge badge-success">download</a>
                                                    </td>
                                                    <td class="text-right">
                                                        <a href="{{ route('usulan.show', $lap->id) }}"
                                                            class="btn btn-link btn-info btn-just-icon like"><i
                                                                class="material-icons">read_more</i></a>
                                                        <a href="{{ route('usulan.edit', $lap->id) }}"
                                                            class="btn btn-link btn-warning btn-just-icon edit"><i
                                                                class="material-icons">mode_edit</i></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    <!-- Modal Proposal -->
    <div class="modal fade" id="formProposal" tabindex="-1" role="dialog" aria-labelledby="formProposal"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Form Pengusulan Proposal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form" id="AddProposalValidation" action="{{ route('usulan.store') }}"
                    method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        @if (Auth::user()->role_id == 2)
                            <input type="hidden" name="nidn_pengusul" value="{{ Auth::user()->nidn }}">
                            <input type="hidden" name="status" value="menunggu">
                            <input type="hidden" name="tanggal_usul" value="{{ now()->toDateString('Y-m-d') }}">
                        @endif
                        @if (Auth::user()->role_id == 2)
                            <input type="hidden" name="nidn_pengusul" value="{{ Auth::user()->nidn }}">
                            <input type="hidden" name="status" value="menunggu">
                            <input type="hidden" name="tanggal_usul" value="{{ now()->toDateString('Y-m-d') }}">
                        @endif
                        <div class="form-group">
                            <label for="judul" class="bmd-label-floating">Judul Proposal</label>
                            <input type="text" class="form-control" id="judul" name="judul" value="{{ old('judul') }}"
                                required>
                        </div>
                        @error('judul')
                            <span id="category_id-error" class="error text-danger" for="input-id"
                                style="display: block;">{{ $message }}</span>
                        @enderror
                        @if (Auth::user()->role_id == 1)
                            <div class="form-group mt-3">
                                <input type="text" class="form-control datepicker" id="tanggal_usul" name="tanggal_usul"
                                    placeholder="Tanggal Pengusulan" value="{{ old('tanggal_usul') }}" required>
                            </div>
                            @error('tanggal_usul')
                                <span id="category_id-error" class="error text-danger" for="input-id"
                                    style="display: block;">{{ $message }}</span>
                            @enderror
                            <div class="form-group">
                                <label for="nidn_pengusul">Pengusul</label>
                                <select class="form-control" data-size="10" title="Pilih Pengusul" data-color="rose"
                                    id="choices-tag-pengusul" name="nidn_pengusul" required>
                                    @foreach ($dosen as $ds)
                                        <option value="{{ str_pad($ds->nidn, 10, '0', STR_PAD_LEFT) }}">
                                            {{ $ds->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('nidn_pengusul')
                                <span id="category_id-error" class="error text-danger" for="input-id"
                                    style="display: block;">{{ $message }}</span>
                            @enderror
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control selectpicker" data-style="btn btn-link" id="status"
                                    name="status" required>
                                    <option value="1">Menunggu</option>
                                    <option value="2">Lanjut</option>
                                    <option value="3">Tidak Lanjut</option>
                                </select>
                            </div>
                            @error('status')
                                <span id="category_id-error" class="error text-danger" for="input-id"
                                    style="display: block;">{{ $message }}</span>
                            @enderror
                        @endif
                        <div class="form-group">
                            <label for=" nidn_anggota">Tambah Anggota</label>
                            <select multiple class="form-control" data-size="10" title="Pilih Anggota" data-color="rose"
                                id="choices-tags" name="nidn_anggota[]" required>
                                @foreach ($dosen as $ds)
                                    @if (Auth::user()->nidn != str_pad($ds->nidn, 10, '0', STR_PAD_LEFT))
                                        <option value="{{ str_pad($ds->nidn, 10, '0', STR_PAD_LEFT) }}">
                                            {{ $ds->nama }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        @error('nidn_anggota')
                            <span id="category_id-error" class="error text-danger" for="input-id"
                                style="display: block;">{{ $message }}</span>
                        @enderror
                        <div class="form-group form-file-upload form-file-multiple">
                            <input type="file" name="path_proposal" class="inputFileHidden" required>
                            <div class="input-group">
                                <input type="text" class="form-control inputFileVisible" placeholder="Pilih File">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-fab btn-round btn-rose">
                                        <i class="material-icons">attach_file</i>
                                    </button>
                                </span>
                            </div>
                            <small class="form-text text-muted text-left"><cite>Maksimal 2Mb dengan format file
                                    .pdf</cite></small>
                        </div>
                        @error('path_proposal')
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
            //YearPicker
            $('#onlyYear').datetimepicker({
                viewMode: 'years',
                format: 'YYYY'
            });

            //datatables
            $('#datatables-usulan').DataTable({
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
            var choicesTags = document.getElementById('choices-tags');
            var color = choicesTags.dataset.color;
            if (choicesTags) {
                const example = new Choices(choicesTags, {
                    maxItemCount: 40,
                    removeItemButton: false,
                    addItems: true,
                    itemSelectText: '',
                    classNames: {
                        item: 'btn btn-sm btn-link btn-' + color + ' me-2',
                    }
                });
            }
            var choicesTags = document.getElementById('choices-tag-pengusul');
            var color = choicesTags.dataset.color;
            if (choicesTags) {
                const example = new Choices(choicesTags, {
                    maxItemCount: 40,
                    removeItemButton: false,
                    addItems: true,
                    itemSelectText: '',
                    classNames: {
                        item: 'btn btn-sm btn-link btn-' + color + ' me-2',
                    }
                });
            }
        });
    </script>
@endsection
