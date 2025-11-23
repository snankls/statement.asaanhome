<!-- Start Page content -->
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="card-box">
                    <?php $this->load->view("attendance/reports/search-filter", array("data" => array(''))); ?>
                    
                	<div id="list">
                        <div id="quotation-datatables" class="table-responsive <?php if ($current_role_id == 7 or $current_role_id == 8) echo 'hide-table-search'; ?>">
                            <table id="dt-table" class="table table-bordered table-hover">
                                <thead>
                                    <tr class="header_columns">
                                        <th data-data="fullname">Name</th>
                                        <th data-data="check_in_date">Date</th>
                                        <th data-data="check_in_day">Day</th>
                                        <th data-data="check_in_time">Check-in</th>
                                        <th data-data="cib_location">Check-in Location</th>
                                        <th data-data="check_out_time">Check-out</th>
                                        <th data-data="cob_location">Check-out Location</th>
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
$(document).ready(function() {
	// Initialize DataTable
    var table = $('#dt-table').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": site_url + "attendance/attendance_individual_list",
            "type": "POST",
            "data": function(d) {
                d.date_range = $('.date-range').val();
                d.user_id = $('.user-id option:selected').val();
            }
        },
        "pageLength": 50,
        "lengthMenu": [10, 50, 100, 500, 1000, 2000, 5000, 10000],
        "searching": false,
        "columns": [
            { "data": "fullname", "orderable": false },
            { "data": "check_in_date", "orderable": false },
            { "data": "check_in_day", "orderable": false },
            { "data": "check_in_time", "orderable": false },
            { "data": "cib_location", "orderable": false },
            { "data": "check_out_time", "orderable": false },
            { "data": "cob_location", "orderable": false },
        ],
        "createdRow": function(row, data, dataIndex) {
            // Add an ID to the <tr> tag based on lead ID
            $(row).attr('id', data.main_lead_id);
        },
    });
	
	// Search button click event
    $('#search-leads').click(function() {
        table.ajax.reload();
    });

    // Reset button to clear filters
    $('#reset-filters').click(function() {
        $('#leads-form')[0].reset();
        table.ajax.reload();
    });
});
</script>
