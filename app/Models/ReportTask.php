<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ReportTask extends Model {
protected $fillable=['report_id','jobdesk_id','done','alasan','solusi'];
public function report(){ return $this->belongsTo(Report::class); }
public function jobdesk(){ return $this->belongsTo(Jobdesk::class); }
}