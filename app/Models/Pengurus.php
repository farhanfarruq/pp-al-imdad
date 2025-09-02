<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Pengurus extends Model {
protected $fillable=['nama','bidang_id','kelas'];
public function bidang(){ return $this->belongsTo(Bidang::class); }
}