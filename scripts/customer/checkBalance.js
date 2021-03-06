function keyPressFn(e, pattern, nxt, modalMessage) {
    if (e.keyCode === 13) {
        e.preventDefault();
        let value = e.target.value.trim();
        if (!pattern.test(value)) {
            setModal(false, modalMessage);
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


let acc_noInput = document.getElementById('acc_no');
let submitBtn = document.getElementById('submitBtn');

function clear() {
    acc_noInput.value = '';
}

acc_noInput.onkeydown = event => { keyPressFn(event, acc_no_pattern, '', "Invalid account number"); };

submitBtn.onclick = e => {
    e.preventDefault();
    let acc_no = acc_noInput.value;
   
    if (!acc_no_pattern.test(acc_no)) {
        setModal(false, "Invalid account number");
        return;
    }
    
    let xhrSender = new XHRSender(document.URL, resp => {
        try {
            let data = JSON.parse(resp);
            if (data.hasOwnProperty('success') && data['success'] === true && data.hasOwnProperty('balance')) {
                clear();
                setModal(true, data['balance']);
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
    xhrSender.addField('acc_no', acc_no);
    xhrSender.send();
};