let fix_accInput = document.getElementById("fix_acc");
let durationInput = document.getElementById("duration");
let amountInput = document.getElementById("amount");
let submitBtn = document.getElementById("submitBtn");



function clear() {
  fix_accInput.value = "";
  durationInput.value = "";
  amountInput.value = "";

}

submitBtn.onclick = (e) => {
  e.preventDefault();
  let fix_acc = fix_accInput.value;
  let duration = durationInput.value;
  let amount = amountInput.value;
  console.log(fix_acc, duration, amount);
  if (!amount || !duration || !fix_acc) {
    setModal(false, "Form should be filled correctly");
    return;
  }
if (!acc_no_pattern.test(fix_acc)) {
  setModal(false, "Invalid account number");
  return;
}
if (!balance_pattern.test(amount)) {
  setModal(false, "Invalid balance amount");
  return;
}
if (!duration_pattern.test(duration)) {
  setModal(false, "Invalid duration");
  return;
}
if (duration>120) {
  setModal(false, "You cannot apply loan for more than 10 years");
  return;
}
  let xhrSender = new XHRSender(document.URL, (resp) => {
    
    try {
      let data = JSON.parse(resp);
      
      
      
      if (data.hasOwnProperty("success") && data["success"] === true) {
        clear();
        // let created_acc = data["created_acc"];
        // let msg = created_acc.concat(" ", "account successfully created!");
        setModal(true, data["reason"]);
        return;
      }
      if (
        data.hasOwnProperty("reason") /*&& data['reason'] instanceof String*/
        ) {
          setModal(false, data["reason"]);
        } else {
          setModal(false, "Sorry try again");
        }
      } catch (e) {
      
      setModal(false, "Something went wrong!");
    }
  });
  xhrSender.addField("fix_acc", fix_acc);
  xhrSender.addField("duration", duration);
  xhrSender.addField("amount", amount);
  xhrSender.send();
};
