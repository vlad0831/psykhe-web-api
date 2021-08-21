<?php

namespace App\Http\Controllers\Query;

use App\Http\Controllers\ApiController;
use App\Models\Filter\Brand;
use App\Models\Filter\Category;
use App\Models\Filter\Color;
use App\Models\Filter\Mood;
use App\Models\Filter\Occasion;
use App\Models\Filter\Option;
use Exception;

class QueryFilterController extends ApiController
{
    public function index()
    {
        try {
            $filters = [
                'brands'     => iterator_to_array(Brand::List()),
                'categories' => Category::Tree(),
                'colors'     => Color::List(),
                'moods'      => Mood::List(),
                'occasions'  => Occasion::List(),
                'options'    => Option::List(),
            ];

            return $this->responseOk($filters);
        } catch (Exception $controllerException) {
            report($controllerException);
            $errorMessage = config('app.debug') ? $controllerException->getMessage() : 'There has been an error getting your recommendations';

            return $this->responseInternalError($errorMessage);
        }
    }
}
