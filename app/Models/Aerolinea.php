<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Model;
 
class Aerolinea extends Model
{
    protected $fillable = [
        'tipo', 'tamanio' 
    ];
}