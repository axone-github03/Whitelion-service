<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title> @yield('title') </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Whitelion" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">


    <link href="{{ asset('assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />


    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('assets/css/app.min.css?v=2') }}" id="app-style" rel="stylesheet" type="text/css" />



    <!-- DataTables -->
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    {{-- <link href="{{ asset('assets/css/datetimepicker.min.css') }}" rel="stylesheet" type="text/css" /> --}}

    <!-- Responsive datatable examples -->
    <link href="{{ asset('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/toastr/build/toastr.min.css') }}">

    {{-- <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" /> --}}
    <link href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <script type="text/javascript">
        function baseURL(parameters = "") {

            var baseURL = '{{ URL::to('/') }}' + parameters;
            return baseURL;

        }

        function getSpaceFilePath(filePath = "") {

            var getSpaceFilePath = '{{ getSpaceFilePath('') }}';

            return getSpaceFilePath + filePath;

        }
    </script>
    <style type="text/css">
        .form-check,
        .form-check-input,
        .form-check-label {
            font-size: 14px;
            letter-spacing: 0.3px;
        }

        .logo-lg img,
        .logo-sm img {
            filter: drop-shadow(2px 4px 6px black);
        }

        .custom-loader {
            animation: none !important;
            border-width: 0 !important;
        }

        .select2-container {
            width: 100% !important;
        }

        .select2-close-mask {
            z-index: 2099;
        }

        .select2-dropdown {
            z-index: 3051;
        }

        .vertical-align-middle td,
        .vertical-align-middle th {
            vertical-align: middle;
        }

        .badge-soft-orange {
            color: orange;
            background-color: rgb(231 205 152 / 18%);

        }

        .dropdown-menu-notification {

            width: 600px;

        }

        .user-notification-avatar-xs {
            min-width: 2rem !important;
        }

        .user-notication-btm:hover {
            fill: black;
        }

        .btn-notification-action.btn-outline-primary:hover {
            background-color: white;
            color: #556ee6;
        }

        ::-webkit-scrollbar {

            width: 20px;

        }

        ::-webkit-scrollbar-thumb {

            background-color: #d6dee1;

            border-radius: 20px;

            border: 6px solid transparent;

            background-clip: content-box;

        }

        ::-webkit-scrollbar-thumb:hover {

            background-color: #a8bbbf;

        }

        ::-webkit-scrollbar-track {

            background-color: transparent;

        }

        .inquiry-log-active {
            background: black;
            border-color: black;
        }

        .inquiry-log-active:focus {
            background: black;
            border-color: black;
            box-shadow: none;
        }

        .fc-event {
            border: 0 !important;
        }

        .class-closing {
            background: #cd4444 !important;
        }

        .sidebar-enable.vertical-collpsed .collpsed-icon {
            left: -10px;
            top: 0px !important;
            position: relative;
        }

        .uncollpsed-icon {
            top: 20px;
            position: relative;
            left: 0px;
        }

        .noti-icon .badge {
            top: 3px !important;
        }

        .input-box input {
            height: 100%;
            width: 100%;
            outline: none;
            font-size: 18px;
            font-weight: 400;
            border: none;
            padding: 0 155px 0 65px;
            background-color: transparent;
        }



        .funnel {
            height: 25px;
            width: auto;
            float: left;
            margin-right: 0.5%;
            position: relative;
            text-align: center;
            text-indent: 16px;
            line-height: 25px;
            font-size: 12px;
            background: #A9A9A9;
            color: #ffffff;
            /* box-shadow: inset 0px 20px 20px 20px rgb(0 0 0 / 15%);*/
        }

        .funnel.active {
            background: #556ee6;
            color: #fff;
        }

        .funnel.active:before {
            border-left-color: #556ee6 !important;
            z-index: 999 !important;
        }

        .funnel.active:before,
        .funnel.active:after {
            position: absolute !important;
            content: '' !important;
            z-index: 1;
            width: 0px !important;
            height: 0 !important;
            top: 60% !important;
            margin: -15px 0 0 !important;
            border: 12px solid transparent;
            border-left-color: #fff;
        }

        .funnel:hover {
            background: #556ee6;
            color: #fff;
        }

        .funnel:hover::before {
            border-left-color: #556ee6;
        }

        /* .funnel:first-child {
            margin-right: 3.99%;
        } */

        .funnel:before,
        .funnel:after {
            position: absolute;
            content: '';
            z-index: 1;
            width: 0px;
            height: 0;
            top: 60%;
            margin: -15px 0 0;
            border: 12px solid transparent;
            border-left-color: #ffffff;
        }

        .funnel:after {
            left: 0%;
        }

        .funnel:before {
            left: 100%;
            z-index: 99;
        }

        .funnel:before {
            border-left-color: #A9A9A9;
        }

        .dropdown-menu-advancefilter {
            width: 700px !important;
        }

        .funnel.next-status-active-class {
            background: #556ee6;
            color: #fff;
        }

        .funnel.next-status-active-class:before {
            border-left-color: #556ee6 !important;
            z-index: 999 !important;
        }

        .funnel.next-status-active-class:before,
        .funnel.next-status-active-class:after {
            position: absolute !important;
            content: '' !important;
            z-index: 1;
            width: 0px !important;
            height: 0 !important;
            top: 50% !important;
            margin: -15px 0 0 !important;
            border: 15px solid transparent;
            border-left-color: #fff;
        }

        .phone_error {
            background-color: #fff;
            padding: 15px;
            width: auto;
            border-radius: 20px;
            box-shadow: 0 2px 5px #00000033;
            cursor: pointer;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 15px;
            position: relative;
            overflow: hidden;
        }

        .phone_error svg,
        .phone_error i {
            color: white;
            border-radius: 50%;
            font-size: 20px;
            width: 50px;
            height: 40px;
            display: flex !important;
            justify-content: center;
            align-items: center;
        }

        .phone_error span {
            font-size: 18px;
            color: white !important;
            line-height: 1.6;
            width: 100%;
        }

        .phone_error.danger {
            border: 1px solid #bd3630;
            background-color: #bd3630b0;
        }

        .phone_error.danger svg,
        .phone_error.danger i {
            background-color: #bd3630;
        }

        .phone_error .bx-x-circle {
            /* position: absolute; */
            /* right: 15px; */
            color: white;
            background-color: transparent !important;
        }

        #phone_no_error_dialog {
            position: absolute;
            top: 40%;
            z-index: 999;
        }

        .ribbon-wrapper-green {
            width: 90px;
            height: 90px;
            overflow: hidden;
            position: absolute;
            top: -3px;
            left: 0px;
        }

        .ribbon-green {
            font: bold 15px Sans-Serif;
            color: #333;
            text-align: center;
            text-shadow: rgba(255, 255, 255, 0.5) 0px 1px 0px;
            -webkit-transform: rotate(314deg);
            -moz-transform: rotate(314deg);
            -ms-transform: rotate(314deg);
            -o-transform: rotate(314deg);
            position: relative;
            padding: 4px 0;
            left: -50px;
            top: 17px;
            width: 160px;
            background-color: #ed0000;
            background-image: -webkit-gradient(linear, left top, left bottom, from(#ed0000), to(#e77575));
            background-image: -webkit-linear-gradient(top, #ed0000, #e77575);
            background-image: -moz-linear-gradient(top, #ed0000, #e77575);
            background-image: -ms-linear-gradient(top, #ed0000, #e77575);
            background-image: -o-linear-gradient(top, #ed0000, #e77575);
            color: #fffef8;
            -webkit-box-shadow: 0px 0px 3px rgba(0, 0, 0, 0.3);
            -moz-box-shadow: 0px 0px 3px rgba(0, 0, 0, 0.3);
            box-shadow: 0px 0px 3px rgba(0, 0, 0, 0.3);
        }

        .ribbon-green:before,
        .ribbon-green:after {
            content: "";
            border-top: 3px solid #6e8900;
            border-left: 3px solid transparent;
            border-right: 3px solid transparent;
            position: absolute;
            bottom: -3px;
        }

        .ribbon-green:before {
            left: 0;
        }

        .ribbon-green:after {
            right: 0;
        }

        #bg_watermark {
            position: absolute;
            z-index: 0;
            background: white;
            display: block;
            min-height: 50%;
            min-width: 50%;
            color: yellow;
        }

        #bg_watermark_text {
            color: lightgrey;
            font-size: 60px;
            font-weight: 900;
            transform: rotate(0deg);
            -webkit-transform: rotate(0deg);
            writing-mode: vertical-lr;
            margin-top: 166px;
            text-orientation: upright;
            color: #dddddd14;
        }

        #searchInput {
            padding-left: 25px !important;
            border: none !important;
            border-radius: 0px !important;
            border-bottom: 1px solid #ddd !important;
            color: #ddd !important;
            background: #2a3042 !important;
        }
    </style>
    @if (Config::get('app.env') == 'local')
        <style>
            .form-label,
            h4,
            .col-form-label {}
        </style>
    @endif

</head>

<body data-sidebar="dark">



    <!-- Loader -->
    <div id="preloader">
        <div id="status">
            <div class="spinner-chase">
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
            </div>
        </div>
    </div>


    <div id="layout-wrapper">

        <header id="page-topbar">
            <div class="navbar-header">


                <div class="d-flex align-items-center">
                    <!-- LOGO -->
                    <div class="navbar-brand-box" style="height: 70px;">
                        <div class="logo-lg">
                            {{-- @if (isAdminOrCompanyAdmin() == 1)
                            <button type="button" style="height: 40px" class="btn header-item noti-icon" id="ai_answer_model">
                                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="25" height="25" x="0" y="0" viewBox="0 0 426 426.798" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M139.066 192.133c2.829.004 5.543-1.121 7.543-3.121s3.125-4.715 3.121-7.543c0-5.89 4.778-10.668 10.668-10.668 5.891 0 10.668 4.777 10.668 10.668s4.774 10.664 10.664 10.664c5.895 0 10.668-4.774 10.668-10.664 0-17.676-14.328-32-32-32-17.671 0-32 14.324-32 32a10.66 10.66 0 0 0 10.668 10.664zM283.629 262.594c-39.836 19.914-99.95 19.914-139.793 0-5.27-2.637-11.676-.5-14.313 4.77-2.632 5.269-.5 11.679 4.77 14.312a180.655 180.655 0 0 0 79.437 17.25 180.697 180.697 0 0 0 79.438-17.25c5.27-2.633 7.406-9.043 4.773-14.313-2.636-5.27-9.043-7.406-14.312-4.77zm0 0" fill="#555b6d" data-original="#000000" class="" opacity="1"></path><path d="M395.066 170.8h-10.668v-32c-.027-29.44-23.89-53.304-53.332-53.331h-32V62.168c14.692-5.191 23.508-20.219 20.872-35.578C317.3 11.227 303.983 0 288.397 0c-15.586 0-28.902 11.227-31.539 26.59-2.636 15.36 6.18 30.387 20.871 35.578v23.3h-128v-23.3c14.696-5.191 23.508-20.219 20.875-35.578C167.97 11.227 154.652 0 139.066 0c-15.586 0-28.906 11.227-31.539 26.59-2.636 15.36 6.176 30.387 20.871 35.578v23.3h-32c-29.441.032-53.3 23.891-53.332 53.333v32H32.398c-17.664.02-31.98 14.336-32 32v42.668c.02 17.664 14.336 31.98 32 32h10.668v42.664c.032 29.445 23.891 53.305 53.332 53.336h53.332v42.664a10.675 10.675 0 0 0 5.5 9.328 10.662 10.662 0 0 0 10.82-.285l82.743-51.707h82.273c29.442-.028 53.305-23.89 53.332-53.336v-42.664h10.668c17.664-.02 31.98-14.336 32-32V202.8c-.02-17.664-14.336-31.98-32-32zM288.398 21.47c5.891 0 10.668 4.773 10.668 10.664 0 5.894-4.777 10.668-10.668 10.668-5.89 0-10.668-4.774-10.668-10.668.012-5.883 4.782-10.653 10.668-10.664zm-149.332 0c5.891 0 10.664 4.773 10.664 10.664 0 5.894-4.773 10.668-10.664 10.668s-10.668-4.774-10.668-10.668c.008-5.887 4.778-10.656 10.668-10.664zM32.398 256.133c-5.886-.004-10.66-4.778-10.668-10.664V202.8c.008-5.887 4.782-10.66 10.668-10.668h10.668v64zm330.668 64c-.02 17.668-14.336 31.984-32 32H245.73c-1.996 0-3.953.562-5.644 1.625l-69.02 43.125V362.8a10.671 10.671 0 0 0-10.668-10.668h-64c-17.664-.016-31.98-14.332-32-32V138.8c.02-17.664 14.336-31.98 32-32h234.668c17.664.02 31.98 14.336 32 32zm42.664-74.664c-.007 5.886-4.777 10.656-10.664 10.664h-10.668v-64h10.668c5.887.012 10.657 4.781 10.664 10.668zm0 0" fill="#555b6d" data-original="#000000" class="" opacity="1"></path><path d="M267.066 149.469c-17.664.02-31.98 14.336-32 32 0 5.89 4.774 10.664 10.664 10.664 5.895 0 10.668-4.774 10.668-10.664s4.778-10.668 10.668-10.668 10.664 4.777 10.664 10.668 4.778 10.664 10.668 10.664c5.891 0 10.668-4.774 10.668-10.664-.02-17.664-14.336-31.98-32-32zm0 0" fill="#555b6d" data-original="#000000" class="" opacity="1"></path></g></svg>
                            </button>
                            
                            @endif --}}

                        </div>
                        <a href="{{ route('dashboard') }}" class="logo logo-light">
                            <span class="logo-sm">
                                <img src="{{ asset('assets/images/logo-light.svg') }}" alt="" height="30">
                            </span>
                        </a>
                    </div>
                    <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect"
                        id="vertical-menu-btn">
                        <i class="fa fa-fw fa-bars"></i>
                    </button>
                </div>
                <div class="w-100 ms-4 " id="top-menu-lead">
                    <div class="row align-items-center">
                        <div class="col-11 d-flex text-end p-0">
                            @if (isset($data['request_module']))
                                <div class="userscomman row col-9 text-start align-items-center">
                                    @if (isset($data['request_status']))
                                        @php
                                            $default_active = '';
                                            foreach ($data['request_status'] as $architect_status_key => $architect_status) {
                                                $default_active = $architect_status['id'];
                                                echo '<a href="javascript:void(0)" class="funnel active" data-id="' . $architect_status['id'] . '" id="arc_funnel_' . $architect_status['id'] . '" onclick="ShowSelectedStatusData(' . $architect_status['id'] . ');">' . $architect_status['header_code'] . ' (<span class=""></span>)</a>';
                                            }
                                        @endphp
                                        <input type="hidden" name="arc_active_status" id="arc_active_status"
                                            value="{{ $default_active }}">
                                    @endif
                                </div>
                            @endif

                        </div>
                        <div class="col-1 text-end">
                            <div class="dropdown d-inline-block">
                                <button type="button" class="btn header-item waves-effect"
                                    id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    <img class="rounded-circle header-profile-user" src="{{ Auth::user()->avatar }}"
                                        alt="Header Avatar">
                                    {{-- <span class="d-none d-xl-inline-block ms-1"
                                        key="t-henry">{{ Auth::user()->first_name }}
                                        {{ Auth::user()->last_name }}</span>
                                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i> --}}
                                </button>

                                <div class="dropdown-menu dropdown-menu-end">
                                    <!-- item-->
                                    <a class="dropdown-item" href="{{ route('profile') }}"><i
                                            class="bx bx-user font-size-16 align-middle me-1"></i> <span
                                            key="t-profile">Profile</span></a>

                                    <a class="dropdown-item" href="{{ route('changepassword') }}"><i
                                            class="bx bx-lock font-size-16 align-middle me-1"></i> <span
                                            key="t-change-password">Change Password</span></a>

                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}"><i
                                            class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i>
                                        <span key="t-logout">Logout</span></a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>


                @if (Config::get('app.env') == 'local')
                    <div class="ribbon-wrapper-green">
                        <div class="ribbon-green">Local</div>
                    </div>
                @endif
            </div>
        </header>

        <div class="modal fade" id="modalView" data-bs-backdrop="static" tabindex="-1" role="dialog"
            aria-labelledby="modalViewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCallLabel">View Name</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group">
                                <label for="" class="form-label">View Name :-</label>
                                <input type="text" class="form-control" id="view_name">
                            </div>
                            @if (isAdminOrCompanyAdmin() == 1)
                                <div class="d-flex mt-2 justify-content-around">
                                    <div class="form-check">
                                        <label class="form-check-label" for="view_public">Public</label>
                                        <input type="radio" id="view_public" name="view_type"
                                            class="form-check-input" value="1">
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label" for="view_private">Private</label>
                                        <input type="radio" id="view_private" name="view_type"
                                            class="form-check-input" value="0" checked>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-primary  float-end ms-2"
                            id="saveFilterAsView">Save</button>
                    </div>
                </div>
            </div>
        </div>
        @include('master_search.index')
    </div>

    <!-- ========== Left Sidebar Start ========== -->
    <div class="vertical-menu">
        <input type="text" class="form-control search-menu" id="searchInput" placeholder="Search...">

        <div data-simplebar class="h-100">
            <!--- Sidemenu -->
            <div id="sidebar-menu">
                <!-- Left Menu Start -->
                <ul class="metismenu list-unstyled" id="side-menu">
                    <li class="menu-title" key="t-menu">Menu</li>

                    <li>
                        <a href="{{ route('dashboard') }}" class="waves-effect">
                            <i class="bx bx-home-circle"></i><span
                                class="badge rounded-pill bg-info float-end"></span>
                            <span key="t-dashboards">Dashboard</span>
                        </a>
                    </li>


                    <li class="menu-title" key="t-service_manage">Service Management</li>
                    <li>
                        <a href="{{ route('service.hierarchy') }}" class=" waves-effect">
                            <i class="bx bx-group"></i>
                            <span key="t-service-hierarchy">Service Hierarchy</span>
                        </a>
                    </li>
                    @php
                        $accessTypes = getUsersAccess(Auth::user()->type);
                    @endphp
                    @if (count($accessTypes) > 0)

                        <li class="menu-title" key="t-user-management">User Management</li>



                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="bx bx-group"></i>
                                <span key="t-users">Users</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">

                                @foreach ($accessTypes as $key => $value)
                                    <li>

                                        <a href="{{ $value['url'] }}" class="waves-effect">

                                            <span key="{{ $value['key'] }}">{{ $value['name'] }}</span>
                                        </a>
                                    </li>
                                @endforeach

                            </ul>
                        </li>

                    @endif




                    <li class="menu-title" key="t-master"> Master</li>
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bxs-map"></i>
                            <span key="t-location">Location</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li>
                                <a href="{{ route('countrylist') }}" class="waves-effect">

                                    <span key="t-country">Country List</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('statelist') }}" class="waves-effect">
                                    <span key="t-state">State List</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('citylist') }}" class="waves-effect">
                                    <span key="t-city">City List</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="menu-title" key="t-crm"> Reward Programm</li>
                    <li>
                        <a href="{{ route('crm.help.document') }}" class="waves-effect">
                            <i class="bx bxs-file-doc"></i>
                            <span key="t-help-document">Help Document</span>
                        </a>
                    </li>

                    <li class="menu-title" key="t-crm"> CRM</li>
                    <li>
                        <a href="{{ route('crm.setting') }}" class="waves-effect">
                            <i class="bx bx-cog "></i>
                            <span key="t-deal">Setting</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('crm.account.table') }}" class="waves-effect">
                            <i class="bx bx-user-circle"></i>
                            <span key="t-lead">Accounts</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('crm.lead.account.contact.table') }}" class="waves-effect">
                            <i class="bx bxs-user-detail"></i>
                            <span key="t-lead">Contacts</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('request.table') }}" class="waves-effect">
                            <i class="bx bxs-user-detail"></i>
                            <span key="t-lead">Request</span>
                        </a>
                    </li>


                </ul>
            </div>
            <!-- Sidebar -->
        </div>
    </div>
    <!-- Left Sidebar End -->



    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content"
        @if (Config::get('app.env') == 'local') style="background-image: url({{ asset('assets/images/is_local.jpg') }});background-repeat: round;" @endif>
        @yield('content')
    </div>

    <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->





    <!-- JAVASCRIPT -->
    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/datetimepicker.min.js') }}"></script>

    <script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>


    <!-- dashboard init -->

    <!-- App js -->
    <script src="{{ asset('assets/js/app.js') }}"></script>

    <!-- Required datatable js -->
    <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Buttons examples -->
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-fixedcolumns/js/fixedColumns.min.js') }}"></script>

    <script src="{{ asset('assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>


    <!-- Responsive examples -->
    <script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

    <!-- Datatable init js -->
    <script src="{{ asset('assets/js/pages/datatables.init.js') }}"></script>
    <script src="{{ asset('assets/libs/toastr/build/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/client/client.min.js') }}"></script>

    <script type="text/javascript"></script>

    <script src="{{ asset('assets/js/c/user-notification.js') }}?v={{ time() }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $("#searchInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#side-menu li").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
        });
        //  $.fn.modal.Constructor.prototype._enforceFocus = function() {};

        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-bottom-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": 200,
            "hideDuration": 1000,
            "timeOut": 3000,
            "extendedTimeOut": 1000,
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
        @if (session('success'))
            toastr["success"](" {{ session('success') }}")
        @endif
        @if (session('error'))
            toastr["error"](" {{ session('error') }}")
        @endif
        @if (session('warning'))
            toastr["warning"](" {{ session('warning') }}")
        @endif
        @if (session('info'))
            toastr["info"](" {{ session('info') }}")
        @endif

        function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }


        function getCookie(cookieName) {
            let cookie = {};
            document.cookie.split(';').forEach(function(el) {
                let [key, value] = el.split('=');
                cookie[key.trim()] = value;
            })
            return cookie[cookieName];
        }

        function setCookie(cname, cvalue, exdays) {
            const d = new Date();
            d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
            let expires = "expires=" + d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }


        $(document).on('click', '.dropdown-menu-notification', function(e) {

            $(this).addClass('show');
            $("#page-header-notifications-dropdown").addClass('show');

        });

        var windowWidth = $(window).width();
        if (windowWidth <= 1440) {
            $('body').addClass('vertical-collpsed');
        }

        var client = new ClientJS();
        var fingerprint = '';
        fingerprint = fingerprint + '\n getBrowserData : ' + JSON.stringify(client.getBrowserData());
        fingerprint = fingerprint + '\n getFingerprint : ' + client.getFingerprint();
        fingerprint = fingerprint + '\n getCustomFingerprint : ' + client.getCustomFingerprint();
        fingerprint = fingerprint + '\n getUserAgent : ' + client.getUserAgent();
        fingerprint = fingerprint + '\n getUserAgentLowerCase : ' + client.getUserAgentLowerCase();
        fingerprint = fingerprint + '\n Browser Data : ' + client.getBrowser();
        fingerprint = fingerprint + '\n getBrowserVersion : ' + client.getBrowserVersion();
        fingerprint = fingerprint + '\n getBrowserMajorVersion : ' + client.getBrowserMajorVersion();
        fingerprint = fingerprint + '\n isIE : ' + client.isIE();
        fingerprint = fingerprint + '\n isChrome : ' + client.isChrome();
        fingerprint = fingerprint + '\n isFirefox : ' + client.isFirefox();
        fingerprint = fingerprint + '\n isSafari : ' + client.isSafari();
        fingerprint = fingerprint + '\n isOpera : ' + client.isOpera();
        fingerprint = fingerprint + '\n getEngine : ' + client.getEngine();
        fingerprint = fingerprint + '\n getEngineVersion : ' + client.getEngineVersion();
        fingerprint = fingerprint + '\n getOS : ' + client.getOS();
        fingerprint = fingerprint + '\n getOSVersion : ' + client.getOSVersion();
        fingerprint = fingerprint + '\n isWindows : ' + client.isWindows();
        fingerprint = fingerprint + '\n isMac : ' + client.isMac();
        fingerprint = fingerprint + '\n isLinux : ' + client.isLinux();
        fingerprint = fingerprint + '\n isUbuntu : ' + client.isUbuntu();
        fingerprint = fingerprint + '\n isSolaris : ' + client.isSolaris();
        fingerprint = fingerprint + '\n getDevice : ' + client.getDevice();
        fingerprint = fingerprint + '\n getDeviceType : ' + client.getDeviceType();
        fingerprint = fingerprint + '\n getDeviceVendor : ' + client.getDeviceVendor();
        fingerprint = fingerprint + '\n getCPU : ' + client.getCPU();
        fingerprint = fingerprint + '\n isMobile : ' + client.isMobile();
        fingerprint = fingerprint + '\n isMobileMajor : ' + client.isMobileMajor();
        fingerprint = fingerprint + '\n isMobileAndroid : ' + client.isMobileAndroid();
        fingerprint = fingerprint + '\n isMobileOpera : ' + client.isMobileOpera();
        fingerprint = fingerprint + '\n isMobileWindows : ' + client.isMobileWindows();
        fingerprint = fingerprint + '\n isMobileBlackBerry : ' + client.isMobileBlackBerry();
        fingerprint = fingerprint + '\n isMobileIOS : ' + client.isMobileIOS();
        fingerprint = fingerprint + '\n isIphone : ' + client.isIphone();
        fingerprint = fingerprint + '\n isIpad : ' + client.isIpad();
        fingerprint = fingerprint + '\n isIpod : ' + client.isIpod();
        fingerprint = fingerprint + '\n getScreenPrint : ' + client.getScreenPrint();
        fingerprint = fingerprint + '\n getColorDepth : ' + client.getColorDepth();
        fingerprint = fingerprint + '\n getCurrentResolution : ' + client.getCurrentResolution();
        fingerprint = fingerprint + '\n getAvailableResolution : ' + client.getAvailableResolution();
        fingerprint = fingerprint + '\n getDeviceXDPI : ' + client.getDeviceXDPI();
        fingerprint = fingerprint + '\n getDeviceYDPI : ' + client.getDeviceYDPI();
        fingerprint = fingerprint + '\n getPlugins : ' + client.getPlugins();
        fingerprint = fingerprint + '\n isJava : ' + client.isJava();
        fingerprint = fingerprint + '\n getJavaVersion : ' + client
            .getJavaVersion(); // functional only in java and full builds, throws an error otherwise
        fingerprint = fingerprint + '\n isFlash : ' + client.isFlash();
        fingerprint = fingerprint + '\n getFlashVersion : ' + client
            .getFlashVersion(); // functional only in flash and full builds, throws an error otherwise
        fingerprint = fingerprint + '\n isSilverlight : ' + client.isSilverlight();
        fingerprint = fingerprint + '\n getSilverlightVersion : ' + client.getSilverlightVersion();
        fingerprint = fingerprint + '\n getMimeTypes : ' + client.getMimeTypes();
        fingerprint = fingerprint + '\n isMimeTypes : ' + client.isMimeTypes();
        fingerprint = fingerprint + '\n isFont : ' + client.isFont();
        fingerprint = fingerprint + '\n getFonts : ' + client.getFonts();
        fingerprint = fingerprint + '\n isLocalStorage : ' + client.isLocalStorage();
        fingerprint = fingerprint + '\n isSessionStorage : ' + client.isSessionStorage();
        fingerprint = fingerprint + '\n isCookie : ' + client.isCookie();
        fingerprint = fingerprint + '\n getTimeZone : ' + client.getTimeZone();
        fingerprint = fingerprint + '\n getLanguage : ' + client.getLanguage();
        fingerprint = fingerprint + '\n getSystemLanguage : ' + client.getSystemLanguage();
        fingerprint = fingerprint + '\n isCanvas : ' + client.isCanvas();
        fingerprint = fingerprint + '\n getCanvasPrint : ' + client.getCanvasPrint();
        // console.log(fingerprint);
    </script>
   

    @yield('custom-scripts')
    @include('master_search.script')
</body>

</html>
