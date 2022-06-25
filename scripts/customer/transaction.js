let from_account = document.getElementById("from_acc");
let to_account = document.getElementById("to_acc");
let trans_amount = document.getElementById("amount");
let confirmBtn = document.getElementById("confirm");

function keyPressFn(e, nxt) {
    if (e.keyCode === 13) {
        e.preventDefault();
        if (nxt == '') {
            document.getElementById("confirm").click();
        } else {
            let nextElem = document.getElementById(nxt);
            if (nextElem) {
                nextElem.focus();
            }
        }
    }
}

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
    if (!balance_pattern.test(to_acc_value)) {
        setModal(false, "Please enter a valid amount");
        return;
    }

    let xhrSender = new XHRSender(document.URL, resp => {
        try {
            console.log(resp);
            let data = JSON.parse(resp);
            //console.log(data);
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
            console.log(e);
            setModal(false,'Error occured');
        }
    });

    xhrSender.addField('to_acc', to_acc_value);
    xhrSender.addField('from_acc', from_acc_value);
    xhrSender.addField('amount', amount_value);

    xhrSender.send();
};
