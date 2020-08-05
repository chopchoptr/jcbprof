<?php
class ControllerExtensionModuleCallback extends Controller
{
    public function index()
    {
        $this->load->model('extension/module/callback');
        if ($this->request->server["REQUEST_METHOD"]=='POST')
        {
            $this->model_extension_module_callback->RecordData();
        }
        $data = array();
        $data['module_callback_status'] = $this->model_extension_module_callback->LoadSettings();
        if ($_GET && $_GET['route'] == "product/product")
            $data['product_id'] = $_GET['product_id'];
        $data += $this->load->language('extension/module/callback');
        return $this->load->view('extension/module/callback', $data);
    }
}
?>