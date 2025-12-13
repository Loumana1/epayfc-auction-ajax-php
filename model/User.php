

<?php
class User {
    //Je prends tt les proprotes pour moment 
    //Verifier ce que les autres ont mis dans leur Model User 
    // faudra merge rapidement pour que ca soit pas un bouzouf
    public $id;
    public $full_name;
    public $pseudo;
    public $email;
    public $role;
    public $picture_path;
    public $iban;

    public function __construct(
        int $id,
        string $full_name,
        string $pseudo,
        string $email,
        string $role,
        ?string $picture_path,
        ?string $iban
    ) {
        $this->id = $id;
        $this->full_name = $full_name;
        $this->pseudo = $pseudo;
        $this->email = $email;
        $this->role = $role;
        $this->picture_path = $picture_path;
        $this->iban = $iban;
    }


    public function get_Id(): int {
        return $this->id;
    }

    public function get_Pseudo(): string {
        return $this->pseudo;
    }




}