<?php
class ControllerExtensionModuleScheme extends Controller
{
    private function get_attribute_by_id($arrays, $gr_attr, $attr)
    {
    $data = [];
    foreach ($arrays as $array)
        if ($array['name'] == $gr_attr)
        {
            $data = $array['attribute'];
            break;
        }
    foreach ($data as $elem) {
        if($elem['name'] == $attr)
            return($elem['text']);
    }
    return("");
}

    public function index()
    {
        $this->load->model('extension/module/scheme');
        $this->load->model('catalog/product');
        $this->load->model('tool/image');
        
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
        if (isset($_GET['engine'] && isset($_GET['sc_cat_id'])))
        {
           $data['engine'] = $_GET['engine'];
           $data['sc_cat_id'] = $_GET['sc_cat_id'];
           $data['schemes'] = $this->model_extension_module_scheme->get_schemes($_GET['sc_cat_id'], $_GET['engine']);
           //LOAD PRODUCTS
           $prod_cat = $this->model_extension_module_scheme->get_cat_id($_GET['sc_cat_id']);
           $results = $this->model_catalog_product->getProducts($this->get_filter_data($prod_cat));
           $data['products'] = $this->get_products($results, $prod_cat);
           $data['prod_link'] = "/?route=product/category&path=". $prod_cat;
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

    private function get_filter_data($cat_id)
    {
        $page = isset($this->request->get['page']) ? $this->request->get['page'] : 1;
        $limit = isset($this->request->get['limit']) ? (int)$this->request->get['limit'] : $this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit');
        $filter_data  = array(
            'filter_category_id' => $cat_id,
            'start'              => ($page - 1) * $limit,
            'limit'              => $limit
            );
        return($filter_data);
    }

    private function get_products($results, $cat_id)
    {
        if (empty($results))
            return(NULL);
        $data = array();
        foreach ($results as $result) 
        {
            if ($result['image']) 
            {
                $image = $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
            } 
            else 
            {
                $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
            }
            if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) 
            {
                $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
            } 
            else 
            {
                $price = false;
            }
            if ((float)$result['special']) 
            {
                $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
            } 
            else 
            {
                $special = false;
            }
            if ($this->config->get('config_tax')) 
            {
                $tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
            } 
            else 
            {
                $tax = false;
            }
            if ($this->config->get('config_review_status')) 
            {
                $rating = (int)$result['rating'];
            } 
            else 
            {
                $rating = false;
            }
            $data['products'][] = array(
                'product_id'  => $result['product_id'],
                'sku'			=> $result['sku'],
                'attribute_groups'	 => $this->get_attribute_by_id($this->model_catalog_product->getProductAttributes($result['product_id']), 'Поисковые параметры', 'кросс-номера'),
                'thumb'       => $image,
                'name'        => $result['name'],
                'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
                'price'       => $price,
                'special'     => $special,
                'tax'         => $tax,
                'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
                'rating'      => $result['rating'],
                'href'        => $this->url->link('product/product', 'path=' . $cat_id . '&product_id=' . $result['product_id'])
            );
        }
        return ($data['products']);
    }
}
?>