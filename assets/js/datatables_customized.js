/*
 *  Document   : tablesDatatables.js
 *  Description: Custom javascript code used in Tables Datatables page
 *  extra filters information can be passed with fnServerParams attribute
 */
$(window).scroll(function() {

});

var DataTablesOperations = function(){

    return {
        init: function(table_id){
            table_id = DataTablesOperations.GetId(table_id);
            // DataTablesOperations.UpdatePositions(table_id);
        },
        GetUrl: function(table_id){
            instance = DataTablesOperations.GetInstance(2, table_id);
            return instance.ajax.url()
        },
        GetId: function(data_table_id){

            if(typeof data_table_id === 'undefined')
            {
                if($('.page_data_table').length)
                {

                    if($('.dataTables_scrollBody .page_data_table').length)
                        data_table_id = $('.dataTables_scrollBody .page_data_table').attr("id");
                    else
                        data_table_id = $('.page_data_table').attr("id");

                }

            }

            return data_table_id;

        },
        UpdatePositions: function(data_table_id){

            // console.log('DataTablesOperations.UpdatePositions');

            if($('.dataTables_scrollBody').length && !$('.dataTables_scrollBody').hasScrollBar('h'))
            {
                $('.table_cloned').remove();
                $('.dataTables_scrollBody').scrollLeft(0);
            }else
            if($('table.fixed_columns_custom').length)
            {
                var data_table_id = DataTablesOperations.GetId(data_table_id);

                var left_fixed_columns = 0;
                if($('#'+data_table_id).attr('data-fixed_left'))
                    left_fixed_columns = $('#'+data_table_id).data('fixed_left');

                var right_fixed_columns = 0;
                if($('#'+data_table_id).attr('data-fixed_right'))
                    right_fixed_columns = $('#'+data_table_id).data('fixed_right');

                // Scroll head inner handler
                {
                    $('.dataTables_scrollHeadInner.table_cloned').remove();

                    // Left side handler
                    if(left_fixed_columns > 0)
                    {
                        var table_head = $('#'+data_table_id).closest('.dataTables_scroll').find('.dataTables_scrollHead');
                        var table_head_inner = table_head.find('.dataTables_scrollHeadInner');
                        var cloned_table_head_left_cloned = table_head_inner.clone()[0];
                        if($(cloned_table_head_left_cloned).find('thead tr').length > 1)
                            $(cloned_table_head_left_cloned).find('thead tr:eq(0) th:gt(0)').remove();

                        $(cloned_table_head_left_cloned).find('.dataTable.fixed_columns_custom tr:eq(-1) th:gt('+(left_fixed_columns-1)+')').remove();
                        $(cloned_table_head_left_cloned).find('.dataTable.fixed_columns_custom tr:eq(-1) th:eq(0)').css('height', table_head_inner.find('table tr:eq(-1) th:eq(0)').css('height'));
                        $(cloned_table_head_left_cloned).find('.dataTable.fixed_columns_custom').css('width', 0);
                        $(cloned_table_head_left_cloned).find('.dataTable.fixed_columns_custom').removeAttr('id');
                        $(cloned_table_head_left_cloned).addClass('table_cloned');
                        $('#'+data_table_id).closest('.dataTables_scroll').find('.dataTables_scrollHead').append(cloned_table_head_left_cloned);

                    }

                    // Right side handler
                    if(right_fixed_columns > 0)
                    {
                        var cloned_table_head_right_cloned = table_head_inner.clone()[0];
                        if($(cloned_table_head_right_cloned).find('thead tr').length > 1)
                            $(cloned_table_head_right_cloned).find('thead tr:eq(0)').css('visibility', 'hidden');

                        $(cloned_table_head_right_cloned).find('.dataTable.fixed_columns_custom tr:eq(-1) th:nth-last-child('+right_fixed_columns+')').prevAll('th').remove();
                        $(cloned_table_head_right_cloned).find('.dataTable.fixed_columns_custom tr:eq(-1) th:eq(0)').css('height', table_head_inner.find('table tr:eq(-1) th:eq(0)').css('height'));
                        $(cloned_table_head_right_cloned).find('.dataTable.fixed_columns_custom').css('width', 0);
                        $(cloned_table_head_right_cloned).find('.dataTable.fixed_columns_custom').removeAttr('id');
                        $(cloned_table_head_right_cloned).addClass('table_cloned');
                        $('#'+data_table_id).closest('.dataTables_scroll').find('.dataTables_scrollHead').append(cloned_table_head_right_cloned);
                    }

                }

                // Scroll body handler
                if($('.dataTables_scrollBody table:eq(0) tbody tr td').length > 1)
                {
                    $('.dataTables_scrollBody .table_cloned').remove();

                    var table_body_wrapper = document.createElement('div');
                    $(table_body_wrapper)
                        .addClass('table_cloned')
                        .append($('#'+data_table_id).clone());

                    $(table_body_wrapper).find('table thead').remove();
                    $(table_body_wrapper).find('table tfoot').remove();
                    $(table_body_wrapper).find('.dataTable.fixed_columns_custom').css('width', 0);
                    $(table_body_wrapper).find('.dataTable.fixed_columns_custom').removeAttr('id');

                    var table_body_wrapper_right = $(table_body_wrapper).clone()[0];

                    // Left side handler
                    $(table_body_wrapper).find('table tr').each(function(){
                        $(this).find('td:gt('+(left_fixed_columns-1)+')').remove();
                    });

                    var last_row = $(table_body_wrapper).find('table tr:eq(-1)');
                    var last_column = $('td:eq(0)', last_row[0]);
                    var last_colspan = last_column.attr('colspan');

                    if(typeof last_colspan != 'undefined' && last_colspan > 1)
                        last_column.next('td').remove();

                    if(left_fixed_columns > 0)
                        $('.dataTables_scrollBody').append(table_body_wrapper);

                    // Right side handler
                    $(table_body_wrapper_right).find('table tr').each(function(){
                        if(right_fixed_columns > 0)
                            $(this).find('td:nth-last-child('+right_fixed_columns+')').prevAll('td').remove();
                    });

                    if(right_fixed_columns > 0)
                        $('.dataTables_scrollBody').append(table_body_wrapper_right);

                }

                var table_head = $("#"+data_table_id).closest('.dataTables_scroll').find('.dataTables_scrollHead').eq(0);
                var head_pos = table_head[0].getBoundingClientRect();

                $('.dataTables_scrollHeadInner.table_cloned:eq(0)').css({'position':'fixed', 'left':head_pos.left, 'top':head_pos.top});

                var total_width = 0;
                $('.dataTables_scrollHeadInner.table_cloned:eq(1) table tr:eq(-1) th').each(function(){
                    total_width += $(this).outerWidth();
                });

                $('.dataTables_scrollHeadInner.table_cloned:eq(1)').css({'position':'fixed', 'left':(head_pos.right-total_width)+4, 'top':head_pos.top});

                var table_body = $("#"+data_table_id).closest('.dataTables_scroll').find('.dataTables_scrollBody').eq(0);
				var body_pos = table_body[0].getBoundingClientRect();

                $('.dataTables_scrollBody .table_cloned:eq(0)').css({'position':'fixed', 'left':body_pos.left, 'top':body_pos.top});
                $('.dataTables_scrollBody .table_cloned:eq(1)').css({'position':'fixed', 'left':(body_pos.right-total_width)+4, 'top':body_pos.top});

                // adjusting body columns width
                $('.dataTables_scrollHeadInner.table_cloned:eq(0) tr:eq(-1) th').each(function(){
                    var index = $(this).index();
                    var width = $(this).outerWidth();
                    $('.dataTables_scrollBody .table_cloned:eq(0) tr td:eq('+index+')').css('width', width);
                });
                $('.dataTables_scrollHeadInner.table_cloned:eq(1) tr:eq(-1) th').each(function(){
                    var index = $(this).index();
                    var width = $(this).outerWidth();
                    $('.dataTables_scrollBody .table_cloned:eq(1) tr td:eq('+index+')').css('width', width);
                });

            }

        },
        GetPostData: function(aoData, data_table_id){

            var data_table_id = DataTablesOperations.GetId(data_table_id);

            if(typeof aoData == 'undefined')
                var aoData = {};

            $(".datatable_filters, .datatable_extra_data").each(function(index, form){

                $(this).serializeArray().map(function(x){
                    var name = x.name;
                    if(name.indexOf('[]') != -1) {

                        x.name = name.replace("[]", "");

                        if(typeof aoData[x.name] == 'undefined')
                            aoData[x.name] = [];

                        aoData[x.name].push(x.value);

                    }else
                    if($(form).hasClass('post_fields_as_array'))
                    {

                        if(typeof aoData[x.name] == 'undefined')
                            aoData[x.name] = [];

                        aoData[x.name].push(x.value);

                    }else
                        aoData[x.name] = x.value;

                });

            });

            $('.data_table_field, .data_table_cell_field').each(function(index, x){
                var name = x.name;

                if(name.indexOf('[]') != -1) {

                    x.name = name.replace("[]", "");

                    if(typeof aoData[x.name] == 'undefined')
                        aoData[x.name] = [];

                    aoData[x.name].push(x.value);

                }else
                    aoData[x.name] = x.value;

            });

            aoData['current_page'] = "";

            if(typeof $('#'+data_table_id).dataTable().fnSettings().aaSorting[0] != 'undefined')
            {
                var sorting_info = $('#'+data_table_id).dataTable().fnSettings().aaSorting[0];
                aoData['sort_column'] = sorting_info[0];
                aoData['sort_direction'] = sorting_info[1];
            }

            if(!$('#'+data_table_id).hasClass('skip_post_table_ajax_data'))
            {
                var table = DataTablesOperations.GetInstance(2, data_table_id);
                if(typeof table != 'undefined') {
                    var table_ajax_data = table.ajax.json();
                    if(typeof table_ajax_data != 'undefined') {
                        delete table_ajax_data['data'];
                        aoData['table_ajax_data'] = JSON.stringify(table_ajax_data);
                    }
                }
            }

            if(!$('#'+data_table_id).hasClass('skip_post_table_data_list')){
                // build list of objects for each form-control within table rows

                var data_table_data_list = [];
                var table_selected_rows = DataTableRowSelection.GetSelected(data_table_id);
                for(var i in table_selected_rows){

                    var row = $('#'+data_table_id+' tr#'+table_selected_rows[i]);
                    var tr = row[0];
                    var data_obj = {};
                    $('td', tr).each(function() {
                        var td = this;
                        $('.form-control', td).each(function(){
                            data_obj[$(this).attr('name')] = $(this).val();
                        });
                        $('select.select-chosen', td).each(function(){
                            data_obj[$(this).attr('name')] = $(this).val();
                        });
                    });
                    data_table_data_list.push(data_obj)

                }

                aoData['data_table_data_list'] = JSON.stringify(data_table_data_list);
            }

            return aoData;

        },
        GetInstance: function(instance_type, data_table_id){
            // instance_type: 1=Jquery, 2=API
            if(typeof instance_type == 'undefined')
                instance_type = 1;

            data_table_id = DataTablesOperations.GetId(data_table_id);

            var table_instance = '';
            if(typeof DTO.CallBacks.table_instances != 'undefined' && typeof DTO.CallBacks.table_instances[data_table_id] != 'undefined')
                table_instance = DTO.CallBacks.table_instances[data_table_id];
            else
                table_instance = $('table#'+data_table_id);

            if(instance_type == 2)
                table_instance = table_instance.DataTable();

            return table_instance;

        },
        GetLoadedJson: function(data_table_id, get_data_rows){

            if(typeof get_data_rows == 'undefined')
                get_data_rows = false;

            var table = DataTablesOperations.GetInstance(2, data_table_id);
            var loaded_json = table.ajax.json();

            if(!get_data_rows)
                delete loaded_json['data'];

            return loaded_json;
        },
        UpdateColumnsClasses: function(oSettings){

            var data_table = '';
            if(typeof oSettings === 'undefined')
                data_table = DataTablesOperations.GetInstance();
            else
                data_table = $('table#'+oSettings.sTableId);

            var header_columns_container = data_table.find('thead tr:eq(0)');

            // if(data_table.find('thead tr.header_columns').length > 0)
            //     header_columns_container = data_table.find('thead tr.header_columns');
            // else
            // if(data_table.find('thead tr').length === 2)
            //     header_columns_container = data_table.find('thead tr:eq(1)');

            var total_columns = header_columns_container.find('th').length;
            for(c=0; c < total_columns; c++)
            {
                var header_column = header_columns_container.find('th:eq('+c+')');
                var header_class = header_column.attr('class');
                var header_data_attrs = [].filter.call(header_column[0].attributes, at => /^data-/.test(at.name));

                data_table.find('tbody tr').each(function(){
                    $('td:eq('+c+')', this).removeClass(header_class).addClass(header_class);

                    for(i in header_data_attrs)
                    {
                        if(header_data_attrs.hasOwnProperty(i))
                        {
                            $('td:eq('+c+')', this).attr(header_data_attrs[i].name, header_data_attrs[i].value);
                        }
                    }


                });

            }

        },
		TableInstances: {},
		CallBacks: {}
    };

}();
var DTO = DataTablesOperations;

var DataTableRowSelection = function(){
    return {
    	SelectedRows: {},
        HighLightSelected: function(data_table_id){

            console.log('DataTableRowSelection HighLightSelected');
            data_table_id = DataTablesOperations.GetId(data_table_id);

            for(var i in DataTableRowSelection.SelectedRows[data_table_id]){
                var row = $('#'+data_table_id+' tr#'+DataTableRowSelection.SelectedRows[data_table_id][i]);
				row.removeClass('selected').addClass('selected');
            }

			$('th.select-checkbox').closest('tr').removeClass('selected');
			if($('#'+data_table_id+' tbody tr.selected').length == $('#'+data_table_id+' tbody tr').length)
				$('th.select-checkbox').closest('tr').addClass('selected');

			var not_selected_class = $('#'+data_table_id).attr('data-not_selected_class');
			var selected_class = $('#'+data_table_id).attr('data-selected_class');

			if(typeof not_selected_class === 'undefined')
				not_selected_class = 'fa-square-o'

			if(typeof selected_class === 'undefined')
				selected_class = 'fa-check-square-o'

			$('#'+data_table_id+' tr .select-checkbox i').removeClass(selected_class).addClass(not_selected_class);
			$('#'+data_table_id+' tr.selected .select-checkbox i').removeClass(not_selected_class).addClass(selected_class);

			$('.show_with_selection').removeClass('hidden').addClass('hidden');
			if($('#'+data_table_id+' tbody tr.selected').length > 0)
				$('.show_with_selection').removeClass('hidden');


        },
        UpdateSelected: function(data_table_id){

            console.log('DataTableRowSelection UpdateSelected');
            data_table_id = DataTablesOperations.GetId(data_table_id);

            if(typeof data_table_id == 'undefined')
                return false;

            if($('#'+data_table_id).hasClass('ignore_row_selection'))
                DataTableRowSelection.ResetSelected(data_table_id);

			var table_selected_rows = [];
			if(DataTableRowSelection.SelectedRows.hasOwnProperty(data_table_id))
				table_selected_rows = DataTableRowSelection.SelectedRows[data_table_id];

            var selectedRowIds = $('#'+data_table_id+' tbody tr').map(function() {

                var row_id = this.id;
                if($('#'+row_id).hasClass('table_total_row'))
                    row_id = undefined;

                if(typeof row_id !== 'undefined' && row_id && row_id !== 'row_')
                {

					if($(this).hasClass('selected')){
						if(table_selected_rows.indexOf(row_id) === -1)
							table_selected_rows.push(row_id);
					}
					else{
						if(table_selected_rows.indexOf(row_id) !== -1)
							table_selected_rows.splice(table_selected_rows.indexOf(row_id), 1);
					}

                }

            });

            DataTableRowSelection.SelectedRows[data_table_id] = table_selected_rows;

            this.HighLightSelected(data_table_id);

        },
        ResetSelected: function (data_table_id){

            console.log('DataTableRowSelection ResetSelected');

            data_table_id = DataTablesOperations.GetId(data_table_id);
            if(typeof data_table_id == 'undefined')
                return false;

			$('#'+data_table_id+' tr').removeClass('selected');
			DTRS.SelectedRows[data_table_id] = [];
			DTRS.HighLightSelected(data_table_id);

        },
        SelectAll: function (data_table_id){

            console.log('DataTableRowSelection SelectAll');

            data_table_id = DataTablesOperations.GetId(data_table_id);
            if(typeof data_table_id == 'undefined')
                return false;

			$('#'+data_table_id+' tbody tr').removeClass('selected').addClass('selected');
            DataTableRowSelection.UpdateSelected(data_table_id);

        },
        GetSelected: function (data_table_id, return_integers){

            console.log('DataTableRowSelection GetSelected');

            data_table_id = DataTablesOperations.GetId(data_table_id);
            if(typeof data_table_id == 'undefined')
                return false;

            if(typeof return_integers != 'undefined' && return_integers)
            {
                var selected_integers = [];

                for(i in DataTableRowSelection.SelectedRows[data_table_id])
                {
                    var row_id = DataTableRowSelection.SelectedRows[data_table_id][i];
                    var integer_id = row_id.replace('row_', '');
                    selected_integers.push(integer_id)
                }
                return selected_integers;
            }else
                return DataTableRowSelection.SelectedRows[data_table_id];

        },
        GetIds: function (data_table_id){

            console.log('DataTableRowSelection GetIds');

            data_table_id = DataTablesOperations.GetId(data_table_id);

            var table_ids = $('#'+data_table_id+' tbody tr').map(function() {

                var row_id = this.id;
                if(typeof row_id != 'undefined' && row_id && row_id != 'row_')
                    return row_id.replace('row_', '');

            });
            return table_ids;

        },
        IsSelected: function (row_id){
            console.log('DataTableRowSelection IsSelected');
            var selected_ids = DataTableRowSelection.GetSelected();
            if(selected_ids.indexOf(row_id) != -1)
                return true
            return false;

        }
    };
}();
var DTRS = DataTableRowSelection;

$(function(){

    // $('body').on( 'click', '.dataTable tbody tr', function ()
    $('body').on( 'click', '.dataTable tbody tr td.select-checkbox', function ()
	{

        console.log('.dataTable tbody tr td.select-checkbox');
		var dataTableId = DataTablesOperations.GetId($(this).closest('.dataTables_wrapper').attr('id').replace('_wrapper', ''));

        if($(this).hasClass('table_total_row'))
            return false;

		$(this).closest('tr').toggleClass('selected');
		DTRS.UpdateSelected(dataTableId);

    });

    $('body').on( 'click', '.show_hide_table_columns', function () {

        console.log('.show_hide_table_columns click');
		var dataTableId = DataTablesOperations.GetId($('.dataTables_wrapper').attr('id').replace('_wrapper', ''));

		var columns_html = '';
		$('#'+dataTableId+' tr.header_columns th.hide_able').each(function(){
			var column_data = $(this).attr('data-data');
			var column_label = $(this).html();
			var checked_class = $(this).hasClass('hidden') ? "fa-square-o":"fa-check-square-o";
			columns_html += '<a class="dropdown-item" href="#" data-data="'+column_data+'"><i class="check_icon fa '+checked_class+'"></i> '+column_label+'</a>';
		});

		$('.dropdown-menu', this).html(columns_html);

    });

    $('body').on( 'click', '.show_hide_table_columns .dropdown-item', function (e) {

        console.log('.show_hide_table_columns click');
		var dataTableId = DataTablesOperations.GetId($('.dataTables_wrapper').attr('id').replace('_wrapper', ''));

		var checked_class = 'fa-check-square-o';
		var unchecked_class = 'fa-square-o';
		var header_ref = $('#'+dataTableId+' tr th[data-data="'+$(this).attr('data-data')+'"]');
		var body_ref = $('#'+dataTableId+' tr td[data-data="'+$(this).attr('data-data')+'"]');

		if($('.check_icon', this).hasClass(checked_class))
		{
			$('.check_icon', this).removeClass(checked_class).addClass(unchecked_class);
			header_ref.removeClass('hidden').addClass('hidden');
			body_ref.removeClass('hidden').addClass('hidden');
		}else
		if($('.check_icon', this).hasClass(unchecked_class))
		{
			$('.check_icon', this).removeClass(unchecked_class).addClass(checked_class);
			header_ref.removeClass('hidden');
			body_ref.removeClass('hidden');
		}else{
			// not possible to be here
			CF.BuildNormalModalDialog('Something strange happened. please try again');
		}

		return false;

    });

    $('body').on( 'keyup change', '.data_table_cell_field', function () {
		var dataTableId = DataTablesOperations.GetId($(this).closest('.dataTables_wrapper').attr('id').replace('_wrapper', ''));
		load_data_table.reload(dataTableId);
    });

    $('body').on('click', '.dataTable th.select-checkbox', function()
    {
        var dataTableId = DataTablesOperations.GetId($(this).closest('.dataTables_wrapper').attr('id').replace('_wrapper', ''));
		var tr = $(this).closest('tr')[0];
		$(tr).toggleClass('selected')

        if($(tr).hasClass('selected')){
			DTRS.SelectAll(dataTableId);
		}else{
			DTRS.ResetSelected(dataTableId);
		}

    });

    $('body').on('click', '.table_row_selection .row_selection', function()
    {
        console.log('.row_selection');
        var dataTableId = DataTablesOperations.GetId($(this).closest('.dataTables_wrapper').attr('id').replace('_wrapper', ''));
        var current_row = $(this).closest('tr')[0];
        var child_row = $(current_row).next('[data-parent_row_id="'+current_row.id+'"]');
        child_row.removeClass('selected');
        var breakup_child_rows = child_row.find('tbody tr');
        breakup_child_rows.removeClass('selected');
        breakup_child_rows.find('input.row_selection').prop('checked', false);
        $(this).closest('tr').removeClass('selected');

        if(this.checked) { // Check select status
            $(this).closest('tr').addClass('selected');
            child_row.addClass('selected');
            breakup_child_rows.addClass('selected');
            breakup_child_rows.find('input.row_selection').prop('checked', true);

        }
        $('.dd_child_row').each(function () {
            $(this).removeClass('selected');
            var parent_row_id = $(this).attr('data-parent_row_id');
            $("#"+parent_row_id).removeClass('selected');
            $("#"+parent_row_id).find('.row_selection').prop('checked', false);

            if ($('.child_table tbody tr', this).length == $('.child_table tbody tr.selected', this).length){
                $(this).addClass('selected');
                $("#"+parent_row_id).addClass('selected');
                $("#"+parent_row_id).find('.row_selection').prop('checked', true);
            }
            if($('.child_table tbody tr.selected', this).length > 0){
                $('tr#'+parent_row_id).addClass('selected');
                $('tr#'+parent_row_id).find('.row_selection').prop('checked', true);
            }

        });
		DTRS.UpdateSelected(dataTableId);

    });

    $('body').on('click', '.select_deselect_all_checkboxes', function()
    {
        console.log('.select_deselect_all_checkboxes');

        var table_id = DataTablesOperations.GetId($(this).closest('.dataTables_wrapper').attr('id').replace('_wrapper', ''));
		DTRS.ResetSelected(table_id);
        if(this.checked) { // Check select status
            DTRS.SelectAll(table_id);
        }

    });

    $('body').on( 'click', '.DeleteDataTableRows', function ()
    {
    	console.log('DeleteDataTableRows click handler');

        var delete_button = $(this);
        var postLink = delete_button.data('href');
        var dataTableId = DataTablesOperations.GetId($(this).closest('.data_table_operations').attr('data-table_id'));

        if (dataTableId in delete_button.data())
            dataTableId = delete_button.data('dataTableId');

        var selectedRowIds = DTRS.GetSelected(dataTableId, true);

        var select_text = 'select';
        if(DTRS.SelectionType === 'checkbox')
            select_text = 'check';

        if(!selectedRowIds || !selectedRowIds.length)
        {
            display_info('Please '+select_text+' rows to delete');
            return false;
        }

        select_text = select_text == 'select' ? 'selected':'checked';
        var dialog_params = {
            title: 'Delete Rows',
            message: 'Are you sure you want to delete ('+selectedRowIds.length+') '+select_text+' rows ? This cannot be undone.',
            proceed_callback: 'DeleteDataTableRows',
            proceed_arg_json: {'delete_button': delete_button, 'dataTableId': dataTableId, 'selectedRowIds':selectedRowIds}
        }

        CF.BuildModalDialog(dialog_params);

        return false;
    });

    $('body').on( 'click', '.ExportTableData', function ()
    {

    	console.log('DeleteDataTableRows click handler');

        var action_button = this;
        var postLink = $(action_button).data('href');
        var dataTableId = DataTablesOperations.GetId($(action_button).closest('.data_table_operations').attr('data-table_id'));

		if (typeof $(action_button).attr('data-dataTableId') !== 'undefined')
            dataTableId = action_button.data('dataTableId');

        var selectedRowIds = DTRS.GetSelected(dataTableId, true);

        var post_link = $(this).attr('data-href')
		var post_data = {
        	'table_id': dataTableId,
        	'selected_records': selectedRowIds
		};
        $.extend(post_data, DTO.GetPostData())
		CF.PostDataTo(post_link, post_data)
        return false;

    });

    $('body').on( 'click', '.table_field_filters', function ()
    {
		var  table_id = $(this).closest('.data_table_operations').attr('data-table_id');

		if($('#'+table_id+' .table_filters_row').length == 0){
			var header_row_filter = $('#'+table_id+' thead tr.header_columns').clone()[0];
			$(header_row_filter).addClass('table_filters_row');
			$('th', header_row_filter).each(function(){

				$(this).addClass('no-sort');
				$(this).removeClass('select-checkbox');

				if($(this).hasClass('no-search')){
					$(this).html('');
				}else{
					var new_input = document.createElement('input');
					$(new_input)
						.attr('name', $(this).data('data')+"_filter")
						.attr('type', 'text')
						.attr('placeholder', 'search...')
						.addClass('data_table_cell_field form-control');
					$(this).html(new_input);
				}

			});
			$('#'+table_id+' thead tr:eq(0)').after(header_row_filter);
		}

		$('#'+table_id+' .table_filters_row').toggle();

        return false;

    });

    // Handle click on columns to the right wrapper
    $('body').on( 'click', '.table_cloned tr th, .table_cloned tr td', function ()
    {
        var column_index = $(this).index();
        var row_index = $(this).closest('tr').index();

        if($(this).closest('.dataTables_scrollHead').length)
        {
            $(this).closest('.dataTables_scrollHead').find('.dataTables_scrollHeadInner table.fixed_columns_custom thead tr:eq('+row_index+') th:eq('+column_index+')').click()
        }else{
            $(this).closest('.dataTables_scrollBody').find('table.fixed_columns_custom tbody tr:eq('+row_index+') td:eq('+column_index+')').click()
        }

        return false;

    });

    $('body').on('mouseenter', '.dataTables_scrollBody table:eq(0) tbody tr', function(){
        var row_index = $(this).index();
        $('.dataTables_scrollBody .table_cloned:eq(0) tbody tr:eq('+row_index+')').addClass('hover');
        $('.dataTables_scrollBody .table_cloned:eq(1) tbody tr:eq('+row_index+')').addClass('hover');
    });

    $('body').on('mouseleave', '.dataTables_scrollBody table:eq(0) tbody tr', function(){
        var row_index = $(this).index();
        $('.dataTables_scrollBody .table_cloned:eq(0) tbody tr:eq('+row_index+')').removeClass('hover');
        $('.dataTables_scrollBody .table_cloned:eq(1) tbody tr:eq('+row_index+')').removeClass('hover');
    });

    $('body').on('mouseenter', '.dataTables_scrollBody .table_cloned table tbody tr', function(){
        var row_index = $(this).index();

        $('.dataTables_scrollBody table').each(function(){
            $(this).find('tbody tr:eq('+row_index+')').addClass('hover');
        });

    });

    $('body').on('mouseleave', '.dataTables_scrollBody .table_cloned table tbody tr', function(){
        var row_index = $(this).index();
        $('.dataTables_scrollBody table').each(function(){
            $(this).find('tbody tr:eq('+row_index+')').removeClass('hover');
        });

    });

	// Post data on click of a button with class operation_button
	$('html').on('click', '.operation_button', function(){

		let operation = $(this).attr('data-operation');
		let operation_url = $(this).attr('data-href');

		if($(this).hasClass('has_form'))
		{
			let data_form = $(this).closest('form')[0];

			let all_fields_ok = true;

			$('.required', data_form).each(function(){
				if($(this).val() === '' || !$(this).val()){
					all_fields_ok = false;
					CF.ShowFieldError($(this).attr('name'), 'This field is required');
				}

			});

			if(!all_fields_ok)
				return false;

			if(typeof operation_url === 'undefined')
				operation_url = $(data_form).attr('action');

		}

		if($(this).hasClass('check_table_selection')){

			var selected = DTRS.GetSelected();
			if(selected.length === 0){
				display_danger('Please select some rows.');
				return false;
			}

		}

		var ajax_data = {
			// 'callback': employee_operation_response,
			'url': operation_url,
			'operation': operation
		};

		if($(this).hasClass('has_form')){
			ajax_data.form_data = JSON.stringify($(this).closest('form').serializeArray());
		}

		if($('.page_data_table').length){
			ajax_data['table_ids'] = DTRS.GetSelected(undefined, true)
		}

		CF.PostAjaxData(ajax_data)

		return false;

	});

});

var DeleteDataTableRows = function(params){

    var delete_button = params.delete_button;
    var postLink = params.delete_link || delete_button.data('href');
    var dataTableId = params.dataTableId || false;
    var selectedRowIds = params.selectedRowIds;

    // Sending an ajax call to delete selected ids
    var post_data = new FormData();
    post_data.set('operation', 'delete_records');
    post_data.set('delete_records', selectedRowIds);
    // post_data.set('csrfmiddlewaretoken', $('input[name="csrfmiddlewaretoken"]').val());
    if(typeof delete_button.data('special_operation') != 'undefined' && delete_button.data('special_operation') == 1)
        post_data.set('special_operation', 1);

    if($(delete_button).hasClass('send_table_data'))
    {
        var data_table_id = dataTableId;
        var table = $('table#' + data_table_id).DataTable();
        if(!$('#'+data_table_id).hasClass('skip_post_table_data'))
        {
            var TableData = table.rows().data();
            for (var index in TableData) {
                if ($.isNumeric(index))
					post_data.append('table_data', JSON.stringify(TableData[index]))
            }
        }

        var ao_data = DataTablesOperations.GetPostData();
        for (var k in ao_data)
            post_data.append(k, ao_data[k])
    }

	CF.AjaxStart('DeleteDataTableRows');
    var ajax_method = 'POST';
    if(typeof $(delete_button).attr('method') !== 'undefined')
        ajax_method = $(delete_button).attr('method');

    $.ajax(
        {
            data: post_data,
            type: ajax_method,
            url: postLink,
            cache: false,
            processData: false,
            contentType: false
        }
    ).done(function(res)
    {

        try {
            res = JSON.parse(res);
        } catch(e){

        }

        if(res == 'external_exists')
        {
            display_danger('Records with related data cannot be deleted.');
            if(dataTableId)
                $('#'+dataTableId).DataTable().ajax.reload();
        }
        else
        if(res == 'permission_denied')
        {
            display_danger('You do not have permission to delete this information.');
        }
        else
        if(res)
        {
            CF.UpdateLocalStorageData(res);

            if (params.delete_callback) {

                if(!Array.isArray(params.delete_callback))
                    params.delete_callback = [params.delete_callback];

                for(i in params.delete_callback)
                {
                    var callback = window[params.delete_callback[i]];
                    for(key in res){
                        params[key] = res[key];
                    }
                    callback(params);
                }

            }
            else{

                if (typeof res.reload_page !== 'undefined' && res.reload_page)
                    reload_page();
                else
                {
                    if (res.success){

                        if(res.message)
                        {
                            if(res.message !== 'hide')
                                display_info(res.message);
                        }
                        else{
                            display_info("Selected records have been deleted");
                        }

                        if(typeof $(delete_button).attr('data-reload_link') !== 'undefined')
                            load_page($(delete_button).attr('data-reload_link'))

                    }else{
                        display_danger(res.message)
                    }

                    if(dataTableId && !$('table#' + data_table_id).hasClass('table_static'))
                        $('#'+dataTableId).DataTable().ajax.reload();
                }

            }

        }
        else
        {
            display_danger('Something went wrong please try again.')
        }

		CF.AjaxStop('DeleteDataTableRows');

    });

};

function getFiltersData(){
    console.log('get getFiltersData Data');
    return $('form#reports_show_filters').serialize();
    console.log('get getFiltersData Data');
}

function buildAjaxData(){
    console.log('buildAjaxData');
    count = 0;
    var obj_ = [];
    while(true){
        if($('#filter-entity'+count, '#filters').length){
            var obj = {
                "filter-entity": $('#filter-entity'+count).val(),
                "filter-field": $('#filter-field'+count).val(),
                "filter-operator": $('#filter-operator'+count).val(),
                "filter-value": $('#filter-value'+count).val(),
            }
            console.log(count);
            console.log($('#filter-entity'+count).val());
            console.log($('#filter-field'+count).val());
            console.log($('#filter-operator'+count).val());
            console.log($('#filter-value'+count).val());
            console.log(count+'end');
            obj_.push(obj);
        }
        else {
            break;
        }
        count++;
    }
    console.log(obj_);
    return obj_;

    //var settings = $("#reports").dataTable().fnSettings();
    //console.log(settings);
    //
    //var obj = {
    //    //default params
    //    "draw" : settings.iDraw,
    //    "start" : settings._iDisplayStart,
    //    "length" : settings._iDisplayLength,
    //    "columns" : "",
    //    "order": "",
    //
    //    "cmd" : "refresh",
    //    "from": $("#from-date").val()+" "+$("#from-time").val(),
    //    "to"  : $("#to-date").val()+" "+$("#to-time").val()
    //    };
    //
    //    //building the columns
    //    var col = new Array(); // array
    //
    //    for(var index in settings.aoColumns){
    //        var data = settings.aoColumns[index];
    //        col.push(data.sName);
    //
    //    }
    //
    //    var ord = {
    //        "column" : settings.aLastSort[0].col,
    //        "dir" : settings.aLastSort[0].dir
    //    };
    //
    //    //assigning
    //    obj.columns = col;
    //    obj.order = ord;

    //$('')
    //$('')

    console.log('//buildAjaxData//');
    //return obj;
}

//do not show db table name and ids
function show_log_history(log_table, log_id) {
	var o = new Object();
	o.name = log_table;
	o.id = log_id;
	$('#logHistory').modal('show');
	$("#log-history").html(loader_small());
	$("#log-history").load(site_url + "log_history/log_history/", o);
}

var load_data_table = function(){

    return {

        init: function(DataUrl, ordering, searching, paging, table_id, order_by, stateSave, callbacks){

            console.log('Datatable Initialized');

            if(typeof table_id == 'undefined')
			{
				if($('.page_data_table').length)
				{

					if($('.dataTables_scrollBody .page_data_table').length)
						table_id = $('.dataTables_scrollBody .page_data_table').attr("id");
					else
						table_id = $('.page_data_table').attr("id");

				}
				else
					return false;
			}

            if(typeof ordering == 'undefined')
                ordering = true;

            if(typeof searching == 'undefined')
                searching = true;

            if(typeof paging == 'undefined')
                paging = true;

            if(typeof stateSave == 'undefined')
                stateSave = true;

            if(typeof DataUrl === 'undefined')
			{
				DataUrl = $('#'+table_id).data('url');

				if($('#'+table_id).attr('data-url') !== 'undefined')
					$('#'+table_id).attr('data-url', DataUrl);
			}

			var serverSide = false;
			if($('#'+table_id).attr('data-url') !== 'undefined')
				serverSide = true;

            if(typeof order_by === 'undefined')
            {
                order_by = [[ 1, 'asc' ]];
                if($('#'+table_id+' .select-checkbox').length > 0){
                    order_by = [[ 2, 'asc' ]];
                }
            }

            var pageLength = 50;
            if(typeof $('#'+table_id).data('page_length') != 'undefined')
                pageLength = $('#'+table_id).data('page_length');

            var table_options = {
                "serverSide": serverSide,
                "searching": searching,
                "ordering": ordering,
                "paging": paging,
                "pageLength": pageLength,
                "stateSave": stateSave,
                "order": order_by
            };

            if(serverSide)
                table_options["ajax"] = { "url": DataUrl, "type": "post" };

            if (typeof callbacks != 'undefined'){
                if(typeof callbacks['fnRowCallback'] != 'undefined'){
                    table_options['fnRowCallback'] = callbacks['fnRowCallback'];
                }
                if(typeof callbacks['fnDrawCallback'] != 'undefined'){
                    table_options['fnDrawCallback'] = callbacks['fnDrawCallback'];
                }
            }

			var last_column_index = $('th').index($('th.grid_actions'));
			if(last_column_index === -1)
				last_column_index = '0';

			$.extend(true, table_options, {
				// "responsive": true,
				"lengthMenu": [ 10, 50, 100, 500, 1000, 2000, 5000, 10000 ],
				"processing": true,
				"columnDefs": [
					{
						"targets": 'no-sort',
						"orderable": false
					},
					{
						"targets": 'no-search',
						"searchable": false
					}
				],
				"fnDrawCallback": function( oSettings ) {

					console.log("default draw call back");

					DTO.UpdateColumnsClasses(oSettings)

					if(typeof DTO.CallBacks.TableDrawCallBack != 'undefined'){
						DTO.CallBacks.TableDrawCallBack(oSettings, this);
					}

					// place operations in table header

					var toc = $('#'+oSettings.sTableId+'_wrapper').prev('.table_operations_container');
					if(toc.length)
					{

						var enable_refresh = $('#'+oSettings.sTableId).attr('data-enable_refresh');
						if(typeof enable_refresh == 'undefined')
							enable_refresh = '1';

						if(!$('#'+oSettings.sTableId).hasClass('table_static'))
						{
							var refresh_button = document.createElement('a');
							$(refresh_button).addClass("btn btn-small btn-primary btn-option NoAjax")
								.html('<i class="fa fa-refresh"></i> Refresh')
								.attr('onclick', "load_data_table.reload('"+oSettings.sTableId+"');");

							if($('.fa-refresh', toc[0]).length == 0)
								$('div:eq(0)', toc[0]).append(refresh_button);
						}else
						{
							if(enable_refresh == '1'){
								var refresh_button = document.createElement('a');
								$(refresh_button).addClass("btn btn-sm btn-primary btn-option NoAjax")
									.html('<i class="fa fa-refresh"></i>  Refresh');

								var refresh_link = window.location.hash;
								if($('#'+oSettings.sTableId).attr('data-refresh_link') !== 'undefined')
									refresh_link = $('#'+oSettings.sTableId).attr('data-refresh_link');

								$(refresh_button).attr('onclick', "load_page('"+refresh_link+"')");

								if($('.fa-refresh', toc[0]).length == 0)
									$('div:eq(0)', toc[0]).append(refresh_button);
							}
						}

						if($('#'+oSettings.sTableId+'_length').length > 0)
						{
							// $('#'+oSettings.sTableId+'_length').parent().after(toc.html());
						}else{
							// $('#'+oSettings.sTableId+'_processing').before(toc.html());
						}

						// toc.remove();

					}

					DTRS.HighLightSelected();

					/* Add Bootstrap classes to select and input elements added by datatables above the table */
					// $('.dataTables_filter input').addClass('form-control').attr('placeholder', 'Search');
					// $('.dataTables_length select').addClass('form-control select-chosen no-search');
					// DataTablesOperations.UpdatePositions(oSettings.sTableId);

				},
				"createdRow": function ( row, data, index ) {
					$('td.total_td', row).last().addClass('strong');
				},
				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {

					console.log("default footer row call back");

					var record_id = nRow.id;
					record_id = record_id.replace("row_", "");

					if($(this.selector).hasClass('manage_index_auto')){
						$('td:eq(0)', nRow).html(iDisplayIndexFull+1);
					}

					//updateColumnsClasses(nRow, $(this));

					if(typeof DTO.CallBacks.TableRowCallBack != 'undefined')
						return DTO.CallBacks.TableRowCallBack(nRow, aData, iDisplayIndex, iDisplayIndexFull);
					else
						return nRow;

				},
				"fnServerParams": function (aoData) {
					aoData = DTO.GetPostData(aoData)
				}

			});

            var table = $('table#'+table_id).DataTable(table_options);

            if(typeof DTO.TableInstances == 'undefined' )
				DTO.TableInstances = {};

			DTO.TableInstances[table_id] = table;

            if(!serverSide)
            {}

            DataTablesOperations.init(table_id);

        },

        init_obj: function(params_obj){

            if(typeof params_obj === 'undefined')
                params_obj = {};

            load_data_table.init(params_obj.DataUrl, params_obj.ordering, params_obj.searching, params_obj.paging,
                params_obj.table_id, params_obj.order_by, params_obj.stateSave, params_obj.callbacks)

        },

        load: function(DataUrl, table_id)
        {
            if(typeof table_id == 'undefined')

                if($('.page_data_table').length)
                {

                    if($('.dataTables_scrollBody .page_data_table').length)
                        table_id = $('.dataTables_scrollBody .page_data_table').attr("id");
                    else
                        table_id = $('.page_data_table').attr("id");

                }
                else

                    return false;

            var table = $('#'+table_id).DataTable();
            table.ajax.url(DataUrl).load();

        },

        reload: function(table_id)
        {
            table_id = DataTablesOperations.GetId(table_id);
            $('#'+table_id).DataTable().ajax.reload();

        }

    };

}();

function initializeDataTable(dataUrl, additionalData = {}) {
    // Collect column definitions from the table header
    const columns = $('#dt-table thead tr.header_columns th').map(function() {
        return {
            data: $(this).data('data'),
            orderable: false,
            searchable: $(this).data('searchable') !== false
        };
    }).get();

    // Initialize and return the DataTable instance with additional data
    return $('#dt-table').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: dataUrl,
            type: 'POST',
            dataSrc: 'data.data',
            data: function(d) {
                // Merge additionalData into the AJAX data request
                $.extend(d, additionalData);
            }
        },
        pageLength: 50,
        lengthMenu: [10, 50, 100, 500, 1000, 2000, 5000, 10000],
        searching: true,
        columns: columns,
        order: [],
        createdRow: function(row, data, dataIndex) {
            $(row).attr('id', data.DT_RowId);
        },
        rowCallback: function(row, data, index) {
            // Add any row-specific callback logic here if needed
        },
    });
}

//check/uncheck and show/hidden
$(document).ready(function() {
    // Select the header checkbox and add a click event
    $('#dt-table thead th.checkbox-select').on('click', function() {
        const headerIcon = $(this).find('i');
        const isChecked = headerIcon.hasClass('fa-check-square-o');
        
        if (isChecked) {
            // Uncheck all checkboxes and remove the "selected" class
            headerIcon.removeClass('fa-check-square-o').addClass('fa-square-o');
            $('#dt-table tbody tr').each(function() {
                $(this).removeClass('selected');
                $(this).find('td i.fa').removeClass('fa-check-square-o').addClass('fa-square-o');
            });
        } else {
            // Check all checkboxes and add the "selected" class
            headerIcon.removeClass('fa-square-o').addClass('fa-check-square-o');
            $('#dt-table tbody tr').each(function() {
                $(this).addClass('selected');
                $(this).find('td i.fa').removeClass('fa-square-o').addClass('fa-check-square-o');
            });
        }
        toggleButtons();
    });

    // Handle clicks on the <td> and <i> element within the <td>
    $('#dt-table tbody').on('click', 'td:first-child', function(event) {
        const icon = $(this).is('i') ? $(this) : $(this).find('i');
        const row = icon.closest('tr');

        if (icon.length) {
            icon.toggleClass('fa-square-o fa-check-square-o');
            row.toggleClass('selected');
        }

        toggleButtons();
    });

    // Function to toggle visibility of the buttons inside .data_table_operations
    function toggleButtons() {
        const selectedRows = $('#dt-table tbody tr.selected').length;

        if (selectedRows > 0) {
            // If there are selected rows, remove 'hidden' class from .data_table_operations
            $('.data_table_operations .show_with_selection').removeClass('hidden');
        } else {
            // If no rows are selected, add 'hidden' class to .data_table_operations
            $('.data_table_operations .show_with_selection').addClass('hidden');
        }
    }
});

// Delete Rows
$(document).ready(function() {
    // Make sure the modal button is bound correctly
    $('#confirmDeleteBtn').on('click', function() {
        console.log('Delete button clicked');  // Debugging line to check if the button click works

        // Get the selected row IDs
        var selectedIds = [];
        $('#dt-table tbody tr.selected').each(function() {
            var rowId = $(this).attr('id');  // Get the row ID (e.g., <tr id="6">)
            selectedIds.push(rowId);  // Add it to the array
        });

        // If no rows are selected, alert the user
        if (selectedIds.length === 0) {
            alert('No records selected to delete.');
            return;
        }

        // Get the table and column from the hidden fields
        var table = $('input[name="table"]').val();
        var column = $('input[name="column"]').val();

        // Prepare data to send to the backend
        var data = {
            delete_Ids: selectedIds,  // Array of selected IDs
            db_table: table,          // Table name
            primary_id: column        // Column name (primary ID)
        };

        // Send the AJAX request to the backend for deletion
        $.ajax({
            url: $('#deleteSelectedBtn').data('href'),  // Use the URL from the button's data-href attribute
            type: 'POST',
            data: data,  // Send the data to the backend
            success: function(response) {
                if (response.msg === 'SUCCESS') {
                    // Hide the modal
                    $('#deleteModal').modal('hide');
                    
                    // Reload the DataTable
                    var table = $('#dt-table').DataTable(); // Replace 'yourDataTableId' with your table's ID
                    table.ajax.reload(null, false);  // Reload without resetting the pagination
                } else {
                    console.log('Error: unable to delete');  // Show error message
                }
            },
            error: function(xhr, status, error) {
                console.log('Request failed: ' + error);  // Handle any AJAX request errors
            }
        });
    });
});
