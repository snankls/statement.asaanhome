<!-- Start Page content -->
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="card-box">
					
                    <?php if ($current_role_id != 6) { ?>
                    <div class="row">
						<div class="col-12 text-right"><a href="<?php echo site_url('booking/add'); ?>" class="btn btn-info waves-effect">Create New <?php echo $title; ?></a></div>
					</div><br>
                    <?php } ?>
                    
					<div id="list">
						<div class="table-responsive">
							<table id="dt-table" class="table table-striped table-hover page_data_table">
								<thead>
									<tr class="header_columns">
										<th class="no-sort no-search" data-data="counter" style="min-width: 20px; max-width: 20px; text-align:center;">#</th>
										<th data-data="project_name" width="100">Project Name</th>
										<th class="no-search" data-data="property_type">Property Type</th>
										<th data-data="booking_unit">Unit #</th>
										<th data-data="registration">Registration</th>
										<th data-data="customer_name" width="100">Customer Name</th>
										<th data-data="total_price">Total Amount</th>
										<th class="no-search" data-data="book_amount">Booking Amount</th>
										<th class="no-search" data-data="due_amount">Due Amount</th>
										<th class="no-search" data-data="paid_amount">Paid Amount</th>
										<th class="no-search" data-data="overdue_amount">Overdue Amount</th>
										<th class="no-search" data-data="mobile">Mobile</th>
										<th data-data="landline">Landline</th>
										<th data-data="cnic">CNIC</th>
										<th data-data="agency_name" width="100">Agency Name</th>
										<th class="no-search" data-data="future_amount">Future Amount</th>
										<th data-data="agency_commission">Agency Commission</th>
										<th class="no-search" data-data="create_date">Created By</th>
										<th class="fixed_column no-search" data-data="actions" style="min-width: 110px; max-width: 110px; position: relative;">Actions</th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<th colspan="6" style="text-align:left">Grand Total:</th>
										<th class="total-price"></th>
										<th class="book-amount"></th>
										<th class="due-amount"></th>
										<th class="paid-amount"></th>
										<th class="overdue-amount"></th>
										<th colspan="4"></th>
										<th class="future-amount"></th>
										<th class="agency-commission"></th>
										<th colspan="2"></th>
									</tr>
								</tfoot>
								<tbody></tbody>
							</table>
						</div>
					</div>
					<!-- <div id="list">
                        <div class="table-responsive">
                            <table id="dt-table" class="table table-striped table-hover page_data_table">
                                <thead>
                                    <tr class="header_columns">
                                        <th class="no-sort no-search" data-data="counter" style="min-width: 20px; max-width: 20px; text-align:center;">#</th>
                                        <th data-data="project_name" width="100">Project Name</th>
                                        <th class="no-search" data-data="property_type">Property Type</th>
                                        <th data-data="booking_unit">Unit #</th>
                                        <th data-data="registration">Registration</th>
                                        <th data-data="customer_name" width="100">Customer Name</th>
                                        <th data-data="total_price">Total Amount</th>
                                        <th class="no-search" data-data="book_amount">Booking Amount</th>
                                        <th class="no-search" data-data="due_amount">Due Amount</th>
                                        <th class="no-search" data-data="paid_amount">Paid Amount</th>
                                        <th class="no-search" data-data="overdue_amount">Overdue Amount</th>
                                        <th class="no-search" data-data="mobile">Mobile</th>
                                        <th data-data="landline">Landline</th>
                                        <th data-data="cnic">CNIC</th>
                                        <th data-data="agency_name" width="100">Agency Name</th>
                                        <th class="no-search" data-data="future_amount">Future Amount</th>
                                        <th data-data="agency_commission">Agency Commission</th>
                                        <th class="no-search" data-data="create_date">Created By</th>
                                        <th class="fixed_column no-search" data-data="actions" style="min-width: 110px; max-width: 110px; position: relative;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
					</div> -->
				</div>
			</div>
		</div>
		<!-- end row -->
	</div> <!-- container -->
</div> <!-- content -->

<!--Installment Modal-->
<div class="modal fade" id="installmentModal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Add Installment</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<form id="installment-form" class="form-horizontal Form" role="form" enctype="multipart/form-data">
					<input type="hidden" name="update_id">
					<input type="hidden" name="inventory_id">
					<div class="form-group">
						<label class="col-form-label">Date</label>
						<input type="text" class="form-control datepicker required" name="amount_date">
					</div>
                    <div class="form-group">
						<label class="col-form-label">Installment Amount</label>
						<input type="text" class="form-control required" name="amount">
					</div>
					<div class="form-group">
                        <label class="col-form-label">Payment Method</label>
                        <select name="payment_method" id="payment_method" class="form-control">
                            <option value="">Select One</option>
                            <?php foreach(payment_method() as $k => $v){ ?>
                            <option value="<?=$k?>"><?=$v?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group" id="reference" style="display: none;">
                        <label class="col-form-label">Reference</label>
                        <input type="text" name="reference" class="form-control required">
                    </div>
					<div class="form-group">
						<label class="col-form-label">Image ⁠⁠⁠Proof <span class="error-message">*</span></label><br />
                        <input type="file" name="proof_image" class="required">
					</div>
				</form>
			</div>
			<div class="modal-footer text-right">
				<button type="button" class="btn btn-custom waves-effect waves-light" onclick="installmentSubmitForm();">Save</button>
			</div>
		</div>
	</div>
</div>

<script>
//Account Statement
function account_statement(update_id, booking_inventory_id) {
    const url = site_url + "pdf/account_statement?booking_id=" + update_id + "&inventory_id=" + booking_inventory_id;
    window.open(url, '_blank');
}

//Installment
function installment(obj) {
	let row = $(obj).closest('tr');
	let table_instance = DTO.GetInstance(2);
	let row_data = table_instance.rows(row.index()).data()[0];
	
	$('#installmentModal [name="update_id"]').val(row_data.booking_id);
	$('#installmentModal [name="inventory_id"]').val(row_data.booking_inventory_id);
	$('#installmentModal').modal('show');
}

//Delete Record
function delete_record(update_id, inventory_id){
	if (confirm("do you want to delete permanent?")) {
		var o = new Object;
		o.update_id = update_id;
		o.inventory_id = inventory_id;
		$.post( site_url + 'booking/booking_delete/', o, function(result)
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

// $(window).load(function() {
// 	window.history.replaceState({}, document.title, site_url + "booking" + "");
// });

// $(document).ready(function(e) {
// 	//List Record
// 	load_data_table.init_obj({
// 		DataUrl: site_url + "booking/booking_list",
// 		order_by: [[ 3, 'desc' ]],
// 	});
// });

$(document).ready(function(e) {
    var table = $('#dt-table').DataTable({
		pageLength: 100,
		lengthMenu: [[100, 200, 500, 1000, -1], [100, 200, 500, 1000, "All"]],
        ajax: {
            url: site_url + "booking/booking_list",
            type: 'GET'
        },
        columns: [
            { data: 'counter' },
            { data: 'project_name' },
            { data: 'property_type' },
            { data: 'booking_unit' },
            { data: 'registration' },
            { data: 'customer_name' },
            { data: 'total_price' },
            { data: 'book_amount' },
            { data: 'due_amount' },
            { data: 'paid_amount' },
            { data: 'overdue_amount' },
            { data: 'mobile' },
            { data: 'landline' },
            { data: 'cnic' },
            { data: 'agency_name' },
            { data: 'future_amount' },
            { data: 'agency_commission' },
            { data: 'create_date' },
            { data: 'actions' }
        ],
        footerCallback: function (row, data, start, end, display) {
			var api = this.api();

			var unformatNumber = function(numStr) {
				if (typeof numStr !== 'string') return parseFloat(numStr) || 0;
				return parseFloat(numStr.replace(/,/g, '')) || 0;
			};

			var columnsToSum = {
				6: 'total-price',         // total_price
				7: 'book-amount',         // book_amount
				8: 'due-amount',          // due_amount
				9: 'paid-amount',         // paid_amount
				10: 'overdue-amount',     // overdue_amount
				15: 'future-amount',      // future_amount
				16: 'agency-commission'   // agency_commission
			};

			// Loop through each column index and sum filtered data
			$.each(columnsToSum, function(colIndex, className) {
				var total = api.column(colIndex, { search: 'applied' }).data().reduce(function(a, b) {
					return unformatNumber(a) + unformatNumber(b);
				}, 0);

				// Format and update footer cell
				$('.' + className).html(total.toLocaleString());
			});
		}
    });
});

// $(document).ready(function() {
//     initializeDataTable(site_url + "booking/booking_list");
// });
</script>