<?php
class Guest
{
    private $id;
    private $photo;
    private $prix_max;
    private $shopping;
    private $gardening;
    private $chores;
    private $description;

    private $conn;

    public function __construct($database)
    {
        $this->conn = $database;
    }

    public function getGuests()
    {
        $sql = "SELECT * FROM guest;";
        $query = $this->conn->prepare($sql);
        $query->execute();

        return $query->fetchAll();
    }

    public function insertGuest($id)
    {
        $sql = "INSERT INTO guest (id, photo, prix_max, shopping, gardening, chores, description)
                VALUES (:pId, NULL, NULL, NULL, NULL, NULL, NULL);";

        $query = $this->conn->prepare($sql);
        $query->bindParam("pId", $id);

        return $query->execute();
    }

    public function updateGuest($id, $photo, $prix_max, $shopping, $gardening, $chores, $description)
    {
        $sql = "UPDATE guest
                SET photo = :pPhoto, prix_max = :pPrixMax, shopping = :pShopping, gardening = :pGardening, 
                    chores = :pChores, description = :pDescription
                WHERE id = :pId;";

        $query = $this->conn->prepare($sql);
        $query->bindParam("pId", $id);
        $query->bindParam("pPhoto", $photo);
        $query->bindParam("pPrixMax", $prix_max);
        $query->bindParam("pShopping", $shopping);
        $query->bindParam("pGardening", $gardening);
        $query->bindParam("pChores", $chores);
        $query->bindParam("pDescription", $description);

        return $query->execute();
    }

    public function getGuestById($id)
    {
        $sql = "SELECT * FROM guest WHERE id = :pId;";
        $query = $this->conn->prepare($sql);
        $query->bindParam("pId", $id);
        $query->execute();

        return $query->fetch();
    }

    public function deleteGuest($id)
    {
        $sql = "DELETE FROM guest WHERE id = :pId;";
        $query = $this->conn->prepare($sql);
        $query->bindParam("pId", $id);

        return $query->execute();
    }
}
