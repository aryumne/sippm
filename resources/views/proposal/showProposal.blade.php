@extends('layouts.main')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header card-header-text card-header-info">
                        <div class="card-text">
                            <h4 class="fw-400">Detail Usulan Proposal</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td>
                                            <p>Judul Proposal</p>
                                        </td>
                                        <td class="text-left">
                                            <h5>
                                                {{ $proposal->judul }}
                                            </h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>Tanggal Pengusulan</p>
                                        </td>
                                        <td class="text-left">
                                            <h5>
                                                {{ $proposal->tanggal_usul }}
                                            </h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>Pengusul</p>
                                        </td>
                                        <td class="text-left">
                                            <h5>
                                                @foreach ($proposal->dosen as $dsn)
                                                    @if ($dsn->pivot->isLeader == true)
                                                        {{ $dsn->nama }}
                                                    @endif
                                                @endforeach
                                            </h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>Anggota</p>
                                        </td>
                                        <td class="text-left">
                                            @foreach ($proposal->dosen as $dsn)
                                                @if ($dsn->pivot->isLeader == false)
                                                    <h5> {{ $dsn->nama }}</h5>
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>File Proposal</p>
                                        </td>
                                        <td class="text-left">
                                            <a href="{{ asset('storage/' . $proposal->path_proposal) }}" target="_blank"
                                                class="badge badge-success">{{ substr($proposal->path_proposal, 9) }}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>Status</p>
                                        </td>
                                        <td class="text-left">
                                            <h5>
                                                @if ($proposal->status == 1)
                                                    Menunggu
                                                @elseif ($proposal->status == 2)
                                                    Lanjut
                                                @else
                                                    Tidak Lanjut
                                                @endif
                                            </h5>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-right">
                            <a href="{{ route('usulan.edit', $proposal->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header card-header-text card-header-info">
                        <div class="card-icon">
                            <i class="material-icons">assignment</i>
                        </div>
                        <div class="row card-title">
                            <div class="col-md-6">
                                <h4 class="fw-400">Penilaian Reviewer 1</h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        The place is close to Barceloneta Beach and bus stop just 2 min by walk and near to "Naviglio" where
                        you can enjoy the main night life in Barcelona...
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
