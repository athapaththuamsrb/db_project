let buttonList = document.querySelectorAll(".button");
buttonList.forEach(function (i) {
  i.addEventListener("click", function (e) {
    let loanID = e.target.value;
if (!loan_id_pattern.test(loanID)) {
  setModal(false, "Invalid loan ID");
  return;
}
    let xhrSender = new XHRSender(document.URL, (resp) => {
      try {
        let data = JSON.parse(resp);

        if (data.hasOwnProperty("success") && data["success"] === true) {
          e.target.parentElement.parentElement.style.display = "none";
          setModal(true, data["reason"]);
          return;
        }
        if (
          data.hasOwnProperty("reason") /*&& data['reason'] instanceof String*/
        ) {
          setModal(false, data["reason"]);
          return;
        } else {
          setModal(false, "Sorry try again");
          return;
        }
      } catch (e) {
        setModal(false, "Something went wrong!");
      }
    });
    xhrSender.addField("loanID", loanID);
    xhrSender.send();
  });
});
