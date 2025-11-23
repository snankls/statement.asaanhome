<?php if (!empty($installment_list)) { ?>
<div class="form-row">
    <div class="form-group col-12">
        <table id="installment-table" class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th width="50">Sr #</th>
                    <th>Date</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody id="purchase-details">
                <tr class="purchase-details-row">
                    <?php $i=1; ?>
                    <td><?php echo $i; ?></td>
                    <td><input type="text" name="date[]" class="form-control datepicker" /></td>
                    <td><input type="text" name="amount[]" class="form-control installment-amount" /></td>
                </tr>
                <?php for ($k = 1; $k < $installment_list; $k++) { ?>
                <tr class="purchase-details-row">
                    <td><?php echo $i + $k; ?></td>
                    <td><input type="text" name="date[]" class="form-control datepicker" tabIndex="-1" readonly="readonly" /></td>
                    <td><input type="text" name="amount[]" class="form-control installment-amount" /></td>
                </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" style="text-align: right;"><strong>Total:</strong></td>
                    <td id="total-amount" style="font-weight:bold;">0</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<?php } ?>

<script>
// Datepicker initialization
jQuery(".datepicker").datepicker({
    autoclose: true,
    format: 'dd-M-yyyy', // Change the date format here
    todayHighlight: true,
    orientation: "bottom left"
});

function updateDates(selectedDate) {
    var startDate = new Date(selectedDate);
    var inputs = document.querySelectorAll('.datepicker');
    
    // Start filling dates from the second row
    for (var i = 1; i < inputs.length; i++) {
        var newDate = new Date(startDate);
        newDate.setMonth(startDate.getMonth() + i);
        inputs[i].value = newDate.toLocaleDateString('en-GB', {day: '2-digit', month: 'short', year: 'numeric'}).replace(/ /g, '-');
    }
}

jQuery('.datepicker').change(function() {
    var selectedDate = this.value;
    updateDates(selectedDate);
});

// Calculate total sum of amounts
function calculateTotal() {
    var total = 0;
    var amountInputs = document.querySelectorAll('.installment-amount');
    amountInputs.forEach(function(input) {
        total += parseFloat(input.value) || 0;
    });
    document.getElementById('total-amount').textContent = total.toLocaleString('en-US');
    
    var totalPrice = parseFloat(document.getElementsByName('total_price')[0].value);
    if (total > totalPrice) {
        alert("Total amount cannot exceed total price.");
        // Reset the last entered amount
        var lastAmountInput = amountInputs[amountInputs.length - 1];
        lastAmountInput.value = (parseFloat(lastAmountInput.value) - (total - totalPrice)).toLocaleString('en-US');
        total = totalPrice;
        document.getElementById('total-amount').textContent = total.toLocaleString('en-US');
    }
}

// Call calculateTotal() when any amount input changes
jQuery('.installment-amount').on('input', function() {
    calculateTotal();
});

// Initial total calculation
calculateTotal();
</script>
