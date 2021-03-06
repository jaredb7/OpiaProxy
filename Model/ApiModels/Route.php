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
class Route
{

    static $swaggerTypes = array(
        'Code' => 'string',
        'Name' => 'string',
        'Direction' => 'string',
        'Vehicle' => 'string',
        'ServiceType' => 'string',
        'IsPrepaid' => 'bool',
        'IsExpress' => 'bool',
        'IsFree' => 'bool',
        'IsTransLinkService' => 'bool'
    );

    /**
     * The route's code, eg SHLP for the Spring Hill Loop
     */
    public $Code; // string
    /**
     * Display name of the route, eg Spring Hill Loop
     */
    public $Name; // string
    /**
     * Direction the vehicle travels on this route, eg Inbound. North = 0, South = 1, East = 2, West = 3, Inbound = 4, Outbound = 5, Inward = 6, Outward = 7, Upward = 8, Downward = 9, Clockwise = 10, Counterclockwise = 11, Direction1 = 12, Direction2 = 13, Null = 14
     */
    public $Direction; // string
    /**
     * The type of vehicle travelling on this route, eg Bus. None = 0, Bus = 2, Ferry = 4, Train = 8, Walk = 16
     */
    public $Vehicle; // string
    /**
     * Bitmask of route service types, eg NightLink | Prepaid. Regular = 1, Express = 2, NightLink = 4, School = 8, Industrial = 16
     */
    public $ServiceType; // string
    /**
     * Is this a prepaid route?
     */
    public $IsPrepaid; // bool
    /**
     * Is this an express route?
     */
    public $IsExpress; // bool
    /**
     * Is travel on this route free?
     */
    public $IsFree; // bool
    /**
     * Is this a Translink service?
     */
    public $IsTransLinkService; // bool
}