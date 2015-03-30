/**
* Tipo.js
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
      via: 'tipo'
    },
    publicaciones: {
      collection: 'publicacion',
      via: 'tipo'
    },
    archivos: {
      collection: 'archivo',
      via: 'tipo'
    }
  }
};

