<!-- Start Page content -->
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="card-box">
                	<?php if ( $current_role_id != 6 ){ ?>
					<div class="row">
						<div class="col-12 text-right"><a href="<?php echo site_url('voucher/add'); ?>" class="btn btn-info waves-effect">Create New <?php echo $title; ?></a></div>
					</div><br>
                    <?php } ?>
                    
					<div id="list">
                        <div class="table-responsive">
                            <table id="dt-table" class="table table-striped table-hover page_data_table">
                                <thead>
                                    <tr class="header_columns">
                                        <th data-data="counter" style="min-width: 20px; max-width: 20px; text-align:center;">#</th>
                                        <th data-data="voucher_id" width="80">Voucher ID</th>
                                        <th data-data="transaction_type">Transaction Type</th>
                                        <th data-data="project_name">Project Name</th>
                                        <th data-data="voucher_date">Voucher Date</th>
                                        <th data-data="total_debit">Total Debit</th>
                                        <th data-data="total_credit">Total Credit</th>
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
		</div>
		<!-- end row -->
	</div> <!-- container -->
</div> <!-- content -->

<script>
//Delete Record
function delete_record(update_id){
	if (confirm("do you want to delete permanent?")) {
		var o = new Object;
		o.update_id = update_id;
		$.post( site_url + 'voucher/voucher_delete/', o, function(result)
		{
			if( result.msg == "SUCCESS" ){
				location.reload();
			}else{
				alert(result.data);
			}
		},"json");
    }
	return false;
}

$(document).ready(function() {
    initializeDataTable(site_url + "voucher/voucher_list");
});
</script>
