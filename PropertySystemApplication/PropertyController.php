<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include_once "Database/DatabaseConnection.php";
include_once "Model/PropertyModel.php";

class PropertyController
{

    public function synchLocalDatabaseWithRemoteAPI()
    {
        $conn = new Database();
        $db = $conn->connect();
        // $db = PropertyController::connectToDatabase();
        // $db = $this->connectToDatabase();
        $property = new PropertyModel($db);

        $localDatabase = ($property->read())->fetchAll();
        $remoteDatabase = json_decode(PropertyController::fetchPropertiesFromAPI(1), true);
        // $remoteDatabase = json_decode($this->fetchPropertiesFromAPI(1), true);

        //TODO: refractor out into its own function
        foreach ($remoteDatabase['data'] as $remoteProperty) {
            $exist = false;
            $needUpdate = false;
            foreach ($localDatabase as $localProperty) {
                if ($remoteProperty['uuid'] === $localProperty['uuid']) {
                    $exist = true;
                    if ($remoteProperty['updated_at'] > $localProperty['updated_at']) {
                        $needUpdate = true;
                    }
                    break;
                }
            }

            if ($exist === false) {
                $property->insert($remoteProperty);
            } else if ($needUpdate === true) {
                $property->update($remoteProperty);
            }
        }

        $numberOfPropertyPages = $remoteDatabase['last_page'];
        if ($numberOfPropertyPages > 1) {
            for ($i = 2; $i < $numberOfPropertyPages + 1; $i++) {
                $remoteDatabase = json_decode(PropertyController::fetchPropertiesFromAPI($i), true);
                // $remoteDatabase = json_decode($this->fetchPropertiesFromAPI($i), true);

                //TODO: refractor out into its own function
                foreach ($remoteDatabase['data'] as $remoteProperty) {
                    $exist = false;
                    $needUpdate = false;
                    foreach ($localDatabase as $localProperty) {
                        if ($remoteProperty['uuid'] === $localProperty['uuid']) {
                            $exist = true;
                            if ($remoteProperty['updated_at'] > $localProperty['updated_at']) {
                                $needUpdate = true;
                            }
                            break;
                        }
                    }

                    if ($exist === false) {
                        $property->insert($remoteProperty);
                    } else if ($needUpdate === true) {
                        $property->update($remoteProperty);
                    }
                }
            }
        }
    }

    // private function connectToDatabase() {
    //     $conn = new Database();
    //     $db = $conn->connect();

    //     return $db;
    // }

    private function fetchPropertiesFromAPI($pageNumber)
    {
        $curlSession = curl_init();
        curl_setopt($curlSession, CURLOPT_URL, "http://trialapi.craig.mtcdevserver.com/api/properties?api_key=3NLTTNlXsi6rBWl7nYGluOdkl2htFHug&page[number]={$pageNumber}&page[size]=100");
        curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curlSession);
        curl_close($curlSession);

        return $response;
    }
}
