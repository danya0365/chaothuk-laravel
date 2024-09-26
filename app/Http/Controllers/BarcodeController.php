<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Milon\Barcode\Facades\DNS1DFacade as DNS1D;
use Milon\Barcode\Facades\DNS2DFacade as DNS2D;

class BarcodeController extends Controller
{
    public function qr($code)
    {
        return DNS2D::getBarcodeSVG($code, 'QRCODE', 10, 10);
    }

    public function code128($code)
    {
        return DNS1D::getBarcodeSVG($code, 'C128', 4, 60);
    }
}
