<p id="image_captcha"><?php echo $captcha['capImage']; ?></p> &nbsp;
<a href="javascript:void(0);" class="captcha-refresh" >reload</a>
<form method="post">
    <input type="text" name="captcha" value=""/>
    <input type="submit" name="submit" value="SUBMIT"/>
</form>

<script src="<?php echo base_url() ?>/assets/vendors/base/vendors.bundle.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>/assets/demo/base/scripts.bundle.js" type="text/javascript"></script>

<script>
    $( function(){

        $('.captcha-refresh').on('click', function(){

            $.get('<?php echo base_url().'login/form/reload'; ?>', function(data){

                $('#image_captcha').html(data);

            });

        });

    });

</script>