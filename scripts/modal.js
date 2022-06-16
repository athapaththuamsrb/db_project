const span = document.getElementsByClassName("close")[0];
const mFooter = document.getElementById('mFooter');
const mBody = document.getElementById('mBody');
const modal = document.getElementById("myModal");

span.onclick = function () {
  modal.style.display = "none";
}
window.onclick = function (event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
function setModal(status, msg) {
  while (mBody.firstChild) {
    mBody.removeChild(mBody.lastChild);
  }
  while (mFooter.firstChild) {
    mFooter.removeChild(mFooter.lastChild);
  }
  let p = document.createElement('p');
  let h3 = document.createElement('h3');
  if (status) {
    document.getElementById("mHeader").style.background = "green";
    mFooter.style.background = "green";
    h3.innerText = 'Thank you!';
  } else {
    document.getElementById("mHeader").style.background = "red";
    mFooter.style.background = "red";
    h3.innerText = 'Try Again!';
  }
  p.innerText = msg;
  mBody.appendChild(p);
  mFooter.appendChild(h3);
  modal.style.display = "block";
}