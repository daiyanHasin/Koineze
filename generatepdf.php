<?php
require_once('C:\xampp\htdocs\Koinze website\tc-lib-pdf-main\tc-lib-pdf-main\src\Tcpdf.php');

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Create a new PDF instance
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Set PDF options and properties here (e.g., author, title, etc.)

    // Add a page
    $pdf->AddPage();

    // Customize PDF content here, e.g., include user information, earnings, expenses, etc.
    $pdf->SetFont('helvetica', '', 12);
    $pdf->writeHTML("<h1>User Profile PDF Report</h1>");
    // Include more content based on your needs

    // Close and output PDF
    $pdf->Output('profile_report.pdf', 'D'); // D: Download

    exit;
}
?>