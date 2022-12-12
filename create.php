<?php

require 'vendor/autoload.php';

use Square\Environment;
use Square\Exceptions\ApiException;
use Square\SquareClient;

/* Creating a new instance of the SquareClient class. */
$client = new SquareClient([
    'accessToken' => getenv('SQUARE_ACCESS_TOKEN'),
    'environment' => Environment::SANDBOX,
]);

/* An array of parameters that will be used to create the category. */
$param = [
    'name' => 'Test Category',
];

try {
    /* Setting the name of the category. */
    $category_data = new \Square\Models\CatalogCategory();
    $category_data->setName($param['name']);

    /* Creating a new instance of the CatalogObject class with id. */
    $object = new \Square\Models\CatalogObject('CATEGORY', '#' . $param['name']);
    $object->setCategoryData($category_data);

    /* Creating a new instance of the UpsertCatalogObjectRequest class with idempotency_key. */
    $body = new \Square\Models\UpsertCatalogObjectRequest(uniqid('-', true), $object);

    /* Calling the `upsertCatalogObject` method of the `CatalogApi` class to add category. */
    $api_response = $client->getCatalogApi()->upsertCatalogObject($body);

    if ($api_response->isSuccess()) {
        /* Get the result of the API call and serializing it. */
        $result = $api_response->getResult();
        $categories = $result->getCatalogObject()->jsonSerialize();
        echo "<pre>Created category :-<br>";
        print_r($categories['category_data']->jsonSerialize());
        echo "</pre>";
    } else {
        $errors = $api_response->getErrors();
        echo "<pre>";
        print_r($errors);
        echo "</pre>";
    }

} catch (ApiException $e) {
    echo "ApiException occurred: <b/>";
    echo $e->getMessage() . "<p/>";
}
