/**
* Etiqueta.js
*
* @description :: TODO: You might write a short summary of how this model works and what it represents here.
* @docs        :: http://sailsjs.org/#!documentation/models
*/

module.exports = {
<<<<<<< HEAD
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
    icon: {
      type: 'string'
    },
    descripcion:{
      type: 'text',
      required: true
    },
    usuarios: {
      collection: 'Usuario',
      via: 'etiquetas',
      dominant: true
    },
    publicaciones: {
<<<<<<< HEAD
      collection: 'publicacion',
=======
      collection: 'Publicacion',
>>>>>>> origin/master
      via: 'etiquetas',
      dominant: true
    },
    archivos: {
<<<<<<< HEAD
      collection: 'archivo',
      via: 'etiquetas',
      dominant: true
    },
    usuarios: {
      collection: 'usuario',
      via: 'etiquetas',
      dominant: true
=======
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
>>>>>>> origin/master
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

