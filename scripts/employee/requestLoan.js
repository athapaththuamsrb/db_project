let sav_accInput = document.getElementById("sav_acc");
let durationInput = document.getElementById("duration");
let amountInput = document.getElementById("amount");
let submitBtn = document.getElementById("submitBtn");

function clear() {
  sav_accInput.value = "";
  durationInput.value = "";
  amountInput.value = "";
}

submitBtn.onclick = (e) => {
  e.preventDefault();
  let sav_acc = sav_accInput.value;
  let duration = durationInput.value;
  let amount = amountInput.value;
  if (!sav_acc || !duration || !amount) {
    setModal(false, "Form should be filled correctly");
    return;
  }
  if (!acc_no_pattern.test(sav_acc)) {
    setModal(false, "Invalid account number");
    return;
  }
  if (!balance_pattern.test(amount)) {
    setModal(false, "Invalid amount");
    return;
  }
  if (!duration_pattern.test(duration)) {
    setModal(false, "Invalid duration");
    return;
  }
  if (duration > 120) {
    setModal(false, "You cannot apply loan for more than 10 years");
    return;
  }

  let xhrSender = new XHRSender(document.URL, (resp) => {
    try {
      let data = JSON.parse(resp);

      if (data.hasOwnProperty("success") && data["success"] === true) {
        clear();
        
        setModal(true, data["reason"]);
        return;
      }
      if (
        data.hasOwnProperty("reason") 
      ) {
        setModal(false, data["reason"]);
      } else {
        setModal(false, "Sorry try again");
      }
    } catch (e) {
      setModal(false, "Something went wrong!");
    }
  });
  xhrSender.addField("sav_acc", sav_acc);
  xhrSender.addField("duration", duration);
  xhrSender.addField("amount", amount);
  xhrSender.send();
};
