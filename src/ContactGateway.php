<?php

class ContactGateway
{
    private PDO $conn;
    
    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }
    
    public function getAll(): array
    {
        $sql = "SELECT *
                FROM contacts";
                
        $stmt = $this->conn->query($sql);
        
        $data = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            
            $data[] = $row;
        }
        
        return $data;
    }
    
    public function create(array $data): string
    {
        $sql = "INSERT INTO contacts (first_name, last_name, mobile, email, postcode)
                VALUES (:first_name, :last_name, :mobile, :email, :postcode)";
                
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(":first_name", $data["first_name"], PDO::PARAM_STR);
        $stmt->bindValue(":last_name", $data["last_name"], PDO::PARAM_STR);
        $stmt->bindValue(":mobile", $data["mobile"], PDO::PARAM_STR);
        $stmt->bindValue(":email", $data["email"]??"", PDO::PARAM_STR);
        $stmt->bindValue(":postcode", $data["postcode"]??"", PDO::PARAM_STR);
        
        $stmt->execute();
        
        return $this->conn->lastInsertId();
    }
    
    public function get(string $id): array | false
    {
        $sql = "SELECT *
                FROM contacts
                WHERE id = :id";
                
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        
        $stmt->execute();
        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $data;
    }
    
    public function update(array $current, array $new): int
    {
        $sql = "UPDATE contacts
                SET first_name = :first_name, last_name = :last_name, mobile = :mobile, email=:email,
                postcode=:postcode
                WHERE id = :id";
                
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(":first_name", $new["first_name"] ?? $current["first_name"], PDO::PARAM_STR);
        $stmt->bindValue(":last_name", $new["last_name"] ?? $current["last_name"], PDO::PARAM_STR);
        $stmt->bindValue(":mobile", $new["mobile"] ?? $current["mobile"], PDO::PARAM_STR);
        $stmt->bindValue(":email", $new["email"] ?? $current["email"], PDO::PARAM_STR);
        $stmt->bindValue(":postcode", $new["postcode"] ?? $current["postcode"], PDO::PARAM_STR);
        
        $stmt->bindValue(":id", $current["id"], PDO::PARAM_INT);
        
        $stmt->execute();
        
        return $stmt->rowCount();
    }
    
    public function delete(string $id): int
    {
        $sql = "DELETE FROM contacts
                WHERE id = :id";
                
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        
        $stmt->execute();
        
        return $stmt->rowCount();
    }
}











