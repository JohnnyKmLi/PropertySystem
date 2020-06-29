<?php
class PropertyModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function read()
    {
        $query = 'SELECT p.id, p.uuid, p.county, p.country, p.town, p.description, p.address, p.image_full, p.image_thumbnail, p.latitude, p.longitude, p.num_bedrooms, p.num_bathrooms, p.price, p.type, p.created_at, p.updated_at, pt.title AS property_type, pt.description AS property_type_description, pt.created_at AS property_type_created_at, pt.updated_at AS property_type_updated_at
              FROM properties p
              INNER JOIN property_types pt
              ON p.property_type_id = pt.id
              ORDER BY p.updated_at DESC';

        $statement = $this->conn->prepare($query);
        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function update($property)
    {
        $this->updatePropertyTypesTable($property);
        $this->updatePropertiesTable($property);
    }

    public function insert($property)
    {

        $query = "INSERT INTO property_types (id, title, description, created_at, updated_at)
                  SELECT *
                  FROM (
                    SELECT {$property['property_type']['id']} AS id, '{$property['property_type']['title']}' AS title, '{$property['property_type']['description']}' AS description, '{$property['property_type']['created_at']}' AS created_at, '{$property['property_type']['updated_at']}' AS updated_at
                  ) AS temp
                  WHERE NOT EXISTS (
                    SELECT id
                    FROM property_types
                    WHERE id = '{$property['property_type']['id']}' AND updated_at >= '{$property['property_type']['updated_at']}' 
                  ) LIMIT 1";
        $statement = $this->conn->prepare($query);
        $statement->execute();

        //required as some towns had an apostrophe
        $county = str_replace("'", "''", $property['county']);
        $country = str_replace("'", "''", $property['country']);
        $town = str_replace("'", "''", $property['town']);
        $description = str_replace("'", "''", $property['description']);
        $address = str_replace("'", "''", $property['address']);

        $query = "INSERT INTO properties (uuid, property_type_id, county, country, town, description, address, image_full, image_thumbnail, latitude, longitude, num_bedrooms, num_bathrooms, price, type, created_at, updated_at)
                  SELECT *
                  FROM (
                    SELECT
                        '{$property['uuid']}' AS uuid,
                        {$property['property_type_id']} AS property_type_id,
                        '{$county}' AS county,
                        '{$country}' AS country,
                        '{$town}' AS town,
                        '{$description}' AS description,
                        '{$address}' AS address,
                        '{$property['image_full']}' AS image_full,
                        '{$property['image_thumbnail']}' AS image_thumbnail,
                        {$property['latitude']} AS latitude,
                        {$property['longitude']} AS longitude,
                        {$property['num_bedrooms']} AS num_bedrooms,
                        {$property['num_bathrooms']} AS num_bathrooms,
                        {$property['price']} AS price,
                        '{$property['type']}' AS type,
                        '{$property['created_at']}' AS created_at,
                        '{$property['updated_at']}' AS updated_at
                  ) AS temp
                  WHERE NOT EXISTS (
                    SELECT uuid
                    FROM properties
                    WHERE uuid = '{$property['uuid']}' AND updated_at >= '{$property['updated_at']}'
                  ) LIMIT 1";
        $statement = $this->conn->prepare($query);
        $result = $statement->execute();

        return $result;
    }

    public function delete($uuid)
    {
        $query = "DELETE FROM properties
                  WHERE properties.uuid = '{$uuid}'";

        $statement = $this->conn->prepare($query);
        $result = $statement->execute();

        return $result;
    }


    private function updatePropertyTypesTable($property)
    {
        if (array_key_exists('property_type', $property)) {
            $query = "UPDATE property_types
                SET
                    title = '{$property['property_type']['title']}',
                    description = '{$property['property_type']['description']}',
                    updated_at = '{$property['property_type']['updated_at']}'
                WHERE id = {$property['property_type']['id']}";
        } else {
            $query = "UPDATE property_types
                SET
                    title = '{$property['title']}',
                    description = '{$property['description']}',
                    updated_at = '{$property['updated_at']}'
                WHERE id = {$property['id']}";
        }

        $statement = $this->conn->prepare($query);
        $result = $statement->execute();

        return $result;
    }

    private function updatePropertiesTable($property)
    {
        $county = str_replace("'", "''", $property['county']);
        $country = str_replace("'", "''", $property['country']);
        $town = str_replace("'", "''", $property['town']);
        $description = str_replace("'", "''", $property['description']);
        $address = str_replace("'", "''", $property['address']);

        $query = "UPDATE properties
                  SET
                    property_type_id = {$property['property_type_id']},
                    county = '{$county}',
                    country = '{$country}',
                    town = '{$town}',
                    description = '{$description}',
                    address = '{$address}',
                    image_full = '{$property['image_full']}',
                    image_thumbnail = '{$property['image_thumbnail']}',
                    latitude = {$property['latitude']},
                    longitude = {$property['longitude']},
                    num_bedrooms = {$property['num_bedrooms']},
                    num_bathrooms = {$property['num_bathrooms']},
                    price = {$property['price']} ,
                    updated_at = '{$property['updated_at']}'
                  WHERE uuid = '{$property['uuid']}'";
        $statement = $this->conn->prepare($query);
        $result = $statement->execute();

        return $result;
    }
}
