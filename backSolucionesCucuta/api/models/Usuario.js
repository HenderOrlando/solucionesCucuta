/**
* Usuario.js
*
* @description :: TODO: You might write a short summary of how this model works and what it represents here.
* @docs        :: http://sailsjs.org/#!documentation/models
*/

module.exports = {
  autoPk: true,
  autoUpdatAt: true,
  autoCreateAt: true,
  attributes: {
    id: {
      type: 'string',
      primaryKey: true,
      unique: true
    },
    username: {
      type: 'string',
      unique: true,
      required: true,
      size: 40
    },
    password:{
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
      via: 'usuarios',
      dominant: true
    },
    archivos: {
      collection: 'Archivo',
      via: 'usuarios',
      dominant: true
    },
    tipo: {
      model: 'Tipo'
    },
    estado: {
      model: 'Estado'
    },
    roles: {
      collection: 'Rol',
      via: 'usuarios'
    },
    etiquetas: {
      collection: 'Etiqueta',
      via: 'usuarios'
    }
  }
};

