@extends('layouts.main')

@section('content')
    .<div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-lg-10 col-xl-8">
                <div class="card">
                    <div class="card-header card-header-info card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">people_alt</i>
                        </div>
                        <div class="row card-title">
                            <div class="col-6">
                                <h4 class="fw-400">Daftar Reviewers</h4>
                            </div>
                            <div class="col-6 text-right">
                                <button type="button" class="btn btn-secondary text-rose mt-0" data-toggle="modal"
                                    data-target="#addReviewer">
                                    <span class="material-icons">add</span> Akun reviewer
                                </button>
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
                                        <th>NIDN</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reviewers as $rvw)
                                        <tr>
                                            <td>{{ str_pad($rvw->dosen->nidn, 10, '0', STR_PAD_LEFT) }}</td>
                                            <td>{{ $rvw->dosen->nama }}</td>
                                            <td>{{ $rvw->email }}</td>
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

@section('modal')
    <div class="modal fade" id="addReviewer" tabindex="-1" role="">
        <div class="modal-dialog modal-register" role="document">
            <div class="modal-content">
                <div class="card card-signup card-plain">
                    <div class="modal-header d-flex justify-content-center">
                        <div class="col-lg-9 card-header card-header-info text-center">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                <i class="material-icons">clear</i>
                            </button>

                            <h4 class="card-title">Akun Reviewer</h4>
                            <div class="social-line">
                                <p class="description text-center text-light">Pastikan NIDN reviewer terdaftar!</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <form id="AddReviewerValidation" class="form" method="POST"
                            action="{{ route('admin.reviewers.store') }}">
                            @csrf
                            <div class="card-body">
                                <div class="form-group bmd-form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="material-icons">person</i>
                                            </div>
                                        </div>
                                        <div class="col-10 px-0">
                                            <select class="form-control selectpicker" data-style="btn btn-link" id="nidn"
                                                name="nidn" required>
                                                @foreach ($dosen as $ds)
                                                    <option value="{{ str_pad($ds->nidn, 10, '0', STR_PAD_LEFT) }}">
                                                        {{ $ds->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group bmd-form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="material-icons">email</i>
                                            </div>
                                        </div>
                                        <input type="email" class="form-control" name="email" id="email"
                                            placeholder="Email" required value="{{ old('email') }}">
                                    </div>
                                </div>
                                <div class="form-group bmd-form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="material-icons">lock_outline</i>
                                            </div>
                                        </div>
                                        <input type="password" id="password2" name="password" placeholder="Password"
                                            class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-center">
                                <button type="submit" class="btn btn-rose btn-link btn-wd btn-lg">Continue</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
