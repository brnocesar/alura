<?php

namespace App\Helper;

use Symfony\Component\HttpFoundation\Request;

class UrlDataExtractor
{
    private function getRequestData(Request $request)
    {
        
        $filter = $request->query->all();

        $sort   = $request->query->get('sort') ?? [];
        unset($filter['sort']);
        $currentPage = $request->query->get('page') ?? 1;
        unset($filter['page']);
        $perPage = $request->query->get('perPage') ?? 10;
        unset($filter['perPage']);

        return [$sort, $filter, $currentPage, $perPage];
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

    public function getCurrentPage(Request $request)
    {
        [, , $page] = $this->getRequestData($request);

        return $page;
    }

    public function getItensPerPage(Request $request)
    {
        [, , , $perPage] = $this->getRequestData($request);

        return $perPage;
    }
}
