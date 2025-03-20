<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YouTube Downloader</title>
</head>

<body>
    <h2>YouTube Video Downloader</h2>
    <form action="/download" method="POST">
        @csrf
        <input type="text" name="url" placeholder="Enter YouTube URL" required>
        <button type="submit">Download</button>
    </form>
</body>

</html>
