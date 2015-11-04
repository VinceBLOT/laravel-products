<?php

return [

    /*
     * Product number settings
     */
    'productNumber' => [

        /*
         * Defines if the product number must increment automatically. Possible options:
         *      true        the product number will be set automatically
         *      false       the product number must be picked by the user
         */
        'autoIncrements' => env('PRODUCTS_PN_INCREMENTS', true),

        /*
         * The length a product number must have for auto increment.
         */
        'length' => env('PRODUCTS_PN_LENGTH', 6),

    ],

];
