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
                            <h4 class="fw-400">Luaran Buku</h4>
                        </div>
                        <div class="col-md-6 text-right">
                            <a href="{{route('luaran-buku.create')}}" class="btn btn-rose btn-round mt-0">
                                <span class="material-icons">add</span> Buku
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="material-datatables">
                        <table id="luaran-buku" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Penulis</th>
                                    <th>Judul Buku</th>
                                    <th>Tahun Terbit</th>
                                    <th>Penerbit</th>
                                    <th>ISBN</th>
                                    <th class="disabled-sorting text-center">Actions</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Penulis</th>
                                    <th>Judul Buku</th>
                                    <th>Tahun Terbit</th>
                                    <th>Penerbit</th>
                                    <th>ISBN</th>
                                    <th class="disabled-sorting text-center">Actions</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach ($lapBukus as $buku)
                                <tr>
                                    <td>
                                        @foreach ($buku->timIntern as $intern)
                                        @if($intern->pivot->isLeader == true)
                                        {{ $intern->nama }}
                                        @endif
                                        @endforeach
                                        @foreach ($buku->timExtern as $extern)
                                        @if($extern->isLeader == true)
                                        {{ $extern->nama }}
                                        @endif
                                        @endforeach
                                    </td>
                                    <td>{{$buku->judul}}</td>
                                    <td>{{$buku->tahun}}</td>
                                    <td>{{$buku->penerbit}}</td>
                                    <td>{{$buku->isbn ?  $buku->isbn : '-'}}</td>
                                    <td class="text-center">
                                        <a href="{{ route('luaran-buku.show', $buku) }}" class="btn btn-link btn-info btn-just-icon like"><i class="material-icons">read_more</i></a>
                                        @foreach ($buku->timIntern as $ketua)
                                        @if($ketua->pivot->isLeader == true)
                                        @if($ketua->nidn == Auth::user()->nidn)
                                        <a href="{{ route('luaran-buku.edit', $buku) }}" class="btn btn-link btn-warning btn-just-icon edit"><i class="material-icons">mode_edit</i></a>
                                        <form action="{{ route('luaran-buku.destroy', $buku->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method("DELETE")
                                            <button type="submit" onclick="return confirm('Yakin menghapus data ini ?')" data-bs-toggle="tooltip" data-bs-original-title="Delete" class="btn btn-link btn-danger btn-just-icon edit"><i class="material-icons">delete_outline</i></button>
                                        </form>
                                        @endif
                                        @else
                                        @if(Auth::user()->nidn == $ketua->nidn && Auth::user()->id == $buku->user_id)
                                        <a href="{{ route('luaran-buku.edit', $buku) }}" class="btn btn-link btn-warning btn-just-icon edit"><i class="material-icons">mode_edit</i></a>
                                        <form action="{{ route('luaran-buku.destroy', $buku->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method("DELETE")
                                            <button type="submit" onclick="return confirm('Yakin menghapus data ini ?')" data-bs-toggle="tooltip" data-bs-original-title="Delete" class="btn btn-link btn-danger btn-just-icon edit"><i class="material-icons">delete_outline</i></button>
                                        </form>
                                        @endif
                                        @endif
                                        @endforeach
                                        @if(Auth::user()->role_id == 1)
                                        <a href="{{ route('luaran-buku.edit', $buku) }}" class="btn btn-link btn-warning btn-just-icon edit"><i class="material-icons">mode_edit</i></a>
                                        <form action="{{ route('luaran-buku.destroy', $buku->id) }}" method="POST" class="d-inline">
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

        $('#luaran-buku').DataTable({
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
