<?php

namespace App\Models;

use App\Enums\TaskTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Task extends Model
{
    use SoftDeletes, HasFactory;
    use LogsActivity;

    public $inPermission = true;

    protected $fillable = [
        'name' , 'user_id', 'department_id' , 'status','assigner_id', 'due_date', 'details', 'share_with', 'form_request_id', 'stage_id'
    ];

    public static $stages =  [
        [
            'id' => 1,
            'name' => 'بحاجة الي المراجعة',
            'key' => 'new',
            'tasks' => []
        ],
        [
            'id' => 2,
            'name' => 'جاري العمل عليها',
            'key' => 'in_progress',
            'tasks' => []
        ],
        [
            'id' => 3,
            'name' => 'متأخر',
            'key' => 'not_finished',
            'tasks' => []
        ],
        [
            'id' => 4,
            'name' => 'مكتمل',
            'key' => 'finished',
            'tasks' => []
        ],
        [
            'id' => 5,
            'name' => 'الارشيف',
            'key' => 'archieve',
            'tasks' => []
        ],
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(self::getFillable());
    }


    public function file()
    {
        return $this->morphOne(File::class, 'fileable');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigner_id');
    }


}
