<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Milon\Barcode\Facades\DNS1DFacade as DNS1D;
use Milon\Barcode\Facades\DNS2DFacade as DNS2D;

class BarcodeController extends Controller
{
    public function qr($code)
    {;
        return response()->json([
            'status' => true,
            'data' => 'data:image/png;base64,' . DNS2D::getBarcodePNG($code, 'QRCODE', 10, 10),
        ], 200);
    }

    public function code128($code)
    {
        return response()->json([
            'status' => true,
            'data' => 'data:image/png;base64,' . DNS1D::getBarcodePNG($code, 'C128', 4, 60),
        ], 200);
    }

    public function code128a($code)
    {
        return response()->json([
            'status' => true,
            'data' => 'data:image/png;base64,' . DNS1D::getBarcodePNG($code, 'C128A', 4, 60),
        ], 200);
    }

    public function code128b($code)
    {
        return response()->json([
            'status' => true,
            'data' => 'data:image/png;base64,' . DNS1D::getBarcodePNG($code, 'C128B', 4, 60),
        ], 200);
    }

    public function code128c($code)
    {
        return response()->json([
            'status' => true,
            'data' => 'data:image/png;base64,' . DNS1D::getBarcodePNG($code, 'C128C', 4, 60),
        ], 200);
    }
}
