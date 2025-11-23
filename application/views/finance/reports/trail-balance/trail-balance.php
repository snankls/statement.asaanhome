<!-- Start Page content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card-box">
                    <form id="complains-form">
                        <div class="form-row">
                            <div class="form-group col-lg-4 col-xs-12">
                                <label class="col-form-label">From Date</label>
                                <input type="text" id="from-date" class="form-control datepicker">
                            </div>
                            <div class="form-group col-lg-4 col-xs-12">
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
	o.from_date	= $('#from-date').val();
	o.to_date	= $('#to-date').val();
	
	$('#search').html(loader_big());
	$("#search").load(site_url + "reports/trail_balance_search/", o);
}
</script>
