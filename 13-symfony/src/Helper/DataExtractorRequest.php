<?php

namespace App\Helper;

use Symfony\Component\HttpFoundation\Request;

class DataExtractorRequest
{
    private function getRequestData(Request $request)
    {
        $sort   = $request->query->get('sort');
        $filter = $request->query->all();
        unset($filter['sort']);

        return [$sort, $filter];
    }

    public function getSortParams(Request $request)
    {
        [$sort, ] = $this->getRequestData($request);

        return $sort;
    }
    
    public function getFilterParams(Request $request)
    {
        [, $filter] = $this->getRequestData($request);

        return $filter;
    }
}
