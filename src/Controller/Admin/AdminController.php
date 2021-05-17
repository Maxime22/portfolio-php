<?php 

namespace Controller\Admin;

use Controller\Controller;

class AdminController extends Controller{

    public function index(){
        return $this->render("admin/index.html.twig",[]);
    }

}