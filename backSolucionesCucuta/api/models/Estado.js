/**
* Estado.js
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
      type: 'text',
      required: true
    },
    usuarios: {
      collection: 'Usuario',
      via: 'estado'
    },
    publicaciones: {
      collection: 'Publicacion',
      via: 'estado'
    },
    archivos: {
      collection: 'Archivo',
      via: 'estado'
    },
    etiquetas: {
      collection: 'Etiqueta',
      via: 'estado'
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

