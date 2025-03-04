<?php
namespace App\Controllers;

class FoodController
{
    public function index()
    {
        require __DIR__ . '/../views/food/index.php';
    }
}