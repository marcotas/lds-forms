(function () {

    var laroute = (function () {

        var routes = {

            absolute: false,
            rootUrl: 'https://lds-forms.test',
            routes : [{"host":null,"methods":["GET","HEAD"],"uri":"oauth\/authorize","name":null,"action":"\Laravel\Passport\Http\Controllers\AuthorizationController@authorize"},{"host":null,"methods":["POST"],"uri":"oauth\/authorize","name":null,"action":"\Laravel\Passport\Http\Controllers\ApproveAuthorizationController@approve"},{"host":null,"methods":["DELETE"],"uri":"oauth\/authorize","name":null,"action":"\Laravel\Passport\Http\Controllers\DenyAuthorizationController@deny"},{"host":null,"methods":["POST"],"uri":"oauth\/token","name":null,"action":"\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken"},{"host":null,"methods":["GET","HEAD"],"uri":"oauth\/tokens","name":null,"action":"\Laravel\Passport\Http\Controllers\AuthorizedAccessTokenController@forUser"},{"host":null,"methods":["DELETE"],"uri":"oauth\/tokens\/{token_id}","name":null,"action":"\Laravel\Passport\Http\Controllers\AuthorizedAccessTokenController@destroy"},{"host":null,"methods":["POST"],"uri":"oauth\/token\/refresh","name":null,"action":"\Laravel\Passport\Http\Controllers\TransientTokenController@refresh"},{"host":null,"methods":["GET","HEAD"],"uri":"oauth\/clients","name":null,"action":"\Laravel\Passport\Http\Controllers\ClientController@forUser"},{"host":null,"methods":["POST"],"uri":"oauth\/clients","name":null,"action":"\Laravel\Passport\Http\Controllers\ClientController@store"},{"host":null,"methods":["PUT"],"uri":"oauth\/clients\/{client_id}","name":null,"action":"\Laravel\Passport\Http\Controllers\ClientController@update"},{"host":null,"methods":["DELETE"],"uri":"oauth\/clients\/{client_id}","name":null,"action":"\Laravel\Passport\Http\Controllers\ClientController@destroy"},{"host":null,"methods":["GET","HEAD"],"uri":"oauth\/scopes","name":null,"action":"\Laravel\Passport\Http\Controllers\ScopeController@all"},{"host":null,"methods":["GET","HEAD"],"uri":"oauth\/personal-access-tokens","name":null,"action":"\Laravel\Passport\Http\Controllers\PersonalAccessTokenController@forUser"},{"host":null,"methods":["POST"],"uri":"oauth\/personal-access-tokens","name":null,"action":"\Laravel\Passport\Http\Controllers\PersonalAccessTokenController@store"},{"host":null,"methods":["DELETE"],"uri":"oauth\/personal-access-tokens\/{token_id}","name":null,"action":"\Laravel\Passport\Http\Controllers\PersonalAccessTokenController@destroy"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/user","name":null,"action":"Closure"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/minutes","name":"api.minutes.index","action":"App\Http\Controllers\Api\MinutesController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/minutes\/create","name":"api.minutes.create","action":"App\Http\Controllers\Api\MinutesController@create"},{"host":null,"methods":["POST"],"uri":"api\/minutes","name":"api.minutes.store","action":"App\Http\Controllers\Api\MinutesController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/minutes\/{minute}","name":"api.minutes.show","action":"App\Http\Controllers\Api\MinutesController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/minutes\/{minute}\/edit","name":"api.minutes.edit","action":"App\Http\Controllers\Api\MinutesController@edit"},{"host":null,"methods":["PUT","PATCH"],"uri":"api\/minutes\/{minute}","name":"api.minutes.update","action":"App\Http\Controllers\Api\MinutesController@update"},{"host":null,"methods":["DELETE"],"uri":"api\/minutes\/{minute}","name":"api.minutes.destroy","action":"App\Http\Controllers\Api\MinutesController@destroy"},{"host":null,"methods":["POST"],"uri":"api\/recipes\/destroy-bulk","name":"api.recipes.destroy-bulk","action":"App\Http\Controllers\Api\RecipesController@destroyBulk"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/recipes","name":"api.recipes.index","action":"App\Http\Controllers\Api\RecipesController@index"},{"host":null,"methods":["POST"],"uri":"api\/recipes","name":"api.recipes.store","action":"App\Http\Controllers\Api\RecipesController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/recipes\/{recipe}","name":"api.recipes.show","action":"App\Http\Controllers\Api\RecipesController@show"},{"host":null,"methods":["PUT","PATCH"],"uri":"api\/recipes\/{recipe}","name":"api.recipes.update","action":"App\Http\Controllers\Api\RecipesController@update"},{"host":null,"methods":["DELETE"],"uri":"api\/recipes\/{recipe}","name":"api.recipes.destroy","action":"App\Http\Controllers\Api\RecipesController@destroy"},{"host":null,"methods":["POST"],"uri":"api\/users\/{user}\/restore","name":"api.users.restore","action":"App\Http\Controllers\Api\UsersController@restore"},{"host":null,"methods":["DELETE"],"uri":"api\/users\/{user}\/force","name":"api.users.force-destroy","action":"App\Http\Controllers\Api\UsersController@forceDestroy"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/users","name":"api.users.index","action":"App\Http\Controllers\Api\UsersController@index"},{"host":null,"methods":["POST"],"uri":"api\/users","name":"api.users.store","action":"App\Http\Controllers\Api\UsersController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/users\/{user}","name":"api.users.show","action":"App\Http\Controllers\Api\UsersController@show"},{"host":null,"methods":["PUT","PATCH"],"uri":"api\/users\/{user}","name":"api.users.update","action":"App\Http\Controllers\Api\UsersController@update"},{"host":null,"methods":["DELETE"],"uri":"api\/users\/{user}","name":"api.users.destroy","action":"App\Http\Controllers\Api\UsersController@destroy"},{"host":null,"methods":["GET","HEAD"],"uri":"\/","name":null,"action":"Closure"},{"host":null,"methods":["GET","HEAD"],"uri":"login","name":"login","action":"App\Http\Controllers\Auth\LoginController@showLoginForm"},{"host":null,"methods":["POST"],"uri":"login","name":null,"action":"App\Http\Controllers\Auth\LoginController@login"},{"host":null,"methods":["POST"],"uri":"logout","name":"logout","action":"App\Http\Controllers\Auth\LoginController@logout"},{"host":null,"methods":["GET","HEAD"],"uri":"register","name":"register","action":"App\Http\Controllers\Auth\RegisterController@showRegistrationForm"},{"host":null,"methods":["POST"],"uri":"register","name":null,"action":"App\Http\Controllers\Auth\RegisterController@register"},{"host":null,"methods":["GET","HEAD"],"uri":"password\/reset","name":"password.request","action":"App\Http\Controllers\Auth\ForgotPasswordController@showLinkRequestForm"},{"host":null,"methods":["POST"],"uri":"password\/email","name":"password.email","action":"App\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail"},{"host":null,"methods":["GET","HEAD"],"uri":"password\/reset\/{token}","name":"password.reset","action":"App\Http\Controllers\Auth\ResetPasswordController@showResetForm"},{"host":null,"methods":["POST"],"uri":"password\/reset","name":null,"action":"App\Http\Controllers\Auth\ResetPasswordController@reset"},{"host":null,"methods":["GET","HEAD"],"uri":"home","name":"home","action":"App\Http\Controllers\HomeController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"atas","name":"minutes.index","action":"\Illuminate\Routing\ViewController"},{"host":null,"methods":["GET","HEAD"],"uri":"atas\/next","name":"minutes.next","action":"App\Http\Controllers\Web\MinutesController@next"},{"host":null,"methods":["GET","HEAD"],"uri":"atas\/prev","name":"minutes.prev","action":"App\Http\Controllers\Web\MinutesController@prev"},{"host":null,"methods":["GET","HEAD"],"uri":"atas\/next-form","name":"minutes.next-form","action":"App\Http\Controllers\Web\MinutesController@nextForm"},{"host":null,"methods":["GET","HEAD"],"uri":"atas\/prev-form","name":"minutes.prev-form","action":"App\Http\Controllers\Web\MinutesController@prevForm"},{"host":null,"methods":["GET","HEAD"],"uri":"atas\/{minute}","name":"minutes.show","action":"App\Http\Controllers\Web\MinutesController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"atas\/{minute}\/form","name":"minutes.form","action":"App\Http\Controllers\Web\MinutesController@form"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/usuarios","name":"admin.users.index","action":"\Illuminate\Routing\ViewController"},{"host":null,"methods":["GET","HEAD"],"uri":"receitas","name":"recipes.index","action":"App\Http\Controllers\Web\RecipesController@index"}],
            prefix: '',

            route : function (name, parameters, route) {
                route = route || this.getByName(name);

                if ( ! route ) {
                    return undefined;
                }

                return this.toRoute(route, parameters);
            },

            url: function (url, parameters) {
                parameters = parameters || [];

                var uri = url + '/' + parameters.join('/');

                return this.getCorrectUrl(uri);
            },

            toRoute : function (route, parameters) {
                var uri = this.replaceNamedParameters(route.uri, parameters);
                var qs  = this.getRouteQueryString(parameters);

                if (this.absolute && this.isOtherHost(route)){
                    return "//" + route.host + "/" + uri + qs;
                }

                return this.getCorrectUrl(uri + qs);
            },

            isOtherHost: function (route){
                return route.host && route.host != window.location.hostname;
            },

            replaceNamedParameters : function (uri, parameters) {
                uri = uri.replace(/\{(.*?)\??\}/g, function(match, key) {
                    if (parameters.hasOwnProperty(key)) {
                        var value = parameters[key];
                        delete parameters[key];
                        return value;
                    } else {
                        return match;
                    }
                });

                // Strip out any optional parameters that were not given
                uri = uri.replace(/\/\{.*?\?\}/g, '');

                return uri;
            },

            getRouteQueryString : function (parameters) {
                var qs = [];
                for (var key in parameters) {
                    if (parameters.hasOwnProperty(key)) {
                        qs.push(key + '=' + parameters[key]);
                    }
                }

                if (qs.length < 1) {
                    return '';
                }

                return '?' + qs.join('&');
            },

            getByName : function (name) {
                for (var key in this.routes) {
                    if (this.routes.hasOwnProperty(key) && this.routes[key].name === name) {
                        return this.routes[key];
                    }
                }
            },

            getByAction : function(action) {
                for (var key in this.routes) {
                    if (this.routes.hasOwnProperty(key) && this.routes[key].action === action) {
                        return this.routes[key];
                    }
                }
            },

            getCorrectUrl: function (uri) {
                var url = this.prefix + '/' + uri.replace(/^\/?/, '');

                if ( ! this.absolute) {
                    return url;
                }

                return this.rootUrl.replace('/\/?$/', '') + url;
            }
        };

        var getLinkAttributes = function(attributes) {
            if ( ! attributes) {
                return '';
            }

            var attrs = [];
            for (var key in attributes) {
                if (attributes.hasOwnProperty(key)) {
                    attrs.push(key + '="' + attributes[key] + '"');
                }
            }

            return attrs.join(' ');
        };

        var getHtmlLink = function (url, title, attributes) {
            title      = title || url;
            attributes = getLinkAttributes(attributes);

            return '<a href="' + url + '" ' + attributes + '>' + title + '</a>';
        };

        return {
            // Generate a url for a given controller action.
            // laroute.action('HomeController@getIndex', [params = {}])
            action : function (name, parameters) {
                parameters = parameters || {};

                return routes.route(name, parameters, routes.getByAction(name));
            },

            // Generate a url for a given named route.
            // laroute.route('routeName', [params = {}])
            route : function (route, parameters) {
                parameters = parameters || {};

                return routes.route(route, parameters);
            },

            // Generate a fully qualified URL to the given path.
            // laroute.route('url', [params = {}])
            url : function (route, parameters) {
                parameters = parameters || {};

                return routes.url(route, parameters);
            },

            // Generate a html link to the given url.
            // laroute.link_to('foo/bar', [title = url], [attributes = {}])
            link_to : function (url, title, attributes) {
                url = this.url(url);

                return getHtmlLink(url, title, attributes);
            },

            // Generate a html link to the given route.
            // laroute.link_to_route('route.name', [title=url], [parameters = {}], [attributes = {}])
            link_to_route : function (route, title, parameters, attributes) {
                var url = this.route(route, parameters);

                return getHtmlLink(url, title, attributes);
            },

            // Generate a html link to the given controller action.
            // laroute.link_to_action('HomeController@getIndex', [title=url], [parameters = {}], [attributes = {}])
            link_to_action : function(action, title, parameters, attributes) {
                var url = this.action(action, parameters);

                return getHtmlLink(url, title, attributes);
            }

        };

    }).call(this);

    /**
     * Expose the class either via AMD, CommonJS or the global object
     */
    if (typeof define === 'function' && define.amd) {
        define(function () {
            return laroute;
        });
    }
    else if (typeof module === 'object' && module.exports){
        module.exports = laroute;
    }
    else {
        window.laroute = laroute;
    }

}).call(this);

