<?php

namespace App\Http\Traits;

use Illuminate\Http\Response;
use App\Http\Requests\BulkDestroyRequest;

trait BulkDestroy
{
    public function bulkDestroy(BulkDestroyRequest $request)
    {
        $this->model::destroy($request->ids);

        return response('', Response::HTTP_NO_CONTENT);
    }
}
