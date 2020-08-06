<?php
class ModelExtensionModuleCallback extends Model 
{

    public function RecordData()
    {
      if ($this->request->post['action'] == "send_callback")
      {
        date_default_timezone_set("Europe/Moscow");
        $data = $this->request->post;
        $time = date("Y-m-d H:i:s");
        $this->db->query("INSERT INTO `oc_callback`(`name`, `phone`, `mail`, `vin`, `product_id`, `quest`, `date`) VALUES ('".$data['consumer_name'] ."','".$data['consumer_tel'] ."','".$data['consumer_mail'] ."','".$data['consumer_vin'] ."','".$data['product_id'] ."','".$data['consumer_text'] ."', '". $time ."')");
        $query = $this->db->query("SELECT id FROM `oc_callback` WHERE `date` = '".$time."'");
        return ($query->row);
      }
      return ("");
    }
}
?>