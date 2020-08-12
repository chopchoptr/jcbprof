<?php
class ModelExtensionModuleCallback extends Model
{
    public function ChangeData()
    {
        if ($this->request->post['action'] == "write")
            $this->write_call_data($this->request->post);
        if ($this->request->post['action'] == "del")
            $this->del_call_data($this->request->post);
    }

    private function write_call_data($data)
    {
        date_default_timezone_set("Europe/Moscow");
        $time = date("Y-m-d H:i:s");
        $data['email'] = strlen($data['email']) > 0 ? $data['email'] : " ";
        $data['vin'] = strlen($data['vin']) > 0 ? $data['vin'] : " ";
        $data['question'] = strlen($data['question']) > 0 ? $data['question'] : " ";
        $data['notes'] = strlen($data['notes']) > 0 ? $data['notes'] : " "; 
        $this->db->query(" UPDATE `oc_callback` SET `mail`= '". $data['email']."', `vin`= '". $data['vin'] ."' ,`quest`= '". $data['question']. "', `notes`= '". $data['notes'] ."', `date` = '". $time ."', `status`= '". $data['status'] ."' WHERE `id` = '". $data['id']."'");
    }

    private function del_call_data($data)
    {
        $this->db->query("DELETE FROM `oc_callback` WHERE `id`= ". $data['id']);
    }

    public function GetData()
    {
        $query = $this->db->query("SELECT * FROM `oc_callback` ORDER BY `date` DESC");
        return($query->rows);      
    }

    public function initTable()
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS `oc_callback` (
            `id` int(11) NOT NULL primary key AUTO_INCREMENT ,
            `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
            `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
            `mail` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
            `vin` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
            `product_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
            `quest` text COLLATE utf8mb4_unicode_ci NOT NULL,
            `notes` text COLLATE utf8mb4_unicode_ci NOT NULL,
            `date` datetime NOT NULL,
            `status` int(11) NOT NULL DEFAULT 1
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    public function dropTable()
    {
        $this->db->query("DROP TABLE IF EXISTS `oc_callback`");
    }
}
?>