let from_account = document.getElementById("from_acc");
let to_account = document.getElementById("to_acc");
let owner_name = document.getElementById("ownername");
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
    owner_name.value = '';
    
}

confirmBtn.onclick = e => {
    e.preventDefault();
    let from_acc_value = from_account.value;
    let to_acc_value = to_account.value;
    let amount_value = trans_amount.value;
    let owner_name_value = owner_name.value;

    
    if (!/^[0-9]{12}$/.test(from_acc_value)) {
        setModal(false, "Invalid Account number");
        return;
    }
    if (! /^[0-9]+(\.[0-9]{2})?$/.test(to_acc_value)) {
        setModal(false, "Please enter a valid amount");
        return;
    }
    if (!/^[a-zA-Z0-9._]{5,12}$/.test(to_acc_value)) {
        setModal(false, "Please enter a valid username");
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
            setModal(false,'Error occured');
        }
    });

    xhrSender.addField('ownername', owner_name_value);
    xhrSender.addField('to_acc', to_acc_value);
    xhrSender.addField('from_acc', from_acc_value);
    xhrSender.addField('amount', amount_value);

    xhrSender.send();
};
