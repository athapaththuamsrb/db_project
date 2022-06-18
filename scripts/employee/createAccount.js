function showMessage(msg) {
  alert(msg); // TODO: modify this to show in a better way
}
function getType() {
  const typeValue = document.getElementById("type").value;
  if (typeValue === "fd") {
    document.getElementById("fd_visible").style.display = "block";
  } else {
    document.getElementById("fd_visible").style.display = "none";
  }
}
let owner_idInput = document.getElementById("owner_id");
let acc_noInput = document.getElementById("acc_no");
let acc_typeInput = document.getElementById("type");
let balanceInput = document.getElementById("balance");
let branch_idInput = document.getElementById("branch_id");
let durationInput = document.getElementById("duration");
let savings_acc_noInput = document.getElementById("savings_acc_no");
let submitBtn = document.getElementById("submitBtn");

acc_typeInput.onchange = getType;

function clear() {
  owner_idInput.value = "";
  acc_noInput.value = "";
  acc_typeInput.value = "savings";
  balanceInput.value = "";
  branch_idInput.value = "";
  durationInput.value = "";
  savings_acc_noInput.value = "";
}

submitBtn.onclick = (e) => {
  e.preventDefault();
  let owner_id = owner_idInput.value;
  let acc_no = acc_noInput.value;
  let acc_type = acc_typeInput.options[acc_typeInput.selectedIndex].value;
  let balance = balanceInput.value;
  let branch_id = branch_idInput.value;
  let duration = durationInput.value;
  let savings_acc_no = savings_acc_noInput.value;

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
  xhrSender.addField("branch_id", branch_id);
  xhrSender.addField("duration", duration);
  xhrSender.addField("savings_acc_no", savings_acc_no);
  xhrSender.send();
};
