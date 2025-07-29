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
    public function index(): string
    {
        $this->view->setData([
            "page" => "dashboard",
        ]);
        return $this->view->render("pages/panel/admin/index");
    }
    public function produk(): string
    {
        $this->view->setData([
            "page" => "produk",
            "kategori" => KategoriModel::all(),

        ]);
        return $this->view->render("pages/panel/admin/produk");
    }
    public function kategori(): string
    {
        $this->view->setData([
            "page" => "kategori",
        ]);
        return $this->view->render("pages/panel/admin/kategori");
    }

    public function user(): string
    {
        $this->view->setData([
            "page" => "user",
        ]);
        return $this->view->render("pages/panel/admin/user");
    }

    public function laporan(): string
    {
        $this->view->setData([
            "page" => "laporan",
        ]);
        return $this->view->render("pages/panel/admin/laporan");
    }
}
