<?php
    class PropertyModel {
        private $conn;

        public function __construct($conn) {
            $this->conn = $conn;
        }

        public function read() {
            // $query = 'SELECT idproperty
            //           FROM property';

            // $statement = $this->conn->prepare($query);
            // $statement->execute();

            // return $statement;
        }

        // public function update($property) {
        //     echo "\n";
        //     echo "\n";
        //     var_dump($property['uuid']);

        //     $query = "UPDATE properties
        //               SET
        //                 uuid = {$property['uuid']}
        //                 "

        // }

        public function insert($property) {

            print_r("---------------------------------------------------------\n");
            print_r("{$property['property_type']['id']}\n");
            print_r("'{$property['property_type']['title']}'\n");
            print_r("{$property['property_type']['description']}\n");
            print_r("{$property['property_type']['created_at']}\n");
            print_r("{$property['property_type']['updated_at']}\n");
            print_r("---------------------------------------------------------\n");

            $query = "INSERT INTO property_types (id, title, description, created_at, updated_at)
                      SELECT *
                      FROM (
                        SELECT {$property['property_type']['id']} AS id, '{$property['property_type']['title']}' AS title, '{$property['property_type']['description']}' AS description, '{$property['property_type']['created_at']}' AS created_at, '{$property['property_type']['updated_at']}' AS updated_at
                      ) AS temp
                      WHERE NOT EXISTS (
                        SELECT id
                        FROM property_types
                        WHERE id = '{$property['property_type']['id']}'
                      ) LIMIT 1";

            // $query = "INSERT INTO `property_types` (
            //         id,
            //         title,
            //         description,
            //         created_at,
            //         updated_at
            //     )
            //     VALUES (
            //         '{$property['property_type']['id']}',
            //         '{$property['property_type']['title']}',
            //         '{$property['property_type']['description']}',
            //         '{$property['property_type']['created_at']}',
            //         '{$property['property_type']['updated_at']}'
            // )";
            $statement = $this->conn->prepare($query);
            $statement->execute();


            $query = "INSERT INTO properties (uuid, property_type_id, county, country, town, description, address, image_full, image_thumbnail, latitude, longitude, num_bedrooms, num_bathrooms, price, type, created_at, updated_at)
                      SELECT *
                      FROM (
                        SELECT
                            '{$property['uuid']}' AS uuid,
                            {$property['property_type_id']} AS property_type_id,
                            '{$property['county']}' AS county,
                            '{$property['country']}' AS country,
                            '{$property['town']}' AS town,
                            '{$property['description']}' AS description,
                            '{$property['address']}' AS address,
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
                      ) AS tmp
                      WHERE NOT EXISTS (
                        SELECT uuid
                        FROM properties
                        WHERE uuid = '{$property['uuid']}'
                      ) LIMIT 1";
            // $query = "INSERT INTO `properties` (
            //         uuid,
            //         property_type_id,
            //         county,
            //         country,
            //         town,
            //         description,
            //         address,
            //         image_full,
            //         image_thumbnail,
            //         latitude,
            //         longitude,
            //         num_bedrooms,
            //         num_bathrooms,
            //         price,
            //         type,
            //         created_at,
            //         updated_at
            //     )
            //     VALUES (
            //         '{$property['uuid']}',
            //         '{$property['property_type_id']}',
            //         '{$property['county']}',
            //         '{$property['country']}',
            //         '{$property['town']}',
            //         '{$property['description']}',
            //         '{$property['address']}',
            //         '{$property['image_full']}',
            //         '{$property['image_thumbnail']}',
            //         '{$property['latitude']}',
            //         '{$property['longitude']}',
            //         '{$property['num_bedrooms']}',
            //         '{$property['num_bathrooms']}',
            //         '{$property['price']}',
            //         '{$property['type']}',
            //         '{$property['created_at']}',
            //         '{$property['updated_at']}'
            //     )";
            $statement = $this->conn->prepare($query);
            $statement->execute();
        }
    }
?>