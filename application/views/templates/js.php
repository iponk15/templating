<?php
	foreach ((array)@$js as $key => $value) {
		echo '<script src="'.base_url('assets/pages/scripts/'.$value.'.js').'" type="text/javascript"></script>';
	}
?>