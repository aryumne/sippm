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
                            <h4 class="fw-400">Luaran Naskah Akademik</h4>
                        </div>
                        <div class="col-md-6 text-right">
                            <a href="{{route('luaran-naskah.create')}}" class="btn btn-rose btn-round mt-0">
                                <span class="material-icons">add</span> Naskah Akedemik
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="material-datatables">
                        <table id="luaran-naskah" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Penulis</th>
                                    <th>Judul Naskah</th>
                                    <th>Peruntukan Naskah</th>
                                    <th>Tahun Penyusunan</th>
                                    <th>File Laporan</th>
                                    <th class="disabled-sorting text-center">Actions</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Penulis</th>
                                    <th>Judul Naskah</th>
                                    <th>Peruntukan Naskah</th>
                                    <th>Tahun Penyusunan</th>
                                    <th>File Laporan</th>
                                    <th class="disabled-sorting text-center">Actions</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach ($lapNaskahs as $naskah)
                                <tr>
                                    <td>
                                        @foreach ($naskah->timIntern as $intern)
                                        @if($intern->pivot->isLeader == true)
                                        {{ $intern->nama }}
                                        @endif
                                        @endforeach
                                        @foreach ($naskah->timExtern as $extern)
                                        @if($extern->isLeader == true)
                                        {{ $extern->nama }}
                                        @endif
                                        @endforeach
                                    </td>
                                    <td>{{$naskah->judul}}</td>
                                    <td>{{$naskah->peruntukan->nama_peruntukan}}</td>
                                    <td>{{$naskah->tahun}}</td>
                                    <td>
                                        <a href="{{ asset('storage/' . $naskah->path_naskah) }}" target="_blank" class="badge badge-success">download file</a>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('luaran-naskah.show', $naskah) }}" class="btn btn-link btn-info btn-just-icon like"><i class="material-icons">read_more</i></a>
                                        @foreach ($naskah->timIntern as $ketua)
                                        @if($ketua->pivot->isLeader == true)
                                        @if($ketua->nidn == Auth::user()->nidn)
                                        <a href="{{ route('luaran-naskah.edit', $naskah) }}" class="btn btn-link btn-warning btn-just-icon edit"><i class="material-icons">mode_edit</i></a>
                                        <form action="{{ route('luaran-naskah.destroy', $naskah->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method("DELETE")
                                            <button type="submit" onclick="return confirm('Yakin menghapus data ini ?')" data-bs-toggle="tooltip" data-bs-original-title="Delete" class="btn btn-link btn-danger btn-just-icon edit"><i class="material-icons">delete_outline</i></button>
                                        </form>
                                        @endif
                                        @else
                                        @if(Auth::user()->nidn == $ketua->nidn && Auth::user()->id == $naskah->user_id)
                                        <a href="{{ route('luaran-naskah.edit', $naskah) }}" class="btn btn-link btn-warning btn-just-icon edit"><i class="material-icons">mode_edit</i></a>
                                        <form action="{{ route('luaran-naskah.destroy', $naskah->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method("DELETE")
                                            <button type="submit" onclick="return confirm('Yakin menghapus data ini ?')" data-bs-toggle="tooltip" data-bs-original-title="Delete" class="btn btn-link btn-danger btn-just-icon edit"><i class="material-icons">delete_outline</i></button>
                                        </form>
                                        @endif
                                        @endif
                                        @endforeach
                                        @if(Auth::user()->role_id == 1)
                                        <a href="{{ route('luaran-naskah.edit', $naskah) }}" class="btn btn-link btn-warning btn-just-icon edit"><i class="material-icons">mode_edit</i></a>
                                        <form action="{{ route('luaran-naskah.destroy', $naskah->id) }}" method="POST" class="d-inline">
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

        $('#luaran-naskah').DataTable({
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
