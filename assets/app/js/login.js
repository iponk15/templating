var SnippetLogin = function(){
    var e = $("#m_login"),i=function(e,i,a){
        var l = $('<div class="m-alert m-alert--outline alert alert-'+i+' alert-dismissible" role="alert">\t\t\t<button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>\t\t\t<span></span>\t\t</div>');
        e.find(".alert").remove(),l.prependTo(e),mUtil.animateClass(l[0],"fadeIn animated"),l.find("span").html(a)
    },
    
    a = function(){
        e.removeClass("m-login--forget-password"),
        e.removeClass("m-login--signup"),
        e.addClass("m-login--signin"),
        mUtil.animateClass(e.find(".m-login__signin")[0],"flipInX animated")
    },

    l = function(){
        $("#m_login_forget_password").click(function(i){
            i.preventDefault(),
            e.removeClass("m-login--signin"),
            e.removeClass("m-login--signup"),
            e.addClass("m-login--forget-password"),
            mUtil.animateClass(e.find(".m-login__forget-password")[0],"flipInX animated")
        }),
        
        $("#m_login_forget_password_cancel").click(function(e){
            e.preventDefault(),a()
        }),
        
        $("#m_login_signup").click(function(i){
            i.preventDefault(),e.removeClass("m-login--forget-password"),
            e.removeClass("m-login--signin"),
            e.addClass("m-login--signup"),
            mUtil.animateClass(e.find(".m-login__signup")[0],"flipInX animated")
        }),
        
        $("#m_login_signup_cancel").click(function(e){
            e.preventDefault(),a()
        })
    };
    
    return{
        init:function(){
            l(),$("#m_login_signin_submit").click(function(e){
                e.preventDefault();
                var a   = $(this),l=$(this).closest("form");
                var uri = a.attr('action');

                l.validate({
                    rules:{
                        user_email:{
                            required:!0,
                            email: !0
                        },
                        user_password:{
                            required:!0
                        },
                        catpcha:{
                            required:!0
                        }
                    }
                }),
                l.valid() && (a.addClass("m-loader m-loader--right m-loader--light").attr("disabled",!0),l.ajaxSubmit({
                    url      : uri,
                    dataType : 'json',
                    success:function(e,t,r,s){
                        if(e.status == 1){
                            window.location = base_url + 'welcome';
                        }else if(e.status == 2){
                            $('#syncap').trigger('click');
                            
                            setTimeout(function(){
                                a.removeClass("m-loader m-loader--right m-loader--light").attr("disabled",!1),i(l,"warning",e.message);
                            },100);
                        }else{
                            $('#syncap').trigger('click');
                            
                            setTimeout(function(){
                                a.removeClass("m-loader m-loader--right m-loader--light").attr("disabled",!1),i(l,"danger","Incorrect username or password. Please try again.");
                            },100);
                        }
                    }
                }))
            }),
            
            $("#m_login_signup_submit").click(function(l){
                l.preventDefault();
                var t   = $(this),r = $(this).closest("form");
                var uri = t.attr('action');

                r.validate({
                    rules: {
                        nama: {
                            required: !0
                        },
                        email: {
                            required: !0,
                            email: !0
                        },
                        password: {
                            required: !0,
                            hurufKecil: true,
                            hurufBesar: true,
                            angka     : true,
                            minlength : 8,
                            maxlength : 16
                        },
                        rpassword: {
                            equalTo   : '.pSwd',
                            required: !0
                        },
                        agree: {
                            required: !0
                        }
                    },
                    messages: {
                        nama: {
                            required: 'Nama harus diisi'
                        },
                        email: {
                            required: 'Email harus diisi'
                        }
                    }
                }),
                r.valid() && (t.addClass("m-loader m-loader--right m-loader--light").attr("disabled",!0),r.ajaxSubmit({
                    url      : uri,
                    dataType : 'json',
                    success: function(l, s, n, o) {
                        var mess = l.message;
                        if(l.status == '2'){
                            setTimeout(function() {
                                t.removeClass("m-loader m-loader--right m-loader--light").attr("disabled", !1), r.clearForm(), r.validate().resetForm(), a();
                                var l = e.find(".m-login__signin form");
                                l.clearForm(), l.validate().resetForm(), i(l, "warning", mess);
                            }, 2e3)
                        }else{
                            setTimeout(function() {
                                t.removeClass("m-loader m-loader--right m-loader--light").attr("disabled", !1), r.clearForm(), r.validate().resetForm(), a();
                                var l = e.find(".m-login__signin form");
                                l.clearForm(), l.validate().resetForm(), i(l, "info", mess);
                            }, 2e3)
                        }
                    }
                }))
            }),
            
            $("#m_login_forget_password_submit").click(function(l){
                l.preventDefault();var t=$(this),r=$(this).closest("form");r.validate({
                    rules:{
                        email:{
                            required:!0,
                            email:!0
                        }
                    }
                }),
                
                r.valid()&&(t.addClass("m-loader m-loader--right m-loader--light").attr("disabled",!0),r.ajaxSubmit({
                    url:"",
                    success:function(l,s,n,o){
                        setTimeout(function(){
                            t.removeClass("m-loader m-loader--right m-loader--light").attr("disabled",!1),
                            r.clearForm(),r.validate().resetForm(),a();
                            var l = e.find(".m-login__signin form");
                            l.clearForm(),l.validate().resetForm(),i(l,"success","Cool! Password recovery instruction has been sent to your email.")
                        },2e3)
                    }
                }))
            })
        }
    }
}();

jQuery(document).ready(
    function(){
        SnippetLogin.init();
    }
);