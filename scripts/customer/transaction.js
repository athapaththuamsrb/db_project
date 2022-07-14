let from_account = document.getElementById("from_acc");
let to_account = document.getElementById("to_acc");
let trans_amount = document.getElementById("amount");
let confirmBtn = document.getElementById("submitBtn");

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

to_account.onkeydown = event => { keyPressFn(event, acc_no_pattern, 'amount'); };
trans_amount.onkeydown = event => { keyPressFn(event, balance_pattern, ''); };

function clear() {
    from_account.value = '';
    to_account.value = '';
    trans_amount.value = '';
    
}

confirmBtn.onclick = e => {
    e.preventDefault();
    let from_acc_value = from_account.value;
    let to_acc_value = to_account.value;
    let amount_value = trans_amount.value;

    if (!acc_no_pattern.test(to_acc_value)) {
        setModal(false, "Invalid Account number");
        return;
    }
    if (!balance_pattern.test(amount_value)) {
        setModal(false, "Please enter a valid amount");
        return;
    }

    let xhrSender = new XHRSender(document.URL, resp => {
        try {
            let data = JSON.parse(resp);
            if (data.hasOwnProperty('success') && data['success'] === true ) {
                clear();
                setModal(true,"Transaction Successful");
                return;
            }
            if (data.hasOwnProperty('success') && data['success'] === false && data.hasOwnProperty('msg')) {
                setModal(false, data['msg']);
                return;
            } 
            if (data.hasOwnProperty('success') && data['success'] === false ) {
                setModal(false,"Transaction Failed");
                return;
            }
            else{
                setModal(false, "Try Again");
            } 
        } catch (e) {
            setModal(false,'Error occured');
        }
    });

    xhrSender.addField('to_acc', to_acc_value);
    xhrSender.addField('from_acc', from_acc_value);
    xhrSender.addField('amount', amount_value);

    xhrSender.send();
};
