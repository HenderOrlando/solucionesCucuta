/**
 * MainControllerController
 *
 * @description :: Server-side logic for managing maincontrollers
 * @help        :: See http://links.sailsjs.org/docs/controllers
 */

var bcrypt = require('bcrypt');

module.exports = {



  /**
   * `MainController.index()`
   */
  index: function (req, res, next) {
    var
    figures = [
      {
        srcBase:  'images/empresas/',
        src:      'bn_inmobiliaria.jpg',
        href:     '/',
        nombre:   'BNG Inmobiliaria'
      },
      {
        srcBase:  'images/empresas/',
        src:      'calzado_orluis.jpg',
        href:     '/',
        nombre:   'Calzado Orluis'
      },
      {
        srcBase:  'images/empresas/',
        src:      'cambios_el_triunfo.jpg',
        href:     '/',
        nombre:   'Cambios El Triunfo'
      },
      {
        srcBase:  'images/empresas/',
        src:      'camilo_celu.jpg',
        href:     '/',
        nombre:   'CamiloCelu'
      }
    ],
    banners = [],
    brand = {
      src:    'images/logo.png',
      nombre: 'Soluciones Cúcuta',
      subnavs:  [
        {
          href:   '#inicio',
          src:    false,
          nombre: 'inicio'
        }
      ]
    },
    navs = [],
    publicaciones = [];
    Publicacion.find({tipo: 3}).populateAll().sort('id ASC').exec(function(err, found){
      if(err){
        return res.negotiate(err);
      }
      while (found.length){
        var publicacion = found.pop();
        brand.subnavs.push({
          href:   '#'+publicacion.slug,
          src:    false,
          nombre: publicacion.slug
        });

        var pub = {
          attrs:    [
            {
              name: 'id',
              value: publicacion.slug
            }
          ],
          title:    publicacion.titulo,
          metas:     [],
          subtitle: publicacion.subtitulo,
          content:   [publicacion.contenido],
          footer:   false,
          media:    false,
          fullwidth:true
        };
        var autores = '';
        for(var i = 0; i < publicacion.usuarios.length; i++){
          var usuario = publicacion.usuarios[i];
          autores += (i != 0?', ':'')+usuario.username;
        }
        if(autores){
          pub.metas.push({
            name : 'escrito por',
            value : autores
          });
        }

        var etiquetasPub = '';
        for(var i = 0; i < publicacion.etiquetas.length; i++){
          var etiquetas = publicacion.etiquetas[i];
          autores += (i != 0?', ':'')+etiquetas.nombre;
        }
        if(etiquetasPub){
          pub.metas.push({
            name : 'etiquetas',
            value : etiquetas
          });
        }

        publicaciones.push(pub);

      }
      // Agrega Login al final
      brand.subnavs.push({
        href:   '/Login/',
        src:    false,
        nombre: 'entrar'
      });
      Etiqueta.find({tipo: 1}).populate('etiquetas').sort('id DESC').exec(function (err, found){
        if(err){
          return res.negotiate(err);
        }
        while (found.length){
          var etiqueta = found.pop();
          if(etiqueta.etiquetas.length){
            var subnavs = [];
            if(etiqueta.etiquetas.length){
              for(var i in etiqueta.etiquetas){
                var subEtiqueta = etiqueta.etiquetas[i];
                if(subEtiqueta && subEtiqueta.nombre && subEtiqueta.slug){
                  subnavs.push({
                    href:   '/Buscar/'+etiqueta.slug+'/'+subEtiqueta.slug+'/',
                    src:    false,
                    nombre: subEtiqueta.nombre.toLowerCase()
                  });
                }
              }
            }
            navs.push({
              li:     true,
              href:   '/Buscar/'+etiqueta.slug+'/',
              src:    false,
              nombre: etiqueta.nombre.toLowerCase(),
              subnavs:  subnavs
            });
          }
        }

        Usuario.find({rol: 1}).populate('archivos',{tipo: 6, estado: 1}).exec(function(err, found){
          if(err){
            res.negotiate(err);
          }
          if(found){
            while(found.length){
              var usuario = found.pop(),
                archivos = usuario.archivos;
              if(archivos.length){
                var archivo = archivos[0];
                banners.push({
                  srcBase:    '',
                  src:        archivo.url,
                  href:       '/',
                  nombre:     usuario.nombre,
                  descripcion:usuario.descripcion
                });
              }
            }
          }
          res.view('homepage');
          /*res.view('homepage',{
            "articles":       publicaciones,
            "figures":        figures,
            "banners":        banners,
            "footers":        navs,
            "navs":           navs,
            "brand":          brand
          });*/
        });
      });
    });
  },


  /**
   * `MainController.buscar()`
   */
  buscar: function (req, res) {
    return res.json({
      todo: 'buscar() is not implemented yet!'
    });
  },


  /**
   * `MainController.login()`
   */
  login: function (req, res, next) {
    var username = req.param('username', false),
      password = req.param('password', false);
    if(username && password){
      Usuario.findOne().where({
        or: [
          { username: username }
          //,{ username: username }
        ]
      }).exec(function(err, usuario){
        if(err){
          return res.negotiate(err);
        }
        if(usuario){
          bcrypt.compare(password, usuario.password, function(err, valid){
            if(err){
              res.status(404);
              res.send({
                msgs: [
                  {
                    type: 'error',
                    title: 'clave inválida',
                    content: 'No se valido la clave, intenta mas tarde'
                  }
                ]
              })
            }
            if(!valid){
              res.status(404);
              res.send({
                msgs: [
                  {
                    type: 'error',
                    title: 'clave no valida',
                    content: 'La clave no es valida'
                  }
                ]
              })
            }else{
              req.session.authenticated = true;
              req.session.usuario = usuario;
              res.status(200);
              res.send({
                redirect: '/Perfil',
                msgs: [
                  {
                    type: 'success',
                    title: 'bienvenido',
                    content: 'Hola, '+usuario.username
                  }
                ]
              });
            }
          });
        }else{
          res.status(404);
          res.send({
            msgs: [
              {
                type: 'error',
                title: 'sin cuenta',
                content: 'El usuario "'+username+'" no fue encontrado'
              }
            ]
          });
        }
      });
    }
    return;
  },

  /**
   * `MainController.getNavbarPage()`
   */
  getNavbarPage: function (req, res, next) {
    var navs = [],
      brand = {
        src:    'images/logo.png',
        nombre: 'Soluciones Cúcuta',
        subnavs:  [
          {
            href:   '#inicio',
            src:    false,
            nombre: 'inicio'
          }
        ]
      };
    Publicacion.find({tipo: 3}).populateAll().sort('id ASC').exec(function(err, found) {
      if (err) {
        return res.negotiate(err);
      }
      while (found.length) {
        var publicacion = found.pop();
        brand.subnavs.push({
          href: '#' + publicacion.slug,
          src: false,
          nombre: publicacion.slug
        });
      }
      // Agrega Login al final
      brand.subnavs.push({
        href: '/Login/',
        src: false,
        nombre: 'entrar'
      });
      Etiqueta.find({tipo: 1}).populate('etiquetas').sort('id DESC').exec(function (err, found){
        if(err){
          return res.negotiate(err);
        }
        while (found.length){
          var etiqueta = found.pop();
          if(etiqueta.etiquetas.length){
            var subnavs = [];
            if(etiqueta.etiquetas.length){
              for(var i in etiqueta.etiquetas){
                var subEtiqueta = etiqueta.etiquetas[i];
                if(subEtiqueta && subEtiqueta.nombre && subEtiqueta.slug){
                  subnavs.push({
                    href:   '/Buscar/'+etiqueta.slug+'/'+subEtiqueta.slug+'/',
                    src:    false,
                    nombre: subEtiqueta.nombre.toLowerCase()
                  });
                }
              }
            }
            navs.push({
              li:     true,
              href:   '/Buscar/'+etiqueta.slug+'/',
              src:    false,
              nombre: etiqueta.nombre.toLowerCase(),
              subnavs:  subnavs
            });
          }
        }
        return res.json({
          "navs": navs,
          "brand": brand
        });
      });
    });
  },

  /**
   * `MainController.getHeaderPage()`
   */
  getHeaderPage: function (req, res, next) {
    var banners = [];
    Usuario.find({rol: 1}).populate('archivos',{tipo: 6, estado: 1}).exec(function(err, found){
      if(err){
        res.negotiate(err);
      }
      if(found){
        while(found.length){
          var usuario = found.pop(),
            archivos = usuario.archivos;
          if(archivos.length){
            var archivo = archivos[0];
            banners.push({
              srcBase:    '',
              src:        archivo.url,
              href:       '/',
              nombre:     usuario.nombre,
              descripcion:usuario.descripcion
            });
          }
        }
      }
      return res.json({
        "banners": banners
      });
    });
  },

  /**
   * `MainController.getContentPage()`
   */
  getContentPage: function (req, res, next) {
    var publicaciones = [],
      figures = [];
    Publicacion.find({tipo: 3}).populateAll().sort('id ASC').exec(function(err, found){
      if(err){
        return res.negotiate(err);
      }
      while (found.length){
        var publicacion = found.pop();

        var pub = {
          attrs:    [
            {
              name: 'id',
              value: publicacion.slug
            }
          ],
          title:    publicacion.titulo,
          metas:     [],
          subtitle: publicacion.subtitulo,
          content:   [publicacion.contenido],
          footer:   false,
          media:    false,
          fullwidth:true
        };
        var autores = '';
        for(var i = 0; i < publicacion.usuarios.length; i++){
          var usuario = publicacion.usuarios[i];
          autores += (i != 0?', ':'')+usuario.username;
        }
        if(autores){
          pub.metas.push({
            name : 'escrito por',
            value : autores
          });
        }

        var etiquetasPub = '';
        for(var i = 0; i < publicacion.etiquetas.length; i++){
          var etiquetas = publicacion.etiquetas[i];
          autores += (i != 0?', ':'')+etiquetas.nombre;
        }
        if(etiquetasPub){
          pub.metas.push({
            name : 'etiquetas',
            value : etiquetas
          });
        }

        publicaciones.push(pub);

      }

      Usuario.find({rol: 1}).populate('archivos',{tipo: 7, estado: 1}).exec(function(err, found){
        if(err){
          res.negotiate(err);
        }
        if(found){
          while(found.length){
            var usuario = found.pop(),
              archivos = usuario.archivos;
            if(archivos.length){
              var archivo = archivos[0];
              figures.push({
                srcBase:    '',
                src:        archivo.url,
                href:       '/',
                nombre:     usuario.nombre,
                descripcion:usuario.descripcion
              });
            }
          }
        }
      });
      return res.json({
        "articles":       publicaciones,
        "figures":        figures
      });
    });
  },

  /**
   * `MainController.getFooterPage()`
   */
  getFooterPage: function (req, res, next) {
    var footers = [];
    Etiqueta.find({tipo: 1}).populate('etiquetas').sort('id DESC').exec(function (err, found){
      if(err){
        return res.negotiate(err);
      }
      while (found.length){
        var etiqueta = found.pop();
        if(etiqueta.etiquetas.length){
          var subnavs = [];
          if(etiqueta.etiquetas.length){
            for(var i in etiqueta.etiquetas){
              var subEtiqueta = etiqueta.etiquetas[i];
              if(subEtiqueta && subEtiqueta.nombre && subEtiqueta.slug){
                subnavs.push({
                  href:   '/Buscar/'+etiqueta.slug+'/'+subEtiqueta.slug+'/',
                  src:    false,
                  nombre: subEtiqueta.nombre.toLowerCase()
                });
              }
            }
          }
          footers.push({
            li:     true,
            href:   '/Buscar/'+etiqueta.slug+'/',
            src:    false,
            nombre: etiqueta.nombre.toLowerCase(),
            subnavs:  subnavs
          });
        }
      }
      return res.json({
        "footers": footers
      });
    });
  },


  /**
   * `MainController.loginView()`
   */
  loginView: function (req, res) {
    res.view('partials/form_login');
    return;
  },

  /**
   * `MainController.logout()`
   */
  logout: function (req, res) {
    req.session.authenticated = false;
    req.session.usuario = undefined;
    res.redirect('/Login/');
    return;
  },


  /**
   * `MainController.empresas()`
   */
  empresas: function (req, res, next) {
    var query = req.param('query');
    if(query.match(/\..+$/)){
      return next();
    }
    Etiqueta
      .findOneBySlug(slug)
      .exec(function(err, etiqueta){
        if(err){
          return res.negotiate(err);
        }
        if(!etiqueta){
          return next();
        }
      });
    return res.json({
      todo: 'empresas() is not implemented yet!'
    });
  },


  /**
   * `MainController.campanas()`
   */
  campanas: function (req, res) {
    return res.json({
      todo: 'campanias() is not implemented yet!'
    });
  },


  /**
   * `MainController.desarrolloDiseno()`
   */
  desarrolloDiseno: function (req, res) {
    return res.json({
      todo: 'desarrolloDisenio() is not implemented yet!'
    });
  },


  /**
   * `MainController.serviciosInteligentes()`
   */
  serviciosInteligentes: function (req, res) {
    return res.json({
      todo: 'serviciosInteligentes() is not implemented yet!'
    });
  }
};

