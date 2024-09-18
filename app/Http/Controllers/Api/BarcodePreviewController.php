<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Milon\Barcode\Facades\DNS1DFacade as DNS1D;
use Milon\Barcode\Facades\DNS2DFacade as DNS2D;

class BarcodePreviewController extends Controller
{
    public function qr($code)
    {;
        return DNS2D::getBarcodeHTML($code, 'QRCODE', 10, 10);
    }

    public function code128($code)
    {
        return DNS1D::getBarcodeHTML($code, 'C128', 4, 60);
    }

    public function code128a($code)
    {
        return DNS1D::getBarcodeHTML($code, 'C128A', 4, 60);
    }

    public function code128b($code)
    {
        return DNS1D::getBarcodeHTML($code, 'C128B', 4, 60);
    }

    public function code128c($code)
    {
        return DNS1D::getBarcodeHTML($code, 'C128C', 4, 60);
    }
}
