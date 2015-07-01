/**
 * UsuarioController
 *
 * @description :: Server-side logic for managing usuarios
 * @help        :: See http://links.sailsjs.org/docs/controllers
 */

module.exports = {

  /**
   * `MainController.count()`
   */
  count: function (req, res, next) {
    count = 0;
    Usuario.count().exec(function(err, found){
      if(err){
        return res.negotiate(err);
      }
      if(found){
        count = found
      }
      return res.json({
        count: count
      });
    });
  },

  /**
   * `MainController.agregar()`
   */
  agregar: function (req, res, next) {
    return res.json({
      todo: 'agregar() is not implemented yet!'
    });
  },

  /**
   * `MainController.borrar()`
   */
  borrar: function (req, res, next) {
    return res.json({
      todo: 'borrar() is not implemented yet!'
    });
  },

  /**
   * `MainController.listar()`
   */
  listar: function (req, res, next) {
    return res.json({
      todo: 'listar() is not implemented yet!'
    });
  },

  /**
   * `MainController.listar()`
   */
  perfil: function (req, res, next) {
    res.view('usuario/perfil');
    return;
  }


};

