<?php
require_once 'config.php';

/**
 * Class easy_store
 * @author Abhishek Kumar Pathak <officialabhishekpathak@gmail.com>
 */
class easy_store
{
    private static PDO $pdo;
    public static array $errors;

    private static function connect()
    {
        $dns = "mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_NAME;
        self::$pdo = new PDO($dns, DB_USERNAME, DB_PASSWORD);
        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    private static function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    private static function isJSONContentType(): bool
    {
        return $_SERVER["CONTENT_TYPE"] === 'application/json';
    }


    private static function required_fields():array
    {
        global $fields;
        $data = [];
        foreach ($fields as $key => $value){
            if($value){
                $data[] = $key;
            }
        }
        return $data;
    }


    private static function getData(): array
    {
        global $fields;
        if(empty($fields)){
            die(self::response(["message" => "Add fields to the config.php file (No field provided)"]));
        }

        $fields_key = array_keys($fields);
        $data = [];

        $reqData = $_REQUEST;
        if(self::isPost() && self::isJSONContentType()){
            $jsonData = file_get_contents("php://input");
            $jsonData = json_decode($jsonData, true);
            $reqData = $reqData + $jsonData;
        }
        foreach ($reqData as $key => $value){
            if(in_array($key, $fields_key)){
                if(is_numeric($value)){
                    $data[$key] = (int)$value;
                }else {
                    $data[$key] = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_NULL_ON_FAILURE);
                }
            }
        }

        if(empty($data)){
            die(self::response(["message" => "No data provided"]));
        }

        return $data;
    }


    private static function validate(): bool
    {
        $fields = self::required_fields();
        foreach ($fields as $key){
            if( !isset($_REQUEST[$key]) || $_REQUEST[$key] == ""){
                self::$errors[$key] = "$key is a required field";
            }
        }
        return empty(self::$errors);
    }




    private static function save()
    {
        $data = self::getData();

        $keys = array_keys($data);
        $params = array_map(fn($attr) => ":$attr", $keys);
        $query = "INSERT INTO ".DB_TABLE_NAME." (".implode(",", $keys).") VALUES (".implode(",", $params).");";

        try {
            self::connect();
            $stmt = self::$pdo->prepare($query);
            foreach ($keys as $key){
                $stmt->bindValue(":$key", $data[$key]);
            }
            if($stmt->execute()){
                return self::response(["message" => "Data saved successfully"], 1);
            }
            return self::response(["message" => $stmt->errorInfo()]);
        } catch (PDOException $e) {
            return self::response(["message" => $e->getMessage()]);
        }
    }



    public static function response($data, $is_success = 0){
        header('Content-Type: application/json');
        $response = [
            "status" => $is_success,
            "data" => $data
        ];
        return json_encode($response);
    }

    public static function run(){

        if(self::validate()){
            return self::save();
        }
        return self::response(self::$errors);
    }

}



echo easy_store::run();


