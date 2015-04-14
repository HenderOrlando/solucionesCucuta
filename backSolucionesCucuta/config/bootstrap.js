/**
 * Bootstrap
 * (sails.config.bootstrap)
 *
 * An asynchronous bootstrap function that runs before your Sails app gets lifted.
 * This gives you an opportunity to set up your data model, run jobs, or perform some special logic.
 *
 * For more information on bootstrapping your app, check out:
 * http://sailsjs.org/#/documentation/reference/sails.config/sails.config.bootstrap.html
 */

module.exports.bootstrap = function(next) {
  /*var usuarios = [
    {
      username: 'telecucuta',
      password: 'telecucuta',
      descripcion: 'Usuario TeleCúcuta.',
      estado: 1,
      rol: 1
    },
    {
      username: 'alófamilia',
      password: 'alófamilia',
      descripcion: 'Usuario Aló Familia.',
      estado: 1,
      rol: 1
    },
    {
      username: 'perfumeriaángela',
      password: 'perfumeriaángela',
      descripcion: 'Usuario Perfumeria Ángela.',
      estado: 1,
      rol: 1
    },
    {
      username: 'draudioojitos',
      password: 'draudioojitos',
      descripcion: 'Usuario Dr Audio Ojitos.',
      estado: 1,
      rol: 1
    },
    {
      username: 'lumatexlasmercedes',
      password: 'lumatexlasmercedes',
      descripcion: 'Usuario Lumatex las Mercedes.',
      estado: 1,
      rol: 1
    },
    {
      username: 'maurocel',
      password: 'maurocel',
      descripcion: 'Usuario Maurocel.',
      estado: 1,
      rol: 1
    },
    {
      username: 'maurys',
      password: 'maurys',
      descripcion: 'Usuario Maurys.',
      estado: 1,
      rol: 1
    },
    {
      username: 'odontologíapopular',
      password: 'odontologíapopular',
      descripcion: 'Usuario Odontología Popular.',
      estado: 1,
      rol: 1
    },
    {
      username: 'pulacell',
      password: 'pulacell',
      descripcion: 'Usuario PulaCell.',
      estado: 1,
      rol: 1
    },
    {
      username: 'calzadoorluis',
      password: 'calzadoorluis',
      descripcion: 'Usuario Calzado Orluis.',
      estado: 1,
      rol: 1
    },
    {
      username: 'solucionescúcuta',
      password: 'solucionescúcuta',
      descripcion: 'Usuario Soluciones Cúcuta.',
      estado: 1,
      rol: 1
    },
    {
      username: 'psdsoluciones',
      password: 'psdsoluciones',
      descripcion: 'Usuario PSD Soluciones.',
      estado: 1,
      rol: 1
    },
    {
      username: 'thelumierefilms',
      password: 'thelumierefilms',
      descripcion: 'Usuario The Lumiere Films.',
      estado: 1,
      rol: 1
    },
    {
      username: 'victormercado',
      password: 'victormercado',
      descripcion: 'Usuario Victor Mercado.',
      estado: 1,
      rol: 1
    },
    {
      username: 'camilocelu',
      password: 'camilocelu',
      descripcion: 'Usuario CamiloCelu.',
      estado: 1,
      rol: 1
    },
    {
      username: 'wzcomunicaciones',
      password: 'wzcomunicaciones',
      descripcion: 'Usuario WZ Comunicaciones.',
      estado: 1,
      rol: 1
    },
    {
      username: 'leidycel',
      password: 'leidycel',
      descripcion: 'Usuario LeidyCel.',
      estado: 1,
      rol: 1
    },
    {
      username: 'supermercadoonel',
      password: 'supermercadoonel',
      descripcion: 'Usuario Supermercado Onel.',
      estado: 1,
      rol: 1
    },
    {
      username: 'emilydiseños',
      password: 'emilydiseños',
      descripcion: 'Usuario Emily Diseños.',
      estado: 1,
      rol: 1
    },
    {
      username: 'creacionescheviton',
      password: 'creacionescheviton',
      descripcion: 'Usuario Creaciones Cheviton.',
      estado: 1,
      rol: 1
    },
    {
      username: 'bninmobiliaria',
      password: 'bninmobiliaria',
      descripcion: 'Usuario BN Inmobiliaria.',
      estado: 1,
      rol: 1
    },
    {
      username: 'cambioseltriunfo',
      password: 'cambioseltriunfo',
      descripcion: 'Usuario Cambios El Triunfo.',
      estado: 1,
      rol: 1
    },
    {
      username: 'creacionesdanayyelitza',
      password: 'creacionesdanayyelitza',
      descripcion: 'Usuario Creciones Dana y Yelitza.',
      estado: 1,
      rol: 1
    },
    {
      username: 'latiendadevale',
      password: 'latiendadevale',
      descripcion: 'Usuario La Tienda de Vale.',
      estado: 1,
      rol: 1
    },
    {
      username: 'misioncorporativa',
      password: 'misioncorporativa',
      descripcion: 'Usuario Misión Corporativa.',
      estado: 1,
      rol: 1
    },
    {
      username: 'viveresfaiber',
      password: 'viveresfaiber',
      descripcion: 'Usuario Vívere Faiber.',
      estado: 1,
      rol: 1
    },
    {
      username: 'sueñodebelleza',
      password: 'sueñodebelleza',
      descripcion: 'Usuario Sueño de belleza.',
      estado: 1,
      rol: 1
    },
    {
      username: 'serviciotecnico4g',
      password: 'serviciotecnico4g',
      descripcion: 'Usuario Servicio Técnico 4G.',
      estado: 1,
      rol: 1
    },
    {
      username: 'comunicacioneuriad',
      password: 'comunicacioneuriad',
      descripcion: 'Usuario Comunicaciones URIAD.',
      estado: 1,
      rol: 1
    },
    {
      username: 'sundertecnology',
      password: 'sundertecnology',
      descripcion: 'Usuario Sunder Tecnology.',
      estado: 1,
      rol: 1
    },
    {
      username: 'kmialarmas',
      password: 'kmialarmas',
      descripcion: 'Usuario KMI Alarmas.',
      estado: 1,
      rol: 1
    },
    {
      username: 'elcatiremoises',
      password: 'elcatiremoises',
      descripcion: 'Usuario El Catire Moises.',
      estado: 1,
      rol: 1
    },
    {
      username: 'variedadesjomaardila',
      password: 'variedadesjomaardila',
      descripcion: 'Usuario Variedades Joma Ardila.',
      estado: 1,
      rol: 1
    },
    {
      username: 'mantenimientomv',
      password: 'mantenimientomv',
      descripcion: 'Usuario Mantenimiento MV.',
      estado: 1,
      rol: 1
    }
  ];
  Usuario.create(usuarios).exec(function createCB(err,usuario){
    if(err){
      console.log(err);
      return;
    }
    console.log('Creado: '+usuario);
  });*/
/*
  // Carga los Estados Básicos

  var estados = [
    {
      nombre: 'Activo',
      descripcion: 'Estado activo.'
    },
    {
      nombre: 'Inactivo',
      descripcion: 'Estado Inactivo'
    }
  ];
  Estado
    .findOne(estados[0])
    .exec(function(err, estado){
      if(err){
        return;
      }
      if(!estado) {
        for (var i = 0; i <= estados.length; i++) {
          Estado.create(estados[i], function (err, created) {
            if(err){
              return;
            }
          });
        }
      }
    });

  // Carga los Tipos Básicos

  var tipos = [
    {
      nombre: 'Menú',
      dominio: 'etiqueta',
      descripcion: 'Etiqueta de un Menú.'
    },
    {
      nombre: 'Clave',
      dominio: 'etiqueta',
      descripcion: 'Etiqueta de Palabra Clave.'
    },
    {
      nombre: 'Articulo',
      dominio: 'publicacion',
      descripcion: 'Publicación de un Articulo.'
    },
    {
      nombre: 'Presentación',
      dominio: 'publicacion',
      descripcion: 'Publicación que presenta un Cliente.'
    },
    {
      nombre: 'Banner',
      dominio: 'archivo',
      descripcion: 'Archivo Imágen del Artículo para ubicar en la lista.'
    },
    {
      nombre: 'Banner Superior',
      dominio: 'archivo',
      descripcion: 'Archivo Imágen del Artículo para ubicar en la parte superior.'
    },
    {
      nombre: 'Banner Lateral',
      dominio: 'archivo',
      descripcion: 'Archivo Imágen del Artículo para ubicar en la parte lateral.'
    }
  ];

  Tipo
    .findOne(tipos[0])
    .exec(function(err, tipo){
      if(err){
        return;
      }
      if(!tipo) {
        for (var tmp = 0; tmp <= tipos.length; tmp++) {
          Tipo.create(tipos[tmp], function (err, created) {
            if(err){
              return;
            }
          });
        }
      }
    });

  // Carga los Roles Básicos

  var roles = [
    {
      nombre: 'Cliente',
      descripcion: 'Usuario que paga por el servicio.'
    },
    {
      nombre: 'Administrador',
      descripcion: 'Usuario que administra la aplicación.'
    }
  ];

  Rol
    .findOne(roles[0])
    .exec(function(err, rol){
      if(err){
        return;
      }
      if(!rol) {
        for (var i = 0; i <= roles.length; i++) {
          Rol.create(roles[i], function (err, created) {
            if(err){
              return;
            }
          });
        }
      }
    });

  // Carga los Usuarios Básicos

  var usuarios = [
    {
      username: 'Admin',
      password: 'superadmin',
      descripcion: 'Usuario administrador.'
    }
  ];
  Estado.findOne({slug: 'activo'}).exec(function(err, estadoActivo){
    if(err){
      return;
    }
    if(estadoActivo){
      // Busca el rol y crea el admin
      Rol.findOne({slug: 'administrador'}).exec(function(err, rolAdmin){
        if(err){
          return;
        }
        if(rolAdmin){
          Usuario
            .findOne(usuarios[0])
            .exec(function(err, usuario){
              if(err){
                return;
              }
              if(!usuario) {
                for (var i = 0; i <= usuarios.length; i++) {
                  usuario = usuarios[i];
                  if(usuarios[i]){
                    Usuario.create(usuario, function (err, created) {
                      if(err){
                        return;
                      }
                      if(created){
                        Usuario.update({id: created.id}, {rol: rolAdmin.id, estado: estadoActivo.id}, function(err, updated){
                          if(err){
                            return;
                          }
                        });
                      }
                    });
                  }
                }
              }
            });
        }
      });

      // Agrega las Etiquetas
      var etiquetas = [
        {
          nombre: 'Empresas',
          descripcion: 'Etiqueta de Empresas',
          etiquetas: [
            {
              nombre: 'Hogar',
              descripcion: 'Etiqueta de Empresas para el Hogar'
            },
            {
              nombre: 'Oficina',
              descripcion: 'Etiqueta de Empresas para la Oficina'
            },
            {
              nombre: 'Insumos',
              descripcion: 'Etiqueta de Empresas para Insumos'
            },
            {
              nombre: 'Autos y Vehículos',
              descripcion: 'Etiqueta de Empresas para Autos y Vehículos'
            },
            {
              nombre: 'Transporte',
              descripcion: 'Etiqueta de Empresas para el Transporte'
            },
            {
              nombre: 'Recreación y Deporte',
              descripcion: 'Etiqueta de Empresas para la Recreación y el Deporte'
            },
            {
              nombre: 'Turismo',
              descripcion: 'Etiqueta de Empresas para el Turismo'
            },
            {
              nombre: 'Restaurantes',
              descripcion: 'Etiqueta de Empresas para los Restaurantes'
            },
            {
              nombre: 'Estética',
              descripcion: 'Etiqueta de Empresas para la Estética'
            },
            {
              nombre: 'Salud',
              descripcion: 'Etiqueta de Empresas para la Salud'
            },
            {
              nombre: 'Belleza',
              descripcion: 'Etiqueta de Empresas para la Belleza'
            },
            {
              nombre: 'Servicios Empresariales',
              descripcion: 'Etiqueta de Empresas para Servicios Empresariales'
            },
            {
              nombre: 'Educación',
              descripcion: 'Etiqueta de Empresas para la Educación'
            },
            {
              nombre: 'Instituciones Públicas',
              descripcion: 'Etiqueta de Empresas para Instituciones Públicas'
            },
            {
              nombre: 'Moda',
              descripcion: 'Etiqueta de Empresas para la Moda'
            },
            {
              nombre: 'Industria',
              descripcion: 'Etiqueta de Empresas para la Industria'
            },
            {
              nombre: 'Agro',
              descripcion: 'Etiqueta de Empresas para el Agro'
            },
            {
              nombre: 'Ingeniería',
              descripcion: 'Etiqueta de Empresas para la Ingeniería'
            },
            {
              nombre: 'Diseño',
              descripcion: 'Etiqueta de Empresas para el Diseño'
            },
            {
              nombre: 'Reparaciones',
              descripcion: 'Etiqueta de Empresas para las Reparaciones'
            },
            {
              nombre: 'Comunicaciones',
              descripcion: 'Etiqueta de Empresas para las Comunicaciones'
            },
            {
              nombre: 'Arte',
              descripcion: 'Etiqueta de Empresas para el Arte'
            }
          ]
        },
        {
          nombre: 'Campañas',
          descripcion: 'Etiqueta de Campañas',
          etiquetas: [
            {
              nombre: 'Publicitarias',
              descripcion: 'Etiqueta de Campañas Pubicitarias'
            },
            {
              nombre: 'Sociales',
              descripcion: 'Etiqueta de Campañas Sociales'
            },
            {
              nombre: 'Políticas',
              descripcion: 'Etiqueta de Campañas Políticas'
            },
            {
              nombre: 'Públicas',
              descripcion: 'Etiqueta de Campañas Públicas'
            },
            {
              nombre: 'Tu Campaña',
              descripcion: 'Etiqueta de Campañas Propias'
            }
          ]
        },
        {
          nombre: 'Desarrollo y Diseño',
          descripcion: 'Etiqueta de Desarrollo y Diseño',
          etiquetas: [
            {
              nombre: 'Sitios Web',
              descripcion: 'Etiqueta de Desarrollo y Diseño de Sitios Web'
            },
            {
              nombre: 'Aplicaciones Web',
              descripcion: 'Etiqueta de Desarrollo y Diseño de Aplicaciones Web'
            },
            {
              nombre: 'Social Media',
              descripcion: 'Etiqueta de Desarrollo y Diseño de Social Media'
            },
            {
              nombre: 'Gráfico',
              descripcion: 'Etiqueta de Desarrollo y Diseño Gráfico'
            }
          ]
        },
        {
          nombre: 'Servicios Inteligentes',
          descripcion: 'Etiqueta de Servicios Inteligentes',
          etiquetas: [
            {
              nombre: 'Consultoría de Diseño',
              descripcion: 'Etiqueta de Servicios Inteligentes para Consultoría de Diseño'
            },
            {
              nombre: 'Consultoría de Desarrollo',
              descripcion: 'Etiqueta de Servicios Inteligentes para Consultoría para el diseño, construcción y desarrollo de Software'
            },
            {
              nombre: 'Consultoría de Aplicaciones',
              descripcion: 'Etiqueta de Servicios Inteligentes para Consultoría para la selección e implementación de Aplicaciones'
            }
          ]
        }
      ];

      Tipo.findOne({slug: 'menu',dominio: 'etiqueta'}).exec(function(err, tipo){
        if(err){
          return;
        }
        if(tipo){
          for (var i = 0; i <= etiquetas.length; i++) {
            var padre = etiquetas[i];
            if(padre && padre.etiquetas){
              var hijos = padre.etiquetas
              //padre.etiquetas = undefined;
              padre.tipo = tipo.id;
              padre.estado = estadoActivo.id;
              Etiqueta.create(padre, function (err, createdPadre) {
                if(err){
                  return;
                }
                if(createdPadre){
                }
              });
            }
          }
        }
      })
    }
  });*/
  // It's very important to trigger this callback method when you are finished
  // with the bootstrap!  (otherwise your server will never lift, since it's waiting on the bootstrap)
  next();
};
