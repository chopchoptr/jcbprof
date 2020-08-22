<?php
class ControllerExtensionModuleManudesc extends Controller
{
    public function install()
    {
        $this->load->model('extension/module/manudesc');
        if ($this->model_extension_module_manudesc->col_exist())
            $this->model_extension_module_manudesc->update_db_table();
        $post['module_manudesc_status'] = 1;
        $this->load->model('setting/setting');
        $this->model_setting_setting->editSetting('module_manudesc', $post);
        $this->response->redirect( $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
    }

    public function uninstal()
    {
        $this->load->model('extension/module/manudesc');
        $post['module_manudesc_status'] = 0;
        $this->load->model('setting/setting');
        $this->model_setting_setting->editSetting('module_manudesc', $post);
        $this->response->redirect( $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));

    }

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
            'href' => $this->url->link('extension/module/manudesc', 'user_token='. $this->session->data['user_token'], true)
        );
        return ($data);
    }
    
    
    
    public function index()
    {
        //load model
        $this->load->model('extension/module/manudesc');
        //catch buttons in admin panel
        if ($this->request->server["REQUEST_METHOD"]=='POST')
        {
            //save data
            $this->model_extension_module_manudesc->SaveSettings();
            //alert & redirect
            $this->session->data['success'] = "Настройки сохранены";
            $this->response->redirect( $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
        }
        //build data
        $data = array();
        //get module status
        $data["module_manudesc_status"] = $this->model_extension_module_manudesc->LoadSettings();
        echo $data["module_manudesc_status"];
        //get lang vars
        $data += $this->load->language("extension/module/manudesc");
        $data += $this->GetBreadCrumbs();
        //connect page elements
        $data['action'] = $this->url->link('extension/module/manudesc', 'user_token='.$this->session->data['user_token'], true);
        $data['cancel'] = $this->url->link('marketplace/extension','user_token='.$this->session->data['user_token'].'&type=module', true);
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        //output data in view
        $this->response->setOutput($this->load->view('extension/module/manudesc', $data));
    }
}
?>