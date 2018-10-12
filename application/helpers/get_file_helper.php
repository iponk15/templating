<?php 

	function get_additional( $add, $tipe ){
		$arr = [
			'datatables' => [ 
				'css' => [
					'<link href="'.base_url('assets/vendors/custom/datatables/datatables.bundle.css').'" rel="stylesheet" type="text/css" />' 
				],
				'js' => [
					'<script src="'.base_url('assets/vendors/custom/datatables/datatables.bundle.js').'" type="text/javascript"></script>' 
				]
			]
		];

		$each = @$arr[ $add ][ $tipe ];
		if ( $each )
		{
			foreach ( $each as $key => $value ) {
				echo $value."\n";
			}
		}

	}
	
?>