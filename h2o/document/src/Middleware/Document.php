<?php

namespace H2o\Document\Src\Middleware;

use App\Models\H2oDocument;
use App\Models\H2oDocumentEntries;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Document
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     * getallheaders()
     * $_SERVER
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->header('document-save')) {
            $document_description = json_decode(base64_decode($request->header('document-description')));
            $group_key = 'other';
            if (isset($document_description->group)) {
                $group_key = $document_description->group ?? 'other';
            }
            $group = H2oDocumentEntries::where('key', $group_key)->first();
            if (!$group) {
                $group = H2oDocumentEntries::newEntries([
                    'name' => $document_description->name,
                    'key' => $group_key
                ]);
            }
            $url = $request->url();
            $header = $request->header();
            if (isset($header['document-save'])) {
                unset($header['document-save']);
            }
            if (isset($header['document-description'])) {
                unset($header['document-description']);
            }
            $post_data = $request->all();
            $method = $request->getMethod();
            H2oDocument::newDocument(
                [
                    'name' => $document_description->name ?? $url,
                    'method' => $method,
                    'url' => $url,
                    'group_id' => $group->id,
                    'notes' => $document_description->notes,
                    'header' => json_encode($header),
                    'post_data' => json_encode($post_data),
                    'description_response' => json_encode($document_description->response ?? []),
                    'description_post_data' => json_encode($document_description->post_data ?? []),
                ]
            );

        }
        return $next($request);
    }
}
