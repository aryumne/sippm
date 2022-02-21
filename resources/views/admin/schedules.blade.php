@extends('layouts.main')
@section('content')
    .<div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-lg-10 col-xl-8">
                <div class="card">
                    <div class="card-header card-header-info card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">schedule</i>
                        </div>
                        <div class="row card-title">
                            <div class="col-6">
                                <h4 class="fw-400">Penjadwalan Akses</h4>
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
                                        <th>Nama Akses</th>
                                        <th>Tanggal Mulai</th>
                                        <th>Batas Akses</th>
                                        <th>Update</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($schedules as $schd)
                                        <tr>
                                            <td>{{ $schd->jadwal->nama_jadwal }}</td>
                                            <td>{{ $schd->started_at->format('d M Y') }}</td>
                                            <td>{{ $schd->finished_at->format('d M Y') }}</td>
                                            <td>
                                                <button type="button" class="btn btn-link btn-warning btn-just-icon edit"
                                                    data-toggle="modal" data-target="#updateSchedule{{ $schd->id }}"><i
                                                        class="material-icons">mode_edit</i></button>
                                                <!-- Modal Edit Laporan Kemajuan -->
                                                <div class="modal fade" id="updateSchedule{{ $schd->id }}"
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
                                                            <form class="form"
                                                                action="{{ route('schedule.update', $schd) }}"
                                                                method="POST">
                                                                <div class="modal-body">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="form-group">
                                                                        <label class="label-control">Tanggal Mulai</label>
                                                                        <input type="text"
                                                                            class="form-control datetimepicker"
                                                                            name="started_at"
                                                                            value="{{ old('started_at', $schd->started_at) }}"
                                                                            required />
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="label-control">Batas Akses</label>
                                                                        <input type="datetime"
                                                                            class="form-control datetimepicker"
                                                                            name="finished_at"
                                                                            value="{{ old('finished_at', $schd->finished_at) }}"
                                                                            required />
                                                                    </div>
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
