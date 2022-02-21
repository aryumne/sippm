@extends('layouts.auth')
@section('content')
    <div class="row">
        <div class="col-lg-5 col-md-6 col-sm-8 ml-auto mr-auto">
            <form class="form" id="RegisterValidation" method="POST" action="{{ route('register') }}">
                @csrf
                <div class="card card-login card-hidden">
                    <div class="card-header card-header-info text-center">
                        <h4 class="card-title">SIGN UP</h4>
                        <div class="social-line">
                            <p>Pastikan NIDN anda terdaftar!</p>
                        </div>
                    </div>
                    <div class="card-body">
                        <span class="form-group bmd-form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="material-icons">person</i>
                                    </div>
                                </div>
                                <input type="number" class="form-control" name="nidn" id="nidn" placeholder="NIDN"
                                    required value="{{ old('nidn') }}" number="true" minlength="10" maxlength="10">
                            </div>
                        </span>
                        <span class="form-group bmd-form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="material-icons">email</i>
                                    </div>
                                </div>
                                <input type="email" class="form-control" name="email" id="email" placeholder="Email"
                                    required value="{{ old('email') }}">
                            </div>
                        </span>
                        <span class="form-group bmd-form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="material-icons">lock_outline</i>
                                    </div>
                                </div>
                                <input type="password" id="password2" name="password" placeholder="Password"
                                    class="form-control" required>
                            </div>
                        </span>
                        <span class="form-group bmd-form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="material-icons">lock_outline</i>
                                    </div>
                                </div>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    placeholder="Konfirmasi Password" equalTo="#password2" class="form-control" required>
                            </div>
                        </span>
                    </div>
                    <div class="card-footer justify-content-center" id="login">
                        <button type="submit" class="btn btn-rose btn-link btn-lg">Sign up</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
