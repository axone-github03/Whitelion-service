<div class="modal fade" id="modalPointLog" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" role="dialog"
    aria-labelledby="modalPointLogLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPointLogLabel"> Point Log</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="min-height:100%;">


                <table id="pointLogTable" class="table align-middle table-nowrap mb-0 w-100">
                    <thead>
                        <tr>

                            <th>Log</th>




                        </tr>
                    </thead>


                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalInquiryLog" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1"
    role="dialog" aria-labelledby="modalInquiryLogLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalInquiryLogLabel"> Inquiry List</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="min-height:100%;">

                <div class="row text-center mb-3">
                    <div class="col-3">
                        <h5 class="mb-0" id="totalInquiry">0</h5>
                        <button class="btn btn-primary btn-sm" id="btnInquiryLogTotal">Total Inquiry</button>
                    </div>
                    <div class="col-3">
                        <h5 class="mb-0" id="totalRunningInquiry">0</h5>
                        <button class="btn btn-primary btn-sm" id="btnInquiryLogRunning">Running Inquiry</button>
                    </div>
                    <div class="col-3">
                        <h5 class="mb-0" id="totalWonInquiry">0</h5>
                        <button class="btn btn-primary btn-sm" id="btnInquiryLogWon">Won Inquiry</button>
                    </div>
                    <div class="col-3">
                        <h5 class="mb-0" id="totalRejectedInquiry">0</h5>
                        <button class="btn btn-primary btn-sm" id="btnInquiryLogLost">Lost Inquiry</button>
                    </div>
                </div>

                <div class="float-end">

                    <button type="button" class="btn-sm btn btn-outline-dark waves-effect waves-light float-end"
                        aria-haspopup="true" aria-expanded="false">Quotation Amount: <span
                            id="totalInquiryLogQuotationAmount"></span></button>
                </div>
                <table id="InquiryTable" class="table align-middle table-nowrap mb-0 w-100">
                    <thead>
                        <tr>

                            <th>#Id</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Quotation Amount</th>
                            <th>Electrician</th>
                            <th>Channel Partner</th>

                        </tr>
                    </thead>


                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>






<div class="modal fade" id="modalUser" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" role="dialog"
    aria-labelledby="modalUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalUserLabel"> Service Request Form</h5>
                <button type="button" class="btn-close close_button" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body" style="min-height:100%;">
                <div class="col-md-12 text-center loadingcls">
                    <button type="button" class="btn btn-light waves-effect">
                        <i class="bx bx-hourglass bx-spin font-size-16 align-middle me-2"></i> Loading...
                    </button>
                </div>
                <form id="formUser" action="{{ route('request.save') }}" method="POST" class="needs-validation"
                    novalidate>
                    @csrf
                    <input type="hidden" name="request_id" id="request_id">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-body section_user_detail">
                                <div class="row">
                                    <div style="display: none;" id="phone_no_error_dialog">
                                        <div class="col-6 text-center d-flex justify-content-center m-auto"
                                            style="height: 60px; line-height: 60px;">
                                            <div class="phone_error danger">
                                                <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink"
                                                    xmlns:svgjs="http://svgjs.com/svgjs" width="512"
                                                    height="512" x="0" y="0" viewBox="0 0 512 512"
                                                    style="enable-background:new 0 0 512 512" xml:space="preserve"
                                                    class="">
                                                    <g>
                                                        <g data-name="Layer 2">
                                                            <circle cx="256" cy="256" r="256"
                                                                fill="#ffffff" data-original="#ff2147" opacity="1"
                                                                class=""></circle>
                                                            <g fill="#fff">
                                                                <path
                                                                    d="M256 307.2a35.89 35.89 0 0 1-35.86-34.46l-4.73-119.44a35.89 35.89 0 0 1 35.86-37.3h9.46a35.89 35.89 0 0 1 35.86 37.3l-4.73 119.44A35.89 35.89 0 0 1 256 307.2z"
                                                                    fill="#bd3630" data-original="#ffffff"
                                                                    class="" opacity="1"></path>
                                                                <rect width="71.66" height="71.66" x="220.17"
                                                                    y="324.34" rx="35.83" fill="#bd3630"
                                                                    data-original="#ffffff" class=""
                                                                    opacity="1"></rect>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </svg>
                                                <span id="error_text">This Phone Number Is Alredy Register</span>
                                                <i class="bx bx-x-circle ms-2" id="close_phone_no_error_dialog"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3 change_color" id="req_customer_div">
                                        <label for="req_customer" class="col-2 col-form-label">Customer</label>
                                        <div class="col-6">
                                            <select class="form-control select2-ajax" id="req_customer"
                                                name="req_customer"></select>
                                            <div class="invalid-feedback">Please select Customer</div>
                                        </div>
                                    </div>

                                    <h4 class="mb-3" style="font-weight: 600 !important;">
                                        Customer Details</h4>

                                    <div class="col-md-6">

                                        <div class="row mb-1">
                                            <label for="insert_phone_number" class="col-sm-4 col-form-label">Contact
                                                No <code class="highlighter-rouge">*</code></label>
                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <div class="input-group-text">+91</div>
                                                    <input type="number" class="form-control" id="req_phone_number"
                                                        name="req_phone_number" placeholder="Phone number"
                                                        value="" maxlength="10">
                                                    <div class="col-12 text-danger" id="phone_no_validation"
                                                        style="display: none;">This Phone Number Is Alredy Register
                                                    </div>
                                                </div>
                                            </div>
                                        </div>



                                        <div class="row mb-1">
                                            <label for="req_first_name" class="col-sm-4 col-form-label">Client Name
                                                <code class="highlighter-rouge">*</code></label>
                                            <div class="col-sm-8">
                                                <input class="form-control" id="req_first_name" name="req_first_name"
                                                    placeholder="Client Name" value="">
                                            </div>
                                        </div>


                                        <div class="row mb-1 change_color" id="">
                                            <label for="architect_sale_person_id"
                                                class="col-sm-4 col-form-label">Electrician <code
                                                    class="highlighter-rouge">*</code></label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control"
                                                    placeholder="Electrician name" name="req_electrician_name"
                                                    id="req_electrician_name">

                                            </div>
                                            <div class="col-sm-4">
                                                <input type="number" class="form-control"
                                                    placeholder="Electrician Number" name="req_electrician_number"
                                                    id="req_electrician_number">

                                            </div>
                                        </div>

                                        <div class="row mb-1 change_color" id="">
                                            <label for="architect_sale_person_id"
                                                class="col-sm-4 col-form-label">Point Of Contact <code
                                                    class="highlighter-rouge">*</code></label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" placeholder="Contact name"
                                                    name="req_point_name" id="req_point_name">

                                            </div>
                                            <div class="col-sm-4">
                                                <input type="number" class="form-control"
                                                    placeholder="Contact number" name="req_point_number"
                                                    id="req_point_number">

                                            </div>
                                        </div>
                                        <div class="row mb-1 change_color" id="req_person_type_div">
                                            <label for="req_person_type" class="col-sm-4 col-form-label">Request
                                                Person
                                                Type</label>
                                            <div class="col-sm-8">
                                                <select class="form-control select2-ajax" id="req_person_type"
                                                    name="req_person_type">
                                                    <option value="1">123</option>
                                                </select>

                                                <div class="invalid-feedback">Please select Person Type</div>
                                            </div>
                                        </div>


                                        <div class="row mb-3 mt-3">
                                            <label for="req_type_id" class="col-sm-4 col-form-label">Request Type</label>
                                            <div class="form-check col-3 ps-3" id="flexRadioDefaultDiv1">
                                                <input class="form-check-input ms-0 me-2" type="checkbox" name="request_type[]" id="flexRadioDefault1" value="1">
                                                <label class="form-check-label" for="flexRadioDefault1">Installation</label>
                                            </div>
                                            <div class="form-check col-2" id="flexRadioDefaultDiv2">
                                                <input class="form-check-input " type="checkbox" name="request_type[]" id="flexRadioDefault2" value="2">
                                                <label class="form-check-label" for="flexRadioDefault2">Setup</label>
                                            </div>
                                            <div class="form-check col-2" id="flexRadioDefaultDiv3">
                                                <input class="form-check-input" type="checkbox" name="request_type[]" id="flexRadioDefault3" value="3">
                                                <label class="form-check-label" for="flexRadioDefault3">Complaint</label>
                                            </div>
                                        </div>
                                        <div class="row mb-1 change_color" id="">
                                            <label for="architect_sale_person_id"
                                                class="col-sm-4 col-form-label">Power Type<code
                                                    class="highlighter-rouge">*</code></label>
                                            <div class="col-sm-8">
                                                <input type="number" class="form-control"
                                                    placeholder="Enter Power Type" name="req_power_type"
                                                    id="req_power_type">

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="row mb-1">
                                            <label for="horizontal-firstname-input"
                                                class="col-sm-4 col-form-label">Site Address <code
                                                    class="highlighter-rouge">*</code></label>
                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <div class="input-group-text" style="padding: 0;">
                                                        <input type="text" style="width: 80px;"
                                                            class="form-control" id="req_house_no"
                                                            name="req_house_no" placeholder="H.No" value="">
                                                    </div>
                                                    <input class="form-control" id="req_address_line1"
                                                        name="req_address_line1" placeholder="first Address"
                                                        value="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-1 ">
                                            <label for="req_address_line2" class="col-sm-4 col-form-label"></label>
                                            <div class="col-sm-8">
                                                <input class="form-control" id="req_address_line2"
                                                    name="req_address_line2" placeholder="Second Address"
                                                    value="">
                                            </div>
                                        </div>

                                        <div class="row mb-1">
                                            <label for="horizontal-firstname-input"
                                                class="col-sm-4 col-form-label"></label>
                                            <div class="col-sm-4">
                                                <input class="form-control" id="req_area" name="req_area"
                                                    placeholder="Area" value="">
                                            </div>
                                            <div class="col-sm-4">
                                                <input class="form-control" id="req_city" name="req_city"
                                                    placeholder="City" value="">
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <label for="horizontal-firstname-input"
                                                class="col-sm-4 col-form-label"></label>
                                            <div class="col-sm-4">
                                                <input class="form-control" id="req_state" name="req_state"
                                                    placeholder="State" value="">
                                            </div>
                                            <div class="col-sm-4">
                                                <input class="form-control" id="req_pincode" name="req_pincode"
                                                    placeholder="Pincode" value="">
                                            </div>
                                        </div>

                                        <div class="row mb-1" id="req_quotation_div">
                                            <label for="req_quotation" class="col-sm-4 col-form-label">Quotation PDF
                                                </br>
                                                <span id="req_quotation_file"></span></label>
                                            <div class="col-sm-8" id="req_quotation_input_div">
                                                <input class="form-control" type="file" value=""
                                                    id="req_quotation" name="req_quotation">
                                            </div>
                                        </div>
                                        <div class="row mb-1" id="req_note_div">
                                            <label for="req_note" class="col-sm-4 col-form-label">Notes</label>
                                            <div class="col-sm-8">
                                                <input class="form-control" type="text" id="req_note"
                                                    name="req_note" placeholder="Notes" value="">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="row mb-3 mt-3">
                                    <label for="req_send_customer" class="col-sm-2 col-form-label">Send to Customer</label>
                                    <div class="form-check col-2 ps-3" id="flexRadioDefaultDiv4">
                                        <input class="form-check-input ms-0 me-2" type="checkbox" name="req_send_customer[]" id="flexRadioDefault4" value="1">
                                        <label class="form-check-label" for="flexRadioDefault4">Installation</label>
                                    </div>
                                    <div class="form-check col-2" id="flexRadioDefaultDiv5">
                                        <input class="form-check-input " type="checkbox" name="req_send_customer[]" id="flexRadioDefault5" value="2">
                                        <label class="form-check-label" for="flexRadioDefault5">Setup</label>
                                    </div>
                                    <div class="form-check col-2" id="flexRadioDefaultDiv6">
                                        <input class="form-check-input" type="checkbox" name="req_send_customer[]" id="flexRadioDefault6" value="3">
                                        <label class="form-check-label" for="flexRadioDefault6">Complaint</label>
                                    </div>
                                    <div class="form-check col-2" id="flexRadioDefaultDiv7">
                                        <input class="form-check-input" type="checkbox" name="req_send_customer[]" id="flexRadioDefault7" value="4">
                                        <label class="form-check-label" for="flexRadioDefault7">Complaint</label>
                                    </div>
                                    <div class="form-check col-2" id="flexRadioDefaultDiv8">
                                        <input class="form-check-input" type="checkbox" name="req_send_customer[]" id="flexRadioDefault8" value="5">
                                        <label class="form-check-label" for="flexRadioDefault8">Complaint</label>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer" id="divFooter">
                                <div id="btnSave">
                                    <button type="button" class="btn btn-light close_button"
                                        data-bs-dismiss="modal">Close</button>
                                    <button id="btnSaveFinal" onclick="saveArchitect()"
                                        class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
