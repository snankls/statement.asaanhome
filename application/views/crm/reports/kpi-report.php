<!-- Start Page content -->
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="card-box">
                	<?php $this->load->view("crm/leads/snippets/search-filter", array("data" => array('kpi-report'))); ?>
                    
					<div id="list">
                        <div id="quotation-datatables" class="table-responsive <?php if ($current_role_id == 7 or $current_role_id == 8) echo 'hide-table-search'; ?>">
                            <table id="kpi-report-table-list" class="table table-bordered table-hover">
                                <thead>
                                	<tr>
                                        <th colspan="2">Particular</th>
                                        <th colspan="3">All Records</th>
                                        <th colspan="5">Records as per Selected Timeline</th>
                                    </tr>
                                    <tr class="header_columns">
                                        <th data-data="team_name">Team Name</th>
                                        <th data-data="team_member">Team Member</th>
                                        <th data-data="total_leads">Total Leads</th>
                                        <th data-data="potential_leads">Potential Leads</th>
                                        <th data-data="closing_leads">Closing Leads</th>
                                        <th data-data="productive_calls">Productive Calls</th>
                                        <th data-data="non_productive_calls">Non Productive Calls</th>
                                        <th data-data="attempted_calls">Attempted Calls</th>
                                        <th data-data="meetings_arranged">Meetings Arranged</th>
                                        <th data-data="meetings_done">Meetings Done</th>
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
    var table = $('#kpi-report-table-list').DataTable({
        "processing": true,
        "serverSide": true,
        "paging": false,
        "info": false,
		"lengthChange": false,
        "ajax": {
            "url": site_url + "reports/leads_kpi_report_list",
            "type": "POST",
            "data": function(d) {
                d.last_followup_date = $('#last-followup-today-date').val();
            }
        },
        "searching": false,
        "columns": [
            { "data": "team_name", "orderable": false },
            { "data": "team_member", "orderable": false },
            { "data": "total_leads", "orderable": false },
            { "data": "potential_leads", "orderable": false },
            { "data": "closing_leads", "orderable": false },
            { "data": "productive_calls", "orderable": false },
            { "data": "non_productive_calls", "orderable": false },
            { "data": "attempted_calls", "orderable": false },
            { "data": "meetings_arranged", "orderable": false },
            { "data": "meetings_done", "orderable": false },
        ],
        "createdRow": function(row, data, dataIndex) {
            $(row).attr('id', data.main_lead_id);
        },
        "rowCallback": function(row, data, index) {
			$('td:eq(0)', row).removeClass('sorting_1');
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
</script>
