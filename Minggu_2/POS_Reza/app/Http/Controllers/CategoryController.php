<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function foodBeverage(){
        return view('blog.category.food-beverage');
    }
    public function beautyHealth(){
        return view('blog.category.beauty-health');
    }
    public function homeCare(){
        return view('blog.category.home-care');
    }
    public function babyKid(){
        return view('blog.category.baby-kid');
    }
}