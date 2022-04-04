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
                        <div class="col-md-6">
                            <h4 class="fw-400">Daftar Kegiatan {{ $jenis == 'penelitian' ? 'Penelitian' : 'PkM' }}</h4>
                        </div>
                        <div class="col-md-6 text-right">
                            <a href="{{ route('kegiatan.create', $jenis) }}" class="btn btn-rose btn-round mt-0">
                                <span class=" material-icons">add</span> Kegiatan
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="toolbar mb-3 px-3">
                        <div class="card-collapse">
                            <div class="card-header" role="tab" id="headingOne">
                                <h5 class="mb-0">
                                    <a data-toggle="collapse" href="#collapseOne" aria-expanded="{{ request('tahun_kegiatan') != null || request('faculty_id') != null || request('sumber_dana') != null? 'true': 'false' }}" aria-controls="collapseOne" class="collapsed">
                                        Filter data
                                        <i class="material-icons">filter_alt</i>
                                    </a>
                                </h5>
                            </div>
                            <div id="collapseOne" class="collapse {{ request('tahun_kegiatan') != null || request('faculty_id') != null || request('sumber_dana') != null? 'show': '' }}" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion" style="">
                                <div class="card-body">
                                    <form action="{{ route('kegiatan.index', $jenis) }}" method="GET">
                                        @csrf
                                        <div class="row align-items-center">
                                            <div class="col-md-3">
                                                <h6>Fakultas</h6>
                                            </div>
                                            <div class="col-md-9 px-0">
                                                <div class="form-group m-0">
                                                    <select class="form-control selectpicker" data-style="btn btn-link" id="faculty_id" name="faculty_id">
                                                        <option value="">Semua</option>
                                                        @foreach ($faculties as $faculty)
                                                        <option value="{{ $faculty->id }}" {{ request('faculty_id') == $faculty->id ? 'Selected' : '' }}>
                                                            {{ $faculty->nama_faculty }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-md-3">
                                                <h6>Sumber Dana</h6>
                                            </div>
                                            <div class="col-md-9 px-0">
                                                <div class="form-group m-0">
                                                    <select class="form-control selectpicker" data-style="btn btn-link" id="sumber_dana" name="sumber_dana">
                                                        <option value="">Semua</option>
                                                        @foreach ($sumberDana as $sumber)
                                                        <option value="{{ $sumber->sumber }}" {{ request('sumber_dana') == $sumber->sumber ? 'Selected' : '' }}>
                                                            {{ $sumber->sumber }}</option>
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
                                                    <input type="year" class="form-control pl-3" id="onlyYear" value="{{ request('tahun') }}" name="tahun" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                            </div>
                                            <div class="col-md-9 text-left pl-0">
                                                <button type="submit" class="btn btn-sm btn-rose">Filter</button>
                                                <a href="{{ route('kegiatan.index', $jenis) }}" class="btn btn-sm btn-secondary text-rose">Reset</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @if (request('sumber_dana'))
                        <div class="row py-2 align-items-center bg-light">
                            <div class="col-md-3 col-sm-6 text-md-right pt-3">
                                <h5 class="fw-400">TOTAL SUMBER DANA :</h5>
                            </div>
                            <div class="col-md-9 col-sm-7 pl-md-0">
                                <h5 class="fw-500 pt-3">
                                    {{ 'Rp ' . number_format($dataKegiatans->sum('jumlah_dana'), 2, ',', '.') }}
                                </h5>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="material-datatables">
                        <table id="datatables-penelitian" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Judul Kegiatan</th>
                                    <th>Ketua</th>
                                    <th>Fakultas</th>
                                    <th>Sumber Dana</th>
                                    <th>Jumlah Dana</th>
                                    <th>Tahun</th>
                                    <th class="disabled-sorting text-center">Actions</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Judul Kegiatan</th>
                                    <th>Ketua</th>
                                    <th>Fakultas</th>
                                    <th>Sumber Dana</th>
                                    <th>Jumlah Dana</th>
                                    <th>Tahun</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach ($dataKegiatans as $p)
                                <tr>
                                    <td>{{ $p->judul_kegiatan }}</td>
                                    <td>
                                        @foreach ($p->timIntern as $ketua)
                                        @if($ketua->pivot->isLeader == true)
                                        {{ $ketua->nama }}
                                        @endif
                                        @endforeach
                                    </td>
                                    <td>{{ $p->prodi->faculty->nama_faculty }}</td>
                                    <td>{{ $p->sumberDana->sumber }}</td>
                                    <td>{{ 'Rp ' . number_format($p->jumlah_dana, 2, ',', '.') }}</td>
                                    <td>{{ $p->tahun }}</td>
                                    <td class="text-center" width="140px">
                                        <a href="{{ route('kegiatan.show', $p) }}" class="btn btn-link btn-info btn-just-icon like"><i class="material-icons">read_more</i></a>
                                        @foreach ($p->timIntern as $ketua)
                                        @if($ketua->pivot->isLeader == true)
                                        @if($ketua->nidn == Auth::user()->nidn || Auth::user()->role_id == 1)
                                        <a href="{{ route('kegiatan.edit', $p) }}" class="btn btn-link btn-warning btn-just-icon edit"><i class="material-icons">mode_edit</i></a>
                                        <form action="{{ route('kegiatan.destroy', $p) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method("DELETE")
                                            <button type="submit" onclick="return confirm('Yakin akan menghapus data ini ?')" data-bs-toggle="tooltip" data-bs-original-title="Delete" class="btn btn-link btn-danger btn-just-icon edit"><i class="material-icons">delete_outline</i></button>
                                        </form>
                                        @endif
                                        @endif
                                        @endforeach
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

@section('customSCript')
<script type="text/javascript">
    $(document).ready(function() {
        $('#datatables-penelitian').DataTable({
            //pagingType documentation : "https://datatables.net/reference/option/pagingType"
            "pagingType": "first_last_numbers"
            , "lengthMenu": [
                [10, 25, 50, -1]
                , [10, 25, 50, "All"]
            ]
            , responsive: true
            , language: {
                search: "_INPUT_"
                , searchPlaceholder: "Search records"
            , }
        });
        // Format mata uang.
        $('.dana').mask('000.000.000', {
            reverse: true
        });
    });

</script>
@endsection
