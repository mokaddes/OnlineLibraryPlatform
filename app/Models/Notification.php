<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed $title
 * @property mixed|null $admin_id
 * @property mixed $action_route
 * @property mixed|null $type
 * @property mixed|string $notify_for
 * @property mixed $user_id
 * @method static findOrFail($id)
 */
class Notification extends Model
{
    use HasFactory;
}
