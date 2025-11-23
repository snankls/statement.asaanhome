jQuery.fn.center = function (random) {
	this.css("position", "absolute");

	let top = Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) + $(window).scrollTop())
	let left = Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) + $(window).scrollLeft())

	if (typeof random !== "undefined" && random) {
		top = CF.GetRandomInt(1, ($(window).height() - $(this).outerHeight()));
		left = CF.GetRandomInt(1, ($(window).width() - $(this).outerWidth()));
	}
	this.css("top", top + "px");
	this.css("left", left + "px");
	return this;
}

Object.defineProperty(String.prototype, 'capitalize', {
	value: function() {
		return this.charAt(0).toUpperCase() + this.slice(1);
	},
	enumerable: false
});

var CUSTOM_FUNCTIONS = {}

function get_object_attribute(data_object, object_keys, default_value) {
	if (typeof default_value == 'undefined')
		default_value = '';

	try {
		var value = data_object;
		if (object_keys.length > 0) {
			for (k in object_keys)
				if (object_keys.hasOwnProperty(k))
					value = value[object_keys[k]];
		}

		default_value = value

	} catch (err) {

	}
	// if(typeof data_object != 'undefined' && typeof data_object[object_key] != 'undefined')
	//     default_value = data_object[object_key];

	return default_value;
}

function addFormatter(input, formatFn) {
	let oldValue = input.value;

	const handleInput = event => {
		const result = formatFn(input.value, oldValue, event);
		if (typeof result === 'string') {
			input.value = result;
		}

		oldValue = input.value;
	}

	handleInput();
	input.addEventListener("input", handleInput);
}

Number.prototype.toFixedCustom = function(precesion){
	var x = Number(this);
	return CF.toFixed(x, precesion);
}

var CommonFunctions = function () {

	return {
		DateFormat: "DD-MMM-YYYY",
		DateTimeFormat: "DD-MMM-YYYY hh:mm A",
		Decimal_Places: 4,
		Decimal_Places_Display: 2,
		//Accounting: accounting,
		//toFixed: accounting.toFixed,
		LOCAL_STORAGE: window.localStorage,
		LOCAL_STORAGE_KEY: 'fadera_erp-',
		SESSION_STORAGE: window.sessionStorage,
		SESSION_STORAGE_KEY: 'fadera_erp-',

		GetLoader: function (loader_type) {

			if (typeof loader_type === "undefined")
				loader_type = "small";

			if (loader_type == "big")
				return loader_big();
			else if (loader_type == "tiny")
				return '<i class="fa fa-spinner fa-spin loader">';

			return loader_small();
		},

		IsOffScreen: function (el, return_diff) {

			if (typeof return_diff === "undefined")
				return_diff = false

			let rect = el.getBoundingClientRect();
			let is_offscreen = (
				(rect.x + rect.width) < 0
				|| (rect.y + rect.height) < 0
				|| (rect.x > window.innerWidth || rect.y > window.innerHeight)
			);
			if (is_offscreen && return_diff) {

				let difference = {
					right: rect.x - window.innerWidth
				}
				return difference;
				// console.log(rect.x + rect.width)
				// console.log(rect.y + rect.height)
				// console.log(rect.x > window.innerWidth)
				// console.log(rect.y > window.innerHeight)
			}

			return is_offscreen;
		},

		GetUrlVars: function () {
			var vars = {};
			var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (m, key, value) {
				vars[key] = value;
			});
			return vars;
		},

		ToastMessage: function (heading, text, position, icon, color) {
			$.toast({
				heading: heading,
				text: text,
				position: position,
				icon: icon,
				loader: false,        // Change it to false to disable loader
				loaderBg: color  // To change the background
			})
		},

		HandleFormFieldErrors: function (data_obj, move_to_first_error) {

			console.log('HandleFormFieldErrors');

			if (typeof move_to_first_error === 'undefined')
				move_to_first_error = true;

			if (typeof data_obj.move_to_first_error !== 'undefined')
				move_to_first_error = data_obj.move_to_first_error;

			$('.form-group.has-error .field_container .form-control').each(function () {
				CF.ShowFieldError(this.name);
			});
			if (data_obj.hasOwnProperty('field_errors') && data_obj.field_errors != '' && Object.keys(data_obj.field_errors).length) {
				for (var key in data_obj.field_errors) {
					if (data_obj.field_errors.hasOwnProperty(key)) {
						CF.ShowFieldError(key, data_obj.field_errors[key], true, false);
					}
				}
			}

			// global_level_errors
			if (data_obj.hasOwnProperty('field_errors') && data_obj.field_errors != '' &&
				data_obj.field_errors.hasOwnProperty('__all__') && Object.keys(data_obj.field_errors.__all__).length) {

				for (var key in data_obj.field_errors.__all__) {
					if (data_obj.field_errors.__all__.hasOwnProperty(key)) {
						display_danger(data_obj.field_errors.__all__[key])
					}
				}

			}

			if (move_to_first_error)
				CF.MoveToFirstErrorMessage();
		},

		GetFieldSelectorJs: function (field_identifier) {
			return CF.GetFieldSelector(field_identifier, 'js');
		},

		GetFieldSelector: function (field_identifier, return_type, return_index) {

			var field_selector = undefined;

			if (field_identifier instanceof jQuery)
				field_selector = field_identifier;
			else if ($(field_identifier).length > 0)
				field_selector = $(field_identifier);
			else if (typeof field_identifier === 'object' && field_identifier !== null)
				field_selector = $(field_identifier);
			else if ($('.' + field_identifier + 'Field').length > 0)
				field_selector = $('.' + field_identifier + 'Field');
			else if ($('input[name="' + field_identifier + '"]').length > 0)
				field_selector = $('input[name="' + field_identifier + '"]');
			else if ($('#' + field_identifier).length > 0)
				field_selector = $('#' + field_identifier);
			else if ($('.' + field_identifier).length > 0)
				field_selector = $('.' + field_identifier);
			else if ($('#id_' + field_identifier).length > 0)
				field_selector = $('#id_' + field_identifier);

			if (typeof field_selector !== 'undefined') {
				if (typeof return_index !== 'undefined')
					field_selector = field_selector.eq(return_index);
				else if (typeof return_index == 'first')
					field_selector = field_selector.eq(0);
				else if (typeof return_index == 'last')
					field_selector = field_selector.eq(-1);

				if (return_type == 'js')
					field_selector = field_selector.eq(0)[0];
			}

			return field_selector
		},

		PostDataTo: function (url, postData, close_current_window) {

			if (typeof close_current_window !== 'undefined' && close_current_window) {

				var window_closed = window.top.close();

				// windows is not closed for some reason.
				if (typeof window_closed === 'undefined') {
					CF.AjaxStop();
					IGNORE_HASH_CHANGE = true;
					history.go(-1);
				}

			}

			var dynamicForm = document.createElement("form");
			dynamicForm.method = "POST";
			if ('method' in postData) {
				dynamicForm.method = postData.method;
				delete postData['method'];
			}

			dynamicForm.action = url;
			if (typeof postData.open_in_current_window !== 'undefined' && postData.open_in_current_window) {
				delete postData.open_in_current_window;
			} else
				dynamicForm.target = '_blank';

			for (var k in postData) {
				var dynamicInput = document.createElement("input");
				$(dynamicInput)
					.attr("name", k)
					.attr("value", postData[k]);
				dynamicForm.appendChild(dynamicInput);
			}

			document.body.appendChild(dynamicForm);

			dynamicForm.submit();
			document.body.removeChild(dynamicForm);

		},

		PostAjaxData: function (url, ajax_data) {

			if (typeof url === 'object') {
				ajax_data = url;
				url = ajax_data.url;
			}

			console.log('PostAjaxData');

			if (typeof ajax_data.data_table_id === 'undefined')
				ajax_data['data_table_id'] = DataTablesOperations.GetId();

			var form_data = new FormData();
			if (typeof ajax_data.form_id != 'undefined')
				form_data = new FormData($("#" + ajax_data.form_id)[0]);

			for (var key in ajax_data) {
				if (ajax_data.hasOwnProperty(key))
					form_data.append(key, ajax_data[key]);
			}

			if ($('#' + ajax_data.data_table_id).length && $('#' + ajax_data.data_table_id).hasClass('dataTable')) {

				var table = $('table#' + ajax_data.data_table_id).DataTable();

				if (!$('#' + ajax_data.data_table_id).hasClass('skip_post_table_data')) {
					var TableData = table.rows().data();

					for (var index in TableData) {
						if ($.isNumeric(index))
							form_data.append('table_data', JSON.stringify(TableData[index]))
					}
				}

				if (!$('#' + ajax_data.data_table_id).hasClass('skip_post_table_ajax_data')) {
					var table_ajax_data = table.ajax.json();
					if (typeof table_ajax_data != 'undefined') {
						delete table_ajax_data['data'];
						form_data.append('table_ajax_data', JSON.stringify(table_ajax_data));
					}
				}

				let table_filter_data = DTO.GetPostData({}, ajax_data.data_table_id);
				for (k in table_filter_data) {
					if (table_filter_data.hasOwnProperty(k))
						form_data.append(k, table_filter_data[k])
				}

			}

			if (typeof ajax_data.hide_ajax_loader == 'undefined' || ajax_data.hide_ajax_loader == 0)
				CF.AjaxStart('PostAjaxData');

			if (typeof ajax_data.disable_buttons !== "undefined")
				CF.SetButtonDisabled(ajax_data.disable_buttons)

			return $.ajax({
				type: "POST",
				url: url,
				dataType: 'json',
				data: form_data,
				cache: false,
				processData: false,
				contentType: false,
				success: function (response) {

					if (typeof response.loader_container !== "undefined")
						$(response.loader_container).html('');

					CF.UpdateLocalStorageData(response);

					$.extend(response, ajax_data);

					if (typeof ajax_data.destroy_modal !== "undefined")
						CF.DestroyModalDialog();

					if (typeof ajax_data.callback_before_redirect !== "undefined" && ajax_data.callback_before_redirect) {
						CommonFunctions.ExecuteCallback(response, 'callback_before_redirect');
					} else if (CF.CheckAndRedirect(response)) {
						CF.AjaxStop('PostAjaxDataRedirected');
						return false;
					} else if (ajax_data.callback) {
						CommonFunctions.ExecuteCallback(response, 'callback');
					} else if (response.success)
						CF.BuildNormalModalDialog(response);

					else if (response.message)
						CF.BuildNormalModalDialog(response);

					else if (!response.hide_message)
						CF.BuildNormalModalDialog("Something went wrong please try again");

					if (ajax_data.reload_table || response.reload_table)
						load_data_table.reload(ajax_data.data_table_id);

					if (typeof response.hide_ajax_loader === 'undefined' || response.hide_ajax_loader === 0)
						CF.AjaxStop('PostAjaxData');

					if (typeof response.reload_page !== 'undefined' && response.reload_page)
						window.location.reload()

					if (typeof ajax_data.disable_buttons !== "undefined")
						CF.SetButtonDisabled(ajax_data.disable_buttons, false);


				},
				error: function (res) {

					if (ajax_data.error_callback) {
						ajax_data.response = res;
						CommonFunctions.ExecuteCallback(ajax_data, 'error_callback');
					} else {
						CF.AjaxStop('PostAjaxData Error');
						console.log(res);
					}
				}
			});

		},

		IsArray: function (obj) {
			return Array.isArray(obj);
		},

		ArrayRemoveValue: function (values_array, value) {
			for (var i = 0; i < values_array.length; i++) {
				if (values_array[i] === value) {
					values_array.splice(i, 1);
				}
			}
			return values_array
		},

		ArrayUnique: function (array_with_duplicates) {
			return Array.from(new Set(array_with_duplicates))
		},

		InitializePopovers: function () {

			// Initialize Popovers
			$('[data-toggle="popover"], .enable-popover').popover({
				container: 'body', animation: true, html: 'true',
				showCallback: function () {

					// console.log('popover show call back');
					// console.log($(this.$element));
					// console.log($(this.$tip));
					// popover_opener = $(this.$element)

					var triggerer_element = this.$element;

					var close_button = document.createElement('button');
					$(close_button).attr('type', 'button');
					$(close_button).addClass('pull-right popover-close btn fa fa-times');
					$(close_button).on('click', function () {
						$(triggerer_element).trigger('click');
					});

					if (!$(this.$tip).find('.popover-close').length) {
						$(this.$tip).find('.popover-title').after(close_button);
					} else
						$(close_button).remove();

				},
				hideCallback: function () {
				}
			});

		},

		GetMoneyValue: function (value, e, to_fixed) {

			let money_value = 0;
			let money_symbol = "SAR ";
			let money_format = "%s%v";

			if (typeof e !== "undefined" && typeof $(e).attr('data-symbol') !== "undefined")
				money_symbol = $(e).attr('data-symbol')

			if (typeof e !== "undefined" && typeof $(e).attr('data-money-format') !== "undefined") {
				money_format = $(e).attr('data-money-format')
				if (money_format === 'money') {
					money_format = money_symbol + "%v";
				} else if (money_format === 'percent') {
					money_format = "%v %";
				}

			}

			if(typeof to_fixed === "undefined")
				to_fixed = CF.Decimal_Places_Display;

			if ($.isNumeric(value)) {
				value = CF.NoCurrency(parseFloat(value).toFixedCustom(to_fixed).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));
				money_value += value
			} else
				money_value += "0.0000";

			return CommonFunctions.Accounting.formatMoney(money_value, money_symbol, to_fixed, undefined, undefined, money_format);

		},

		GetNormalValue: function (value, to_fixed, for_display) {

			// Exception for Datatable non editable fields
			if (value === '-')
				return '-';

			if (value === null)
				return null;

			// Possibly uuid is coming in value that needs to be converted.
			try {
				if ((value.match(/-/g) || []).length > 1)
					return value;
			} catch (err) {
			}

			if (typeof for_display === 'undefined')
				for_display = false;

			if (typeof value === 'undefined')
				value = '';

			value = value.toString();

			if (typeof to_fixed === 'undefined')
				to_fixed = CF.Decimal_Places;

			var result = CommonFunctions.Accounting.unformat(value).toFixedCustom(to_fixed);
			if (!for_display)
				result = parseFloat(CommonFunctions.Accounting.formatNumber(result, to_fixed, ""));

			return result

			// try {
			//     var normal_value = value.replace('$', '');
			//     normal_value = normal_value.replace(',', '');
			// }
			// catch (err) {
			//     normal_value = '';
			// }
			//
			// if (normal_value == '')
			//     normal_value = 0
			//
			// return parseFloat(normal_value).toFixed(to_fixed);

		},

		// Repeating function to match definition with python currency functions
		Currency: function (value, e, to_fixed) {
			return CommonFunctions.GetMoneyValue(value, e, to_fixed);
		},

		// Repeating function to match definition with python currency functions
		NoCurrency: function (value, to_fixed, for_display) {
			return CommonFunctions.GetNormalValue(value, to_fixed, for_display)
		},

		UpdateCurrencyFields: function (selector) {

			if (typeof selector === 'undefined') {
				selector = '.currency_field, input.datatables-editor';
			}

			$(selector).each(function () {

				if ($(this).hasClass('text_input') || $(this).parent().hasClass('text_cell'))
					return true;

				if ($(this).hasClass('allow_empty') && $(this).val() === '')
					return true;

				decimal_places = undefined;
				if($(this).attr('data-decimal_places') !== "undefined")
					decimal_places = $(this).attr('data-decimal_places')

				var n_value = CF.Currency(CF.NoCurrency($(this).val(), undefined, decimal_places), this, decimal_places);
				if ($(this).parent().hasClass('hours_cell'))
					n_value = CF.NoCurrency($(this).val(), 2, true);

				if (($(this).hasClass("number_field") || $(this).parent().hasClass('number_cell')))
					n_value = CF.NoCurrency($(this).val(), 0, true);

				$(this).val(n_value);

			});

		},

		ScrollTo: function (element, options) {

			if (typeof options == 'undefined')
				options = {'duration': 3000};

			// https://api.jquery.com/animate/
			$('html, body').animate({
				scrollTop: $(element).offset().top
			}, options.duration);

		},

		SortArrayByKeyDesc: function (array, key, direction) {

			if (typeof direction == 'undefined')
				direction = 'asc';

			if (direction === 'asc') {
				return array.sort(function (a, b) {
					var x = a[key];
					var y = b[key];
					return ((x < y) ? -1 : ((x > y) ? 1 : 0));
				});
			} else {
				return array.sort(function (a, b) {
					var x = a[key];
					var y = b[key];
					return ((x > y) ? -1 : ((x < y) ? 1 : 0));
				});
			}

		},

		PadLeft: function (string_to_pad, pad_length, pad_character) {
			var s = string_to_pad, c = pad_character || '0', len = pad_length || 1;
			while (s.length < len) s = c + s;
			return s;
		},

		RemoveClassStartingWith: function (element, class_to_remove) {
			$(element).removeClass(function (index, className) {
				return (className.match(new RegExp("\\S*" + class_to_remove + "\\S*", 'g')) || []).join(' ')
			});
		},

		UpdateTooltips: function (element) {
			CommonFunctions.RemoveTooltips();
			if (typeof element !== 'undefined') {
				if ($(element).hasClass('build-tooltip')) {
					let val = $(element).val();
					if (element.tagName === 'TEXTAREA')
						val = $(element).text();

					if (element.tagName === 'SELECT')
						val = $('option:selected', element).text();

					if ($(element).hasClass('title-notify'))
						$(element).parent().attr('data-title-notify', val);
					else
						$(element).parent().attr('data-title', val);
				}

				//$(element).tooltip({container: 'body', animation: false});

			} else {
				$('.build-tooltip').each(function () {

					let val = $(this).val();
					if (this.tagName === 'TEXTAREA')
						val = $(this).text();

					if (this.tagName === 'SELECT')
						val = $('option:selected', this).text();

					if ($(this).hasClass('title-notify'))
						$(this).parent().attr('data-title-notify', val);
					else
						$(this).parent().attr('data-title', val);

				});

				//$('[data-toggle="tooltip"], .enable-tooltip').tooltip({container: 'body', animation: false});
			}
		},

		RemoveTooltips: function () {
			$('div.tooltip').remove();
		},

		UpdateDatePickers: function (start_date, end_date, identifier) {

			console.log("updateDatePickers");

			if (typeof start_date == 'undefined')
				start_date = false;

			if (typeof end_date == 'undefined')
				end_date = false;

			if (typeof identifier == 'undefined')
				identifier = false;

			var date_pickers = '.input-datepicker, .input-daterange-datepicker';
			if (identifier)
				date_pickers = CF.GetFieldSelectorJs(identifier);

			$(date_pickers).each(function (index, element) {

				let dp = this;
				var update_values = 1;
				if (typeof $(dp).data('update_values') !== 'undefined')
					update_values = parseInt($(dp).data('update_values'), 10);

				if ($(dp).hasClass('input-daterange')) {
					// if (start_date && update_values !== 0)
					//     $('input:eq(0)', dp).val(start_date);

					// if (end_date && update_values !== 0)
					//     $('input:eq(1)', dp).val(end_date);

					$('input:eq(1)', dp).trigger('keyup');
					$('input:eq(0)', dp).trigger('keyup');
				}

				if (start_date)
					$(dp).attr('data-date-start-date', start_date);

				if (end_date)
					$(dp).attr('data-date-end-date', end_date);

				if (typeof $(dp).attr('data-date-start-date') !== 'undefined')
					$(dp).datepicker('setStartDate', $(dp).attr('data-date-start-date'));

				if (typeof $(dp).attr('data-date-end-date') !== 'undefined')
					$(dp).datepicker('setEndDate', $(dp).attr('data-date-end-date'));

				let format = "dd-M-yyyy";
				if (typeof $(dp).attr('data-date-format') !== 'undefined')
					format = $(dp).attr('data-date-format');

				let options = {
					"format": format
				}
				if (typeof $(dp).attr('data-date-before_show_day') !== 'undefined') {
					var before_show_day = $(dp).attr('data-date-before_show_day');
					if (typeof before_show_day !== 'function') {
						if (before_show_day.search('CUSTOM_FUNCTIONS.') !== -1) {
							before_show_day = before_show_day.replace("CUSTOM_FUNCTIONS.", "");
							before_show_day = CUSTOM_FUNCTIONS[before_show_day]
						} else
							before_show_day = window[before_show_day];
					}
					options.beforeShowDay = before_show_day;
					//$(dp).datepicker({"beforeShowDay": before_show_day});
				}

				if ($(dp).hasClass('input-daterange-datepicker')) {
					CF.InitiateDateRangePicker(this);
				} else {
					$(dp).datepicker(options);

					var value = $(dp).val();
					if (update_values)
						$(dp).datepicker("update", value);
				}

			});

		},

		UpdateDateTimePickers: function (start_date, end_date, identifier) {

			console.log("CommonFunctions.UpdateDateTimePickers");

			if (typeof start_date == 'undefined')
				start_date = false;

			if (typeof end_date == 'undefined')
				end_date = false;

			if (typeof identifier == 'undefined')
				identifier = false;

			var date_pickers = '.input-date_time_picker, .input-date_time_picker_range';
			if (identifier)
				date_pickers = CF.GetFieldSelectorJs(identifier);

			$(date_pickers).each(function (index, element) {

				var update_values = 1;
				if (typeof $(this).data('update_values') != 'undefined')
					update_values = parseInt($(this).data('update_values'), 10);

				if ($(this).hasClass('input-datetimerange')) {
					if (start_date && update_values)
						$('input:eq(0)', this).val(start_date);

					if (end_date && update_values)
						$('input:eq(1)', this).val(end_date);

					$('input:eq(1)', this).trigger('keyup');
					$('input:eq(0)', this).trigger('keyup');
				}

				if (start_date)
					$(this).attr('data-date-start-date', start_date);

				if (end_date)
					$(this).attr('data-date-end-date', end_date);

				// if(typeof $(this).attr('data-date-start-date') != 'undefined')
				//     $(this).datetimepicker('setStartDate', $(this).attr('data-date-start-date'));

				// if(typeof  $(this).attr('data-date-end-date') != 'undefined')
				//     $(this).datetimepicker('setEndDate', $(this).attr('data-date-end-date'));

				if (typeof $(this).attr('data-date-before_show_day') != 'undefined') {
					var before_show_day = $(this).attr('data-date-before_show_day');
					if (typeof before_show_day != 'function') {
						if (before_show_day.search('CUSTOM_FUNCTIONS.') !== -1) {
							before_show_day = before_show_day.replace("CUSTOM_FUNCTIONS.", "");
							before_show_day = CUSTOM_FUNCTIONS[before_show_day]
						} else
							before_show_day = window[before_show_day];
					}
					// $(this).datetimepicker({'beforeShowDay': before_show_day});
				}

				var value = $(this).val();
				// if(update_values)
				// $(this).datetimepicker("update", value);

				if ($(this).hasClass('input-daterange-datepicker')) {
					CF.InitiateDateRangePicker(this);
				} else
					$(this).datetimepicker();

			});

		},

		InitiateDateRangePicker: function (e, custom_options) {

			var options = {
				dateFormat: CF.DateFormat,
				autoUpdateInput: false,
				locale: {
					cancelLabel: 'Clear'
				}
			};

			if(typeof custom_options !== undefined)
				for(k in custom_options)
					options[k] = custom_options[k];

			$(e).daterangepicker(options);
			$(e).attr('data-picker_format', options.dateFormat);

			$(e).on('apply.daterangepicker', function (ev, picker) {
				$(e).val(picker.startDate.format($(e).attr('data-picker_format')) + ' - ' + picker.endDate.format($(e).attr('data-picker_format')));
				$(e).trigger('change');
				if($(e).attr('data-datatable') !== "undefined")
					load_data_table.reload($(e).attr('data-datatable'));
			});

			$(e).on('cancel.daterangepicker', function (ev, picker) {
				$(e).val('');
				$(e).trigger('change');
				if($(e).attr('data-datatable') !== "undefined")
					load_data_table.reload($(e).attr('data-datatable'));
			});

		},

		Round: function (value, decimal_places) {

			if (typeof decimal_places === 'undefined')
				decimal_places = 2;

			return +(Math.round(parseFloat(value) + "e+" + decimal_places) + "e-" + decimal_places);

		},

		TruncateText: function (params) {

			if (params == null) {
				params = {
					str: 'string',
					max_length: null,
					ending: null,
					add_title: null,
				}
			}

			var str = params.str.trim();

			if (params.max_length == null) {
				params.max_length = 100;
			}

			if (params.ending == null) {
				params.ending = '...';
			}

			var return_text = "";

			if (str.length > params.max_length) {

				return_text = str.substring(0, params.max_length - params.ending.length) + params.ending;

				if (params.add_title != null && params.add_title) {
					var span_tag = document.createElement('span');
					$(span_tag)
						.attr('data-toggle', 'tooltip')
						.attr('data-original-title', str)
						.html(return_text);

					return_text = span_tag;
				}

			} else {
				return_text = str;
			}

			return return_text;

		},

		QueryStringToObject: function (queryString, separator, inner_separator) {

			if (typeof separator === 'undefined')
				separator = '&';

			if (typeof inner_separator === 'undefined')
				inner_separator = '=';

			var pairs = queryString.split(separator);
			var result = {};
			pairs.forEach(function (pair) {
				pair = pair.split(inner_separator);
				var name = pair[0]
				var value = pair[1]
				if (name.length)
					if (result[name] !== undefined) {
						if (!result[name].push) {
							result[name] = [result[name]];
						}
						result[name].push(value || '');
					} else {
						result[name] = value || '';
					}
			});
			return (result);
		},

		ObjectToQueryString: function (obj, separator, inner_separator) {

			if (typeof separator === 'undefined')
				separator = '&';

			if (typeof inner_separator === 'undefined')
				inner_separator = '=';

			var str = [];
			for (var p in obj) {
				if (obj.hasOwnProperty(p)) {

					if (CF.IsArray(obj[p]))
						obj[p] = obj[p].join(inner_separator)

					str.push(encodeURIComponent(p) + inner_separator + encodeURIComponent(obj[p]));

				}
			}
			return str.join(separator);
		},

		UpdateChosenSearchBoxes: function () {
			// This function is called on initialization and after changes to any chosen search box.
			console.log('updateChosenSearchBoxes');

			$('select.select-chosen').each(function (i, e) {

				var select_box = this;
				// if(!$(this).hasClass('no-sort') && !$(this).hasClass('multiselect'))
				if ($(this).hasClass('apply-sorting')) {
					var box_values = [];
					$('option:selected', select_box).each(function () {
						box_values.push($(select_box).val())
					});

					var box_options = $("option", select_box);
					box_options.sort(function (a, b) {
						if (a.text > b.text) return 1;
						else if (a.text < b.text) return -1;
						else return 0
					});
					$(this).empty().append(box_options);

					for (var i in box_values)
						$('option[value="' + box_values[i] + '"]', select_box).attr('selected', true);
				}

				if (!$(this).next().hasClass('chosen-container')) {
					disable_search = false;
					if ($(this).hasClass('no-search'))
						disable_search = true;

					$(this).chosen({
						width: "100%",
						search_contains: false,
						enable_split_word_search: false,
						disable_search: disable_search
					});

				} else
					$(this).trigger("chosen:updated");

				if ($(this).hasClass('select-chosen-buttons') && !$(this).parent().find('.select-chosen-all').length) {
					var buttons_container = document.createElement('div');
					$(buttons_container).addClass('select_chosen_buttons_container');

					// chosen all button
					var chosen_all = document.createElement('button');
					$(chosen_all).addClass('select-chosen-all btn btn-primary');
					$(chosen_all).html('Select All');
					$(chosen_all).appendTo(buttons_container);

					// chosen none button
					var chosen_none = document.createElement('button');
					$(chosen_none).addClass('select-chosen-none btn btn-primary');
					$(chosen_none).html('Remove All');
					$(chosen_none).appendTo(buttons_container);
					$(this).parent().append(buttons_container);

				}

			});

			$('form .chosen-container').each(function (index, element) {
				$(this).width('100%');
			});

			$('.no-chosen, .no-chosen-search').each(function (index, element) {

				if ($(this).hasClass('apply-sorting')) {
					var my_options = $("option", this);
					my_options.sort(function (a, b) {
						if (a.text > b.text) return 1;
						else if (a.text < b.text) return -1;
						else return 0
					})
					$(this).empty().append(my_options);
				}

				$(this).next('.chosen-container').eq(0).find('.chosen-search').hide();
			})

		},

		UpdateSelect2SearchBoxes: function () {
			// This function is called on initialization and after changes to any chosen search box.
			console.log('UpdateSelect2SearchBoxes');

			$('select.select2').each(function (i, e) {
				if ($(this).hasClass("select2-hidden-accessible")) {
					$(this).removeClass("select2-hidden-accessible");
					$(this).next(".select2-container").remove();
				}
				$(this).select2();
			});

		},

		PopulateDropdownOptions: function (params) {

			// loads options in dropdown only first time with ajax for each unique identifier
			if (typeof params === 'undefined')
				var params = {
					options_url: '',
					options_callback: '',
					dropdown: '',
					selected: '',
					clear_dropdown: '',
					identifier: '',
				}
			params.hide_ajax_loader = 1;

			console.log('CommonFunctions PopulateDropdownOptions');
			CommonFunctions.ShowFieldLoader(params.dropdown);

			if (typeof params === 'undefined' || typeof params.options_url === 'undefined' || typeof params.dropdown === 'undefined')
				return false;

			if (typeof params.clear_dropdown === 'undefined')
				params.clear_dropdown = false;

			if (typeof params.identifier === 'undefined')
				params.identifier = 'dropdown_options';

			if (params.clear_dropdown) {

			} else {

				if ($('.PageAjaxContent .dynamic_content_block .' + params.identifier).length) {
					$(params.dropdown).html($('.PageAjaxContent .dynamic_content_block .' + params.identifier).html());

					if (typeof $(params.dropdown).attr('data-selected') !== "undefined")
						$('option[value="' + $(params.dropdown).attr('data-selected') + '"]', params.dropdown).prop('selected', true);
					$(params.dropdown).trigger('change');
					// UpdateChosenSearchBoxes();
					CommonFunctions.ExecuteCallback(params, 'options_callback')
					CommonFunctions.HideFieldLoader(params.dropdown);
				} else {

					params.callback = CommonFunctions.PopulateDropdownOptionsResponse;
					CF.PostAjaxData(params.options_url, params);
				}
			}
		},

		PopulateDropdownOptionsResponse: function (response) {

			console.log('PopulateDropdownOptionsResponse ' + response.identifier);

			$('.PageAjaxContent .dynamic_content_block .' + response.identifier).remove();
			if ($('.PageAjaxContent .dynamic_content_block').length == 0)
				$(document.createElement('div')).addClass('dynamic_content_block hidden').appendTo($('.PageAjaxContent'));

			var dropdown_options_container = document.createElement('div');
			$(dropdown_options_container)
				.addClass(response.identifier)
				.html(response.dropdown_options)
				.appendTo($('.PageAjaxContent .dynamic_content_block'));
			$(response.dropdown).html(response.dropdown_options);

			// if(typeof response.selected !== 'undefined' && response.selected)
			$(response.dropdown).val(response.selected)

			// updateChosenSearchBoxes();

			CommonFunctions.ExecuteCallback(response, 'options_callback')
			CommonFunctions.HideFieldLoader(response.dropdown);

			let selected = $(response.dropdown).attr('data-selected');
			if (typeof selected !== "undefined")
				$('option[value="' + selected + '"]', response.dropdown).prop('selected', true);

			if (
				$('option', response.dropdown).length <= 2 && (
					($('option:selected', response.dropdown).length === 0 || $('option:selected', response.dropdown).val() === "") &&
					$('option:eq(0)', response.dropdown).val() === ''
				)
			) {
				$('option', response.dropdown).attr('selected', false);
				$('option:eq(1)', response.dropdown).attr('selected', true);
			}

			$(response.dropdown).trigger('change');

		},

		ExecuteCallback: function (json_obj, callback_name) {
			if (typeof json_obj[callback_name] !== 'undefined') {

				var callback_func = json_obj[callback_name];

				if (!Array.isArray(callback_func))
					callback_func = [callback_func];

				for (i in callback_func) {
					var callback = callback_func[i];
					if (typeof callback !== 'function') {
						if (callback.search('CUSTOM_FUNCTIONS.') !== -1) {
							callback = callback.replace("CUSTOM_FUNCTIONS.", "");
							callback = CUSTOM_FUNCTIONS[callback]
						} else
							callback = window[callback];
					}
					callback(json_obj);

				}

			}
		},

		ShowFieldLoader: function (field_reference) {

			CommonFunctions.HideFieldLoader(field_reference);
			let field_identifier = CF.GetFieldSelectorJs(field_reference);
			let loader = $(document.createElement('i')).addClass('fa fa-spinner fa-spin field_loader');

			if ($(field_identifier).closest('tr').length === 1) {
				$(field_identifier).after(loader);
				$(field_identifier).addClass('hidden');

			} else {

				$(field_identifier).closest('.form-group').find('.col-form-label').append(loader);

			}

		},

		HideFieldLoader: function (field_reference) {

			if (typeof field_reference === 'undefined') {
				$('.field_loader').remove();
			}
			let field_identifier = CF.GetFieldSelectorJs(field_reference);

			if ($(field_identifier).closest('tr').length === 1) {
				$(field_identifier).closest('tr').find('.field_loader').remove();
				$(field_identifier).removeClass('hidden');
			} else {
				$(field_identifier).closest('.form-group').find('.col-form-label .field_loader').remove();
			}

		},

		ShowButtonLoader: function (button_reference) {
			console.log('ShowButtonLoader');

			CommonFunctions.HideButtonLoader(button_reference);
			let button_identifier = CF.GetFieldSelectorJs(button_reference);
			let loader = $(document.createElement('i')).addClass('fa fa-spinner fa-spin button_loader');

			let loader_index = $('.btn').length;
			$(button_identifier).attr('data-loader_index', loader_index);
			$(loader).addClass('loader_index' + loader_index);

			if ($(button_identifier).closest('.btn_wrapper').length === 1) {
				$(button_identifier).closest('.btn_wrapper').prepend(loader);
			} else {
				$(button_identifier).before(loader);
			}

		},

		HideButtonLoader: function (button_reference) {
			console.log('HideButtonLoader');
			let button_identifier = CF.GetFieldSelectorJs(button_reference);

			if (typeof button_reference === 'undefined' || $(button_identifier).length === 0) {
				$('.button_loader').remove();
			}

			let loader_index = $(button_identifier).attr('data-loader_index');
			$('.button_loader.loader_index' + loader_index).remove();

		},

		Notify: function (params) {

			// https://notifyjs.jpillora.com/
			// update in library: replace data-notify-text></span> with data-notify-html></span>
			if (typeof params === 'undefined') {
				// let params = {
				//   // whether to hide the notification on click
				//   clickToHide: true,
				//   // whether to auto-hide the notification
				//   autoHide: true,
				//   // if autoHide, hide after milliseconds
				//   autoHideDelay: 5000,
				//   // show the arrow pointing at the element
				//   arrowShow: true,
				//   // arrow size in pixels
				//   arrowSize: 5,
				//   // position defines the notification position though uses the defaults below
				//   position: '...',
				//   // default positions
				//   elementPosition: 'bottom left',
				//   globalPosition: 'top right',
				//   // default style
				//   style: 'bootstrap',
				//   // default class (string or [string])
				//   className: 'error',
				//   // show animation
				//   showAnimation: 'slideDown',
				//   // show animation duration
				//   showDuration: 400,
				//   // hide animation
				//   hideAnimation: 'slideUp',
				//   // hide animation duration
				//   hideDuration: 200,
				//   // padding between element and notification
				//   gap: 2
				// };

				return false;
			}

			if (typeof params.message === 'undefined')
				return false;

			if (typeof params.position === 'undefined') {
				if (typeof params.identifier !== 'undefined')
					params.position = 'top left';
				else
					params.position = 'top right';
			}

			if (typeof params.className === 'undefined')
				params.className = 'success';

			if (typeof params.identifier !== 'undefined') {
				if (typeof $(params.identifier).attr('data-autoHide') !== 'undefined')
					params.autoHide = parseInt($(params.identifier).attr('data-autoHide'));

				if (typeof $(params.identifier).attr('data-clickToHide') !== 'undefined')
					params.clickToHide = parseInt($(params.identifier).attr('data-clickToHide'));

				if ($(params.identifier).hasClass('show_on_body'))
					params.identifier = undefined
			}

			if (typeof params.autoHide === 'undefined')
				params.autoHide = true;

			params.afterOpen = function () {
				alert('The notice is on the screen.');
			}
			console.log('notify ' + params.message);

			let target = $;
			if (typeof params.identifier !== 'undefined') {
				target = $(params.identifier);
			}

			if (target !== $ && target.length > 1)
				target = $(target[0]);

			target.notify(
				params.message,
				// decodeURIComponent(params.message),
				params
				// {
				//     position: params.position,
				//     className: params.className,
				//     autoHide: params.autoHide
				// }
			);
		},

		ClearNotify: function (container) {

			if (typeof container === 'undefined')
				container = 'body';

			$('.notifyjs-wrapper', container).remove();

		},

		SetFieldReadonly: function (field_identifiers, set_readonly) {

			if (typeof field_identifiers === 'undefined')
				return false;

			if (!Array.isArray(field_identifiers))
				field_identifiers = [field_identifiers];

			if (typeof set_readonly == 'undefined')
				set_readonly = true;

			for (var k in field_identifiers) {

				var field_selector = CF.GetFieldSelector(field_identifiers[k]);
				$(field_selector).each(function () {

					$(this).removeClass('readonly').attr('readonly', false);
					if (set_readonly)
						$(this).addClass('readonly').attr('readonly', true);

				});

			}

		},

		SetFieldRequired: function (field_identifiers, set_required) {

			if (typeof field_identifiers === 'undefined')
				return false;

			if (!Array.isArray(field_identifiers))
				field_identifiers = [field_identifiers];

			if (typeof set_required == 'undefined')
				set_required = true;

			for (var k in field_identifiers) {

				var field_selector = CF.GetFieldSelector(field_identifiers[k]);

				$(field_selector).each(function () {

					$(this).removeClass('not_required').addClass('not_required');
					$(this).closest('.form-group').find('label').find('.text-danger').remove();

					if (set_required) {
						$(this).removeClass('not_required');
						if ($(this).closest('.form-group').find('label span.text-danger').length == 0)
							$(this).closest('.form-group').find('label').append('<span class="text-danger"> *</span>');
					}

				});

			}

		},

		SetFieldVisibility: function (field_identifiers, visibility) {

			if (typeof field_identifiers === 'undefined')
				return false;

			if (!Array.isArray(field_identifiers))
				field_identifiers = [field_identifiers];

			if (typeof visibility == 'undefined')
				visibility = true;

			for (var k in field_identifiers) {
				var field_selector = CF.GetFieldSelector(field_identifiers[k]);

				$(field_selector).each(function () {
					$(this).closest('.form-group').css('visibility', 'hidden');
					if (visibility)
						$(this).closest('.form-group').css('visibility', 'initial');
				});

			}

		},

		SetFieldHidden: function (field_identifiers, hidden) {

			if (typeof field_identifiers === 'undefined')
				return false;

			if (!Array.isArray(field_identifiers))
				field_identifiers = [field_identifiers];

			if (typeof hidden == 'undefined')
				hidden = true;

			for (var k in field_identifiers) {
				var field_selector = CF.GetFieldSelector(field_identifiers[k]);

				$(field_selector).each(function () {

					$(this).closest('.form-group').removeClass('hidden');

					if (hidden)
						$(this).closest('.form-group').removeClass('hidden').addClass('hidden');

				});

			}

		},

		ShowFieldError: function (field_identifiers, message, show_message, notify_field, highlight_field) {

			console.log("ShowFieldError");

			if (typeof field_identifiers === 'undefined')
				return false;

			if (typeof field_identifiers === 'object' && typeof field_identifiers.identifier !== "undefined") {
				message = field_identifiers.message;
				show_message = field_identifiers.show_message;
				notify_field = field_identifiers.notify_field;
				highlight_field = field_identifiers.highlight_field;
				notify_params = field_identifiers.notify_params;
				field_identifiers = field_identifiers.identifier;
			}

			if (typeof show_message === 'undefined')
				show_message = true;

			if (typeof highlight_field === 'undefined')
				highlight_field = true;

			if (typeof notify_field === 'undefined')
				notify_field = false;

			if (!Array.isArray(field_identifiers))
				field_identifiers = [field_identifiers];

			if (message === '')
				show_message = false;

			if (Array.isArray(message))
				message = message.join('<br />');

			for (var k in field_identifiers) {

				var field_selector = CF.GetFieldSelector(field_identifiers[k]);

				$(field_selector).each(function () {

					$(this).closest('.form-group').removeClass('has-success has-error');
					$(this).closest('.form-group').find('.has-error').removeClass('has-error');
					$(this).closest('.form-group').find('.help-block').remove();

					if (typeof message !== 'undefined') {

						if (highlight_field)
							$(this).closest('.form-group').addClass('has-error');

						if (notify_field)
							display_danger({
								message: message,
								identifier: this,
								notify: true,
								notify_params: notify_params
							});
						else if (show_message) {
							if ($(this).closest('.form-group').find('.field_container').length > 0)
								$(this).closest('.form-group').find('.field_container').append('<div class="help-block animation-slideDown">' + message + '</div>');
							else
								$(this).closest('.form-group').append('<div class="help-block animation-slideDown">' + message + '</div>');
						}
					}

				});

			}

		},

		GetFormButtonClicked: function (form) {
			var button_clicked = false;
			if ($('.form_submit_button.clicked', form).length > 0)
				button_clicked = $('.form_submit_button.clicked', form)[0];
			return button_clicked
		},

		ResetFormButtonClicked: function (form) {
			console.log('ResetFormButtonClicked');
			$('.form_submit_button', form).removeClass('clicked');
			$('.form_submit_button', form).attr('disabled', false);
			CF.StopFormLoader(form);
		},

		StartFormLoader: function (form) {

			CF.StopFormLoader(form);

			let loader_container = form;

			if ($('#result', form).length > 0)
				loader_container = $('#result', form)[0];
			else if ($('.form_loader', form).length > 0)
				loader_container = $('.form_loader', form)[0];

			$(loader_container).append(loader_small());

		},

		StopFormLoader: function (form) {

			let loader_container = form;

			if ($('#result', form).length > 0)
				loader_container = $('#result', form)[0];
			else if ($('.form_loader', form).length > 0)
				loader_container = $('.form_loader', form)[0];

			$('.content_loader', loader_container).remove();

		},

		MoveToFirstErrorMessage: function (identifier, container) {
			console.log('MoveToFirstErrorMessage')
			if (typeof container === 'undefined')
				container = 'html, body';

			if (typeof identifier === 'undefined' && $('.form-control.error').length > 0) {
				identifier = '.form-control.error'
				if ($(identifier).length === 0 && $('.notifyjs-bootstrap-error').length > 0) {
					identifier = '.notifyjs-bootstrap-error';
				}
			}

			identifier = CF.GetFieldSelectorJs(identifier);

			console.log('moving to: ');
			console.log(identifier);

			if ($(identifier).length > 0) {

				let accordian = $(identifier).closest('.card');

				if (accordian.length > 0) {
					if (accordian.find('.accordion-heading a').attr('aria-expanded') == 'false')
						accordian.find('.accordion-heading a').click();
				}

				let top_offset = $(identifier).eq(0).offset().top;
				let left_offset = $(identifier).eq(0).offset().left;

				//if(container !== 'html, body')
				{
					left_offset -= 250;
					top_offset -= 350;
				}

				console.log('container');

				$(container).animate({
					scrollTop: top_offset,
					scrollLeft: left_offset,
				}, 1000);
			}
		},

		MoveToElement: function (element, container) {

			// Move screen to a specific element

			if (typeof container === 'undefined')
				var container = 'html, body';

			element = CF.GetFieldSelectorJs(element);

			if ($(element).length > 0) {

				var top_offset = $(element).eq(0).offset().top;
				var left_offset = $(element).eq(0).offset().left;

				if (container !== 'html, body')
					left_offset -= 250;

				$(container).animate({
					scrollTop: top_offset,
					scrollLeft: left_offset,
				}, 1000);
			}
		},

		IsBoolean: function (value) {
			var pattern = /^(0|1)$/i;
			return pattern.test(value);
		},

		IsAlphaNumeric: function (value) {
			var pattern = /^([a-zA-Z0-9 ]+)$/;
			return pattern.test(value);
		},

		IsNumeric: function (value) {
			var pattern = /^(?=.*[0-9])([0-9]+)$/i;
			return pattern.test(value);
		},

		NumericOnly: function (evt, message) {

			if (typeof message === "undefined")
				message = true;

			let specialKeys = new Array();
			specialKeys.push("Backspace");
			specialKeys.push("Delete");
			specialKeys.push("Enter");
			specialKeys.push("Shift");
			// Arrow Keys
			let arrow_keys = ["ArrowUp", "ArrowLeft", "ArrowRight", "ArrowDown"];

			let key = evt.key;
			let keyCode = evt.keyCode;
			let value = $(evt.currentTarget).val();
			if(key == "." && value.split('.').length > 1){
				if (message)
					display_danger('Only one decimal is allowed.');
				evt.stopPropagation();
				evt.preventDefault();
				return false;
			}else{
				let ret = ((key == "." && value.split('.').length == 1) || (keyCode >= 96 && keyCode <= 105) || (keyCode >= 48 && keyCode <= 57) || (keyCode >= 96 && keyCode <= 105) || specialKeys.indexOf(keyCode) != -1 || specialKeys.indexOf(key) != -1 || arrow_keys.indexOf(key) != -1);
				if (!ret) {
					if (message)
						display_danger('Please enter numbers only.');
					evt.stopPropagation();
					evt.preventDefault();
				}
				return ret;
			}



		},

		IsValidEmailAddress: function (value) {
			var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
			return pattern.test(value);
		},

		KeepLastAjaxLoader: function () {
			// Hide all loaders
			$('.AjaxLoadingWrapper.cloned,.AjaxLoadingIndicator.cloned').hide();
			// show only last loader
			$('.AjaxLoadingWrapper.cloned:eq(-1),.AjaxLoadingIndicator.cloned:eq(-1)').show();
		},

		AjaxStart: function (identifier) {

			if (typeof identifier === 'undefined')
				identifier = 'global';

			console.log('ajax_start ' + identifier);
			$('.AjaxLoadingIndicator').not('.cloned').clone().addClass('cloned ' + identifier).prependTo('body');
			$('.AjaxLoadingWrapper').not('.cloned').clone().addClass('cloned ' + identifier).prependTo('body');
			$('.AjaxLoader').html(loader_small());
			$('.data_loader').removeClass('hidden');

			//$('body').prepend('<div class="content_loader_hidden'+identifier+'">Hidden Loading...</div>');

			CF.KeepLastAjaxLoader();

		},

		AjaxStop: function (identifier) {

			if (typeof identifier === 'undefined')
				identifier = 'global';

			console.log('ajax_stop ' + identifier);
			if (identifier === 'all')
				$('.ajaxLoadingProgress.cloned').remove();
			else
				$('.ajaxLoadingProgress.' + identifier).remove();

			//$('.content_loader_hidden.'+identifier).remove();
			$('.AjaxLoader').html('');
			$('.data_loader').addClass('hidden');

			CF.KeepLastAjaxLoader();
		},

		SetButtonDisabled: function (field_identifiers, set_disabled, message) {

			if (typeof field_identifiers === 'undefined')
				return false;

			if (!Array.isArray(field_identifiers))
				field_identifiers = [field_identifiers];

			if (typeof set_disabled == 'undefined')
				set_disabled = true;

			for (var k in field_identifiers) {

				var field_selector = CF.GetFieldSelector(field_identifiers[k]);
				$(field_selector).each(function () {

					var target_span = $(this).closest('span.btn_wrapper')[0];

					$(this).removeClass('disabled').attr('disabled', false);
					if (set_disabled)
						$(this).addClass('disabled').attr('disabled', true);

					if (typeof target_span === 'undefined') {
						new_span = document.createElement('span');
						$(new_span).addClass('enable-tooltip btn_wrapper');
						$(this).after(new_span);
						target_span = $(this).next('span.btn_wrapper')[0];
						$(this).appendTo(target_span);
					}

					$(target_span).removeClass('enable-tooltip')
						.attr("data-toggle", null)
						.attr("data-placement", null)
						.attr("data-original-title", null);

					if (typeof message !== 'undefined') {
						$(target_span).addClass('enable-tooltip btn_wrapper')
							.attr("data-toggle", "modal")
							.attr("data-placement", "top")
							.attr("data-original-title", message);
					}
				});
			}
			CommonFunctions.UpdateTooltips();
		},

		UpdateFieldsWithCheckbox: function (checkbox) {

			if (typeof checkbox === 'undefined')
				checkbox = '.field_with_checkbox .field_checkbox_input'

			$(checkbox).each(function () {
				var field_input = $(this).closest('.field_with_checkbox').find('.field_input')[0];
				$(field_input).addClass('hidden');

				if ($(this).is(':checked')) {
					$(field_input).removeClass('hidden');
				}

			})
		},

		AreCookiesEnabled: function () {

			var cookieEnabled = (navigator.cookieEnabled) ? true : false;

			if (typeof navigator.cookieEnabled == "undefined" && !cookieEnabled) {
				document.cookie = "testcookie";
				cookieEnabled = (document.cookie.indexOf("testcookie") != -1) ? true : false;
			}
			return (cookieEnabled);

		},

		ReadCookie: function (c_name) {

			var c_value = document.cookie;
			var c_start = c_value.indexOf(" " + c_name + "=");
			if (c_start == -1) {
				c_start = c_value.indexOf(c_name + "=");
			}
			if (c_start == -1) {
				c_value = null;
			} else {
				c_start = c_value.indexOf("=", c_start) + 1;
				var c_end = c_value.indexOf(";", c_start);
				if (c_end == -1) {
					c_end = c_value.length;
				}
				c_value = unescape(c_value.substring(c_start, c_end));
			}
			return c_value.replace('"', '').replace('"', '');

		},

		// Add css on the fly.
		AddCss: function (cssCode, uniqueId) {
			var styleElement = document.createElement("style");
			styleElement.setAttribute("id", uniqueId)
			styleElement.type = "text/css";
			if (styleElement.styleSheet) {
				styleElement.styleSheet.cssText = cssCode;
			} else {
				styleElement.appendChild(document.createTextNode(cssCode));
			}
			document.getElementsByTagName("head")[0].appendChild(styleElement);
		},

		// Remove css added on the fly.
		RemoveCss: function (uniqueId) {
			var sheetToBeRemoved = document.getElementById(uniqueId);
			var sheetParent = sheetToBeRemoved.parentNode;
			sheetParent.removeChild(sheetToBeRemoved);
		},

		SetDynamicStorage: function (key, value) {
			console.log("SetDynamicStorage");
			var storage_key = CF.LOCAL_STORAGE_KEY + key;
			var content_container = ".dynamic_content[data-storage_key=" + storage_key + "]";
			if ($(content_container).length == 0) {
				let new_container = document.createElement('div');
				$(new_container).addClass("dynamic_content");
				$(new_container).attr("data-storage_key", storage_key);
				$('body .content-page .content').prepend(new_container);
			}

			$(content_container).html(value);

		},

		GetDynamicStorage: function (key, is_json) {

			if (typeof is_json === "undefined")
				is_json = false

			var storage_key = CF.LOCAL_STORAGE_KEY + key;
			var content_container = ".dynamic_content[data-storage_key=" + storage_key + "]";
			let content = $(content_container).html();
			content = typeof content !== "undefined" ? content : is_json ? "{}" : "";
			return content

		},

		RemoveDynamicStorage: function (key, search_key) {

			var storage_key = CF.LOCAL_STORAGE_KEY + key;
			if (typeof search_key === 'undefined')
				search_key = false;

			if (search_key) {
				let s_data = CF.GetDynamicStorageData(key);
				for (let k in s_data) {
					CF.RemoveDynamicStorage(k);
				}
			} else
				$(".dynamic_content[data-storage_key=" + storage_key + "]").remove();
		},

		GetDynamicStorageData: function (contains_key, parse) {
			console.log("GetDynamicStorageData");

			if (typeof parse === 'undefined')
				parse = false;

			var storage_key = CF.LOCAL_STORAGE_KEY;

			let storage_keys = [];
			$('.dynamic_content').each(function () {
				storage_keys.push($(this).attr('data-storage_key'))
			})
			let storage_data = {};
			for (i in storage_keys) {
				if (storage_keys[i].indexOf(contains_key) !== -1) {
					let key = storage_keys[i].replace(storage_key, "");
					let storage = CF.GetDynamicStorage(key, parse);
					if (parse)
						storage = JSON.parse(storage);

					storage_data[key] = storage;

				}
			}
			return storage_data;

		},

		SetLocalStorage: function (key, value, sticky) {
			// all keys starting with LOCAL_STORAGE_KEY in start will be reset in before_page_load function
			// through ClearLocalStorage
			// except sticky keys
			var storage_key = CF.LOCAL_STORAGE_KEY;
			if (typeof sticky === 'undefined')
				sticky = false;

			if (sticky)
				storage_key += 'sticky-';

			CF.LOCAL_STORAGE.setItem(storage_key + key, value);
		},

		GetLocalStorage: function (key, sticky) {

			var storage_key = CF.LOCAL_STORAGE_KEY;
			if (typeof sticky === 'undefined')
				sticky = false;

			if (sticky)
				storage_key += 'sticky-';

			return CF.LOCAL_STORAGE.getItem(storage_key + key);

		},

		GetLocalStorageData: function (contains_key, sticky, parse) {
			console.log("GetLocalStorageData");
			var storage_key = CF.LOCAL_STORAGE_KEY;
			if (typeof sticky === 'undefined')
				sticky = false;

			if (typeof parse === 'undefined')
				parse = false;

			if (sticky)
				storage_key += 'sticky-';

			let storage_keys = Object.keys(CF.LOCAL_STORAGE);
			let storage_data = {};
			for (i in storage_keys) {
				if (storage_keys[i].indexOf(contains_key) !== -1) {
					let key = storage_keys[i].replace(storage_key, "");
					let storage = CF.GetLocalStorage(key, sticky);
					if (parse)
						storage = JSON.parse(storage);

					storage_data[key] = storage;

				}
			}
			return storage_data;

		},

		RemoveLocalStorage: function (key, sticky, search_key) {

			var storage_key = CF.LOCAL_STORAGE_KEY;
			if (typeof sticky === 'undefined')
				sticky = false;

			if (typeof search_key === 'undefined')
				search_key = false;

			if (sticky)
				storage_key += 'sticky-';

			if (search_key) {
				let s_data = CF.GetLocalStorageData(key, sticky);
				for (let k in s_data) {
					CF.RemoveDynamicStorage(k);
				}
			} else
				CF.LOCAL_STORAGE.removeItem(storage_key + key);
		},

		ClearLocalStorage: function () {

			var arr = []; // Array to hold the keys
			// Iterate over localStorage and insert the keys that meet the condition into arr
			for (var i = 0; i < CF.LOCAL_STORAGE.length; i++) {
				if (CF.LOCAL_STORAGE.key(i).startsWith(CF.LOCAL_STORAGE_KEY)) {
					arr.push(CF.LOCAL_STORAGE.key(i));
				}
			}

			// Iterate over arr and remove the items by key excluding keys with sticky word
			for (var i = 0; i < arr.length; i++) {
				if (arr[i].indexOf('sticky-') === -1)
					CF.LOCAL_STORAGE.removeItem(arr[i]);
			}

		},

		UpdateLocalStorageData: function (res) {
			if (typeof res.display_modal != 'undefined' && res.display_modal) {
				var storage_key = '';
				if (res.storage_key != 'undefined')
					storage_key = res.storage_key;

				CF.SetLocalStorage(storage_key + 'modal_title', res.modal_title);
				CF.SetLocalStorage(storage_key + 'modal_body', res.modal_body);
				CF.SetLocalStorage(storage_key + 'modal_width', res.modal_width);
			}
		},

		SetSessionStorage: function (key, value, sticky) {
			// all keys starting with LOCAL_STORAGE_KEY in start will be reset in before_page_load function
			// through ClearSessionStorage
			// except sticky keys
			var storage_key = CF.SESSION_STORAGE_KEY;
			if (typeof sticky === 'undefined')
				sticky = false;

			if (sticky)
				storage_key += 'sticky-';

			CF.SESSION_STORAGE.setItem(storage_key + key, value);

		},

		GetSessionStorage: function (key, sticky) {

			var storage_key = CF.SESSION_STORAGE_KEY;
			if (typeof sticky === 'undefined')
				sticky = false;

			if (sticky)
				storage_key += 'sticky-';

			return CF.SESSION_STORAGE.getItem(storage_key + key);
		},

		RemoveSessionStorage: function (key, sticky) {

			var storage_key = CF.SESSION_STORAGE_KEY;
			if (typeof sticky === 'undefined')
				sticky = false;

			if (sticky)
				storage_key += 'sticky-';

			CF.SESSION_STORAGE.removeItem(storage_key + key);
		},

		ClearSessionStorage: function () {

			var arr = []; // Array to hold the keys
			// Iterate over localStorage and insert the keys that meet the condition into arr
			for (var i = 0; i < CF.SESSION_STORAGE.length; i++) {
				if (CF.SESSION_STORAGE.key(i).startsWith(CF.SESSION_STORAGE_KEY)) {
					arr.push(CF.SESSION_STORAGE.key(i));
				}
			}

			// Iterate over arr and remove the items by key excluding keys with sticky word
			for (var i = 0; i < arr.length; i++) {
				if (arr[i].indexOf('sticky-') === -1)
					CF.SESSION_STORAGE.removeItem(arr[i]);
			}

		},

		UpdateSessionStorageData: function (res) {
			if (typeof res.display_modal != 'undefined' && res.display_modal) {
				var storage_key = '';
				if (res.storage_key != 'undefined')
					storage_key = res.storage_key;

				CF.SetSessionStorage(storage_key + 'modal_title', res.modal_title);
				CF.SetSessionStorage(storage_key + 'modal_body', res.modal_body);
				CF.SetSessionStorage(storage_key + 'modal_width', res.modal_width);
			}
		},

		GetRandomInt: function (min, max) {
			min = Math.ceil(min);
			max = Math.floor(max);
			return Math.floor(Math.random() * (max - min) + min); //The maximum is exclusive and the minimum is inclusive
		},

		BuildModalDialog: function (params_json) {

			console.log('BuildModalDialog');

			var context = {
				modal_id: 0,
				multi: 0,
				title: '',
				display_title: true,
				message: 'Are you sure ?',
				load_url: false,
				load_arg_json: {},
				proceed_text: 'Proceed',
				cancel_text: 'Cancel',
				close_text: 'Close',
				hide_footer: false,
				proceed_callback: false,
				cancel_callback: false,
				close_callback: false,
				proceed_arg_json: {},
				cancel_arg_json: {},
				close_arg_json: {},
				model_width: '300px',
				model_type: 'confirmation',
				buttons: [] // any extra action buttons with name and callback function.
			}

			for (var key in params_json) {
				context[key] = params_json[key];
			}

			if (context.send_params_to_proceed_callback)
				context['proceed_arg_json'] = params_json;

			if (context.send_params_to_cancel_callback)
				context['cancel_arg_json'] = params_json;

			if (context.send_params_to_close_callback)
				context['close_arg_json'] = params_json;

			// context.buttons['proceed'] = {text: context.proceed_text, callback: context.proceed_callback, arg: context.proceed_arg_json}
			// context.buttons['cancel'] = {text: context.cancel_text, callback: context.cancel_callback, arg: context.cancel_arg_json}
			// context.buttons['close'] = {text: context.close_text, callback: context.close_callback, arg: context.close_arg_json}
			// var dynamic_model = document.createElement('div');
			// $(dynamic_model)
			// 	.attr('id', 'jquery_custom_model')
			// 	.attr('tabindex', -1)
			// 	.attr('role', 'dialog')
			// 	.attr('aria-hidden', 'true')
			// 	.attr('class', 'modal');

			let dynamic_model = $('#jquery_custom_model').clone();

			if (!context.modal_id && context.multi)
				context.modal_id = "jquery_custom_multi_modal_" + $('[id^="jquery_custom_multi_modal"]').length;

			if (!context.modal_id)
				context.modal_id = "jquery_custom_model_cloned_" + $('.jquery_custom_model_cloned').length;

			let new_modal_class = "jquery_custom_model_cloned " + context.modal_id
			CF.DestroyModalDialog(context.modal_id);

			if (context.multi) {
				new_modal_class += " multi_modal";
				context.max_width = "none";
			}

			dynamic_model.attr('id', context.modal_id);
			dynamic_model.addClass(new_modal_class);
			dynamic_model.find('.modal-dialog').css({width: context.model_width});
			if (typeof context.max_width !== "undefined")
				dynamic_model.find('.modal-dialog').css({'max-width': context.max_width});

			dynamic_model.find('.modal-title').html(context.title);

			if (!context.title || context.title == '') {
				dynamic_model.find('.modal-header').remove();
			} else {
				dynamic_model.find('.modal-header').prepend('<i class="mdi mdi-cursor-move modal_move_handle"></i>');
				//dynamic_model.find('.modal-header').prepend('<i class="mdi modal_move_handle"></i>');
			}

			var popup_error_messages = $('.popup_error_messages').clone();
			popup_error_messages.attr('id', 'popup_error_messages_cloned');
			if (context.load_url) {

				CF.AjaxStart('ModalDialog');
				let load_args = CF.ObjectToQueryString(context.load_arg_json);
				dynamic_model.find('.modal-body').html("Loading...");
				dynamic_model.find('.modal-body').load(context.load_url + '?' + load_args, function (load_response) {

					try {
						let load_response = JSON.parse(load_response);
						if (typeof load_response.RedirectTo !== "undefined") {
							dynamic_model.modal('hide');
							window.location.href = load_response.RedirectTo;
						}
					} catch (e) {
					}

					dynamic_model.find('.modal-body').prepend(popup_error_messages);
					CF.AjaxStop('ModalDialog');
				});

			} else {

				dynamic_model.find('.modal-body').html(context.message);
				dynamic_model.find('.modal-body').prepend(popup_error_messages);

			}

			if (context.buttons.length > 0) {
				let button = dynamic_model.find('.modal-footer button:eq(0)').clone()[0];
				dynamic_model.find('.modal-footer button').remove();
				for (let i in context.buttons) {
					let b = context.buttons[i];
					let m_b = $(button).clone()[0];
					$(m_b)
						.attr('id', b.name + "_button")
						.text(b.text)
						.appendTo(dynamic_model.find('.modal-footer')[0])
					;

				}
			} else {
				if (context.model_type == 'confirmation') {
					dynamic_model.find('.modal-footer').find('#save_button').text(context.proceed_text);
					dynamic_model.find('.modal-footer').find('#cancel_button').text(context.cancel_text);
				} else {
					dynamic_model.find('.modal-footer').find('#save_button').text(context.close_text);
					dynamic_model.find('.modal-footer').find('#cancel_button').hide();
				}
			}

			if (context.hide_footer) {
				dynamic_model.find('.modal-footer').remove();
			}

			$('body').append(dynamic_model);
			let modal_dom = false;
			if (!context.multi) {
				modal_dom = $('#' + context.modal_id)
					.modal({
						backdrop: 'static',
						keyboard: false
					});
			} else {
				modal_dom = $('#' + context.modal_id)[0];
				$(modal_dom).show();
				$(modal_dom).center(1);

				// $('body')
				// 	.css('padding-right', 0)
				// 	.removeClass('modal-open');
				// $('.modal-backdrop.show').remove();

			}

			if (modal_dom) {
				$('#' + context.modal_id)
					.draggable({
						containment: 'parent',
						handle: ".modal_move_handle"
					}).removeClass('ui-draggable')
				;

				if (context.buttons.length > 0) {

					for (let i in context.buttons) {
						let b = context.buttons[i];
						$(modal_dom).on('click', '#' + b.name + '_button', function (e) {
							if (b.callback) {

								if (!Array.isArray(b.callback))
									b.callback = [b.callback];

								for (let i in b.callback) {
									if (typeof b.callback[i] === 'function')
										b.callback[i](b.arg);
									else {
										var callback = window[b.callback[i]];
										callback(b.arg);
									}
								}

							} else
								CF.DestroyModalDialog(context.modal_id);

						});
					}

				} else {
					$(modal_dom).on('click', '#save_button', function (e) {
						if (context.proceed_callback) {

							if (!Array.isArray(context.proceed_callback))
								context.proceed_callback = [context.proceed_callback];

							for (i in context.proceed_callback) {
								if (typeof context.proceed_callback[i] === 'function')
									context.proceed_callback[i](context.proceed_arg_json);
								else {
									var callback = window[context.proceed_callback[i]];
									callback(context.proceed_arg_json);
								}
							}

						} else
							CF.DestroyModalDialog(context.modal_id);

					});
					$(modal_dom).on('click', '#cancel_button', function () {

						console.log('Model Cancel');

						if (context.cancel_callback) {

							if (!Array.isArray(context.cancel_callback))
								context.cancel_callback = [context.cancel_callback];

							for (i in context.cancel_callback) {
								if (typeof context.cancel_callback[i] === 'function')
									context.cancel_callback[i](context.cancel_arg_json);
								else {
									var callback = window[context.cancel_callback[i]];
									callback(context.cancel_arg_json);
								}
							}
						} else
							CF.DestroyModalDialog(context.modal_id);

					});
				}
			}

			return modal_dom;

		},

		DestroyModalDialog: function (modal_id, hide_only) {
			console.log('DestroyModalDialog');

			if (typeof modal_id !== "undefined") {
				$('#' + modal_id).modal('hide').remove();
				$('#' + modal_id).remove();
			} else {
				// Remove previously created dialog
				$('[id^="jquery_custom_model"]').modal('hide');
				$('[id^="confirm_box"]').modal('hide');
				if (typeof hide_only === "undefined" || !hide_only) {
					$('[id^="jquery_custom_model"]:not(.sample_modal)').remove();
					$('[id^="confirm_box"]').remove();
				}
				$('#popup_error_messages_cloned').remove();
			}

		},

		BuildNormalModalDialog: function (message, button_text, title, model_width, multi, modal_id) {

			if (typeof title == 'undefined' || title == null)
				title = false;

			if (typeof button_text == 'undefined' || button_text == null)
				button_text = 'Ok';

			if (typeof model_width == 'undefined' || model_width == null)
				model_width = '300px';

			if (typeof multi === 'undefined' || multi == null)
				multi = 0;

			if (typeof modal_id === 'undefined' || modal_id == null)
				modal_id = 0;

			var dialog_params = {
				modal_id: modal_id,
				multi: multi,
				title: title,
				message: message,
				model_type: 'normal',
				close_text: button_text,
				model_width: model_width
			};

			if (typeof message === 'object') {

				if (typeof message.model_width !== 'undefined')
					dialog_params.model_width = message.model_width;

				dialog_params.message = message.message;

			}

			CF.BuildModalDialog(dialog_params);

		},

		CloseModal: function () {
			$('#jquery_custom_model_cloned').modal('hide');
		},

		ConfirmBox: function (message, title, callback, proceed_text) {
			if ($('#confirm_box').length == 0) {
				CF.BuildModalDialog({
					modal_id: 'confirm_box',
					title: title,
					message: message,
					proceed_callback: callback,
					proceed_text: proceed_text
				})
			}
		},

		GetViewActions: function (page, record_id, from) {

			$('.view-actions-container').html('<i class="fa fa-spin fa-spinner"></i>')

			if (typeof from === "undefined")
				from = "backend"

			let ajax_data = {
				page: page,
				record_id: record_id,
				callback: function (res) {
					$('.view-actions-container').html(res.view_actions);
					if ($('.more_view_actions').length > 0)
						$('.view-actions-container').append($('.more_view_actions').html());
				}
			};

			if (typeof page.page !== 'undefined') {
				$.extend(ajax_data, page);
			}

			ajax_data.url = site_url + from + "/get_view_actions";
			CF.PostAjaxData(ajax_data)

		},

		CheckAndRedirect: function (response) {

			if (response.hasOwnProperty('RedirectTo') && response.RedirectTo !== false) {

				CF.SetLocalStorage('ajax-data', CF.ObjectToQueryString(response));

				if (response.ExternalLink)
					CF.PostDataTo(response.RedirectTo, {method: 'GET'}, true);
				else
					window.location.href = response.RedirectTo;

				return true;
			}

			return false;

		},

		GetUniqueId: function (prefix, number) {

			if (typeof number === "undefined")
				number = 1;

			if ($('#' + prefix + number).length > 0)
				return CF.GetUniqueId(prefix, number += 1)
			else
				return prefix + number;
		},

		GetElementProperties: function (e) {

			let props = {
				'height': 0,
				'width': 0,
				'marginTop': 0,
				'marginBottom': 0,
				'marginLeft': 0,
				'marginRight': 0,
				'paddingTop': 0,
				'paddingBottom': 0,
				'paddingLeft': 0,
				'paddingRight': 0,
			}

			if (typeof e !== "undefined") {
				let hide_element = false;
				if (!$(e).is(':visible')) {
					hide_element = true;
					$(e).show();
				}

				props = {
					'height': parseFloat(window.getComputedStyle(e).height),
					'width': parseFloat(window.getComputedStyle(e).width),
					'marginTop': parseFloat(window.getComputedStyle(e).marginTop),
					'marginBottom': parseFloat(window.getComputedStyle(e).marginBottom),
					'marginLeft': parseFloat(window.getComputedStyle(e).marginLeft),
					'marginRight': parseFloat(window.getComputedStyle(e).marginRight),
					'paddingTop': parseFloat(window.getComputedStyle(e).paddingTop),
					'paddingBottom': parseFloat(window.getComputedStyle(e).paddingBottom),
					'paddingLeft': parseFloat(window.getComputedStyle(e).paddingLeft),
					'paddingRight': parseFloat(window.getComputedStyle(e).paddingRight)
				}

				if (hide_element) {
					hide_element = false;
					$(e).hide();
				}

			}

			return props;

		},

		GetHeight: function (e, include_margin, include_padding) {

			if (typeof include_margin === "undefined")
				include_margin = 1

			if (typeof include_padding === "undefined")
				include_padding = 1

			let props = CF.GetElementProperties(e, include_margin, include_padding)
			if (include_margin)
				props.height += props.marginTop + props.marginBottom;
			if (include_padding)
				props.height += props.paddingTop + props.paddingBottom;

			return props.height

		},

		GetWidth: function (e, include_margin, include_padding) {

			if (typeof include_margin === "undefined")
				include_margin = 1

			if (typeof include_padding === "undefined")
				include_padding = 1

			let props = CF.GetElementProperties(e, include_margin, include_padding)
			if (include_margin)
				props.width += props.marginLeft + props.marginRight;
			if (include_padding)
				props.width += props.paddingLeft + props.paddingRight;

			return props.width

		},

		GetNextHeight: function (e, include_margin, include_padding, for_pdf) {

			if (typeof for_pdf === "undefined")
				for_pdf = 1;

			let next_tag = $(e).next();
			if (for_pdf) {
				if (e.tagName === 'TR') {
					next_tag = $(e).next('tr');
					if (next_tag.length === 0) {
						// This was last tr
						next_tag = $(e).parent('table').next();

					}

				}

				if (next_tag.length > 0) {
					if (next_tag[0].tagName === 'TABLE') {
						next_tag = $('>tbody>tr', next_tag[0])
					}

				}


			}
			return CF.GetHeight(next_tag[0]);

		},

		GetPdfNextTag: function (e) {

			let next_tag = $(e).next()[0];
			if (e.tagName === 'TR') {
				next_tag = $(e).next('tr')[0];
				if ($(next_tag).length === 0) {
					// This was last tr
					next_tag = $(e).parent('table').next()[0];
				}

			}

			if ($(next_tag).length > 0) {
				if (next_tag.tagName === 'TABLE') {
					next_tag = $('>tbody>tr', next_tag)[0]
				}

			}

			return next_tag;

		},

		BuildPrintPreview: function (params) {
		},

		Switchery: function (e) {

			if (typeof e !== "undefined") {
				e = CF.GetFieldSelectorJs(e);
				new Switchery(e, $(e).data());
			} else {
				$('[data-plugin="switchery"]').each(function (idx, obj) {
					new Switchery($(this)[0], $(this).data());
				});
			}

		},
		
		Coa1Changed: function (e, selected) {

			console.log('.Coa1Changed');

			let container = $('body')[0];
			if ($(e).closest('tr').length)
				container = $(e).closest('tr')[0];

			if (typeof selected === 'undefined')
				selected = $(e).attr('data-selected');

			if (typeof selected === 'undefined')
				selected = 0;

			let params = {
				project_id: $('.project-dropdown option:selected', container).val(),
				options_url: site_url + "api/get_coa_1_name_json",
				dropdown: $('.coa-1-dropdown', container)[0],
				selected: selected
			}

			if(typeof $(e).attr('data-target') !== "undefined")
				params.dropdown = $(e).attr('data-target');

			// if($(e).closest('form').length){
			// 	params.dropdown = $(e).closest('form').find('.region-dropdown')[0];
			// }

			CF.PopulateDropdownOptions(params);

		},
		
		Coa2Changed: function (e, selected) {

			console.log('.Coa2Changed');

			let container = $('body')[0];
			if ($(e).closest('tr').length)
				container = $(e).closest('tr')[0];

			if (typeof selected === 'undefined')
				selected = $(e).attr('data-selected');

			if (typeof selected === 'undefined')
				selected = 0;

			let params = {
				coa_1_id: $('.coa-1-dropdown option:selected', container).val(),
				coa_2_id: $('option:selected', e).val(),
				options_url: site_url + "api/get_coa_2_name_json",
				dropdown: $('.coa-2-dropdown', container)[0],
				selected: selected
			}

			if(typeof $(e).attr('data-target') !== "undefined")
				params.dropdown = $(e).attr('data-target');

			// if($(e).closest('form').length){
			// 	params.dropdown = $(e).closest('form').find('.region-dropdown')[0];
			// }

			CF.PopulateDropdownOptions(params);

		},
		
		Coa3Changed: function (e, selected) {

			console.log('.Coa3Changed');

			let container = $('body')[0];
			if ($(e).closest('tr').length)
				container = $(e).closest('tr')[0];

			if (typeof selected === 'undefined')
				selected = $(e).attr('data-selected');

			if (typeof selected === 'undefined')
				selected = 0;

			let params = {
				//coa_1_id: $('.coa-1-dropdown option:selected', container).val(),
				coa_2_id: $('.coa-2-dropdown option:selected', container).val(),
				coa_3_id: $('option:selected', e).val(),
				options_url: site_url + "api/get_coa_3_name_json",
				dropdown: $('.coa-3-dropdown', container)[0],
			}

			if(typeof $(e).attr('data-target') !== "undefined")
				params.dropdown = $(e).attr('data-target');

			// if($(e).closest('form').length){
			// 	params.dropdown = $(e).closest('form').find('.region-dropdown')[0];
			// }

			CF.PopulateDropdownOptions(params);

		},
		
		Coa4Changed: function (e, selected) {

			console.log('.Coa4Changed');

			let container = $('body')[0];
			if ($(e).closest('tr').length)
				container = $(e).closest('tr')[0];

			if (typeof selected === 'undefined')
				selected = $(e).attr('data-selected');

			if (typeof selected === 'undefined')
				selected = 0;

			let params = {
				project_id: $('.project-id option:selected', container).val(),
				options_url: site_url + "api/get_coa_4_name_json",
				dropdown: $('.coa-4-dropdown', container)[0],
			}

			if(typeof $(e).attr('data-target') !== "undefined")
				params.dropdown = $(e).attr('data-target');

			// if($(e).closest('form').length){
			// 	params.dropdown = $(e).closest('form').find('.region-dropdown')[0];
			// }

			CF.PopulateDropdownOptions(params);

		},
		
		PropertyTypesChanged: function (e, selected) {

			console.log('.PropertyTypesChanged');

			let container = $('body')[0];
			if ($(e).closest('tr').length)
				container = $(e).closest('tr')[0];

			if (typeof selected === 'undefined')
				selected = $(e).attr('data-selected');

			if (typeof selected === 'undefined')
				selected = 0;

			let params = {
				project_id: $('.project-dropdown option:selected', container).val(),
				options_url: site_url + "api/get_property_name_json",
				dropdown: $('.property-type-dropdown', container)[0],
				selected: selected
			}

			if(typeof $(e).attr('data-target') !== "undefined")
				params.dropdown = $(e).attr('data-target');

			// if($(e).closest('form').length){
			// 	params.dropdown = $(e).closest('form').find('.region-dropdown')[0];
			// }

			CF.PopulateDropdownOptions(params);

		},
		
		BookingPropertyTypesChanged: function (e, selected) {

			console.log('.BookingPropertyTypesChanged');

			let container = $('body')[0];
			if ($(e).closest('tr').length)
				container = $(e).closest('tr')[0];

			if (typeof selected === 'undefined')
				selected = $(e).attr('data-selected');

			if (typeof selected === 'undefined')
				selected = 0;

			let params = {
				property_type: $('.project-dropdown', container).attr('data-property'),
				project_id: $('.project-dropdown option:selected', container).val(),
				options_url: site_url + "api/get_booking_property_name_json",
				dropdown: $('.property-type-dropdown', container)[0],
				selected: selected,
			}

			if(typeof $(e).attr('data-target') !== "undefined")
				params.dropdown = $(e).attr('data-target');

			// if($(e).closest('form').length){
			// 	params.dropdown = $(e).closest('form').find('.region-dropdown')[0];
			// }

			CF.PopulateDropdownOptions(params);

		},
		
		BookingUnitChanged: function (e, selected) {

			console.log('.BookingUnitChanged');

			let container = $('body')[0];
			if ($(e).closest('tr').length)
				container = $(e).closest('tr')[0];

			if (typeof selected === 'undefined')
				selected = $(e).attr('data-selected');

			if (typeof selected === 'undefined')
				selected = 0;

			let params = {
				project_id: $('.project-dropdown option:selected', container).val(),
				property_type_id: $('.property-type-dropdown option:selected', container).val(),
				booking_id: $('.property-type-dropdown', container).attr('data-booking'),
				inventory_id: $('.project-dropdown option:selected', container).attr('data-inventory'),
				
				options_url: site_url + "api/get_unit_number_json",
				dropdown: $('.unit-dropdown', container)[0],
				selected: selected
			}

			if(typeof $(e).attr('data-target') !== "undefined")
				params.dropdown = $(e).attr('data-target');

			// if($(e).closest('form').length){
			// 	params.dropdown = $(e).closest('form').find('.region-dropdown')[0];
			// }
			
			CF.PopulateDropdownOptions(params);

		},
		
		BookingSearchPropertyTypesChanged: function (e, selected) {

			console.log('.BookingSearchPropertyTypesChanged');

			let container = $('body')[0];
			if ($(e).closest('tr').length)
				container = $(e).closest('tr')[0];

			if (typeof selected === 'undefined')
				selected = $(e).attr('data-selected');

			if (typeof selected === 'undefined')
				selected = 0;

			let params = {
				project_id: $('.project-dropdown option:selected', container).val(),
				options_url: site_url + "api/get_booking_search_property_name_json",
				dropdown: $('.property-type-dropdown', container)[0],
				selected: selected,
			}

			if(typeof $(e).attr('data-target') !== "undefined")
				params.dropdown = $(e).attr('data-target');

			// if($(e).closest('form').length){
			// 	params.dropdown = $(e).closest('form').find('.region-dropdown')[0];
			// }

			CF.PopulateDropdownOptions(params);

		},
		
		BookingSearchUnitChanged: function (e, selected) {

			console.log('.BookingSearchUnitChanged');

			let container = $('body')[0];
			if ($(e).closest('tr').length)
				container = $(e).closest('tr')[0];

			if (typeof selected === 'undefined')
				selected = $(e).attr('data-selected');

			if (typeof selected === 'undefined')
				selected = 0;

			let params = {
				project_id: $('.project-dropdown option:selected', container).val(),
				property_type_id: $('.property-type-dropdown option:selected', container).val(),
				
				options_url: site_url + "api/get_search_unit_number_json",
				dropdown: $('.unit-dropdown', container)[0],
				selected: selected
			}

			if(typeof $(e).attr('data-target') !== "undefined")
				params.dropdown = $(e).attr('data-target');

			// if($(e).closest('form').length){
			// 	params.dropdown = $(e).closest('form').find('.region-dropdown')[0];
			// }
			
			CF.PopulateDropdownOptions(params);

		},
		
		FinanceQueryChanged: function (e, selected) {

			console.log('.FinanceQueryChanged');

			let container = $('body')[0];
			if ($(e).closest('tr').length)
				container = $(e).closest('tr')[0];

			if (typeof selected === 'undefined')
				selected = $(e).attr('data-selected');

			if (typeof selected === 'undefined')
				selected = 0;

			let params = {
				project_id: $('.project-dropdown option:selected', container).val(),
				
				options_url: site_url + "api/get_finance_query_json",
				dropdown: $('.query-dropdown', container)[0],
				selected: selected
			}

			if(typeof $(e).attr('data-target') !== "undefined")
				params.dropdown = $(e).attr('data-target');

			// if($(e).closest('form').length){
			// 	params.dropdown = $(e).closest('form').find('.region-dropdown')[0];
			// }
			
			CF.PopulateDropdownOptions(params);

		},

		BuildResizableTables: function () {

			$("table.resizable").colResizable({disable: true});
			$("table.resizable th:gt(1), table.resizable td:gt(1)").css('width', 'auto');
			$("table.resizable").colResizable({
				liveDrag: true,
				gripInnerHtml: "<div class='grip'></div>"
			});

		},

		LoadProductPriceLists: function () {

			$('select.product_price_list_id').each(function () {

				let params = {
					options_url: site_url + "backend/get_products_price_lists_json",
					dropdown: this
				}
				CF.PopulateDropdownOptions(params)

			});

		},

		InitTreeTable: function (table_ref, force) {

			if (typeof force === "undefined")
				force = false;

			table_ref = CF.GetFieldSelectorJs(table_ref);
			$(table_ref).treetable({
				expandable: true,
				indent: 6,
				onInitialized: {
					apply: function (tree) {
						console.log('tree initialed')
						console.log(tree)
						//$(tree.table).find('tr').find('td').find('.indenter').not(':eq(0)').remove();
					}
				}
			}, force);
			CF.InitTreeTableDragDrop(table_ref);

		},

		InitTreeTableDragDrop: function (table_ref) {

			table_ref = CF.GetFieldSelectorJs(table_ref);

			$(table_ref).find(".ui-draggable").draggable({
				helper: "clone",
				opacity: .75,
				refreshPositions: true,
				revert: "invalid",
				revertDuration: 300,
				scroll: true
			});

			$(table_ref).find(".ui-draggable").each(function () {

				$(this).parents("tr").droppable({
					accept: ".ui-draggable",
					drop: function (e, ui) {
						var droppedEl = ui.draggable.parents("tr");
						$(table_ref).treetable("move", droppedEl.data("ttId"), $(this).data("ttId"));
					},
					hoverClass: "accept",
					over: function (e, ui) {
						var droppedEl = ui.draggable.parents("tr");
						if (this != droppedEl[0] && !$(this).is(".expanded")) {
							$(table_ref).treetable("expandNode", $(this).data("ttId"));
						}
					}
				});
			});

		},

		ModalRecordSetup: function (obj, copy) {
			CF.DestroyModalDialog();
			console.log('modal_record_setup');

			if (typeof copy === "undefined")
				copy = false;

			let title = 'Create';
			$('.sample-html.setup-html .page_setup_form .record_id').val('');
			$('.page_setup_form').each(function () {
				if ($(this).closest('.sample-html.setup-html').length === 0)
					this.reset();
			});

			let create = $(obj).attr('data-create') === "1"
			let row_data = {}
			if (!create) {
				if (copy)
					title = 'Copy';
				else
					title = 'Edit';
			}

			CF.UpdateSelect2SearchBoxes();

			let modal_options = {
				title: title + ' ' + $('.setup-html .page_setup_form').data('title'),
				message: $('.sample-html.setup-html').html(),
				proceed_text: "Submit",
				proceed_callback: function () {
					$('.modal-body .page_setup_form').submit();
				},
			}

			if (typeof $(obj).attr('data-model_width') !== "undefined" && $(obj).attr('data-model_width'))
				modal_options.model_width = $(obj).attr('data-model_width');

			let setup_modal = CF.BuildModalDialog(modal_options)

			if (!create) {
				let row = $(obj).closest('tr')[0];
				let table_instance = DTO.GetInstance(2);
				let row_data = table_instance.rows(row).data()[0];
				for (let k in row_data) {
					let el = $('.page_setup_form [name="' + k + '"]', setup_modal)[0];
					let v = row_data[k];
					if (typeof el !== "undefined" && !$(el).hasClass('static')) {

						if (el.tagName === 'SELECT') {
							$('option', el).prop('selected', false);
							$("option", el).filter(function () {
								return $(this).val() == v || $(this).text() == v;
							}).prop('selected', true);

							$(el).attr('data-selected', v).trigger('change');
						} else {
							$(el).val('' + v + '');
						}

					}

				}
				if (copy)
					$('.page_setup_form .record_id', setup_modal).val('');

				if (typeof CUSTOM_FUNCTIONS.load_modal_data !== "undefined") {
					CUSTOM_FUNCTIONS.load_modal_data(row_data);
				}
			}

			CF.UpdateDatePickers();
			// $('.selectpicker').selectpicker('refresh');
		},

		StickyButtons: function (ref) {
			if (typeof ref === "undefined")
				ref = '.sticky-element'
			let options = {
				menuBg: '#2c2c2c',
				menuHeight: '20px;',
				menuPadding: '5px 1%',
				menuBtnMargin: '0'
			}
			$(ref).each(function () {
				//$(this).stickybuttons(options);
			});
		},

		CalculatePercentage: function (v1, v2) {
			let percentage = 0;
			if (typeof v2 !== "undefined" || v2 > 0) {
				percentage = (parseFloat(v1) * 100) / v2;
			}
			return CF.NoCurrency(percentage);
		},

		CalculateValue: function (a, operator, b) {
			let result = "";
			if (operator === "+")
				result = a + b;
			else if (operator === "-")
				result = a - b;
			else if (operator === "/")
				result = a / b;
			else if (operator === "*")
				result = a * b;

			return result;

		},

		BringToTop: function (obj, ref) {

			let maximum = null;
			$(ref).each(function () {
				let value = parseFloat($(this).css('z-index'));
				maximum = (value > maximum) ? value : maximum;
			});
			$(obj).css('z-index', maximum + 1);
		},

		InitialiseImageUpload: function (container, manual_options) {

			console.log('InitialiseImageUpload');

			if (typeof container === "undefined")
				container = ".image_uploader";

			$(container).each(function (index) {

				let options = {
					fileUpload: "#image-upload-data",
					input: "#image-upload",
					// label: "Drag & Drop the file or Click to Browse",
					label: '<img class="image_placeholder" src="' + site_url + 'images/placeholder.png">',
					dragDrop: true,
					maxSize: "2 MB",
					multiple: false,
					maxFile: 1,
					fileType: ["jpg", "jpeg", "png"],
					required: $(this).hasClass('required'),
					required_error: "Image is required",
					existing_image: ""
				}

				let ele = this;

				for (k in options) {
					if (typeof $(ele).attr('data-' + k) !== "undefined")
						options[k] = $(ele).attr('data-' + k);
				}

				if (typeof $(ele).attr('data-input') !== "undefined") {
					options.input = $(ele).attr('data-input');
					options.fileUpload = options.input + "-data";
				}

				if (typeof manual_options !== "undefined") {
					for (k in manual_options) {
						options[k] = manual_options[k];
					}
				}

				if (options.input.charAt(0) !== "#" && options.input.charAt(0) !== ".") {
					options.input = "#" + options.input;
					options.fileUpload = "#" + options.fileUpload;
				}

				if (options.required) {
					let file_identifier = options.fileUpload.replace('#', '');
					if (file_identifier.charAt(0) === ".")
						file_identifier = file_identifier.substring(1);

					$(ele).after("<input data-required_error='" + options.required_error + "' type='hidden' class='required' name='" + file_identifier + "' id='" + file_identifier + "' class='" + file_identifier + "'>");
				}

				$(ele).aksFileUpload(options);
				if (options.multiple === false)
					$(options.input).attr('name', $(options.input).attr('name').replace("[]", ""));

				if (options.existing_image) {
					$('.aks-file-upload-content', ele).append('<div data-file="' + options.input + '" data-file-type="jpg" class="aks-file-upload-preview " style="height: 240px;"><header class="aks-file-upload-p-header"><div class="aks-file-upload-p-header-icon"><svg width="22" height="22" viewBox="0 0 101 121" xmlns="http://www.w3.org/2000/svg"><defs><linearGradient id="aksimageicona" x1=".5" x2=".5" y2="1"><stop stop-color="#36D2AD" offset="0"></stop><stop stop-color="#2DBC9A" offset="1"></stop></linearGradient><filter id="aksimageiconb" color-interpolation-filters="sRGB"filterUnits="userSpaceOnUse"><feFlood flood-opacity="0" result="BackgroundImageFix"></feFlood><feColorMatrix in="SourceAlpha" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"></feColorMatrix><feOffset dx="0" dy="-2"></feOffset><feGaussianBlur stdDeviation="1"></feGaussianBlur><feColorMatrixvalues="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.09019608 0"></feColorMatrix><feBlend in2="BackgroundImageFix" result="effect0_dropShadow"></feBlend><feBlend in="SourceGraphic" in2="effect0_dropShadow" result="shape"></feBlend></filter></defs><g transform="translate(.5 .5)"><g fill-rule="evenodd"><pathd="m60 0 40 40v66c0 7.732-6.268 14-14 14h-72c-7.732 0-14-6.268-14-14v-92c0-7.732 6.268-14 14-14h46z"fill="url(#aksimageicona)" fill-opacity=".8"></path><path transform="matrix(-1 0 0 -1 100 40)" d="M0 0L25.2929 0C33.0249 0 40 6.97512 40 14.7071L40 40L0 0Z" fill="#fff" fill-opacity=".4" filter="url(#aksimageiconb)"></path></g><g transform="translate(20 50)"><pathd="m42.224 0h-40.002c-1.2274 0-2.2223 0.99512-2.2223 2.2227v35.556c0 1.2269 0.9949 2.2209 2.2223 2.2209h40.002c1.2274 0 2.2223-0.994 2.2223-2.2209v-35.556c0-1.2276-0.995-2.2227-2.2223-2.2227zm-4.4449 33.334h-31.112v-11.111l6.6669-6.6674 11.112 11.112 6.6667-6.6678 6.667 6.6678v6.6663zm-4.4447-17.778c-2.4547 0-4.4444-1.9895-4.4444-4.4442 0-2.4556 1.9897-4.4445 4.4444-4.4445s4.4447 1.9889 4.4447 4.4445c0 2.4547-1.9899 4.4442-4.4447 4.4442z"fill="#fff"></path></g></g></svg></div><div class="aks-file-upload-p-header-content"><span class="aks-file-upload-title"></span><span class="aks-file-upload-size"></span></div><div class="aks-file-upload-delete" data-delete="' + options.input + '"><svg width="26" height="26" viewBox="0 0 26 26" xmlns="http://www.w3.org/2000/svg"><path d="M11.586 13l-2.293 2.293a1 1 0 0 0 1.414 1.414L13 14.414l2.293 2.293a1 1 0 0 0 1.414-1.414L14.414 13l2.293-2.293a1 1 0 0 0-1.414-1.414L13 11.586l-2.293-2.293a1 1 0 0 0-1.414 1.414L11.586 13z"fill="currentColor" fill-rule="nonzero"></path></svg></div></header><main class="aks-file-upload-p-main"><div class="aks-file-upload-image"><img src="' + options.existing_image + '" style="height: 240px;"/></div></main></div>');
					aks_file_uploaded_callback(this);
				}


			});

		},

		InitialiseTelephoneInputs: function (element) {

			console.log('InitialiseTelephoneInputs');

			if (typeof element === "undefined")
				element = $('input[type="tel"]');

			let options = {
				initialCountry: 'SA',
				preferredCountries: ["sa", "pk", "us",],
				separateDialCode: true,
				nationalMode: false,
				utilsScript: site_url + '/admin/plugins/intl-tel-input/build/js/utils.js'
			};

			if (typeof window.preferredCountries !== "undefined")
				options.preferredCountries = window.preferredCountries;

			$(element).each(function () {
				let e = this;
				let name = $(e).attr('name');
				let number = $(e).val();
				if ($("." + name + "_display").length === 0) {
					let new_input = $(e).clone()[0];
					$(new_input).attr('type', 'hidden');
					$(new_input).removeAttr('class');
					$(new_input).addClass(name + "_full");
					$(new_input).val(number);
					$(e).after(new_input);
					$(e).attr('name', name + "_display");
				}

				let instance = $(e).intlTelInput(options);

				e.addEventListener("countrychange", function (event) {
					$("." + name + "_full").val($(e).intlTelInput("getNumber").replace(" ", ""));
					countries = window.intlTelInputGlobals.getCountryData()

					queries = "";
					for (i in countries) {
						country_data = countries[i];
						queries += "update glm_countries set iso2='" + country_data.iso2 + "', dial_code='" + country_data.dialCode + "' WHERE '" + country_data.name + "' LIKE CONCAT(country_name,'%'); ";
					}
					console.log(queries)

				});

				$(e).unbind("keyup");
				$(e).bind("keyup", function (event) {
					$("." + name + "_full").val($(e).intlTelInput("getNumber").replace(" ", ""));
				});

				// this.addEventListener("open:countrydropdown", function() {
				// 	// triggered when the user opens the dropdown
				// });
				//
				// this.addEventListener("close:countrydropdown", function() {
				// 	// triggered when the user closes the dropdown
				// });

			})

		},

		Prevent: function (e) {
			e.preventDefault();
			e.stopPropagation();
		},

		IsScrolledIntoView: function(elem)
		{

			var docViewTop = $(window).scrollTop();
			var docViewBottom = docViewTop + $(window).height();

			var elemTop = $(elem).offset().top;
			var elemBottom = elemTop + $(elem).height();

			return ((elemBottom < docViewBottom) && (elemTop > docViewTop));

		}

	}

}();


// Short name for above variable
var CF = CommonFunctions;

// Use this one to show messages accross whole application.
function display_message_box(box_class, message, custom_class) {

	CF.BuildNormalModalDialog(message);

}

function update_messages_box_position() {

	$('.update_messages_container').css('width', $('.PageAjaxContent').outerWidth());
	if ($('.update_messages_container .sticky_message').length && $(window).scrollTop() > 20)
		$('.update_messages_container').removeClass('sticky').addClass('sticky');
	else
		$('.update_messages_container').removeClass('sticky');

}

function hide_message_box(custom_class) {

	if (typeof custom_class != 'undefined') {
		MESSAGE_BOX = $('div.' + custom_class);
		MESSAGE_BOX.removeClass(custom_class);
	}

	if (MESSAGE_BOX.is(":visible")) {
		MESSAGE_BOX.slideUp('slow', function () {
			MESSAGE_BOX.removeClass('alert-success').removeClass('alert-warning').removeClass('alert-danger').removeClass('alert-info');
			MESSAGE_BOX.find('.message_icon').removeClass('fa-check-circle').removeClass('fa-info-circle').removeClass('fa-exclamation-circle').removeClass('fa-times-circle');
			DataTablesOperations.UpdatePositions();
		});

	}

}

function display_success(message, custom_class) {

	let identifier = undefined;
	let notify = undefined;

	if (typeof message !== "undefined") {
		identifier = message.identifier;
		notify = message.notify;
	}
	let use_active_element = message.use_active_element;
	if (typeof use_active_element === 'undefined')
		use_active_element = true;

	if (typeof message === 'object') {
		custom_class = message.custom_class;
		notify_params = message.notify_params;
		message = message.message;
	}

	if (typeof identifier === 'undefined')
		identifier = custom_class;

	if (typeof identifier === 'undefined' && use_active_element) {
		identifier = $(document.activeElement).is(':visible') && !$(document.activeElement).is(':disabled') ? document.activeElement : undefined;
		if (identifier.tagName === 'BODY')
			identifier = undefined;
	}

	if (!$(identifier).is(':visible') || $(identifier).is(':disabled'))
		identifier = undefined;

	if (typeof notify === 'undefined')
		notify = true;

	if (notify && identifier !== 'undefined') {
		var notification_params = {
			identifier: identifier,
			message: message,
			className: 'success'
		}
		if (typeof notify_params !== 'undefined')
			$.extend(notification_params, notify_params);

		CF.Notify(notification_params);

	} else
		display_message_box('alert-success', message, custom_class);

}

function display_info(message, custom_class) {

	let identifier = undefined;
	let notify = undefined;

	if (typeof message !== "undefined") {
		identifier = message.identifier
		notify = message.notify
	}

	let use_active_element = message.use_active_element;
	if (typeof use_active_element === 'undefined')
		use_active_element = true;

	if (typeof message === 'object') {
		custom_class = message.custom_class;
		var notify_params = message.notify_params;
		message = message.message;
	}

	if (typeof identifier === 'undefined')
		identifier = custom_class;

	if (typeof identifier === 'undefined' && use_active_element) {
		identifier = $(document.activeElement).is(':visible') && !$(document.activeElement).is(':disabled') ? document.activeElement : undefined;
		if (identifier.tagName === 'BODY')
			identifier = undefined;
	}

	if (typeof notify === 'undefined')
		notify = true;

	if (notify && identifier !== 'undefined') {

		var notification_params = {
			identifier: identifier,
			message: message,
			className: 'info'
		}
		if (typeof notify_params !== 'undefined')
			$.extend(notification_params, notify_params);

		CF.Notify(notification_params);
	} else
		display_message_box('alert-info', message, custom_class);
}

function display_warning(message, custom_class) {

	let identifier = undefined;
	let notify = undefined;

	if (typeof message !== "undefined") {
		identifier = message.identifier
		notify = message.notify
	}

	let use_active_element = message.use_active_element;
	if (typeof use_active_element === 'undefined')
		use_active_element = true;

	if (typeof message === 'object') {
		custom_class = message.custom_class;
		var notify_params = message.notify_params;
		message = message.message;
	}

	if (typeof identifier === 'undefined')
		identifier = custom_class;

	if (typeof identifier === 'undefined' && use_active_element) {
		identifier = $(document.activeElement).is(':visible') && !$(document.activeElement).is(':disabled') ? document.activeElement : undefined;
		if (identifier.tagName === 'BODY')
			identifier = undefined;
	}

	if (typeof notify === 'undefined')
		notify = true;

	if (notify && identifier !== 'undefined') {
		var notification_params = {
			identifier: identifier,
			message: message,
			className: 'warn'
		}
		if (typeof notify_params !== 'undefined')
			$.extend(notification_params, notify_params);

		CF.Notify(notification_params);

	} else
		display_message_box('alert-warning', message, custom_class);
}

function display_danger(message, custom_class) {

	let identifier = undefined;
	let notify = undefined;

	if (typeof message !== "undefined") {
		identifier = message.identifier
		notify = message.notify
	}

	let use_active_element = message.use_active_element;
	if (typeof use_active_element === 'undefined')
		use_active_element = true;

	if (typeof message === 'object') {
		custom_class = message.custom_class;
		notify_params = message.notify_params;
		message = message.message;
	}

	if (typeof identifier === 'undefined')
		identifier = custom_class;

	if (typeof identifier === 'undefined' && use_active_element) {
		identifier = $(document.activeElement).is(':visible') && !$(document.activeElement).is(':disabled') ? document.activeElement : undefined;
		if (identifier.tagName === 'BODY')
			identifier = undefined;
	}

	if (typeof notify === 'undefined')
		notify = true;

	if (notify && identifier !== 'undefined') {
		var notification_params = {
			identifier: identifier,
			message: message,
			className: 'error'
		}
		if (typeof notify_params !== 'undefined')
			$.extend(notification_params, notify_params);

		CF.Notify(notification_params);
	} else
		display_message_box('alert-danger', message, custom_class)
}

function display_success_inline(message, container) {
	if(typeof container === "undefined"){
		display_success(message)
		return false;
	}
	$(container).html("<div class='alert bg-success'>"+message+"</div>");
	$(container).removeClass("hidden");
}

function display_info_inline(message, container) {
	if(typeof container === "undefined"){
		display_info(message)
		return false;
	}
	$(container).html("<div class='alert bg-info'>"+message+"</div>");
	$(container).removeClass("hidden");
}

function display_warning_inline(message, container) {
	if(typeof container === "undefined"){
		display_warning(message)
		return false;
	}
	$(container).html("<div class='alert bg-warning'>"+message+"</div>");
	$(container).removeClass("hidden");
}

function display_danger_inline(message, container) {
	if(typeof container === "undefined"){
		display_danger(message)
		return false;
	}
	$(container).html("<div class='alert bg-danger'>"+message+"</div>");
	$(container).removeClass("hidden");
}

if(typeof check_and_load_data === "undefined"){

	function check_and_load_data(first_load, input_params){

		if(typeof first_load === "undefined")
			first_load = false;

		var params = {
			// url: '',
			first_load: first_load,
			items_row: '.list_block',
			items_container: '.list_items',
			filter_input: '#searchInput',
			totals_container: '.list_items_total',
		};

		if(typeof input_params !== "undefined"){

			for(i in input_params)
				if(typeof params[i] !== "undefined")
					params[i] = input_params[i];

		}

		if($(params.items_container).length === 0)
			return false;

		if(typeof $(params.items_container).attr('data-url') !== "undefined" && typeof params.url === "undefined")
			params.url = $(params.items_container).attr('data-url');

		if(typeof params.url === "undefined")
			return false;

		if(params.first_load)
			$(params.items_container).html('');

		var last_item = $(params.items_row).eq(-1);
		var total_records = last_item.attr('data-total_records');
		if(typeof total_records === "undefined" || params.first_load)
			total_records = 5;

		var all_displayed = last_item.attr('data-all_displayed');
		if(typeof all_displayed === "undefined" || params.first_load)
			all_displayed = 0;

		if(params.first_load || (CF.IsScrolledIntoView(last_item) && !$(params.items_container).hasClass('scrolling') && all_displayed == 0))
		{
			$(params.items_container).after(loader_small());
			$(params.items_container).addClass('scrolling');
			var start = last_item.attr('data-total_displayed');
			if(typeof start === "undefined" || params.first_load)
				start = 0;
			// else
			// 	start = parseInt(start)+1;

			let ajax_data = {
				url: params.url,
				start: start,
				filter: $(params.filter_input).val(),
				callback: function(res){
					$(params.items_container).append(res.records_html);
					$(params.items_container).removeClass('scrolling');

					$('.content_loader, .loader_small').remove();

					last_item = $(params.items_row).eq(-1);
					last_item.attr("data-total_displayed", res.total_displayed);
					last_item.attr("data-total_records", res.total_records);

					var total_records = res.total_records;

					$('.total_displayed', params.totals_container).html(res.total_displayed);
					// $('.total_records', params.totals_container).html(res.total_records);
					$(params.totals_container).removeClass('hidden');

					$('.filtered_from', params.totals_container).removeClass('hidden').addClass('hidden');
					if(typeof res.filtered_records !== "undefined"){
						total_records = res.filtered_records;
						// $('.total_records', params.totals_container).html(res.filtered_records);
						$('.filtered_from .filtered_total', params.totals_container).html(res.total_records);
						$('.filtered_from', params.totals_container).removeClass('hidden');
					}
					$('.total_records', params.totals_container).html(total_records);

					last_item.attr('data-all_displayed', res.total_displayed == total_records ? 1:0);

				}
			}

			if(typeof load_data_call !== "undefined")
				load_data_call.abort();

			load_data_call = CF.PostAjaxData(ajax_data);

		}

	}

}

/*
* Pages related js code
*/

/*function disabe_button_form(disable) {
	if(disable != '')
		$(e).attr("disabled", true);
	else
		$(e).attr("disabled", false);
}*/

function show_hide_columns() {
	console.log('show_hide_columns()');
	$(".show_hide_check:not(:checked)").each(function () {
		$('table .' + $(this).attr("name")).hide();
	});
	if ($('.section-heading').length > 0) {
		$('.section-heading').each(function () {
			let total_heads = $(this).closest('thead').find('tr:eq(1) th:visible').length;
			$('th', this).attr('colspan', total_heads === 0 ? 100 : total_heads);
		})
	}
}

function get_blank_page(params) {

	if (typeof params.page_number === "undefined")
		params.page_number = 1
	else
		params.page_number++

	let page = $(".print_page_template.default_template")
			.clone()
			.removeClass("default_template")
			.addClass("page page" + params.page_number)
			.css("display", "block")
			.css("overflow", "visible")
		// .css('height', params.page_height+'px')
	;
	$('body').append(page);
	params.total_height = 0;
	return $('.page' + params.page_number)[0];
}

let hide_page_breaks_view = true;

function break_in_to_pages(params) {

	console.log('Break in to pages');
	if($('.template-view.overlay_container').length > 0){
		CF.PrintPages = $('.template-view > .paged_data .page.print_page_template').length;
		CF.PrintPdfHeight = CF.GetHeight($('.template-view.overlay_container')[0]);
		CF.PrintPdfWidth = CF.GetWidth($('.template-view.overlay_container')[0]);
	}

	if(typeof params !== "undefined" && typeof params.page_number !== "undefined" && params.page_number >= 50){
		CF.BuildNormalModalDialog("Too many pages ("+params.page_number+"). something is wrong...");
		return false;
	}

	if(typeof params !== "undefined" && typeof params.page_number !== "undefined" && params.page_number >= 1){
		//display_danger(params.page_number+" pages processed. something is not right.");
		if (hide_page_breaks_view) {
			// $('.template-view > .paged_data').remove();
			// $('.template-view > .print_page_template').remove();
		}

	}

	if (typeof params === 'undefined')
		params = {};

	if ($('.paged_data_copy').length === 0 && hide_page_breaks_view) {
		let copy_container = document.createElement('div');
		$(copy_container).addClass('copy_container paged_data_copy')
		$(copy_container).html($('.print_data').clone()[0]);
		$('.template-view').prepend(copy_container);
		$('.paged_data').css('visibility', 'hidden');
		$('.template-view > .print_data.quotation_print').css('visibility', 'hidden');
	}

	if ($('.template-view > .print_data.quotation_print').length === 1) {
		$('body > .template-view-design').remove();

		if (typeof params.remove_row !== 'undefined') {
			$(params.remove_row).remove();
			params.remove_row = undefined;
		}

		let new_page = get_blank_page(params);
		console.log('New Page ' + params.page_number);

		if (typeof params.page_height === 'undefined') {
			$(".print_page_template.default_template").css("display", "block").css("visibility", "hidden");
			// params.page_height = parseInt($('.template-view-design').css('height'));
			params.page_height = CF.GetHeight($(".print_content", new_page)[0]);//$(".print_content", new_page).outerHeight();
			// params.header_height = 0;
			// params.footer_height = 0;
			// params.page_height = (params.page_height-(params.header_height+params.footer_height));
			if (!hide_page_breaks_view)
				$(".print_page_template.default_template").css("display", "none").css("visibility", "visible");
		}

		//if($('.template-view > .print_data.quotation_print').outerHeight() > params.page_height)
		if (CF.GetHeight($('.template-view > .print_data.quotation_print')[0]) > params.page_height ||
			$('.template-view > .print_data.quotation_print > table > tbody > tr.break').length > 0
		)
			// if(CF.GetHeight($('.print_data.quotation_print')[0]) > 0)
		{

			$('.template-view > .print_data.quotation_print > *').each(function () {

				let tag = this.tagName;
				let next_tag = CF.GetPdfNextTag(this);
				let height = CF.GetHeight(this);

				if (tag === 'BR')
					height = 22;

				if (tag === 'TABLE') {

					let table = this;
					let break_page = false
					$('>thead>tr:not(.columns_resizer), >tbody>tr:not(.columns_resizer), >tr:not(.columns_resizer)', this).each(function () {

						if (!break_page) {

							let row_index = $(this).index();

							let is_last_row = $(this).closest('tr').next().length === 0;

							height = CF.GetHeight(this);
							if (is_last_row) {
								let table_props = CF.GetElementProperties(table);
								height += table_props.marginBottom
								height += table_props.paddingBottom
							}

							let next_row_index = row_index + 1;

							params.total_height += height;

							if (params.total_height + CF.GetNextHeight(this) > params.page_height || $(this).hasClass('break')) {
								//if (row_index > 0)
								{
									let cloned_table = $(table).clone()[0];

									// This is the last row
									if (is_last_row) {
										$(table).remove();
									} else {
										$('>tbody>tr:lt(' + next_row_index + ')', table).remove();
										$(table).addClass('cloned');
										$('>tbody>tr:gt(' + row_index + ')', cloned_table).remove();
									}

									$('.print_content', new_page).append(cloned_table);

								}

								break_page = true

							}

						}

					});

					if (break_page) {
						console.log('Height: ' + params.total_height + " <=> Page Height:" + params.page_height);
						console.log('Done with page ' + params.page_number);

						$('.paged_data').append(new_page);
						params.total_height = 0;
						console.log("break_in_to_pages recursive 1");
						break_in_to_pages(params)
						return false;
					}

					if (params.total_height > 0)
						$('.print_content', new_page).append(table);

				} else {
					next_tag = CF.GetPdfNextTag(this);
					$('.print_content', new_page).append(this);
					params.total_height += height;
				}

				//let next_height = CF.GetHeight($(this).next()[0]);//$(this).next().outerHeight();
				if (params.total_height + CF.GetHeight(next_tag) > params.page_height) {
					console.log('Height: ' + params.total_height + " <=> Page Height:" + params.page_height);
					console.log('Done with page ' + params.page_number);
					$('.paged_data').append(new_page);
					console.log("break_in_to_pages recursive 2 ");
					break_in_to_pages(params)
					return false;
				}

			});

		} else {

			$('.template-view > .print_data.quotation_print > *').each(function () {
				$('.print_content', new_page).append(this);
			});

			$('.paged_data').append(new_page);

			$('.template-view > .print_data.quotation_print').remove();
			$('body > .template-view-design').remove();

			$('.print_page_template:not(.default_template)').each(function () {
				let page_format = $('.page_number', this).html();
				page_format = page_format.replace('[PageNumber]', $(this).index() + 1);
				page_format = page_format.replace('[TotalPages]', $('.print_page_template:not(.default_template)').length);
				$('.page_number', this).html(page_format);
			});

			console.log('Done with Last Page ' + params.page_number);

			console.log("break_in_to_pages recursive 3");
			break_in_to_pages(params)
			return false;
		}

	} else {

		console.log('No print data left.');
		CF.PrintPages = $('.template-view > .paged_data .page.print_page_template').length;
		CF.PrintPdfHeight = CF.GetHeight($('.template-view > .paged_data')[0]) * 1.5;
		CF.PrintPdfWidth = CF.GetWidth($('.template-view > .paged_data')[0]);
		if (hide_page_breaks_view) {
			$('.template-view > .paged_data').remove();
			$('.template-view > .print_page_template').remove();
		}
		update_form_state();
	}

	return false;

}

// for sale_quotation and change order edit screens
// Delete Section
function delete_section(obj) {

	CF.ConfirmBox(
		'Delete section.',
		'Are you sure you want to delete this section ?',
		function () {

			let sec_id = $(obj).closest('.section-container').attr('data-section');
			$('[data-for_section="'+sec_id+'"]').remove();

			$(obj).closest('.section-container').remove();
			if (typeof quotation_calculation !== "undefined")
				quotation_calculation();

			if (typeof percentage_calculation !== "undefined")
				percentage_calculation();

			if (typeof order_calculation !== "undefined")
				order_calculation()

			if (typeof update_list_actions !== "undefined")
				update_list_actions()

			if (typeof CUSTOM_FUNCTIONS.delete_section_callback !== "undefined")
				CUSTOM_FUNCTIONS.delete_section_callback(obj);

			CF.DestroyModalDialog();

		}
	)

	return false;

}

// Delete Edit Items
function delete_items(obj) {

	if(typeof obj !== "undefined"){
		CF.BuildModalDialog({
			title: 'Delete item.',
			message: 'Are you sure you want to delete this item ?',
			proceed_callback: function () {
				$(obj).closest("tr").remove();
				CF.DestroyModalDialog(undefined, 1);
				$('.quantity').trigger('keyup');
				percentage_calculation();
			},
		})
	}else{
		if ($('.case:checkbox:checked').length == 0) {
			display_danger('No item(s) selected.');
		} else {
			let items_to_remove = $('.case:checkbox:checked');
			CF.BuildModalDialog({
				title: 'Delete item.',
				message: 'Are you sure you want to delete (' + items_to_remove.length + ') item(s) ?',
				proceed_callback: function () {
					items_to_remove.parents("tr").remove();
					CF.DestroyModalDialog(undefined, 1);
					$('.quantity').trigger('keyup');
					percentage_calculation();
				},
			})
		}
	}

	return false;


}

// Delete Terms
function delete_terms(obj) {

	let heading = $(obj).closest('.card').find('.table thead tr th:eq(1)').html();
	let selected_boxes = $('input:checkbox:checked', $(obj).closest('.card')[0]);

	if (selected_boxes.length === 0) {
		display_danger('No ' + heading + ' selected.');
	} else {
		CF.BuildModalDialog({
			title: 'Delete ' + heading + '.',
			message: 'Are you sure you want to delete this selected ' + heading + ' ?',
			proceed_callback: function () {
				selected_boxes.parents("tr").remove();
			},
		})
	}
	return false;
}

// for sale_quotation and change order edit screens

// activity functions
function activity_detail_changed(e, record_id) {

	$('#activity-form .activity_options').removeClass('hidden').addClass('hidden');
	$('#activity-form .activity_options .required').removeClass('required');

	let activity_options = $("#activity-form .activity_options_" + $(e).val())[0];
	$(activity_options).removeClass('hidden');
	$(activity_options).find('[data-required="required"]').addClass('required');

}

function activity_option_changed(e) {
	console.log('activity_option_changed');
	$('.activity_option_action .required').removeClass('required');
	$('.activity_option_action').removeClass('hidden').addClass('hidden');
	$('.activity_option_action_' + $(e).val()).removeClass('hidden');
	$('.activity_option_action_' + $(e).val()).find('[data-required="required"]').addClass('required');
	CF.UpdateDatePickers();
}

function aks_file_uploaded_callback(preview_container, settings) {

	console.log("aks_file_uploaded_callback")
	if (typeof preview_container === "undefined" && $('.aks-file-upload-content').length === 1)
		preview_container = $('.aks-file-upload-content')[0];

	if (typeof preview_container === "undefined")
		return false;

	if ($(preview_container).closest(".image_upload_box").attr('data-multiple') !== "1")
		$('.aks-file-upload-label', preview_container).hide();

}

function aks_file_delete_callback(preview_container, settings) {
	console.log("aks_file_delete_callback")
	if (typeof preview_container === "undefined" && $('.aks-file-upload-content').length === 1)
		preview_container = $('.aks-file-upload-content')[0];

	if (typeof preview_container === "undefined")
		return false;

	$('.aks-file-upload-label', preview_container).show();

}

// written at the end of createImg function in admin/plugins/jquery-image-uploader-preview-and-delete/src/image-uploader.js
function image_uploader_file_select_handler_callback(container) {


	if (typeof CUSTOM_FUNCTIONS.image_uploader_file_select_handler_callback !== "undefined")
		CUSTOM_FUNCTIONS.image_uploader_file_select_handler_callback(container);

}

function image_uploader_delete_callback(delete_button) {

	if (typeof CUSTOM_FUNCTIONS.image_uploader_delete_callback !== "undefined")
		CUSTOM_FUNCTIONS.image_uploader_delete_callback(delete_button);

}

function image_uploader_build_callback(container, preload_call) {

	if (typeof CUSTOM_FUNCTIONS.image_uploader_build_callback !== "undefined")
		CUSTOM_FUNCTIONS.image_uploader_build_callback(container, preload_call);

}

function image_uploader_displayed_callback(container) {

	if (typeof CUSTOM_FUNCTIONS.image_uploader_displayed_callback !== "undefined")
		CUSTOM_FUNCTIONS.image_uploader_displayed_callback(container);

}

CF.ClearLocalStorage();

function move_rows(e, row, next_row, prev_row){

	if(typeof next_row === "undefined")
		next_row = $(row).next();

	if(typeof prev_row === "undefined")
		prev_row = $(row).prev();

	if ($(e).is(".move_up") && typeof prev_row !== "undefined") {
		$(row).insertBefore(prev_row);
	} else if ($(e).is(".move_down") && typeof next_row !== "undefined") {
		$(row).insertAfter(next_row);
	}

	$(row).toggleClass("row_moved").fadeOut(400, function () {
		$(this).toggleClass("row_moved").fadeIn(400);
	})

}

$(document).ready(function () {

	$.validator.prototype.checkForm = function () {
		// overriden in a specific page.
		this.prepareForm();
		for (var i = 0, elements = (this.currentElements = this.elements()); elements[i]; i++) {
			if (this.findByName(elements[i].name).length != undefined && this.findByName(elements[i].name).length > 1) {
				for (var cnt = 0; cnt < this.findByName(elements[i].name).length; cnt++) {
					this.check(this.findByName(elements[i].name)[cnt]);
				}
			} else {
				this.check(elements[i]);
			}
		}
		return this.valid();
	}

	$(window).on('scroll', function () {
		console.log("window scroll up down");
		if($('.load_data_on_scroll').length > 0){
			check_and_load_data();
		}

	});

	CF.UpdateCurrencyFields();
	CF.StickyButtons();

	$('body').on('click', '.form_submit_button', function (evt) {
		CF.Prevent(evt);
		let form = $(this).closest('form')[0];
		$(form).removeClass('validated');
		$(this).removeClass('clicked').addClass('clicked');
		$('.form_submit_button', form).attr('disabled', true);

		$(form).validate();
		if (!$(form).valid()) {
			CF.MoveToFirstErrorMessage();
			CF.ResetFormButtonClicked(form);
			display_danger('Please fix form error(s).', $('.form-control.error')[0]);
		}else{
			$(form).addClass('validated');
			$(form).submit();
		}

		return false;

	});

	$('body').on('click', '.delete_revision', function () {

		$('i', this).removeClass('fa-remove').addClass('fa-spinner fa-spin')

		let ajax_data = {
			url: $(this).attr('data-delete_url'),
			delete_records: $(this).closest('tr').attr('id')
		}
		ajax_data.callback = function () {
			$('#tab-revisions').click();
		}

		CF.PostAjaxData(ajax_data);

	});

	$('body').on('click', '.toggle_status', function () {

		var container = this;
		$('i', this).removeClass('fa-times').removeClass('fa-check');
		$('span.text', this).html(CF.GetLoader('tiny'));

		let ajax_data = {
			url: site_url + "/backend/update_status",
			rec: $(this).attr('data-rec'),
			status: $(this).attr('data-status'),
			pid: $(this).attr('data-pid'),
			key: $(this).attr('data-key'),
			callback: function (res) {

				if (res.success) {
					display_success(res.message);
					$('i', container).addClass(res.new_class);
					$('span.text', container).html(res.new_text);
					$(container).attr('data-status', res.new_status);
				}
				$('i.loader', container).remove();
			}
		}

		CF.PostAjaxData(ajax_data);

		return false;

	});

	$(document).on('click', '.jquery_custom_model_cloned', function () {
		CF.BringToTop(this, '.jquery_custom_model_cloned')
	});

	$(document).on('click', '[data-dismiss="modal"]', function () {
		let modal_id = $(this).closest('.jquery_custom_model_cloned').attr('id');
		CF.DestroyModalDialog(modal_id);
	});

	$(document).on('mouseover', '[data-title-notify]', function () {
		display_info($(this).attr('data-title-notify'), this);
	});

	$(document).on('blur change keyup', '.build-tooltip', function () {
		CF.UpdateTooltips(this)
	});

	$(document).on("blur change", ".currency_field", function () {
		console.log("Currency field blur/change handler");
		CF.UpdateCurrencyFields(this);
	});

	// stop form submit if min is greater.
	$(document).on("blur change keyup", 'input.sale_price_min', function (e) {
		let sale_price = $(this).closest('.prices_container').find('input.sale_price').val();
		let sale_price_min = $(this).val();
		if (CF.NoCurrency(sale_price_min) > CF.NoCurrency(sale_price)) {
			display_danger('Max available is ' + sale_price, this);
			$(this).val(CF.Currency(sale_price));
			e.stopPropagation();
			e.preventDefaults();
		}

	});

	$(document).on("focus", ".currency_field", function () {

		if ($(this).not('[readonly]')) {

			console.log("Currency, input.datatables-editor field click handler");

			if ($(this).hasClass('text_input') || $(this).parent().hasClass('text_cell'))
				return true;

			if ($(this).hasClass('allow_empty') && $(this).val() == '')
				return true;

			var n_value = CF.GetNormalValue($(this).val(), CF.Decimal_Places, true);
			if (($(this).hasClass("number_field") || $(this).parent().hasClass('number_cell')) && !$(this).parent().hasClass('hours_cell'))
				n_value = CF.GetNormalValue($(this).val(), 0, true);

			$(this).val(n_value).select();
		}

	});

	$('body').on('click', '.open_close_legend', function () {

		console.log('open_close_legend');

		let legend = this;
		let container = $(this).closest('fieldset');
		if ($(this).closest('.fieldset').length > 0)
			container = $(this).closest('.fieldset');

		let closed = $('i.fa', legend).hasClass('fa-minus-circle');
		container.children('.open_close_row').slideToggle(function () {
			$('i.fa', legend).removeClass('fa-minus-circle').removeClass('fa-plus-circle');
			if (closed)
				$('i.fa', legend).addClass('fa-plus-circle')
			else
				$('i.fa', legend).addClass('fa-minus-circle')
		});

	});

	// $('body').on('click', '.checkbox-content .checkbox', function(){
	// 	console.log('sjashkdasd');
	// 	let cb = $('input[type="checkbox"]', this)[0];
	// 	$(cb).click();
	// });

	$('body').on('click', '.show_hide_check:checkbox', function () {
		console.log('.show_hide_check:checkbox');
		let table_reference = $(this).closest
		if ($(this).is(':checked'))
			$('table .' + $(this).attr("name")).show();
		else
			$('table .' + $(this).attr("name")).hide();

		if ($('.section-heading').length > 0) {
			$('.section-heading').each(function () {
				$('th', this).attr('colspan', $(this).closest('thead').find('tr:eq(1) th:visible').length);
			})
		}

	});

	$('body').on('click', '.ColVis_collection li', function () {
		$('.show_hide_check:checkbox', this).click()
	});

	//Redirect Message
	let url_params = CF.GetUrlVars();
	let show_message = url_params.show_message;
	if (typeof show_message !== "undefined") {

		let message_type = url_params.message_type;
		let message = url_params.message;
		let message_position = url_params.message_position;
		let message_icon = url_params.message_icon;
		let message_color = url_params.message_color;

		if (show_message === 'permission_direct') {
			message_type = 'Error';
			message = 'Direct access is not allowed.';
			message_position = 'top-right';
			message_icon = 'error';
			message_color = '#9EC600';
		} else if (show_message === 'permission_denied') {
			message_type = 'Error';
			message = 'You are not allowed to access that page.';
			message_position = 'top-right';
			message_icon = 'info';
			message_color = '#9EC600';
		} else if (show_message === 'page_not_available') {
			message_type = 'Error';
			message = 'That page is not available.';
			message_position = 'top-right';
			message_icon = 'info';
			message_color = '#9EC600';
		} else if (show_message === 'operation_error') {
			message_type = 'Error';
			message = 'That operation is not available.';
			message_position = 'top-right';
			message_icon = 'info';
			message_color = '#9EC600';
		} else if (show_message === 'record_not_found') {
			message_type = 'Error';
			message = 'Record not found.';
			message_position = 'top-right';
			message_icon = 'error';
			message_color = '#9EC600';
		}
		// CF.Notify({
		// 	"autoHideDelay": 5000,
		// 	"position": "top right",
		// 	"message": message,
		// 	"className": message_icon,
		// })
		CF.ToastMessage(message_type, message, message_position, message_icon, message_color);

	}

	// Modify required label to add field label in start
	jQuery.extend(jQuery.validator.messages, {

		required: function (result, e) {

			if (typeof $(e).attr('data-required_error') !== "undefined")
				return $(e).attr('data-required_error');

			let field_label = "This field ";

			if ($(e).closest('.form-group').find('label').length > 0)
				field_label = $(e).closest('.form-group').find('label').html();

			if ($(e).closest('tr').length) {
				let table = $(e).closest('table')[0];
				let index = $(e).closest('td').index();
				field_label = $('thead tr th', table).eq(index).html();
			}

			return field_label + " is required.";

		},

	});

	jQuery.validator.methods.required = function (value, element, param) {

		//console.log("required", element);
		// Check if dependency is met.
		if (!this.depend(param, element))
			return "dependency-mismatch";

		if ($(element).hasClass('currency_field') && CF.NoCurrency(value) <= 0)
			return false;

		switch (element.nodeName.toLowerCase()) {
			case 'select':
				// could be an array for select-multiple or a string, both are fine this way
				var val = $(element).val();
				if (val == null || typeof val === "undefined")
					return false;
				return val !== '' && val.length > 0;
			case 'input':
				if (this.checkable(element))
					return this.getLength(value, element) > 0;
			default:
				return $.trim(value).length > 0;
		}
	}
	// Process forms with class Form via ajax
	// Ajax Form Submit function
	$('body').on('submit', '.Form', function (event) {

		console.log('.Form submit handler');

		CF.StartFormLoader(this);

		var form = this;
		if(!$(this).hasClass('validated'))
		{
			$(form).validate();
			if (!$(form).valid()) {
				console.log("form errors 1");
				display_danger('Please fix form errors.');
				CF.MoveToFirstErrorMessage();
				CF.ResetFormButtonClicked(form);
				return false;
			}
		}else
			$(this).removeClass('validated');

		var button_clicked = CF.GetFormButtonClicked(form);

		// Remove all errors from fields
		$('.has-error .help-block').remove();
		$('.has-error').removeClass('has-error');

		if ($(this).hasClass('has_custom_validations') && typeof CUSTOM_FUNCTIONS.custom_form_validations !== 'undefined' && !CUSTOM_FUNCTIONS.custom_form_validations(this)) {
			console.log("form errors has_custom_validations");
			display_danger('Please fix form errors.');
			CF.MoveToFirstErrorMessage();
			CF.ResetFormButtonClicked(form);
			return false;
		}

		// execute any additional function(s) required to run before form submit
		if (typeof CUSTOM_FUNCTIONS.custom_form_functions != "undefined")
			CUSTOM_FUNCTIONS.custom_form_functions(form);

		// some errors occured somewhere in validations stop form process here
		if ($('.form_errors li').length > 0) {
			CF.ResetFormButtonClicked(form);
			return false;
		}

		// form should be posted without ajax, returning true for any further form processing
		if ($(this).hasClass('NoAjax')) {
			CF.ResetFormButtonClicked(form);
			return true;
		}

		var reload_data_table = 1;
		if (typeof $(form).attr('data-reload_data_table') != 'undefined')
			reload_data_table = parseInt($(form).data('reload_data_table'));

		var submitButton = $(this).find('button[type="submit"]');
		var buttonText = submitButton.text();
		submitButton.attr('disabled', true);

		// form_data = new FormData();
		var form_data = new FormData(this);
		var form_data_obj = formToObject(form);
		if (typeof $(this).attr('data-ignore_form_data') !== 'undefined' && $(this).attr('data-ignore_form_data') == '1') {
			form_data = new FormData();
		}

		form_data.set('ajaxLoad', 1);

		var post_link = $(this).attr('action');
		if (button_clicked && typeof $(button_clicked).attr('data-form_url') !== 'undefined')
			post_link = $(button_clicked).attr('data-form_url');

		// Append table data to form post method if present
		var data_table_id = false;
		if (typeof $(this).data('data_table_id') != 'undefined') {
			data_table_id = $(this).data('data_table_id');
			if ($('table#' + data_table_id + '.dataTable').length > 0) {
				let table = $('table#' + data_table_id).DataTable();
				if (!$('#' + data_table_id).hasClass('skip_post_table_data')) {
					var TableData = table.rows().data();
					for (var index in TableData) {
						if ($.isNumeric(index))
							form_data.append('table_data', JSON.stringify(TableData[index]));
					}
				}

				var ao_data = DataTablesOperations.GetPostData();
				for (k in ao_data)
					form_data.append(k, ao_data[k])
			}
		}

		if (!post_link)
			post_link = $(location).attr('href').replace(/^.*#/, '');

		CF.AjaxStart('Form_Submit');

		// sending all form data in one key.
		var obj = Object.fromEntries(
			Array.from(form_data.keys()).map(key => [
				key, form_data.getAll(key).length > 1 ?
					form_data.getAll(key) : form_data.get(key)
			])
		);
		obj = $.extend(obj, form_data_obj);
		form_data.append('data', JSON.stringify(obj));

		//form_data.set('data', JSON.stringify(form_data.serializePHPObject()));

		var ajax_options = {
			data: form_data,
			type: 'POST',
			url: post_link,
			cache: false,
			processData: false,
			contentType: false
		};

		var is_json_form = $(this).attr('data-json_form');
		if (typeof is_json_form !== typeof undefined)
			is_json_form = is_json_form == 'true';

		var json_form_url = false;
		if (is_json_form) {
			ajax_options['dataType'] = 'json';
			json_form_url = $(this).data('c_form_url');

			if (typeof json_form_url === typeof undefined)
				json_form_url = post_link;
		}

		$.ajax(ajax_options).done(function (data, textStatus, request) {

			submitButton.attr('disabled', false);

			if (data.hasOwnProperty('pdf_file')) {
				CF.ResetFormButtonClicked(form);
				$('.form_submit_button', form).removeAttrs('disabled');
				var win = window.open(data.pdf_file, '_blank');
				win.focus();
				CF.AjaxStop('Form_Submit 1');
				return false
			}

			if (request.getResponseHeader('content-type') == 'application/pdf') {

				CF.PostDataTo(post_link, {method: 'GET'}, true);
				CF.ResetFormButtonClicked(form);
				CF.AjaxStop('Form_Submit 2');
				return false;
			}

			if (is_json_form || request.getResponseHeader('content-type') === 'application/json') {

				if (data.success && typeof $(button_clicked).attr('data-goto') !== "undefined") {
					window.location.href = $(button_clicked).attr('data-goto') + "?app_success=" + data.message;
					return false;
				}

				if (data.hasOwnProperty('RedirectTo') && data.RedirectTo !== false) {
					CF.SetLocalStorage('ajax-data', CF.ObjectToQueryString(data));
					if (data.ExternalLink)
						CF.PostDataTo(response.RedirectTo, {method: 'GET'}, true);
					else
						window.location.href = data.RedirectTo;

					CF.ResetFormButtonClicked(form);
					CF.AjaxStop('Form_Submit 3');
					return false;
				}

				if (data.msg) {
					data.success = true;
					if (data.msg === 'ERROR')
						data.success = false;

					data.message = data.data
				}

				if (data.success) {
					if ($(form).attr('data-popup_form') === "1")
						$('#jquery_custom_model_cloned').modal('hide');

					$(form).removeClass('copy_form');
					data.custom_class = submitButton
					display_success(data);
					var load_box_index = -1;
					if ($('.load_data_in_first_box').length > 0)
						load_box_index = 0;
				} else {
					data.custom_class = submitButton
					if (data.message)
						display_danger(data);

					CF.HandleFormFieldErrors(data);
				}

				if (data.reload_page)
					window.location.reload();

			} else {

				if (data.indexOf('RedirectTo:') !== -1) {
					var redirect_url = data.split('RedirectTo:');
					if (redirect_url[0].indexOf('ExternalLink-') !== -1)
						CF.PostDataTo(redirect_url[1], {method: 'GET'}, true);
					else {
						window.location = redirect_url[1];
					}

					CF.ResetFormButtonClicked(form);
					CF.AjaxStop('Form_Submit 4');
					return false;

				}

				CF.AjaxStop('Form_Submit 5');
				if ($(form).hasClass("AjaxForm")) {
					$(form).removeClass('copy_form');
					if ($(form).hasClass('popup_form'))
						submitButton = undefined;
					display_success('Data Updated Successfully', submitButton);
				}

			}

			CF.AjaxStop('form_submit 6');

			if (typeof CUSTOM_FUNCTIONS.form_submit_callback != "undefined")
				CUSTOM_FUNCTIONS.form_submit_callback(data);

			if (typeof data.reload_table !== 'undefined' && data.reload_table)
				reload_data_table = 1;

			if (reload_data_table)
				load_data_table.reload();

			/*if (data.RedirectTo)
				window.location.href = data.RedirectTo;*/

			if (typeof data.close_modal === "undefined" || parseInt(data.close_modal) === 1)
				$('.modal').modal('hide');

			CF.ResetFormButtonClicked(form);

		});

		return false;

	});

	$('body').on('keydown', '[id^=percentage_amount], .form-control.quantity, .number_only_input', function (event) {
		return CF.NumericOnly(event, this);
	});

	let ajax_data = CF.GetLocalStorage('ajax-data');
	if (ajax_data !== null) {
		ajax_data = CF.QueryStringToObject(ajax_data);
		if (typeof ajax_data.success !== "undefined") {
			if (ajax_data.success)
				display_success(decodeURIComponent(ajax_data.message));
			else
				display_danger(decodeURIComponent(ajax_data.message));

			CF.RemoveLocalStorage('ajax-data')
		}
	}

	$('body').on('click', '.move_row_arrows', function (e) {

		console.log('move_row_arrows');

		if(typeof $(this).attr('data-row') !== "undefined"){

			CF.Prevent(e);
			let row = $(this).closest($(this).attr('data-row'));
			move_rows(this, row);

			if(typeof $(this).attr('data-other_row') !== "undefined"){
				let o_row = $($(this).attr('data-other_row'));
				move_rows(this, o_row);
			}

			return false;

		}
		else{
			let row = $(this).closest('tr')[0];
			let prev_row = $(row).prev().is('tr') ? $(row).prev() : $(row).prev().prev();
			let next_row = $(row).next().is('tr') ? $(row).next() : $(row).next().next();

			if ($(this).is(".move_up")) {
				$(row).insertBefore(prev_row);
			} else if ($(this).is(".move_down")) {
				$(row).insertAfter(next_row);
			}

			$(row).toggleClass("row_moved").fadeOut(400, function () {
				$(this).toggleClass("row_moved").fadeIn(400)
			})
		}


	});

	CF.UpdateTooltips();

	$('body').on('click', 'i.page-breaker', function () {

		if ($(this).hasClass('fa-circle-o')) {
			$(this).removeClass('fa-circle-o').addClass('fa-check-circle-o');
		} else {
			$(this).removeClass('fa-check-circle-o').addClass('fa-circle-o');
		}

	});

	setTimeout(function () {
		let location_hash = location.hash;
		if ($(location_hash).length > 0)
			$(location_hash).click();
	}, 500);

	$("body").bind('bodyClassChanged', function () {
		CF.SetLocalStorage('menu_enlarged', $('body').hasClass('enlarged'));
	});

	// display page content when page is loaded
	$('.content-page > .content').show();
	$('.page_loader').remove();

	$('.select2').each(function () {

		$(this).select2({
			// templateResult: formatOption,
			// templateSelection: formatOption,
			// escapeMarkup: function(m) { return m; }
		});

	})
	/*
		$('body').on('keydown', 'div.dropdown-menu.open li', function(){

			if (e.keyCode == 38) { // Up
				var previousEle = $(this).prev();
				if (previousEle.length == 0) {
					previousEle = $(this).nextAll().last();
				}
				var selVal = $('.selectpicker option').filter(function () {
					return $(this).text() == previousEle.text();
				}).val();
				$('.selectpicker').selectpicker('val', selVal);

				return;
			}
			if (e.keyCode == 40) { // Down
				var nextEle = $(this).next();
				if (nextEle.length == 0) {
					nextEle = $(this).prevAll().last();
				}
				var selVal = $('.selectpicker option').filter(function () {
					return $(this).text() == nextEle.text();
				}).val();
				$('.selectpicker').selectpicker('val', selVal);

				return;
			}
		});
		*/

	$('body').on('click', '.checkbox.checkbox-primary', function(){
		$('input[type="checkbox"]', this).prop('checked', !$('input[type="checkbox"]', this).prop('checked'));
	});

	$('body').on('change', 'input.select_one[type="checkbox"]', function(){
		$('input.select_one.'+$(this).attr('data-related')+'[type="checkbox"]').not(this).prop('checked', false);
		if($('input.select_one.'+$(this).attr('data-related')+'[type="checkbox"]:checked').length == 0)
			$('input.select_one.'+$(this).attr('data-related')+'[type="checkbox"]').not(this).eq(0).prop('checked', true);
	})

});
