<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use HasFactory;
    protected $appends = ['url'];
    // media_trigger = 1  => seller logo
    // media_trigger = 2 => buyer picture
    // media_trigger = 3 => category picture
    // media_trigger = 4 => product picture
    // media_trigger = 5 => advertisement picture
    // media_trigger = 6 => wallet picture
    protected $fillable = ['name','old_name','type','mediable_type','mediable_id','media_trigger','is_cover'];
    public function getUrlAttribute(){
        return $this->name ? 'public/storage/'.$this->name : '';
    }
}
