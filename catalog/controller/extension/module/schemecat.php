<?php
class ControllerExtensionModuleSchemecat extends Controller
{
    public function index()
    {
        $this->load->model('extension/module/schemecat');
        $data = array();
        $data['cats'] = $this->model_extension_module_schemecat->get_cats();
        $data += $this->load->language('extension/module/schemecat');
        return $this->load->view('extension/module/schemecat', $data);
    }
}
?>