<?php
class Manage extends CI_Model {
  public function __construct() {
    parent::__construct();
  }

  public function acceptReserved($id){
    $this->db->query("UPDATE reserveduser SET Status = 'Accepted' WHERE RequestID = '$id'");
  }

  public function declineReserved($id){
    $this->db->query("UPDATE reserveduser SET Status = 'Declined' WHERE RequestID = '$id'");
  }

  public function printAllFacility(){
    $query = $this->db->query("SELECT * FROM facility");
		return $query->result_array();
  }

  public function printOneFacility($id){
    $query = $this->db->query("SELECT * FROM facility WHERE FacilityID = $id");
		return $query->result_array();
  }

  public function getFacilityName($id) {
    $query = $this->db->query("SELECT FacilityName FROM facility WHERE FacilityID = $id");
    $query = $query->row_array();
    return $query['FacilityName'];
  }

  // public function getBookingInfo() {
  //   $query = $this->db->query("SELECT user.Username, StartTime, EndTime, ReqFacilityID, `Date`
  //     FROM user, reserveduser
  //     WHERE user.UserID = reserveduser.RequesterID
  //   ");

  //   $query = $query->result_array();
  //   return $query;
  // }
  // public function getBookingInfo($id,$date) {
  //   $query = $this->db->query("SELECT StartTime, EndTime, `Date`, ReqFacilityID
  //     FROM reserveduser WHERE ReqFacilityID = $id AND `Date` = $date");

  //   $query = $query->result_array();
  //   return $query;
  // }
}