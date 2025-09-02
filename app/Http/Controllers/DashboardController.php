<?php
namespace App\Http\Controllers;
use Inertia\Inertia; use App\Models\{Bidang, Report}; use Illuminate\Http\Request; use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller {
public function index(){
$user = Auth::user();
$bidang = Bidang::select('id','slug','name','icon','color')->get();
return Inertia::render('Dashboard', [
'user'=> $user->only(['name','email']),
'bidang'=>$bidang,
]);
}


public function rekap(Request $r){
$this->authorize('viewAny', Report::class); // optional policy
$query = Report::with(['bidang','tasks','uploads']);
if($r->filled('start')) $query->whereDate('tanggal','>=',$r->start);
if($r->filled('end')) $query->whereDate('tanggal','<=',$r->end);
if($r->filled('bidang')) $query->whereHas('bidang', fn($q)=>$q->where('slug',$r->bidang));
if($r->user()->hasRole('admin_bidang')){
// filter ke bidang tertentu jika perlu (mis: simpan di profile user)
}
$data = $query->latest()->paginate(20);
return Inertia::render('Admin/Rekap', ['data'=>$data]);
}
}