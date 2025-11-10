<!doctype html>
<html lang="ru">
<head><meta charset="utf-8"></head>
<body>
<h2>Создана новая задача</h2>
<p><strong>Проект:</strong> {{ $task->project->name ?? ('#'.$task->project_id) }}</p>
<p><strong>Заголовок:</strong> {{ $task->title }}</p>
<p><strong>Статус:</strong> {{ $task->status }}</p>
@if($task->due_date)
    <p><strong>Срок:</strong> {{ $task->due_date->format('Y-m-d') }}</p>
@endif
@if($url)
    <p><strong>Вложение:</strong> <a href="{{ $url }}">{{ $url }}</a></p>
@endif
</body>
</html>
