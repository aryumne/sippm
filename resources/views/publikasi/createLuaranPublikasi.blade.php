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
                            <h4 class="fw-400">Tambah Luaran Publikasi</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="pt-2 px-md-3">
                        <form class="form" id="AddLuaranPublikasi" action="{{route('luaran-publikasi.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row py-2">
                                <div class="col-md-3 pt-2">
                                    <label class="label-control">Judul Artikel</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control pl-2" value="{{ old('judul')}}" name="judul" id="judul" required />
                                    @error('judul')
                                    <span id="category_id-error" class="error text-danger" for="input-id" style="display: block;">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row py-2">
                                <div class="col-md-3 pt-2">
                                    <label class="label-control">Nama jurnal</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control pl-2" value="{{ old('nama')}}" name="nama" id="nama" required />
                                    @error('nama')
                                    <span id="category_id-error" class="error text-danger" for="input-id" style="display: block;">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row py-2">
                                <div class="col-md-3 pt-2">
                                    <label class="label-control">Laman jurnal</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control pl-2" value="{{ old('laman')}}" name="laman" id="laman" required />
                                    @error('laman')
                                    <span id="category_id-error" class="error text-danger" for="input-id" style="display: block;">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row py-2">
                                <div class="col-md-3 pt-2">
                                    <label class="label-control">Tahun Publikasi</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="year" class="form-control pl-2" value="{{ old('tahun')}}" name="tahun" id="tahun" required />
                                    @error('tahun')
                                    <span id="category_id-error" class="error text-danger" for="input-id" style="display: block;">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row py-2">
                                <div class="col-md-3 pt-2">
                                    <label class="label-control">Media Publikasi</label>
                                </div>
                                <div class="col-md-9">
                                    <select class="form-control selectpicker" data-style="btn btn-link" id="jenis_jurnal_id" name="jenis_jurnal_id" data-style="btn btn-primary btn-round" required>
                                        <option disabled selected>Pilih Media Publikasi</option>
                                        @foreach ($jenisJurnals as $jenis)
                                        <option value="{{ $jenis->id }}" {{ old('jenis_jurnal_id') == $jenis->id ? 'Selected' : ''}}>
                                            {{ $jenis->jurnal }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('jenis_jurnal_id')
                                    <span id="category_id-error" class="error text-danger" for="input-id" style="display: block;">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row py-2">
                                <div class="col-md-3 pt-2">
                                    <label class="label-control">Ketua Penulis</label>
                                </div>
                                <div class="col-md-9">
                                    @if (Auth::user()->role_id == 1)
                                    <div id="nidn_ketua">
                                        <select class="form-control" data-size="10" data-color="rose" id="choices-tag-ketua" name="nidn_ketua" required>
                                            @foreach ($dosens as $ds)
                                            <option value="{{ str_pad($ds->nidn, 10, '0', STR_PAD_LEFT) }}">
                                                {{ $ds->nama }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @elseif (Auth::user()->role_id >= 2)
                                    <div id="nidn_ketua">
                                        <select class="form-control" data-size="10" data-color="rose" id="choices-tag-ketua" name="nidn_ketua" required>
                                            @foreach ($dosens as $ds)
                                            @if($ds->nidn == Auth::user()->nidn)
                                            <option value="{{ str_pad($ds->nidn, 10, '0', STR_PAD_LEFT) }}" selected>
                                                {{ $ds->nama }}
                                            </option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    @endif

                                    <label id="labelCheckboxKetua">
                                        <input type="checkbox" name="checkKetua" id="checkKetua">
                                        <span class="text-checkbox">Ketua Penulis dari Luar Universitas Papua</span>
                                    </label>
                                    <div class="row" id="ketua_luar">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control " value="{{ old('nama_ketua')}}" name="nama_ketua" id="nama_ketua" placeholder="Nama Ketua Penulis" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" value="{{ old('asal_ketua')}}" name="asal_ketua" id="asal_ketua" placeholder="Asal Instansi" />
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="row py-2">
                                <div class="col-md-3 pt-2">
                                    <label class="label-control">Anggota Penulis</label>
                                </div>
                                <div class="col-md-9">
                                    <div id="nidn_anggota">
                                        <select multiple class="form-control" data-size="10" data-color="rose" id="choices-tag-anggota" name="nidn_anggota[]">
                                            @foreach ($dosens as $ds)
                                            <option value="{{ str_pad($ds->nidn, 10, '0', STR_PAD_LEFT) }}">
                                                {{ $ds->nama }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="row" id="anggota_luar">
                                        <div class="col-12" id="listAnggotaLuar">
                                            <div class="row px-3">
                                                <button type="button" class="btn btn-sm btn-warning px-2" onclick="addRowAnggota()">
                                                    <i class="material-icons">add</i> Anggota dari luar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row py-2">
                                <div class="col-md-3 pt-2">
                                    <label class="label-control">Unggah file</label>
                                </div>
                                <div class="col-md-9">
                                    <div class="form-group form-file-upload form-file-multiple" id="unggah_artikel">
                                        <input type="file" name="path_publikasi" class="inputFileHidden" required>
                                        <div class="input-group">
                                            <input type="text" id="path_publikasi" class="form-control inputFileVisible" placeholder="single file">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-fab btn-warning btn-round">
                                                    <i class="material-icons">attach_file</i>
                                                </button>
                                            </span>
                                        </div>
                                        <small class="form-text text-muted text-left"><cite>Maksimal 8 Mb dengan format file .pdf</cite></small>
                                        @error('path_publikasi')
                                        <span id="category_id-error" class="error text-danger" for="input-id" style="display: block;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row px-2 justify-content-between">
                                <a href="{{ route('luaran-publikasi.index') }}" class="btn btn-secondary text-rose">Batal</a>
                                <button type="submit" class="btn btn-rose">Simpan</button>
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
<script type="text/javascript">
    var x = 1;

    function addRowAnggota() {
        console.log("ada" + x);
        var htmlRow = ' <div class="row" id="anggotaKe-' + x + '"><div class="col-md-6"><div class="form-group"> <input type="text" class="form-control" name="nama_anggota[]" id="nama_anggota" placeholder="Nama anggota Penulis" /></div></div><div class="col-md-5"><div class="form-group"><input type="text" class="form-control" name="asal_anggota[]" id="asal_anggota" placeholder="Asal Instansi" /></div></div><button type="button" class="btn btn-sm btn-danger btn-just-icon" onclick="removeRowAnggota(' + x + ')"><i class="material-icons">remove</i></div>';

        $('#listAnggotaLuar').append(htmlRow);
        x++;
    }

    function removeRowAnggota(y) {
        $('#anggotaKe-' + y).remove();
    }

</script>
<script>
    $(document).ready(function() {
        $('#ketua_luar').hide();
        //checkbox Ketua Luar
        $('#checkKetua').change(function() {
            if ($(this).is(":checked")) {
                $("#nidn_ketua").hide();
                $('#labelCheckboxKetua').css('display', "block");
                $('#ketua_luar').show();
                $('#nama_ketua').prop('required', true);
                $('#asal_ketua').prop('required', true);
            } else {
                $("#nidn_ketua").show();
                $('#ketua_luar').hide();
                $('#nama_ketua').prop('required', false);
                $('#asal_ketua').prop('required', false);
            }
        });

        //YearPicker
        $('#tahun').datetimepicker({
            viewMode: 'years'
            , format: 'Y'
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
