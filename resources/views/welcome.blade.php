<!DOCTYPE html>
<html lang="ru">
<head>
    <title></title>
    <meta charset="utf-8">
</head>
<body>
<?php
    $message = \Illuminate\Support\Facades\Cache::get('message');
    if(!empty($message)) {
        echo '<p> your message: </p>';
        echo $message . '<br /><br />';
    }
?>
<form method='post' action="/put/cache">
    @csrf
    Write a message:<br /><br />
    <textarea name='message'></textarea>
    <br />
    <input type='submit' value='Send'>
</form>
</body>
</html>
