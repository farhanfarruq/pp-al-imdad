<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Report extends Model {
protected $fillable=['tanggal','bidang_id','pengurus_nama','bpk_tab','user_id'];
protected $casts=['tanggal'=>'date'];
public function bidang(){ return $this->belongsTo(Bidang::class); }
public function tasks(){ return $this->hasMany(ReportTask::class); }
public function uploads(){ return $this->hasMany(Upload::class); }
public function user(){ return $this->belongsTo(User::class); }
}