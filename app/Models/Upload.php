<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Upload extends Model {
protected $fillable=['report_id','path','mime','size'];
public function report(){ return $this->belongsTo(Report::class); }
}