<?php
include '../../config/Database.php';
require_once("Jwt_key.php");
require_once("Notification.php");
require_once("Trail.php");

class Ticket {

    private $jwt;
    private $notification;
    private $trail;
    private $today;

    public function __construct() {
        date_default_timezone_set("Asia/Manila");
        $this->today = date("Y-n-j H:i:s");
        $this->jwt = new JWT_Key();
        $this->notification = new Notification();
        $this->trail = new Trail();
    }

    public function addTicket($token, $data, $level)
    {
        $tokenArray = $this->jwt->decodeToken($token);
        $creatorId = $tokenArray->id;
        $status = "";
        $supportedId = null;
        $is_reassign = 0;
        $supports = [];
        $allowedSupports = [];
        $ticketCounts = [];
        $dateToday = date("Y-n-j");
        // GET ALL SUPPORT WHO HAS A CERTAIN CATEGORY
        $supports = $this->getCategorySupports($data['Category_Id']);
        if($supports) {
            $status = "Assigned";
            // GET COUNT OF TICKET FOR EVERY SUPPORT, ADD IT TO AN ARRAY
            $sqlTickets = "SELECT count(t.Ticket_Id) as TicketCount
                                from tickets t
                                INNER JOIN issues i ON i.Issue_Id = t.Issue
                                INNER JOIN priorities p on p.Priority_Id = i.Priority_Id
                                where t.SupportedBy = ?
                                AND Date(t.DateCreated) = ?
                                AND p.Level = ?";
            for($ctr=0; $ctr<sizeOf($supports); $ctr++) {
                $ticketCount = (new Database())->query($sqlTickets, [$supports[$ctr]['Support_Id'], $dateToday, $level],'select');
                $supports[$ctr]["TicketCount"] = $ticketCount[0]["TicketCount"];
                $ticketCounts[] = ($ticketCount[0]["TicketCount"]);
            }
            // GETTING THE SMALLEST TICKET COUNT
            $smallest = min($ticketCounts);
            //GETTING SUPPORTS WITH THE APPROPRIATE SMALLEST TICKET COUNT
            for($ctr=0; $ctr<sizeOf($supports); $ctr++) {
                if($supports[$ctr]["TicketCount"] == $smallest) {
                    $allowedSupports[] = $supports[$ctr]["Support_Id"];
                }
            }
            //RANDOM INDEX OF THE ALLOWEDSUPPORTS ARRAY
            $supportedId = $allowedSupports[rand(0, sizeOf($allowedSupports)-1)];
        } else {
            $status = "Pending";
            $is_reassign = 1;
        }
        $sql = "INSERT INTO tickets(Issue, Description, CreatedBy, SupportedBy, DateCreated, Status, Is_Reassign) VALUE(?, ?, ?, ?, ?, ?, ?)";
		$query = (new Database())->query($sql, [
			$data["Issue"],
			$data["Description"],
            $creatorId,
			$supportedId,
            $this->today,
			$status,
            $is_reassign
		], 'insert');
        $url = "/sts/tickets/".$query["insert_id"];
        $notificationData = [$supportedId, "A new ticket has been added", $url, "view-supported-tickets"];
        $this->notification->insertNotification($notificationData);
        $this->trail->insertTrail($creatorId, $query["insert_id"], "add");
		return $query;
    }

    public function getCategorySupports($category_id) {
        $sqlSupports = "SELECT DISTINCT s.Support_Id 
                                FROM support_preferences s
                                WHERE s.Category_Id = ?
                                ORDER BY s.Support_Id;";
        $supports = (new Database())->query($sqlSupports, [$category_id],'select');
        return $supports;
    }

    public function getAllTickets() {
    	$sql = "SELECT t.*, i.Issue as IssueName, i.Description as IssueDescription,
                c.Category_Id, c.Name, c.Description as CategoryDescription,
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
                ORDER BY t.Ticket_Id desc;";
        $res = (new Database())->query($sql);
        $res = $this->getCustomDates($res);
        return $res;
    }

    public function getSupportedTickets($token) {
        $token_array = $this->jwt->decodeToken($token);
        $userId = $token_array->id;
    	$sql = "SELECT t.*, i.Issue as IssueName, i.Description as IssueDescription,
                c.Category_Id, c.Name, c.Description as CategoryDescription,
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
                where t.SupportedBy = $userId
                ORDER BY t.Ticket_Id desc;";
        $res = (new Database())->query($sql);
        $res = $this->getCustomDates($res);
        return $res;
    }

    public function getIssuedTickets($token) {
        $token_array = $this->jwt->decodeToken($token);
        $userId = $token_array->id;
    	$sql = "SELECT t.*, i.Issue as IssueName, i.Description as IssueDescription,
                c.Category_Id, c.Name, c.Description as CategoryDescription,
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
                where t.CreatedBy = $userId
                ORDER BY t.Ticket_Id desc;";
        $res = (new Database())->query($sql);
        $res = $this->getCustomDates($res);
        return $res;
    }

    private function getCustomDates($res) {
        $currentDate = new DateTime();
        for($ctr=0;$ctr<sizeOf($res);$ctr++) {
            $addTime = "+".$res[$ctr]["Days"]." days, +".$res[$ctr]["Hours"]." hours, +".$res[$ctr]["Minutes"]." minutes";
            $res[$ctr]["Scheduled_Date"] = date("Y-n-j H:i:s", strtotime($addTime, strtotime($res[$ctr]["DateCreated"])));
            $scheduledDate = new DateTime($res[$ctr]["Scheduled_Date"]);
            $interval = $currentDate->diff($scheduledDate);
            if($interval->format("%y")!=0)
                $res[$ctr]["time"] = $interval->format('%y Year/s');
            else if($interval->format("%m")!=0)
                $res[$ctr]["time"] = $interval->format('%m Month/s');
            else if($interval->format("%d")!=0)
                $res[$ctr]["time"] = $interval->format('%a Day/s');
            else if($interval->format("%h")!=0)
                $res[$ctr]["time"] = $interval->format('%h Hr/s');
            else if($interval->format("%i")!=0)
                $res[$ctr]["time"] = $interval->format('%i Min/s');

            if($scheduledDate > $currentDate) { 
                // within the date
                $res[$ctr]["isWithin"] = 1;
            } else {
                $res[$ctr]["isWithin"] = 0;
            }
            //Last Updated
            $dateModified = new DateTime($res[$ctr]["DateModified"]);
            $lastUpdated = $currentDate->diff($dateModified);
            if($lastUpdated->format("%d")!=0)
                $res[$ctr]["lastUpdated"] = $lastUpdated->format('%a Day/s');
            else if($lastUpdated->format("%h")!=0)
                $res[$ctr]["lastUpdated"] = $lastUpdated->format('%h Hr/s');
            else if($lastUpdated->format("%i"))
                $res[$ctr]["lastUpdated"] = $lastUpdated->format('%i Min/s');
            else 
                $res[$ctr]["lastUpdated"] = $lastUpdated->format('%s Sec/s');
            
        }
        return $res;
    }

    // public function getLastUpdated($res, $ctr) {
    //     $currentDate = new DateTime();
    //     $dateModified = new DateTime($res[$ctr]["DateModified"]);
    //     $lastUpdated = $currentDate->diff($dateModified);
    //     if($lastUpdated->format("%d")!=0)
    //         $res[$ctr]["lastUpdated"] = $lastUpdated->format('%a Day/s');
    //     else if($lastUpdated->format("%h")!=0)
    //         $res[$ctr]["lastUpdated"] = $lastUpdated->format('%h Hr/s');
    //     else if($lastUpdated->format("%i"))
    //         $res[$ctr]["lastUpdated"] = $lastUpdated->format('%i Min/s');
    //     else 
    //         $res[$ctr]["lastUpdated"] = $lastUpdated->format('%s Sec/s');
    //     return $res;
    // }

    public function getTicketComments($ticket_id) {
        $sql = "SELECT Comment, DateCreated, users.Firstname, users.Middlename, users.Lastname, users.Img_Name
                    FROM comments
                    INNER JOIN users on users.User_Id = comments.User_Id
                    WHERE Ticket_Id = ?
                    ORDER BY comments.DateCreated asc";
        $res = (new Database())->query(
            $sql,
            [$ticket_id],
            'select'
        );
        return $res;
    }
    
    public function getTotalTickets($filteredDate, $type, $token) {
        if($type!="All") {
            $tokenArray = $this->jwt->decodeToken($token);
            $id = $tokenArray->id;
        }
        $sql = "SELECT t.*, d.* FROM tickets t 
                    INNER JOIN users u on t.CreatedBy = u.User_Id
                    INNER JOIN departments d on u.Department_Id = d.Department_Id ";
        if($filteredDate=="today")
            $sql .= "WHERE CAST(t.DateCreated as Date) = CAST(NOW() as DATE) ";
        else if($filteredDate=="yesterday")
            $sql .= "WHERE CAST(t.DateCreated as Date) >= CAST(DATE_SUB(NOW(),INTERVAL 1 DAY) as DATE) ";
        else if($filteredDate=="last 7 days")
            $sql .= "WHERE CAST(t.DateCreated as Date) >= CAST(DATE_SUB(NOW(),INTERVAL 7 DAY) as DATE) ";
        else if($filteredDate=="last month")
            $sql .= "WHERE CAST(t.DateCreated as Date) >= CAST(DATE_SUB(NOW(),INTERVAL 1 MONTH) as DATE) ";
        else if($filteredDate=="last year")
            $sql .= "WHERE CAST(t.DateCreated as Date) >= CAST(DATE_SUB(NOW(),INTERVAL 1 YEAR) as DATE) ";

        if($type=="Supported") {
            $sql .= "AND t.SupportedBy = ". $id;
        } else if($type=="Issued") {
            $sql .= "AND t.CreatedBy = ". $id;
        }

        $res = (new Database())->query(
            $sql,
            [],
            'select'
        );
        return $res;
    }

    public function getSingleTicket($ticketId) {
        $sql = "SELECT t.*, i.Issue as IssueName, i.Description as IssueDescription,
                c.Category_Id, c.Name, c.Description as CategoryDescription,
                p.*,
                created.Lastname as CreatedLastname, created.Firstname as CreatedFirstname,
                created.Middlename as CreatedMiddlename, created.Company_Email as CreatedEmail, poscreated.Position_Name as CreatedPosition,
                supported.Lastname as SupportedLastname, supported.Firstname as SupportedFirstname, supported.Middlename as SupportedMiddlename, supported.Company_Email as SupportedEmail, p1.Position_Name as SupportedPosition, supported.Img_Name as SupporterImage
                FROM tickets t
                LEFT JOIN issues i on i.Issue_Id = t.Issue
                LEFT JOIN categories c on c.Category_Id = i.Category_Id
                LEFT JOIN priorities p on p.Priority_Id = i.Priority_Id
                LEFT JOIN users created on created.User_Id = t.CreatedBy
                LEFT JOIN users supported on supported.User_Id = t.SupportedBy
                LEFT JOIN positions poscreated on poscreated.Position_Id = created.Position_Id
                LEFT JOIN positions p1 on p1.Position_Id = supported.Position_Id
                where t.Ticket_Id = $ticketId;";
        $res = (new Database())->query($sql);
        if($res) {
            $res[0]["Solution"] = ($res[0]["Solution"]==null)? "" : $res[0]["Solution"];
            $res[0]["HourCreated"] = date('G', strtotime($res[0]["DateCreated"]));
            $res[0]["MinuteCreated"] = date('i', strtotime($res[0]["DateCreated"]));
            $res[0]["HourClosed"] = ($res[0]["DateClosed"]!="")? date('G', strtotime($res[0]["DateClosed"])): "";
            $res[0]["MinuteClosed"] = ($res[0]["DateClosed"]!="")? date('i', strtotime($res[0]["DateClosed"])): "";
        }
        // print_r($res);
        return $res;
    }

    public function deleteTicket($ticketId) {
    	$sql = "DELETE from tickets where Ticket_Id = $ticketId ";
        $res = (new Database())->query($sql);
    }

    public function updateTicket($id, $data)
    {
        $createdDate = date('Y-n-j', strtotime($data["DateCreated"]));
        $createdDate = $createdDate." ".$data["HourCreated"].":".$data["MinuteCreated"];

        $closedDate = $data["DateClosed"]!=""? date('Y-n-j', strtotime($data["DateClosed"])): null;
        $closedDate = $closedDate!=null? $closedDate." ".$data["HourClosed"].":".$data["MinuteClosed"]: null;

        $sql = " UPDATE tickets 
                SET Issue = ?,  Description= ?, Solution=?, CreatedBy= ?, SupportedBy= ?, DateCreated= ?, DateClosed = ?, Status = ?  
                WHERE Ticket_Id = ? ";
        $usersQuery = (new Database())->query(
            $sql,
            [ $data['Issue'] ,$data['Description'], $data['Solution'] , $data['CreatedBy'], $data['SupportedBy'], 
            $createdDate, $closedDate, $data["Status"], $id ],
            'update'
        );
        return $usersQuery;
    }

    public function updateTicketStatus($id, $status, $solution)
    {
        $array = [];
        if($status=="Open") {
            $sql = "UPDATE tickets 
                        SET Status = ?, Is_Reassign = ?, Reassignment_Reason = ?
                        WHERE Ticket_Id = ? ";
            $array = [$status, 0, null, $id];
            $message = "Ticket #".$id." status have changed into ".$status;
            $this->notification->getToBeNotifiedId($id, $message, "issuer");
            $this->trail->getNameViaUpdate($id, "support", $status);
        } else if($status=="Resolved") {
            $sql = "UPDATE tickets 
                        SET Status = ?, Is_Reassign = ?, Reassignment_Reason = ?, Reopen_Reason = ?, Solution = ? 
                        WHERE Ticket_Id = ? ";
            $array = [$status, 0, null, null, $solution, $id];
            $message = "Ticket #".$id." status have changed into ".$status;
            $this->notification->getToBeNotifiedId($id, $message, "issuer");
            $this->trail->getNameViaUpdate($id, "support", $status);
        }  else if($status=="Closed") {
            $sql = "UPDATE tickets 
                    SET Status = ?, DateClosed = ?, Is_Reassign = ?, Reassignment_Reason = ?, Reopen_Reason = ?, Solution = ? 
                    WHERE Ticket_Id = ? ";
            $array = [$status, $this->today, 0, null, null, $solution, $id];
            $message = "Ticket #".$id." status have changed into ".$status;
            $this->notification->getToBeNotifiedId($id, $message, "support");
            $this->trail->getNameViaUpdate($id, "issuer", $status);
        } else {
            $sql = "UPDATE tickets 
                        SET Status = ?, Solution = ? 
                        WHERE Ticket_Id = ? ";
            $array = [$status, null, $id];
            $message = "Ticket #".$id." status have changed into ".$status;
            $this->notification->getToBeNotifiedId($id, $message, "issuer");
            $this->trail->getNameViaUpdate($id, "support", $status);
        }
        $ticketQuery = (new Database())->query($sql, $array, 'update');
        return $ticketQuery;
    }

    public function updateRating($id, $rate)
    {
        $sql = " UPDATE tickets 
                    SET Rating = ?
                    WHERE Ticket_Id = ? ";
        $ticketQuery = (new Database())->query(
            $sql,
            [ $rate, $id ],
            'update'
        );
        return $ticketQuery;
    }

    public function updateReassignment($id, $value)
    {
        $sql = " UPDATE tickets 
                    SET Is_Reassign = ?
                    WHERE Ticket_Id = ? ";
        $query = (new Database())->query(
            $sql,
            [ $value, $id ],
            'update'
        );
        return $query;
    }

    public function reassignUserTicket($ticketId, $supportedBy, $categoryId, $level)
    {
        $todayDate = date('Y-n-j');
        $is_reassign = 0;
        $supports = [];
        $allowedSupports = [];
        $ticketCounts = [];
        $sqlSupports = "SELECT  DISTINCT s.Support_Id 
                                FROM support_preferences s
                                WHERE s.Category_Id = ? 
                                AND s.Support_Id <> ?
                                ORDER BY s.Support_Id;";
        $supports = (new Database())->query($sqlSupports, [$categoryId, $supportedBy],'select');
        if($supports) {
            // GET COUNT OF TICKET FOR EVERY SUPPORT, ADD IT TO AN ARRAY
            $sqlTickets = "SELECT count(t.Ticket_Id) as TicketCount
                                from tickets t
                                INNER JOIN issues i ON i.Issue_Id = t.Issue
                                INNER JOIN priorities p on p.Priority_Id = i.Priority_Id
                                where t.SupportedBy = ?
                                AND Date(t.DateCreated) = ?
                                AND p.Level = ?;";
            for($ctr=0; $ctr<sizeOf($supports); $ctr++) {
                $ticketCount = (new Database())->query($sqlTickets, [$supports[$ctr]['Support_Id'], $todayDate, $level],'select');
                $supports[$ctr]["TicketCount"] = $ticketCount[0]["TicketCount"];
                $ticketCounts[] = ($ticketCount[0]["TicketCount"]);
            }
            // GETTING THE SMALLEST TICKET COUNT
            $smallest = min($ticketCounts);
            //GETTING SUPPORTS WITH THE APPROPRIATE SMALLEST TICKET COUNT
            for($ctr=0; $ctr<sizeOf($supports); $ctr++) {
                if($supports[$ctr]["TicketCount"] == $smallest) {
                    $allowedSupports[] = $supports[$ctr]["Support_Id"];
                }
            }
            //RANDOM INDEX OF THE ALLOWEDSUPPORTS ARRAY
            $url = "/sts/tickets/".$ticketId;
            $notificationData = [$supportedBy, "Your request for reassignment have been approved", $url, "view-supported-tickets"];
            $this->notification->insertNotification($notificationData);
            $this->trail->insertTrail($supportedBy, $ticketId, "reassignmentapproved");

            $supportedBy = $allowedSupports[rand(0, sizeOf($allowedSupports)-1)];
            
            $notificationData = [$supportedBy, "A new ticket has been added", $url, "view-supported-tickets"];
            $this->notification->insertNotification($notificationData);
            $this->trail->insertTrail($supportedBy, $ticketId, "reassignment");
        } else {
            $is_reassign = 1;
        }
        $sql = " UPDATE tickets 
                    SET SupportedBy = ?, Is_Reassign = ?
                    WHERE Ticket_Id = ? ";
        $query = (new Database())->query($sql,[ $supportedBy, $is_reassign, $ticketId ],'update');
        return $query;
    }

    public function insertComment($ticket_id, $comment, $token)
    {
        $tokenArray = $this->jwt->decodeToken($token);
        $user_id = $tokenArray->id;
        $sql = " INSERT INTO comments(Comment, User_Id, Ticket_Id) values (?, ?, ?) ";
        $ticketQuery = (new Database())->query(
            $sql,
            [ $comment, $user_id, $ticket_id ],
            'insert'
        );
        return $ticketQuery;
    }

    public function isTicketIssued($ticketid, $token) {
        $tokenArray = $this->jwt->decodeToken($token);
        $user_id = $tokenArray->id;
        $sql = "SELECT * FROM tickets 
                WHERE Ticket_Id = ?
                AND CreatedBy = ?;";
        $ticketQuery = (new Database())->query(
            $sql,
            [$ticketid, $user_id],
            'select'
        );
        if($ticketQuery) 
            return true;
        else
            return false;
    }

    public function isTicketSupported($ticketid, $token) {
        $tokenArray = $this->jwt->decodeToken($token);
        $user_id = $tokenArray->id;
        $sql = "SELECT * FROM tickets 
                WHERE Ticket_Id = ?
                AND SupportedBy = ?;";
        $ticketQuery = (new Database())->query(
            $sql,
            [$ticketid, $user_id],
            'select'
        );
        if($ticketQuery) 
            return true;
        else
            return false;
    }

    public function updateReassignmentReason($ticketid, $reason) {
        $sql = "UPDATE tickets 
                    SET Is_Reassign = ?, Reassignment_Reason = ?
                    WHERE Ticket_Id = ? ";
        $query = (new Database())->query($sql,[ 1, $reason, $ticketid ], 'update');
        $this->notification->getToBeNotifiedId($ticketid, "", "head");
    }

    public function reopeningOfTicket($ticketid, $reason) {
        $sql = "UPDATE tickets 
                    SET Status = ?, Reopen_Reason = ?
                    WHERE Ticket_Id = ? ";
        $query = (new Database())->query(
            $sql,
            [ "Open", $reason, $ticketid ],
            'update'
        );
        $message = "Ticket #".$ticketid." has been reopened.";
        $this->notification->getToBeNotifiedId($ticketid, $message, "support");
        $this->trail->getNameViaUpdate($ticketid, "issuer", "Open");
    }

    public function getIssuedSupportedRecentTickets($token) {
        $tokenArray = $this->jwt->decodeToken($token);
        $user_id = $tokenArray->id;
        $currentDate = new DateTime();
        $sql = "SELECT t1.*, i1.*, 'view-issued-tickets' AS 'Type'
                    FROM tickets t1
                    INNER JOIN issues i1 ON i1.Issue_Id = t1.Issue
                    WHERE t1.CreatedBy = ?
                    UNION
                    SELECT t2.*, i2.*, 'view-supported-tickets' AS 'Type'
                    FROM tickets t2
                    INNER JOIN issues i2 ON i2.Issue_Id = t2.Issue
                    WHERE t2.SupportedBy = ?
                    ORDER BY DateModified DESC";
        $res = (new Database())->query($sql, [$user_id, $user_id]);

        for($ctr=0;$ctr<sizeOf($res);$ctr++) {
            $dateModified = new DateTime($res[$ctr]["DateModified"]);
            $lastUpdated = $currentDate->diff($dateModified);
            if($lastUpdated->format("%d")!=0)
                $res[$ctr]["lastUpdated"] = $lastUpdated->format('%a Day/s');
            else if($lastUpdated->format("%h")!=0)
                $res[$ctr]["lastUpdated"] = $lastUpdated->format('%h Hr/s');
            else if($lastUpdated->format("%i"))
                $res[$ctr]["lastUpdated"] = $lastUpdated->format('%i Min/s');
            else 
                $res[$ctr]["lastUpdated"] = $lastUpdated->format('%s Sec/s');
        }
        return $res;
    }


}