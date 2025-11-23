<!--ToDo: fix loading of log popup box-->
<?php /*if(!empty($buttons_before)){

	foreach($buttons_before as $b){
		$on_click = "";
		if(@$b['on_click'])
			$on_click = "onclick='$b[on_click]'";

		$b["class"] = $b["class"] ?? "btn-success";

		echo "<a href='$b[url]' $on_click class='btn btn-small default enable-tooltip $b[class]' data-title='$b[title]'><span class='$b[span_class]'></span> $b[title]</a>";
	}

}*/ ?>

<?php if(@$log_table){ ?>
	<a href="javascript:;" class="btn btn-warning btn-small default enable-tooltip" data-title="View Log"
	   onClick="return show_log_history('{log_table}', '{log_id}');">
		<i class="fa fa-history"></i> Log
	</a>
<?php } ?>
<?php if(@$add_url){ ?>
	<a href="{add_url}" class="btn btn-primary btn-small default enable-tooltip" data-title="Create New">
		<span class="fa fa-plus"></span> Create
	</a>
<?php } ?>
<?php if(@$edit_url){ ?>
	<a href="{edit_url}" class="btn btn-success btn-small default enable-tooltip" data-title="Edit">
		<span class="fa fa-edit"></span> Edit
	</a>
<?php } ?>
<?php /*if(@$copy_url){ ?>
	<a href="{copy_url}" class="btn btn-success btn-small default enable-tooltip" data-title="Copy">
		<span class="fa fa-clone"></span> Copy
	</a>
<?php } ?>
<?php if(@$print_url){ ?>
	<a href="javascript:;" class="btn btn-dark btn-small default enable-tooltip" data-title="Print" onclick="printDiv('print');">
		<span class="fa fa-print"></span> Print
	</a>
<?php }*/ ?>

<?php if(@$buttons_after){

	foreach($buttons_after as $b){
		echo "<a href='$b[url]' class='btn btn-success btn-small default enable-tooltip' data-title='$b[title]'><span class='$b[span_class]'></span> $b[title]</a>";
	}

} ?>