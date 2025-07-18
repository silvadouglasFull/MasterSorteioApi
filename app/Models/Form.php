<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Form
 * 
 * @property int $form_id
 * @property string $form_doc
 * @property int|null $form_number
 * @property string|null $form_email
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Collection|Awarded[] $awardeds
 *
 * @package App\Models
 */
class Form extends Model
{
	protected $table = 'forms';
	protected $primaryKey = 'form_id';

	protected $casts = [
		'form_number' => 'int',
		'form_email' => 'string',
	];

	protected $fillable = [
		'form_doc',
		'form_number',
		'form_email',
	];

	public function awardeds()
	{
		return $this->hasMany(Awarded::class, 'awd_doc');
	}
}
