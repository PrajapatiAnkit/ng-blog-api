<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * This function returns all the active categories
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $categories = Category::active()->get();
       return ResponseHelper::successResponse(__('data_returned_successfully'), $categories);
    }
}
