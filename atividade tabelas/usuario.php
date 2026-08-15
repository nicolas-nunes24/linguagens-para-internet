<?php
class Usuario {
    private $id_usuario;
    private $nome;
    private $email;
    private $cargo;
    private $ativo; 

    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->ativo = true; 
    }

   
    public function getIdUsuario() { 
        return $this->id_usuario;
    }
    public function getNome() {
        return $this->nome;
    }
    public function getEmail() {
        return $this->email;
    }
    public function getCargo() {
        return $this->cargo;
    }
    public function getAtivo() {
        return $this->ativo;
    }

 
    public function setIdUsuario($id_usuario) { 
        $this->id_usuario = $id_usuario;
    }
    public function setNome($nome) {
        $this->nome = $nome;
    }
    public function setEmail($email) {
        $this->email = $email;
    }
    public function setCargo($cargo) {
        $this->cargo = $cargo;
    }
    public function setAtivo($ativo) {
        $this->ativo = $ativo;
    }


    public function save() {
        if ($this->id_usuario) { 
            $sql = "UPDATE Usuario SET Nome = :n, Email = :e, Cargo = :c, Ativo = :a WHERE Id_usuario = :id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':n'  => $this->nome,
                ':e'  => $this->email,
                ':c'  => $this->cargo,
                ':a'  => $this->ativo ? 1 : 0, // Ensure boolean is handled correctly
                ':id' => $this->id_usuario
            ]);
        } else {
            $sql = "INSERT INTO Usuario (Nome, Email, Cargo, Ativo) VALUES (:n, :e, :c, :a)";
            $stmt = $this->pdo->prepare($sql);
            $ok = $stmt->execute([
                ':n' => $this->nome,
                ':e' => $this->email,
                ':c' => $this->cargo,
                ':a' => $this->ativo ? 1 : 0
            ]);
            
            if ($ok) {
                $this->id_usuario = $this->pdo->lastInsertId(); 
            }
            return $ok;
        }
    }

    public function load($id_usuario) {
        $stmt = $this->pdo->prepare("SELECT * FROM Usuario WHERE Id_usuario = :id");
        $stmt->execute([':id' => $id_usuario]);
        
        if ($dados = $stmt->fetch(PDO::FETCH_ASSOC)) {
            
            $this->id_usuario = $dados['Id_usuario'];
            $this->nome       = $dados['Nome'];
            $this->email      = $dados['Email'];
            $this->cargo      = $dados['Cargo'];
            $this->ativo      = (bool) $dados['Ativo'];
            return true;
        }
        return false;
    }

    public function delete() {
        if (!$this->id_usuario) return false;
        
        // IMPLEMENTED SOFT DELETE: 
        // Instead of deleting the row, we deactivate the user so sample records don't break.
        $stmt = $this->pdo->prepare("UPDATE Usuario SET Ativo = FALSE WHERE Id_usuario = :id");
        
        $success = $stmt->execute([':id' => $this->id_usuario]);
        if ($success) {
            $this->ativo = false; // Update the object's state
        }
        return $success;
    }

    public static function all(PDO $pdo, $onlyActive = true) {
        $sql = $onlyActive ? "SELECT * FROM Usuario WHERE Ativo = TRUE" : "SELECT * FROM Usuario";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>