<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BusinessPermitDocumentController extends Controller
{
    public function owner(string $document)
    {
        abort_unless(in_array($document, ['current', 'pending'], true), 404);

        $shop = Shop::where('user_id', Auth::id())->firstOrFail();
        $path = $document === 'current'
            ? $shop->business_permit_path
            : $shop->pending_business_permit_path;

        abort_unless($path && Storage::disk('local')->exists($path), 404);

        return Storage::disk('local')->response($path);
    }

    public function admin(int $shopId, string $document)
    {
        abort_unless(in_array($document, ['current', 'pending'], true), 404);

        $shop = Shop::findOrFail($shopId);
        $path = $document === 'current'
            ? $shop->business_permit_path
            : $shop->pending_business_permit_path;

        abort_unless($path && Storage::disk('local')->exists($path), 404);

        return Storage::disk('local')->response($path);
    }
}
