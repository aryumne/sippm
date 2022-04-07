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
                                    <th class="disabled-sorting text-center">Actions</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Penulis</th>
                                    <th>Judul Artikel</th>
                                    <th>Nama Jurnal</th>
                                    <th>Tahun Publikasi</th>
                                    <th>Media Publikasi</th>
                                    <th class="disabled-sorting text-center">Actions</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach ($lapPublikasis as $publikasi)
                                <tr>
                                    <td>
                                        @foreach ($publikasi->timIntern as $intern)
                                        @if($intern->pivot->isLeader == true)
                                        {{ $intern->nama }}
                                        @endif
                                        @endforeach
                                        @foreach ($publikasi->timExtern as $extern)
                                        @if($extern->isLeader == true)
                                        {{ $extern->nama }}
                                        @endif
                                        @endforeach
                                    </td>
                                    <td>{{$publikasi->judul}}</td>
                                    <td>{{$publikasi->nama}}</td>
                                    <td>{{$publikasi->tahun}}</td>
                                    <td>{{$publikasi->jenis_jurnal->jurnal}}</td>
                                    <td class="text-center">
                                        <a href="{{ route('luaran-publikasi.show', $publikasi) }}" class="btn btn-link btn-info btn-just-icon like"><i class="material-icons">read_more</i></a>
                                        @foreach ($publikasi->timIntern as $ketua)
                                        @if($ketua->pivot->isLeader == true)
                                        @if($ketua->nidn == Auth::user()->nidn)
                                        <a href="{{ route('luaran-publikasi.edit', $publikasi) }}" class="btn btn-link btn-warning btn-just-icon edit"><i class="material-icons">mode_edit</i></a>
                                        <form action="{{ route('luaran-publikasi.destroy', $publikasi->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method("DELETE")
                                            <button type="submit" onclick="return confirm('Yakin menghapus data ini ?')" data-bs-toggle="tooltip" data-bs-original-title="Delete" class="btn btn-link btn-danger btn-just-icon edit"><i class="material-icons">delete_outline</i></button>
                                        </form>
                                        @endif
                                        @else
                                        @if(Auth::user()->nidn == $ketua->nidn && Auth::user()->id == $publikasi->user_id)
                                        <a href="{{ route('luaran-publikasi.edit', $publikasi) }}" class="btn btn-link btn-warning btn-just-icon edit"><i class="material-icons">mode_edit</i></a>
                                        <form action="{{ route('luaran-publikasi.destroy', $publikasi->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method("DELETE")
                                            <button type="submit" onclick="return confirm('Yakin menghapus data ini ?')" data-bs-toggle="tooltip" data-bs-original-title="Delete" class="btn btn-link btn-danger btn-just-icon edit"><i class="material-icons">delete_outline</i></button>
                                        </form>
                                        @endif
                                        @endif
                                        @endforeach
                                        @if(Auth::user()->role_id == 1)
                                        <a href="{{ route('luaran-publikasi.edit', $publikasi) }}" class="btn btn-link btn-warning btn-just-icon edit"><i class="material-icons">mode_edit</i></a>
                                        <form action="{{ route('luaran-publikasi.destroy', $publikasi->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method("DELETE")
                                            <button type="submit" onclick="return confirm('Yakin menghapus data ini ?')" data-bs-toggle="tooltip" data-bs-original-title="Delete" class="btn btn-link btn-danger btn-just-icon edit"><i class="material-icons">delete_outline</i></button>
                                        </form>
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
