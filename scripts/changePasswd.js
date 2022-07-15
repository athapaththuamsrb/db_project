let curpassInput = document.getElementById('curpass');
let newpassInput = document.getElementById('newpass');
let cnfpasswdInput = document.getElementById('cnfpassword');

function keyPressFn(e, pattern, nxt) {
    if (e.keyCode === 13) {
        e.preventDefault();
        let value = e.target.value.trim();
        if (!pattern.test(value)) {
            return;
        }
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

function clear() {
    curpassInput.value = '';
    newpassInput.value = '';
    cnfpasswdInput.value = '';
}

curpassInput.onkeydown = event => { keyPressFn(event, password_pattern, 'newpass'); };
newpassInput.onkeydown = event => { keyPressFn(event, password_pattern, 'cnfpassword'); };
cnfpasswdInput.onkeydown = event => { keyPressFn(event, password_pattern, ''); };

submitBtn.onclick = e => {
    e.preventDefault();
    let curpass = curpassInput.value.trim();
    let newpass = newpassInput.value.trim();
    let cnfpassword = cnfpasswdInput.value.trim();
    if (!password_pattern.test(curpass)) {
        setModal(false, "Invalid current password");
        return;
    }
    if (!password_pattern.test(newpass)) {
        setModal(false, "Invalid new password");
        return;
    }
    if (newpass !== cnfpassword) {
        setModal(false, "Passwords does not match");
        return;
    }
    let xhrSender = new XHRSender(document.URL, resp => {
        try {
            let data = JSON.parse(resp);
            if (data.hasOwnProperty('success') && data['success'] === true) {
                clear();
                setModal(true, 'Password changed successfully');
                return;
            }
            if (data.hasOwnProperty('reason') && data['reason'] instanceof String) {
                setModal(false, data['reason']);
            } else {
                setModal(false, 'Sorry try again');
            }
        } catch (e) {
            setModal(false, 'Error occured');
        }
    });
    xhrSender.addField('curpass', curpass);
    xhrSender.addField('newpass', newpass);
    xhrSender.send();
};