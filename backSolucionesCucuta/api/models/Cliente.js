/**
* Cliente.js
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
    usuario: {
      model: 'usuario'
    },
    createAt: {
      type: 'datetime',
      defaultsTo: function (){ return new Date(); }
    },
    updateAt: {
      type: 'datetime',
      defaultsTo: function (){ return new Date(); }
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
      via: 'usuarios'
    }
  }
};

