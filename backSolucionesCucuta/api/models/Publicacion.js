/**
* Publicacion.js
*
* @description :: TODO: You might write a short summary of how this model works and what it represents here.
* @docs        :: http://sailsjs.org/#!documentation/models
*/

module.exports = {
  autoUpdatAt: true,
  autoCreateAt: true,
  attributes: {
    titulo: {
      type: 'string',
      unique: true,
      required: true
    },
    subtitulo: {
      type: 'string',
      unique: true,
      required: true
    },
    slug: {
      type: 'string'
    },
    contenido:{
      type: 'text',
      required: true
    },
    usuarios: {
      collection: 'Usuario',
      via: 'publicaciones'
    },
    tipo: {
      model: 'Tipo'
    },
    estado: {
      model: 'Estado'
    },
    archivos: {
      collection: 'Archivo',
      via: 'publicaciones'
    },
    etiquetas: {
      collection: 'Etiqueta',
      via: 'publicaciones'
    }
  },

  // Lifecycle Callbacks
  beforeCreate: function (values, next) {
    this.cleanInputs(values);
    if(!values.titulo){
      return next({err: ["Debe existir un titulo!"]});
    }
    values.slug = this.capitalizeSlug(values.titulo);

    if(!values.tipo){
      Tipo.findOne({slug: 'presentacion', dominio: 'Publicacion'}, function(err, found){
        if(err){
          return next(err);
        }
        if(found){
          values.tipo = found.id;
        }else{
          return next(err);
        }
      });
    }
    if(!values.estado){
      Estado.findOne({slug: 'inactivo'}, function(err, found){
        if(err){
          return next(err);
        }
        if(found){
          values.estado = found.id;
        }else{
          return next(err);
        }
      });
    }

    next();
  }
};

