<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccessRequest;
use App\Models\User;
use App\Models\Video;
use App\Models\VideoAccess;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'customerCount' => User::customers()->count(),
            'videoCount' => Video::count(),
            'pendingCount' => AccessRequest::pending()->count(),
            'activeAccessCount' => VideoAccess::active()->count(),
        ]);
    }
}
