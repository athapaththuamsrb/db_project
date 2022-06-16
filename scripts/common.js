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