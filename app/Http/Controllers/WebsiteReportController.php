<?php

namespace App\Http\Controllers;

use App\Models\Website;
use Illuminate\Http\Request;
use App\Models\WebsiteReport;

class WebsiteReportController extends Controller
{
  public function index($id)
  {
    $website = Website::findOrFail($id);
    $page = 'Report Website';
    return view('report_website', compact('page', 'website'));
  }

  public function store(Request $request, $id)
  {
    WebsiteReport::create([
      'website_id' => $id,
      'user_id' => $request->user()->id,
      'report_reason' => $request->report_reason ? $request->report_reason : null,
    ]);

    return back()->with('status', ['success', 'Website has been reported successfully.']);
  }
}
