document.querySelector(".form__select").addEventListener('change', function (e) {
    let ftpInputs = document.querySelectorAll('form .form__ftp input');

    if (e.target.value === 'ftp') {

        for (let input of ftpInputs) {
            input.removeAttribute('disabled');
        }

        document.querySelector("form .form__ftp").style.display = 'block';

    } else {

        for (let input of ftpInputs) {
            input.setAttribute('disabled', 'true');
        }

        document.querySelector("form .form__ftp").style.display = 'none';

    }
});