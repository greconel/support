<?php

namespace App\Http\Controllers\Ampp\CompanyPolicies;

use App\Http\Controllers\Controller;

class IndexCompanyPolicyController extends Controller
{
    public function __invoke()
    {
        return view('ampp.companyPolicies.index');
    }
}
