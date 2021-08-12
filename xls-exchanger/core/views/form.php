<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Загрузка файла</title>
    <link rel="stylesheet" href="/core/assets/css/style.css">
</head>
<body>
    <form class="form" enctype="multipart/form-data" method="post" action="">
        <div class="form__title">JSON -> XLSX</div>
        <div class="form__file">
            <div class="form__field">
                <label for="file_orders">Загрузите файл с заказами в формате json</label>
                <input type="file" name="file_orders" id="file_orders" accept="application/json" required>
            </div>

            <div class="form__field">
                <label for="file_name">Как будет называться новый файл?</label>
                <input type="text" name="file_name" id="file_name" placeholder="Введите название, например, &quot;items&quot;" required>
            </div>

            <div class="form__field">
                <label for="export_type">Как сохранить новый файл?</label>
                <select class="form__select" name="export_type" id="export_type" required>
                    <option class="form__option" value="local">Сохранить файл локально</option>
                    <option class="form__option" value="ftp">Сохранить файл по FTP</option>
                </select>
            </div>
        </div>

        <div class="form__ftp">
            <div class="form__ftp-title">Параметры подключения по FTP</div>
            <div class="form__field">
                <label for="ftp_host">FTP HOST</label>
                <input type="text" name="ftp_host" id="ftp_host" placeholder="Введите FTP HOST" required disabled>
            </div>
            <div class="form__field">
                <label for="ftp_port">FTP PORT</label>
                <input type="number" name="ftp_port" id="ftp_port" placeholder="Введите FTP PORT, по умолчанию - 21" disabled>
            </div>
            <div class="form__field">
                <label for="ftp_login">FTP LOGIN</label>
                <input type="text" name="ftp_login" id="ftp_login" placeholder="Введите FTP LOGIN" required disabled>
            </div>
            <div class="form__field">
                <label for="ftp_password">FTP PASSWORD</label>
                <input type="password" name="ftp_password" id="ftp_password" placeholder="Введите FTP PASSWORD" required disabled>
            </div>
            <div class="form__field">
                <label for="ftp_dir">FTP DIR</label>
                <input type="text" name="ftp_dir" id="ftp_dir" placeholder="Введите FTP DIR, например &quot;www/site.ru&quot;" required disabled>
            </div>
        </div>

        <input class="form__submit" type="submit" value="Выполнить">
    </form>
    <script src="/core/assets/js/script.js"></script>
</body>
</html>