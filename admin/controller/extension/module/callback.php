<?php
class ControllerExtensionModuleCallback extends Controller 
{
    public function index ()
    {
        $this->load->model('extension/module/callback');
        if ($this->request->server["REQUEST_METHOD"]=='POST')
        {
            $this->model_extension_module_callback->SaveSettings();
            $this->session->data['success'] = "Настройки сохранены";
            $this->response->redirect( $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
        }
        $data = array();
        $data["module_callback_status"] = $this->model_extension_module_callback->LoadSettings();
        $data += $this->load->language("extension/module/callback");
        $data += $this->GetBreadCrumbs();
        $data['action'] = $this->url->link('extension/module/callback', 'user_token='.$this->session->data['user_token'], true);
        $data['cancel'] = $this->url->link('marketplace/extension','user_token='.$this->session->data['user_token'].'&type=module', true);
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        //output data in view
        $this->response->setOutput($this->load->view('extension/module/callback', $data));
    }

    private function GetBreadCrumbs ()
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
            'href' => $this->url->link('extension/module/callback', 'user_token='. $this->session->data['user_token'], true)
        );
        return ($data);
    }
}
?>