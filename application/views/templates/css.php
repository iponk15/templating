<?php
	foreach ((array)@$css as $key => $value) {
		echo '<link href="'.base_url('assets/pages/css/'.$value.'.css').'" rel="stylesheet" type="text/css" />'."\n";
	}
?>