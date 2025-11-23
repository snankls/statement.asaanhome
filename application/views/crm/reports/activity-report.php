<!-- Start Page content -->
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="card-box">
                	<?php $this->load->view("crm/leads/snippets/search-filter", array("data" => array('activity-report'))); ?>
                    
					<div id="list">
                        <div id="quotation-datatables" class="table-responsive <?php if ($current_role_id == 7 or $current_role_id == 8) echo 'hide-table-search'; ?>">
                            <table id="leads-table-list" class="table table-striped table-hover display">
                                <thead>
                                    <tr class="header_columns">
                                        <th style="min-width: 100px; max-width: 100px;" data-data="action">Action</th>
                                        <th data-data="lead_id">Lead ID</th>
                                        <th data-data="name">Client Name</th>
                                        <th data-data="create_date">Date</th>
                                        <th data-data="create_time">Time</th>
                                        <th data-data="task_performed">Task Performed</th>
                                        <th data-data="lead_performed_by">Task Performed By</th>
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

<!--Followup List-->
<div class="modal fade" id="viewActivityModal" role="dialog">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
        	<div class="modal-header">
				<h4 class="modal-title">Activity Reports</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
            <div id="followup-list"></div>
		</div>
	</div>
</div>

<script>
$(document).ready(function() {
	// Check if URL has a user_id parameter, and trigger the search if true
    const urlParams = new URLSearchParams(window.location.search);
	if (['user_id', 'task_performed'].some(param => urlParams.has(param))) {
		$('#search-leads').trigger('click');
	}
	
    // Initialize DataTable
    var table = $('#leads-table-list').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": site_url + "reports/leads_activity_report_list",
            "type": "POST",
            "data": function(d) {
                d.task_performed = $('#task-performed').val();
                d.allocation_id = $('#allocation-id').val();
                d.last_followup_date = $('#last-followup-date').val();
            }
        },
        "pageLength": 50,
        "lengthMenu": [10, 50, 100, 500, 1000, 2000, 5000, 10000],
        "searching": false,
        "columns": [
            { "data": "action", "orderable": false },
            { "data": "lead_id", "orderable": false },
            { "data": "name", "orderable": false },
            { "data": "create_date", "orderable": false },
            { "data": "create_time", "orderable": false },
            { "data": "task_performed", "orderable": false },
            { "data": "task_performed_by", "orderable": false },
        ],
        "createdRow": function(row, data, dataIndex) {
            // Add an ID to the <tr> tag based on lead ID
            $(row).attr('id', data.main_lead_id);
        },
        "rowCallback": function(row, data, index) {
			// Select the first <td> and modify its classes
			$('td:eq(0)', row)
				.removeClass('sorting_1')   // Remove the 'sorting_1' class
		}
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

//View Activity
function view_activity(id) {
    var o = new Object();
	o.lead_id = id;
	
	$('#followup-list').html(loader_big());
	$("#followup-list").load(site_url + "reports/leads_activity_report_details_list/", o);
}
</script>
