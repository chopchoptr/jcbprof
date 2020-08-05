<?php
class ModelExtensionModuleCallback extends Model 
{
    public function LoadSettings() 
    {
      return $this->config->get('module_callback_status');
    }

    public function RecordData()
    {
      $data = $this->request->post;
      
    }
}
?>