<?php

namespace App\Mail;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Task $task) {}

    public function build()
    {
        return $this->subject('Новая задача: ' . $this->task->title)
            ->view('emails.task_created', [
                'task' => $this->task,
                'url'  => $this->task->attachmentUrl(),
            ]);
    }
}
