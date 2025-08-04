<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;
use App\Models\KategoriModel;
use App\Models\PenggunaModel;
use App\Models\WisataModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Manage extends BaseController
{
    protected PenggunaModel $user;
    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger,
    ) {
        parent::initController($request, $response, $logger);
        $this->user = PenggunaModel::find(auth()->user()->id);
        $this->view->setData([
            "user" => $this->user,
        ]);
    }
    public function index()
    {
        $this->view->setData([
            "page" => "dashboard",
        ]);

        // if (auth()->user()->inGroup('user')) {
        //     return redirect()->to(route_to('panel/transaksi-barang'));
        // }
        return $this->view->render("pages/panel/admin/index");
    }
    public function jastip(): string
    {
        $this->view->setData([
            "page" => "jastip",


        ]);
        return $this->view->render("pages/panel/admin/jastip");
    }

    public function user(): string
    {
        $this->view->setData([
            "page" => "user",
        ]);
        return $this->view->render("pages/panel/admin/user");
    }
}
