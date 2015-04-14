/**
 * PublicacionController
 *
 * @description :: Server-side logic for managing publicacions
 * @help        :: See http://links.sailsjs.org/docs/controllers
 */

module.exports = {

  /**
   * `MainController.count()`
   */
  count: function (req, res, next) {
    count = 0;
    Publicacion.count().exec(function(err, found){
      if(err){
        next(err);
      }
      if(found){
        count = found
      }
      res.json({
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
  }
};

