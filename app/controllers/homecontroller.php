<?php
namespace App\Controllers;

use App\Services\ProductService;

class HomeController
{
    public function index()
    {
        require __DIR__ . '/../views/home/index.php';
    }
}