@extends('layouts.main')
@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-7">
        <div class="card">
          <div class="card-header card-header-icon card-header-info">
            <div class="card-icon">
              <i class="material-icons">perm_identity</i>
            </div>
            <h4 class="card-title">Ubah Profile
            </h4>
          </div>

          @foreach ( $data as $d)
          <div class="card-body">
            <form action="{{ route('dosen.update', $d->nidn) }}" method="POST" enctype="multipart/form-data"
              class="form-horizontal">
              @csrf
              @method('PUT')
              <input type="hidden" name="nidn" value="{{ $d->nidn }}">
              {{--
              <div class="row" id="profile">
                <label class="col-sm-2 col-form-label">Profile photo</label>
                <div class="col-sm-9">
                  <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                    <div class="fileinput-new thumbnail img-circle">
                      <img src="{{ $d->path_foto }}" alt="{{ $d->path_foto }}">
                    </div>
                    <div class="fileinput-preview fileinput-exists thumbnail img-circle"></div>
                    <div>
                      <span class="btn btn-rose btn-file">
                        <span class="fileinput-new">Select image</span>
                        <span class="fileinput-exists">Change</span>
                        <input type="file" name="path_foto" id="input-picture">
                      </span>
                      <a href="#pablo" class="btn btn-danger fileinput-exists" data-dismiss="fileinput"><i
                          class="fa fa-times"></i> Remove</a>
                    </div>
                  </div>
                </div>
              </div>
              @error('path_foto')
              <span id="category_id-error" class="error text-danger" for="input-id" style="display: block;">{{ $message
                }}</span>
              @enderror --}}

              <div class="row">
                <label class="col-sm-2 col-form-label">NIDN</label>
                <div class="col-sm-9">
                  <div class="form-group bmd-form-group is-filled">
                    <input class="form-control" name="nidn" id="nidn" type="text" value="{{ old('nidn', $d->nidn) }}"
                      required="true" aria-required="true" disabled>
                  </div>
                </div>
              </div>
              @error('nidn')
              <span id="category_id-error" class="error text-danger" for="input-id" style="display: block;">{{ $message
                }}</span>
              @enderror

              <div class="row">
                <label class="col-sm-2 col-form-label">Nama</label>
                <div class="col-sm-9">
                  <div class="form-group bmd-form-group is-filled">
                    <input class="form-control" name="nama" id="nama" type="text" value="{{ old('nama', $d->nama) }}"
                      required="true" aria-required="true">
                  </div>
                </div>
              </div>
              @error('nama')
              <span id="category_id-error" class="error text-danger" for="input-id" style="display: block;">{{ $message
                }}</span>
              @enderror

              <div class="row">
                <label class="col-sm-2 col-form-label">Jabatan</label>
                <div class="col-sm-9">
                  <div class="form-group bmd-form-group is-filled">
                    <select class="form-control selectpicker" data-style=" btn btn-link" name="jabatan" id="jabatan_id">
                      @foreach ($jabatan as $k)
                      <option value="{{ $k->id }}" {{ $k->id == $d->jabatan_id ? 'Selected' : '' }}> {{ $k->nama_jabatan
                        }} </option>
                      @endforeach
                    </select>
                    </select>
                  </div>
                </div>
              </div>
              @error('jabatan')
              <span id="category_id-error" class="error text-danger" for="input-id" style="display: block;">{{ $message
                }}</span>
              @enderror

              <div class="row">
                <label class="col-sm-2 col-form-label" for="prodi">prodi</label>
                <div class="col-sm-9">
                  <div class="form-group bmd-form-group is-filled">
                    <select class="form-control selectpicker" data-style=" btn btn-link" data-size="8" name="prodi"
                      id="prodi_id">
                      @foreach ($prodi as $k)
                      <option value="{{ $k->id }}" {{ $k->id == $d->prodi_id ? 'Selected' : '' }}> {{ $k->nama_prodi }}
                      </option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
              @error('prodi')
              <span id="category_id-error" class="error text-danger" for="input-id" style="display: block;">{{ $message
                }}</span>
              @enderror

              <div class="row">
                <label class="col-sm-2 col-form-label mt-4">Nomor Hp</label>
                <div class="col-sm-9 mt-4">
                  <div class="form-group bmd-form-group is-filled">
                    <input class="form-control" name="noHp" id="noHp" type="text"
                      value="{{ old('noHp', $d->handphone) }}" required="true" aria-required="true">
                  </div>
                </div>
              </div>
              @error('noHp')
              <span id="category_id-error" class="error text-danger" for="input-id" style="display: block;">{{ $message
                }}</span>
              @enderror

              <div class="row">
                <label class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-9">
                  <div class="form-group bmd-form-group is-filled">
                    <input class="form-control" name="email" id="email" type="email"
                      value="{{ old('email', $d->email) }}" required="true" aria-required="true">
                  </div>
                </div>
              </div>
              @error('email')
              <span id="category_id-error" class="error text-danger" for="input-id" style="display: block;">{{ $message
                }}</span>
              @enderror

              <button type="submit" class="btn btn-rose pull-right">Ubah Profile</button>
              <div class="clearfix"></div>
            </form>
          </div>
          @endforeach
        </div>
      </div>

      <div class="col-md-5">
        <div class="card">
          <div class="card-header card-header-icon card-header-info">
            <div class="card-icon">
              <i class="material-icons">lock</i>
            </div>
            <h4 class="card-title">Form Ganti Password</h4>
          </div>
          <div class="card-body">
            <form method="POST" action="{{ route('user.update', Auth::user()->id) }}" class="form-horizontal">
              @csrf
              @method('PUT')
              <div class="row">
                <label class="col-sm-4 col-form-label">Password Lama</label>
                <div class="col-sm-7">
                  <div class="form-group bmd-form-group is-filled">
                    <input class="form-control form-password" name="current_password" id="current_password"
                      type="password" value="{{ old('current_password') }}" required="true" aria-required="true">
                  </div>
                </div>
              </div>
              @error('current_password')
              <span id="category_id-error" class="error text-danger" for="input-id" style="display: block;">{{ $message
                }}</span>
              @enderror

              <div class="row">
                <label class="col-sm-4 col-form-label">Password Baru</label>
                <div class="col-sm-7">
                  <div class="form-group bmd-form-group is-filled">
                    <input class="form-control form-password" name="password" id="password" type="password"
                      required="true" aria-required="true" onChange="onChange()">
                  </div>
                </div>
              </div>
              @error('password')
              <span id="category_id-error" class="error text-danger" for="input-id" style="display: block;">{{ $message
                }}</span>
              @enderror

              <div class="row">
                <label class="col-sm-4 col-form-label">Konfirmasi Password</label>
                <div class="col-sm-7">
                  <div class="form-group bmd-form-group is-filled">
                    <input class="form-control form-password" name="password_confirm" id="password_confirm"
                      type="password" required="true" aria-required="true" onChange="onChange()">
                  </div>
                </div>
              </div>
              @error('password_confirm')
              <span id="category_id-error" class="error text-danger" for="input-id" style="display: block;">{{ $message
                }}</span>
              @enderror
              <div class="mb-3">
                <input type="checkbox" class="form-checkbox"> Show password
              </div>
              <div class="text-center mb-3 gantiSubmit">
                <button type="submit" id="gantiSubmit" class="btn btn-rose pull-right">Ganti Password</button>
              </div>
              <div class="clearfix"></div>
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
  $(document).ready(function () {
      $('.form-checkbox').click(function () {
          if ($(this).is(':checked')) {
              $('.form-password').attr('type', 'text');
          } else {
              $('.form-password').attr('type', 'password');
          }
      });
  });
  $('#password, #password_confirm').on('keyup', function () {
      if ($('#password').val() != $('#password_confirm').val()) {
          $('#message').html('Password Tidak Sama').css('color', 'red');
      } else
          $('#message').html('Password Sama').css('color', 'green');
  });
  function onChange() {
      const password = document.querySelector('input[name=password]');
      const confirm = document.querySelector('input[name=password_confirm]');
      if (confirm.value === password.value) {
          confirm.setCustomValidity('');
      } else {
          confirm.setCustomValidity('Passwords tidak Sama');
      }
  }
</script>
@endsection