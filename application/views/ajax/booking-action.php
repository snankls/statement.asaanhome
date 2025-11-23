<button type="button" class="btn btn-dark btn-small" title="Account Statement" onClick="account_statement('{update_id}', '{booking_inventory_id}');" download><i class="fa fa-file-text-o"></i></button>&ensp;

<?php if ($current_role_id != 6) { ?>
<button type="button" class="btn btn-primary btn-small" title="Installment" onClick="installment(this);"><i class="fa fa-dollar"></i></button>&ensp;
<?php } ?>

<?php if ( $is_admin == "yes" ){ ?>
<button type="button" class="btn btn-danger btn-small" title="Delete" onClick="delete_record('{update_id}', '{booking_inventory_id}');"><i class="fa fa-times"></i></button>
<?php } ?>