@extends("layouts.main")

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-10 col-xl-7">
                <div class=" card">
                    <div class="card-header card-header-warning card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">assignment</i>
                        </div>
                        <div class="row card-title">
                            <div class="col-12">
                                <h4 class="fw-400">Edit Usulan Proposal</h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="pt-2 px-md-3">
                            <form class="form" id="EditProposalValidation"
                                action="{{ route('usulan.update', $proposal->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="judul" class="bmd-label-floating">Judul Proposal</label>
                                    <input type="text" class="form-control" id="judul" name="judul"
                                        value="{{ old('judul', $proposal->judul) }}" required>
                                </div>
                                @error('judul')
                                    <span id="category_id-error" class="error text-danger" for="input-id"
                                        style="display: block;">{{ $message }}</span>
                                @enderror
                                @if (Auth::user()->role_id == 1)
                                    <div class="form-group mt-3">
                                        <input type="date" class="form-control" id="tanggal_usul" name="tanggal_usul"
                                            placeholder="Tanggal Pengusulan"
                                            value="{{ old('tanggal_usul', $proposal->tanggal_usul) }}">
                                    </div>
                                    @error('tanggal_usul')
                                        <span id="category_id-error" class="error text-danger" for="input-id"
                                            style="display: block;">{{ $message }}</span>
                                    @enderror
                                    <div class="form-group">
                                        <label for="nidn_pengusul">Pengusul</label>
                                        <select class="form-control" data-color="rose" id="choices-tag-pengusul"
                                            id="nidn_pengusul" name="nidn_pengusul" required>
                                            @foreach ($dosen as $ds)
                                                <option value="{{ str_pad($ds->nidn, 10, '0', STR_PAD_LEFT) }}"
                                                    @foreach ($proposal->dosen as $agt) @if ($agt->pivot->isLeader == true && $agt->pivot->nidn == str_pad($ds->nidn, 10, '0', STR_PAD_LEFT))
Selected @endif
                                                    @endforeach
                                                    >
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
                                            <option value="1" {{ $proposal->status == '1' ? 'Selected' : '' }}>Menunggu
                                            </option>
                                            <option value="2" {{ $proposal->status == '2' ? 'Selected' : '' }}>Lanjut
                                            </option>
                                            <option value="3" {{ $proposal->status == '3' ? 'Selected' : '' }}>Tidak
                                                Lanjut
                                            </option>
                                        </select>
                                    </div>
                                    @error('status')
                                        <span id="category_id-error" class="error text-danger" for="input-id"
                                            style="display: block;">{{ $message }}</span>
                                    @enderror
                                @endif
                                <div class="form-group py-0 my-0">
                                    <cite>Jika File berkas tidak ada perubahan, maka upload file dikosongkan
                                        saja</cite>
                                </div>
                                <div class="form-group form-file-upload form-file-multiple">
                                    <input type="file" name="path_proposal" class="inputFileHidden">
                                    <div class="input-group">
                                        <input type="text" class="form-control inputFileVisible" placeholder="Pilih File">
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
                                    <label for=" nidn_anggota"> Anggota</label>
                                    <select multiple class="form-control" data-color="rose" data-size="10"
                                        title="Pilih Anggota" data-style="btn btn-link" id="choices-tags-anggota"
                                        name="nidn_anggota[]">
                                        @foreach ($dosen as $ds)
                                            @if (Auth::user()->role_id == 2)
                                                @if (Auth::user()->nidn != str_pad($ds->nidn, 10, '0', STR_PAD_LEFT))
                                                    <option value="{{ str_pad($ds->nidn, 10, '0', STR_PAD_LEFT) }}"
                                                        @foreach ($proposal->dosen as $agt) @if ($agt->pivot->isLeader == false && str_pad($agt->pivot->nidn, 10, '0', STR_PAD_LEFT) == str_pad($ds->nidn, 10, '0', STR_PAD_LEFT))
Selected @endif
                                                        @endforeach
                                                        >
                                                        {{ $ds->nama }}
                                                    </option>
                                                @endif
                                            @elseif (Auth::user()->role_id == 1)
                                                <option value="{{ str_pad($ds->nidn, 10, '0', STR_PAD_LEFT) }}"
                                                    @foreach ($proposal->dosen as $agt) @if ($agt->pivot->isLeader == false && str_pad($agt->pivot->nidn, 10, '0', STR_PAD_LEFT) == str_pad($ds->nidn, 10, '0', STR_PAD_LEFT))
Selected @endif
                                                    @endforeach
                                                    >
                                                    {{ $ds->nama }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                @error('nidn_anggota')
                                    <span id="category_id-error" class="error text-danger" for="input-id"
                                        style="display: block;">{{ $message }}</span>
                                @enderror
                                <div class="card-footer">
                                    @if (Auth::user()->role_id == 1)
                                        <a href="{{ route('usulan.show', $proposal->id) }}" class="btn btn-secondary"
                                            data-dismiss="modal">Batal</a>
                                    @elseif(Auth::user()->role_id == 2)
                                        <a href="{{ route('usulan.index') }}" class="btn btn-secondary"
                                            data-dismiss="modal">Batal</a>
                                    @endif
                                    <button type="submit" class="btn btn-rose">Simpan</button>
                                </div>
                            </form>
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

@section('customSCript')
    <script type="text/javascript">
        var choicesTags = document.getElementById('choices-tags-anggota');
        var color = choicesTags.dataset.color;
        if (choicesTags) {
            const example = new Choices(choicesTags, {
                maxItemCount: 40,
                removeItemButton: false,
                addItems: true,
                itemSelectText: '',
                classNames: {
                    item: 'btn btn-sm btn-link btn-' + color + ' me-2',
                }
            });
        }
        var choicesTags = document.getElementById('choices-tag-pengusul');
        var color = choicesTags.dataset.color;
        if (choicesTags) {
            const example = new Choices(choicesTags, {
                removeItemButton: false,
                addItems: true,
                itemSelectText: '',
                classNames: {
                    item: 'btn btn-sm btn-link btn-' + color + ' me-2',
                }
            });
        }
    </script>
@endsection
