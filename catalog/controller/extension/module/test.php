<?php
class ControllerExtensionModuleTest extends Controller
{
    public function index()
    {
        $this->load->model('extension/module/test');
        $data = array();
        $data['module_test_status'] = $this->model_extension_module_test->LoadSettings();
        $data += $this->load->language('extension/module/test');
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );
        $data['breadcrumbs'][] = array(
            'text' => $data['heading_title'],
            'href' => $this->url->link('extension/module/test')
        );

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['header'] = $this->load->controller('common/header');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/test', $data));
    }
}
?>