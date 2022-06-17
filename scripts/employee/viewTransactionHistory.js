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

let owner_idInput = document.getElementById('owner_id');
let acc_noInput = document.getElementById('acc_no');
let start_dateInput = document.getElementById('start_date');
let end_dateInput = document.getElementById('end_date');
let submitBtn = document.getElementById('submitBtn');

function clear() {
    owner_idInput.value = '';
    acc_noInput.value = '';
    start_dateInput = '';
    end_dateInput = '';
}

submitBtn.onclick = e => {
    e.preventDefault();
    let owner_id = owner_idInput.value;
    let acc_no = acc_noInput.value;
    let start_date = start_dateInput.value;
    let end_date = end_dateInput.value;

    /*
    if (!/^[0-9]{1,5}$/.test(owner_id)) {
        showMessage("Invalid owner ID");
        return;
    }
    if (!/^[a-zA-Z0-9.\-\x20]{2,30}$/.test(acc_no)) {
        showMessage("Invalid account number");
        return;
    }
    */

    let xhrSender = new XHRSender(document.URL, resp => {
        try {
            let data = JSON.parse(resp);
            let tblDiv = document.getElementById('table');
            if (!tblDiv) return;
            let chld = tblDiv.lastElementChild;
            while (chld) {
                tblDiv.removeChild(chld);
                chld = tblDiv.lastElementChild;
            }
            tblDiv.hidden = true;
            if (data.hasOwnProperty('success') && data['success'] === true && data.hasOwnProperty('data')) {
                clear();
                let transactionData = data['data'];
                if (!transactionData || transactionData.length==0) return;
                let tblBuilder = new TableBuilder();
                tblBuilder.addHeadingRow('Transaction ID', 'From', 'To', 'Amount', 'Time');
                transactionData.forEach(transaction => {
                    tblBuilder.addRow(transaction['trans_id'], transaction['from_acc'], transaction['to_acc'], transaction['amount'], transaction['trans_time']);
                });
                let table = tblBuilder.build();
                tblDiv.appendChild(table);
                tblDiv.hidden = false;
                // let msg = JSON.stringify(data['data']);
                // showMessage(msg); // display this on the page in a proper way rather than an alert
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
    xhrSender.addField('owner_id', owner_id);
    xhrSender.addField('acc_no', acc_no);
    xhrSender.addField('start_date', start_date);
    xhrSender.addField('end_date', end_date);
    xhrSender.send();
};