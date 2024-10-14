<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Awarded
 * 
 * @property int $awd_id
 * @property int $awd_doc
 * @property int $awd_was_awd
 * @property Carbon|null $awarded_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Form $form
 *
 * @package App\Models
 */
class Awarded extends Model
{
	protected $table = 'awarded';
	protected $primaryKey = 'awd_id';

	protected $casts = [
		'awd_doc' => 'int',
		'awd_was_awd' => 'int',
		'awarded_at' => 'datetime'
	];

	protected $fillable = [
		'awd_doc',
		'awd_was_awd',
		'awarded_at'
	];

	public function form()
	{
		return $this->belongsTo(Form::class, 'awd_doc');
	}
}
