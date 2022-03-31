@extends('layouts.main')
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-lg-8 col-xl-7">
                <h4 class="fw-400 text-uppercase mb-5">Informasi Jadwal Pembukaan Akses</h4>
                @foreach ($notifications as $notif)
                @if ($notif->finished_at > now())
                <div class="alert alert-info alert-with-icon mt-1" data-notify="container">
                    <i class="material-icons" data-notify="icon">notifications</i>
                    <span data-notify="icon" class="now-ui-icons ui-1_bell-53"></span>
                    <h5 class="fw-400">{{ $notif->jadwal->nama_jadwal }}</h5>
                    <span data-notify="message">Mulai :
                        <strong>{{ $notif->started_at->format('d M Y') }}</strong> Sampai
                        <strong>{{ $notif->finished_at->format('d M Y') }}</strong></span>
                </div>
                @else
                <div class="alert alert-default alert-with-icon mt-1" data-notify="container">
                    <i class="material-icons" data-notify="icon">notifications</i>
                    <h5 class="fw-400">{{ $notif->jadwal->nama_jadwal }}</h5>
                    <span data-notify="message ">Belum dibuka</span>
                </div>
                @endif
                @endforeach
            </div>
            <div class="col-md-12 col-lg-4 col-xl-5 mt-5">
                <div class="card card-profile">
                    <div class="card-avatar">
                        <a href="#pablo">
                            <img class="img" src="{{ asset('img/profile.png') }}" />
                        </a>
                    </div>
                    <div class="card-body">
                        <h6 class="card-category text-gray">{{ Auth::user()->role->nama_role }}</h6>
                        <h4 class="card-title">{{ Auth::user()->dosen->nama }}</h4>
                        <div class="row mt-4">
                            <div class="col-3 text-right pt-1">
                                <h6>Email</h6>
                            </div>
                            <div class="col-9 text-left">
                                <p class="card-text">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                        <div class="row pt-2">
                            <div class="col-3 text-right pt-1">
                                <h6>Fakultas</h6>
                            </div>
                            <div class="col-9 text-left">
                                <p class="card-text">
                                    {{ Auth::user()->dosen->prodi->faculty->nama_faculty }}</p>
                            </div>
                        </div>
                        <div class="row pt-2">
                            <div class="col-3 text-right pt-1">
                                <h6>Prodi</h6>
                            </div>
                            <div class="col-9 text-left">
                                <p class="card-text">
                                    {{ Auth::user()->dosen->prodi->nama_prodi }}</p>
                            </div>
                        </div>
                        <div class="row pt-2">
                            <div class="col-3 text-right pt-1">
                                <h6>Jabatan</h6>
                            </div>
                            <div class="col-9 text-left">
                                <p class="card-text">
                                    {{ Auth::user()->dosen->jabatan->nama_jabatan ?? 'Belum diupdate' }}</p>
                            </div>
                        </div>
                        <div class="row pt-2">
                            <div class="col-3 text-right pt-1">
                                <h6>Nomor Hp.</h6>
                            </div>
                            <div class="col-9 text-left">
                                <p class="card-text">{{ Auth::user()->dosen->handphone ?? 'Belum diupdate' }}
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('editProfile') }}" class="btn btn-sm btn-rose btn-round">Update</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="dashboard">
            <div class="col-12">
                <div class="card">
                    <div class="card-header card-header-icon card-header-warning">
                        <div class="card-icon">
                            <i class="material-icons">insert_chart</i>
                        </div>
                        <div class="card-title">
                            Jumlah Proposal - <small>Per Fakultas</small>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="ct-chart" id="facultyChart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('customSCript')
<script type="text/javascript">
    $(document).ready(function() {
        var result = JSON.parse('<?= $jsonFakultas ?>');
        const labelData = [];
        const seriesData = [];
        $.each(result, function(index, value) {
            labelData.push(index);
            seriesData.push(value);
        });

        var data = {
            // A labels array that can contain any sort of values
            labels: labelData,
            // Our series array that contains series objects or in this case series data arrays
            series: [
                seriesData
            ]
        };
        optionsFacultyChart = {
            lineSmooth: Chartist.Interpolation.cardinal({
                tension: 0
            }),
            // low: 0,
            // high: 32,
            chartPadding: {
                top: 0
                , right: 0
                , bottom: 0
                , left: 0
            }
        , };
        var responsiveOptions = [
            ['screen and (min-width: 641px) and (max-width: 1024px)', {
                seriesBarDistance: 10
                , axisX: {
                    labelInterpolationFnc: function(value) {
                        return value;
                    }
                }
            }]
            , ['screen and (max-width: 640px)', {
                seriesBarDistance: 5
                , axisX: {
                    labelInterpolationFnc: function(value) {
                        return value[0];
                    }
                }
            }]
        ];
        // Create a new line chart object where as first parameter we pass in a selector
        // that is resolving to our chart container element. The Second parameter
        // is the actual data object.
        new Chartist.Bar('#facultyChart', data, optionsFacultyChart, responsiveOptions);
    });

</script>
@endsection
