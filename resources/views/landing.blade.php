@extends('layouts.auth')
@section('content')
    <div class="row">
        <div class="col-md-10 col-lg-9 col-xl-8 ml-auto mr-auto mb-1 text-center">
            <h2 style="font-weight: 500; font-size: 1.85rem">SISTEM INFORMASI PENELITIAN DAN PENGABDIAN KEPADA MASYARAKAT
            </h2>
            <strong class="text-lead text-light mt-3 mb-0">
                UNIVERSITAS PAPUA
            </strong>
        </div>
    </div>
    <div class="row mt-2 justify-content-center">
        <button data-toggle="modal" data-target="#registerModal" class="btn btn-rose btn-round"
            style="background-color: transparent !important; border: 1px solid white; padding: 10px 25px; margin-left: 10px;">SIGN
            UP</button>
        <div class="mx-2">
        </div>
        <button data-toggle="modal" data-target="#loginModal" class="btn btn-rose btn-round">SIGN
            IN</button>
    </div>

@endsection

@section('modal')
    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" role="">
        <div class="modal-dialog modal-login" role="document">
            <div class="modal-content">
                <div class="card card-signup card-plain">
                    <div class="modal-header">
                        <div class="card-header card-header-info text-center">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                <i class="material-icons">clear</i>
                            </button>

                            <h4 class="card-title">Sign In</h4>
                            <div class="social-line">
                                <p class="description text-center text-light">Pastikan email dan password terdaftar!</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body mt-3">
                        @if ($errors->any())
                            @foreach ($errors->all() as $e)
                                <p class="description text-center text-danger">{{ $e }}</p>
                            @endforeach
                        @endif
                        <form class="form" method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="card-body">
                                <div class="form-group bmd-form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="material-icons">email</i></div>
                                        </div>
                                        <input type="email" class="form-control" name="email" id="email"
                                            placeholder="Email" required autofocus value="{{ old('email') }}">
                                    </div>
                                </div>
                                <div class="form-group bmd-form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="material-icons">lock_outline</i>
                                            </div>
                                        </div>
                                        <input type="password" id="password" name="password" placeholder="Password"
                                            class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group bmd-form-group ">
                                    <div class="input-group pe-4 d-flex justify-content-end">
                                        <label class="form-check-label">
                                            <a href="{{ route('password.request') }}" class="text-dark">lupa
                                                password?</a>
                                        </label>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="submit" class="btn btn-rose btn-link btn-wd btn-lg">Get Started</button>
                    </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Register Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1" role="">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="card card-signup card-plain">
                    <div class="modal-header d-flex justify-content-center">
                        <div class="col-lg-9 card-header card-header-info text-center">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                <i class="material-icons">clear</i>
                            </button>

                            <h4 class="card-title">SIGN UP</h4>
                            <div class="social-line">
                                <p class="description text-center text-light">Pastikan NIDN anda terdaftar!</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        @if ($errors->any())
                            @foreach ($errors->all() as $e)
                                <p class="
                                description text-center text-danger">
                                    {{ $e }}</p>
                            @endforeach
                        @endif
                        <form id="RegisterValidation" class="form" method="POST"
                            action="{{ route('register') }}">
                            @csrf
                            <div class="card-body">
                                <div class="form-group bmd-form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="material-icons">person</i>
                                            </div>
                                        </div>
                                        <input type="number" class="form-control" name="nidn" id="nidn" placeholder="NIDN"
                                            required value="{{ old('nidn') }}" number="true" minlength="10"
                                            maxlength="10">
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
                                <div class="form-group bmd-form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="material-icons">lock_outline</i>
                                            </div>
                                        </div>
                                        <input type="password" id="password_confirmation" name="password_confirmation"
                                            placeholder="Konfirmasi Password" equalTo="#password2" class="form-control"
                                            required>
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
