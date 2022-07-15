function getType() {
  const typeValue = document.getElementById("type").value;
  if (typeValue === "fd") {
    document.getElementById("fd_visible").style.display = "block";
    balanceInput.onkeydown = event => { keyPressFn(event, balance_pattern, 'savings_acc_no', "Invalid balance"); };
    savings_acc_noInput.onkeydown = event => { keyPressFn(event, acc_no_pattern, '', "Invalid savings account number"); };
  } else {
    document.getElementById("fd_visible").style.display = "none";
    balanceInput.onkeydown = event => { keyPressFn(event, balance_pattern, '', "Invalid balance"); };
  }
}

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

let owner_idInput = document.getElementById("owner_id");
let acc_noInput = document.getElementById("acc_no");
let acc_typeInput = document.getElementById("type");
let balanceInput = document.getElementById("balance");
let durationInput = document.getElementById("duration");
let savings_acc_noInput = document.getElementById("savings_acc_no");
let submitBtn = document.getElementById("submitBtn");

acc_typeInput.onchange = getType;

function clear() {
  owner_idInput.value = "";
  acc_noInput.value = "";
  acc_typeInput.value = "savings";
  balanceInput.value = "";
  durationInput.value = "6";
  document.getElementById("fd_visible").style.display = "none";
  savings_acc_noInput.value = "";
}

owner_idInput.onkeydown = event => { keyPressFn(event, username_pattern, 'acc_no', "Invalid username"); };
acc_noInput.onkeydown = event => { keyPressFn(event, acc_no_pattern, 'acc_type', "Invalid account number"); };


submitBtn.onclick = (e) => {
  e.preventDefault();
  let owner_id = owner_idInput.value;
  let acc_no = acc_noInput.value;
  let acc_type = acc_typeInput.options[acc_typeInput.selectedIndex].value;
  let balance = balanceInput.value;
  let duration = durationInput.options[durationInput.selectedIndex].value;
  let savings_acc_no = savings_acc_noInput.value;

  if (!username_pattern.test(owner_id)) {
    setModal(false, "Invalid owner ID");
    return;
  }
  if (!acc_no_pattern.test(acc_no)) {
    setModal(false, "Invalid account number");
      return;
  }
  if (!balance_pattern.test(balance)) {
    setModal(false, "Invalid balance amount");
    return;
  }
  if (acc_type === "fd"){ 
    if(!acc_no_pattern.test(savings_acc_no)){
      setModal(false, "Invalid savings account number");
      return;
    }
    if (parseInt(duration) <= 0 || parseInt(duration) >= 100){
      setModal(false, "Invalid duration");
      return;
    }
  }


  let xhrSender = new XHRSender(document.URL, (resp) => {
    try {
      let data = JSON.parse(resp);
      if (data.hasOwnProperty("success") && data["success"] === true) {
        clear();
        let created_acc = data['created_acc'];
        let msg = created_acc.concat(" ", "account successfully created!")
        setModal(true, msg);
        return;
      }
      if (
        data.hasOwnProperty("reason") /*&& data['reason'] instanceof String*/
      ) {
        setModal(false, data["reason"]);
      } else {
        setModal(false,"Sorry try again");
      }
    } catch (e) {
        setModal(false, "Error occured");
    }
  });
  xhrSender.addField("owner_id", owner_id);
  xhrSender.addField("acc_no", acc_no);
  xhrSender.addField("acc_type", acc_type);
  xhrSender.addField("balance", balance);
  xhrSender.addField("duration", duration);
  xhrSender.addField("savings_acc_no", savings_acc_no);
  xhrSender.send();
};
