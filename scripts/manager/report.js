document.getElementById('lateLoanBtn').onclick = e => {
    e.preventDefault();

    let xhrSender = new XHRSender(document.URL, xhr => {
        let cont_type = xhr.getResponseHeader('Content-Type');
        if (cont_type === 'application/pdf') {
            let blob = new Blob([xhr.response], { type: 'application/pdf' });
            let a = document.createElement("a");
            a.target = '_blank';
            let url = window.URL.createObjectURL(blob);
            a.href = url;
            a.download = 'late_loan_report.pdf';
            a.click();
            window.URL.revokeObjectURL(url);
        } else {
            setModal(false, 'PDF download error');
        }
    });
    xhrSender.addField('type', 'loan');
    xhrSender.send('blob', true);
}

document.getElementById('transactionBtn').onclick = e => {
    e.preventDefault();

    let xhrSender = new XHRSender(document.URL, xhr => {
        let cont_type = xhr.getResponseHeader('Content-Type');
        if (cont_type === 'application/pdf') {
            let blob = new Blob([xhr.response], { type: 'application/pdf' });
            let a = document.createElement("a");
            a.target = '_blank';
            let url = window.URL.createObjectURL(blob);
            a.href = url;
            a.download = 'transaction_report.pdf';
            a.click();
            window.URL.revokeObjectURL(url);
        } else {
            setModal(false, 'PDF download error');
        }
    });
    xhrSender.addField('type', 'transaction');
    xhrSender.send('blob', true);
}