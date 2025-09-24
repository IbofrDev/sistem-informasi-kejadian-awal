<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // <-- PASTIKAN BARIS INI ADA
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    // --- PERBAIKAN DI SINI ---
    // Trait ini memberikan kemampuan '$this->authorize()' kepada semua controller
    // yang mewarisinya (termasuk AdminController).
    use AuthorizesRequests, ValidatesRequests;
}
