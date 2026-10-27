<?php
header('Content-Type: application/json; charset=utf-8');
function respond($data,int $status=200): never { http_response_code($status); echo json_encode($data,JSON_UNESCAPED_UNICODE|JSON_INVALID_UTF8_SUBSTITUTE); exit; }

try {
require_once __DIR__.'/../../auth.php';
if(empty($_SESSION['student_id'])) respond(['success'=>false,'message'=>'Please log in.'],401);
$method=$_SERVER['REQUEST_METHOD'];
if($method==='GET'){$res=$conn->query('SELECT event_id AS id,title,category,event_date,venue AS location,description FROM events ORDER BY event_date');respond(['success'=>true,'events'=>$res->fetch_all(MYSQLI_ASSOC)]);}
if(($_SESSION['role']??'')!=='admin')respond(['success'=>false,'message'=>'Administrator access required.'],403);
$input=json_decode(file_get_contents('php://input'),true);if(!is_array($input))$input=$_POST;
if($method==='POST'){$id=(int)($input['id']??0);$title=trim($input['title']??'');$category=trim($input['category']??'');$date=trim($input['event_date']??'');$location=trim($input['location']??'');$description=trim($input['description']??'');if($title===''||$category===''||$location===''||!preg_match('/^\d{4}-\d{2}-\d{2}$/',$date))respond(['success'=>false,'message'=>'Title, category, valid date and location are required.'],422);
 if($id){$s=$conn->prepare('UPDATE events SET title=?,category=?,event_date=?,venue=?,description=? WHERE event_id=?');$s->bind_param('sssssi',$title,$category,$date,$location,$description,$id);$s->execute();$changed=$s->affected_rows;$s->close();if(!$changed){$c=$conn->prepare('SELECT event_id FROM events WHERE event_id=?');$c->bind_param('i',$id);$c->execute();$exists=$c->get_result()->num_rows>0;$c->close();if(!$exists)respond(['success'=>false,'message'=>'Event not found.'],404);}log_action($conn,(int)$_SESSION['student_id'],'api_event_update',$title);respond(['success'=>true,'message'=>'Event updated.']);}
 $s=$conn->prepare('INSERT INTO events(title,category,event_date,venue,description) VALUES(?,?,?,?,?)');$s->bind_param('sssss',$title,$category,$date,$location,$description);$s->execute();$newId=$conn->insert_id;$s->close();log_action($conn,(int)$_SESSION['student_id'],'api_event_create',$title);respond(['success'=>true,'message'=>'Event added.','id'=>$newId],201);
}
if($method==='DELETE'){$id=(int)($input['id']??$_GET['id']??0);if($id<1)respond(['success'=>false,'message'=>'Valid event ID required.'],422);$s=$conn->prepare('DELETE FROM events WHERE event_id=?');$s->bind_param('i',$id);$s->execute();$deleted=$s->affected_rows;$s->close();if(!$deleted)respond(['success'=>false,'message'=>'Event not found.'],404);log_action($conn,(int)$_SESSION['student_id'],'api_event_delete','Event id '.$id);respond(['success'=>true,'message'=>'Event deleted.']);}
header('Allow: GET, POST, DELETE');respond(['success'=>false,'message'=>'Method not allowed.'],405);

} catch (Throwable $e) {
    // Log the real error to the PHP error log; return valid JSON to Fetch.
    error_log('Practical 13 API error: ' . $e->getMessage());
    respond([
        'success' => false,
        'message' => 'API/database error. Check the PHP error log and verify the events table columns.'
    ], 500);
}
