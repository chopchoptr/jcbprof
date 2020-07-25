<?php
class ControllerExtensionModuleTest extends Controller
{
    private function GetBreadCrumbs()
    {
        $data = array();
        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token='. $this->session->data['user_token'], true)
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token='. $this->session->data['user_token']."&type=module", true)
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/test', 'user_token='. $this->session->data['user_token'], true)
        );
        return ($data);
    }
    
    
    
    public function index()
    {
        //load model
        $this->load->model('extension/module/test');
        //catch buttons in admin panel
        if ($this->request->server["REQUEST_METHOD"]=='POST')
        {
            //save data
            $this->model_extension_module_test->SaveSettings();
            //alert & redirect
            $this->session->data['success'] = "Настройки сохранены";
            $this->response->redirect( $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
        }
        //build data
        $data = array();
        //get module status
        $data["module_test_status"] = $this->model_extension_module_test->LoadSettings();
        echo $data["module_test_status"];
        //get lang vars
        $data += $this->load->language("extension/module/test");
        $data += $this->GetBreadCrumbs();
        //connect page elements
        $data['action'] = $this->url->link('extension/module/test', 'user_token='.$this->session->data['user_token'], true);
        $data['cancel'] = $this->url->link('marketplace/extension','user_token='.$this->session->data['user_token'].'&type=module', true);
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        //output data in view
        $this->response->setOutput($this->load->view('extension/module/test', $data));
    }


}
?>