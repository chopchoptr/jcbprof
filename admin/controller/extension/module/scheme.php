<?php
class ControllerExtensionModuleScheme extends Controller 
{
    public function install()
    {
        $this->load->model('extension/module/scheme');
        $this->model_extension_module_scheme->create_db_table();
        $post['module_scheme_status'] = 1;
        $this->load->model('setting/setting');
        $this->model_setting_setting->editSetting('module_scheme', $post);
        $this->response->redirect( $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
    }

    public function uninstall()
    {
        $this->load->model('extension/module/scheme');
        $this->model_extension_module_scheme->drop_db_table();
        $post['module_scheme_status'] = 0;
        $this->load->model('setting/setting');
        $this->model_setting_setting->editSetting('module_scheme', $post);
        $this->response->redirect( $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
    }

    public function index()
    {
        //prepare module data
        $this->load->model('extension/module/scheme');
        $data = array();
        $data['options_link'] = $this->url->link('extension/module/scheme',  'user_token=' . $this->session->data['user_token'] . '&type=module');
        $data['categories_link'] = $this->url->link('extension/module/scheme',  'user_token=' . $this->session->data['user_token'] . '&modpage=categories');
        $data['schemes_link'] = $this->url->link('extension/module/scheme',  'user_token=' . $this->session->data['user_token'] . '&modpage=schemes');
        $data += $this->load->language("extension/module/scheme");
        $data += $this->GetBreadCrumbs();
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $data['action'] = $this->url->link('extension/module/scheme', 'user_token='.$this->session->data['user_token'], true);
        // change options page
        if (isset($_GET['modpage']))
        {
            //categories page
            if ($_GET['modpage'] == "categories")
            {
                if ($this->request->server["REQUEST_METHOD"]=='POST')
                {
                    if ($this->request->post['action'] == "add")
                        $this->model_extension_module_scheme->add_category();    
                    if ($this->request->post['action'] == "delete")
                        $this->model_extension_module_scheme->delete_category();  
                    if ($this->request->post['action'] == "change")
                        $this->model_extension_module_scheme->change_category();  
                    $this->response->redirect( $this->url->link('extension/module/scheme', 'user_token=' . $this->session->data['user_token'] . '&modpage=categories', true));
                }
                $data['rows'] = $this->model_extension_module_scheme->get_category();
                $this->response->setOutput($this->load->view('extension/module/scheme/categories', $data));
            }
            //schemes page
            if ($_GET['modpage'] == "schemes")
            {
                if ($this->request->server["REQUEST_METHOD"]=='POST')
                {
                    if ($this->request->post['action'] == "add")
                        $this->model_extension_module_scheme->add_scheme();    
                    if ($this->request->post['action'] == "delete")
                        $this->model_extension_module_scheme->delete_scheme();  
                    if ($this->request->post['action'] == "change")
                        $this->model_extension_module_scheme->change_scheme();  
                    $this->response->redirect( $this->url->link('extension/module/scheme', 'user_token=' . $this->session->data['user_token'] . '&modpage=schemes', true));
                }

                $data['cats'] = $this->model_extension_module_scheme->get_category();
                $data['rows'] = $this->model_extension_module_scheme->get_scheme();
                $data['point_link'] = $this->url->link('extension/module/scheme',  'user_token=' . $this->session->data['user_token'] . '&modpage=scheme');
                $this->response->setOutput($this->load->view('extension/module/scheme/schemes', $data));
            }
            //add points to scheme page
            if ($_GET['modpage'] == "scheme")
            {
                if ($this->request->server["REQUEST_METHOD"]=='POST')
                {
                    $scheme_id = $_GET['scheme_id'];
                    if ($scheme_id > 0 && $this->request->post['action'] == "save")
                        $this->model_extension_module_scheme->update_points($scheme_id);
                    $this->response->redirect( $this->url->link('extension/module/scheme', 'user_token=' . $this->session->data['user_token'] . '&modpage=scheme&scheme_id='. $scheme_id, true));
                }
                if (isset($_GET['scheme_id']) && $_GET['scheme_id'] > 0)
                {
                    $scheme = $this->model_extension_module_scheme->get_scheme_by_id($_GET['scheme_id']);
                    $data['scheme_image'] = "/image/scheme/". $scheme['image'];
                    $data['points'] = $this->model_extension_module_scheme->get_points($_GET['scheme_id']);
                }
                $this->response->setOutput($this->load->view('extension/module/scheme/scheme', $data));
            }
            //add filters to point page
            if ($_GET['modpage'] == "point")
            {
                //update data on server
                if ($this->request->server["REQUEST_METHOD"]=='POST')
                {
                    $point_id = $_GET['point_id'];
                    if ($point_id > 0 && $this->request->post['action'] == "update")
                        $this->model_extension_module_scheme->update_point($point_id);
                    $this->response->redirect( $this->url->link('extension/module/scheme', 'user_token=' . $this->session->data['user_token'] . '&modpage=point&point_id='. $point_id, true));
                }
                // render form
                if (isset($_GET['point_id']) && $_GET['point_id'] > 0)
                {
                    $data['point'] = $this->model_extension_module_scheme->get_point($_GET['point_id']);
                    $data['point_flt'] = explode(",",$data['point']['filter']);
                    $data['filtres'] = $this->model_extension_module_scheme->get_filters();
                }
                $this->response->setOutput($this->load->view('extension/module/scheme/point', $data));
            }

        }
        // module options page
        else
        {
            if ($this->request->server["REQUEST_METHOD"]=='POST')
            {
                $this->model_extension_module_scheme->SaveSettings();
                $this->session->data['success'] = "Настройки сохранены";
                $this->response->redirect( $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
            }
            $data["module_scheme_status"] = $this->model_extension_module_scheme->LoadSettings();
            $data['cancel'] = $this->url->link('marketplace/extension','user_token='.$this->session->data['user_token'].'&type=module', true);
            $this->response->setOutput($this->load->view('extension/module/scheme/options', $data));
        }
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
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/scheme', 'user_token='. $this->session->data['user_token'], true)
        );
        return ($data);
    }
}
?>