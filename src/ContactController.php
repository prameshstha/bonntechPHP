<?php
class ContactController
{
    public function __construct(private ContactGateway $gateway){

    }
    public function processRequest(string $method, ?string $id): void{
       
        if($id){
            $this->processResourceRequest($method, $id);
        }else{
            $this->processCollectionRequest($method);
        }
    }
    private function processResourceRequest(string $method, string $id):void{
        $contact = $this->gateway->get($id);
        if(!$contact){
            http_response_code(404);
            echo json_encode(['message'=>"Contact not found"]);
            return;
        }
        switch($method){
            case "GET":
            echo json_encode($contact);
            break;
            case "PATCH":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                
                 $rows = $this->gateway->update($contact, $data);
                 http_response_code(200);
                 echo json_encode([
                    "message"=>"Contact updated", "id"=>$id,
                "rows"=>$rows]);
                 break;
            case "DELETE":
                $rows = $this->gateway->delete($id);
                http_response_code(202);
                echo json_encode(["message"=> "Contact deleted",
                "rows"=> $rows]);
                break;

            default:
            http_response_code(405);
            header("Allow: GET, PATCH, DELETE");

        }
        

    }
    private function processCollectionRequest(string $method):void{
        switch($method){
            case "GET":
                $json = [];
                $json = json_encode($this->gateway->getAll());
                echo $json;
                break;
            case "POST":
                 $data = (array) json_decode(file_get_contents("php://input"), true);
                $errors = $this->getValidationErrors($data);
                
                if ( ! empty($errors)) {
                    http_response_code(422);
                    echo json_encode(["errors" => $errors]);
                    break;
                }
                
                 $id = $this->gateway->create($data);
                 http_response_code(201);
                 echo json_encode([
                    "message"=>"Contact created", "id"=>$id]);
                 break;
            default:
            http_response_code(405);
            header("Allow: GET, POST");
        }
        
    }


        private function getValidationErrors(array $data, bool $is_new = true): array
    {
        $errors = [];
        
        if ($is_new && empty($data["first_name"])) {
            $errors[] = "Firstname is required";
        }
        if ($is_new && empty($data["last_name"])) {
            $errors[] = "Lastname is required";
        }
        if ($is_new && empty($data["mobile"])) {
            $errors[] = "mobile is required";
        }
        
     
        
        return $errors;
    }
}