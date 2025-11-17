<?php

namespace App\Http\Controllers;

use App\Models\AssociationRule;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function trackView($id)
    {
        $rule = AssociationRule::findOrFail($id);
        $rule->increment('view_count');
        return response()->json(['message' => 'View tracked']);
    }

    public function trackOrder($id)
    {
        $rule = AssociationRule::findOrFail($id);
        $rule->increment('order_count');
        return response()->json(['message' => 'Order tracked']);
    }

    public function trackLike($id)
    {
        $rule = AssociationRule::findOrFail($id);
        $rule->increment('like_count');
        return response()->json(['message' => 'Like tracked']);
    }
}
