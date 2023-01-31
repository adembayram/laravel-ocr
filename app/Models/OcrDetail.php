<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OcrDetail extends Model
{
    use HasFactory;
    protected $fillable = ['ocr_id','content'];
    protected  $primaryKey = 'ocr_id';
}
