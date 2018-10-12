<div class="m-portlet m-portlet--mobile">
	<div class="m-portlet__head">
		<div class="m-portlet__head-caption">
			<div class="m-portlet__head-title">
				<h3 class="m-portlet__head-text">
					<span><?php echo $pagetitle ?></span>
				</h3>
			</div>
		</div>
		<div class="m-portlet__head-tools">
			<ul class="m-portlet__nav">
				<li class="m-portlet__nav-item">
					<a href="<?php echo base_url($url.'/show_add'); ?>" class="m-portlet__nav-link m-portlet__nav-link--icon ajaxify">
						<i class="la la-refresh"></i>
					</a>
				</li>
			</ul>
		</div>
	</div>
	<div class="m-portlet m-portlet--tab">
		<!--begin::Form-->
		<form class="m-form m-form--fit m-form--label-align-right form_add" action="<?php echo base_url('user/action_add') ?>" method="post">
			<div class="m-portlet__body">
				<div class="m-form__content">
					<div class="m-alert m-alert--icon alert alert-warning m--hide m_form_msg" user="alert">
						<div class="m-alert__icon"><i class="la la-warning"></i></div>
						<div class="m-alert__text">Perhatian !!! <br> Ada inputan yang masih belum di isi.</div>
						<div class="m-alert__close"><button type="button" class="close" data-close="alert" aria-label="Close"></button></div>
					</div>
				</div>
				<div class="form-group m-form__group row">
					<label for="example-text-input" class="col-form-label col-lg-2 col-sm-12">Nopeg <span class="m--font-danger">*</span></label>
					<div class="col-lg-4 col-md-9 col-sm-12">
						<input class="form-control m-input" type="number" name="user_nopeg" placeholder="Nomer Pegawai" tabindex="1" maxlength="6" minlength="6">
					</div>
				</div>
				<div class="form-group m-form__group row">
					<label for="example-text-input" class="col-form-label col-lg-2 col-sm-12">Nama <span class="m--font-danger">*</span></label>
					<div class="col-lg-4 col-md-9 col-sm-12">
						<input class="form-control m-input" type="text" name="user_nama" placeholder="Nama" tabindex="2">
					</div>
				</div>
				<div class="form-group m-form__group row">
					<label for="example-text-input" class="col-form-label col-lg-2 col-sm-12">Email <span class="m--font-danger">*</span></label>
					<div class="col-lg-4 col-md-9 col-sm-12">
						<input class="form-control m-input" type="email" name="user_email" placeholder="Email" tabindex="3">
					</div>
				</div>
				<div class="form-group m-form__group row">
					<label class="col-form-label col-lg-2 col-sm-12">Role <span class="m--font-danger">*</span></label>
					<div class="col-lg-4 col-md-9 col-sm-12">
						<select required class="form-control m-select2 user_role" name="user_role" tabindex="4">
							<option value=""></option>
							<option value="1">Superadmin</option>
							<option value="2">KK (Kilo-kilo)</option>
							<option value="3">AA</option>
							<option value="4">DM</option>
						</select>				
						<span class="m-form__help"></span>
					</div>
				</div>
				<div class="form-group m-form__group row">
					<label for="example-text-input" class="col-form-label col-lg-2 col-sm-12">Password <span class="m--font-danger">*</span></label>
					<div class="col-lg-4 col-md-9 col-sm-12 input-group">
						<input class="form-control m-input" type="password" id="pass" name="user_pass" placeholder="Password" tabindex="5" minlength="8" maxlength="10">
						<div class="input-group-append">
							<span class="input-group-text">
								<i toggle="#pass" class="fa fa-fw fa-eye field-icon toggle-password"></i>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group m-form__group row">
					<label for="example-text-input" class="col-form-label col-lg-2 col-sm-12">Confirm Password <span class="m--font-danger">*</span></label>
					<div class="col-lg-4 col-md-9 col-sm-12 input-group">
						<input class="form-control m-input" id="cpass" type="password" name="user_cpass" placeholder="Confirm Password" tabindex="6">
						<div class="input-group-append">
							<span class="input-group-text">
								<i toggle="#cpass" class="fa fa-fw fa-eye field-icon toggle-password"></i>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group m-form__group row f_bo" style="display:none;">
					<label class="col-form-label col-lg-2 col-sm-12">BO <span class="m--font-danger">*</span></label>
					<div class="col-lg-4 col-md-9 col-sm-12">
						<select disabled required class="form-control m-select2 user_bo" name="user_bo" tabindex="7" style="width:100%;">
							<option value=""></option>
						</select>				
						<span class="m-form__help"></span>
					</div>
				</div>
			</div>
			<div class="m-portlet__foot m-portlet__foot--fit">
				<div class="m-form__actions">
					<div class="row">
						<div class="offset-2 col-10">
                            <a href="<?php echo base_url('user'); ?>" class="btn btn-danger m-btn btn-sm  m-btn m-btn--icon ajaxify" tabindex="7">
                            	<span><i class="fa fa-backspace"></i>
									<span>Back</span>
								</span>
                            </a>
                            <button type="submit" class="btn btn-success m-btn btn-sm m-btn m-btn--icon" tabindex="8">
                            	<span>
	                            	<i class="fa fa-check-circle"></i>
	                            	<span>Submit</span>
                            	</span>
                            </button>
                        </div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<a href="<?php echo base_url('user/show_add'); ?>" class="reload ajaxify"></a>

<script type="text/javascript">
	$(document).ready(function () {
		global.init_select2('.user_bo','fetch/fetch_bo/has_bo','Select BO');

		$('.user_role').select2({
			placeholder : 'Select Role',
			allowClear  : true
		}).on('change',function(){
			var val = $(this).val();

			if(val != '1' && val != '4'){
				$('.f_bo').fadeIn('slow');
				$('.user_bo').attr('disabled',false);
				$('.user_bo').attr('required',true);
			}else{
				$('.f_bo').fadeOut('slow');
				$('.user_bo').attr('disabled',true);
				$('.user_bo').attr('required',false);
			}
		});

		// set form validation
		var rules = {
			user_nopeg		: { required : true,
								angka	 : true 
							},
	        user_nama		: { required: true },
	        user_email		: { required: true },
	        user_pass		: {
	        	 				required	: true,
	        	 				angka		: true, 
	        	 				hurufBesar	: true, 
	        	 				hurufKecil	: true
	        	 				// equalto		: '#cpass'
	        				},
	        user_cpass	 	:  { required  : true,
	        					equalTo	   : '#pass' 
	        				},

	    };

	    var message = {};
		global.init_form_validation('.form_add',rules,message);

		$(".toggle-password").click(function() {
			$(this).toggleClass('fa-eye fa-eye-slash');
				var input = $($(this).attr("toggle"));
				if (input.attr("type") == "password") {
					input.attr('type', 'text');
				} else {
					input.attr('type', 'password');
				}
			});
	});
</script>