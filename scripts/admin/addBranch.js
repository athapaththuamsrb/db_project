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

let idInput = document.getElementById('id');
let nameInput = document.getElementById('name');
let locInput = document.getElementById('location');
let managerInput = document.getElementById('manager');
let submitBtn = document.getElementById('submitBtn');

function clear() {
    idInput.value = '';
    nameInput.value = '';
    locInput.value = '';
    managerInput.value = '';
}

submitBtn.onclick = e => {
    e.preventDefault();
    let id = idInput.value;
    let name = nameInput.value;
    let location = locInput.value;
    let manager = managerInput.value;
    if (!/^[0-9]{1,5}$/.test(id)) {
        showMessage("Invalid branch ID");
        return;
    }
    if (!/^[a-zA-Z0-9.\-\x20]{2,30}$/.test(name)) {
        showMessage("Invalid branch name");
        return;
    }
    if (!/^[a-zA-Z0-9.,\/\-\x20]{2,50}$/.test(location)) {
        showMessage("Invalid location");
        return;
    }
    if (!/^[a-zA-Z0-9._]{5,12}$/.test(manager)) {
        showMessage("Invalid manager id");
        return;
    }
    let xhrSender = new XHRSender(document.URL, resp => {
        try {
            let data = JSON.parse(resp);
            if (data.hasOwnProperty('success') && data['success'] === true) {
                clear();
                showMessage('Branch created successfully');
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
    xhrSender.addField('id', id);
    xhrSender.addField('name', name);
    xhrSender.addField('location', location);
    xhrSender.addField('manager', manager);
    xhrSender.send();
};