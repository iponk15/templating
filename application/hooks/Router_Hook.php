<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Router_Hook
{
        /**
         * Loads routes from database.
         *
         * @access public
         * @params array : hostname, username, password, database, db_prefix
         * @return void
         */
    function get_routes($params)
    {
        global $DB_ROUTES;

        mysql_connect($params[0], $params[1], $params[2]);

        mysql_select_db($params[3]);

        $sql = "SELECT * FROM {$params[4]}routes";
        $query = mysql_query($sql);

        $routes = array();
        while ($route = mysql_fetch_array($query, MYSQL_ASSOC)) {
            $routes[$route['route']] = $route['controller'];
        }
        mysql_free_result($query);
        mysql_close();
        $DB_ROUTES = $routes;
    }
}