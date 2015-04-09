/**
* Etiqueta.js
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
    usuarios: {
      collection: 'Usuario',
      via: 'etiquetas',
      dominant: true
    },
    publicaciones: {
      collection: 'Publicacion',
      via: 'etiquetas',
      dominant: true
    },
    archivos: {
      collection: 'Archivo',
      via: 'etiquetas',
      dominant: true
    },
    etiquetas: {
      collection: 'Etiqueta',
      via: 'etiquetaPadre'
    },
    etiquetaPadre: {
      model: 'Etiqueta'
    },
    tipo: {
      model: 'Tipo'
    },
    estado: {
      model: 'Estado'
    }
  },

  // Lifecycle Callbacks
  beforeCreate: function (values, next) {
    if(!values.nombre){
      return next({err: ["Debe existir un nombre!"]});
    }
    values.slug = this.capitalizeSlug(values.nombre);

    next();
  }
};

