<!-- Start Page content -->
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="card-box">
                	<?php $this->load->view("crm/leads/snippets/search-filter", array("data" => array())); ?>
                    
					<div id="list">
                        <div class="table_operations_container">
							<div class="data_table_operations text-right">
								<a href="javascript:;" class="btn btn-success btn-small hidden show_with_selection" onclick="shift_todo_to_leads();">
									<i class="fa fa-filter"></i> Move to Leads
								</a>
							</div>
						</div>

                        <div id="quotation-datatables" class="table-responsive <?php if ($current_role_id == 7 or $current_role_id == 8) echo 'hide-table-search'; ?>">
                            <table id="leads-table-list" class="table table-striped table-hover display">
                                <thead>
                                    <tr class="header_columns">
                                        <th class="select-checkbox no-sort no-search" style="min-width: 13px; max-width: 13px;" data-data="checkbox">
                                            <i class="fa fa-square-o"></i>
                                        </th>
                                        <th style="min-width: 60px; max-width: 60px; text-align:center;" data-data="action">Action</th>
                                        <th class="no-sort no-search" width="50" data-data="create_date">Cerate Date</th>
                                        <th data-data="main_lead_id">ID</th>
                                        <th data-data="name">Full Name</th>
                                        <th data-data="project_name">Project Name</th>
                                        <th data-data="fullname">Allocation Name</th>
                                        <th data-data="lead_source">Lead Source</th>
                                        <th data-data="lead_status">Lead Status</th>
                                        <th data-data="last_followup_date">Last Followup Date</th>
                                        <th data-data="task_performed">Task Performed</th>
                                        <th data-data="next_followup_date">Next Followup Date</th>
                                        <th data-data="next_task">Next Task</th>
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
<div class="modal fade" id="followupModal" role="dialog">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
            <div id="followup-list"></div>
		</div>
	</div>
</div>

<!--Edit Leads-->
<div class="modal fade" id="editLeadModal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Edit Leads</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<form id="update-form" class="form-horizontal Form" role="form">
                	<input type="hidden" name="update_id" value="" />
                    <div class="form-group">
                        <label class="col-form-label">Name <span class="error-message">*</span></label>
                        <input type="text" name="name" class="form-control" value="" />
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Country <span class="error-message">*</span></label>
                        <select name="country" class="form-control select2 required">
                            <option value="">Select One</option>
                            <?php foreach(country_list() as $k => $v){ ?>
                            <option value="<?=$k?>"><?=$v?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Phone Number <span class="error-message">*</span></label>
                        <input type="text" name="phone_number" id="phone_number" class="form-control phone-masking" placeholder="3001234567" maxlength="10" />
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Email Address <span class="error-message">*</span></label>
                        <input type="email" name="email_address" id="email_address" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">City <span class="error-message">*</span></label>
                        <input type="text" name="city" id="city" class="form-control" />
                    </div>
                    <div id="form-error-message" class="error-message"></div>
				</form>
			</div>
			<div class="modal-footer text-right">
				<button type="button" class="btn btn-custom waves-effect waves-light" onclick="submitForm();">Save</button>
			</div>
		</div>
	</div>
</div>

<?php $this->load->view("crm/leads/snippets/ajax", array("data" => array())); ?>

<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#leads-table-list').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": site_url + "leads/leads_list",
            "type": "POST",
            "data": function(d) {
                d._timestamp = new Date().getTime();
                d.lead_id = $('#lead-id').val();
                d.name = $('#name').val();
                d.last_followup_date = $('#last-followup-date').val();
                d.next_followup_date = $('#next-followup-date').val();
                d.lead_added_date = $('#lead-added-date').val();
                d.fullname = $('#fullname').val();
                d.phone_number = $('#phone-number').val();
                d.task_performed = $('#task-performed').val();
                d.next_task = $('#next-task').val();
                d.lead_source = $('#lead-source').val();
                d.project_id = $('#project-id').val();
                d.allocation_id = $('#allocation-id').val();
                d.status = $('#status').val();
                d.page_view = 'todo_list';
            }
        },
        "pageLength": 50,
        "lengthMenu": [10, 50, 100, 500, 1000, 2000, 5000, 10000],
        "searching": false,
        "columns": [
            { "data": "checkbox", "orderable": false },
            { "data": "action", "orderable": false },
            { "data": "create_date", "orderable": false },
            { "data": "lead_id", "orderable": false },
            { "data": "name", "orderable": false },
            { "data": "project_name", "orderable": false },
            { "data": "allocation_name", "orderable": false },
            { "data": "lead_source", "orderable": false },
            { "data": "lead_status", "orderable": false },
            { "data": "last_followup_date", "orderable": false },
            { "data": "task_performed", "orderable": false },
            { "data": "next_followup_date", "orderable": false },
            { "data": "next_task", "orderable": false }
        ],
        "createdRow": function(row, data, dataIndex) {
            // Add an ID to the <tr> tag based on lead ID
            $(row).attr('id', data.main_lead_id);
        },
        "rowCallback": function(row, data, index) {
			// Select the first <td> and modify its classes
			$('td:eq(0)', row)
				.removeClass('sorting_1')
				.addClass('select-checkbox');
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

function handleAction(selectElement, mainLeadId) {
    var action = selectElement.value;

    if (action === 'add_followup') {
        // Open the modal for adding followup
        $('#followupModal').modal('show');
        add_followup(mainLeadId);
    } else if (action === 'edit_leads') {
        // Handle the edit action
        edit_record(mainLeadId);
    }

    // Reset the select back to the default option
    selectElement.selectedIndex = 0;
}

//Shift Leads
function shift_todo_to_leads() {
    if (!confirm("Are you sure you want to shift the selected todo?")) {
        return; // exit if user clicks Cancel
    }
    
    var selectedLeads = [];

    // Loop through the selected rows in the table
    $('#leads-table-list tbody tr.selected').each(function() {
        var leadId = $(this).attr('id');
        selectedLeads.push(leadId);
    });

    // Create the data object to send to the controller
    var data = {
        lead_ids: selectedLeads,
    };

    // Make an AJAX request to send the data to your controller
    $.ajax({
        url: site_url + 'leads/shift_todo_to_leads',
        type: 'POST',
        data: data,
        success: function(response) {
            $('#shiftLeadsModal').modal('hide');
			$('#leads-table-list').DataTable().ajax.reload();
        },
        error: function(xhr, status, error) {
            alert('An error occurred while shifting leads: ' + error);
        }
    });
}
</script>
