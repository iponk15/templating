//function update status
function f_status(stat, ele, eve, dtele){
    eve.preventDefault();

    if(stat == 1){
        var mes = "Are you sure want to change Status ?";
        var sus = "Successfully Change Status!";
        var err = "Error Change Status!";
        var head= "Status Changed!";
        var html = false;
    }else if(stat == 2){
        var mes = "Are you sure want to Delete data ?";
        var sus = "Successfully Delete data!";
        var err = "Error Delete data!";
        var head= "Deleted!";
        var html = false;
    }else if(stat == 3){
        var mes = "<b>This will delete all related Subscription too!</b></br>Are you sure want to Delete data ?";
        var sus = "Successfully Delete data!";
        var err = "Error Delete data!";
        var head= "Deleted";
        var html = true;
    }else if(stat == 4){
        var mes = "Are you sure want to create reservation ?";
        var sus = "Successfully Make Reservation!";
        var err = "Error Make Reservation!";
        var head= "Reservation!";
        var html = false;
    }else if(stat == 5){
        var mes = "Are you sure want to cancel reservation ?";
        var sus = "Successfully Cancel Reservation!";
        var err = "Error Cancel Reservation!";
        var head= "Reservation!";
        var html = false;
    }else if(stat == 6){
        var mes = "Are you sure want to create receipt ?";
        var sus = "Successfully Create Receipt!";
        var err = "Error Create Receipt!";
        var head= "Receipt!";
        var html = false;
    }
    swal({
        title: "Are you sure?",
        text: mes,
        html: html,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: 'Yes',
        closeOnConfirm: false
    }).then(function(isConfirm){
        if(isConfirm.value == true){
            var href = $(ele).attr('href');
            $.post(href, function(data1, textStatus, xhr) {
                swal(
                    head,
                    sus,
                    'success'
                );

                $(".reload").trigger("click");
            }, 'json');
        }
    });
};

$(document).on('click','.ajaxify',function(e){
    e.preventDefault();

    var ajaxify = [null, null, null];
    var url     = $(this).attr("href");
    var content = $('.body-content');
    var active  = 'm-menu__item--active';
    var activeparent = 'm-menu__item--submenu m-menu__item--open m-menu__item--expanded';
    var activeparents = 'm-menu__item--submenu m-menu__item--open';
    // var activesub1 = 'subparent1';
    // var activesub2 = 'subparent2';
    var cat     = $(this).attr('class');
    var titlep   = $('title').text();

    // Single menu / tidak punya child/parent
    if (cat == 'm-menu__link ajaxify') {
        $('li').removeClass(active);
        $('li').removeClass(activeparent);
        // $('li').removeClass(activesub1);
        $(this).parent().addClass(active);
    }

    if (cat == 'm-menu__link ajaxify submenu') {
        $('li').removeClass(active);
        $('li').removeClass(activeparent);
        // $('li').removeClass(activesub1);
        $(this).parent().addClass(active);
        $(this).parent().parent().parent().parent().addClass(activeparent);
        // $(this).addClass(activesub1);
    }

    if (cat == 'm-menu__link ajaxify submenu subparent1') {
        $('li').removeClass(active);
        $('li').removeClass(activeparent);
        $('li').removeClass(activeparents);
        // $('li').removeClass(activesub1);
        $(this).parent().addClass(active);
        // console.log('1');
        $(this).parent().parent().parent().parent().parent().parent().parent().addClass(activeparent);
        $(this).parent().parent().parent().parent().addClass(activeparents);
        // $(this).addClass(activesub1);
    }
    
    
    history.pushState(null, null, url);
    if(url != ajaxify[2]){
        ajaxify.push(url);
    }

    ajaxify     = ajaxify.slice(-3, 5);
    var the     = $(this);
    var posting = $.post( url, { status_link: 'ajax' } );

    posting.done(function( data ) {
        content.html(data);

        // set blockui
        mApp.block(content, {});
        setTimeout(function() {
            mApp.unblock(content);
        }, 1000);

        // set otomastis scroll top
        $('.m-scroll-top').trigger('click');
        var titlej   = $('.m-content .m-portlet__head-title h3 span').html();
        var title = $('.tab-title').text();
        $('title').text(titlej+' - Hotel Aircrew System');
    });

});

$(window).bind('popstate',function(){
    var state = window.location.href;
    var pageContent = $('.body-content');
    $.ajax({
        type: "POST",
        cache: false,
        url: state,
        data: { status_link: 'ajax' },
        dataType: "html",
        success: function(res) {
            if (res == 'out') {
                window.location = base_url + 'home';
            } else {
                pageContent.html(res);
                $('.m-scroll-top').trigger('click');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            errorAjaxify();
        }
    });
});

$(document).on('keyup blur', '.currency', function(a){
    var e = window.event || e;
    var keyUnicode = e.charCode || e.keyCode;
    var max = 11;

    if (e !== undefined) {
        switch (keyUnicode) {
            case 16: break; // Shift
            case 27: this.value = ''; break; // Esc: clear entry
            case 35: break; // End
            case 36: break; // Home
            case 37: break; // cursor left
            case 38: break; // cursor up
            case 39: break; // cursor right
            case 40: break; // cursor down
            case 78: break; // N (Opera 9.63+ maps the "." from the number key section to the "N" key too!) (See: http://unixpapa.com/js/key.html search for ". Del")
            case 110: break; // . number block (Opera 9.63+ maps the "." from the number block to the "N" key (78) !!!)
            case 190: break; // .
            default: $(this).formatCurrency({ colorize: true, negativeFormat: '-%s%n', roundToDecimalPlace: -1, eventOnDecimalsEntered: true });
        }
    }

    if (a.which < 0x20) {
        // e.which < 0x20, then it's not a printable character
        // e.which === 0 - Not a character
        return;     // Do nothing
    }
    if (this.value.length == max) {
        a.preventDefault();
    } else if (this.value.length > max) {
        // Maximum exceeded
        this.value = this.value.substring(0, max);
    }
    
})


