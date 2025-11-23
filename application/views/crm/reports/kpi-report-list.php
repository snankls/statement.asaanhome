<table id="leads-table-list" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th colspan="2" class="text-center">Particular</th>
            <th colspan="3" class="text-center">All Records</th>
            <th colspan="5" class="text-center">Records as per Selected Timeline</th>
        </tr>
        <tr>
            <th>Team Name</th>
            <th>Team Member</th>
            <th>Total Leads</th>
            <th>Potential Leads</th>
            <th>Closing Leads</th>
            <th>Productive Calls</th>
            <th>Non-Productive Calls</th>
            <th>Attempted Calls</th>
            <th>Meetings Arranged</th>
            <th>Meetings Done</th>
        </tr>
    </thead>
    
    <?php if (!empty($record_list)) : ?>
		<?php
		$total_total_leads = 0;
		$total_potential_leads = 0;
		$total_closing_leads = 0;
		$total_productive_calls = 0;
		$total_non_productive_calls = 0;
		$total_attempted_calls = 0;
		$total_meetings_arranged = 0;
		$total_meetings_done = 0;
		
		foreach ($record_list as $record) :
			$total_total_leads += $record->total_leads;
			$total_potential_leads += $record->potential_leads;
			$total_closing_leads += $record->closing_leads;
			$total_productive_calls += $record->productive_calls;
			$total_non_productive_calls += $record->non_productive_calls;
			$total_attempted_calls += $record->attempted_calls;
			$total_meetings_arranged += $record->meetings_arranged;
			$total_meetings_done += $record->meetings_done;
		?>
        <tr>
            <td><?= $record->team_name; ?></td>
            <td><?= $record->fullname; ?></td>
            <td class="text-center"><a href="<?= site_url('leads?user_id=' . $record->user_id); ?>" target="_blank"><?= $record->total_leads; ?></a></td>
            <td class="text-center"><a href="<?= site_url('leads?user_id=' . $record->user_id . '&lead_status=3'); ?>" target="_blank"><?= $record->potential_leads; ?></a></td>
            <td class="text-center"><a href="<?= site_url('leads?user_id=' . $record->user_id . '&lead_status=4'); ?>" target="_blank"><?= $record->closing_leads; ?></a></td>
            <td class="text-center"><a href="<?= site_url('reports/activity-report?user_id=' . $record->user_id . '&task_performed=2'); ?>" target="_blank"><?= $record->productive_calls; ?></a></td>
            <td class="text-center"><a href="<?= site_url('reports/activity-report?user_id=' . $record->user_id . '&task_performed=3'); ?>" target="_blank"><?= $record->non_productive_calls; ?></a></td>
            <td class="text-center"><a href="<?= site_url('reports/activity-report?user_id=' . $record->user_id . '&task_performed=1'); ?>" target="_blank"><?= $record->attempted_calls; ?></a></td>
            <td class="text-center"><a href="<?= site_url('reports/activity-report?user_id=' . $record->user_id . '&task_performed=5'); ?>" target="_blank"><?= $record->meetings_arranged; ?></a></td>
            <td class="text-center"><a href="<?= site_url('reports/activity-report?user_id=' . $record->user_id . '&task_performed=6'); ?>" target="_blank"><?= $record->meetings_done; ?></a></td>
        </tr>
        <?php endforeach; ?>
    <?php else : ?>
        <tr>
            <td colspan="10" align="center">No records found</td>
        </tr>
    <?php endif; ?>
    
    <tfoot>
        <tr class="text-center">
            <td colspan="2"><strong>Grand Total</strong></td>
            <td><strong><?php echo $total_total_leads; ?></strong></td>
            <td><strong><?php echo $total_potential_leads; ?></strong></td>
            <td><strong><?php echo $total_closing_leads; ?></strong></td>
            <td><strong><?php echo $total_productive_calls; ?></strong></td>
            <td><strong><?php echo $total_non_productive_calls; ?></strong></td>
            <td><strong><?php echo $total_attempted_calls; ?></strong></td>
            <td><strong><?php echo $total_meetings_arranged; ?></strong></td>
            <td><strong><?php echo $total_meetings_done; ?></strong></td>
        </tr>
    </tfoot>
</table>
