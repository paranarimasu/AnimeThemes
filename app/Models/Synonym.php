<?php

namespace App\Models;

use App\ScoutElastic\SynonymIndexConfigurator;
use App\ScoutElastic\SynonymSearchRule;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use ScoutElastic\Searchable;

class Synonym extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable, Searchable;

    protected $fillable = ['text'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'synonym';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'synonym_id';

    protected $indexConfigurator = SynonymIndexConfigurator::class;

    protected $searchRules = [
        SynonymSearchRule::class
    ];

    protected $mapping = [
        'properties' => [
            'text' => [
                'type' => 'text'
            ]
        ]
    ];

    /**
     * Gets the anime that owns the synonym
     */
    public function anime() {
        return $this->belongsTo('App\Models\Anime', 'anime_id', 'anime_id');
    }
}
