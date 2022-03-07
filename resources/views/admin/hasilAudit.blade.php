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
                                <h4 class="fw-400">Hasil Penilaian Proposal</h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-pills nav-pills-warning" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" style="padding: 4px 10px;" data-toggle="tab" href="#link1"
                                    role="tablist">
                                    Lihat dari proposal
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" style="padding: 4px 10px;" data-toggle="tab" href="#link2"
                                    role="tablist">
                                    Lihat dari reviewer
                                </a>
                            </li>
                        </ul>
                        <div class="material-datatables">
                            <div class="tab-content tab-space">
                                <div class="tab-pane active" id="link1">
                                    <table id="hasilAudit-proposal"
                                        class="table table-striped table-no-bordered table-hover" cellspacing="0"
                                        width="100%" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Judul Proposal</th>
                                                <th class="text-center">Review 1</th>
                                                <th class="text-center">Review 2</th>
                                                <th class="text-center">Rata-Rata</th>
                                                <th class="disabled-sorting">Detail</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($proposals as $pps)
                                                @if (count($pps->reviewer) != 0)
                                                    <tr class="text-center">
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td class="text-left">{{ $pps->judul }}</td>
                                                        @foreach ($pps->hasilAudit as $hasil)
                                                            <td>{{ $hasil->total }}</td>
                                                        @endforeach
                                                        @if (count($pps->hasilAudit) == 0)
                                                            <td> belum mereview </td>
                                                            <td> belum mereview </td>
                                                            <td> - </td>
                                                        @elseif(count($pps->hasilAudit) == 1)
                                                            <td> belum mereview </td>
                                                            <td> - </td>
                                                        @elseif(count($pps->hasilAudit) == 2)
                                                            <td> {{ ($pps->hasilAudit[0]->total + $pps->hasilAudit[1]->total) / 2 }}
                                                            </td>
                                                        @endif
                                                        <td> <a href="{{ route('usulan.show', $pps->id) }}"
                                                                class="btn btn-link btn-info btn-just-icon like"><i
                                                                    class="material-icons">read_more</i></a></td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane" id="link2">
                                    <table id="hasilAudit-reviewer"
                                        class="table table-striped table-no-bordered table-hover" cellspacing="0"
                                        width="100%">
                                        <thead class="text-dark">
                                            <th>ID</th>
                                            <th>Reviewer</th>
                                            <th>Judul Proposal</th>
                                            <th>File Proposal</th>
                                            <th>Review</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($reviewerAudits as $ua)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $ua->user->dosen->nama }}</td>
                                                    <td>{{ $ua->proposal->judul }}</td>
                                                    <td>
                                                        <a href="{{ asset('storage/' . $ua->proposal->path_proposal) }}"
                                                            target="_blank" class="btn btn-sm btn-secondary text-rose">lihat
                                                            Proposal</a>
                                                    </td>
                                                    <td>
                                                        @if ($ua->hasil == null)
                                                            @can('penilaian_proposal')
                                                                <a href="{{ route('reviewer.audit.form', $ua->id) }}"
                                                                    class="btn btn-sm btn-danger">Belum dinilai</a>
                                                            @endcan
                                                        @else
                                                            <button type="button" class="btn btn-sm btn-success"
                                                                data-toggle="modal"
                                                                data-target="#detail{{ $ua->id }}">Lihat
                                                                nilai</button>
                                                            <div class="modal fade" id="detail{{ $ua->id }}"
                                                                tabindex="-1" role="dialog"
                                                                aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-lg">
                                                                    <div class="modal-content">
                                                                        <div
                                                                            class="card-header card-header-text card-header-info">
                                                                            <div class="card-text">
                                                                                <h4 class="card-title">Hasil Penilaian
                                                                                </h4>
                                                                            </div>
                                                                        </div>
                                                                        <div class="card-body">
                                                                            {{-- head --}}
                                                                            <div class="row px-2">
                                                                                <div class="col-12">
                                                                                    <div class="row pt-3 pb-1">
                                                                                        <div class="col-3 fw-400">
                                                                                            Judul Penelitian
                                                                                        </div>
                                                                                        <div class="col-9">
                                                                                            <p class="card-text">
                                                                                                {{ $ua->proposal->judul }}
                                                                                            </p>
                                                                                        </div>
                                                                                    </div>
                                                                                    @foreach ($ua->proposal->dosen as $dsn)
                                                                                        @if ($dsn->pivot->isLeader == true)
                                                                                            <div class="row py-1">
                                                                                                <div class="col-3 fw-300">
                                                                                                    Ketua Peneliti
                                                                                                </div>
                                                                                                <div class="col-9">
                                                                                                    <p
                                                                                                        class="card-text">
                                                                                                        {{ $dsn->nama }}
                                                                                                    </p>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="row py-1">
                                                                                                <div class="col-3 fw-400">
                                                                                                    Fakultas
                                                                                                </div>
                                                                                                <div class="col-9">
                                                                                                    <p
                                                                                                        class="card-text">
                                                                                                        {{ $dsn->prodi->faculty->nama_faculty }}
                                                                                                    </p>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="row py-1">
                                                                                                <div class="col-3 fw-400">
                                                                                                    Catatan
                                                                                                </div>
                                                                                                <div class="col-9">
                                                                                                    <p
                                                                                                        class="card-tex text-justify">
                                                                                                        {{ $ua->hasil->komentar }}
                                                                                                    </p>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="row py-1">
                                                                                                <div class="col-3 fw-400">
                                                                                                    Tanggal Monev
                                                                                                </div>
                                                                                                <div class="col-9">
                                                                                                    <p
                                                                                                        class="card-tex text-justify">
                                                                                                        {{ $ua->hasil->created_at->format('d M Y') }}
                                                                                                    </p>
                                                                                                </div>
                                                                                            </div>
                                                                                        @endif
                                                                                    @endforeach
                                                                                </div>
                                                                            </div>
                                                                            {{-- end head --}}
                                                                            <div class="row pt-3 px-1">
                                                                                <div class="col-12">
                                                                                    <div class="row">
                                                                                        <div class="col-9">
                                                                                            <h6>KRITERIA PENILAIAN</h6>
                                                                                        </div>
                                                                                        <div class="col-3 text-center">
                                                                                            <h6>NILAI</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div
                                                                                        class="row pt-2 bg-light align-items-center">
                                                                                        <div class="col-9">
                                                                                            <p
                                                                                                class="card-text fw-400 mb-0">
                                                                                                Perumusan
                                                                                                masalah:</p>
                                                                                            <ul class="mt-0">
                                                                                                <li>Ketajaman perumusan
                                                                                                    masalah</li>
                                                                                                <li>Tujuan Penelitian</li>
                                                                                            </ul>
                                                                                        </div>
                                                                                        <div class="col-3 text-center">
                                                                                            <h6 class="fw-400">
                                                                                                {{ $ua->hasil->perumusan }}
                                                                                            </h6>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div
                                                                                        class="row pt-2 align-items-center">
                                                                                        <div class="col-9">
                                                                                            <p
                                                                                                class="card-text fw-400 mb-0">
                                                                                                Peluang
                                                                                                luaran penelitian:</p>
                                                                                            <ul class="mt-0">
                                                                                                <li>Publikasi ilmiah</li>
                                                                                                <li>Pengembangan
                                                                                                    iptek-sosbud</li>
                                                                                                <li>Pengayaan bahan ajar
                                                                                                </li>
                                                                                            </ul>
                                                                                        </div>
                                                                                        <div class="col-3 text-center">
                                                                                            <h6 class="fw-400">
                                                                                                {{ $ua->hasil->peluang }}
                                                                                            </h6>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div
                                                                                        class="row pt-2 bg-light align-items-center">
                                                                                        <div class="col-9">
                                                                                            <p
                                                                                                class="card-text fw-400 mb-0">
                                                                                                Metode
                                                                                                penelitian:</p>
                                                                                            <ul class="mt-0">
                                                                                                <li>Ketepatan dan kesesuaian
                                                                                                    metode yang
                                                                                                    digunakan</li>
                                                                                            </ul>
                                                                                        </div>
                                                                                        <div class="col-3 text-center">
                                                                                            <h6 class="fw-400">
                                                                                                {{ $ua->hasil->metode }}
                                                                                            </h6>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div
                                                                                        class="row pt-2 align-items-center">
                                                                                        <div class="col-9">
                                                                                            <p
                                                                                                class="card-text fw-400 mb-0">
                                                                                                Tinjauan
                                                                                                pustaka:</p>
                                                                                            <ul class="mt-0">
                                                                                                <li>Relevansi</li>
                                                                                                <li>Kemutakhiran</li>
                                                                                                <li>Penyusunan daftar
                                                                                                    pustaka</li>
                                                                                            </ul>
                                                                                        </div>
                                                                                        <div class="col-3 text-center">
                                                                                            <h6 class="fw-400">
                                                                                                {{ $ua->hasil->tinjauan }}
                                                                                            </h6>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div
                                                                                        class="row pt-2 bg-light align-items-center">
                                                                                        <div class="col-9">
                                                                                            <p
                                                                                                class="card-text fw-400 mb-0">
                                                                                                Kelayakan
                                                                                                penelitian:</p>
                                                                                            <ul class="mt-0">
                                                                                                <li>Kesesuaian waktu</li>
                                                                                                <li>Kesesuaian biaya</li>
                                                                                                <li>Kesesuaian personalia
                                                                                                </li>
                                                                                            </ul>
                                                                                        </div>
                                                                                        <div class="col-3 text-center">
                                                                                            <h6 class="fw-400">
                                                                                                {{ $ua->hasil->kelayakan }}
                                                                                            </h6>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div
                                                                                        class="row pt-2 align-items-center">
                                                                                        <div class="col-9">
                                                                                            <h5 class="fw-500">
                                                                                                TOTAL
                                                                                                NILAI</h5>
                                                                                        </div>
                                                                                        <div class="col-3 text-center">
                                                                                            <h5 class="fw-400">
                                                                                                {{ $ua->hasil->total }}
                                                                                            </h5>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
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
                <!--  end card  -->
            </div>
        </div>
    </div>
@endsection

@section('customSCript')
    <script>
        $(document).ready(function() {
            $('#hasilAudit-proposal').DataTable({
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
            $('#hasilAudit-reviewer').DataTable({
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
