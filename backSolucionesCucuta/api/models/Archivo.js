/**
* Archivo.js
*
* @description :: TODO: You might write a short summary of how this model works and what it represents here.
* @docs        :: http://sailsjs.org/#!documentation/models
*/

module.exports = {
<<<<<<< HEAD
  autoPk: true,
  autoUpdateAt: true,
  autoCreateAt: true,
  attributes: {
    id: {
      type: 'string',
      primaryKey: true,
      unique: true
    },
    slug: {
      type: 'string',
      unique: true
    },
=======
  autoUpdatAt: true,
  autoCreateAt: true,
  attributes: {
>>>>>>> origin/master
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
    url: {
      type: 'string',
      required: true
    },
    usuarios: {
      collection: 'Usuario',
      via: 'archivos'
    },
    tipo: {
      model: 'Tipo'
    },
<<<<<<< HEAD
    rol: {
      model: 'rol'
    },
    url: {
      type: 'string',
      required: true
    },
    rol: {
      model: 'rol'
    },
    tipo: {
      model: 'tipo'
    },
    estado: {
      model: 'estado'
    },
    etiquetas: {
      collection: 'etiqueta',
      via: 'archivos'
    },
    usuarios: {
      collection: 'usuario',
      via: 'archivos'
=======
    estado: {
      model: 'Estado'
    },
    etiquetas: {
      collection: 'Etiqueta',
      via: 'archivos'
    },
    publicaciones: {
      collection: 'Publicacion',
      via: 'archivos'
    }
  },

  // Lifecycle Callbacks
  beforeCreate: function (values, next) {
    if(!values.nombre){
      return next({err: ["Debe existir un nombre!"]});
>>>>>>> origin/master
    }
    values.slug = this.capitalizeSlug(values.nombre);

    next();
  }
};

