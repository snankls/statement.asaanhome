<!-- Start Page content -->
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="card-box">
					
                    <?php if ($current_role_id != 6) { ?>
                    <div class="row">
						<div class="col-12 text-right"><a href="<?php echo site_url('inventory/add'); ?>" class="btn btn-info waves-effect">Create New <?php echo $title; ?></a></div>
					</div><br>
                    <?php } ?>
                    
					<div id="list" class="table-responsive">
                        <table id="dt-table" class="table table-striped table-hover page_data_table">
                            <thead>
                                <tr class="header_columns">
                                    <th data-data="counter" style="min-width: 20px; max-width: 20px; text-align:center;">#</th>
                                    <th data-data="project_name">Project Name</th>
                                    <th data-data="property_type">Property Type</th>
                                    <th data-data="floor_block">Floor/Block</th>
                                    <th data-data="unit_number">Unit Number</th>
                                    <th data-data="payment_plan_inv">Payment Plan</th>
                                    <th data-data="unit_size">Unit Size</th>
                                    <th data-data="unit_category">Unit Category</th>
                                    <th data-data="total_price">Total Price</th>
                                    <th data-data="inventory_status">Status</th>
                                    <th data-data="create_date">Created By</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
					</div>
				</div>
			</div>
		</div>
		<!-- end row -->
	</div> <!-- container -->
</div> <!-- content -->

<script>
var table;

$(document).ready(function() {
    table = initializeDataTable(site_url + "inventory/inventory_list");
});

// Delete Record 
function delete_record(id) {
    if (confirm("Do you want to delete this record permanently?")) {
        var o = new Object;
        o.delete_id = id;
        $.post(site_url + 'inventory/inventory_delete/', o, function(result) {
            if (result.msg === "SUCCESS") {
                table.ajax.reload(null, false);
            } else {
                alert(result.data);
            }
        }, "json");
    }
    return false;
}
</script>
