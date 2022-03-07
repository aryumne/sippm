@extends("layouts.main")

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-10 col-xl-8">
                <div class=" card">
                    <div class="card-header card-header-warning card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">assignment</i>
                        </div>
                        <div class="row card-title">
                            <div class="col-12">
                                <h4 class="fw-400">Edit Laporan Kegiatan</h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="pt-2 px-md-3">
                            <form class="form" id="EditKegiatanValidation"
                                action="{{ route('kegiatan.update', $kegiatan) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="judul" class="bmd-label-floating">Judul
                                        Kegiatan</label>
                                    <input type="text" class="form-control" id="judul" name="judul"
                                        value="{{ old('judul', $kegiatan->judul_kegiatan) }}" required>
                                </div>
                                @error('judul')
                                    <span id="category_id-error" class="error text-danger" for="input-id"
                                        style="display: block;">{{ $message }}</span>
                                @enderror

                                <div class="form-group text-left">
                                    <label for="sumberDana">Sumber Dana</label>
                                    <select class="form-control selectpicker" data-style="btn btn-link" id="sumberDana"
                                        name="sumberDana" required>
                                        @foreach ($sumberDana as $sd)
                                            <option value="{{ $sd->id }}"
                                                {{ $kegiatan->sumber_id == $sd->id ? 'Selected' : '' }}>
                                                {{ $sd->sumber }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('sumberDana')
                                    <span id="category_id-error" class="error text-danger" for="input-id"
                                        style="display: block;">{{ $message }}</span>
                                @enderror

                                <div class="form-group">
                                    <label for="dana" class="bmd-label-floating pt-2">Jumlah
                                        Dana</label>
                                    <input type="text" class="form-control dana" id="dana" name="dana"
                                        value="{{ old('dana', $kegiatan->jumlah_dana) }}" required>
                                </div>
                                @error('dana')
                                    <span id="category_id-error" class="error text-danger" for="input-id"
                                        style="display: block;">{{ $message }}</span>
                                @enderror

                                <div class="form-group mt-3">
                                    <label for="judul" class="bmd-label-floating">Tanggal
                                        Kegiatan</label>
                                    <input type="date" class="form-control" id="tanggal_kegiatan" name="tanggal_kegiatan"
                                        placeholder="Tanggal Kegiatan"
                                        value="{{ old('tanggal_kegiatan', $kegiatan->tanggal_kegiatan->format('Y-m-d')) }}"
                                        required>
                                </div>
                                @error('tanggal_kegiatan')
                                    <span id="category_id-error" class="error text-danger" for="input-id"
                                        style="display: block;">{{ $message }}</span>
                                @enderror

                                <div class="form-group">
                                    <label for=" nidn_anggota"> Anggota Pelaksana </label>
                                    <select multiple class="form-control" data-color="rose" data-size="10"
                                        title="Pilih Anggota" data-style="btn btn-link" id="choices-tags-anggota"
                                        name="nidn_anggota[]">
                                        @foreach ($dosen as $ds)
                                            @if (Auth::user()->nidn != str_pad($ds->nidn, 10, '0', STR_PAD_LEFT))
                                                <option value="{{ str_pad($ds->nidn, 10, '0', STR_PAD_LEFT) }}"
                                                    @foreach ($kegiatan->anggotaKegiatan as $agt) @if (str_pad($agt->nidn, 10, '0', STR_PAD_LEFT) == str_pad($ds->nidn, 10, '0', STR_PAD_LEFT))
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

                                <div class="form-group py-0 my-0">
                                    <cite>Jika File berkas tidak ada perubahan, upload file dikosongkan
                                        saja</cite>
                                </div>
                                <div class="form-group form-file-upload form-file-multiple">
                                    <input type="file" name="path_kegiatan" class="inputFileHidden">
                                    <div class="input-group">
                                        <input type="text" class="form-control inputFileVisible" placeholder="Single File">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-fab btn-round btn-primary">
                                                <i class="material-icons">attach_file</i>
                                            </button>
                                        </span>
                                    </div>
                                    <small class="form-text text-muted text-left"><cite>Maksimal 8Mb dengan format file
                                            .pdf</cite></small>
                                </div>
                                @error('path_kegiatan')
                                    <span id="category_id-error" class="error text-danger" for="input-id"
                                        style="display: block;">{{ $message }}</span>
                                @enderror

                                <div class="card-footer">
                                    <a href="{{ route('kegiatan.show', $kegiatan) }}" class="btn btn-secondary"
                                        data-dismiss="modal">Batal</a>
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
    <script type="text/javaScript">
        $(document).ready(function() {
                                // Format mata uang.
                                $('.dana').mask('000.000.000', {
                                    reverse: true
                                });
                            });
                        </script>
@endsection
