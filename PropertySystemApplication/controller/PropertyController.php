<?php

class PropertyController
{
    public function viewProperties()
    {
        $conn = $this->connectToDatabase();
        $property = new PropertyModel($conn);

        $localDatabase = $property->read();

        return json_encode($localDatabase);
    }

    public function removeProperty($uuid){
        $conn = $this->connectToDatabase();
        $property = new PropertyModel($conn);

        return $property->delete($uuid);
    }

    public function syncLocalDatabaseWithRemoteAPI()
    {
        $conn = $this->connectToDatabase();
        $property = new PropertyModel($conn);

        $localDatabase = $property->read();
        $remoteDatabase = json_decode($this->fetchPropertiesFromAPI(1), true);

        $this->insertOrUpdateLocalDatabaseWhenRemoteDatabaseChanged($property, $localDatabase, $remoteDatabase);

        $numberOfPropertyPages = $remoteDatabase['last_page'];
        if ($numberOfPropertyPages > 1) {
            for ($i = 2; $i < $numberOfPropertyPages + 1; $i++) {
                $remoteDatabase = json_decode($this->fetchPropertiesFromAPI($i), true);

                $this->insertOrUpdateLocalDatabaseWhenRemoteDatabaseChanged($property, $localDatabase, $remoteDatabase);
            }
        }
    }

    private function connectToDatabase()
    {
        $dbConnection = new DatabaseConnection();
        $conn = $dbConnection->connect();

        return $conn;
    }

    private function fetchPropertiesFromAPI($pageNumber)
    {
        $curlSession = curl_init();
        curl_setopt($curlSession, CURLOPT_URL, "http://trialapi.craig.mtcdevserver.com/api/properties?api_key=3NLTTNlXsi6rBWl7nYGluOdkl2htFHug&page[number]={$pageNumber}&page[size]=100");
        curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curlSession);
        curl_close($curlSession);

        return $response;
    }

    private function insertOrUpdateLocalDatabaseWhenRemoteDatabaseChanged($property, $localDatabase, $remoteDatabase)
    {
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
