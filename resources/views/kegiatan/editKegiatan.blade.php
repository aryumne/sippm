@extends('layouts.main')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-10 col-xl-8">
            <div class="card">
                <div class="card-header card-header-info card-header-icon">
                    <div class="card-icon">
                        <i class="material-icons">assignment</i>
                    </div>
                    <div class="row card-title">
                        <div class="col-12">
                            <h4 class="fw-400">Kegiatan {{ $jenis == "penelitian" ? 'Penelitian' : 'PkM' }}</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="pt-2 px-md-3">
                        <form class="form" id="EditKegiatanValidation" action="{{ route('kegiatan.update',$kegiatan) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row py-2">
                                <div class="col-md-3 pt-2">
                                    <label class="label-control">Judul {{ $jenis == "penelitian" ? 'Penelitian' : 'PkM' }} </label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control pl-2" value="{{ old('judul', $kegiatan->judul_kegiatan)}}" name="judul" id="judul" required />
                                    @error('judul')
                                    <span id="category_id-error" class="error text-danger" for="input-id" style="display: block;">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row py-2">
                                <div class="col-md-3 pt-2">
                                    <label class="label-control">Sumber Dana</label>
                                </div>
                                <div class="col-md-9">
                                    <select class="form-control selectpicker" data-style="btn btn-link" id="sumberDana" name="sumberDana" required>
                                        <option disabled selected>Pilih Sumber Dana</option>
                                        @foreach ($sumberDana as $SD)
                                        <option value="{{ $SD->id }}" {{ $kegiatan->sumber_id == $SD->id ? 'Selected' : ''}}>
                                            {{ $SD->sumber }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('sumberDana')
                                    <span id="category_id-error" class="error text-danger" for="input-id" style="display: block;">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row py-2">
                                <div class="col-md-3 pt-2">
                                    <label class="label-control">Jumlah Dana</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control dana pl-2" id="dana" name="dana" value="{{ old('dana', $kegiatan->jumlah_dana) }}" required>
                                    @error('dana')
                                    <span id="category_id-error" class="error text-danger" for="input-id" style="display: block;">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row py-2">
                                <div class="col-md-3 pt-2">
                                    <label class="label-control">Tahun Kegiatan</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="year" class="form-control pl-2" value="{{ old('tahun', $kegiatan->tahun)}}" name="tahun" id="tahun" required />

                                    @error('tahun')
                                    <span id="category_id-error" class="error text-danger" for="input-id" style="display: block;">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row py-2">
                                <div class="col-md-3 pt-2">
                                    <label class="label-control">Ketua Pelaksana</label>
                                </div>
                                <div class="col-md-9">
                                    @foreach ($kegiatan->timIntern as $internKetua)
                                    @if ($internKetua->pivot->isLeader == true)
                                    @if (Auth::user()->role_id == 1)
                                    <div id="nidn_ketua">
                                        <select class="form-control" data-size="10" data-color="rose" id="choices-tag-ketua" name="nidn_ketua" required>
                                            @foreach ($dosens as $ds)
                                            <option value="{{ str_pad($ds->nidn, 10, '0', STR_PAD_LEFT) }}" {{ str_pad($ds->nidn, 10, '0', STR_PAD_LEFT) == $internKetua->nidn ? 'Selected' : '' }}>
                                                {{ $ds->nama }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @elseif (Auth::user()->role_id >= 2)
                                    <div id="nidn_ketua">
                                        <select class="form-control" data-size="10" data-color="rose" id="choices-tag-ketua" name="nidn_ketua" required>
                                            @foreach ($dosens as $ds)
                                            @if($internKetua->nidn == $ds->nidn)
                                            <option value="{{ str_pad($ds->nidn, 10, '0', STR_PAD_LEFT) }}" selected>
                                                {{ $ds->nama }}
                                            </option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    @endif
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                            <div class="row py-2">
                                <div class="col-md-3 pt-2">
                                    <label class="label-control">Anggota Pelaksana</label>
                                </div>
                                <div class="col-md-9">
                                    <div id="nidn_anggota">
                                        <select multiple class="form-control" data-size="10" data-color="rose" id="choices-tag-anggota" name="nidn_anggota[]">
                                            @foreach ($dosens as $ds)
                                            @if (Auth::user()->role_id == 1)
                                            <option value="{{ str_pad($ds->nidn, 10, '0', STR_PAD_LEFT) }}" @foreach ($kegiatan->timIntern as $anggota)
                                                @if($anggota->pivot->isLeader == false)
                                                {{ $anggota->nidn == $ds->nidn ? 'Selected' : '' }}
                                                @endif
                                                @endforeach>
                                                {{ $ds->nama }}
                                            </option>

                                            @elseif(Auth::user()->role_id >= 2)
                                            @if(Auth::user()->nidn != $ds->nidn)
                                            <option value="{{ str_pad($ds->nidn, 10, '0', STR_PAD_LEFT) }}" @foreach ($kegiatan->timIntern as $anggota)
                                                @if($anggota->pivot->isLeader == false)
                                                {{ $anggota->nidn == $ds->nidn ? 'Selected' : '' }}
                                                @endif
                                                @endforeach>
                                                {{ $ds->nama }}
                                            </option>
                                            @endif
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row py-2">
                                <div class="col-md-3 pt-2">
                                    <label class="label-control">Unggah file</label>
                                </div>
                                <div class="col-md-9">
                                    <div class="form-group form-file-upload form-file-multiple" id="unggah_artikel">
                                        <input type="file" name="path_kegiatan" class="inputFileHidden">
                                        <div class="input-group">
                                            <input type="text" id="path_kegiatan" class="form-control inputFileVisible" placeholder="single file">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-fab btn-warning btn-round">
                                                    <i class="material-icons">attach_file</i>
                                                </button>
                                            </span>
                                        </div>
                                        <small class="form-text text-muted text-left"><cite>Jika tidak ada perubahan pada file sebelumnya tidak perlu mengunggah ulang, ( maksimal 8 Mb dengan format file .pdf)</cite></small>
                                        @error('path_kegiatan')
                                        <span id="category_id-error" class="error text-danger" for="input-id" style="display: block;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row px-2 justify-content-between">
                                <a href="{{ route('kegiatan.index', $jenis) }}" class="btn btn-secondary text-rose">Batal</a>
                                <button type="  " class="btn btn-rose">Simpan</button>
                            </div>
                        </form>
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

        // Format mata uang.
        $('.dana').mask('000.000.000', {
            reverse: true
        });

        //choices-tag-ketua
        var choicesTagsKetua = document.getElementById('choices-tag-ketua');
        var color = choicesTagsKetua.dataset.color;
        if (choicesTagsKetua) {
            const example = new Choices(choicesTagsKetua, {
                maxItemCount: 40
                , removeItemButton: false
                , addItems: true
                , itemSelectText: ''
                , classNames: {
                    item: 'btn btn-sm btn-link btn-' + color + ' me-2'
                , }
            });
        }
        $('.choices').css("margin-bottom", "3px");

        //choices-tag-anggota
        var choicesTagsAnggota = document.getElementById('choices-tag-anggota');
        var color = choicesTagsAnggota.dataset.color;
        if (choicesTagsAnggota) {
            const example = new Choices(choicesTagsAnggota, {
                maxItemCount: 40
                , removeItemButton: false
                , addItems: true
                , itemSelectText: ''
                , classNames: {
                    item: 'btn btn-sm btn-link btn-' + color + ' me-2'
                , }
            });
        }

    });

</script>
@endsection
