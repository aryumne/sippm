@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header card-header-info card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">assignment_turned_in</i>
                        </div>
                        <div class="row card-title">
                            <div class="col-md-6">
                                <h4 class="fw-400">Hasil MONEV Laporan Kemajuan</h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="material-datatables">
                            <table id="datatables-hasilMonev" class="table table-striped table-no-bordered table-hover"
                                cellspacing="0" width="100%" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Judul Proposal</th>
                                        <th class="text-center">Total Nilai</th>
                                        <th class="disabled-sorting">Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kemajuans as $kmj)
                                        @if (count($kmj->reviewer) != 0)
                                            <tr class="text-center">
                                                <td>{{ $loop->iteration }}</td>
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

@section('customSCript')
    <script>
        $(document).ready(function() {
            $('#datatables-hasilMonev').DataTable({
                //pagingType documentation : "https://datatables.net/reference/option/pagingType"
                "pagingType": "first_last_numbers",
                "lengthMenu": [
                    [10, 15, 25, 55, -1],
                    [10, 15, 25, 55, "All"]
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
