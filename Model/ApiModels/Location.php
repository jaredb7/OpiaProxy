<?php
/**
 *  Copyright 2011 Wordnik, Inc.
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */

/**
 * $model.description$
 *
 * NOTE: This class is auto generated by the swagger code generator program. Do not edit the class manually.
 *
 */
class Location
{

    static $swaggerTypes = array(
        'Id' => 'string',
        'Description' => 'string',
        'Type' => 'string',
        'Position' => 'LatLng',
        'LandmarkType' => 'string',
        'Route' => 'Route'
    );

    /**
     * Unique idenfier of this location
     */
    public $Id; // string
    /**
     * Description of the location
     */
    public $Description; // string
    /**
     * Type of this location. None = 0, Landmark = 1, StreetAddress = 2, Stop = 3, GeographicPosition = 4
     */
    public $Type; // string
    /**
     * The geographic position of this location, if available
     */
    public $Position; // LatLng
    /**
     * The type of landmark this location is at, or null if associated with a landmark
     */
    public $LandmarkType; // string
}