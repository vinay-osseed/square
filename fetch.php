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

try {

    /* Calling the Square API to get a list of all the categories in the catalog. */
    $api_response = $client->getCatalogApi()->listCatalog();

    if ($api_response->isSuccess()) {
        $result = $api_response->getResult();
    } else {
        $errors = $api_response->getErrors();
        print_r($errors);
        echo "</pre>";
    }

    /* Printing the list of categories from the catalog. */
    echo "<pre>Available category :-<br>";
    foreach ($result->getObjects() ?? [] as $key => $value) {
        $categories = $value->jsonSerialize();
        print_r($categories['category_data']->jsonSerialize());
    }
    echo "</pre>";

} catch (ApiException $e) {
    echo "ApiException occurred: <b/>";
    echo $e->getMessage() . "<p/>";
}
