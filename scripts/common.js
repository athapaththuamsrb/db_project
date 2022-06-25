class XHRSender {
    constructor(url, callback) {
        this.fields = {};
        this.url = url;
        this.callback = callback;
    }

    addField(fieldName, value) {
        this.fields[fieldName] = value;
    }

    send(responseType = '', callxhr = false) {
        let encoded = Object.keys(this.fields).map((index) => {
            return encodeURIComponent(index) + '=' + encodeURIComponent(this.fields[index]);
        });
        let reqBody = encoded.join("&");
        let xhr = new XMLHttpRequest();
        xhr.open("POST", this.url, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.responseType = responseType;
        xhr.onreadystatechange = () => {
            if (xhr.responseURL.includes('/login.php') && !this.url.includes('/login.php')){
                window.location = '/login.php';
                return;
            }
            if (xhr.readyState == XMLHttpRequest.DONE) {
                if (callxhr) {
                    this.callback(xhr);
                } else {
                    this.callback(xhr.response);
                }
            }
        };
        xhr.send(reqBody);
    }
}

class TableBuilder {
    constructor(table = null) {
        if (table) {
            this.table = table;
        } else {
            this.table = document.createElement('table');
        }
    }

    addHeadingRow(...headings) {
        let tr = document.createElement('tr');
        headings.forEach(heading => {
            let th = document.createElement('th');
            th.innerText = heading;
            tr.appendChild(th);
        });
        this.table.appendChild(tr)
    }

    addRow(...data) {
        let tr = document.createElement('tr');
        data.forEach(text => {
            let td = document.createElement('td');
            td.innerText = text;
            tr.appendChild(td);
        });
        this.table.appendChild(tr)
    }

    build() {
        return this.table;
    }
}

const username_pattern = /^[a-zA-Z0-9._]{5,12}$/;
const password_pattern = /^[\x21-\x7E]{8,15}$/;
const acc_no_pattern = /^[a-zA-Z0-9._]{5,12}$/; /* change pattern */
const balance_pattern = /^([0-9]+(\.?[0-9]?[0-9]?)?)$/;
const branch_id_pattern = /^[a-zA-Z0-9._]{5,12}$/; /* change pattern */
