@extends('layouts.main')
@section('title', $data['title'])
@section('content')
    <style type="text/css">
        td p {
            max-width: 100%;
            white-space: break-spaces;
            word-break: break-all;
        }

        td {
            vertical-align: middle;
        }

        p .closing-badge {
            border-radius: 0.25rem !important;
            background: rgb(239 242 247);
            padding: 5px 4px;
        }



        div.div_tip {
            /* min-width: 100%; */
            display: none;
            background: #bbbefcf0;
            position: absolute;
            /* z-index: -1; */
            border-radius: 5px;
            -moz-border-radius: 5px;
            box-shadow: 0px 1px 2px #888888;
            -moz-box-shadow: 0px 1px 2px #888888;
        }

        div.div_tip:hover,
        {
        display: block;
        }

        .closing-badge:hover+.div_tip {
            display: block !important;
            z-index: 999;
        }

        div.div_tip .tip_arrow {
            position: absolute;
            /*top: 100%;*/
            /*left: 50%;*/
            border: solid transparent;
            height: 0;
            width: 0;
            pointer-events: none;
        }

        div.div_tip .tip_arrow {
            /*border-color: rgba(62, 83, 97, 0);*/
            /*border-top-color: #3e5361;*/
            border-width: 10px;
            /*margin-left: -10px; */
        }
    </style>

    <div class="page-content">
        <div class="container-fluid">

            <div id="table_list">
                {{-- <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">Architects </h4>
                            <div class="page-title-right">
                                
                            </div>
                        </div>
                    </div>
                </div> --}}
                <!-- end page title -->
                <!-- start row -->
                <div class="col-12 text-end p-0 mb-3">
                    <button id="addBtnUser" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal"
                        data-bs-target="#modalUser" role="button"><i
                            class="bx bx-plus font-size-16 align-middle me-2"></i>Request</button>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">

                                <table id="datatable" class="table table-striped dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>Client Name </th>
                                            <th>Contect Number</th>
                                            <th>Location </th>
                                            <th>Status</th>
                                            <th>Preferable <br>Date & Time</th>
                                            <th>Assigned Service <br> Coordiator</th>
                                            <th>Created By</th>
                                            <th>Req.Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>

                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->

    @csrf
    @include('request/comman/create_architects_modal');
@endsection('content')
@section('custom-scripts')

    <script src="{{ asset('assets/libs/parsleyjs/parsley.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-validation.init.js') }}"></script>
    <script src="{{ asset('assets/js/pages/jquery.form.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/libs/summernote/summernote.min.js') }}"></script>
    <script src="text/javascript">
        var csrfToken = $("[name=_token]").val();
        var ajaxURLAjax = '{{ route('request.ajax') }}'

       
    </script>
    @include('request/comman/create_architects_script');

@endsection
