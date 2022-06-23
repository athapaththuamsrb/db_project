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
      setModal(false, e);
    }
  });
  xhrSender.addField("sav_acc", sav_acc);
  xhrSender.addField("duration", duration);
  xhrSender.addField("amount", amount);
  xhrSender.send();
};
