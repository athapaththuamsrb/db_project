function keyPressFn(e, nxt) {
    if (e.keyCode === 13) {
        e.preventDefault();
        if (nxt == '') {
            document.getElementById("submitBtn").click();
        } else {
            let nextElem = document.getElementById(nxt);
            if (nextElem) {
                nextElem.focus();
            }
        }
    }
}

function showMessage(msg) {
    alert(msg); // TODO: modify this to show in a better way
}

let typeInput = document.getElementById('type');
let unameInput = document.getElementById('username');
let passwdInput = document.getElementById('password');
let cnfpasswdInput = document.getElementById('cnfpassword');
let submitBtn = document.getElementById('submitBtn');

function clear() {
    unameInput.value = '';
    passwdInput.value = '';
    cnfpasswdInput.value = '';
}

submitBtn.onclick = e => {
    e.preventDefault();
    let username = unameInput.value;
    let password = passwdInput.value;
    let cnfpassword = cnfpasswdInput.value;
    if (!/^[\x21-\x7E]{8,15}$/.test(password)) {
        showMessage("Invalid password");
        return;
    }
    if (!/^[a-zA-Z0-9._]{5,12}$/.test(username)) {
        showMessage("Invalid username");
        return;
    }
    if (password !== cnfpassword) {
        showMessage("Passwords does not match");
        return;
    }
    let xhrSender = new XHRSender(document.URL, resp => {
        try {
            let data = JSON.parse(resp);
            if (data.hasOwnProperty('success') && data['success'] === true) {
                clear();
                showMessage('User added successfully');
                //setTimeout(e => { window.location = 'index.php'; }, 1000);
                return;
            }
            if (data.hasOwnProperty('reason') && data['reason'] instanceof String) {
                showMessage(data['reason']);
            } else {
                showMessage('Sorry try again');
            }
        } catch (e) {
            showMessage('Error occured');
        }
    });
    xhrSender.addField('username', username);
    xhrSender.addField('password', password);
    xhrSender.addField('type', typeInput.value);
    xhrSender.send();
};