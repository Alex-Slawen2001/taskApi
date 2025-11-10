<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
class Task extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    protected $fillable = [
        'project_id',
        'title',
        'description',
        'status',
        'due_date',
        'assignee_id',
    ];
    protected $casts = [
        'due_date' => 'date:Y-m-d',
    ];
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachments')->useDisk('public');
    }
    public function attachmentUrl(): ?string
    {
        return $this->getFirstMediaUrl('attachments') ?: null;
    }
}
