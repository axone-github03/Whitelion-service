@extends('layouts.main')
@section('title', $data['title'])
@section('content')

    <link href="{{ asset('assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet"
        type="text/css">

    <link href="{{ asset('assets/libs/%40fullcalendar/core/main.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/%40fullcalendar/daygrid/main.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/%40fullcalendar/bootstrap/main.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/%40fullcalendar/timegrid/main.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .basis-member.staff {
            padding: 1rem 0;
            font-family: 'Raleway', sans-serif;
        }

        .basis-member.staff .member-box {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            margin-bottom: 30px;
        }

        .basis-member.staff .member-box .card-body {
            position: relative;
            height: 150px
        }

        .basis-member.staff .member-box .shape {
            width: 200px;
            height: 200px;
            background: #556ee6;
            opacity: 0.2;
            position: absolute;
            top: 0;
            right: -100px;
            transform: rotate(45deg);
        }

        .basis-member.staff .member-box .card-img-top {
            position: relative;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background-color: #556ee6;
            border-top-left-radius: 0;
            border-bottom: 5px solid #556ee6;
        }

        .basis-member.staff .member-box .member-title {
            font-size: 20px;
            font-weight: 600;
            letter-spacing: -0.025em;
        }
    </style>


    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Dashboard</h4>



                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="card-body">
                <div class="basis-member staff">
                    <div class="container m-0">
                        <div class="row">
                            <div class="col-lg-5 col-md-5 col-xl-3 col-sm-3 col-10">
                                <div class="card member-box shadow-lg">
                                    <span class="shape bg-primary"></span>
                                    <img class="card-img-top" src="{{ asset(Auth::user()->avatar) }}" alt="">
                                    <div class="card-body">
                                        <h4 class="member-title">{{ Auth::user()->first_name }}
                                            {{ Auth::user()->last_name }}</h4>
                                        </br>
                                        <small>Whitelion Production</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            

        </div> <!-- container-fluid -->
    </div>









    @csrf

@endsection('content')

@section('custom-scripts')

    <!-- JAVASCRIPT -->

    <!-- plugin js -->
    <script src="{{ asset('assets/libs/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jquery-ui-dist/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/libs/%40fullcalendar/core/main.min.js') }}"></script>
    <script src="{{ asset('assets/libs/%40fullcalendar/bootstrap/main.min.js') }}"></script>
    <script src="{{ asset('assets/libs/%40fullcalendar/daygrid/main.min.js') }}"></script>
    <script src="{{ asset('assets/libs/%40fullcalendar/timegrid/main.min.js') }}"></script>
    <script src="{{ asset('assets/libs/%40fullcalendar/interaction/main.min.js') }}"></script>

    <script src="{{ asset('assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>


    <script type="text/javascript">


        var csrfToken = $("[name=_token").val();
    </script>



@endsection
