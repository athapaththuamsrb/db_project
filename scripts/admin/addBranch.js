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

let idInput = document.getElementById('id');
let nameInput = document.getElementById('name');
let locInput = document.getElementById('location');
let managerInput = document.getElementById('manager');
let submitBtn = document.getElementById('submitBtn');

idInput.onkeydown = event => { keyPressFn(event, branch_id_pattern, 'name'); };
nameInput.onkeydown = event => { keyPressFn(event, /^[a-zA-Z0-9.\-\x20]{2,30}$/, 'location'); };
locInput.onkeydown = event => { keyPressFn(event, /^[a-zA-Z0-9.,\/\-\x20]{2,50}$/, 'manager'); };
managerInput.onkeydown = event => { keyPressFn(event, username_pattern, ''); };

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
        setModal(false, "Invalid branch ID");
        return;
    }
    if (!/^[a-zA-Z0-9.\-\x20]{2,30}$/.test(name)) {
        setModal(false, "Invalid branch name");
        return;
    }
    if (!/^[a-zA-Z0-9.,\/\-\x20]{2,50}$/.test(location)) {
        setModal(false, "Invalid location");
        return;
    }
    if (!username_pattern.test(manager)) {
        setModal(false, "Invalid manager id");
        return;
    }
    let xhrSender = new XHRSender(document.URL, resp => {
        try {
            let data = JSON.parse(resp);
            if (data.hasOwnProperty('success') && data['success'] === true) {
                clear();
                setModal(true, 'Branch created successfully');
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
    xhrSender.addField('id', id);
    xhrSender.addField('name', name);
    xhrSender.addField('location', location);
    xhrSender.addField('manager', manager);
    xhrSender.send();
};