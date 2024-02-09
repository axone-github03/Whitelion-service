@extends('layouts.main')
@section('title', $data['title'])
@section('content')



                <div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Country List

                                    </h4>



                                </div>


                            </div>
                        </div>
                        <!-- end page title -->


                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">



                                        <table id="datatable" class="table table-striped dt-responsive  nowrap w-100">
                                            <thead>
                                            <tr>
                                                <th>Id</th>
                                                <th>Country Code</th>
                                                <th>Country Name</th>
                                                <th>Country Flag</th>
                                                <th>Date & Time</th>


                                            </tr>
                                            </thead>


                                            <tbody>

                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->

                    </div>
                    <!-- container-fluid -->
                </div>
                <!-- End Page-content -->






    @csrf
@endsection('content')
@section('custom-scripts')
<script type="text/javascript">


    var ajaxURL='{{route('countrylist.ajax')}}';
    var csrfToken=$("[name=_token").val();
    var locationPageLength= getCookie('locationPageLength')!==undefined?getCookie('locationPageLength'):10;


var table=$('#datatable').DataTable({
  "aoColumnDefs": [{ "bSortable": false, "aTargets": [] }],
  "order":[[ 0, 'desc' ]],
  "processing": true,
  "serverSide": true,
  "pageLength": locationPageLength,
  "ajax": {
    "url": ajaxURL,
    "type": "POST",
     "data": {
        "_token": csrfToken,
        }


  },
  "aoColumns" : [
    {"mData" : "id"},
    {"mData" : "code"},
    {"mData" : "name"},
    {"mData" : "flag"},
    {"mData" : "created_at"}



  ]
});

$('#datatable').on( 'length.dt', function ( e, settings, len ) {

    setCookie('locationPageLength',len,100);


});


</script>
@endsection