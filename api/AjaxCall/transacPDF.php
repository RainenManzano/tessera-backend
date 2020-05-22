<?php
include '../Classes/PDFCreator.php';
$pdf = new PDFCreator();
$type = $_GET['type'];

switch($type) {
  case 'departmentMaintenanceReport':
    $pdf->departmentMaintenanceReport();
    break;

  case 'positionMaintenanceReport':
    $pdf->positionMaintenanceReport();
    break;
 
  case 'priorityMaintenanceReport':
    $pdf->priorityMaintenanceReport();
    break;

  case 'categoryMaintenanceReport':
    $pdf->categoryMaintenanceReport();
    break;

  case 'issueMaintenanceReport':
    $pdf->issueMaintenanceReport();
    break;

  case 'supportMaintenancePreferenceReport':
    $pdf->supportMaintenancePreferenceReport();
    break;

  case 'supportMaintenancePreferenceReport':
    $pdf->supportMaintenancePreferenceReport();
    break;

  case 'allTicketsReport':
    $pdf->allTicketsReport();
    break;

  case 'departmentTotalIssueReport':
    $pdf->departmentTotalIssueReport();
    break;

  case 'getPerformanceReport':
    $pdf->getPerformanceReport();
    break;

  case 'getSummarySupported':
    $pdf->getSummarySupported();
    break;

  default:
    echo "No indicated report";




}