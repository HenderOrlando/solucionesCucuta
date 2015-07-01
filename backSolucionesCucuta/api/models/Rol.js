/**
* Rol.js
*
* @description :: TODO: You might write a short summary of how this model works and what it represents here.
* @docs        :: http://sailsjs.org/#!documentation/models
*/

module.exports = {
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
    nombre: {
      type: 'string',
      unique: true,
      required: true
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
      collection: 'usuario',
      via: 'rol'
    },
    publicaciones: {
      collection: 'publicacion',
      via: 'rol'
    },
    archivos: {
      collection: 'archivo',
      via: 'rol'
    }
  }
};

