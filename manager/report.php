<?php
require_once('auth.php');
$user = (new Authenticator())->checkAuth();

require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";

function makePDF(string $html, string $title): ?string
{
    try {
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator('Seychelles bank');
        $pdf->SetAuthor('Seychelles bank');
        $pdf->SetTitle('Late loan report');
        $pdf->SetSubject('Late loan report');

        $pdf->SetHeaderData('', 20, $title, (new DateTime('today'))->format('Y-m-d'));
        //$pdf->setPrintHeader(false);
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
        $pdf->SetFont('dejavusansb', '', 14, '', true);
        $pdf->SetFont('dejavusans', '', 10, '', true);

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

function generateTableHTML(array $headings, array $data): string
{
    $headrow = implode('</th><th class="head">', $headings);
    $bodyrows = array_map(function ($rowdata) {
        $bodyrow = implode('</td><td class="data">', $rowdata);
        return "<tr><td class=\"data\">$bodyrow</td></tr>";
    }, $data);
    $table = "<tr><th class=\"head\">$headrow</th></tr>\n" . implode("\n", $bodyrows);
    $html = "<html><head><style>
    table {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
        padding-top: 6px;
        padding-bottom: 6px;
      }
      td {
        text-align: right;
        border: 1px solid #777;
      }
      th {
        text-align: center;
        border: 1px solid #777;
        background-color: #04aa6d;
        color: white;
        font-family:dejavusansb;
      }</style></head>\n<body>\n<table>\n$table\n</table>\n</body></html>";
    return $html;
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
    if ($data == null) {
        fail('Error occured');
    }
    $html = generateTableHTML($data[0], $data[1]);
    $pdfFilePath = makePDF($html, 'Late Loan Report');
    if (!$pdfFilePath) {
        fail('PDF creation failed');
    }
    header('Content-Type: application/pdf');
    header("Content-Disposition: attachment;");
    header('Content-Length: ' . filesize($pdfFilePath));
    if (ob_get_length() > 0) {
        ob_clean();
    }
    flush();
    readfile($pdfFilePath);
    unlink($pdfFilePath);
} else {
    $data = $conn->getTransactions($user->getUsername());
    if ($data == null) {
        fail('Error occured');
    }
    $html = generateTableHTML($data[0], $data[1]);
    $pdfFilePath = makePDF($html, 'Transaction Report');
    if (!$pdfFilePath) {
        fail('PDF creation failed');
    }
    header('Content-Type: application/pdf');
    header("Content-Disposition: attachment;");
    header('Content-Length: ' . filesize($pdfFilePath));
    if (ob_get_length() > 0) {
        ob_clean();
    }
    flush();
    readfile($pdfFilePath);
    unlink($pdfFilePath);
}
