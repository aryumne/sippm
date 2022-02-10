@extends('layouts.auth')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form id="RegisterValidation" method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="card ">
                    <div class="card-header card-header-rose card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">mail_outline</i>
                        </div>
                        <h4 class="card-title">Lupa Password?</h4>
                    </div>
                    <div class="card-body ">
                        <p>Jangan khawatir! Masukkan email yang digunakan login, kami akan mengirimkan link reset password
                            di email anda.</p>
                        <div class="form-group">
                            <label for="exampleEmail" class="bmd-label-floating"> Email Address *</label>
                            <input type="email" class="form-control" id="email" name="email" required="true">
                        </div>
                    </div>
                    <div class="card-footer justify-content-end">
                        <button type="submit" class="btn btn-rose">Send Reset Link</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
