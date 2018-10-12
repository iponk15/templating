var global = function () {

    // Set Child Datatable
    var help_datatable_child = function(clas,url,column,clas_select,url_child,column_child,eexcel='') {
        var datatable = $(clas).mDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: url,
                    },
                },
                pageSize: 10, // display 20 records per page
                serverPaging: true,
                serverFiltering: false,
                serverSorting: true,
            },

            // layout definition
            layout: {
                theme: 'default',
                scroll: false,
                height: null,
                footer: false,
            },

            // column sorting
            sortable: true,

            pagination: true,

            detail: {
                title: 'Load sub table',
                content: subTableInit,
            },

            search: {
                input: $(clas_select.generalSearch),
            },

            // columns definition
            columns:column,
        });

        Object.keys(clas_select).forEach(function(key){
            $(clas_select[key]).on('change', function() {
                datatable.search($(this).val().toLowerCase(), key);
            });
        });

        $(eexcel.button).on('click', function() {
            var dat  = $(eexcel.form).serializeArray();
            var form = '<form style="display:none;" action="'+eexcel.url+'" method="post" class="hideform">';
            
            for(i = 0;i < dat.length; i++){
                form = form +'<input type="text" name="'+dat[i].name+'"value="'+dat[i].value+'"></input>';
            }

            var form = form+"<form>";
            $('.forma').append(form);
            $('.hideform').submit()

        });

        function subTableInit(e) {
            var id = e.data.id_content;
            $('<div/>').attr('id', 'child_data_ajax_' + e.data.RecordID).appendTo(e.detailCell).mDatatable({
                data: {
                    type: 'remote',
                    source: {
                        read: {
                            url: url_child+'/'+id,
                            headers: {'x-my-custom-header': 'some value', 'x-test-header': 'the value'},
                            params: {
                                // custom query params
                                query: {
                                    generalSearch: '',
                                    CustomerID: e.data.RecordID,
                                },
                            },
                        },
                    },
                    pageSize: 10,
                    serverPaging: true,
                    serverFiltering: false,
                    serverSorting: true,
                },

                // layout definition
                layout: {
                    theme: 'default',
                    scroll: true,
                    height: 300,
                    footer: false,

                // enable/disable datatable spinner.
                spinner: {
                    type: 1,
                    theme: 'default',
                },
                },

                sortable: true,

                // columns definition
                columns: column_child,
            });
        }
    };

    // Set datatable
    var help_datatable = function (clas,url,column,clas_select,eexcel='') {
        var datatable = $(clas).mDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: url
                    }
                },
                pageSize: 10,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true
            },
            // layout definition
            layout: {
            theme: 'default', // datatable theme
            class: '', // custom wrapper class
            scroll: false, // enable/disable datatable scroll both horizontal and vertical when needed.
            footer: false // display/hide footer
            },

            // column sorting
            sortable: true,

            pagination: true,

            drawCallback: false,

            search: {
                input: $(clas_select.generalSearch)
            },

            // columns definition
            columns: column 
        });

        Object.keys(clas_select).forEach(function(key){
            $(clas_select[key]).on('change', function() {
                datatable.search($(this).val().toLowerCase(), key);
            });
        });

        $(eexcel.button).on('click', function() {
            var dat = $(eexcel.form).serializeArray();
            var form = '<form style="display:none;" action="'+eexcel.url+'" method="post" class="hideform">';
            for(i = 0;i < dat.length; i++){
                form = form +'<input type="text" name="'+dat[i].name+'"value="'+dat[i].value+'"></input>';
            }
            var form = form+"<form>";
            $('.forma').append(form);
            $('.hideform').submit()

        });
    };

    var help_form_validation = function (clas,rules,messages) {
        var warning      = $('.alert-warning', clas);
        var form_confirm = $(clas).attr('data-confirm');

        $( clas ).validate({
            // define validation rules
            rules: rules,

            //display error alert on form submit  
            invalidHandler: function(event, validator) {     
                var alert = $('.m_form_msg');
                alert.removeClass('m--hide').show();
                mUtil.scrollTo(alert, -200);
            },

            submitHandler: function (form) {
                if(form_confirm == 1){
                    swal({
                        title	    	  : 'Are you sure ?',
                        animation   	  : false,
                        customClass 	  : 'animated tada',
                        text 			  : "You want edit this data !",
                        type 			  : 'warning',
                        showCancelButton  : true,
                        confirmButtonText : 'Yes, Edit it!'
                    }).then(function(result) {
                        if (result.value) {
                            var options = { 
                                dataType:      'json',
                                success:       callback_form,
                                error:         callback_error
                            }; 

                            $(form).ajaxSubmit(options);

                        }
                    });
                }else if(form_confirm == 2){
                    swal({
                        title             : 'Are you sure ?',
                        animation         : false,
                        customClass       : 'animated tada',
                        text              : "You want upload !",
                        type              : 'warning',
                        showCancelButton  : true,
                        confirmButtonText : 'Yes, Upload!'
                    }).then(function(result) {
                        if (result.value) {
                            var options = { 
                                dataType:      'json',
                                success:       callback_form,
                                error:         callback_error
                            }; 

                            $(form).ajaxSubmit(options);

                        }
                    });
                }else{
                    var options = { 
                        dataType:      'json',
                        success:       callback_form,
                        error:         callback_error
                    }; 

                    $(form).ajaxSubmit(options);
                }
                // Close Modal
                // $('body').removeClass('modal-open');
                // $('.modal-backdrop').remove();
                
            }
        });

        function callback_form(res, statusText, xhr, $form){
            if(res.status == 1){
                warning.hide();

                toastr.options = call_toastr('4000');
                var $toast = toastr['success'](res.message, "Success");

                if($('.reload').length)
                {
                    $('.reload').trigger('click');
                }
                $('.modal').modal('hide');
                $('.modal-backdrop').remove();

            }else if(res.status == 2){
                warning.hide();

                toastr.options = call_toastr('4000');
                var $toast     = toastr['warning'](res.message, "Warning");
            }else if(res.status == 0){
                warning.hide();

                toastr.options = call_toastr('4000');
                var $toast     = toastr['error'](res.message, "Error");
            }else{
                warning.find('span').html(res.message);
                warning.show();
            }

        }

        function callback_error(){
            toastr.options = call_toastr('4000');
            var $toast     = toastr['error']('Something wrong!', "Error");
        }

        function call_toastr(duration){
            var option = {
                "closeButton": true,
                "debug": false,
                "positionClass": "toast-top-right",
                "onclick": null,
                "showDuration": "1000",
                "hideDuration": "1000",
                "timeOut": duration,
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };

            return option;
        }
    }

    var hlp_select2 = function(clas,url,placeholder,tag,clear) {
        var Dtag = (typeof tag == "undefined" || tag == false  ? false : true); 
        var Dpcd = (typeof placeholder == "undefined" ? 'Select Option' : placeholder); 
        var Dclr = (typeof clear == "undefined" ? false : true);

        if(typeof url == 'undefined' || url == '' || url == null){
            $(clas).select2({
                allowClear	 : Dclr,
                placeholder	 : Dpcd
            });
        }else{
            $(clas).select2({
                allowClear	 : Dclr,
                placeholder	 : Dpcd,
                ajax: {
                    url 	 : base_url + url,
                    dataType : 'json',
                    delay	 : 250,
                    data 	 : function(params) {
                        return {
                            q		: params.term, // search term
                            page 	: params.page
                        };
                    },
                    processResults 	: function(data, params) {
                        // parse the results into the format expected by Select2
                        // since we are using custom formatting functions we do not need to
                        // alter the remote JSON data, except to indicate that infinite
                        // scrolling can be used
                        params.page = params.page || 1;

                        return {
                            results 	: data.items,
                            pagination 	: {
                                more: (params.page * 30) < data.total_count
                            }
                        };
                    },
                    cache: true
                },
                tags				: Dtag,
                tokeSparator		: [','],
                escapeMarkup		: function(markup) { return markup; }, // let our custom formatter work
                minimumInputLength	: 1,
                templateResult		: formatResult, // omitted for brevity, see the source of this page
                templateSelection	: formatResult // omitted for brevity, see the source of this page
            });
        }

        function formatResult(result){
            return result.text;
        }
    }

    var hlp_touchpin = function(clas,url,field) {

        var bloodhound = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.whitespace,
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            // `states` is an array of state names defined in "The Basics"
            prefetch: base_url + url
        });

        $(clas).typeahead({
            hint 	  : true,
            highlight : true,
            minLength : 1
        },{
            names 	  : 'states',
            source    : bloodhound
        }).change(function(){
            var val = $(this).val();
            var spt = val.split(" - ");
            var dta = { 'nopeg' : spt[0] };
            var lnk = base_url + url;

            $.post(lnk,dta,function(data){
                $('.f_nama').val(data[0].emp_nama);
                $('.f_posisi').val(data[0].emp_posisi);
            },'json');

            $(field).val(spt[0]);
        }); 
    }

    var hlp_dtrp = function(tipe,clas,prm){
        // 1 = datepicker, 2 = datettimepicker, 3 = timepicker, 4 = rangepicker

        if(tipe == '1'){
            $(clas).datepicker(prm);
        }else if(tipe == '2'){
            $(clas).datetimepicker(prm);
        }else if(tipe == '3'){
            $(clas).timepicker(prm);
        }else{
            $(clas).daterangepicker(prm,function(start, end, label) {
                $(clas + ' .form-control').val( start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY'));
            });
        }
    }

    var hlp_summernote = function (clas,prm) {
        prm.dialogsInBody = true;
        $(clas).summernote(prm);
    }

    hlp_treeview = function(clas,prm){
        $(clas).jstree({
            'plugins': ["wholerow", "types"],
            'core': {
                "themes" : {
                    "responsive": false
                },    
                'data': prm
            },
            "types" : {
                "default" : {
                    "icon" : "fa fa-folder m--font-warning"
                },
                "file" : {
                    "icon" : "fa fa-file  m--font-warning"
                }
            },
        });
    }

    var hlp_bootstrapSelect = function (clas) {
        $(clas).selectpicker();
    }

    var hpl_table_basic = function(clas){
        var e;
        (e = $(clas)).DataTable({
            responsive: !0,
            lengthMenu: [5, 10, 25, 50],
            pageLength: 10,
            language: {
                lengthMenu: "Display _MENU_"
            },
            order: [
                [1, "asc"]
            ],
            columnDefs: [{
                targets: 0,
                width: "30px",
                className: "dt-right",
                orderable: !1,
                render: function(e, a, t, n) {
                    return e
                }
            }]
        }), e.on("change", ".m-group-checkable", function() {
            var e = $(this).closest("table").find("td:first-child .m-checkable"),
                a = $(this).is(":checked");
            $(e).each(function() {
                a ? ($(this).prop("checked", !0), $(this).closest("tr").addClass("active")) : ($(this).prop("checked", !1), $(this).closest("tr").removeClass("active"))
            })
        }), e.on("change", "tbody tr .m-checkbox", function() {
            $(this).parents("tr").toggleClass("active")
        })
    }

    return {
        init_datatable		 : function (clas,url,column,clas_select,eexcel) { help_datatable(clas,url,column,clas_select,eexcel) },
        init_datatable_child : function (clas,url,column,clas_select,url_child,column_child,eexcel='') { help_datatable_child(clas,url,column,clas_select,url_child,column_child,eexcel='') },
        init_form_validation : function (clas,rules,messages) { help_form_validation(clas,rules,messages) },
        init_select2	     : function (clas,url,placeholder,tag,clear) { hlp_select2(clas,url,placeholder,tag,clear) },
        init_touchpin	     : function (clas,url,field) { hlp_touchpin(clas,url,field) },
        init_dtrp		     : function (tipe,clas,prm) { hlp_dtrp(tipe,clas,prm) },
        init_summernote      : function (clas,prm) { hlp_summernote(clas,prm) },
        init_treeview        : function (clas,prm) { hlp_treeview(clas,prm) },
        init_bootstrapSelect : function (clas) { hlp_bootstrapSelect(clas) },
        init_tableBasic      : function (clas) { hpl_table_basic(clas) }
    };
}();
