<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <input id="link" type="text">
    <button id="button">Get link</button>
    <div id="short_url"></div>
    <script>
        function getShortUrl() {
            const link = document.querySelector('#link');
            fetch('/shorten?url=' + link.value).then(response => {
                return response.json();
            }).then(json => {
                const short_url = document.querySelector('#short_url');
                short_url.innerHTML = json.short_url;
            })
        }
        document.querySelector('#button').addEventListener('click', getShortUrl);
    </script>
</body>
</html>
