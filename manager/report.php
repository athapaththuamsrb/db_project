<?php
require_once('auth.php');
$user = (new Authenticator())->checkAuth();

require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";

function makePDF(string $html): ?string
{
    try {
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator('Multi-Grammar');
        $pdf->SetAuthor('Multi-Grammar');
        $pdf->SetTitle('Multi-Grammar PDF');
        $pdf->SetSubject('Multi-Grammar PDF');

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        // Set font
        $pdf->SetFont('dejavusans', '', 14, '', true);

        // Add a page
        $pdf->AddPage();

        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/tmp')) {
            mkdir($_SERVER['DOCUMENT_ROOT'] . '/tmp', 0777, true);
        }

        do {
            $i = rand(0, PHP_INT_MAX);
            $pdfFilePath = $_SERVER['DOCUMENT_ROOT'] . "/tmp/$i.pdf";
        } while (file_exists($pdfFilePath));

        $pdf->Output($pdfFilePath, 'F');
        if (file_exists($pdfFilePath)) return $pdfFilePath;
    } catch (Exception $e) {
    }
    return null;
}

function fail(string $reason)
{
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'reason' => $reason]);
    die();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    @include($_SERVER['DOCUMENT_ROOT'] . '/views/manager/report.php');
    die();
}

if (!isset($_POST['type']) || !in_array($_POST['type'], ['loan', 'transaction'], true)) {
    fail('No valid type provided');
}

require_once('../utils/dbcon.php');
$conn = DatabaseConn::get_conn();
if (!$conn) {
    fail('Error occured');
}

if ($_POST['type'] === 'loan') {
    $data = $conn->getLateLoans($user->getUsername());
    if (!$data) {
        fail('Error occured');
    }
    $pdfFilePath = makePDF('late loan');
    if (!$pdfFilePath) {
        fail('PDF creation failed');
    }
    header('Content-Type: application/pdf');
    header("Content-Disposition: attachment;");
    header('Content-Length: ' . filesize($pdfFilePath));
    ob_clean();
    flush();
    readfile($pdfFilePath);
    unlink($pdfFilePath);
} else {
    $pdfFilePath = makePDF('transaction report');
    if (!$pdfFilePath) {
        fail('PDF creation failed');
    }
    header('Content-Type: application/pdf');
    header("Content-Disposition: attachment;");
    header('Content-Length: ' . filesize($pdfFilePath));
    ob_clean();
    flush();
    readfile($pdfFilePath);
    unlink($pdfFilePath);
}
