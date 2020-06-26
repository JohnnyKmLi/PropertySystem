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
        $query = 'SELECT *
                      FROM properties
                      INNER JOIN property_types
                      ON properties.property_type_id = property_types.id
                      ORDER BY properties.updated_at DESC';

        $statement = $this->conn->prepare($query);
        $statement->execute();

        return $statement;
    }

    public function update($property)
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
        $statement->execute();

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
}
