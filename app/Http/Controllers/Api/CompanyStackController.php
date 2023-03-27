<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyStackController extends Controller
{

    public function index()
    {
        $company = new Company();
        $companies  = $company::paginate(1);

        if (count($companies) > 0) {
            CompanyResource::collection($companies); // 
            return response()->json([ $companies ], 200);
        } else {

            return response()->json(['message' => 'No  Companies found '], 404);
        }
    }
    public function show($id)
    {

        $company = new Company();

        $companyStack = $company->first($id);

        return response()->json(['company_details' =>  $companyStack]);
    }
}