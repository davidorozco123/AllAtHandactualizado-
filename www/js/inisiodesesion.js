//creamos el modulo e inyectamos ngCookies
var app = angular.module('cookies', ['ngCookies']);


//damos configuración de ruteo a nuestro sistema de login
app.config(function($routeProvider)
{
    $routeProvider.when("/login", {
        controller : "loginController",
        templateUrl : "templates/login.html"
    })
    .when("/home", {
        controller : "homeController",
        templateUrl : "templates/home.html"
    })
});

//factoria que controla la autentificación, devuelve un objeto
//$cookies para crear cookies
//$cookieStore para actualizar o eliminar
//$location para cargar otras rutas
app.factory("auth", function($cookies,$cookieStore,$location)
{
    return{
        login : function(username, password)
        {
            //creamos la cookie con el nombre que nos han pasado
            $cookies.username = username,
            $cookies.password = password;
            //mandamos a la home
            $location.path("/home");
        },
        logout : function()
        {
            //al hacer logout eliminamos la cookie con $cookieStore.remove
            $cookieStore.remove("username"),
            $cookieStore.remove("password");
            //mandamos al login
            $location.path("/login");
        },
        checkStatus : function()
        {
            //creamos un array con las rutas que queremos controlar
            var rutasPrivadas = ["/home","/dashboard","/login"];
            if(this.in_array($location.path(),rutasPrivadas) && typeof($cookies.username) == "undefined")
            {
                $location.path("/login");
            }
            //en el caso de que intente acceder al login y ya haya iniciado sesión lo mandamos a la home
            if(this.in_array("/login",rutasPrivadas) && typeof($cookies.username) != "undefined")
            {
                $location.path("/home");
            }
        },
        in_array : function(needle, haystack)
        {
            var key = '';
            for(key in haystack)
            {
                if(haystack[key] == needle)
                {
                    return true;
                }
            }
            return false;
        }
    }
});

//creamos el controlador pasando $scope y $http, así los tendremos disponibles
app.controller('loginController', function($scope,auth)
{
    //la función login que llamamos en la vista llama a la función
    //login de la factoria auth pasando lo que contiene el campo
    //de texto del formulario
    $scope.login = function()
    {
        auth.login($scope.username, $scope.password);
    }

});


//creamos el controlador pasando $scope y auth
app.controller('homeController', function($scope, $cookies, auth)
{
    //devolvemos a la vista el nombre del usuario
    $scope.username = $cookies.username;
    $scope.password = $cookies.password;
    //la función logout que llamamos en la vista llama a la función
    //logout de la factoria auth
    $scope.logout = function()
    {
        auth.logout();
    }

});


//mientras corre la aplicación, comprobamos si el usuario tiene acceso a la ruta a la que está accediendo
app.run(function($rootScope, auth)
{
    //al cambiar de rutas
    $rootScope.$on('$routeChangeStart', function()
    {
        //llamamos a checkStatus, el cual lo hemos definido en la factoria auth
        //la cuál hemos inyectado en la acción run de la aplicación
        auth.checkStatus();
    })
})
