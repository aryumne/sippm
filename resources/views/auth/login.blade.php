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
    <div class="row mt-4 justify-content-center">
        <div class="col-lg-4 col-md-6 col-sm-7 ml-auto mr-auto">
            <form class="form" id="LoginValidation" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="card card-login card-hidden">
                    <div class="card-header card-header-rose text-center">
                        <h4 class="card-title">Sign In</h4>
                        <div class="social-line">
                            <p>Pastikan email anda terdaftar!</p>
                        </div>
                    </div>
                    <div class="card-body ">
                        <span class="form-group bmd-form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="material-icons">email</i></div>
                                </div>
                                <input type="email" class="form-control" name="email" id="email" placeholder="Email"
                                    required autofocus value="{{ old('email') }}">
                            </div>
                        </span>
                        <span class="form-group bmd-form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="material-icons">lock_outline</i>
                                    </div>
                                </div>
                                <input type="password" id="password" name="password" placeholder="Password"
                                    class="form-control" required>
                            </div>
                        </span>
                        <span class="form-group bmd-form-group">
                            <div class="input-group mt-3 d-flex justify-content-end">
                                <label class="form-check-label">
                                    <a href="{{ route('password.request') }}" class="text-dark">lupa
                                        password?</a>
                                </label>
                            </div>
                        </span>
                    </div>
                    <div class="card-footer justify-content-center" id="login">
                        <button type="submit" class="btn btn-rose btn-link btn-lg">Get Started</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
