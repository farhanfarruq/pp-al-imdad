<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Jobdesk extends Model {
protected $fillable=['bidang_id','label','bpk_tab','is_active'];
public function bidang(){ return $this->belongsTo(Bidang::class); }
}