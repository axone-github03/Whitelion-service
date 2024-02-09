<div class="modal fade" id="modalLeadLog" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" role="dialog" aria-labelledby="modalInquiryLogLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLeadLogLabel">Lead List</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="min-height:100%;">
                <div class="row text-center mb-3">
                    <div class="col-3">
                        <h5 class="mb-0" id="totalLead">0</h5>
                        <button class="btn btn-primary btn-sm inquiry-log-active" id="btnLeadLogTotal">Total
                            Lead</button>
                    </div>
                    <div class="col-3">
                        <h5 class="mb-0" id="totalRunningLead">0</h5>
                        <button class="btn btn-primary btn-sm" id="btnLeadLogRunning">Running Lead</button>
                    </div>
                    <div class="col-3">
                        <h5 class="mb-0" id="totalWonLead">0</h5>
                        <button class="btn btn-primary btn-sm" id="btnLeadLogWon">Won Lead</button>
                    </div>
                    <div class="col-3">
                        <h5 class="mb-0" id="totalRejectedLead">0</h5>
                        <button class="btn btn-primary btn-sm" id="btnLeadLogLost">Lost Lead</button>
                    </div>
                </div>
                <div class="float-end">
                    <button type="button" class="btn-sm btn btn-outline-dark waves-effect waves-light float-end"
                        aria-haspopup="true" aria-expanded="false">Quotation Amount: <span
                            id="totalLeadLogQuotationAmount"></span></button>
                </div>

                <table id="LeadLogTable" class="table align-middle table-nowrap mb-0 w-100">
                    <thead>
                        <tr>
                            <th>#Id</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Quotation Amount</th>
                            <th id="user_type_column"></th>
                            <th id="user_type_column1"></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



<script type="text/javascript">
    
</script>
