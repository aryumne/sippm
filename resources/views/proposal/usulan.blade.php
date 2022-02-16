@extends('layouts.main')

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-10 col-xl-7">
                    <div class="card">
                        <div class="card-header card-header-text card-header-info">
                            <div class="card-icon">
                                <i class="material-icons">assignment</i>
                            </div>
                            <div class="row card-title">
                                <div class="col-6">
                                    <h4 class="fw-400">Daftar Proposal</h4>
                                </div>
                                <div class="col-6 text-right">
                                    <button type="button" class="btn btn-secondary text-rose mt-0" data-toggle="modal"
                                        data-target="#formProposal">
                                        <span class="material-icons">add</span> Proposal Baru
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            @foreach ($pengusul as $pgs)
                                @foreach ($pgs->proposal as $pps)
                                    <div class="card">
                                        <div class="card-body">
                                            <h3 class="card-title fw-400">{{ $pps->judul }}</h3>
                                            <h6 class="card-subtitle mb-2 text-muted">
                                                {{ $pps->tanggal_usul }}</h6>
                                            <div class="row pb-2">
                                                <div class="col-lg-8">
                                                    <h5 class="fw-300">Pengusul:
                                                        @foreach ($pps->dosen as $agt)
                                                            @if ($agt->pivot->isLeader == true)
                                                                {{ $agt->nama }}
                                                            @endif
                                                        @endforeach
                                                    </h5>
                                                    <h6 class="fw-300">Anggota:</h6>
                                                    <div class="row">
                                                        @foreach ($pps->dosen as $agt)
                                                            @if ($agt->pivot->isLeader == false)
                                                                <div class="col-md-6">
                                                                    <p class="h5">{{ $agt->nama }}</p>
                                                                    <p class="card-text" style="margin-top: -15px;">
                                                                        {{ str_pad($agt->nidn, 10, '0', STR_PAD_LEFT) }}
                                                                    </p>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-8">
                                                    <a href="{{ asset('storage/' . $pps->path_proposal) }}"
                                                        target="_blank" class="card-link text-rose">Download</a>
                                                    <a href="#0" class="card-link text-rose">Edit Berkas</a>
                                                </div>
                                                <div class="col-4 text-right">
                                                    <cite class="card-text text-capitalize ">
                                                        {{ $pps->status }}</cite>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    <!-- Modal Proposal -->
    <div class="modal fade" id="formProposal" tabindex="-1" role="dialog" aria-labelledby="formProposal"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Form Pengusulan Proposal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form" id="AddProposalValidation" action="{{ route('usulan.store') }}" method="POST"
                    enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <span class="form-group bmd-form-group email-error ">
                            @if ($errors->any())
                                @foreach ($errors->all() as $e)
                                    <p class="
                                description text-center text-danger">
                                        {{ $e }}</p>
                                @endforeach
                            @endif
                        </span>
                        @if (Auth::user()->role_id == 2)
                            <input type="hidden" name="nidn_pengusul" value="{{ Auth::user()->nidn }}">
                            <input type="hidden" name="status" value="menunggu">
                            <input type="hidden" name="tanggal_usul" value="{{ now()->toDateString('Y-m-d') }}">
                        @endif
                        <div class="form-group">
                            <label for="judul" class="bmd-label-floating">Judul Proposal</label>
                            <input type="text" class="form-control" id="judul" name="judul" value="{{ old('judul') }}"
                                required>
                        </div>
                        @error('judul')
                            <span id="category_id-error" class="error text-danger" for="input-id"
                                style="display: block;">{{ $message }}</span>
                        @enderror
                        @if (Auth::user()->role_id == 1)
                            <div class="form-group mt-3">
                                <input type="text" class="form-control datepicker" id="tanggal_usul" name="tanggal_usul"
                                    placeholder="Tanggal Pengusulan" value="{{ now()->toDateString('Y-m-d') }}"
                                    value="{{ old('tanggal_usul') }}" required>
                            </div>
                            @error('tanggal_usul')
                                <span id="category_id-error" class="error text-danger" for="input-id"
                                    style="display: block;">{{ $message }}</span>
                            @enderror
                            <div class="form-group">
                                <label for="nidn_pengusul">Pengusul</label>
                                <select class="form-control selectpicker" data-style="btn btn-link" id="nidn_pengusul"
                                    name="nidn_pengusul" required>
                                    @foreach ($dosen as $ds)
                                        <option
                                            value="{{ strlen($ds->nidn) <= 9 ? str_pad($ds->nidn, 10, '0', STR_PAD_LEFT) : $ds->nidn }}">
                                            {{ $ds->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('nidn_pengusul')
                                <span id="category_id-error" class="error text-danger" for="input-id"
                                    style="display: block;">{{ $message }}</span>
                            @enderror
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control selectpicker" data-style="btn btn-link" id="status"
                                    name="status" required>
                                    <option>Menunggu</option>
                                    <option>Lanjut</option>
                                    <option>Tidak Lanjut</option>
                                </select>
                            </div>
                            @error('status')
                                <span id="category_id-error" class="error text-danger" for="input-id"
                                    style="display: block;">{{ $message }}</span>
                            @enderror
                        @endif
                        <div class="form-group form-file-upload form-file-multiple">
                            <input type="file" name="path_proposal" class="inputFileHidden" required>
                            <div class="input-group">
                                <input type="text" class="form-control inputFileVisible" placeholder="Single File">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-fab btn-round btn-primary">
                                        <i class="material-icons">attach_file</i>
                                    </button>
                                </span>
                            </div>
                        </div>
                        @error('path_proposal')
                            <span id="category_id-error" class="error text-danger" for="input-id"
                                style="display: block;">{{ $message }}</span>
                        @enderror
                        <div class="form-group">
                            <label for="nidn_anggota">Tambah Anggota</label>
                            <select multiple class="form-control selectpicker" data-style="btn btn-link" id="nidn_anggota"
                                name="nidn_anggota[]" required>
                                @foreach ($dosen as $ds)
                                    <option
                                        value="{{ strlen($ds->nidn) <= 9 ? str_pad($ds->nidn, 10, '0', STR_PAD_LEFT) : $ds->nidn }}">
                                        {{ $ds->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('nidn_anggota')
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
            // initialise Datetimepicker and Sliders
            md.initFormExtendedDatetimepickers();
            if ($('.slider').length != 0) {
                md.initSliders();
            }
            // FileInput
            $('.form-file-simple .inputFileVisible').click(function() {
                $(this).siblings('.inputFileHidden').trigger('click');
            });

            $('.form-file-simple .inputFileHidden').change(function() {
                var filename = $(this).val().replace(/C:\\fakepath\\/i, '');
                $(this).siblings('.inputFileVisible').val(filename);
            });

            $('.form-file-multiple .inputFileVisible, .form-file-multiple .input-group-btn').click(function() {
                $(this).parent().parent().find('.inputFileHidden').trigger('click');
                $(this).parent().parent().addClass('is-focused');
            });

            $('.form-file-multiple .inputFileHidden').change(function() {
                var names = '';
                for (var i = 0; i < $(this).get(0).files.length; ++i) {
                    if (i < $(this).get(0).files.length - 1) {
                        names += $(this).get(0).files.item(i).name + ',';
                    } else {
                        names += $(this).get(0).files.item(i).name;
                    }
                }
                $(this).siblings('.input-group').find('.inputFileVisible').val(names);
            });

            $('.form-file-multiple .btn').on('focus', function() {
                $(this).parent().siblings().trigger('focus');
            });

            $('.form-file-multiple .btn').on('focusout', function() {
                $(this).parent().siblings().trigger('focusout');
            });
        });
    </script>
@endsection
