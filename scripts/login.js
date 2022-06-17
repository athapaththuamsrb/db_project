function keyPressFn(e, pattern, cur, nxt) {
    if (e.keyCode === 13) {
        e.preventDefault();
        let value = document.getElementById(cur).value;
        if (!pattern.test(value)){
            document.getElementById('invalid').hidden = false;
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

document.getElementById('username').onkeydown = event => { keyPressFn(event, username_pattern, 'username', 'password'); };
document.getElementById('password').onkeydown = event => { keyPressFn(event, password_pattern, 'password', ''); };

function showSlides() {
    let i;
    let slides = document.getElementsByClassName("mySlides");
    let dots = document.getElementsByClassName("dot");
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    slideIndex++;
    if (slideIndex > slides.length) {
        slideIndex = 1;
    }
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
    }
    if (slides[slideIndex - 1] && dots[slideIndex - 1]) {
        slides[slideIndex - 1].style.display = "block";
        dots[slideIndex - 1].className += " active";
    }
    setTimeout(showSlides, 2000); // Change image every 2 seconds
}

let slideIndex = 0;
showSlides();
document.getElementById('username').focus();