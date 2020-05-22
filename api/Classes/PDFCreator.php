<?php
include '../../config/Database.php';
include '../../libs/vendor/autoload.php'; 

class PDFCreator extends TCPDF
{

    public function createTablePdf($page_orientation, $title, $html) {
        // create new PDF document
        $pdf = new PDFCreator($page_orientation, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Tessera Helpdesk');
        $pdf->SetTitle('');
        $pdf->SetSubject('');
        $pdf->SetKeywords('TCPDF, PDF');
        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $title, PDF_HEADER_STRING."Tessera");
        // set header and footer fonts
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $pdf->setLanguageArray($l);
        }
        // set font
        $pdf->SetFont('helvetica', '', 12);
        // add a page
        $pdf->AddPage();
        // output HTML content
        $pdf->writeHTML($html, true, false, true, false, '');
        // reset pointer to the last page
        $pdf->lastPage();
        // close and output PDF document
        $pdf->Output('report.pdf', 'I');
    }

    public function departmentMaintenanceReport() {
        $title = "Department Maintenance Report";
        $headers = ["Name", "Description"];
        $sql = " SELECT * FROM departments 
                    ORDER BY Name ASC;";
        $data = (new Database())->query($sql);
        $keys = ["Name", "Description"];
        // create some HTML content
        $html = '<table cellspacing="0" cellpadding="1" border="1">';
        $html .= '<tr>';
        foreach($headers as $header) {
            $html .= '<td align="center"><b>'.$header.'</b></td>';
        }
        $html .= '</tr>';
        for($ctr=0;$ctr<count($data);$ctr++) {
            $html .= '<tr>';
            for($counter=0;$counter<count($keys);$counter++) {
                $html .= '<td align="center">'.$data[$ctr][$keys[$counter]].'</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</table>';
        $html .= '<br><br>';
        $totaldepart = "SELECT COUNT(*) AS 'ALLDEPARTMENT' FROM departments;";       
        $totaldeparts = (new Database())->query($totaldepart);
        $html .= '<h4>TOTAL DEPARTMENT: '.$totaldeparts[0]['ALLDEPARTMENT'].'</h4>';
        $this->createTablePdf("P", $title, $html);
    }

    public function departmentTotalIssueReport() {
        $title = "Department Total Issue Report";
        $headers = ["NAME", "TOTAL ISSUES"];
       
        $sql = "SELECT
        departments.Name,
        COUNT(DISTINCT(tickets.Ticket_Id)) as 'TOTAL ISSUES'
        FROM
            tickets
        INNER JOIN users ON tickets.CreatedBy = users.User_Id
        INNER JOIN departments ON users.Department_Id = departments.Department_Id
        GROUP BY
            departments.Department_Id
        ORDER BY departments.Name desc";


        $data = (new Database())->query($sql);
        // print_r($data);
        // die();

        $keys = ["Name", "TOTAL ISSUES"];
        // create some HTML content
        $html = '<table cellspacing="0" cellpadding="1" border="1">';
        $html .= '<tr>';
        foreach($headers as $header) {
            $html .= '<td align="center"><b>'.$header.'</b></td>';
        }
        $html .= '</tr>';
        for($ctr=0;$ctr<count($data);$ctr++) {
            $html .= '<tr>';
            for($counter=0;$counter<count($keys);$counter++) {
                $html .= '<td align="center">'.$data[$ctr][$keys[$counter]].'</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</table>';

        // $html .= '<br><br>';
        // $totaldepart = "SELECT COUNT(*) AS 'ALLDEPARTMENT' FROM departments;";       
        // $totaldeparts = (new Database())->query($totaldepart);
        // $html .= '<h4>TOTAL DEPARTMENT: '.$totaldeparts[0]['ALLDEPARTMENT'].'</h4>';

        
        $this->createTablePdf("P", $title, $html);
    }

    public function positionMaintenanceReport() {
        $title = "Position Maintenance Report";
        $headers = ["Position", "Description", "Department"];
        $sql = "SELECT p.*, d.Name as Department
                from positions p
                LEFT JOIN departments d on p.Department_Id = d.Department_Id
                order by d.Name ASC;";
        $data = (new Database())->query($sql);
        $keys = ["Position_Name", "Position_Desc", "Department"];
        // create some HTML content
        $html = '<table cellspacing="0" cellpadding="1" border="1">';
        $html .= '<tr>';
        foreach($headers as $header) {
            $html .= '<td align="center"><b>'.$header.'</b></td>';
        }
        $html .= '</tr>';
        for($ctr=0;$ctr<count($data);$ctr++) {
            $html .= '<tr>';
            for($counter=0;$counter<count($keys);$counter++) {
                $html .= '<td align="center">'.$data[$ctr][$keys[$counter]].'</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</table>';
        $html .= '<br><br>';
        $totalpos = "SELECT COUNT(*) AS 'ALLPOSITIONS' FROM positions;";       
        $totalpostions = (new Database())->query($totalpos);
        $html .= '<h4>TOTAL POSITION: '.$totalpostions[0]['ALLPOSITIONS'].'</h4>';
        $this->createTablePdf("P", $title, $html);
    }

    public function priorityMaintenanceReport() {
        $title = "Priority Maintenance Report";
        $headers = ["Level", "Description", "Duration"];
        $sql = " SELECT *, '' as Duration FROM priorities order by Level asc";
        $data = (new Database())->query($sql);
        for($ctr=0;$ctr<count($data);$ctr++) {
            $data[$ctr]["Duration"] = $data[$ctr]["Days"]." ".$data[$ctr]["Hours"]." ".$data[$ctr]["Minutes"];
        }
        $keys = ["Level", "Label", "Duration"];
        // create some HTML content
        $html = '<table cellspacing="0" cellpadding="1" border="1">';
        $html .= '<tr>';
        foreach($headers as $header) {
            $html .= '<td align="center"><b>'.$header.'</b></td>';
        }
        $html .= '</tr>';
        for($ctr=0;$ctr<count($data);$ctr++) {
            $html .= '<tr>';
            for($counter=0;$counter<count($keys);$counter++) {
                $html .= '<td align="center">'.$data[$ctr][$keys[$counter]].'</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</table>';
        $html .= '<br><br>';
        $this->createTablePdf("P", $title, $html);
    }

    public function categoryMaintenanceReport() {
        $title = "Category Maintenance Report";
        $headers = ["Category", "Description"];
        $sql = " SELECT * FROM categories order by Category_Id ASC ";
        $data = (new Database())->query($sql);
        $keys = ["Name", "Description"];
        // create some HTML content
        $html = '<table cellspacing="0" cellpadding="1" border="1">';
        $html .= '<tr>';
        foreach($headers as $header) {
            $html .= '<td align="center"><b>'.$header.'</b></td>';
        }
        $html .= '</tr>';
        for($ctr=0;$ctr<count($data);$ctr++) {
            $html .= '<tr>';
            for($counter=0;$counter<count($keys);$counter++) {
                $html .= '<td align="center">'.$data[$ctr][$keys[$counter]].'</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</table>';
        $this->createTablePdf("P", $title, $html);
    }

    public function issueMaintenanceReport() {
        $title = "Issue Maintenance Report";
        $headers = ["Issue", "Description", "Category", "Level"];
        $sql = "SELECT i.*, p.*, c.Name, c.Description as CategoryDescription, '' as Duration
                    FROM issues i
                    INNER JOIN categories c on c.Category_Id = i.Category_Id
                    INNER JOIN priorities p on p.Priority_Id = i.Priority_Id
                    order by i.Issue_Id ASC ";
        $data = (new Database())->query($sql);
        $keys = ["Issue", "Description", "Name", "Label"];
        // create some HTML content
        $html = '<table cellspacing="0" cellpadding="1" border="1">';
        $html .= '<tr>';

        foreach($headers as $header) {
            $html .= '<td align="center"><b>'.$header.'</b></td>';
        }
        $html .= '</tr>';
        for($ctr=0;$ctr<count($data);$ctr++) {
            $html .= '<tr>';
            for($counter=0;$counter<count($keys);$counter++) {
                $html .= '<td align="center">'.$data[$ctr][$keys[$counter]].'</td>';
            }
            $html .= '</tr>';
        }

        $html .= '</table>';

        $html .= '<br><br>';
        $totaliss = "SELECT COUNT(*) AS ALLISSUE FROM issues;";       
        $totalissue = (new Database())->query($totaliss);
        $html .= '<h4>TOTAL ISSUES: '.$totalissue[0]['ALLISSUE'].'</h4>';

        $this->createTablePdf("P", $title, $html);
    }

    public function supportMaintenancePrceferenceReport() {

    }

    public function allTicketsReport() {
        $title = "All Ticket Report";
        $headers = ["ID", "Issue", "Created By", "Supported By", "Level", "Status"];
        $sql = "SELECT t.*, i.Issue as IssueName, i.Description as IssueDescription,
                    p.*,
                    created.Lastname as CreatedLastname, created.Firstname as CreatedFirstname,
                    created.Middlename as CreatedMiddlename,
                    supported.Lastname as SupportedLastname, supported.Firstname as SupportedFirstname, 
                    supported.Middlename as SupportedMiddlename
                    FROM tickets t
                    LEFT JOIN issues i on i.Issue_Id = t.Issue
                    LEFT JOIN categories c on c.Category_Id = i.Category_Id
                    LEFT JOIN priorities p on p.Priority_Id = i.Priority_Id
                    LEFT JOIN users created on created.User_Id = t.CreatedBy
                    LEFT JOIN users supported on supported.User_Id = t.SupportedBy
                    ORDER BY t.Ticket_Id asc;";
        $data = (new Database())->query($sql);
        $keys = ["Ticket_Id", "IssueName", "CreatedLastname", "SupportedLastname", "Label", "Status"];
        // create some HTML content
        $html = '<table cellspacing="0" cellpadding="1" border="1">';
        $html .= '<tr>';
        foreach($headers as $header) {
            $html .= '<th align="center" ><b>'.$header.'</b></th>';
        }
        $html .= '</tr>';
        for($ctr=0;$ctr<count($data);$ctr++) {
            $html .= '<tr>';
            for($counter=0;$counter<count($keys);$counter++) {
                $html .= '<td align="center">'.$data[$ctr][$keys[$counter]].'</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</table>';
        $totaltickets = "SELECT COUNT(*) AS 'ALLTICKETS' FROM tickets;";
        $totalOpentickets = "SELECT COUNT(*) AS 'ALLOPEN_TICKETS' FROM tickets WHERE Status ='Open';";
        $totalClosedtickets = "SELECT COUNT(*) AS 'ALLCLOSE_TICKETS' FROM tickets WHERE Status ='Closed';";
        $totalResolvedtickets = "SELECT COUNT(*) AS 'ALLRESOLVED_TICKETS' FROM tickets WHERE Status ='Resolved';";
        $totalAssignedtickets = "SELECT COUNT(*) AS 'ALLASSIGNED_TICKETS' FROM tickets WHERE Status ='Assigned';";  
        $totaltickets = (new Database())->query($totaltickets);
        $totalOpentickets = (new Database())->query($totalOpentickets);
        $totalClosedtickets = (new Database())->query($totalClosedtickets);
        $totalResolvedtickets = (new Database())->query($totalResolvedtickets);
        $totalAssignedtickets = (new Database())->query($totalAssignedtickets);
        $html .= "<br>";
        $html .= '<h4>TOTAL TICKETS: '.$totaltickets[0]['ALLTICKETS'].'</h4>';
        $html .= '<h4>TOTAL OPEN TICKETS: '.$totalOpentickets[0]['ALLOPEN_TICKETS'].'</h4>';
        $html .= '<h4>TOTAL CLOSED TICKETS: '.$totalClosedtickets[0]['ALLCLOSE_TICKETS'].'</h4>';
        $html .= '<h4>TOTAL RESOLVED TICKETS: '.$totalResolvedtickets[0]['ALLRESOLVED_TICKETS'].'</h4>';
        $html .= '<h4>TOTAL ASSIGNED TICKETS: '.$totalAssignedtickets[0]['ALLASSIGNED_TICKETS'].'</h4>';
        $this->createTablePdf("L", $title, $html);
    }


    public function getPerformanceReport() {
       $title = "Performance Report";
        $headers = ["User Number", "Name","Rating of Support"];
        $sql = "SELECT 
        users.User_Id, users.Lastname,users.Firstname,users.Middlename,SUM(Rating)/COUNT(Rating) AS 'RATING OF SUPPORT'          
        FROM tickets
            INNER  JOIN users ON tickets.SupportedBy = users.User_Id
        WHERE Role = 1 OR 2
        GROUP BY users.User_Id
        ORDER BY users.Lastname;";
        $data = (new Database())->query($sql);

        $keys = ["User_Id", "Lastname", "Firstname", "Middlename","RATING OF SUPPORT"];

        $html = '<table cellspacing="0" cellpadding="1" border="1">';
        
        $html .= '<tr>';

        foreach($headers as $header) {
            $html .= '<td align="center"><b>'.$header.'</b></td>';
        }
        $html .= '</tr>';

        foreach($data as $items) {
            $html .= '<tr>';
            // for($counter=0;$counter<count($keys);$counter++) {
       
                $html .= '<td align="center">'.$items['User_Id'].'</td>';
                $html .= '<td align="center">'.$items['Lastname'].", ".$items['Firstname']." ".$items['Middlename'].'</td>';
                $html .= '<td align="center">'.$items['RATING OF SUPPORT'].'</td>';


            // }
            $html .= '</tr>';
        }

        $html .= '</table>';
        $html .= '<br><br>';
        $this->createTablePdf("P", $title, $html);
    }

     public function getSummarySupported() {
       $title = "Summary Supported Tickets Report";
        $headers = ["Name", "Total Closed","Total Resolved","Total Assigned","Total Open"];
        $sql = "SELECT
             users.Lastname,users.Firstname,users.Middlename,
            SUM(Status= 'Closed') AS 'TOTAL CLOSED',
            SUM(Status= 'Resolved') AS 'TOTAL RESOLVED',
            SUM(Status= 'Assigned') AS 'TOTAL ASSIGNED',
            SUM(Status= 'Open') AS 'TOTAL OPEN'
            
        FROM tickets
            INNER  JOIN users ON tickets.SupportedBy = users.User_Id  
        GROUP BY users.User_Id
        ORDER BY users.Lastname;";

        $data = (new Database())->query($sql);

        $html = '<table cellspacing="0" cellpadding="1" border="1">';
        
        $html .= '<tr>';

        foreach($headers as $header) {
            $html .= '<td align="center"><b>'.$header.'</b></td>';
        }
        $html .= '</tr>';

        foreach($data as $items) {
            $html .= '<tr>';
            // for($counter=0;$counter<count($keys);$counter++) 
                $html .= '<td>'.$items['Lastname'].", ".$items['Firstname']." ".$items['Middlename'].'</td>';
                $html .= '<td align="center">'.$items['TOTAL CLOSED'].'</td>';
                $html .= '<td align="center">'.$items['TOTAL RESOLVED'].'</td>';
                $html .= '<td align="center">'.$items['TOTAL ASSIGNED'].'</td>';
                $html .= '<td align="center">'.$items['TOTAL OPEN'].'</td>';


            // }
            $html .= '</tr>';
        }

        $html .= '</table>';
        $html .= '<br><br>';
        $this->createTablePdf("P", $title, $html);
    }







}


//============================================================+
// END OF FILE
//============================================================+