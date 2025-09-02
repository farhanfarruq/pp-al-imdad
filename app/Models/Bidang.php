<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Bidang extends Model {
protected $fillable=['slug','name','icon','color'];
public function penguruses(){ return $this->hasMany(Pengurus::class); }
public function jobdesks(){ return $this->hasMany(Jobdesk::class); }
public function reports(){ return $this->hasMany(Report::class); }
}