<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\DataSource;
use App\Models\Stack;
use Illuminate\Http\Request;
use App\Services\ScraperService;

class DataControlController extends Controller
{
    public function index()
    {

        $companies = Company::get(['id', 'name']);
        $stacks = Stack::all();
        $dataSources = DataSource::all();

        return view('admin.scraper', compact('companies', 'stacks', 'dataSources'));
    }

    public function initiateDataSourcing(Request  $request)
    {


        // upload cv
        // we list companies based on your profile
        // this companies listed on your profil, you cna send them a cold mail. 
        // organise your job hunting.
        // if you must shoot you shots as in your tech job hunting, make sure you are shooting in the right direction

        // then this where whatcompanystack comes in !


        $request->validate([
            'company' => 'required', // actuallly the company id
            'stack' => 'required',
            'data_source' => 'required',

        ]);



        $scraper = new ScraperService($request->input('company'), $request->input('data_source'), $request->input('stack'));

        // dd($request->all());
        $scraper->dataSource();

        // for prgramming language

        $newResult = Company::with(['plangs' => function ($query) {
            $query->where('status', 1)->with('frameworks', function ($query) {
                $query->whereHas('companies');
            });
        }, 'frameworks' => function ($query) {
            $query->where('status', 1);
        }])->first();
        dd($newResult);
        return view('admin.scrapperResultPreview', compact('newResult'));
    }
}