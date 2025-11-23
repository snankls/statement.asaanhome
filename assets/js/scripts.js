(function ($) {

    "use strict";
	
	
	// Mobile Nav Hide Show
	/*if($('.mobile-menu').length){
		
		//$('.mobile-menu .menu-box').mCustomScrollbar();
		
		var mobileMenuContent = $('.main-header .nav-outer .main-menu').html();
		$('.mobile-menu .menu-box .menu-outer').append(mobileMenuContent);
		$('.sticky-header .main-menu').append(mobileMenuContent);
		
		//Hide / Show Submenu
		$('.mobile-menu .navigation > li.dropdown > .dropdown-btn').on('click', function(e) {
			e.preventDefault();
			var target = $(this).parent('li').children('ul');
			
			if ($(target).is(':visible')){
				$(this).parent('li').removeClass('open');
				$(target).slideUp(500);
				$(this).parents('.navigation').children('li.dropdown').removeClass('open');
				$(this).parents('.navigation').children('li.dropdown > ul').slideUp(500);
				return false;
			}else{
				$(this).parents('.navigation').children('li.dropdown').removeClass('open');
				$(this).parents('.navigation').children('li.dropdown').children('ul').slideUp(500);
				$(this).parent('li').toggleClass('open');
				$(this).parent('li').children('ul').slideToggle(500);
			}
		});

		//3rd Level Nav
		$('.mobile-menu .navigation > li.dropdown > ul  > li.dropdown > .dropdown-btn').on('click', function(e) {
			e.preventDefault();
			var targetInner = $(this).parent('li').children('ul');
			
			if ($(targetInner).is(':visible')){
				$(this).parent('li').removeClass('open');
				$(targetInner).slideUp(500);
				$(this).parents('.navigation > ul').find('li.dropdown').removeClass('open');
				$(this).parents('.navigation > ul').find('li.dropdown > ul').slideUp(500);
				return false;
			}else{
				$(this).parents('.navigation > ul').find('li.dropdown').removeClass('open');
				$(this).parents('.navigation > ul').find('li.dropdown > ul').slideUp(500);
				$(this).parent('li').toggleClass('open');
				$(this).parent('li').children('ul').slideToggle(500);
			}
		});

		//Menu Toggle Btn
		$('.mobile-nav-toggler').on('click', function() {
			$('body').addClass('mobile-menu-visible');

		});

		//Menu Toggle Btn
		$('.mobile-menu .menu-backdrop,.mobile-menu .close-btn').on('click', function() {
			$('body').removeClass('mobile-menu-visible');
			$('.mobile-menu .navigation > li').removeClass('open');
			$('.mobile-menu .navigation li ul').slideUp(0);
		});

		$(document).keydown(function(e){
	        if(e.keyCode == 27) {
				$('body').removeClass('mobile-menu-visible');
			$('.mobile-menu .navigation > li').removeClass('open');
			$('.mobile-menu .navigation li ul').slideUp(0);
        	}
	    });	
	}*/
	
	
	


	// Gear Dropdown
	$(".info-icon").on("click", function(e){
		//alert();
		var objects_menu = $("#payment-info-section");
		$("#payment-info-section").toggle("show");
	
		/* code for offset */
		$(objects_menu).css({
			top: $(this).parent().offset().top + -175,
			left: $(this).parent().offset().left + 200,
		})
		e.stopPropagation();
		e.preventDefault();
	});
	
	//LightBox / Fancybox
	if($('.lightbox-image').length) {
		$('.lightbox-image').fancybox({
			openEffect  : 'fade',
			closeEffect : 'fade',
			helpers : {
				media : {}
			}
		});
	}
	
	//Fact Counter + Text Count
	if($('.product-dashboard-title').length){
		$('.product-dashboard-title').appear(function(){
	
			var $t = $(this),
				n = $t.find("small").attr("data-stop"),
				r = parseInt($t.find("small").attr("data-speed"), 10);
				
			if (!$t.hasClass("counted")) {
				$t.addClass("counted");
				$({
					countNum: $t.find("small").text()
				}).animate({
					countNum: n
				}, {
					duration: r,
					easing: "linear",
					step: function() {
						$t.find("small").text(Math.floor(this.countNum).toLocaleString());
					},
					complete: function() {
						$t.find("small").text(Number(this.countNum).toLocaleString());
					}
				});
			}
			
		},{accY: 0});
	}
	
	
	//Table Show/Hide
	if($('.table-column-hide-show').length){
		$('.table-column-hide-show button').on('click', function(event) {        
			$(this).parent('.table-column-hide-show').children('ul').toggle();
		});
	}

	function footerStyle() {
		if($('footer').length){
			var windowpos = $(window).scrollTop();
			var topHeight = $('.default-banner').innerHeight() + $('header').innerHeight();
			if (windowpos >= topHeight) {
				$('.scroll-to-top').fadeIn(300);
			} else {
				$('.scroll-to-top').fadeOut(300);
			}
		}
	}

	// Scroll to top
	if($('.scroll-to-top').length){
		$(".scroll-to-top").on('click', function() {
			// animate
			$('html, body').animate({
				scrollTop: $('html, body').offset().top
			}, 1000);
		});
	}
	
	//Number Masking
	if($('.masking').length){
    	$('.masking').mask("99999-9999999-9");
	}
	
	//Number Masking
	if($('.phone-masking').length){
    	$('.phone-masking').mask("9999999999");
	}

	
	// Function to get URL parameters
	function getUrlParameter(name) {
		name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
		var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
		var results = regex.exec(window.location.search);
		return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
	}
	
	// Get `last_followup_date` from URL
	var lastFollowupDate = getUrlParameter('last_followup_date');
	var startDate = moment().subtract(3, 'days'); // default start date if needed
	var endDate = moment().add(3, 'days');        // default end date if needed
	
	// Get `next_followup_date` from URL
	var nextFollowupDate = getUrlParameter('next_followup_date');
	if (nextFollowupDate) {
		var dates = nextFollowupDate.split(' - ');
		if (dates.length === 2) {
			startDate = moment(dates[0], 'MMMM D, YYYY');
			endDate = moment(dates[1], 'MMMM D, YYYY');

			$("#next-followup-date").val(nextFollowupDate);

			$("#next-followup-date").data('daterangepicker').setStartDate(startDate);
			$("#next-followup-date").data('daterangepicker').setEndDate(endDate);
		}
	} else {
		$("#next-followup-date").val('');
	}
	
	// Get `next_followup_date` from URL
	var nextFollowupDate = getUrlParameter('next_followup_date');
	if (nextFollowupDate) {
		var dates = nextFollowupDate.split(' - ');
		if (dates.length === 2) {
			startDate = moment(dates[0], 'MMMM D, YYYY');
			endDate = moment(dates[1], 'MMMM D, YYYY');
			$("#next-followup-date").val(nextFollowupDate);
		}
	} else {
		$("#next-followup-date").val('');
	}

	//Daterange
	function daterange() {
		//Last Followup Date
		$("#last-followup-date").val('');
		$("#last-followup-date").daterangepicker({
			format: "MM/DD/YYYY",
			minDate: "01/01/2001",
			maxDate: "12/31/2100",
			dateLimit: { days: 60 },
			showDropdowns: true,
			showWeekNumbers: true,
			timePicker: false,
			timePickerIncrement: 1,
			timePicker12Hour: true,
			autoUpdateInput: !!lastFollowupDate,
			startDate: startDate,
			endDate: endDate,
			opens: "left",
			drops: "down",
			buttonClasses: ["btn", "btn-sm"],
			applyClass: "btn-success",
			cancelClass: "btn-secondary",
			separator: " to ",
			locale: {
				applyLabel: "Submit",
				cancelLabel: "Cancel",
				fromLabel: "From",
				toLabel: "To",
				customRangeLabel: "Custom",
				daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
				monthNames: [
					"January", "February", "March", "April", "May", "June",
					"July", "August", "September", "October", "November", "December"
				],
				firstDay: 1
			},
			ranges: {
				Today: [moment(), moment()],
				Yesterday: [moment().subtract(1, "days"), moment().subtract(1, "days")],
				"Last 7 Days": [moment().subtract(6, "days"), moment()],
				"Last 30 Days": [moment().subtract(29, "days"), moment()],
				"This Month": [moment().startOf("month"), moment().endOf("month")],
				"Last Month": [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]
			}
		}, function (start, end) {
			// Update the input field with the selected date range
			$("#last-followup-date").val(start.format("MMMM D, YYYY") + " - " + end.format("MMMM D, YYYY"));
		});

		
		//Next Followup Date
		$("#next-followup-date").val('');
		$("#next-followup-date").daterangepicker({
			format: "MM/DD/YYYY",
			minDate: "01/01/2001",
			maxDate: "12/31/2100",
			dateLimit: { days: 60 },
			showDropdowns: true,
			showWeekNumbers: true,
			timePicker: false,
			timePickerIncrement: 1,
			timePicker12Hour: true,
			autoUpdateInput: false,
			startDate: moment().subtract(3, "days"),
			endDate: moment().add(3, "days"),
			opens: "left",
			drops: "down",
			buttonClasses: ["btn", "btn-sm"],
			applyClass: "btn-success",
			cancelClass: "btn-secondary",
			separator: " to ",
			locale: {
				applyLabel: "Submit",
				cancelLabel: "Cancel",
				fromLabel: "From",
				toLabel: "To",
				customRangeLabel: "Custom",
				daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
				monthNames: [
					"January", "February", "March", "April", "May", "June",
					"July", "August", "September", "October", "November", "December"
				],
				firstDay: 1
			},
			ranges: {
				Today: [moment(), moment()],
				Yesterday: [moment().subtract(1, "days"), moment().subtract(1, "days")],
				"Last 7 Days": [moment().subtract(6, "days"), moment()],
				"Last 30 Days": [moment().subtract(29, "days"), moment()],
				"This Month": [moment().startOf("month"), moment().endOf("month")],
				"Last Month": [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]
			}
		}, function (start, end) {
			// Update the input field with the selected date range
			$("#next-followup-date").val(start.format("MMMM D, YYYY") + " - " + end.format("MMMM D, YYYY"));
		});
		
		
		//Lead Added Date
		$("#lead-added-date").val('');
		$("#lead-added-date").daterangepicker({
			format: "MM/DD/YYYY",
			minDate: "01/01/2001",
			maxDate: "12/31/2100",
			dateLimit: { days: 60 },
			showDropdowns: true,
			showWeekNumbers: true,
			timePicker: false,
			timePickerIncrement: 1,
			timePicker12Hour: true,
			autoUpdateInput: false,
			startDate: moment().subtract(3, "days"),
			endDate: moment().add(3, "days"),
			opens: "left",
			drops: "down",
			buttonClasses: ["btn", "btn-sm"],
			applyClass: "btn-success",
			cancelClass: "btn-secondary",
			separator: " to ",
			locale: {
				applyLabel: "Submit",
				cancelLabel: "Cancel",
				fromLabel: "From",
				toLabel: "To",
				customRangeLabel: "Custom",
				daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
				monthNames: [
					"January", "February", "March", "April", "May", "June",
					"July", "August", "September", "October", "November", "December"
				],
				firstDay: 1
			},
			ranges: {
				Today: [moment(), moment()],
				Yesterday: [moment().subtract(1, "days"), moment().subtract(1, "days")],
				"Last 7 Days": [moment().subtract(6, "days"), moment()],
				"Last 30 Days": [moment().subtract(29, "days"), moment()],
				"This Month": [moment().startOf("month"), moment().endOf("month")],
				"Last Month": [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]
			}
		}, function (start, end) {
			// Update the input field with the selected date range
			$("#lead-added-date").val(start.format("MMMM D, YYYY") + " - " + end.format("MMMM D, YYYY"));
		});
		
		
		// Today Date Range
		$("#last-followup-today-date").val(moment().format("MMMM D, YYYY") + " - " + moment().format("MMMM D, YYYY"));
		$("#last-followup-today-date").daterangepicker({
			format: "MM/DD/YYYY",
			minDate: "01/01/2001",
			maxDate: "12/31/2100",
			dateLimit: { days: 60 },
			showDropdowns: true,
			showWeekNumbers: true,
			timePicker: false,
			timePickerIncrement: 1,
			timePicker12Hour: true,
			autoUpdateInput: false,
			startDate: moment(),
			endDate: moment(),
			opens: "left",
			drops: "down",
			buttonClasses: ["btn", "btn-sm"],
			applyClass: "btn-success",
			cancelClass: "btn-secondary",
			separator: " to ",
			locale: {
				applyLabel: "Submit",
				cancelLabel: "Cancel",
				fromLabel: "From",
				toLabel: "To",
				customRangeLabel: "Custom",
				daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
				monthNames: [
					"January", "February", "March", "April", "May", "June",
					"July", "August", "September", "October", "November", "December"
				],
				firstDay: 1
			},
			ranges: {
				Today: [moment(), moment()],
				Yesterday: [moment().subtract(1, "days"), moment().subtract(1, "days")],
				"Last 7 Days": [moment().subtract(6, "days"), moment()],
				"Last 30 Days": [moment().subtract(29, "days"), moment()],
				"This Month": [moment().startOf("month"), moment().endOf("month")],
				"Last Month": [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]
			}
		}, function (start, end) {
			// Update the input field with the selected date range
			$("#last-followup-today-date").val(start.format("MMMM D, YYYY") + " - " + end.format("MMMM D, YYYY"));
		});

	}
	
	// Call the daterange function
	daterange();


	//Drag and Drop
	// $(".sortable tbody").sortable({
	// 	handle: ".drag-icon", // Only drag when clicking the handle
	// 	update: function (event, ui) {
	// 		var order = [];
	// 		$('.sortable tbody tr').each(function (index, element) {
	// 			order.push({
	// 				id: $(this).find('.drag-icon').data("id"),
	// 				position: index + 1
	// 			});
	// 		});
	// 	}
	// });

	//Drag and Drop
	$(".sortable tbody").sortable({
		handle: ".drag-icon",
		update: function(event, ui) {
			// This will update hidden sort order inputs when order changes
			updateSortOrder();
		}
	});

	// Function to update sort order values
	function updateSortOrder() {
		$('.sortable tbody tr').each(function(index) {
			$(this).find('input[name="sort_order[]"]').val(index + 1);
		});
	}

	// Call this initially to set the order
	updateSortOrder();

	
	
	$(document).on("click", function(e){
		var objects_menu = $("#payment-info-section");
		if ($(objects_menu).hasClass('show')) {
			$(objects_menu).removeClass('show');
		}
		//e.preventDefault();
	});
	

    /* ==========================================================================
       When document is ready, do
       ========================================================================== */

    $(document).on('ready', function () {
		footerStyle();

		//Datepicker
		jQuery(".datepicker").datepicker({
			"setDate": new Date(),
			autoclose: true,
			format: 'yyyy-mm-dd',
			todayHighlight: true,
			orientation: "bottom left",
		});

		jQuery(".today_date").datepicker({
			"setDate": new Date(),
			"autoclose": true,
			format: 'yyyy-mm-dd',
		}).datepicker('setDate', 'today'),

		jQuery("#datepicker-autoclose").datepicker({autoclose:!0,todayHighlight:!0}),
		jQuery("#datepicker-inline").datepicker(),
		jQuery("#datepicker-multiple-date").datepicker({format:"yyyy-mm-dd",clearBtn:!0,multidate:!0,multidateSeparator:","})
		
		//Table Show/Hide
		$(".show_hide_check:not(:checked)").each(function() {
			var column = "table ." + $(this).attr("name");
			$(column).hide();
		});
		
		$(".show_hide_check:checkbox").click(function(){
			var column = "table ." + $(this).attr("name");
			$(column).toggle();
		});
		
		
    });

    /* ==========================================================================
       When Window is Scrolling, do
       ========================================================================== */

    $(window).on('scroll', function () {
		footerStyle();
    });

    /* ==========================================================================
       When Window is loaded, do
       ========================================================================== */

    $(window).on('load', function () {

    });

    /* ==========================================================================
       When Window is Resizing, do
       ========================================================================== */

    $(window).on('resize', function () {
        
    });


})(window.jQuery);
