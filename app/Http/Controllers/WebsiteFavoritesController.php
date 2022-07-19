<?php

namespace App\Http\Controllers;

use App\Models\WebsiteFavorites;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class WebsiteFavoritesController extends Controller
{
  public function index(Request $request)
  {
    $page = "Favorite Websites";
    $favorites = $request->user()->favorites()->orderByDesc('id')->paginate(15);

    return view('user.favorites', compact('page', 'favorites'));
  }

  public function store(Request $request, $website_id)
  {
    if ($website_id) {
      $favorited = $this->check_favorite($request, $website_id);
      if (!$favorited) {
        WebsiteFavorites::create([
          'user_id' => $request->user()->id,
          'website_id' => $website_id
        ]);

        return response()->json([
          'status' => 'success',
          'message' => 'added'
        ]);
      } else {
        $favorited->delete();
        return response()->json([
          'status' => 'success',
          'message' => 'removed'
        ]);
      }
    }
  }

  public function check_favorite(Request $request, $website_id)
  {
    return WebsiteFavorites::where('user_id', $request->user()->id)->where('website_id', $website_id)->get()->first();
  }

  public function destroy($id)
  {
    $favorite = WebsiteFavorites::findOrFail($id);
    $response = Gate::inspect("delete", $favorite);
    if ($response->allowed()) {
      $favorite->delete();
      return back();
    } else {
      return back()->with("status", ["warning", $response->message()]);
    }
  }
}
