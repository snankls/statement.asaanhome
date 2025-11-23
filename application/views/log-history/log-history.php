<?php if (!empty($record_list)){ ?>
<table id="log-table" class="table table-striped table-hover">
	<thead>
        <tr>
            <th>Updated By</th>
            <th>Date</th>
        </tr>
	</thead>
	<tbody>
    	<?php foreach ($record_list as $data){ ?>
        <tr>
            <td><?php echo $data->fullname; ?></td>
            <td><?php echo $data->created_on; ?></td>
        </tr>
        <?php } ?>
	</tbody>
</table>
<script>
$(document).ready(function(e) {
	$('#log-table').DataTable({
		retrieve: true,
		"aaSorting": [],
	});
});
</script>
<?php } else{ ?>
<p>No Record Available.</p>
<?php } ?>
