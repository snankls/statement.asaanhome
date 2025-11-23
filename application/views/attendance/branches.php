<?php //pre_print($record_list); ?>
<!-- Start Page content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card-box">
                    <h4 class="header-title mb-3">Add Branches</h4>
                    <div class="accordion custom-accordion" id="branch-accordion">
                        <?php $i = 1; foreach ($record_list as $record) { ?>
                            <div class="card border mb-2">
                                <div class="card-header" id="heading<?php echo $i; ?>">
                                    <h5 class="m-0 position-relative branch-title">
                                        <a class="custom-accordion-title text-dark d-block" data-toggle="collapse" href="#collapse<?php echo $i; ?>" aria-expanded="<?php echo ($i == 1) ? 'true' : 'false'; ?>" aria-controls="collapse<?php echo $i; ?>">
                                            <span class="branch-name-display"><?php echo @$record->name ?: 'Branch ' . $i; ?></span>
                                            <span class="fe-trash-2 accordion-arrow" onclick="delete_record('<?php echo @$record->branch_id; ?>', 'branches', 'branch_id', this, event);"></span>
                                        </a>
                                    </h5>
                                </div>
                                <div id="collapse<?php echo $i; ?>" class="collapse <?php echo ($i == 1) ? 'show' : ''; ?>" aria-labelledby="heading<?php echo $i; ?>" data-parent="#branch-accordion">
                                    <div class="card-body">
                                        <div class="form-row">
                                            <input type="hidden" name="update_id[]" class="update_id" value="<?php echo @$record->branch_id; ?>" />
                                            <div class="form-group col-lg-3 col-xs-12">
                                                <label class="col-form-label">Branch Name <span class="error-message">*</span></label>
                                                <input type="text" name="branch_name[]" class="form-control branch_name" value="<?php echo @$record->name; ?>" />
                                            </div>
                                            <div class="form-group col-lg-3 col-xs-12">
                                                <label class="col-form-label">Branch Radius <span class="error-message">*</span></label>
                                                <input type="text" name="branch_radius[]" class="form-control branch_radius required" value="<?php echo @$record->radius; ?>">
                                            </div>
                                            <div class="form-group col-lg-6 col-xs-12">
                                                <label class="col-form-label">Branch Address <span class="error-message">*</span></label>
                                                <input type="text" name="branch_address[]" class="form-control branch_address" value="<?php echo @$record->address; ?>">
                                            </div>
                                        </div>
                                        <input type="hidden" class="latitude" id="latitude<?php echo $i; ?>" name="latitude[]" value="<?php echo @$record->latitude; ?>" />
                                        <input type="hidden" class="longitude" id="longitude<?php echo $i; ?>" name="longitude[]" value="<?php echo @$record->longitude; ?>" />
                                        
                                        <div id="map<?php echo $i; ?>" class="map" style="width: 100%; height: 450px;"></div>
                                    </div>
                                </div>
                            </div>
                        <?php $i++; } ?>
                    </div>
                    
                    <div id="result" class="error-message"></div>
                    
                    <div><br />
                        <a href="javascript:;" class="btn btn-success add-row">Add Branch</a> &nbsp;
                        <a href="javascript:;" class="btn btn-info" id="saveBranches">Save Branches</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
    </div> <!-- container -->
</div> <!-- content -->

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
let mapCount = 1;
const maps = {};

// Initialize maps for existing records
<?php $i = 1; foreach ($record_list as $record) { ?>
    initializeMap('map<?php echo $i; ?>', 'latitude<?php echo $i; ?>', 'longitude<?php echo $i; ?>');
<?php $i++; } ?>

function initializeMap(mapId, latitudeId, longitudeId) {
    // Get initial latitude and longitude from input fields
    const initialLat = $("#" + latitudeId).val();
    const initialLng = $("#" + longitudeId).val();
    
    // Initialize map and set center based on existing coordinates or default location
    const map = L.map(mapId).setView(
        [initialLat || 31.373268, initialLng || 74.187247],
        12
    );

    // Variable to hold the marker
    let marker;

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);

    // Store map instance for re-initialization on accordion open
    maps[mapId] = { map, marker };

    // If latitude and longitude are available, add a marker at that location
    if (initialLat && initialLng) {
        marker = L.marker([initialLat, initialLng]).addTo(map);
        maps[mapId].marker = marker; // Update marker reference
    }

    // Add click event to the map for setting marker and coordinates
    map.on('click', function(e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;

        // Update hidden input fields with selected latitude and longitude
        $("#" + latitudeId).val(lat);
        $("#" + longitudeId).val(lng);

        // If a marker already exists, remove it
        if (marker) {
            map.removeLayer(marker);
        }

        // Add a new marker at the clicked location
        marker = L.marker([lat, lng]).addTo(map);
        maps[mapId].marker = marker; // Update marker reference
    });
}

$(document).ready(function() {
    let initialBranchCount = $('#branch-accordion .card').length;

    $(".add-row").on('click', function() {
		const newBranchNumber = initialBranchCount + 1;
		const newAccordion = `
			<div class="card border mb-2">
				<div class="card-header" id="heading${newBranchNumber}">
					<h5 class="m-0 position-relative branch-title">
						<a class="custom-accordion-title text-dark collapsed d-block" data-toggle="collapse" href="#collapse${newBranchNumber}" aria-expanded="false" aria-controls="collapse${newBranchNumber}">
							<span class="branch-name-display">Branch ${newBranchNumber}</span>
                            <span class="fe-trash-2 accordion-arrow" onclick="delete_record('', 'branches', 'branch_id', this, event);"></span>
						</a>
					</h5>
				</div>
				<div id="collapse${newBranchNumber}" class="collapse" aria-labelledby="heading${newBranchNumber}" data-parent="#branch-accordion">
					<div class="card-body">
						<input type="hidden" name="update_id[]" class="update_id" value="0" />
						<div class="form-row">
							<div class="form-group col-lg-3 col-xs-12">
								<label class="col-form-label">Branch Name <span class="error-message">*</span></label>
								<input type="text" name="branch_name[]" class="form-control branch_name" />
							</div>
							<div class="form-group col-lg-3 col-xs-12">
								<label class="col-form-label">Branch Radius <span class="error-message">*</span></label>
								<input type="text" name="branch_radius[]" class="form-control branch_radius required">
							</div>
							<div class="form-group col-lg-6 col-xs-12">
								<label class="col-form-label">Branch Address <span class="error-message">*</span></label>
								<input type="text" name="branch_address[]" class="form-control branch_address">
							</div>
						</div>
						<div id="map${newBranchNumber}" class="map" style="width: 100%; height: 450px;"></div>
						<input type="hidden" class="latitude" id="latitude${newBranchNumber}" name="latitude[]" />
						<input type="hidden" class="longitude" id="longitude${newBranchNumber}" name="longitude[]" />
					</div>
				</div>
			</div>
		`;
	
		$('#branch-accordion').append(newAccordion);
		initializeMap(`map${newBranchNumber}`, `latitude${newBranchNumber}`, `longitude${newBranchNumber}`);
		
		// Add the event listener for the new input field
		$(`#branch-accordion`).on('input', `.branch_name`, function() {
			const header = $(this).closest('.card').find('.branch-name-display');
			header.text($(this).val() || `Branch ${newBranchNumber}`);
		});
	
		initialBranchCount++;
	});

    // Reinitialize map with delay when accordion panel is shown
    $('#branch-accordion').on('shown.bs.collapse', function(event) {
        const mapId = $(event.target).find('.map').attr('id');
        if (mapId && maps[mapId]) {
            setTimeout(() => {
                maps[mapId].map.invalidateSize();
            }, 300);
        }
    });

    $("#saveBranches").on('click', function() {
		let branches = [];
	
		$('#branch-accordion .card').each(function() {
			let bi = $(this).find('.update_id').val(); // Fetch update_id
			let bn = $(this).find('.branch_name').val(); // Fetch branch name
			let br = $(this).find('.branch_radius').val(); // Fetch branch radius
			let ba = $(this).find('.branch_address').val(); // Fetch branch address
			let lat = $(this).find('.latitude').val(); // Fetch latitude
			let lng = $(this).find('.longitude').val(); // Fetch longitude
		
			// Ensure no empty fields (optional)
			if (bn && br && ba) {
				branches.push({
					update_id: bi || 0,
					branch_name: bn,
					branch_radius: br,
					branch_address: ba,
					latitude: lat,
					longitude: lng
				});
			}
		});
		
		// Check if branches array is empty
		if (branches.length === 0) {
			alert("Please fill in at least one branch before saving.");
			return; // Prevent the AJAX call if no branches are filled
		}
	
		$.ajax({
			url: site_url + 'attendance/office_branches',
			type: 'POST',
			data: { branches: branches }, // Sending branches array
			dataType: 'json', // Expecting JSON response
			success: function(response) {
				if(response.status) {
					$("#result").html(response.status);
				} else {
					$("#result").html('Error, in form save.');
				}
				
				// Set a timeout to fade out the message after 3 seconds
				setTimeout(function() {
					$("#result").fadeOut(300, function() {
						$(this).html(''); // Clear the content after fading out
					});
				}, 3000);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				console.error('AJAX Error:', textStatus, errorThrown); // Log AJAX errors
				alert("Error saving branches.");
			}
		});
	});

});

$(document).on('input', '.branch_name', function() {
    // Find the corresponding header for this input
    const header = $(this).closest('.card').find('.branch-name-display');
    // Update the header text with the current input value
    header.text($(this).val() || 'Branch ' + ($(this).closest('.card').index() + 1));
});

function delete_record(id, db_table, primary_id, element, event) {
    // Prevent the collapse toggle by stopping the event from propagating
    event.stopPropagation();

    if (confirm("Are you sure you want to permanently delete? This cannot be undone.")) {
        const o = { id, db_table, primary_id };
        
        if (id === '') {
            $(element).closest('.card').fadeOut(300, function() {
                $(this).remove();
            });
        } else {
            $.post(site_url + 'api/delete_single/', o, function(result) {
                if (result.msg === "SUCCESS") {
                    $(element).closest('.card').fadeOut(300, function() {
                        $(this).remove();
                    });
                } else {
                    alert(result.data);
                }
            }, "json");
        }
    }
    return false;
}
</script>
