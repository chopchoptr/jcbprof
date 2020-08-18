<?php
class ControllerExtensionModuleCallback extends Controller
{
    public function index()
    {
        $this->load->model('extension/module/callback');
        if ($this->request->server["REQUEST_METHOD"]=='POST')
        {
           $num = $this->model_extension_module_callback->RecordData();
            $str = $_SERVER['REQUEST_URI'];
            $href = parse_url($_SERVER['REQUEST_URI'])['path'];
            if (isset(parse_url($_SERVER['REQUEST_URI'])['query']))
                $str.="&ticket=".$num['id'];
            else
            $str = "ticket=".$num['id'];
            $route = isset($_GET['route']) ? $_GET['route'] : "common/home";
            if( $href != "/index.php" && $href != "/")
                $this->response->redirect($href."?".$str);
            else
                $this->response->redirect($str);
        }
        $data = array();
        $route = isset($_GET['route']) ? $_GET['route'] : NULL;
        $data['product_id'] = $_SERVER['REQUEST_URI'];
        $ticket = isset($_GET['ticket']) ? $_GET['ticket'] : "";
            $data['ticket'] = $ticket;
        $data["ticket_url"] =  str_replace(isset($_GET['route']) ? "&ticket=".$ticket : "ticket=".$ticket, "", $_SERVER['REQUEST_URI']);
        $data += $this->load->language('extension/module/callback');
        return $this->load->view('extension/module/callback', $data);
    }
}
?>