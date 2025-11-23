<!-- Start Page content -->
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="card-box">                     
                    <table id="dt-table" class="table table-striped table-hover display">
                        <thead>
                            <tr class="header_columns">
                                <th data-data="action">Action</th>
                                <th data-data="receipt_image" width="50">Image</th>
                                <th data-data="lead_id">Lead ID</th>
                                <th data-data="name">Name</th>
                                <th data-data="mobile">Mobile</th>
                                <th data-data="cnic">CNIC</th>
                                <th data-data="project_name">Project Name</th>
                                <th data-data="property_types">Property Type</th>
                                <th data-data="unit_number">Unit Number</th>
                                <th data-data="payment_type">Payment Type</th>
                                <th data-data="unit_price">Unit Price</th>
                                <th data-data="discount_amount">Discount Amount</th>
                                <th data-data="settled_price">Settled Price</th>
                                <th data-data="received_amount">Received Amount</th>
                                <th data-data="balance_amount">Balance Amount</th>
                                <th data-data="balance_payment_deadline">Balance Payment Deadline</th>
                                <th data-data="other_conditions">Other Conditions</th>
                                <th data-data="remarks">Remarks</th>
                                <th data-data="create_date">Created By</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
				</div>
			</div>
		</div>
		<!-- end row -->
	</div> <!-- container -->
</div> <!-- content -->

<!--Add Receipt-->
<div class="modal fade" id="receiptModal" role="dialog">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Edit Receipt</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
            <div class="modal-body">
                <?php $this->load->view("crm/leads/receipt/receipt-form", array("data" => array())); ?>
            </div>
		</div>
	</div>
</div>

<script>
function receipt_status(obj, id) {
    $('#receiptModal form')[0].reset();
    
    let row = $(obj).closest('tr');
    var projectName = row.find('td:eq(6)').text().trim();
    var propertyType = row.find('td:eq(7)').text().trim();
    var paymentType = row.find('td:eq(9)').text().trim();
	
    var imageUrl = row.find('td:eq(1) a.lightbox-image img').attr('src');
    var imageName = imageUrl.split('/').pop();
    
    $('#receiptModal [name="update_id"]').val(id);
    $('#receiptModal [name="name"]').val(row.find('td:eq(3)').text());
    $('#receiptModal [name="mobile"]').val(row.find('td:eq(4)').text());
    $('#receiptModal [name="cnic"]').val(row.find('td:eq(5)').text());
    $('#receiptModal [name="project_id"] option').filter(function() {return $(this).text().trim() === projectName;}).prop('selected', true);
    $('#receiptModal [name="project_id"]').trigger('change');
    
	setTimeout(function() {
        $('#receiptModal [name="property_type"] option').filter(function() {return $(this).text().trim() === propertyType;}).prop('selected', true).trigger('change');
    }, 500);
	
    $('#receiptModal [name="unit_number"]').val(row.find('td:eq(8)').text());
    $('#receiptModal [name="payment_type"] option').filter(function() {return $(this).text().trim() === paymentType;}).prop('selected', true);
    $('#receiptModal [name="unit_price"]').val(row.find('td:eq(10)').text());
    $('#receiptModal [name="discount_amount"]').val(row.find('td:eq(11)').text());
    $('#receiptModal [name="settled_price"]').val(row.find('td:eq(12)').text());
    $('#receiptModal [name="received_amount"]').val(row.find('td:eq(13)').text());
    $('#receiptModal [name="balance_amount"]').val(row.find('td:eq(14)').text());
    $('#receiptModal [name="balance_payment_deadline"]').val(row.find('td:eq(15)').text());
    $('#receiptModal [name="other_conditions"]').val(row.find('td:eq(16)').text());
    $('#receiptModal [name="remarks"]').val(row.find('td:eq(17)').text());
    $('#receiptModal [name="update_receipt_image"]').val(imageName);

    // Set anchor and image preview
    $('#receiptModal [name="receipt_image_link"]').attr('href', imageUrl);
    $('#receiptModal [name="receipt_image"]').attr('value', imageName);
    $('#receiptModal [name="receipt_image_preview"]').attr('src', imageUrl);

    // Show the modal first
    $('#receiptModal').modal('show');
}

//Receipt Download
function receipt_download(obj) {
	var o = new Object();
	o.receipt_id = obj;
	$(this).load(site_url + "pdf/receipt_download/", o);
}

// Initialize DataTable and assign to a global variable
let dataTable;
$(document).ready(function() {
    dataTable = initializeDataTable(site_url + "leads/leads_receipt_list");

    // Reload DataTable after the modal closes
    $('#receiptModal').on('hidden.bs.modal', function() {
        if (dataTable) {
            dataTable.ajax.reload(null, false);
        }
    });
});
</script>
