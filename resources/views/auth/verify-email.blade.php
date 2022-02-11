@extends('layouts.auth')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header card-header-text card-header-info">
                    <div class="card-text">
                        <h4 class="card-title">Verification Email</h4>
                    </div>
                </div>
                <div class="card-body">
                    Terima kasih sudah bergabung. Sebelum lanjut, silahkan verifikasi email yang anda daftarkan dengan cara
                    mengklik link verifikasi email yang kami kirim ke email anda. Belum mendapatkan email verifikasi?
                    silahkan klik <strong>Resend Verification Email</strong> di bawah ini.
                </div>
                <div class="card-footer">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button class="btn btn-rose btn-round">Resend Verification Email </button>
                    </form>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-secondary btn-round">Kembali</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
