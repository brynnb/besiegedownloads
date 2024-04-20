<?php

/*
|--------------------------------------------------------------------------
| Detect Active Route
|--------------------------------------------------------------------------
|
| Compare given route with current route and return output if they match.
| Very useful for navigation, marking if the link is active.
|
*/
function isActiveRoute($route, $output = "nav-active")
{
//    if (Request::path() == "/" && $route == 'home') return $output;
    if (Route::currentRouteName() == $route) return $output;
    if (str_contains(Request::path(),$route)) return $output;
//    return Route::currentRouteName() . 'test';
//    return Request::path();
}

function isAllItems()
{
    //i am not happy with this but i am ready to be done with this project, so sorry :(
    //not sure how else to do it
    if(str_contains(Request::path(),'shooting') || str_contains(Request::path(),'contraption') ||
        str_contains(Request::path(),'walking') || str_contains(Request::path(),'air') ||
        str_contains(Request::path(),'ground'))
    {
        return '';
    } else {
        return 'active';
    }
}


/*
|--------------------------------------------------------------------------
| Detect Active Routes
|--------------------------------------------------------------------------
|
| Compare given routes with current route and return output if they match.
| Very useful for navigation, marking if the link is active.
|
*/
function areActiveRoutes(Array $routes, $output = "active")
{
    foreach ($routes as $route)
    {
        if (Route::currentRouteName() == $route) return $output;
    }

}