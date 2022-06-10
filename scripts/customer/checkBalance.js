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

let acc_noInput = document.getElementById('acc_no');
let submitBtn = document.getElementById('submitBtn');

function clear() {
    acc_noInput.value = '';
}

submitBtn.onclick = e => {
    e.preventDefault();
    let acc_no = acc_noInput.value;
    
    /*
    if (!/^[a-zA-Z0-9.\-\x20]{2,30}$/.test(acc_no)) {
        showMessage("Invalid account number");
        return;
    }
    */
    
    let xhrSender = new XHRSender(document.URL, resp => {
        try {
            let data = JSON.parse(resp);
            if (data.hasOwnProperty('success') && data['success'] === true && data.hasOwnProperty('balance')) {
                clear();
                showMessage(data['balance']);
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
    xhrSender.addField('acc_no', acc_no);
    xhrSender.send();
};