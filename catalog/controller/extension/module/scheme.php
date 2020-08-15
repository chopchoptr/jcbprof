<?php
class ControllerExtensionModuleScheme extends Controller
{
    public function index()
    {
        $this->load->model('extension/module/scheme');
        $data = array();
        $data += $this->load->language('extension/module/scheme');
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );
        $data['breadcrumbs'][] = array(
            'text' => $data['heading_title'],
            'href' => $this->url->link('extension/module/scheme')
        );
        $data['cats'] = $this->model_extension_module_scheme->get_categories();
        // load filtered schemes
        if (isset($_GET['engine']))
        {
           $data['engine'] = $_GET['engine'];
           $data['sc_cat_id'] = $_GET['sc_cat_id'];
           $data['schemes'] = $this->model_extension_module_scheme->get_schemes($_GET['sc_cat_id'], $_GET['engine']);
        
           //load current scheme
            if (isset($_GET['scheme_id']))
            {
                $data['sc_id'] = $_GET['scheme_id'];
                $data['scheme'] = $this->model_extension_module_scheme->get_scheme($_GET['scheme_id']);
                $data['points'] = $this->model_extension_module_scheme->get_points($_GET['scheme_id']);
                $data['link'] = $this->url->link('extension/module/scheme',"&scheme_id=".$data['sc_id']."&engine = ".$data['engine']."&sc_cat_id=".$data['sc_cat_id']);
            }
            $data['cat_link'] = $this->url->link('extension/module/scheme', "&engine=".$data['engine']."&sc_cat_id=".$data['sc_cat_id']);
        }
        else
        $data['link'] = $this->url->link('extension/module/scheme');
        $data['product_link'] = $this->url->link('product/category', "path=1");
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['header'] = $this->load->controller('common/header');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/scheme', $data));
    }
}
?>