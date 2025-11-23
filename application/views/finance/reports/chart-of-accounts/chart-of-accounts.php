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
                                <select name="project_id" id="project_id" class="form-control required">
                                    <option value="">Select One</option>
                                    <?php foreach($project_list as $data): ?>
                                    <option value="<?php echo $data->project_id; ?>"><?php echo $data->project_name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-lg-3 col-xs-12">
                                <label class="col-form-label">Select Level </label>
                                <select name="coa_level" id="coa_level" class="form-control">
                                    <option value="">Select One</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                </select>
                            </div>
                            <div class="form-group col-12">
                                <button type="button" class="btn btn-custom waves-effect waves-light" onClick="report_search();">Search</button>
                            </div>
                            <div class="clearfix"></div><br/>
                        </div>
                    </form>
                    
                    <div id="list"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
//Search Complaints
function report_search(){
	var o = new Object();
	o.project_id = $('#project_id').val();
	o.coa_level = $('#coa_level').val();
	
	$('#list').html(loader_big());
	$("#list").load(site_url + "reports/chart_of_account_search/", o);
}

//COA Download
function coa_download() {
    var project_id = $('#project_id').val();
    var coa_level = $('#coa_level').val();

    const url = site_url + "pdf/coa_download?project_id=" + project_id + "&coa_level=" + coa_level;
    window.open(url, '_blank');
}

// function coa_download() {
//     var o = new Object();
//  	o.project_id = $('#project_id').val();
//  	o.coa_level = $('#coa_level').val();
//  	$('#pdf-form [name="project_id"]').val(o.project_id);
	
//  	$('#pdf-form').submit();
// }
</script>
