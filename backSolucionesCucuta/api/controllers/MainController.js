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
    res.view('homepage');
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
        src:    '/images/logo.png',
        nombre: 'Soluciones Cúcuta',
        subnavs:  [
          {
            sref:   'inicio',
            src:    false,
            nombre: 'inicio'
          }
        ]
      },
      namespace = req.param('namespace', 'inicio');
    Publicacion.find({tipo: 3}).populateAll().sort('id DESC').exec(function(err, found) {
      if (err) {
        return res.negotiate(err);
      }
      while (found.length) {
        var publicacion = found.pop();
        brand.subnavs.push({
          sref: publicacion.slug,
          src: false,
          nombre: publicacion.titulo
        });
      }
      // Agrega Login al final
      brand.subnavs.push({
        sref: '/Login/',
        src: false,
        nombre: 'entrar'
      });
      Etiqueta.find({tipo: 1}).populate('etiquetas').sort('id DESC').exec(function (err, found){
        if(err){
          return res.negotiate(err);
        }
        navs = UtilsService.generateNavPage(found);
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
    var nav = req.param('nav', false),
      subnav = req.param('subnav', false);
    var where = {};
    if(nav){
      where = {
        or: [
          {slug: nav.toLowerCase()}
        ]
      };
      if(subnav){
        where.or.push({slug: subnav.toLowerCase()})
      }
    }
    /*sails.log.debug('=> HeaderPage <=')
    sails.log.debug(where)*/
    Usuario.find({rol: 1})
      .populate('archivos',{tipo: 6, estado: 1})
      .populate('etiquetas', where)
      .exec(function(err, found){
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
                sref:       '/',
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
      figures = [],
      clientes = [],
      nav = req.param('nav', false),
      subnav = req.param('subnav', false),
      page = req.param('page', 1),
      limit = req.param('limit', 5),
      where = {},
      total = 0,
      last = 1,
      first = 1,
      convertUsuario = function (usuario){
        var archivo = false,
          archivos = usuario.archivos;
        if(archivos && archivos[0] && archivos[0].url && archivos[0].url != ''){
          archivo = {
            srcBase:    '',
            src:        archivos[0].url,
            sref:       'usuario({ usuario: "'+usuario.slug+'"})',
            nombre:     usuario.nombre,
            descripcion:usuario.descripcion
          };;
        }
        return archivo;
      },
      rtaJson = function(publicaciones, figures, clientes){
        return res.json({
          paginate:   {
            total: total,
            current: page,
            last: last,
            next: page + 1 > last ? first : page + 1,
            prev: page - 1 < first ? last : page - 1 ,
            first: first,
            limit: limit
          },
          "articles": publicaciones,
          "figures":  figures,
          "clientes": clientes
        });
      }
      ;
    if(nav){
      where = {
        where: {
          or: [
            {slug: Etiqueta.capitalizeSlug(nav)},
          ]
        }
      };
      if(subnav){
        where.where.or.push({slug: Etiqueta.capitalizeSlug(subnav)});
      }
    }

    Usuario
      .find({estado: 1})
      .populate('archivos', {tipo: 7, estado: 1})
      .populate('etiquetas')
      .exec(function(err, usuarios){
        if(err){
          res.negotiate(err);
        }
        if(usuarios){
          var
            total = usuarios.length,
            _total = total,
            items = [];

          if(_total > limit){
            _total = limit
          }

          // Figures
          for(var i = 0; i <= _total; i++){
            var rand = Math.floor(Math.random()*(total));
            if(items.indexOf(rand) >= 0){
              i--;
            }else{
              items.push(rand);
              var figure = convertUsuario(usuarios[rand]);
              if(figure){
                figures.push(figure);
              }
            }
          }

          // Clientes o Publicaciones según navegación
          if(nav){
            // Clientes
            for(var i=0; i<usuarios.length; i++){
              var cliente = usuarios[i];
              for(var j = 0; j < cliente.etiquetas.length; j++){
                var etiqueta = cliente.etiquetas[j];
                sails.log.debug('etiqueta('+etiqueta.slug.toLowerCase()+') == nav('+nav.toLowerCase()+') => '+etiqueta.slug.toLowerCase() == nav.toLowerCase());
                sails.log.debug('etiqueta('+etiqueta.slug.toLowerCase()+') == subnav('+(subnav && subnav.toLowerCase())+') => '+etiqueta.slug.toLowerCase() == nav.toLowerCase());
                if((!subnav && etiqueta.slug.toLowerCase() == nav.toLowerCase()) || (subnav && etiqueta.slug.toLowerCase() == subnav.toLowerCase())){
                  clientes.push(convertUsuario(cliente));
                  break;
                }
              }
            }
            return rtaJson(publicaciones, figures, clientes);

          }else{
            // Publicaciones
            Publicacion.find({tipo: 3})
              .populateAll()
              .sort('id ASC')
              .exec(function(err, publicaciones){
                if(err){
                  return res.negotiate(err);
                }
                total = publicaciones.length;
                last = Math.floor(total/limit)+ 1;
                // Fix page
                page = page > last || page < first?first:page;

                var iPub = 0;
                while (iPub < publicaciones.length){
                  var publicacion = publicaciones[iPub];

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
                  if(publicacion.usuarios){
                    for(var i = 0; i < publicacion.usuarios.length; i++){
                      var usuario = publicacion.usuarios[i];
                      autores += (i != 0?', ':'')+usuario.username;
                    }
                  }
                  if(autores.length){
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
                  iPub++;
                }
                return rtaJson(publicaciones, figures, clientes);
              });
          }
        }else{
          res.notFound();
        }

      });

    /*sails.log.debug(where);*/
    /*Usuario.find({rol: 1})
      .populate('archivos',{tipo: 7, estado: 1})
      .populate('etiquetas', where)
      .exec(function(err, usuarios){
        total = usuarios.length;
        last = Math.floor(total/limit)+ 1;
        // Fix page
        page = page > last || page < first?first:page;
        var usrs = [], _total = total;
        *//*if(_total > limit){
          _total = limit
        }*//*
        // Inicio Figures
        var items = [];
        for(var i = 0; i < _total; i++){
          var rand = Math.floor(Math.random()*(total));
          if(items.indexOf(rand) >= 0){
            i--;
          }else{
            items.push(rand);
            usrs.push(usuarios[rand]);
          }
        }
        while(usrs.length){
          var
            usuario = usrs.pop(),
            etiquetas = getListAttr(usuario.etiquetas, 'slug'),
            figure = convertUsuario(usuario);
          if(figure){
            figures.push(figure);
          }
          *//*sails.log.debug(etiquetas);*//*
          if((nav && etiquetas.indexOf(Etiqueta.capitalizeSlug(nav))) || (subnav && etiquetas.indexOf(Etiqueta.capitalizeSlug(subnav)))){
            clientes.push(usuario);
          }
        }
        // Fin Figures
        if(nav){
          var _clientes = [];
          while(clientes.length){
            var cliente = clientes.pop();
            _clientes.push(convertUsuario(cliente));
          }
          clientes = _clientes;

          return rtaJson(publicaciones, figures, clientes);
        }else{
          Publicacion.find({tipo: 3})
            .exec(function(err, _total){
              total = _total.length;
              last = Math.floor(total/limit)+ 1;
              // Fix page
              page = page > last || page < first?first:page;

              Publicacion.find({tipo: 3})
                .populateAll()
                .sort('id ASC')
                .exec(function(err, found){
                  if(err){
                    return res.negotiate(err);
                  }
                  var iPub = 0;
                  while (iPub < found.length){
                    var publicacion = found[iPub];

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
                    iPub++;
                  }
                  return rtaJson(publicaciones, figures, clientes);
                });
            });
        }
      });*/

    /*Etiqueta
      .find(where)
      .populate('usuarios',{rol: 1})
      .sort('nombre DESC')
      .exec(function(err, etiquetas){
        if(err){
          res.negotiate(err);
        }
        var usuarios = [];
        if(etiquetas){
          while(etiquetas.length){
            var etiqueta = etiquetas.pop();
            while(usuarios.length) {
              var usuario = usuarios.pop();
              usuario.push(usuario.id);
            }
          }
          Usuario.find({id: })
        }
      });*/

    /*Usuario.find({rol: 1})
      .populate('archivos',{tipo: 7, estado: 1})
      .populate('etiquetas')
      .exec(function(err, usuarios){
        var total = 0,
          _clientes = [];
        if(nav){
          where.push(nav.toLowerCase());
          if(subnav){
            where.push(subnav.toLowerCase());
          }
          for(var tempA = 0; tempA < usuarios.length; tempA++){
            var etiquetasA = usuarios[tempA].etiquetas;
            for(var tempB = 0; tempB < etiquetasA.length;tempB++){
              for(var tempC = 0; tempC < where.length; tempC++){
                var tag = where[tempC];
                if(etiquetasA[tempB].slug.toLowerCase() == tag){
                  total++;
                  _clientes.push(usuarios[tempA]);
                }
              }
            }
          }
        }else{
          total = usuarios.length;
        }
        var last = 1,
          first = 1;

        var rtaJson = function(publicaciones, figures, clientes){
          res.json({
            paginate:   {
              total: total,
              current: page,
              last: last,
              next: page + 1 > last ? first : page + 1,
              prev: page - 1 < first ? last : page - 1 ,
              first: first,
              limit: limit
            },
            "articles": publicaciones,
            "figures":  figures,
            "clientes": clientes
          });
        }
        Usuario.find({rol: 1})
          .populate('archivos',{tipo: 7, estado: 1})
          .populate('etiquetas')
          .sort('nombre DESC')
          .exec(function(err, found){
            if(err){
              res.negotiate(err);
            }
            if(found){
              var convertUsuario = function (usuario){
                var rta = false,
                  archivos = usuario.archivos;
                if(archivos.length){
                  var archivo = archivos[0];
                  var rta = {
                    srcBase:    '',
                    src:        archivo.url,
                    sref:       '/',
                    nombre:     usuario.nombre,
                    descripcion:usuario.descripcion
                  };
                }
                return rta;
              };
              if(found.length > 5){
                var items = [];
                for(var i = 0; i < found.length; i++){
                  var rand = Math.floor(Math.random()*(found.length));
                  if(items.indexOf(rand) >= 0){
                    i--;
                  }else{
                    items.push(rand);
                    var figure = convertUsuario(found[rand]);
                    if(figure){
                      figures.push(figure);
                    }
                  }
                }
              }else{
                var i = 0;
                while(i < found.length){
                  var figure = convertUsuario(found[i]);
                  if(figure){
                    figures.push(figure);
                  }
                  i++;
                }
              }
            }
            if(nav){
              last = Math.floor(total/limit)+ 1;
              // Fix page
              page = page > last || page < first?first:page;
              var i = 0, ids = [];
              while(i < _clientes.length){
                if(ids.indexOf(_clientes[i].id) < 0){
                  ids.push(_clientes[i].id);
                  var cliente = convertUsuario(_clientes[i]);
                  if(cliente){
                    clientes.push(cliente);
                  }
                }
                i++;
              }
              return rtaJson(publicaciones, figures, clientes);
            }else{
              Publicacion.find({tipo: 3})
                .exec(function(err, _total){
                  total = _total.length;
                  last = Math.floor(total/limit)+ 1;
                  // Fix page
                  page = page > last || page < first?first:page;

                  Publicacion.find({tipo: 3})
                    .populateAll()
                    .sort('id ASC')
                    .exec(function(err, found){
                      if(err){
                        return res.negotiate(err);
                      }
                      var iPub = 0;
                      while (iPub < found.length){
                        var publicacion = found[iPub];

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
                        iPub++;
                      }
                      return rtaJson(publicaciones, figures, clientes);
                    });
                });
            }
          });

      });*/
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
      footers = UtilsService.generateNavPage(found);
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

