<!-- Start Page content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card-box">
                    <form id="finance-ledger-form">
                        <div class="form-row">
                            <div class="form-group col-lg-3 col-xs-12">
                                <label class="col-form-label">Select Project </label>
                                <select name="project_id" id="project_id" class="form-control project-dropdown required" onchange="CF.FinanceQueryChanged(this);">
                                    <option value="">Select One</option>
                                    <?php foreach($project_list as $data): ?>
                                    <option value="<?php echo $data->project_id; ?>"><?php echo $data->project_name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-lg-3 col-xs-12">
                                <label class="col-form-label">Type Query </label>
                                <select class="form-control query-dropdown select2 required" id="query">
                                    <option value="">Select One</option>
                                    
                                </select>
                            </div>
                            <div class="form-group col-lg-3 col-xs-12">
                                <label class="col-form-label">From Date</label>
                                <input type="text" id="from-date" class="form-control datepicker">
                            </div>
                            <div class="form-group col-lg-3 col-xs-12">
                                <label class="col-form-label">To Date</label>
                                <input type="text" id="to-date" class="form-control datepicker">
                            </div>
                            <div class="form-group col-12">
                                <button type="button" class="btn btn-custom waves-effect waves-light" onClick="report_search();">Search</button>
                            </div>
                            <div class="clearfix"></div><br/>
                        </div>
                    </form>
                    
                    <div id="search">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
//Search Complaints
function report_search(){
	var o = new Object();
	o.project_id = $('#project_id').val();
	o.query		= $('#query option:selected').val();
	o.from_date	= $('#from-date').val();
	o.to_date	= $('#to-date').val();
	
	$('#search').html(loader_big());
	$("#search").load(site_url + "reports/finance_ledger_search/", o);
}

//Finance Ledger
function finance_ledger_download(voucher_id) {
    const url = site_url + "pdf/voucher_download?booking_id=" + voucher_id;
    window.open(url, '_blank');


	// var o = new Object();
	// o.voucher_id = obj;
	// $(this).load(site_url + "pdf/voucher_download/", o);
}
</script>