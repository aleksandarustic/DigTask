<?php

namespace App\Utill;

use Illuminate\Database\Eloquent\Model;

/**      
 * Interface for country UtillInterface    
 */
interface CountryUtillInterface
{

    public function getYouTubeData(Model $model);

    public function getWikiData(Model $model);
}
