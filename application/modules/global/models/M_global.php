<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_global extends CI_Model {

    public function __construct(){
        parent::__construct();
        $this->db->query("SET time_zone='+07:00'");
    }

    public function count( $table, $join = NULL, $where = NULL, $where_e = NULL, $group = NULL )
    {
        $this->db->select("count(*) as jumlah")->from($table);

        if ( ! is_null( $join ) )
        {
            foreach ($join as $key => $value) {
                $tipe = ( @$value[2] != '' ) ? $value[2] : 'INNER';
                $this->db->join( $value[0], $value[1], $tipe );
            }
        }

        ( ! is_null($where)
            ? $this->db->where($where) 
            : ''
        );

        ( ! is_null($where_e) 
            ? $this->db->where($where_e, NULL, FALSE) 
            : ''
        );
        
        ( ! is_null($group) 
            ? $this->db->group_by($group, NULL, FALSE) 
            : ''
        );

        $query  = $this->db->get();
        if(!$query) pre($this->db->error(), 1);

        $result = $query->row();
        if(empty($result)){
            return '0';
        }else{
            return $result->jumlah;
        }
    }

    /**
     * Mengambil data dari $tabel
     *
    **/

    public function get( $table, $join = NULL, $where = NULL, $select = '*', $where_e = NULL, $order = NULL, $start = 0, $tampil = NULL, $group = NULL, $array = null )
    {
        if ( is_array($select))
        {
            $this->db->select( $select[0], $select[1] )->from($table);
        }
            else
        {
            $this->db->select($select)->from($table);
        }

        if ( ! is_null( $join ) )
        {
            foreach ($join as $key => $value) {
                $tipe = ( @$value[2] != '' ) ? $value[2] : 'INNER';
                $this->db->join( $value[0], $value[1], $tipe );
            }
        }

        if( ! is_null( $order )){
            if(is_array($order[0])){
                foreach ($order as $key => $value) {
                    $this->db->order_by( $value[0], $value[1]);
                }
            }else{
                $this->db->order_by( $order[0], $order[1]);
            }
              
        } 
        ( ! is_null( $tampil ) 
            ? $this->db->limit( $tampil, $start) 
            : ''
        );
        ( ! is_null( $where ) 
            ? $this->db->where( $where) 
            : ''
        );
        ( ! is_null( $where_e ) 
            ? $this->db->where( $where_e, NULL, FALSE) 
            : ''
        );
        ( ! is_null( $group ) 
            ? $this->db->group_by( $group, NULL, FALSE) 
            : ''
        );

        $query  = $this->db->get();

        $err = $this->db->error();
        if ( $err['code'] != 0 ) {
            pre('lastdb');
            pre($err, 1);
        }

        ( ! is_null( $array ) 
            ? $result = $query->result_array()
            : $result = $query->result()
        );
        
        return $result;
    }

    public function insert( $table, $data = NULL )
    {
        $result    = $this->db->insert( $table, $data );

        if ( $result == TRUE )
        {
            $result             = [];
            $result['status']   = TRUE;
            $result['id']       = $this->db->insert_id();
        }
            else
        {
            $result             = [];
            $result['status']   = FALSE;
        }

        return $result;
    }

    public function update( $table, $data = NULL, $where = NULL, $where_e = NULL )
    {
        ( ! is_null($where_e) 
            ? $this->db->where($where_e, NULL, FALSE) 
            : ''
        );

        $result    = $this->db->update( $table, $data, $where );

        return $result;
    }

    public function delete( $table, $where = NULL, $where_e = NULL )
    {
        ( ! is_null($where_e) 
            ? $this->db->where($where_e, NULL, FALSE) 
            : ''
        );

        $result    = $this->db->delete( $table, $where );

        return $result;
    }

    public function validation( $table, $where, $where_e = NULL )
    {
        $this->db->select('*')->from( $table );
        
        ( ! is_null($where) 
            ? $this->db->where($where) 
            : ''
        );
        ( ! is_null($where_e) 
            ? $this->db->where($where_e, NULL, FALSE) 
            : ''
        );

        $query  = $this->db->get();
        $result = $query->num_rows();

        if( $result > 0 )
        {
            $result = FALSE;
        }
            else
        {
            $result = TRUE;
        }

        return $result;
    }

    public function get_num_rows( $table, $join = NULL, $where = NULL, $where_e = NULL, $group = NULL )
    {
        $this->db->select("count(*) as jumlah")->from($table);

        if ( ! is_null( $join ) )
        {
            foreach ( $join as $rows) 
            {
                $tipe = ( @$rows['tipe'] != '' ) ? $rows['tipe'] : 'INNER';
                $this->db->join( $rows['table'], $rows['on'], $tipe );
            }
        }

        ( ! is_null($where) 
            ? $this->db->where($where) 
            : ''
        );
        ( ! is_null($where_e) 
            ? $this->db->where($where_e, NULL, FALSE) 
            : ''
        );
        ( ! is_null($group) 
            ? $this->db->group_by($group, NULL, FALSE) 
            : ''
        );

        $query  = $this->db->get();
        $result = $query->num_rows();

        return $result;
    }

    public function insert_update_batch( $table, $data )
    {
        $count      = 0;
        $jumlah     = 0;

        foreach ($data as $param) {
            $result     = $this->_insert_update($table, $param);
            if ($result == TRUE){
                $count++;
            }
            $jumlah++;
        }

        if ( $count == $jumlah )
        {
            $result             = [];
            $result['status']   = TRUE;
        }
            else
        {
            $result             = [];
            $result['status']   = FALSE;
            $result['message']  = ($jumlah - $count) . ' data gagal diproses';
        }

        return $result;
    }

    public function insert_update( $table, $data )
    {
        $result     = $this->_insert_update($table, $data);

        if ( $result == TRUE )
        {
            $result             = [];
            $result['status']   = TRUE;
        }
            else
        {
            $result             = [];
            $result['status']   = FALSE;
        }

        return $result;
    }

    public function _insert_update($table, $data)
    {
        $key    = [];
        $value  = [];

        //generate duplicate
        $strDuplicate   = [];
        foreach ($data as $kolom => $nilai) {
            $key[]              = $kolom;
            $value[]            = $nilai;

            $nilai              = $this->db->escape($nilai);
            $strDuplicate[]     = "{$kolom} = {$nilai}";
        }
        $strDuplicate   = implode(",", $strDuplicate);

        // generate tanda tanya
        $tanya  = [];
        foreach ($value as $val)
        {
            $tanya[] = '?';
        }
        $tanya  = implode(", ", $tanya);

        $sKey   = implode(",", $key);

        $sql    = " INSERT INTO {$table}({$sKey}) VALUES ({$tanya})
                    ON DUPLICATE KEY UPDATE
                    {$strDuplicate}
                  ;";
        $query  = $this->db->query($sql, $value);

        return $query;
    }
    public function ginroSecurity($text){
        return htmlspecialchars($text);
    }
    // End Core Model

    public function getDataSp($call){
        $query = $this->db->query($call);
        $res = $query->result();

        $query->next_result(); 
        $query->free_result(); 

        return $res;
    }
}

/* End of file m_global.php */
/* Location: ./application/modules/global/models/m_global.php */