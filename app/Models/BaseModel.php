<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ManagerGroupModel
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property int $status
 * @property string $rules
 * @method static \Illuminate\Database\Eloquent\Builder|SysRoleModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SysRoleModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SysRoleModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|SysRoleModel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SysRoleModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SysRoleModel whereRules($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SysRoleModel whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SysRoleModel whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SysMenuModel where($column, $operator = null, $value = null, $boolean = 'and')
 * @mixin \Eloquent
 */
class BaseModel extends Model
{
    protected $guarded = [];
}
