<?php
class ControllerExtensionModuleManudesc extends Controller
{
    public function index()
    {
        $this->load->model('extension/module/manudesc');
        $data = array();
        $data['manufacturers'] = $this->model_extension_module_manudesc->get_manufacturers();
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['header'] = $this->load->controller('common/header');
        $data['footer'] = $this->load->controller('common/footer');
        $this->response->setOutput($this->load->view('extension/module/manudesc', $data));
    }
}
?>