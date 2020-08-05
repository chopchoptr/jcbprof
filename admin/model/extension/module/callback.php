<?php
class ModelExtensionModuleCallback extends Model
{
    public function SaveSettings()
    {
        $this->load->model('setting/setting');
        $this->model_setting_setting->editSetting('module_callback', $this->request->post);
    }

    public function LoadSettings()
    {
        return $this->config->get('module_callback_status');
    }
}
?>