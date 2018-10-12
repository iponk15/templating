<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fetch extends MX_Controller {

    function __construct() 
    {
        parent::__construct();
    }

    /* Start Fetch Core Controller */

    public function fetch_global($table,$id,$field){
        $q                     = $_GET['q'];
        $where[$field.' LIKE'] = '%'.$q.'%';
        $result                = $this->m_global->get( $table, null, $where, '*', null, null, 0, 5, null, 1 );
        $data                  = [];

        for ($i=0; $i < count( $result ); $i++) {
            $data[$i] = ['id' => $result[$i][$id], 'text' => $result[$i][$field] ];
        }

        echo json_encode( ['items' => $data] );
    }

    public function touchpin($table){
        $post = $this->input->post();

        $where  = ( !empty($post) ? [ 'emp_nopeg' => $post['nopeg'] ] : [ 'emp_nama <>' => '' ] );
        $select = 'emp_nopeg,emp_nama,emp_unit,emp_posisi';
        $data   = $this->m_global->get($table,null,$where,$select);
        
        
        if(!empty($post)){
            echo json_encode($data);
        }else{
            $param  = [];

            foreach ($data as $value) {
                $param[] = $value->emp_nopeg.' - '.$value->emp_nama;
            }

            echo $encode = json_encode($param);
        }

    }

    public function fetch_icon($type){
        $q                       = $_GET['q'];
        $where_e                 = "icon_icon LIKE '%".$q."%' AND icon_tipe = ".$type." AND icon_status = '1'"; 
        $result                  = $this->m_global->get( 'cuti_icon', null, null, '*', $where_e, null, 0, 5, null, 1 );
        $data                    = [];

        for ($i=0; $i < count( $result ); $i++) {
            if($type == 1){
                $data[$i] = ['id' => $result[$i]['icon_icon'], 'text' =>  '<i class="flaticon-'.$result[$i]['icon_icon'].'"></i>&nbsp;&nbsp;&nbsp;' . $result[$i]['icon_icon'] ];
            }else{
                $data[$i] = ['id' => 'fa fa-'.$result[$i]['icon_icon'].' m--font-' . state_color($result[$i]['icon_color']), 'text' =>  '<span class="m-badge m-badge--'.state_color($result[$i]['icon_color']).' m-badge--dot"></span><span class="fa fa-'.$result[$i]['icon_icon'].'"></span>&nbsp;&nbsp;&nbsp;'.$result[$i]['icon_icon']];
            }
        }

        echo json_encode( ['items' => $data] );
    }

    public function fetch_departure()
    {
        $q          = $_GET['q'];
        $select     = 'gendec_airport_c_is_dep';
        $where      = ['gendec_dep_date_scd_lokal' => date('Y-m-d'), 'gendec_airport_c_is_dep LIKE' => '%'.$q.'%'];
        $records    = $this->m_global->get('has_vw_gendec', null, $where, $select);
        $result = array();
        foreach ($records as $data) {
            $result[] = $data->gendec_airport_c_is_dep;
        }
        $unique     = array_unique($result);
        // pre($unique,1);
        $final = [];
        foreach ($unique as $value) {
            $data = ['id' => $value, 'text' => $value ];
            array_push($final, $data);
        }

        echo json_encode( ['items' => $final] );
    }

    public function fetch_destination()
    {
        $q          = $_GET['q'];
        $select     = 'gendec_airport_c_is_dest';
        $where      = ['gendec_dep_date_scd_lokal' => date('Y-m-d'), 'gendec_airport_c_is_dest LIKE' => '%'.$q.'%'];
        $records    = $this->m_global->get('has_vw_gendec', null, $where, $select);
        $result = array();
        foreach ($records as $data) {
            $result[] = $data->gendec_airport_c_is_dest;
        }
        $unique     = array_unique($result);
        // pre($unique,1);
        $final = [];
        foreach ($unique as $value) {
            $data = ['id' => $value, 'text' => $value ];
            array_push($final, $data);
        }

        echo json_encode( ['items' => $final] );
    }

    public function fetch_bo($table){
        $q                     = $_GET['q'];
        $where['bo_code LIKE'] = '%'.$q.'%';
        $where['bo_status']    = '1';
        $select                = 'bo_code,concat(bo_code," - ",bo_airport) bo_airport';
        $result                = $this->m_global->get( $table, null, $where, $select, null, null, 0, 5, null, 1 );
        $data                  = [];
        
        for ($i=0; $i < count( $result ); $i++) {
            $data[$i] = ['id' => $result[$i]['bo_code'], 'text' => $result[$i]['bo_airport'] ];
        }

        echo json_encode( ['items' => $data] );
    }

    public function fetch_ohterHotel($table,$loc){
        $q                               = $_GET['q'];
        $where['hotel_name LIKE']        = '%'.$q.'%';
        $where['hotel_status']           = '1';
        $where[md56('hotel_location',1)] = $loc;
        $select                          = 'hotel_id, hotel_name';
        $result                          = $this->m_global->get( $table, null, $where, $select, null, null, 0, 5, null, 1 );
        $data                            = [];
        
        for ($i=0; $i < count( $result ); $i++) {
            $data[$i] = ['id' => md56($result[$i]['hotel_id']), 'text' => $result[$i]['hotel_name'] ];
        }

        echo json_encode( ['items' => $data] );
    }

}

/* End of file config.php */
/* Location: ./application/modules/config/controllers/config.php */