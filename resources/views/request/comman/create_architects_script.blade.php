<script type="text/javascript">
    var ajaxURLSearchCustomer = '{{ route('request.search.customer') }}'
    var csrfToken = $("[name=_token]").val();
    var ajaxURLAjax = '{{ route('request.ajax') }}'
    var architectPageLength = getCookie('architectPageLength') !== undefined ? getCookie('architectPageLength') : 10;



    $('.close_button').on('click', function() {
        $('#audio_url').attr('src', "");
    })

    $("#addBtnUser").click(function() {

        $('#user_email').attr('disabled', true);
        $('.disable input').attr('disabled', true);
        $('.disable select').attr('disabled', true);
        $('#btnSaveFinal').attr('disabled', true);
        $('#phone_no_validation').hide();
        $('#phone_no_error_dialog').hide();
        resetInputForm();
        $('#phone_no_validation').hide();


        $("#modalUserLabel").html("Service Request Form");
        $("#user_id").val(0);
        $(".loadingcls").hide();
        $(".formUser").show();
        $('#architect_status_div').hide();
        $('#architect_instagram_div').hide();
        $('#architect_recording_div').hide();
        $("#div_source_text").hide();
        $("#div_source_user").hide();
        $("#architect_source_type").trigger('change');



        $('#flexRadioDefaultDiv1').removeClass('pe-none');
        $('#flexRadioDefaultDiv2').removeClass('pe-none');
        $('#user_city_id_div').removeClass('pe-none');
        $('#architect_source_type_div').removeClass('pe-none');
        $('#architect_source_name_div').removeClass('pe-none');
        $('#architect_sale_person_id_div').removeClass('pe-none');
        // $('#architect_anniversary_date_div').removeClass('pe-none');
        // $('#architect_birth_date_div').removeClass('pe-none');
        $('#architect_visiting_card_input_div').removeClass('pe-none');
        $('#architect_aadhar_card_input_div').removeClass('pe-none');
        $('#architect_pan_card_input_div').removeClass('pe-none');
        $('.change_color .select2-selection--single').css('background-color', '#fff');

        $('#user_first_name').attr('readonly', false);
        $('#user_last_name').attr('readonly', false);
        $('#user_phone_number').attr('readonly', false);
        $('#user_email').attr('readonly', false);
        $('#user_house_no').attr('readonly', false);
        $('#user_address_line1').attr('readonly', false);
        $('#user_address_line2').attr('readonly', false);
        $('#user_area').attr('readonly', false);
        $('#user_pincode').attr('readonly', false);
        $('#architect_principal_architect_name').attr('readonly', false);
        $('#architect_firm_name').attr('readonly', false);
        // $('#architect_birth_date').attr('readonly', false);
        // $('#architect_anniversary_date').attr('readonly', false);
        $('#architect_visiting_card').attr('readonly', false);
        $('#architect_aadhar_card').attr('readonly', false);
        $('#architect_pan_card').attr('readonly', false);
        $('#architect_source_text').attr('readonly', false);

    });

    function resetInputForm() {

        $("#formUser").removeClass('was-validated');
        $('#formUser').trigger("reset");
        // $("#btnNext").show();
        // $("#btnSave").hide();

        // $('#v-pills-tab.nav a:first').tab('show');
        // $("#btnNext").show();
        // $("#btnSave ").hide();

        $("#architect_source_type").empty().trigger('change');
        $("#architect_source_name").empty().trigger('change');
        $("#user_status").empty().trigger('change');

        $('.nav a:first').tab('show');
        $("#user_status").select2("val", "1");
        $("#user_country_id").select2("val", "1");
        $("#user_state_id").empty().trigger('change');
        $("#user_city_id").empty().trigger('change');
        $("#architect_sale_person_id").empty().trigger('change');
        $("#architect_visiting_card_file").html("");
        $("#architect_aadhar_card_file").html("");
        $("#architect_pan_card_file").html("");

        $("#btnSaveFinal").prop('disabled', false);
        $("#btnSaveFinal").html("Save");
        // $("#formUser input:not([type=hidden]").prop('disabled', true);
        // $('#formUser select').select2("enable", false);
        // $("#divFooter").hide();



    }


    $("#req_customer").select2({
        ajax: {
            url: ajaxURLSearchCustomer,
            dataType: 'json',
            delay: 0,
            data: function(params) {
                return {
                    q: params.term, // search term
                    page: params.page
                };
            },
            processResults: function(data, params) {
                // parse the results into the format expected by Select2
                // since we are using custom formatting functions we do not need to
                // alter the remote JSON data, except to indicate that infinite
                // scrolling can be used
                params.page = params.page || 1;

                return {
                    results: data.results,
                    pagination: {
                        more: (params.page * 30) < data.total_count
                    }
                };
            },
            cache: false
        },
        placeholder: 'Search for status',
        dropdownParent: $("#modalUser .modal-content")
    });
    $("#req_person_type").select2({
        placeholder: 'Search for status',
        dropdownParent: $("#modalUser .modal-content")
    });

    var table = $('#datatable').DataTable({
        scrollX: true,
        "aoColumnDefs": [{
            "bSortable": false,
            "aTargets": [6]
        }],
        "order": [
            [0, 'desc']
        ],
        "processing": true,
        "serverSide": true,
        "pageLength": architectPageLength,
        "ajax": {
            "url": ajaxURLAjax,
            "type": "POST",
            "data": {
                "_token": csrfToken,
            }
        },
        "aoColumns": [{
                "mData": "name"
            },
            {
                "mData": "number"
            },
            {
                "mData": "location"
            },
            {
                "mData": "status"
            },
            {
                "mData": "date"
            },
            {
                "mData": "customer"
            },
            {
                "mData": "created_by"
            },
            {
                "mData": "reqdate"
            },


        ],
        "drawCallback": function() {

            seachUserId = "";

            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {

                return new bootstrap.Tooltip(tooltipTriggerEl)
            })



        }

    });

    $('#datatable').on('length.dt', function(e, settings, len) {
        setCookie('architectPageLength', len, 100);
    });

    $(document).ready(function() {
        var options = {
            beforeSubmit: showRequest,
            success: showResponse
        };

        $('#formUser').ajaxForm(options);

    });

    function showRequest(formData, jqForm, options) {

        var queryString = $.param(formData);
        $("#formUserCall .loadingcls").hide();
        $(".save-btn").html("Saving...");
        $(".save-btn").prop("disabled", true);

        $("#btnSaveFinal").html("Saving...");
        $("#btnSaveFinal").prop('disabled', true);
        return true;
    }

    // post-submit callback
    function showResponse(responseText, statusText, xhr, $form) {

        $(".save-btn").html("Save");
        $(".save-btn").prop("disabled", false);
        console.log($form[0]['id']);
        $("#btnSaveFinal").prop('disabled', false);
        $("#btnSaveFinal").html("Save");

        if ($form[0]['id'] == "formUser") {
            if (responseText['status'] == 1) {
                toastr["success"](responseText['msg']);
                reloadTable();
                resetInputForm();
                // getDataDetail(responseText['user_id']);
                $("#modalUser").modal('hide');

            } else {
                $('#phone_no_error_dialog').show();
                $('#error_text').text(responseText['msg']);

            }

        }
    }

    function reloadTable() {
        table.ajax.reload();
    }
</script>
