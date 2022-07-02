let amountInput = document.getElementById("amount");
let loan_idInput = document.getElementById("loan_id");
let submitBtn = document.getElementById("submitBtn");

function clear() {
  loan_idInput.value = "";
  amountInput.value = "";
}

submitBtn.onclick = (e) => {
  e.preventDefault();
  let loan_id = loan_idInput.value;
  let amount = amountInput.value;

if (!balance_pattern.test(amount)) {
  setModal(false, "Invalid balance amount");
  return;
}

if (!loan_id_pattern.test(loan_id)) {
  setModal(false, "Invalid loan ID");
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
      setModal(false, e);
    }
  });
  xhrSender.addField("loan_id", loan_id);
  xhrSender.addField("amount", amount);
  xhrSender.send();
};
