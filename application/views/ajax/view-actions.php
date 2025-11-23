<!--ToDo: fix loading of log popup box-->
<?php if(!empty($buttons_before)){

	foreach($buttons_before as $b){
		$on_click = "";
		if(@$b['on_click'])
			$on_click = "onclick='$b[on_click]'";

		$b["class"] = $b["class"] ?? "btn-success";

		echo "<a href='$b[url]' $on_click class='btn btn-small default enable-tooltip $b[class]' data-title='$b[title]'><span class='$b[span_class]'></span> $b[title]</a>";
	}

} ?>

<?php if(@$log_table){ ?>
	<a href="javascript:;" class="btn btn-warning btn-small default enable-tooltip" data-title="View Log"
	   onClick="return show_log_history('{log_table}', '{log_id}');">
		<i class="fa fa-history"></i> Log
	</a>
<?php } ?>
<?php if(@$edit_url){ ?>
	<a href="{edit_url}" class="btn btn-success btn-small default enable-tooltip" data-title="Edit">
		<span class="fa fa-edit"></span> Edit
	</a>
<?php } ?>

<?php if(@$buttons_after){

	foreach($buttons_after as $b){
		echo "<a href='$b[url]' class='btn btn-success btn-small default enable-tooltip' data-title='$b[title]'><span class='$b[span_class]'></span> $b[title]</a>";
	}

} ?>

<a href="#" onclick="CF.MoveToElement('#activity-details-list'); return false;"
   class="btn btn-success btn-small enable-tooltip view_activity"
   data-title="View Activity">
	<span class="fa fa-eye"></span> Activity
</a>
<script type="text/javascript">if($('#activity-details-list').length === 0){$('.view_activity').remove();}</script>
