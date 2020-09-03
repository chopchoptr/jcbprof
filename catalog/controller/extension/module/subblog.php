<?php
class ControllerExtensionModuleSubblog extends Controller
{
    public function index()
    {
        $this->load->model('extension/module/subblog');
        $data = array();
        $data += $this->load->language('extension/module/subblog');
        $data['posts'] = $this->model_extension_module_subblog->getPosts(4);
        return $this->load->view("extension/module/subblog", $data);
    }
}
?>