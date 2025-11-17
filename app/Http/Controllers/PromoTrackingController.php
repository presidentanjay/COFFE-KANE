<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssociationRule;

class PromoTrackingController extends Controller
{
    // 🔁 Tambah jumlah dilihat
    public function trackView($id)
    {
        $rule = AssociationRule::findOrFail($id);
        $rule->increment('view_count'); // 🔄 FIXED
        return response()->json(['success' => true, 'message' => 'View tracked']);
    }

    // 🛒 Tambah jumlah dipesan
    public function trackOrder($id)
    {
        $rule = AssociationRule::findOrFail($id);
        $rule->increment('order_count'); // 🔄 FIXED
        return response()->json(['success' => true, 'message' => 'Order tracked']);
    }

    // ❤️ Tambah jumlah disukai
    public function trackLike($id)
    {
        $rule = AssociationRule::findOrFail($id);
        $rule->increment('like_count'); // 🔄 FIXED
        return response()->json(['success' => true, 'message' => 'Like tracked']);
    }
}
