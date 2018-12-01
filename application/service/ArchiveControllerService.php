<?php
/*******************************************************************
 * @discription API for Archive notes
 ********************************************************************/

header('Access-Control-Allow-Origin: *');
include "/var/www/html/codeigniter/application/service/DatabaseConnection.php";
require '/var/www/html/codeigniter/application/service/JWT.php';
/**
 * class Api Archive notes contoller methods
 */

class ArchiveControllerService
{
/**
 * @var string $connect PDO object
 */
/**
 * @var string $id id
 * @var string $email email
 */
    public $connect = "";
/**
 * @method constructor to establish the database connection
 * @return void
 */
    public function __construct()
    {
        $ref           = new DatabaseConnection();
        $this->connect = $ref->Connection();
    }
/**
 * @method archiveNote() to make notes archive
 * @return void
 */
    public function archiveNote($id, $email)
    {
        $headers = apache_request_headers();
        $token   = explode(" ", $headers['Authorization']);

        $reff = new JWT();
        if ($reff->verify($token[1])) {
            $ref           = new DatabaseConnection();
            $this->connect = $ref->Connection();
            $query         = "UPDATE notes SET isArchive = 'yes' where id = '$id'";
            $statement     = $this->connect->prepare($query);
            if ($statement->execute()) {
                /**
                 * @var string $query has query to select all notes
                 */
                $query = "SELECT * FROM notes where email = '$email' and isArchive = 'no' and isDeleted = 'no' order by dragId desc";
                /**
                 * @var string $statement holds statement object
                 */
                $statement = $this->connect->prepare($query);
                $statement->execute();
                /**
                 * @var array $arr to store result
                 */
                $arr = $statement->fetchAll(PDO::FETCH_ASSOC);
                /**
                 * returns json array response
                 */
                print json_encode($arr);
            } else {
                $data = array(
                    "error" => "202",
                );
                print json_encode($data);
            }
        } else {
            $data = array(
                "error" => "404",
            );
            /**
             * returns json array response
             */
            print json_encode($data);
        }
    }
/**
 * @method fetchArchiveNote() to fetch all  archived notes
 * @return void
 */
    public function fetchArchiveNote($email)
    {
        $headers       = apache_request_headers();
        $token         = explode(" ", $headers['Authorization']);
        $ref           = new DatabaseConnection();
        $this->connect = $ref->Connection();
        $reff          = new JWT();
        if ($reff->verify($token[1])) {
            /**
             * @var string $query has query to select all notes
             */
            $query = "SELECT * FROM notes where  email = '$email' and isArchive = 'yes' and isDeleted = 'no' order by dragId desc";
            /**
             * @var string $statement holds statement object
             */
            $statement = $this->connect->prepare($query);
            $ref       = $statement->execute();
            /**
             * @var array $arr to store result
             */
            $arr = $statement->fetchAll(PDO::FETCH_ASSOC);
            /**
             * returns json array response
             */
            print json_encode($arr);

        } else {
            $data = array(
                "error" => "404",
            );
            /**
             * returns json array response
             */
            print json_encode($data);

        }
    }
/**
 * @method fetchUnArchiveNote() fetch all  unarchive notes
 * @return void
 */
    public function fetchUnArchiveNote($email, $id)
    {
        $headers = apache_request_headers();
        $token   = explode(" ", $headers['Authorization']);
        $reff    = new JWT();
        if ($reff->verify($token[1])) {
            $ref           = new DatabaseConnection();
            $this->connect = $ref->Connection();
            /**
             * @var string $query has query to un archive the archived notes
             */
            $query     = "UPDATE notes SET  isArchive = 'no' where id = '$id'";
            $statement = $this->connect->prepare($query);
            if ($statement->execute()) {
                $reff = new ArchiveControllerService();
                $reff->fetchArchiveNotee($email);

            } else {
                $data = array(
                    "error" => "202",
                );
                /**
                 * returns json array response
                 */
                print json_encode($data);
            }

        } else {
            $data = array(
                "error" => "404",
            );
            /**
             * returns json array response
             */
            print json_encode($data);
        }
    }
/**
 * @method deleteArchiveNote() delete the archive notes
 * @return void
 */
    public function deleteArchiveNote($email, $id)
    {
        $headers = apache_request_headers();
        $token   = explode(" ", $headers['Authorization']);

        $reff = new JWT();
        if ($reff->verify($token[1])) {
            $ref           = new DatabaseConnection();
            $this->connect = $ref->Connection();
            /**
             * @var string $query has query to set the notes to be deleted
             */
            $query     = "UPDATE notes SET isDeleted = 'yes' where id = '$id'";
            $statement = $this->connect->prepare($query);
            if ($statement->execute()) {
                $reff = new ArchiveControllerService();
                $reff->fetchArchiveNotee($email);

            } else {
                $data = array(
                    "error" => "202",
                );
                /**
                 * returns json array response
                 */
                print json_encode($data);
            }

        } else {
            $data = array(
                "error" => "404",
            );
            /**
             * returns json array response
             */
            print json_encode($data);

        }

    }
/**
 * @method fetchArchiveNotee() fetch archive notes
 * @param email
 * @return void
 */
    public function fetchArchiveNotee($email)
    {
        $headers = apache_request_headers();
        $token   = explode(" ", $headers['Authorization']);
        $reff    = new JWT();
        if ($reff->verify($token[1])) {

            /**
             * @var string $query has query to select all notes
             */
            $query = "SELECT * FROM notes where email = '$email' and isArchive = 'yes' and isDeleted = 'no' order by dragId desc";
            /**
             * @var string $statement holds statement object
             */
            $statement = $this->connect->prepare($query);
            $statement->execute();
            /**
             * @var array $arr to store result
             */
            $arr = $statement->fetchAll(PDO::FETCH_ASSOC);
            /**
             * returns json array response
             */
            print json_encode($arr);

        } else {
            $data = array(
                "error" => "404",
            );
            /**
             * returns json array response
             */
            print json_encode($data);

        }
    }
}
