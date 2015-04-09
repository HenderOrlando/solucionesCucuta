/**
* Tipo.js
*
* @description :: TODO: You might write a short summary of how this model works and what it represents here.
* @docs        :: http://sailsjs.org/#!documentation/models
*/

module.exports = {
  autoUpdatAt: true,
  autoCreateAt: true,
  attributes: {
    nombre: {
      type: 'string',
      unique: true,
      required: true
    },
    slug: {
      type: 'string'
    },
    dominio: {
      type: 'string',
      size: 40
    },
    descripcion:{
      type: 'string',
      required: true
    },
    createAt: {
      type: 'datetime',
      defaultsTo: function (){ return new Date(); }
    },
    updateAt: {
      type: 'datetime',
      defaultsTo: function (){ return new Date(); }
    },
    publicaciones: {
      collection: 'Publicacion',
      via: 'tipo'
    },
    archivos: {
      collection: 'Archivo',
      via: 'tipo'
    },
    etiquetas: {
      collection: 'Etiqueta',
      via: 'tipo'
    }
  },

  // Lifecycle Callbacks
  beforeCreate: function (values, next) {
    if(!values.nombre){
      return next({err: ["Debe existir un nombre!"]});
    }
    if(!values.dominio){
      return next({err: ["Debe existir en un dominio!"]});
    }
    values.slug = this.capitalizeSlug(values.nombre);
    values.dominio = this.capitalizeSlug(values.dominio);

    next();
  }
};

