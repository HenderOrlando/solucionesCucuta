/**
 * Route Mappings
 * (sails.config.routes)
 *
 * Your routes map URLs to views and controllers.
 *
 * If Sails receives a URL that doesn't match any of the routes below,
 * it will check for matching files (images, scripts, stylesheets, etc.)
 * in your assets directory.  e.g. `http://localhost:1337/images/foo.jpg`
 * might match an image file: `/assets/images/foo.jpg`
 *
 * Finally, if those don't match either, the default 404 handler is triggered.
 * See `api/responses/notFound.js` to adjust your app's 404 logic.
 *
 * Note: Sails doesn't ACTUALLY serve stuff from `assets`-- the default Gruntfile in Sails copies
 * flat files from `assets` to `.tmp/public`.  This allows you to do things like compile LESS or
 * CoffeeScript for the front-end.
 *
 * For more information on configuring custom routes, check out:
 * http://sailsjs.org/#/documentation/concepts/Routes/RouteTargetSyntax.html
 */

module.exports.routes = {

  /***************************************************************************
  *                                                                          *
  * Make the view located at `views/homepage.ejs` (or `views/homepage.jade`, *
  * etc. depending on your default view engine) your home page.              *
  *                                                                          *
  * (Alternatively, remove this and add an `index.html` file in your         *
  * `assets` directory)                                                      *
  *                                                                          *
  ***************************************************************************/

  /*'/': {
    view: 'homepage'
  }*/

  /***************************************************************************
  *                                                                          *
  * Custom routes here...                                                    *
  *                                                                          *
  *  If a request to a URL doesn't match any of the custom routes above, it  *
  * is matched against Sails route blueprints. See `config/blueprints.js`    *
  * for configuration options and examples.                                  *
  *                                                                          *
  ***************************************************************************/
  '/': {
    controller: "Main",
    action: "index",
    locals: {
      layout: false
    }
  },
  'get /Login': {
    controller: 'Main',
    action: 'loginView',
    view: 'partials/form_login',
    locals: {
      layout: false
    }
  },
  '/Perfil': {
    controller: "Usuario",
    action: "perfil",
    view: 'usuario/perfil',
    locals: {
      layout: false
    }
  },
  'post /Login': {
    controller: "Main",
    action: "login"
  },
  '/Logout': {
    controller: "Main",
    action: "logout"
  }
  // Get Page
  ,'/getNavbarPage': {
    controller: "Main",
    action: "getNavbarPage"
  }
  ,'/getHeaderPage': {
    controller: "Main",
    action: "getHeaderPage"
  }
  ,'/getFooterPage': {
    controller: "Main",
    action: "getFooterPage"
  }
  ,'/getContentPage': {
    controller: "Main",
    action: "getContentPage"
  }
  // Buscador
  ,'/Buscar/:query': {
    controller: "Main",
    action: "buscar",
    skipAssets: true
  }
  // Publicacion
  ,'/Publicacion/Agregar': {
    controller: "Publicacion",
    action: "agregar",
    skipAssets: true
  }
  ,'/Publicacion/Borrar': {
    controller: "Publicacion",
    action: "borrar",
    skipAssets: true
  }
  ,'/Publicacion/Listar': {
    controller: "Publicacion",
    action: "listar",
    skipAssets: true
  }
  // Usuario
  ,'/Usuario/Agregar': {
    controller: "Usuario",
    action: "agregar",
    skipAssets: true
  }
  ,'/Usuario/Borrar': {
    controller: "Usuario",
    action: "borrar",
    skipAssets: true
  }
  ,'/Usuario/Listar': {
    controller: "Usuario",
    action: "listar",
    skipAssets: true,
  }
  //Count
  ,'/Archivo/count': {
    controller: "Archivo",
    action: "count"
  }
  ,'/Estado/count': {
    action: "count",
    controller: "Estado"
  }
  ,'/Etiqueta/count': {
    action: "count",
    controller: "Etiqueta"
  }
  ,'/Publicacion/count': {
    controller: "Publicacion",
    action: "count"
  }
  ,'/Rol/count': {
    controller: "Rol",
    action: "count"
  }
  ,'/Tipo/count': {
    controller: "Tipo",
    action: "count"
  }
  ,'/Usuario/count': {
    controller: "Usuario",
    action: "count"
  }
};
