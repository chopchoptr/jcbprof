<?php
class ModelExtensionModuleTest extends Model 
{
    public function LoadSettings() 
    {
      return $this->config->get('module_test_status');
    }
}
?>