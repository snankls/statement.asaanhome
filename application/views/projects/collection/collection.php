<!-- Start Page content -->
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="card-box">
                	<div id="list">
                        <div class="table-responsive">
                            <table id="dt-table" class="table table-striped table-hover page_data_table">
                                <thead>
                                    <tr class="header_columns">
                                        <th data-data="counter" style="min-width: 20px; max-width: 20px; text-align:center;">#</th>
                                        <th data-data="challan_date">Paid Date</th>
                                        <th data-data="challan_amount">Amount</th>
                                        <th data-data="customer_name">Customer Name</th>
                                        <th data-data="cnic">CNIC</th>
                                        <th data-data="booking_unit">Unit Number</th>
                                        <th data-data="serial">Serial</th>
                                        <th data-data="payment_method">Payment Method</th>
                                        <th data-data="reference">Reference</th>
                                        <th data-data="create_date">Create Date</th>
                                        <th data-data="challan_download" style="min-width: 160px; max-width: 160px; position: relative; text-align: center;">Action</th>
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

<!--Challan Edit Modal-->
<div class="modal fade" id="challanModal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Challan View</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body" id="challan-view-list">
				
			</div>
		</div>
	</div>
</div>

<script>
//Customer Receipt
function challan_receipt(challan_id) {
	const url = site_url + "pdf/challan_receipt?challan_id=" + challan_id;
    window.open(url, '_blank');
}

//Challan View
function challan_view(id){
	var o = new Object();
	o.challan_id = id;
	$('#challanModal').modal('show');
	$("#challan-view-list").html(loader_small());
	$("#challan-view-list").load(site_url + "collection/challan_view/", o);
}

$(document).ready(function() {
    initializeDataTable(site_url + "collection/collection_list");
});
</script>
