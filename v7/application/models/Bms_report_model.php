<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    Class Bms_report_model extends CI_Model
    {
    function __construct () { parent::__construct();
    }
    function all_bank_details()
    {
        $sql = "SELECT paydate,receiptno,checkno,totalamount,owner_name FROM payment where paymode ='cheque'";
        $query = $this->db->query($sql);
        return $query->result();
    }
        /*all data select*/
    function totat_amount()
    {
        $sql = "SELECT  SUM(totalamount) AS totalamount FROM payment where paymode ='cheque'";
        $query = $this->db->query($sql);
        return $query->result();
    }
       /*sum all amount*/
    }