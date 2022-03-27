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
                            <h4 class="fw-400">Luaran Publikasi</h4>
                        </div>
                        <div class="col-md-6 text-right">
                            <a href="{{route('luaran-publikasi.create')}}" class="btn btn-rose btn-round mt-0">
                                <span class="material-icons">add</span> Publikasi
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="material-datatables">
                        <table id="luaran-publikasi" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Penulis</th>
                                    <th>Judul Artikel</th>
                                    <th>Nama Jurnal</th>
                                    <th>Tahun Publikasi</th>
                                    <th>Media Publikasi</th>
                                    <th class="disabled-sorting text-right">Actions</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Penulis</th>
                                    <th>Judul Artikel</th>
                                    <th>Nama Jurnal</th>
                                    <th>Tahun Publikasi</th>
                                    <th>Media Publikasi</th>
                                    <th class="disabled-sorting text-right">Actions</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach ($lapPublikasis as $lap)
                                <tr>
                                    <td>{{ $lap->user->dosen->nama }}</td>
                                    <td>{{$lap->judul}}</td>
                                    <td>{{$lap->nama}}</td>
                                    <td>{{$lap->tahun}}</td>
                                    <td>{{$lap->jenis_jurnal->jurnal}}</td>
                                    <td>
                                        <a href="{{ asset('storage/' . $lap->path_publikasi) }}" target="_blank" class="btn btn-link btn-success btn-just-icon edit">
                                            <i class="material-icons">file_download</i></a>
                                        <a href="{{ route('luaran-publikasi.show', $lap) }}" class="btn btn-link btn-info btn-just-icon like"><i class="material-icons">read_more</i></a>
                                        @if (Auth::user()->id == $lap->user_id)
                                        <a href="{{ route('luaran-publikasi.edit', $lap) }}" class="btn btn-link btn-warning btn-just-icon edit"><i class="material-icons">mode_edit</i></a>
                                        @endif
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

@section('customSCript')
<script>
    $(document).ready(function() {
        //YearPicker
        $('#tahun').datetimepicker({
            viewMode: 'years'
            , format: 'Y'
        });

        $('#luaran-publikasi').DataTable({
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
    });

</script>
@endsection
