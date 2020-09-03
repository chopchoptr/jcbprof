<?php
class ModelExtensionModuleSubblog extends Model
{
    public function SaveSettings()
    {
        $this->load->model('setting/setting');
        $this->model_setting_setting->editSetting('module_subblog', $this->request->post);
    }

    public function LoadSettings()
    {
        return $this->config->get('module_subblog_status');
    }
}
?>