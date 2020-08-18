<?php
class ModelExtensionModuleCallback extends Model 
{

    public function RecordData()
    {
      if ($this->request->post['action'] == "send_callback")
      {
        $data = $this->request->post;
        
        if (empty($data['consumer_name']) || empty($data['consumer_tel']))
          return (['id' => 0]);
        date_default_timezone_set("Europe/Moscow");
        $time = date("Y-m-d H:i:s");
        $data['consumer_name'] = $this->defend_str($data['consumer_name']);
        $data['consumer_tel'] = $this->defend_str($data['consumer_tel']);
        $data['consumer_mail'] = $this->defend_str($data['consumer_mail']);
        $data['consumer_vin'] = $this->defend_str($data['consumer_vin']);
        $data['consumer_text'] = $this->defend_str($data['consumer_text']);
        $data['consumer_tel'] = $this->validate_tel($data['consumer_tel']);
        $data['consumer_mail'] = $this->validate_mail($data['consumer_mail']);
        $this->db->query("INSERT INTO `oc_callback`(`name`, `phone`, `mail`, `vin`, `product_id`, `quest`, `date`) VALUES ('".$data['consumer_name'] ."','".$data['consumer_tel'] ."','".$data['consumer_mail'] ."','".$data['consumer_vin'] ."','".$data['product_id'] ."','".$data['consumer_text'] ."', '". $time ."')");
        $query = $this->db->query("SELECT `id`, `product_id` FROM `oc_callback` WHERE `date` = '".$time."'");
        return ($query->row);
      }
      return ("");
    }

    private function defend_str($string = " ")
    {
      $str = trim($string);
      $str = stripslashes($str);
      $str = strip_tags($str);
      $str = htmlspecialchars($str);
      return ($str);
    }

    private function validate_tel($tel)
    {
      $tel = mb_ereg_replace("[^0-9]", '', $tel);
      if (strlen($tel) == 11)
        $tel = mb_substr($tel, 0, 1) ." (". mb_substr($tel, 1, 3) .") ". mb_substr($tel, 4, 3) ."-". mb_substr($tel, 7, 2) ."-". mb_substr($tel, 9, 2);
      if (mb_substr($tel, 0, 1) == "7")
        $tel = "+".$tel;
      return($tel);
    }

    private function validate_mail($mail = "")
    {
      if (filter_var($mail, FILTER_VALIDATE_EMAIL))
        return($mail);
      return(" ");
    }
}
?>