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
    var articles = [
      {
        attrs:    [
          {
            name: 'id',
            value: 'quienesSomos'
          }
        ],
        title:    'Quienes Somos',
        metas:     [
          {
            name : 'escrito por',
            value : 'Hender Orlando'
          }
        ],
        subtitle: 'Quienes Somos Soluciones Cúcuta',
        content:   [
          {text: 'SOLUCIONES CUCUTA  es una empresa norteamericana constituida por  PSD SOLUCIONES CORPORATIVAS y un grupo de emprendedores cucuteños   en el estado de DELAWERE EE.UU con sede principal en la ciudad de Cúcuta departamento de Norte de Santander  COLOMBIA'}
        ],
        footer:   false,
        media:    false
      },
      {
        attrs:    [
          {
            name: 'id',
            value: 'misión'
          }
        ],
        title:    'Misión',
        metas:     [
          {
            name : 'escrito por',
            value : 'Hender Orlando'
          }
        ],
        subtitle: 'Misión de Soluciones Cúcuta',
        content:   [
          {text: 'Poder brindar a las empresas,  instituciones y personas  de la región y las zonas fronterizas de nuestro país  un medio fácil  y rápido  de interacción virtual  en tiempo real  a nivel nacional e internacional, mediante el uso  de directorios digitales, campañas virtuales, redes sociales y la  comunicación web; logrando de esta manera la modernización empresarial  y estandarización de las nuevas tecnologías en nuestra región y las demás regiones fronterizas de nuestro país,  ampliando así   nuestra  comunicación comercial con el mundo.'}
        ],
        footer:   false,
        media:    false
      },
      {
        attrs:    [
          {
            name: 'id',
            value: 'visión'
          }
        ],
        title:    'Visión',
        metas:     [
          {
            name : 'escrito por',
            value : 'Hender Orlando'
          }
        ],
        subtitle: 'Visión de Soluciones Cúcuta',
        content:   [
          {text: 'Para el año 2016 ser la empresa pionera en el uso de las nuevas tecnologías en la ciudad de Cúcuta y su área metropolitana, así como también en el departamento de NORTE DE SANTANDER y SANTANDER y  todos los departamentos fronterizos de nuestro país incluyendo de esta manera: EL CESAR, LA GUAJIRA, ARAUCA, VICHADA, GUANIA, VAUPES, AMAZONAS, PUTUMAYO, Y NARIÑO'},
          {text: 'Para el año 2017 estar posicionados en todo el territorio nacional brindando interacción virtual  continua a nivel nacional e internacional con todas las empresas instituciones y personas  de nuestro país y los países vecinos. Así mismo plantar una sucursal en el país vecino de Panamá a fin de ampliar nuestra interacción comercial con el mundo.'}
        ],
        footer:   false,
        media:    false
      },
      {
        attrs:    [
          {
            name: 'id',
            value: 'condicionesRestricciones'
          }
        ],
        title:    'Condiciones y Restricciones',
        metas:     [
          {
            name : 'escrito por',
            value : 'Hender Orlando'
          }
        ],
        subtitle: false,
        content:   [
          {text: 'El contrato de uso de nuestros servicios se regirá por las normas y leyes comerciales  y mercantiles del país de Colombia, en caso de ser estas leyes inconclusas e  en cuanto al uso de  nuestros servicios se regirá por las disposiciones que se den en  la legislación comercial  del estado de DELAWERE EE.UU SOLUCIONES CUCUTA es una empresa de PSD SOLUCIONES CORPORAIVAS. Todos los derechos reservados.'}
        ],
        footer:   false,
        media:    false
      }
    ],
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
        src:      'camilocelu.jpg',
        href:     '/',
        nombre:   'CamiloCelu'
      }
    ],
    banners = [
      {
        srcBase:    'images/empresas/',
        src:        'banner_telecucuta.jpg',
        href:       '/',
        nombre:     'TeleCúcuta',
        descripcion:'Servicios TeleCúcuta'
      },
      {
        srcBase:    'images/empresas/',
        src:        'banner_alofamilia.jpg',
        href:       '/',
        nombre:     'Aló Familia',
        descripcion:'Servicios Aló Familia'
      },
      {
        srcBase:    'images/empresas/',
        src:        'banner_psdsoluciones.jpg',
        href:       '/',
        nombre:     'PSD Soluciones',
        descripcion:'Servicios PSD Soluciones'
      }
    ],
    brand = {
      src:    'images/logo.png',
      nombre: 'Soluciones Cúcuta',
      subnavs:  [
        {
          href:   '#inicio',
          src:    false,
          nombre: 'Inicio'
        },
        {
          href:   '#quienesSomos',
          src:    false,
          nombre: 'Quienes Somos'
        },
        {
          href:   '#misión',
          src:    false,
          nombre: 'Misión'
        },
        {
          href:   '#visión',
          src:    false,
          nombre: 'Visión'
        },
        {
          href:   '#condicionesRestricciones',
          src:    false,
          nombre: 'Condiciones y Restricciones'
        },
        {
          href:   '/Login/',
          src:    false,
          nombre: 'Entrar'
        }
      ]
    },
    navs = [];
    Etiqueta.find().populate('etiquetas').sort('id ASC').exec(function (err, found){
      while (found.length){
        var etiqueta = found.pop();
        if(etiqueta.etiquetas.length){
          var subnavs = [];
          if(etiqueta.etiquetas.length){
            for(var i in etiqueta.etiquetas){
              var subEtiqueta = etiqueta.etiquetas[i];
              if(typeof subEtiqueta != 'undefined' && typeof subEtiqueta.nombre != 'undefined' && typeof subEtiqueta.slug != 'undefined'){
                subnavs.push({
                  href:   '/Buscar/'+etiqueta.slug+'/'+subEtiqueta.slug+'/',
                  src:    false,
                  nombre: subEtiqueta.nombre
                });
              }
            }
          }
          navs.push({
            li:     true,
            href:   '/Buscar/'+etiqueta.slug+'/',
            src:    false,
            nombre: etiqueta.nombre,
            subnavs:  subnavs
          });
        }
      }
      res.view('homepage',{
        "articles":       articles,
        "figures":        figures,
        "banners":        banners,
        "footers":         navs,
        "navs":           navs,
        "brand":          brand
      });
      return;
    });
    /*return res.view('homepage',{
      articles:       articles,
      figures:        figures,
      banners:        banners,
      navs:           navs,
      brand:          brand
    });*/
    /*return res.json({
      todo: 'index() is not implemented yet!'
    });*/
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
          res.view('../assets/templates/partials/form_login.ejs',{
            message: {
              type: 'error',
              title: 'búsqueda incompleta',
              content: 'Error en la consulta, intenta mas tarde.'
            }
          });
        }
        if(usuario){
          bcrypt.compare(password, usuario.password, function(err, valid){
            if(err){
              res.view('../assets/templates/partials/form_login.ejs',{
                message: {
                  type: 'error',
                  title: 'clave inválida',
                  content: 'No se valido la clave, intenta mas tarde'
                }
              });
            }
            if(!valid){
              res.view('../assets/templates/partials/form_login.ejs',{
                message: {
                  type: 'error',
                  title: 'clave no valida',
                  content: 'La clave no es valida'
                }
              });
            }else{
              req.session.authenticated = true;
              req.session.usuario = usuario;
              res.view('../assets/templates/partials/form_login.ejs',{
                message: {
                  type: 'success',
                  title: 'bienvenido',
                  content: 'Hola, '+usuario.username
                }
              });
            }
          });
        }else{
          res.view('../assets/templates/partials/form_login.ejs',{
            message: {
              type: 'error',
              title: 'sin cuenta',
              content: 'El usuario "'+username+'" no fue encontrado'
            }
          });
        }
      });
      return;
    }
    return res.view('../assets/templates/partials/form_login.ejs',{
      message: {
        type: 'bienvenido',
        title: 'entra ingresando el nombre de usuario o email y la clave',
        content: ''
      }
    })
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
          return res.serverError(err);
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

