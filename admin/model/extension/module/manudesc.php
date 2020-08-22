<?php
class ModelExtensionModuleManudesc extends Model
{
    public function SaveSettings()
    {
        $this->load->model('setting/setting');
        $this->model_setting_setting->editSetting('module_manudesc', $this->request->post);
    }

    public function LoadSettings()
    {
        return $this->config->get('module_manudesc_status');
    }

    public function update_db_table()
    {
       $this->db->query("ALTER TABLE `oc_manufacturer` ADD `desc` text COLLATE utf8mb4_unicode_ci");
       $this->db->query("ALTER TABLE `oc_manufacturer` ADD `link` varchar(255) COLLATE utf8mb4_unicode_ci");
    }

    public function col_exist()
    {
       $res = $this->db->query("SELECT * FROM `oc_manufacturer`");
       return (isset($res->rows[0]['desc']));
    }

}
?>