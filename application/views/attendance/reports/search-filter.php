<div class="row">
    <div class="col-5"><a href="javascript:;" class="btn btn-info btn-small waves-effect" id="toggle-filter">Filter Show/Hide</a></div>
</div><br>

<div id="filter-form" style="display: none;">
    <form id="leads-form">
        <div class="form-row clearfix">
        	<div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
                <label class="col-form-label">Date Range</label>
                <input type="text" id="next-followup-date" class="form-control date-range">
            </div>
        	<div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
                <label class="col-form-label">Users</label>
                <select class="form-control user-id">
                    <option value="">Select One</option>
                    <?php foreach($users_list as $data): ?>
                    <option value="<?php echo $data->user_id; ?>"><?php echo $data->fullname; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        
        <div class="form-row clearfix">
            <div class="form-group col-lg-3 col-xs-12">
            	<button type="button" class="btn btn-info waves-effect" id="search-leads">Search</button> &ensp;
                <button type="reset" class="btn btn-info waves-effect" id="reset-filters">Reset</button>
            </div>
        </div>
    </form><br />
</div>

<script>
$(document).ready(function(e) {
    // Toggle form visibility
    $('#toggle-filter').on('click', function() {
        $('#filter-form').toggle();
    });
});
</script>