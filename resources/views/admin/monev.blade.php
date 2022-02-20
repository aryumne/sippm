@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-lg-6">
                <div class="card">
                    <div class="card-header card-header-info card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">assignment</i>
                        </div>
                        <div class="row card-title">
                            <div class="col-md-6">
                                <h4 class="fw-400">Reviewer Laporan Kemajuan</h4>
                            </div>
                            <div class="col-md-6 text-right">
                                <button type="button" class="btn btn-secondary text-rose mt-0" data-toggle="modal"
                                    data-target="#formKemajuan">
                                    <span class="material-icons">add</span>Reviewer Laporan
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="material-datatables">
                            <table id="datatables-audit" class="table table-striped table-no-bordered table-hover"
                                cellspacing="0" width="100%" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Reviewer</th>
                                        <th>Judul Proposal</th>
                                        <th>Status</th>
                                        <th class="disabled-sorting">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reviewers as $rvw)
                                        @foreach ($rvw->kemajuan as $kmj)
                                            <tr>
                                                <td>{{ $rvw->dosen->nama }}</td>
                                                <td>{{ $kmj->proposal->judul }}</td>
                                                <td>{{ $kmj->pivot->status == true ? 'Aktif' : 'Nonaktif' }}</td>
                                                <td>
                                                    @if ($kmj->pivot->status == true)
                                                        <form
                                                            action="{{ route('adminpenilaian.monevs.update', $kmj->pivot->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="status" value="0">
                                                            <button type="sumbit" class="btn btn-sm btn-secondary text-rose"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title="Nonaktifkan"><span class="material-icons">
                                                                    toggle_off
                                                                </span></button>
                                                        </form>
                                                    @else
                                                        <form
                                                            action="{{ route('adminpenilaian.monevs.update', $kmj->pivot->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="status" value="1">
                                                            <button type="sumbit" class="btn btn-sm btn-secondary"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title="Aktifkan"><span class="material-icons">
                                                                    toggle_on
                                                                </span></button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--  end card  -->
            </div>
            <div class="col-md-12 col-lg-6">
                <div class="card">
                    <div class="card-header card-header-success card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">assignment_turned_in</i>
                        </div>
                        <div class="row card-title">
                            <div class="col-6">
                                <h4 class="fw-400">Hasil MONEV Laporan Kemajuan</h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="toolbar">
                            <!--        Here you can write extra buttons/actions for the toolbar              -->
                        </div>
                        <div class="material-datatables">
                            <table id="datatables-hasilAudit" class="table table-striped table-no-bordered table-hover"
                                cellspacing="0" width="100%" style="width:100%">
                                <thead>
                                    <tr class="text-center">
                                        <th class="text-left">Judul Proposal</th>
                                        <th>Total Nilai</th>
                                        <th class="disabled-sorting">Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kemajuans as $kmj)
                                        @if (count($kmj->reviewer) != 0)
                                            <tr class="text-center">
                                                <td class="text-left">{{ $kmj->proposal->judul }}</td>
                                                @if ($kmj->hasilMonev)
                                                    <td>{{ $kmj->hasilMonev->total }}</td>
                                                @else
                                                    <td> Belum monev </td>
                                                @endif
                                                <td> <a href="{{ route('laporan-kemajuan.show', $kmj->id) }}"
                                                        class="btn btn-link btn-info btn-just-icon like"><i
                                                            class="material-icons">read_more</i></a></td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--  end card  -->
            </div>
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
                <form class="form" id="AddPenilaianValidation"
                    action="{{ route('adminpenilaian.monevs.store') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="user_id">Pilih Reviewer</label>
                            <select class="form-control selectpicker" data-style="btn btn-link" id="user_id" name="user_id"
                                required>
                                @foreach ($reviewers as $ds)
                                    <option value="{{ $ds->id }}">
                                        {{ $ds->dosen->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('user_id')
                            <span id="category_id-error" class="error text-danger" for="input-id"
                                style="display: block;">{{ $message }}</span>
                        @enderror
                        <div class="form-group">
                            <label for=" proposal_id">Pilih Laporan Kemajuan</label>
                            <select multiple class="form-control selectpicker" data-size="10" title="multiple select"
                                data-style="btn btn-link" id="lap_kemajuan_id" name="lap_kemajuan_id[]" required>
                                @foreach ($newKemajuans as $pps)
                                    <option value="{{ $pps->id }}">
                                        {{ $pps->proposal->judul }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('lap_kemajuan_id')
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
            $('#datatables-audit').DataTable({
                //pagingType documentation : "https://datatables.net/reference/option/pagingType"
                "pagingType": "first_last_numbers",
                "lengthMenu": [
                    [15, 25, 50, -1],
                    [15, 25, 50, "All"]
                ],
                responsive: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search records",
                }
            });
            $('#datatables-hasilAudit').DataTable({
                //pagingType documentation : "https://datatables.net/reference/option/pagingType"
                "pagingType": "first_last_numbers",
                "lengthMenu": [
                    [15, 25, 55, -1],
                    [15, 25, 55, "All"]
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
