<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Framework;
use App\Models\Plang;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class StackService
{


    public function getAllStackInfo(): array
    {
        $allCompanies =  Company::get();
        $allPlangs = Plang::get();
        $allFrameworks = Framework::get();
        return compact('allCompanies', 'allPlangs', 'allFrameworks');
    }

    public function store($request): RedirectResponse
    {
        $request->validate([
            'frameworks' => 'required|array',
            'plangs' => 'required|array',
            'company' => 'required|integer',
        ]);


        switch ($request->stackType) {
            case 'backend':
                return $this->saveBackendStack($request);
                break;
            case 'frontend':
                return $this->saveBackendStack($request);
                break;
                return redirect()->bakc()->with(['errors' => 'compay stack was not specified. confirm you have the right url']);
        }
    }


    private function saveBackendStack($request)
    {
        try {
            $company = Company::findOrFail($request->company);

            DB::transaction(function () use ($company, $request) {
                $company->plangs()->attach($request->plangs, ['is_draft' => 0, 'is_published' => 1]);
                $company->frameworks()->attach($request->frameworks,  ['is_draft' => 0, 'is_published' => 1]);
            });

            return redirect()->back()->with('msg', 'Data was saved successfully');
        } catch (\Exception $e) {

            return redirect()->back()->withErrors(['errors' => $e->getMessage()]);
        }
    }

    private function saveFrontendStack($request)
    {
        try {
            $company = Company::findOrFail($request->company);
            $company->feFrameworks()->attach($request->frameworks,  ['is_draft' => 0, 'is_published' => 1]);
            return redirect()->back()->with('msg', 'Data was saved successfully');
        } catch (\Exception $e) {

            return redirect()->back()->withErrors(['errors' => $e->getMessage()]);
        }
    }
}
