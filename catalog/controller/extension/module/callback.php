<?php
class ControllerExtensionModuleCallback extends Controller
{
    public function index()
    {
        $this->load->model('extension/module/callback');
        if ($this->request->server["REQUEST_METHOD"]=='POST')
        {
           $num = $this->model_extension_module_callback->RecordData();
            if ($_GET && $_GET['product_id'])
            $str = "product_id=".$_GET['product_id'];
            else
            $str = "";
            if ($num['id'] > 0)
                $str.="&ticket=".$num['id'];
            $route = isset($_GET['route']) ? $_GET['route'] : "";
        $this->response->redirect( $this->url->link($route, $str, true));
        }
        $data = array();
        if (isset($_GET))
        {
            $route = isset($_GET['route']) ? $_GET['route'] : "";
            if ($route == "product/product")
                $data['product_id'] = $_GET['product_id'];
            $ticket = isset($_GET['ticket']) ? $_GET['ticket'] : "";
            if ((int) $ticket > 0)
            {
                $data['ticket'] = $ticket;
                $data['route'] = $route;
                $data['url'] = parse_url($_SERVER['REQUEST_URI'])['path'];
            }
        }
        $data += $this->load->language('extension/module/callback');
        return $this->load->view('extension/module/callback', $data);
    }
}
?>