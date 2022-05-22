class XHRSender {
    constructor(url, callback) {
        this.fields = {};
        this.url = url;
        this.callback = callback;
    }

    addField(fieldName, value) {
        this.fields[fieldName] = value;
    }

    send() {
        let encoded = Object.keys(this.fields).map((index) => {
            return encodeURIComponent(index) + '=' + encodeURIComponent(this.fields[index]);
        });
        let reqBody = encoded.join("&");
        let xhr = new XMLHttpRequest();
        xhr.open("POST", this.url, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = () => {
            if (xhr.readyState == XMLHttpRequest.DONE) {
                this.callback(xhr.response);
            }
        };
        xhr.send(reqBody);
    }
}