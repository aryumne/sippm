@extends('layouts.auth')
@section('content')
    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-8 ml-auto mr-auto">
            <form class="form" id="ResetPasswordValidation" method="POST"
                action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                <div class="card card-login card-hidden">
                    <div class="card-header card-header-rose text-center">
                        <h4 class="card-title">Reset New Password</h4>
                        <div class="social-line">
                            <p>Silahkan buat password baru anda!</p>
                        </div>
                    </div>
                    <div class="card-body ">
                        <span class="form-group  bmd-form-group email-error ">
                            @if ($errors->any())
                                @foreach ($errors->all() as $e)
                                    <p class="
                                description text-center text-danger">
                                        {{ $e }}</p>
                                @endforeach
                            @endif
                        </span>
                        <span class="form-group bmd-form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="material-icons">email</i>
                                    </span>
                                </div>
                                <input type="email" class="form-control err-email" id="email" name="email"
                                    placeholder="Email" value="{{ old('email', $request->email) }}"
                                    value="admin@material.com" required>
                            </div>
                        </span>
                        <span class="form-group bmd-form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="material-icons">lock_outline</i>
                                    </span>
                                </div>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Password" required>
                            </div>
                        </span>
                        <span class="form-group bmd-form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="material-icons">lock_outline</i>
                                    </span>
                                </div>
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" placeholder="Konfirmasi password" equalTo="#password"
                                    required>
                            </div>
                        </span>
                    </div>
                    <div class="card-footer justify-content-center" id="login">
                        <button type="submit" class="btn btn-rose btn-link btn-lg">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
