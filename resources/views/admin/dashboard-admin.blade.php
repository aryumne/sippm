@extends('layouts.main')
@section('content')
    <div class="content">
        <div class="container-fluid">
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
            {{-- <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-stats">
                        <div class="card-header card-header-warning card-header-icon">
                            <div class="card-icon">
                                <i class="material-icons">weekend</i>
                            </div>
                            <p class="card-category">Bookings</p>
                            <h3 class="card-title">184</h3>
                        </div>
                        <div class="card-footer">
                            <div class="stats">
                                <i class="material-icons text-danger">warning</i>
                                <a href="#pablo">Get More Space...</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-stats">
                        <div class="card-header card-header-rose card-header-icon">
                            <div class="card-icon">
                                <i class="material-icons">equalizer</i>
                            </div>
                            <p class="card-category">Website Visits</p>
                            <h3 class="card-title">75.521</h3>
                        </div>
                        <div class="card-footer">
                            <div class="stats">
                                <i class="material-icons">local_offer</i> Tracked from Google Analytics
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-stats">
                        <div class="card-header card-header-success card-header-icon">
                            <div class="card-icon">
                                <i class="material-icons">store</i>
                            </div>
                            <p class="card-category">Revenue</p>
                            <h3 class="card-title">$34,245</h3>
                        </div>
                        <div class="card-footer">
                            <div class="stats">
                                <i class="material-icons">date_range</i> Last 24 Hours
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-stats">
                        <div class="card-header card-header-info card-header-icon">
                            <div class="card-icon">
                                <i class="fa fa-twitter"></i>
                            </div>
                            <p class="card-category">Followers</p>
                            <h3 class="card-title">+245</h3>
                        </div>
                        <div class="card-footer">
                            <div class="stats">
                                <i class="material-icons">update</i> Just Updated
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <h3>Manage Listings</h3>
            <br> --}}
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
                low: 0,
                high: 10, // creative tim: we recommend you to set the high sa the biggest value + something for a better look
                chartPadding: {
                    top: 0,
                    right: 0,
                    bottom: 0,
                    left: 0
                },
            };
            var responsiveOptions = [
                ['screen and (min-width: 641px) and (max-width: 1024px)', {
                    seriesBarDistance: 10,
                    axisX: {
                        labelInterpolationFnc: function(value) {
                            return value;
                        }
                    }
                }],
                ['screen and (max-width: 640px)', {
                    seriesBarDistance: 5,
                    axisX: {
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
